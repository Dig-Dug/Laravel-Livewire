<form action="{{ route('posts.destroy', $post) }}" method="POST" onsubmit="return confirm('Delete this post?');">
    @csrf
    @method('DELETE')
    <button class="text-red-600">Delete</button>
</form>

<h1>Posts</h1>
@foreach ($posts as $post )
    <p>{{$post->title}}</p>
@endforeach
