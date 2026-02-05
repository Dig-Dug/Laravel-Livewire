<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Post;
use Illuminate\Support\Facades\Http;

class PostsList extends Component
{
    //public $posts = [];
    public $search = '';

    public $title = '';
    public $body = '';

    public $status = 'draft';
    public $editStatus = '';



    public $editingPostId = null;
    public $editTitle = '';
    public $editBody = '';


    public $apiStatus = null;
    public $apiMessage = '';
    public $externalPostsCount = 0;

    protected $listeners = [
        //'postAdded'=> 'loadPosts',
        'postAdded'=> '$refresh',
    ];

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
        ]);

        Post::create([
            'title' => $this->title,
            'body'  => $this->body,
            'status'=> $this->status,
        ]);

        // reset inputs
        $this->title = '';
        $this->body = '';
        $this->status = 'draft';

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
        ->get();
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
}
