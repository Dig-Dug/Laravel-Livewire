<div class="{{ $darkMode ? 'dark bg-gray-900 min-h-screen' : 'bg-gray-100 min-h-screen' }}">
    <div class="p-6 text-gray-900 dark:text-gray-100">
<div class="flex justify-end mb-4">
    <button
        wire:click="toggleDarkMode"
        class="px-4 py-2 rounded bg-gray-800 text-white dark:bg-gray-200 dark:text-black transition"
    >
        {{ $darkMode ? '☀️ Light Mode' : '🌙 Dark Mode' }}
    </button>
</div>
<div class="max-w-4xl mx-auto p-6 space-y-6">

    <h1 class="text-4xl font-bold text-blue-600 mb-4">Posts</h1>

    {{-- API Buttons --}}
    <div class="flex gap-3 mb-4">
        <button
            wire:click="fetchExternalPosts"
            class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800 transition"
        >
            Fetch External Posts
        </button>

        <button
            wire:click="deleteExternalPosts"
            class="bg-black text-white px-4 py-2 rounded hover:bg-gray-800 transition"
        >
            Delete External Posts
        </button>

        
    </div>

    {{-- API Status --}}
    @if($apiStatus)
        <div class="bg-gray-100 dark:bg-gray-800 border-l-4 border-blue-500 p-4 rounded shadow">

            <p class="text-gray-700 dark:text-gray-300"><strong>Status:</strong> {{ $apiStatus }}</p>
            <p class="text-gray-700 dark:text-gray-300"><strong>Message:</strong> {{ $apiMessage }}</p>
            <p class="text-gray-700 dark:text-gray-300"><strong>Posts fetched:</strong> {{ $externalPostsCount }}</p>
        </div>
    @endif

    @if($imageUrl)
    <div class="mt-3">
        <img 
        src="{{ $imageUrl }}"
        class="w-full h-48 object-cover rounded shadow"
        >
    </div>
    @endif


    <div class="mb-4 flex gap-4 items-center">
            <select wire:model="filter"
            class="p-2 border rounded shadow-sm dark:bg-gray-800 dark:text-gray-100">
        <option value="all">All Posts</option>
        <option value="draft">Draft</option>
        <option value="published">Published</option>
        <option value="external">External</option>
         <option value="most_liked">Most Liked ❤️</option>
        <option value="latest">Latest 🆕</option>
        
        </select>
        
    </div>

    {{-- Search --}}
    <input 
        type="text"
        placeholder="Search posts..."
        wire:model="search"
        class="w-full p-3 border border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-200 focus:outline-none"
    />

    {{-- Create New Post --}}
    <div class="bg-white dark:bg-gray-800 p-4 rounded shadow hover:shadow-lg transition relative">

        <h2 class="text-2xl font-semibold text-gray-700">Create a New Post</h2>

        <input 
            type="text"
            placeholder="Title"
            wire:model="title"
            class="w-full p-3 border border-gray-300 rounded focus:ring focus:ring-blue-200 focus:outline-none"
        >

        <textarea
            placeholder="Body"
            wire:model="body"
            class="w-full p-3 border border-gray-300 rounded h-32 focus:ring focus:ring-blue-200 focus:outline-none"
        ></textarea>
        <div class="mt-2">
    <label class="block text-gray-700 font-semibold mb-1">Upload Image</label>
    <input type="file" wire:model="uploadedImage" class="border p-2 rounded w-full">
    @error('uploadedImage') <span class="text-red-600">{{ $message }}</span> @enderror
