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
        <div class="bg-gray-100 border-l-4 border-blue-500 p-4 rounded shadow">
            <p><strong>Status:</strong> {{ $apiStatus }}</p>
            <p><strong>Message:</strong> {{ $apiMessage }}</p>
            <p><strong>Posts fetched:</strong> {{ $externalPostsCount }}</p>
        </div>
    @endif

    {{-- Search --}}
    <input 
        type="text"
        placeholder="Search posts..."
        wire:model="search"
        class="w-full p-3 border border-gray-300 rounded shadow-sm focus:ring focus:ring-blue-200 focus:outline-none"
    />

    {{-- Create New Post --}}
    <div class="bg-white p-6 rounded shadow space-y-4">
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
            <div class="bg-white p-4 rounded shadow hover:shadow-lg transition relative">
                <h3 class="text-xl font-bold text-gray-800">{{ $post->title }}</h3>
                <p class="text-gray-700">{{ $post->body }}</p>
                <p class="text-sm text-gray-500 mt-1">Status: <span class="font-semibold">{{ $post->status }}</span></p>

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
</div>
