<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Post;
use Illuminate\Support\Facades\Http;

use Livewire\WithFileUploads;
use Livewire\WithPagination;

class PostsList extends Component
{
    use WithFileUploads;
    use WithPagination;
    //public $posts = [];
    public $search = '';

    public $title = '';
    public $body = '';

    public $status = 'draft';
    public $editStatus = '';
    public $imageUrl = null;
    public $uploadedImage;



    public $editingPostId = null;
    public $editTitle = '';
    public $editBody = '';


    public $apiStatus = null;
    public $apiMessage = '';
    public $externalPostsCount = 0;

    public $darkMode = false;

    protected $listeners = [
        //'postAdded'=> 'loadPosts',
        'postAdded'=> '$refresh',
    ];
    protected $paginationTheme = 'tailwind';

    public function mount()
    {
       //$this->loadPosts();
    }

   /*  public function updatedSearch()
    {
        $this->loadPosts(); // refresh on search
    }
 */
   /*  public function loadPosts()
    {
        $this->posts = Post::where('title', 'like', '%' . $this->search . '%')
            ->latest()
            ->get();
    } */

    public function createPost()
    {
        $this->validate([
            'title' => 'required|min:3',
            'body'  => 'required|min:5',
            'uploadedImage'=>'nullable|image|max:2048',
        ]);
        $imagePath = null;
        if($this->uploadedImage){
            $imagePath = $this->uploadedImage->store('posts','public');
        }
        Post::create([
            'title' => $this->title,
            'body'  => $this->body,
            'status'=> $this->status,
            'image_url'=> $imagePath ?? $this->imageUrl,
        ]);

        // reset inputs
        $this->title = '';
        $this->body = '';
        $this->status = 'draft';
        $this->uploadedImage = null;
        $this->imageUrl = null;

      //  $this->loadPosts(); // refresh list
    }

    public function deletePost($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

      //  $this->loadPosts(); // refresh list
    }

    public function editPost($id)
    {
        $post = Post::findOrFail($id);

        $this->editingPostId = $id;
        $this->editTitle = $post->title;
        $this->editBody = $post->body;
       $this->editStatus = $post->status;
    }

    public function updatePost()
    {
        $post = Post::findOrFail($this->editingPostId);
        $post->title = $this->editTitle;
        $post->body = $this->editBody;
        $post->status = $this->editStatus;
        $post->save();

        // cleanup
        $this->editingPostId = null;
        $this->editTitle = '';
        $this->editBody = '';
        $this->editStatus = '';

      //  $this->loadPosts(); // refresh
      
    }

    public function getPostsProperty(){
        return Post::where('title', 'like', '%' . $this->search . '%')
        ->latest()
        //->get();
        ->paginate(3);
    }
public function updatingSearch(){
    $this->resetPage();
}

public function fetchExternalPosts(){
    $response = Http::get('http://jsonplaceholder.typicode.com/posts',
    ['_limit' => 2,]
    );
    $this->apiStatus = $response->status();
    
    
    if(!$response->successful()){
        $this->apiMessage = 'Failure :(';
        return;
    }
    $externalPosts = $response->json();
    $saved = 0;
    foreach($externalPosts as $externalPost){
        $exists = Post::where('title', $externalPost['title'])->exists();
        if(!$exists){
            Post::create([
                'title' => $externalPost ['title'],
                'body' => $externalPost ['body'],
                'status' => 'external',
            ]);
            $saved++;
        }
    }
    $this-> externalPostsCount = count($externalPosts);
    $this-> apiMessage = "Imported {$saved}";
}

public function deleteExternalPosts()
{
    Post::where('status', 'external')->delete();
}

    public function render()
    {
        return view('livewire.posts-list');
    }


    public function attachRandomImage(){
 $this->imageUrl = "http://picsum.photos/600/400?random=". rand(1,1000);   
}

public function getStatusBadgeClassAttribute()
{
    switch ($this->status) {
        case 'draft':
            return 'bg-yellow-100 text-yellow-800';
        case 'published':
            return 'bg-green-100 text-green-800';
        case 'external':
            return 'bg-blue-100 text-blue-800';
        default:
            return 'bg-gray-100 text-gray-800';
    }
}

public function likePost($postId)
{
    $post = Post::findOrFail($postId);
    $post->increment('likes');
}

public function toggleDarkMode(){
    $this->darkMode = !$this->darkMode;
}

}