</div>
<button
        type="button" wire:click="attachRandomImage"
        class= "bg-yellow-600 px-4 py-2 rounded hover:bg-purple-700 transition"
        >Attach Random Image</button>
        <button
            wire:click="createPost"
            class="bg-green-600 text-black px-4 py-2 rounded hover:bg-green-700 transition"
        >
            Save Post
        </button>

        @error('title') <p class="text-red-600">{{ $message }}</p> @enderror
        @error('body') <p class="text-red-600">{{ $message }}</p> @enderror
    </div>

    {{-- Posts List --}}
    <div class="space-y-4 mt-6">
        @foreach($this->posts as $post)
           <div class="bg-white dark:bg-gray-800 p-4 rounded shadow hover:shadow-lg transition relative">

                <h3 class="text-xl font-bold text-gray-800 dark:text-gray-100">{{ $post->title }}</h3>
                
            @if($post->image_url)
    <img 
        src="{{ Str::startsWith($post->image_url, 'http') 
                ? $post->image_url 
                : asset('storage/' . $post->image_url) }}"
        class="w-full h-48 object-cover rounded my-3"
    >
@endif

    @if ($uploadedImage)
    <img src="{{ $uploadedImage->temporaryUrl() }}" class="w-full h-48 object-cover rounded mt-2">
@endif
                
                
               <p class="text-gray-700 dark:text-gray-300">{{ $post->body }}</p>

                <!--<p class="text-sm text-gray-500 mt-1">Status: <span class="font-semibold">{{ $post->status }}</span>
                </p>-->

                <div class="text-sm text-gray-500 dark:text-gray-400 mt-2 flex gap-4">
    <span>📊 {{ $post->word_count }} words</span>
    <span>⏱ {{ $post->reading_time }} min read</span>
</div>
<div class="mt-2">
    <span
        class="px-3 py-1 text-xs font-semibold rounded-full
        @if($post->status === 'draft')
            bg-yellow-100 text-yellow-800
        @elseif($post->status === 'published')
            bg-green-100 text-green-800
        @elseif($post->status === 'external')
            bg-blue-100 text-blue-800
        @else
            bg-gray-100 text-gray-800
        @endif
        "
    >
        {{ ucfirst($post->status) }}
    </span>
</div>


<div class="mt-3 flex items-center gap-3">
    <button wire:click="likePost({{ $post->id }})"
        class="px-3 py-1 bg-pink-100 text-pink-700 rounded hover:bg-pink-200 transition">
        ❤️ Like
    </button>
    <span class="text-sm text-gray-600">
{{ $post->likes }} likes
    </span>
</div>


                {{-- Edit Section --}}
                @if ($editingPostId === $post->id)
                    <div class="mt-4 space-y-2">
                        <input
                            type="text"
                            wire:model="editTitle"
                            class="w-full p-2 border border-gray-300 rounded focus:ring focus:ring-blue-200 focus:outline-none"
                        >
                        <textarea
                            wire:model="editBody"
                            class="w-full p-2 border border-gray-300 rounded h-24 focus:ring focus:ring-blue-200 focus:outline-none"
                        ></textarea>

                        <select wire:model="editStatus" class="border border-gray-300 rounded p-2 focus:ring focus:ring-blue-200">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>

                        <div class="flex gap-2 mt-2">
                            <button
                                wire:click="updatePost"
                                class="bg-green-600 text-black px-3 py-1 rounded hover:bg-green-700 transition"
                            >
                                Save Changes
                            </button>
                            <button
                                wire:click="$set('editingPostId', null)"
                                class="bg-gray-400 text-black px-3 py-1 rounded hover:bg-gray-500 transition"
                            >
                                Cancel
                            </button>
                        </div>
                    </div>
                @endif


 
                {{-- Post Actions --}}
                <div class="absolute top-4 right-4 flex gap-2">
                    <button
                        wire:click="editPost({{ $post->id }})"
                        class="bg-gray-600 text-black px-3 py-1 rounded hover:bg-gray-700 transition"
                    >
                        Edit
                    </button>
                    <button
                        wire:click="deletePost({{ $post->id }})"
                        class="bg-red-600 text-black px-3 py-1 rounded hover:bg-red-700 transition"
                    >
                        Delete
                    </button>
                </div>
            </div>


            
        @endforeach
    </div>
    <div class="mt-6">
{{ $this-> posts->links() }}

</div>
</div>
    </div>
</div>



