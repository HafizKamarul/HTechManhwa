@extends('layouts.futuristic')

@section('title', 'HTech Manhwa - Home')

@section('content')
<!-- Hero Section -->
<section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="text-center" data-aos="fade-up">
            <div class="flex justify-center mb-8">
                <img src="{{ asset('logo.png') }}" alt="HTech Manhwa Logo" class="h-24 w-auto logo-glow">
            </div>
            <h1 class="text-4xl md:text-6xl font-bold font-orbitron purple-glow-text mb-6">READ <span class="text-purple-400">MANHWA</span> IN STYLE</h1>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto mb-10">Don't just read bro, immerse in it!</p>
            <div class="flex justify-center space-x-4">
                <a href="{{ route('manhwas.index') }}" class="px-8 py-3 bg-purple-500 hover:bg-purple-600 text-white font-bold rounded-lg transition-all transform hover:scale-105">
                    Start Reading
                </a>
                <a href="{{ route('manhwas.index') }}" class="px-8 py-3 border border-purple-400 text-purple-400 hover:bg-purple-400/10 font-bold rounded-lg transition-all transform hover:scale-105">
                    Explore Library
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Search Section -->
<section class="py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <form method="GET" action="{{ route('manhwas.index') }}" class="mb-4" data-aos="fade-up">
            <div class="holographic-card rounded-lg p-4">
                <div class="flex">
                    <input type="text" name="search" class="flex-1 bg-transparent text-white placeholder-gray-400 focus:outline-none text-lg" 
                           placeholder="Search for manhwa titles, authors..." value="{{ request('search') }}">
                    <button type="submit" class="ml-4 px-6 py-2 bg-purple-500 hover:bg-purple-600 text-white font-medium rounded transition-all">
                        <i data-feather="search" class="w-5 h-5"></i>
                    </button>
                </div>
            </div>
        </form>
    </div>
</section>

<!-- Featured/Latest Manhwa -->
@if($latestManhwas->count() > 0)
<section class="py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold font-orbitron mb-12 text-center purple-glow-text" data-aos="fade-up">FEATURED <span class="text-purple-400">TITLES</span></h2>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4 sm:gap-6">
            @foreach($latestManhwas as $index => $manhwa)
            <div class="holographic-card rounded-xl overflow-hidden transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ ($index % 6) * 100 }}">
                <div class="relative">
                    @if($manhwa->cover_image)
                        <img src="{{ asset('storage/' . $manhwa->cover_image) }}" alt="{{ $manhwa->title }}" class="w-full object-cover" style="height: 280px;">
                    @else
                        <div class="w-full bg-gradient-to-br from-purple-900 to-gray-800 flex items-center justify-center" style="height: 280px;">
                            <i data-feather="book-open" class="w-12 h-12 sm:w-16 sm:h-16 text-purple-400"></i>
                        </div>
                    @endif
                    <div class="absolute top-2 right-2 bg-purple-500/90 text-white px-1.5 py-0.5 sm:px-2 sm:py-1 rounded text-xs font-bold">NEW</div>
                    <div class="absolute top-2 left-2 bg-gray-900/80 text-purple-400 px-1.5 py-0.5 sm:px-2 sm:py-1 rounded text-xs font-bold">{{ $manhwa->chapters->count() }} CH</div>
                </div>
                
                <div class="p-4">
                    <h3 class="font-bold text-sm mb-3 line-clamp-2 leading-tight text-white" title="{{ $manhwa->title }}">
                        {{ $manhwa->title }}
                    </h3>
                    
                    <a href="{{ route('manhwas.show', $manhwa) }}" class="w-full block py-2 bg-purple-500 hover:bg-purple-600 text-white font-medium rounded text-center text-sm transition-all">
                        Read Now
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Popular Manhwa -->
@if($popularManhwas->count() > 0 && $popularManhwas->first()->views > 0)
<section class="py-16 px-4 sm:px-6 lg:px-8 bg-gray-900/50 backdrop-blur-sm">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold font-orbitron mb-12 text-center purple-glow-text" data-aos="fade-up">POPULAR <span class="text-purple-400">READS</span></h2>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-6 gap-4 sm:gap-6">
            @foreach($popularManhwas->take(6) as $index => $manhwa)
            <div class="holographic-card rounded-xl overflow-hidden transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ ($index % 6) * 100 }}">
                <div class="relative">
                    @if($manhwa->cover_image)
                        <img src="{{ asset('storage/' . $manhwa->cover_image) }}" alt="{{ $manhwa->title }}" class="w-full object-cover" style="height: 280px;">
                    @else
                        <div class="w-full bg-gradient-to-br from-purple-900 to-gray-800 flex items-center justify-center" style="height: 280px;">
                            <i data-feather="trending-up" class="w-12 h-12 sm:w-16 sm:h-16 text-purple-400"></i>
                        </div>
                    @endif
                    @php
                        $badges = ['POPULAR', 'HOT', 'TRENDING'];
                        $colors = ['bg-pink-500/90', 'bg-violet-500/90', 'bg-indigo-500/90'];
                    @endphp
                    <div class="absolute top-2 right-2 {{ $colors[$index % 3] }} text-white px-1.5 py-0.5 sm:px-2 sm:py-1 rounded text-xs font-bold">{{ $badges[$index % 3] }}</div>
                    <div class="absolute bottom-2 left-2 bg-gray-900/80 text-purple-400 px-1.5 py-0.5 sm:px-2 sm:py-1 rounded text-xs font-bold">
                        <i data-feather="eye" class="w-3 h-3 inline mr-1"></i><span class="hidden sm:inline">{{ number_format($manhwa->views) }}</span><span class="sm:hidden">{{ $manhwa->views > 999 ? number_format($manhwa->views/1000, 1) . 'K' : $manhwa->views }}</span>
                    </div>
                </div>
                
                <div class="p-4">
                    <h3 class="font-bold text-sm mb-3 line-clamp-2 leading-tight text-white" title="{{ $manhwa->title }}">
                        {{ $manhwa->title }}
                    </h3>
                    
                    <a href="{{ route('manhwas.show', $manhwa) }}" class="w-full block py-2 bg-purple-500 hover:bg-purple-600 text-white font-medium rounded text-center text-sm transition-all">
                        Read Now
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

