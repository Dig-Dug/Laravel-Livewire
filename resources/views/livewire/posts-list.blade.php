<div>
    <h1>Posts</h1>

    <input 
        type="text" 
        placeholder="Search posts..." 
        wire:model="search"
        style="padding: 5px; margin-bottom: 20px; width: 100%;"
    />
<h2>Create a New uuuPost</h2>

<input 
    type="text" 
    placeholder="Title"
    wire:model="title"
    style="display:block; margin-bottom:10px;"
>

<textarea
    placeholder="Body"
    wire:model="body"
    style="display:block; margin-bottom:10px; width:100%; height:100px;"
></textarea>

<button
    wire:click="createPost"
    style="background:black; color:white; padding:8px 12px;"
>
    Save Post
</button>

@error('title') <p style="color:red;">{{ $message }}</p> @enderror
@error('body') <p style="color:red;">{{ $message }}</p> @enderror

<hr style="margin:25px 0;">

    @foreach ($posts as $post)
        <div style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px;">
            <h3>{{ $post->title }}</h3>
            <p>{{ $post->body }}</p>


            @if ($editingPostId === $post->id)
            <h4>Edit Post</h4>
            <input
            type="text"
            wire:model="editTitle"
            style="display:block; margin-bottom:10px;"
            />
            <textarea
            wire:model="editBody"
            style="display:block; margin-bottom:10px; width:100%;height:100%;"
            ></textarea>
            <button
            wire:click="updatePost"
            style="background:green; color:white; padding: 6px 12px;"
            >Save Changes</button>
            <button
            wire:click="$set('editingPostId', null)"
            style="background:grey; color:white; padding: 6px 12px; margin-left:10px;"
            >Cancel</button>
        @endif
        </div>

        <button
        wire:click="editPost({{ $post->id }})"
        style="margin-top:10px; background:grey; color:white; padding:5px 10px;"
        >Edit</button>
        <button
        wire:click="deletePost({{ $post->id }})"
        style="background:red; color:white; padding:4px 8px; margin-left:10px;"
        >Delete</button>
    @endforeach
</div>
