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

    public function mount()
    {
        $this->loadPosts();
    }

    public function updatedSearch()
    {
        $this->loadPosts(); // refresh on search
    }

    public function loadPosts()
    {
        $this->posts = Post::where('title', 'like', '%' . $this->search . '%')
            ->latest()
            ->get();
    }

    public function createPost()
    {
        $this->validate([
            'title' => 'required|min:3',
            'body'  => 'required|min:5',
        ]);

        Post::create([
            'title' => $this->title,
            'body'  => $this->body,
        ]);

        // reset inputs
        $this->title = '';
        $this->body = '';

        $this->loadPosts(); // refresh list
    }

    public function deletePost($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        $this->loadPosts(); // refresh list
    }

    public function editPost($id)
    {
        $post = Post::findOrFail($id);

        $this->editingPostId = $id;
        $this->editTitle = $post->title;
    }

    public function updatePost()
    {
        $post = Post::findOrFail($this->editingPostId);
        $post->title = $this->editTitle;
        $post->save();

        // cleanup
        $this->editingPostId = null;
        $this->editTitle = '';

        $this->loadPosts(); // refresh
    }

    public function render()
    {
        return view('livewire.posts-list');
    }
}
