@extends('layouts.app')

@section('og:title', 'Post Details')
@section('og:description', $post->postDetails->content ?? 'View the details of this post on Raffia.')
@section('og:image', asset('storage/' . ($post->postDetails->images[0] ?? 'default-post-image.png')))
@section('og:url', route('posts.show', ['id' => $post->id]))

@section('content')
<div id="status-message" class="hidden p-4 mb-4 rounded-lg"></div>

    <section id="posts" class="feed-posts mt-4">
        @if($post instanceof App\Models\ReviewPost)
            @include('partials.review_post', ['post' => $post])
        @elseif($post instanceof App\Models\InformationalPost)
            @include('partials.informational_post', ['post' => $post])
        @endif
    </section>
@endsection