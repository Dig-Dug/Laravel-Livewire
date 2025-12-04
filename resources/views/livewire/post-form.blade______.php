<div>
    <h2>Create Post</h2>

    <form wire:submit.prevent="createPost">
        <input 
            type="text" 
            wire:model="title" 
            placeholder="Title"
            style="display:block; margin-bottom:10px;"
        >

        <textarea 
            wire:model="body" 
            placeholder="Body"
            style="display:block; margin-bottom:10px;"
        ></textarea>

        <button type="submit">Save Post</button>
    </form>
</div>
