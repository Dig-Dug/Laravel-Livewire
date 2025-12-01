<div>
    {{-- Stop trying to control. --}}

    <h1  class="text-xl font-bold mb-3">Posts</h1>  

<ul>
    @foreach ($post as $post )
        <li class="mb-2 border-b pb-2">
<strong>{{ $post-> title}}</strong>
<p class="text-sm text-gray-600">{{$post-> excerpt(80)}}</p>
        </li>
    @endforeach

</ul>



</div>
