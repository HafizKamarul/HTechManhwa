@extends('layouts.futuristic')

@section('title', $genre->name . ' Manhwa - HTech Manhwa')

@section('content')
<!-- Header Section -->
<section class="pt-32 pb-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto" data-aos="fade-up">
        <!-- Breadcrumb -->
        <nav class="mb-6">
            <div class="flex items-center space-x-2 text-sm">
                <a href="{{ route('home') }}" class="text-purple-400 hover:text-purple-300">Home</a>
                <i data-feather="chevron-right" class="w-4 h-4 text-gray-400"></i>
                <a href="{{ route('genres.index') }}" class="text-purple-400 hover:text-purple-300">Genres</a>
                <i data-feather="chevron-right" class="w-4 h-4 text-gray-400"></i>
                <span class="text-gray-300">{{ $genre->name }}</span>
            </div>
        </nav>
        
        <!-- Genre Header -->
        <div class="holographic-card rounded-xl p-8">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                <div class="mb-6 md:mb-0">
                    <div class="flex items-center mb-4">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-700 rounded-full flex items-center justify-center mr-4">
                            <span class="text-2xl font-bold text-white font-orbitron">
                                {{ substr($genre->name, 0, 1) }}
                            </span>
                        </div>
                        <div>
                            <h1 class="text-3xl md:text-4xl font-bold font-orbitron purple-glow-text">{{ $genre->name }}</h1>
                            <p class="text-gray-300 mt-2">
                                {{ $manhwas->total() }} {{ Str::plural('manhwa', $manhwas->total()) }} in this genre
                            </p>
                        </div>
                    </div>
                    
                    @if($genre->description)
                        <p class="text-gray-400 max-w-2xl">{{ $genre->description }}</p>
                    @endif
                </div>
                
                <!-- Quick Stats -->
                <div class="flex flex-col space-y-4 text-center md:text-right">
                    <div>
                        <span class="text-2xl font-bold purple-glow-text">{{ $manhwas->total() }}</span>
                        <p class="text-gray-300 text-sm">Total Manhwa</p>
                    </div>
                    <div>
                        <span class="text-2xl font-bold purple-glow-text">{{ $genre->manhwas()->sum('views') }}</span>
                        <p class="text-gray-300 text-sm">Total Views</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filters and Search -->
<section class="pb-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="holographic-card rounded-xl p-6" data-aos="fade-up">
            <form method="GET" action="{{ route('genres.show', $genre) }}">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Search -->
                    <div class="md:col-span-2">
                        <input type="text" name="search" class="w-full bg-gray-700/50 text-white placeholder-gray-400 rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500" 
                               placeholder="Search within {{ $genre->name }}..." value="{{ request('search') }}">
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
                    <div>
                        <select name="sort" class="w-full bg-gray-700/50 text-white rounded-lg px-4 py-3 focus:outline-none focus:ring-2 focus:ring-purple-500" onchange="this.form.submit()">
                            <option value="title" {{ request('sort') === 'title' ? 'selected' : '' }}>A-Z</option>
                            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Latest Added</option>
                            <option value="popular" {{ request('sort') === 'popular' ? 'selected' : '' }}>Most Popular</option>
                            <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Highest Rated</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Manhwa Grid -->
<section class="pb-20 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        @if($manhwas->count() > 0)
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
                                @foreach($manhwa->genres->take(2) as $manhwaGenre)
                                    <span class="inline-block bg-purple-500/20 text-purple-300 text-xs px-2 py-1 rounded mr-1 mb-1">{{ $manhwaGenre->name }}</span>
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
            <div class="mt-12">
                {{ $manhwas->withQueryString()->links('pagination::tailwind') }}
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="holographic-card rounded-xl p-16 mx-auto max-w-md">
                    <div class="mb-6">
                        <i data-feather="search" class="w-20 h-20 text-gray-400 mx-auto mb-4"></i>
                        <h3 class="text-2xl font-bold font-orbitron text-purple-400 mb-2">
                            @if(request('search'))
                                No Search Results
                            @else
                                No Manhwa Found
                            @endif
                        </h3>
                        <p class="text-gray-400 mb-6">
                            @if(request('search'))
                                No manhwa found matching "{{ request('search') }}" in {{ $genre->name }} genre.
                            @else
                                No manhwa have been added to the {{ $genre->name }} genre yet.
                            @endif
                        </p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        @if(request('search'))
                            <a href="{{ route('genres.show', $genre) }}" class="px-6 py-3 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-all">
                                <i data-feather="x" class="inline w-5 h-5 mr-2"></i>Clear Search
                            </a>
                        @endif
                        <a href="{{ route('genres.index') }}" class="px-6 py-3 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-all">
                            <i data-feather="arrow-left" class="inline w-5 h-5 mr-2"></i>All Genres
                        </a>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Related Genres -->
@if($allGenres->where('id', '!=', $genre->id)->count() > 0)
<section class="pb-20 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="holographic-card rounded-xl p-6" data-aos="fade-up">
            <h3 class="text-xl font-bold purple-glow-text mb-6">Explore Other Genres</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($allGenres->where('id', '!=', $genre->id)->take(6) as $otherGenre)
                    <a href="{{ route('genres.show', $otherGenre) }}" 
                       class="text-center p-4 bg-gray-700/20 hover:bg-purple-500/20 border border-gray-600/30 hover:border-purple-500/30 rounded-lg transition-all group">
                        <div class="w-12 h-12 mx-auto mb-2 bg-gradient-to-br from-purple-500 to-purple-700 rounded-full flex items-center justify-center">
                            <span class="text-lg font-bold text-white font-orbitron">
                                {{ substr($otherGenre->name, 0, 1) }}
                            </span>
                        </div>
                        <h4 class="text-sm font-semibold text-gray-300 group-hover:text-purple-300">
                            {{ $otherGenre->name }}
                        </h4>
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@endif
@endsection

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush
