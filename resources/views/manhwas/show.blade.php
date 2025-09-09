@extends('layouts.futuristic')

@section('title', $manhwa->title . ' - HTech Manhwa')

@section('content')
<!-- Manhwa Details Section -->
<section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cover Image -->
            <div class="lg:col-span-1" data-aos="fade-right">
                <div class="holographic-card rounded-xl overflow-hidden h-fit">
                    @if($manhwa->cover_image)
                        <img src="{{ asset('storage/' . $manhwa->cover_image) }}" 
                             class="w-full h-full object-cover" alt="{{ $manhwa->title }}" 
                             style="aspect-ratio: 3/4; min-height: 500px;">
                    @else
                        <div class="w-full h-full bg-gray-700 flex items-center justify-center" 
                             style="aspect-ratio: 3/4; min-height: 500px;">
                            <i data-feather="image" class="w-20 h-20 text-gray-400"></i>
                        </div>
                    @endif
                </div>
            </div>
            
            <!-- Manhwa Info -->
            <div class="lg:col-span-2" data-aos="fade-left">
                <div class="holographic-card rounded-xl p-6">
                    <!-- Title and Status -->
                    <div class="flex justify-between items-start mb-6">
                        <h1 class="text-3xl md:text-4xl font-bold font-orbitron purple-glow-text">{{ $manhwa->title }}</h1>
                        <span class="px-3 py-1 rounded-full text-sm font-bold
                            @if($manhwa->status === 'completed') bg-green-500/20 text-green-400 border border-green-500/30
                            @elseif($manhwa->status === 'ongoing') bg-blue-500/20 text-blue-400 border border-blue-500/30
                            @elseif($manhwa->status === 'hiatus') bg-yellow-500/20 text-yellow-400 border border-yellow-500/30
                            @else bg-red-500/20 text-red-400 border border-red-500/30
                            @endif">
                            {{ ucfirst($manhwa->status) }}
                        </span>
                    </div>
                    
                    
                    <!-- Author, Artist, Year and Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                        @if($manhwa->author)
                            <div class="text-center p-3 bg-purple-500/10 rounded-lg border border-purple-500/20">
                                <p class="text-purple-400 font-semibold">Author</p>
                                <p class="text-gray-300">{{ $manhwa->author }}</p>
                            </div>
                        @endif
                        @if($manhwa->artist)
                            <div class="text-center p-3 bg-purple-500/10 rounded-lg border border-purple-500/20">
                                <p class="text-purple-400 font-semibold">Artist</p>
                                <p class="text-gray-300">{{ $manhwa->artist }}</p>
                            </div>
                        @endif
                        @if($manhwa->year)
                            <div class="text-center p-3 bg-purple-500/10 rounded-lg border border-purple-500/20">
                                <p class="text-purple-400 font-semibold">Year</p>
                                <p class="text-gray-300">{{ $manhwa->year }}</p>
                            </div>
                        @endif
                        <div class="text-center p-3 bg-purple-500/10 rounded-lg border border-purple-500/20">
                            <p class="text-purple-400 font-semibold">Views</p>
                            <p class="text-gray-300">{{ number_format($manhwa->views) }}</p>
                        </div>
                        @if($manhwa->rating > 0)
                            <div class="text-center p-3 bg-purple-500/10 rounded-lg border border-purple-500/20">
                                <p class="text-purple-400 font-semibold">Rating</p>
                                <p class="text-gray-300">
                                    <i data-feather="star" class="inline w-4 h-4 text-yellow-400"></i>
                                    {{ number_format($manhwa->rating, 1) }}
                                    <span class="text-sm">({{ number_format($manhwa->rating_count) }})</span>
                                </p>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Genres -->
                    @if($manhwa->genres->count() > 0)
                    <div class="mb-6">
                        <h3 class="text-purple-400 font-semibold mb-3">Genres</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($manhwa->genres as $genre)
                                <a href="{{ route('manhwas.index', ['genre' => $genre->slug]) }}" 
                                   class="px-3 py-1 bg-purple-500/20 text-purple-300 rounded-full text-sm hover:bg-purple-500/30 transition-colors border border-purple-500/30">
                                    {{ $genre->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <!-- Description -->
                    @if($manhwa->description)
                    <div class="mb-6">
                        <h3 class="text-purple-400 font-semibold mb-3">Description</h3>
                        <p class="text-gray-300 leading-relaxed">{{ $manhwa->description }}</p>
                    </div>
                    @endif
                    
                    <!-- Quick Actions -->
                    @if($manhwa->chapters->count() > 0)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <a href="{{ route('chapters.show', [$manhwa, $manhwa->chapters->first()]) }}" 
                           class="px-6 py-3 bg-purple-500 hover:bg-purple-600 text-white font-bold rounded-lg transition-all transform hover:scale-105 text-center">
                            <i data-feather="play" class="inline w-5 h-5 mr-2"></i>Start Reading
                        </a>
                        <a href="{{ route('chapters.show', [$manhwa, $manhwa->chapters->last()]) }}" 
                           class="px-6 py-3 border border-purple-400 text-purple-400 hover:bg-purple-400/10 font-bold rounded-lg transition-all transform hover:scale-105 text-center">
                            <i data-feather="fast-forward" class="inline w-5 h-5 mr-2"></i>Latest Chapter
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Chapters List -->
@if($manhwa->chapters->count() > 0)
<section class="pb-20 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="holographic-card rounded-xl overflow-hidden" data-aos="fade-up">
            <div class="bg-purple-500/10 border-b border-purple-500/20 px-6 py-4">
                <h3 class="text-xl font-bold font-orbitron text-purple-400">
                    <i data-feather="list" class="inline w-5 h-5 mr-2"></i>CHAPTERS ({{ $manhwa->chapters->count() }})
                </h3>
            </div>
            <div class="chapter-list">
                @foreach($manhwa->chapters as $chapter)
                <a href="{{ route('chapters.show', [$manhwa, $chapter]) }}" 
                   class="block px-6 py-4 border-b border-gray-800 hover:bg-purple-500/5 transition-all duration-300">
                    <div class="flex justify-between items-center">
                        <div>
                            <h6 class="text-gray-200 font-semibold mb-1">
                                Chapter {{ $chapter->chapter_number }}: {{ $chapter->title }}
                            </h6>
                            <div class="flex items-center text-sm text-gray-400">
                                @if($chapter->page_count > 0)
                                    <span>{{ $chapter->page_count }} pages</span>
                                    <span class="mx-2">•</span>
                                @endif
                                <span>{{ $chapter->published_at ? $chapter->published_at->format('M j, Y') : 'No date' }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            @if($chapter->views > 0)
                                <div class="text-sm text-gray-400 mb-1">
                                    <i data-feather="eye" class="inline w-4 h-4 mr-1"></i>{{ number_format($chapter->views) }}
                                </div>
                            @endif
                            <i data-feather="chevron-right" class="w-5 h-5 text-purple-400"></i>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
</section>
@else
<section class="pb-20 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="holographic-card rounded-xl text-center py-16" data-aos="fade-up">
            <div class="mb-6">
                <i data-feather="inbox" class="w-20 h-20 text-gray-400 mx-auto mb-4"></i>
                <h3 class="text-2xl font-bold font-orbitron text-purple-400 mb-2">No Chapters Available</h3>
                <p class="text-gray-400">This manhwa doesn't have any chapters yet.</p>
            </div>
        </div>
    </div>
</section>
@endif
@endsection
