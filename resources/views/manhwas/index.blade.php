@extends('layouts.futuristic')

@section('title', 'Browse Manhwa - HTech Manhwa')

@section('content')
<!-- Header Section -->
<section class="pt-32 pb-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto text-center" data-aos="fade-up">
        <h1 class="text-4xl font-bold font-orbitron purple-glow-text mb-4">MANHWA <span class="text-purple-400">LIBRARY</span></h1>
        <p class="text-gray-300 text-lg">Discover your next favorite story</p>
    </div>
</section>

<!-- Filters and Search -->
<section class="pb-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="holographic-card rounded-xl p-6" data-aos="fade-up">
            <form method="GET" action="{{ route('manhwas.index') }}">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <input type="text" name="search" class="w-full bg-gray-700/50 text-white placeholder-gray-400 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500" 
                               placeholder="Search titles, authors..." value="{{ request('search') }}">
                    </div>
                    
                    <!-- Genre Filter -->
                    <div>
                        <select name="genre" class="w-full bg-gray-700/50 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">All Genres</option>
                            @foreach($genres as $genre)
                                <option value="{{ $genre->slug }}" {{ request('genre') === $genre->slug ? 'selected' : '' }}>
                                    {{ $genre->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <!-- Status Filter -->
                    <div>
                        <select name="status" class="w-full bg-gray-700/50 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">All Status</option>
                            <option value="ongoing" {{ request('status') === 'ongoing' ? 'selected' : '' }}>Ongoing</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="hiatus" {{ request('status') === 'hiatus' ? 'selected' : '' }}>Hiatus</option>
                            <option value="dropped" {{ request('status') === 'dropped' ? 'selected' : '' }}>Dropped</option>
                        </select>
                    </div>
                    
                    <!-- Sort -->
                    <div class="flex space-x-2">
                        <select name="sort" class="flex-1 bg-gray-700/50 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>Title</option>
                            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest</option>
                            <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Popular</option>
                            <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Rating</option>
                        </select>
                        <button type="submit" class="px-6 py-3 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-all">
                            <i data-feather="search" class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Results Header -->
<section class="pb-6 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="flex justify-between items-center" data-aos="fade-up">
            <div>
                <h2 class="text-2xl font-bold font-orbitron text-white">
                    Browse Results
                    <span class="text-purple-400 text-lg ml-2">({{ $manhwas->total() }} found)</span>
                </h2>
            </div>
            
            @if(request()->hasAny(['search', 'genre', 'status']))
                <a href="{{ route('manhwas.index') }}" class="px-4 py-2 border border-purple-400 text-purple-400 hover:bg-purple-400/10 rounded-lg transition-all">
                    <i data-feather="x" class="w-4 h-4 inline mr-1"></i>Clear Filters
                </a>
            @endif
        </div>
    </div>
</section>

<!-- Manhwa Grid -->
@if($manhwas->count() > 0)
<section class="pb-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 xl:grid-cols-6 gap-6">
            @foreach($manhwas as $index => $manhwa)
            <div class="holographic-card rounded-xl overflow-hidden transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ ($index % 12) * 50 }}">
                <div class="relative">
                    @if($manhwa->cover_image)
                        <img src="{{ asset('storage/' . $manhwa->cover_image) }}" alt="{{ $manhwa->title }}" class="w-full manhwa-cover">
                    @else
                        <div class="w-full manhwa-cover bg-gradient-to-br from-purple-900 to-gray-800 flex items-center justify-content-center">
                            <i data-feather="book-open" class="w-12 h-12 text-purple-400"></i>
                        </div>
                    @endif
                    
                    <!-- Status Badge -->
                    <div class="absolute top-2 left-2">
                        <span class="px-2 py-1 rounded text-xs font-bold text-white
                            @if($manhwa->status === 'completed') bg-green-500/90
                            @elseif($manhwa->status === 'ongoing') bg-blue-500/90
                            @elseif($manhwa->status === 'hiatus') bg-yellow-500/90
                            @else bg-red-500/90
                            @endif">
                            {{ ucfirst($manhwa->status) }}
                        </span>
                    </div>
                    
                    <!-- Chapter Count -->
                    <div class="absolute top-2 right-2">
                        <span class="bg-gray-900/80 text-purple-400 px-2 py-1 rounded text-xs font-bold">
                            {{ $manhwa->chapters->count() }} CH
                        </span>
                    </div>
                    
                    <!-- Views -->
                    @if($manhwa->views > 0)
                        <div class="absolute bottom-2 left-2">
                            <span class="bg-gray-900/80 text-white px-2 py-1 rounded text-xs font-bold">
                                <i data-feather="eye" class="w-3 h-3 inline mr-1"></i>{{ number_format($manhwa->views) }}
                            </span>
                        </div>
                    @endif
                    
                    <!-- Rating -->
                    @if($manhwa->rating > 0)
                        <div class="absolute bottom-2 right-2">
                            <span class="bg-yellow-500/90 text-gray-900 px-2 py-1 rounded text-xs font-bold">
                                <i data-feather="star" class="w-3 h-3 inline mr-1"></i>{{ number_format($manhwa->rating, 1) }}
                            </span>
                        </div>
                    @endif
                </div>
                
                <div class="p-4">
                    <h3 class="font-bold text-sm mb-1 line-clamp-2 leading-tight" title="{{ $manhwa->title }}">
                        {{ $manhwa->title }}
                    </h3>
                    
                    @if($manhwa->author)
                        <p class="text-gray-400 text-xs mb-2 line-clamp-1">{{ $manhwa->author }}</p>
                    @endif
                    
                    <!-- Genres -->
                    @if($manhwa->genres->count() > 0)
                        <div class="mb-3">
                            @foreach($manhwa->genres->take(2) as $genre)
                                <span class="inline-block bg-purple-500/20 text-purple-300 text-xs px-2 py-1 rounded mr-1 mb-1">{{ $genre->name }}</span>
                            @endforeach
                            @if($manhwa->genres->count() > 2)
                                <span class="text-gray-500 text-xs">+{{ $manhwa->genres->count() - 2 }}</span>
                            @endif
                        </div>
                    @endif
                    
                    <a href="{{ route('manhwas.show', $manhwa) }}" class="w-full block py-2 bg-purple-500 hover:bg-purple-600 text-white font-medium rounded text-center text-sm transition-all">
                        Read Now
                    </a>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-12 flex justify-center" data-aos="fade-up">
            <div class="holographic-card rounded-lg px-6 py-4">
                {{ $manhwas->links() }}
            </div>
        </div>
    </div>
</section>
@else
<section class="py-20 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto text-center">
        <div class="holographic-card rounded-xl p-12" data-aos="fade-up">
            <i data-feather="search" class="w-24 h-24 text-purple-400 mx-auto mb-6"></i>
            <h3 class="text-3xl font-bold font-orbitron purple-glow-text mb-4">NO MANHWA FOUND</h3>
            <p class="text-gray-300 text-lg mb-8">Try adjusting your search criteria or browse all manhwa.</p>
            @if(request()->hasAny(['search', 'genre', 'status']))
                <a href="{{ route('manhwas.index') }}" class="px-8 py-3 bg-purple-500 hover:bg-purple-600 text-white font-bold rounded-lg transition-all transform hover:scale-105">
                    <i data-feather="list" class="w-5 h-5 inline mr-2"></i>View All Manhwa
                </a>
            @endif
        </div>
    </div>
</section>
@endif
@endsection
