
@extends('layouts.app')

@section('og:title', 'Contact Us')
@section('og:description', 'Get in touch with the Raffia team for any inquiries or support.')
@section('og:image', asset('storage/default-contact-image.png'))
@section('og:url', route('contacts'))

@section('content')
  <section class="contacts mt-8 px-4 lg:px-8">
    <h1 class="text-4xl font-bold text-primary mb-6">Contact Us</h1>
    <div class="bg-white p-6 rounded-lg shadow-lg">
      <p class="mb-6 text-lg text-gray-700">We'd love to hear from you! Whether you have a question about our platform, need assistance, or just want to provide feedback, feel free to reach out to us.</p>
      
      <section class="mb-6">
        <h2 class="text-2xl font-semibold text-secondary mb-2">General Inquiries</h2>
        <p class="text-lg text-gray-700">If you have any general questions or comments, please email us at:</p>
        <p><a href="mailto:info@raffia.com" class="text-primary font-bold">info@raffia.com</a></p>
      </section>
      
      <section class="mb-6">
        <h2 class="text-2xl font-semibold text-secondary mb-2">Support</h2>
        <p class="text-lg text-gray-700">For support-related inquiries, please contact our support team at:</p>
        <p><a href="mailto:support@raffia.com" class="text-primary font-bold">support@raffia.com</a></p>
      </section>
      
      <section class="mb-6">
        <h2 class="text-2xl font-semibold text-secondary mb-2">Partnerships</h2>
        <p class="text-lg text-gray-700">If you are a restaurant and would like to make a partnership with Raffia, please reach out to our partnerships team at:</p>
        <p><a href="mailto:partnerships@raffia.com" class="text-primary font-bold">partnerships@raffia.com</a></p>
      </section>
    </div>
  </section>
@endsection