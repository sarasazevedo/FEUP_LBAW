
@extends('layouts.app')

@section('og:title', 'Main Features')
@section('og:description', 'Discover the main features of Raffia, the social network for food enthusiasts.')
@section('og:image', asset('storage/default-features-image.png'))
@section('og:url', route('main_features'))

@section('content')
  <section class="main-features mt-8 px-4 lg:px-8">
    <h1 class="text-4xl font-bold text-primary mb-6">Main Features</h1>
    <div class="bg-white p-6 rounded-lg shadow-lg">
      <ul class="list-disc list-inside mb-4 text-lg text-gray-700">
        <li class="mb-4"><span class="font-semibold">User Profiles:</span> Personal profiles where users can upload photos, share their bio, and list their favorite cuisines and restaurants.</li>
        <li class="mb-4"><span class="font-semibold">Restaurant Reviews:</span> Detailed reviews with ratings for various aspects such as food quality, service, ambiance, and value for money.</li>
        <li class="mb-4"><span class="font-semibold">Photo Sharing:</span> Users can upload photos of their meals and dining experiences.</li>
        <li class="mb-4"><span class="font-semibold">Search and Discovery:</span> Advanced search features to find restaurants by location, cuisine, rating, and other criteria.</li>
        <li class="mb-4"><span class="font-semibold">Social Interaction:</span> Commenting, liking, and sharing reviews to foster community engagement.</li>
        <li class="mb-4"><span class="font-semibold">Recommendations:</span> Personalized restaurant recommendations based on user preferences and review history.</li>
        <li class="mb-4"><span class="font-semibold">Events and Deals:</span> Information on restaurant events, special offers, and exclusive deals for users.</li>
        <li class="mb-4"><span class="font-semibold">Notifications:</span> Alerts for new reviews, comments, and updates from followed users and restaurants.</li>
      </ul>
    </div>
  </section>
@endsection