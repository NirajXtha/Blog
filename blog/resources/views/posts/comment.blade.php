<x-app-layout>
    <div class="container">
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Create Post') }}
    </div>
   
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    {{ $post->title }}
    @foreach($post->comment as $comment)
        <div class="comment-div ms-3">
            {{ $comment->comment }}
        </div>
    @endforeach
    <form method="POST" action="{{ route('comment.store' , ['postid' => $post->id]) }}">
        @csrf
        <!-- Comment -->
        <input id="post_id" class="block mt-1 w-full" type="text" name="post_id"  value="{{ $post->id }}" required autofocus hidden />

        <div>
            <label for="comment" >{{ __('Comment') }}</label>
            <input id="comment" class="block mt-1 w-full" type="text" name="comment"  value="" required autofocus />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Submit') }}
            </x-primary-button>
        </div>
    </form>
    </div>
</x-app-layout>
