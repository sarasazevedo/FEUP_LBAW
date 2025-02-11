@extends('layouts.app')

@section('og:title', 'Feed')
@section('og:description', 'Explore the latest posts and updates from the Raffia community.')
@section('og:image', asset('storage/default-feed-image.png'))

@section('content')
<div id="status-message" class="hidden p-4 mb-4 rounded-lg"></div>

  @if(Auth::check())
  <button onclick="openCreatePostModal()" class="fixed bottom-4 right-4 bg-fourth text-white p-4 rounded-full shadow-lg no-print">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
    </svg>
  </button>
  <section id="create-post-modal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden z-50">
    <section class="bg-white rounded-lg overflow-hidden w-11/12 md:w-3/4 lg:w-1/2 relative z-50 shadow-lg">
        <section class="p-4">
            <h2 class="text-xl font-bold mb-4">Create Post</h2>
            @include('partials.create_post')
            <button onclick="closeCreatePostModal()" class="absolute top-0 right-0 m-4 text-black z-60 text-2xl px-4 py-2">&times;</button>
        </section>
    </section>
  </section>
  @endif
  <section id="posts" class="feed-posts mt-4" data-offset="{{ $offset }}" data-base-url="/">
    @forelse ($posts as $post)
      @if($post instanceof App\Models\ReviewPost)
        @include('partials.review_post', ['post' => $post])
      @elseif($post instanceof App\Models\InformationalPost)
        @include('partials.informational_post', ['post' => $post])
      @endif
    @empty
      <h2 class="no_results">There are no posts here. Follow someone.</h2>
    @endforelse
  </section>
@endsection