
@extends('layouts.app')

@section('og:title', 'About Us')
@section('og:description', 'Learn more about Raffia, the social network for food enthusiasts.')
@section('og:image', asset('storage/default-about-image.png'))
@section('og:url', route('about'))

@section('content')
  <section class="about-us mt-8 px-4 lg:px-8">
    <h1 class="text-4xl font-bold text-primary mb-6">About Us</h1>
    <div class="bg-white p-6 rounded-lg shadow-lg">
      <p class="mb-6 text-lg text-gray-700">The Raffia social network is being developed by a group of students in the Bachelor in Informatics and Computing Engineering, in the Database and Web Applications Laboratory course as a product to connect food lovers and restaurants.</p>
      <p class="mb-6 text-lg text-gray-700">The main goal of the project is to develop a social network where food enthusiasts can share their dining experiences, rate restaurants, and discover new culinary spots based on genuine reviews. We aim to build a community that values transparency, quality, and the joy of sharing gastronomic adventures.</p>
      <p class="mb-6 text-lg text-gray-700">Our platform will, from the client's perspective, serve as a go-to resource for anyone looking to explore new eateries, read honest reviews, and connect with fellow food lovers. From the restaurant's perspective, it will be a place to advertise their services and products.</p>
      <p class="mb-6 text-lg text-gray-700">The motivation behind this project stems from a passion for food and the desire to build a community around it. In the competitive world of social networking and review platforms, we recognize that many people seek reliable recommendations when choosing where to dine. Our platform will provide an all-in-one service where users can review, book, and search for restaurants, making it a comprehensive solution. With everything available in one place, users won’t need multiple apps to find, rate, and reserve dining experiences — they can do it all within our platform.</p>
      <p class="mb-6 text-lg text-gray-700">Our platform will operate in a dynamic digital environment, catering to users who value authentic content. The frontend will be designed to offer an intuitive and engaging user experience across all devices, including desktops, tablets, and smartphones.</p>
    </div>
  </section>
@endsection