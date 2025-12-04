<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Post;

class PostForm extends Component
{
    public $title = '';
    public $body = '';

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

        $this->reset(['title', 'body']);

        $this->emit('postAdded');
    }

    public function render()
    {
        return view('livewire.post-form');
    }
}
