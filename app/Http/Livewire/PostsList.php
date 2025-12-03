<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Post;

class PostsList extends Component
{
    public $posts = [];
    public $search = '';
    public $title = '';
    public $body = '';
public $editingPostId = null;
public $editTitle = '';
public $editBody = '';
    public function mount(){
$this->posts = Post::latest()->get();
    }
public function createPost(){
    $this->validate([
        'title'=> 'required|min:3',
        'body'=> 'required|min:5',
    ]);
    Post::create([
        'title'=> $this->title,
        'body'=> $this->body,
    ]);
    $this->title = '';
    $this->body = '';
}

public function deletePost($id){
    Post::findOrFail($id)->delete();
    $this->loadPosts();
}

public function startEditing($postId){
    $this->editingPostId = $postId;
    $post = Post::findOrFail($postId);
    $this->editTitle = $post->title;
    $this->editBody = $post->body;
}


public function render()
{
    $posts = Post::where('title', 'like', '%' . $this->search . '%')->get();

    return view('livewire.posts-list', compact('posts'));
}
}
