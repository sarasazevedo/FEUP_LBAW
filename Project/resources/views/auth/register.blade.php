@extends('layouts.app')

@section('content')
<div class="flex items-center justify-center min-h-screen bg-gray-100">
  <!-- Form for the Register -->
  <div class="w-full max-w-md p-8 space-y-6 bg-primary rounded-lg shadow-md">
    <h2 class="text-2xl font-bold text-white text-center">Register</h2>
    <form method="POST" action="{{ route('register') }}" id="register-form">
      {{ csrf_field() }}

      <fieldset class="mb-4">
        <legend class="block text-sm font-medium text-white">Name <span class="text-red-500">*</span></legend>
        <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm bg-tertiary text-primary focus:outline-none focus:ring-secondary focus:border-secondary sm:text-sm" placeholder="Enter your name">
        @if ($errors->has('name'))
          <span class="text-sm text-white">
            {{ $errors->first('name') }}
          </span>
        @endif
      </fieldset>

      <fieldset class="email mb-4 relative">
        <legend class="block text-sm font-medium text-white">E-Mail <span class="text-red-500">*</span></legend>
        <div class="relative">
          <input id="email" type="email" name="email" value="{{ old('email') }}" required class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm bg-tertiary text-primary focus:outline-none focus:ring-secondary focus:border-secondary sm:text-sm" placeholder="Enter your email">
          <span class="tooltip-icon" data-tooltip="Enter a valid email address. Example: example@domain.com">i</span>
        </div>
        @if ($errors->has('email'))
          <span class="text-sm text-white">
            {{ $errors->first('email') }}
          </span>
        @endif
      </fieldset>

      <fieldset class="username mb-4">
        <legend class="block text-sm font-medium text-white">Username <span class="text-red-500">*</span></legend>
        <input id="username" type="text" name="username" value="{{ old('username') }}" required class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm bg-tertiary text-primary focus:outline-none focus:ring-secondary focus:border-secondary sm:text-sm" placeholder="Enter your username">
        @if ($errors->has('username'))
          <span class="text-sm text-white">{{ $errors->first('username') }}</span>
        @endif
      </fieldset>

      <fieldset class="password mb-4 relative">
        <legend class="block text-sm font-medium text-white">Password <span class="text-red-500">*</span></legend>
        
        <div class="relative">
          <input id="password" type="password" name="password" required class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm bg-tertiary text-primary focus:outline-none focus:ring-secondary focus:border-secondary sm:text-sm" placeholder="Enter your password">
          <span class="tooltip-icon" data-tooltip="Password should be at least 3 characters long.">i</span>
        </div>
        @if ($errors->has('password'))
          <span class="text-sm text-white">
            {{ $errors->first('password') }}
          </span>
        @endif
      </fieldset>

      <fieldset class="confirm_password mb-4">
        <legend class="block text-sm font-medium text-white">Confirm Password <span class="text-red-500">*</span></legend>
        <input id="password-confirm" type="password" name="password_confirmation" required class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm bg-tertiary text-primary focus:outline-none focus:ring-secondary focus:border-secondary sm:text-sm" placeholder="Confirm your password">
        @error('password_confirmation')
          <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
      </fieldset>

      <fieldset class="role mb-4">
        <legend class="block text-sm font-medium text-white">Register as: <span class="text-red-500">*</span></legend>
     
        <select id="role" name="role" required class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm bg-tertiary text-primary focus:outline-none focus:ring-secondary focus:border-secondary sm:text-sm">
          <option value="client">Client</option>
          <option value="restaurant">Restaurant</option>
        </select>
        @error('role')
          <span class="text-red-500 text-sm">{{ $message }}</span>
        @enderror
      </fieldset>

      <fieldset id="restaurant-fields" style="display: none;">
        <legend class="block text-sm font-medium text-white">Restaurant Type <span class="text-red-500">*</span></legend>
        <div class="type mb-4">
         
          <select id="type_id" name="type_id" class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm bg-tertiary text-primary focus:outline-none focus:ring-secondary focus:border-secondary sm:text-sm">
            @foreach(App\Models\RestaurantType::all() as $type)
              <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
          </select>
          @error('type_id')
            <span class="text-red-500 text-sm">{{ $message }}</span>
          @enderror
        </div>

        <div class="capacity mb-4">
          <legend for="capacity" class="block text-sm font-medium text-white">Capacity: <span class="text-red-500">*</span></legend>
          <input id="capacity" type="number" name="capacity" class="w-full px-3 py-2 mt-1 border border-gray-300 rounded-md shadow-sm bg-tertiary text-primary focus:outline-none focus:ring-secondary focus:border-secondary sm:text-sm" placeholder="Enter the capacity of your restaurant">
          @error('capacity')
            <span class="text-red-500 text-sm">{{ $message }}</span>
          @enderror
        </div>
      </fieldset>

      <fieldset class="submit mt-6">
        <legend class="sr-only">Submit</legend>
        <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-primary bg-tertiary border border-transparent rounded-md shadow-sm hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary">
          Register
        </button>
        <a class="block w-full px-4 py-2 mt-2 text-sm font-medium text-center text-primary bg-tertiary border border-primary rounded-md shadow-sm hover:bg-yellow-100 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-secondary" href="{{ route('login') }}">Login</a>
      </fieldset>

      <!-- Success -->
      @if (session('success'))
        <p class="mt-4 text-sm text-white">
          {{ session('success') }}
        </p>
      @endif
    </form>
  </div>
</div>


<style>
.tooltip-icon {
  display: inline-block;
  cursor: pointer;
  color: #fffaee; 
  border: 2px solid #fffaee; 
  border-radius: 50%;
  width: 16px; 
  height: 16px; 
  text-align: center;
  line-height: 14px; 
  font-size: 12px; 
  position: absolute;
  right: -25px; 
  top: 60%;
  transform: translateY(-50%);
}

.tooltip-icon:hover::after {
  content: attr(data-tooltip);
  position: absolute;
  bottom: 100%;
  left: 50%;
  transform: translateX(-50%);
  background-color: #000;
  color: #fff;
  padding: 5px;
  border-radius: 5px;
  white-space: nowrap;
  z-index: 10;
  opacity: 1;
  visibility: visible;
}

.tooltip-icon::after {
  content: '';
  position: absolute;
  bottom: 100%;
  left: 50%;
  transform: translateX(-50%);
  background-color: #000;
  color: #fff;
  padding: 5px;
  border-radius: 5px;
  white-space: nowrap;
  z-index: 10;
  opacity: 0;
  visibility: hidden;
  transition: opacity 0.3s;
}
</style>
@endsection