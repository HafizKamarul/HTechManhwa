@extends('layouts.futuristic')

@section('title', 'Add New Manhwa - Admin')

@section('content')
<section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-8" data-aos="fade-up">
            <h1 class="text-3xl md:text-4xl font-bold font-orbitron purple-glow-text mb-4">
                ADD NEW <span class="text-purple-400">MANHWA</span>
            </h1>
            <p class="text-gray-300">Fill in the details to add a new manhwa to your collection</p>
        </div>
        
        <div class="holographic-card rounded-xl p-8" data-aos="fade-up" data-aos-delay="100">
            <form action="{{ route('admin.manhwa.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Title -->
                <div>
                    <label for="title" class="block text-purple-400 font-semibold mb-2">Title *</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}" required
                           class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    @error('title')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Description -->
                <div>
                    <label for="description" class="block text-purple-400 font-semibold mb-2">Description</label>
                    <textarea id="description" name="description" rows="4"
                              class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Author and Artist -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="author" class="block text-purple-400 font-semibold mb-2">Author</label>
                        <input type="text" id="author" name="author" value="{{ old('author') }}"
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        @error('author')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="artist" class="block text-purple-400 font-semibold mb-2">Artist</label>
                        <input type="text" id="artist" name="artist" value="{{ old('artist') }}"
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        @error('artist')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Status and Year -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="status" class="block text-purple-400 font-semibold mb-2">Status *</label>
                        <select id="status" name="status" required
                                class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                            <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="hiatus" {{ old('status') == 'hiatus' ? 'selected' : '' }}>Hiatus</option>
                            <option value="dropped" {{ old('status') == 'dropped' ? 'selected' : '' }}>Dropped</option>
                        </select>
                        @error('status')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="year" class="block text-purple-400 font-semibold mb-2">Publication Year</label>
                        <input type="number" id="year" name="year" value="{{ old('year', date('Y')) }}" 
                               min="1900" max="{{ date('Y') }}"
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        @error('year')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Cover Image -->
                <div>
                    <label for="cover_image" class="block text-purple-400 font-semibold mb-2">Cover Image</label>
                    <input type="file" id="cover_image" name="cover_image" accept="image/*"
                           class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <p class="text-gray-400 text-sm mt-1">Recommended: JPG, PNG, GIF. Max size: 5MB</p>
                    @error('cover_image')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Genres -->
                <div>
                    <label class="block text-purple-400 font-semibold mb-2">Genres</label>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($genres as $genre)
                        <label class="flex items-center p-3 bg-gray-700/50 rounded-lg border border-gray-600 hover:border-purple-500/50 cursor-pointer transition-all">
                            <input type="checkbox" name="genres[]" value="{{ $genre->id }}" 
                                   {{ in_array($genre->id, old('genres', [])) ? 'checked' : '' }}
                                   class="mr-2 rounded border-gray-600 text-purple-500 focus:ring-purple-500">
                            <span class="text-gray-300 text-sm">{{ $genre->name }}</span>
                        </label>
                        @endforeach
                    </div>
                    @error('genres')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Submit Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                    <button type="submit" 
                            class="px-8 py-3 bg-purple-500 hover:bg-purple-600 text-white font-bold rounded-lg transition-all transform hover:scale-105">
                        <i data-feather="save" class="inline w-5 h-5 mr-2"></i>Create Manhwa
                    </button>
                    <a href="{{ route('admin.index') }}" 
                       class="px-8 py-3 border border-gray-600 text-gray-300 hover:border-purple-400 hover:text-purple-400 font-bold rounded-lg transition-all text-center">
                        <i data-feather="arrow-left" class="inline w-5 h-5 mr-2"></i>Back to Admin
                    </a>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection
