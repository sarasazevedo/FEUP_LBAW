
@extends('layouts.app')

@section('og:title', 'Frequently Asked Questions')
@section('og:description', 'Find answers to common questions about Raffia.')
@section('og:image', asset('storage/default-faq-image.png'))
@section('og:url', route('faq'))

@section('content')
  <section class="faq mt-8 px-4 lg:px-8">
    <h1 class="text-4xl font-bold text-primary mb-6">Frequently Asked Questions</h1>
    <div class="bg-white p-6 rounded-lg shadow-lg">
      <section class="mb-6">
        <h2 class="text-2xl font-semibold text-secondary mb-2">What is Raffia?</h2>
        <p class="text-lg text-gray-700">Raffia is a social network developed by students in the Bachelor in Informatics and Computing Engineering, aimed at connecting food lovers and restaurants. It allows users to share dining experiences, rate restaurants, and discover new culinary spots based on genuine reviews.</p>
      </section>
      <section class="mb-6">
        <h2 class="text-2xl font-semibold text-secondary mb-2">How can I create an account?</h2>
        <p class="text-lg text-gray-700">You can create an account by clicking on the "Register" link on the homepage and filling out the registration form with your details.</p>
      </section>
      <section class="mb-6">
        <h2 class="text-2xl font-semibold text-secondary mb-2">How do I write a review?</h2>
        <p class="text-lg text-gray-700">Once you have an account, you can write a review by clicking on the plus icon on the bottom left. Then a popup will appear and you just have to fill out the review form and submit it.</p>
      </section>
      <section class="mb-6">
        <h2 class="text-2xl font-semibold text-secondary mb-2">Can I upload photos of my dining experiences?</h2>
        <p class="text-lg text-gray-700">Yes, you can upload photos of your meals and dining experiences when writing a review or by updating your profile.</p>
      </section>
      <section class="mb-6">
        <h2 class="text-2xl font-semibold text-secondary mb-2">How do I search for restaurants?</h2>
        <p class="text-lg text-gray-700">You can use the advanced search features to find restaurants by location, cuisine, rating, and other criteria. Simply enter your search terms in the search bar and browse the results.</p>
      </section>
      <section class="mb-6">
        <h2 class="text-2xl font-semibold text-secondary mb-2">How do I follow other users?</h2>
        <p class="text-lg text-gray-700">You can follow other users by visiting their profile and clicking on the "Follow" button. This allows you to stay updated with their reviews and activities.</p>
      </section>
      <section class="mb-6">
        <h2 class="text-2xl font-semibold text-secondary mb-2">How do I receive notifications?</h2>
        <p class="text-lg text-gray-700">You will receive notifications for new reviews, comments, and updates from users and restaurants you follow. Make sure your notification settings are enabled in your profile.</p>
      </section>
      <section class="mb-6">
        <h2 class="text-2xl font-semibold text-secondary mb-2">How can restaurants advertise on Raffia?</h2>
        <p class="text-lg text-gray-700">Restaurants can advertise their services and products by creating a profile and posting about their events, special offers, and exclusive deals. Contact our support team for more information on advertising options.</p>
      </section>
    </div>
  </section>
@endsection