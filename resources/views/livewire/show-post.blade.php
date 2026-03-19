<div class="max-w-2xl mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">
        {{ $post->title }}
    </h1>

    <p class="text-gray-600 mb-2">
        {{ $post->reading_time }} min read • {{ $post->word_count }} words
    </p>

    <p class="mb-4">
        {{ $post->body }}
    </p>

    <p class="text-sm text-gray-500">
        Likes: {{ $post->likes }}
    </p>
</div>