<!-- Genres Section -->
@if($genres->count() > 0)
<section class="py-16 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <h2 class="text-3xl font-bold font-orbitron mb-12 text-center purple-glow-text" data-aos="fade-up">EXPLORE <span class="text-purple-400">GENRES</span></h2>
        
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4">
            @foreach($genres as $index => $genre)
            <a href="{{ route('manhwas.index', ['genre' => $genre->slug]) }}" 
               class="holographic-card rounded-lg p-4 text-center transition-all duration-300 hover:scale-105" 
               data-aos="fade-up" data-aos-delay="{{ ($index % 10) * 50 }}">
                <h4 class="font-bold text-purple-300 mb-2">{{ $genre->name }}</h4>
                <small class="text-gray-400">{{ $genre->manhwas->count() }} titles</small>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($latestManhwas->count() === 0 && $popularManhwas->count() === 0)
<section class="py-20 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto text-center">
        <div class="holographic-card rounded-xl p-12" data-aos="fade-up">
            <i data-feather="book-open" class="w-24 h-24 text-purple-400 mx-auto mb-6"></i>
            <h3 class="text-3xl font-bold font-orbitron purple-glow-text mb-4">NO MANHWA AVAILABLE YET</h3>
            <p class="text-gray-300 text-lg mb-8">Upload some manhwa files to get started with your collection!</p>
            <div class="bg-gray-800/50 rounded-lg p-6 text-left">
                <h4 class="font-bold text-purple-400 mb-4">Quick Setup Guide:</h4>
                <ol class="text-sm text-gray-300 space-y-2 list-decimal list-inside">
                    <li>Create manhwa folders in <code class="bg-gray-700 px-2 py-1 rounded">storage/app/public/manhwa/chapters/</code></li>
                    <li>Add chapter images in numbered folders</li>
                    <li>Add cover images to <code class="bg-gray-700 px-2 py-1 rounded">storage/app/public/manhwa/covers/</code></li>
                    <li>Add manhwa data to the database</li>
                </ol>
            </div>
        </div>
    </div>
</section>
@endif
@endsection
