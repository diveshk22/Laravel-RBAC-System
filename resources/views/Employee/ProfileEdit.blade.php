@extends('layout.app')

@section('content')

<div class="min-h-screen bg-gray-100 flex items-center justify-center py-10">
    
    <div class="w-full max-w-3xl bg-white shadow-xl rounded-2xl p-8">

        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center">
            Edit Profile
        </h2>

        <form action="{{ route('profile.update') }}" 
              method="POST" 
              enctype="multipart/form-data" 
              class="space-y-6">

            @csrf
            @method('PUT')

            @role('employee')
            <!-- Profile Image Section -->
            <div class="flex flex-col items-center">

                <div class="relative">
                    <img 
                        id="imagePreview"
                        src="{{ auth()->user()->photo 
                                ? asset('storage/' . auth()->user()->photo) 
                                : asset('default-avatar.png') }}"
                        class="w-20 h-20 rounded-full object-cover border-2 border-indigo-300"
                    >
                    <label 
                        for="image"
                        class="absolute bottom-0 right-0 bg-indigo-600 p-2 rounded-full cursor-pointer hover:bg-indigo-700 transition"
                    >
                        <svg 
                            xmlns="http://www.w3.org/2000/svg" 
                            class="h-5 w-5 text-white" 
                            fill="none"
                            viewBox="0 0 24 24" 
                            stroke="currentColor"
                        >
                            <path 
                                stroke-linecap="round" 
                                stroke-linejoin="round" 
                                stroke-width="2"
                                d="M15.232 5.232l3.536 3.536M9 13l6-6 3 3-6 6H9v-3z"
                            />
                        </svg>
                    </label>
                </div>

                <input 
                    type="file" 
                    name="image" 
                    id="image" 
                    class="hidden" 
                    accept="image/*"
                >

                @error('image')
                    <p class="text-red-500 text-sm mt-2">
                        {{ $message }}
                    </p>
                @enderror

            </div>
            @endrole

            <!-- Name -->
            <div>
                <label class="block text-sm font-semibold text-gray-700">
                    Name
                </label>

                <input 
                    type="text" 
                    name="name"
                    value="{{ old('name', auth()->user()->name) }}"
                    class="mt-2 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    required
                >

                @error('name')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Email -->
            <div>
                <label class="block text-sm font-semibold text-gray-700">
                    Email
                </label>

                <input 
                    type="email" 
                    name="email"
                    value="{{ old('email', auth()->user()->email) }}"
                    class="mt-2 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                    required
                >

                @error('email')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label class="block text-sm font-semibold text-gray-700">
                    New Password
                </label>

                <input 
                    type="password" 
                    name="password"
                    class="mt-2 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                >

                @error('password')
                    <p class="text-red-500 text-sm mt-1">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label class="block text-sm font-semibold text-gray-700">
                    Confirm Password
                </label>

                <input 
                    type="password" 
                    name="password_confirmation"
                    class="mt-2 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:outline-none"
                >
            </div>

            <!-- Submit Button -->
            <div class="text-center">
                <button 
                    type="submit"
                    class="w-full bg-indigo-600 text-white py-3 rounded-lg font-semibold hover:bg-indigo-700 transition duration-300 shadow-md"
                >
                    Update Profile
                </button>
            </div>

        </form>
    </div>
</div>

<!-- SweetAlert CDN -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Image Preview Script -->
<script>
    document.getElementById('image').addEventListener('change', function(event) {
        const reader = new FileReader();

        reader.onload = function() {
            document.getElementById('imagePreview').src = reader.result;
        }

        if (event.target.files[0]) {
            reader.readAsDataURL(event.target.files[0]);
        }
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: '{{ session('success') }}',
        confirmButtonColor: '#4f46e5'
    });
</script>
@endif  

@endsection