<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Post;

class PostsList extends Component
{
    public function render()
    {
        return view('livewire.posts-list',
    ['post'=> Post::latest()->get(),]);
    }
}
