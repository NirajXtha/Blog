<x-app-layout>
    <div class="container">
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('Create Post') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <form method="POST" action="{{ route('post.update' , ['postid' => $post->id]) }}">
        @csrf
        @method('PUT')
        <!-- Title -->
        <div>
            <label for="title" >{{ __('Title') }}</label>
            <input id="title" class="block mt-1 w-full" type="text" name="title"  value="{{ $post->title }}" required autofocus />
        </div>

         <!-- Description -->
         <div>
            <label for="description" >{{ __('Description') }}</label>
            <textarea id="description" rows="10" class="block mt-1 w-full"  name="description"  required autofocus>{{ $post->description }}</textarea>
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Submit') }}
            </x-primary-button>
        </div>
    </form>
    </div>
</x-app-layout>
