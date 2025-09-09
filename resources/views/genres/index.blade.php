@extends('layouts.futuristic')

@section('title', 'Browse Genres - HTech Manhwa')

@section('content')
<!-- Header Section -->
<section class="pt-32 pb-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto text-center" data-aos="fade-up">
        <h1 class="text-4xl font-bold font-orbitron purple-glow-text mb-4">MANHWA <span class="text-purple-400">GENRES</span></h1>
        <p class="text-gray-300 text-lg">Explore stories by category</p>
    </div>
</section>

<!-- Stats Section -->
<section class="pb-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="holographic-card rounded-xl p-6 text-center" data-aos="fade-up">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="text-center">
                    <h3 class="text-2xl font-bold purple-glow-text">{{ $genres->count() }}</h3>
                    <p class="text-gray-300">Total Genres</p>
                </div>
                <div class="text-center">
                    <h3 class="text-2xl font-bold purple-glow-text">{{ $genres->sum('manhwas_count') }}</h3>
                    <p class="text-gray-300">Total Manhwa</p>
                </div>
                <div class="text-center">
                    <h3 class="text-2xl font-bold purple-glow-text">{{ number_format($genres->avg('manhwas_count'), 1) }}</h3>
                    <p class="text-gray-300">Avg per Genre</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Genres Grid -->
<section class="pb-20 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        @if($genres->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($genres as $genre)
                    <div class="holographic-card rounded-xl overflow-hidden group hover:scale-105 transition-all duration-300" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 4) * 100 }}">
                        <div class="p-6">
                            <div class="text-center">
                                <!-- Genre Icon/Initial -->
                                <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-purple-500 to-purple-700 rounded-full flex items-center justify-center">
                                    <span class="text-2xl font-bold text-white font-orbitron">
                                        {{ substr($genre->name, 0, 1) }}
                                    </span>
                                </div>
                                
                                <!-- Genre Name -->
                                <h3 class="text-xl font-bold purple-glow-text mb-2 group-hover:text-purple-300 transition-colors">
                                    {{ $genre->name }}
                                </h3>
                                
                                <!-- Manhwa Count -->
                                <p class="text-gray-300 mb-4">
                                    {{ $genre->manhwas_count }} {{ Str::plural('manhwa', $genre->manhwas_count) }}
                                </p>
                                
                                @if($genre->description)
                                    <p class="text-gray-400 text-sm mb-4 line-clamp-2">
                                        {{ $genre->description }}
                                    </p>
                                @endif
                                
                                <!-- Browse Button -->
                                <a href="{{ route('genres.show', $genre) }}" 
                                   class="inline-flex items-center px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-all duration-300 group-hover:scale-105">
                                    <i data-feather="book-open" class="w-4 h-4 mr-2"></i>
                                    Browse
                                </a>
                            </div>
                        </div>
                        
                        <!-- Hover Effect Overlay -->
                        <div class="absolute inset-0 bg-gradient-to-t from-purple-900/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 pointer-events-none"></div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="text-center py-16">
                <div class="holographic-card rounded-xl p-16 mx-auto max-w-md">
                    <div class="mb-6">
                        <i data-feather="tag" class="w-20 h-20 text-gray-400 mx-auto mb-4"></i>
                        <h3 class="text-2xl font-bold font-orbitron text-purple-400 mb-2">No Genres Found</h3>
                        <p class="text-gray-400 mb-6">
                            No genres have been created yet. Genres will appear here once manhwa are added with genre classifications.
                        </p>
                    </div>
                    <a href="{{ route('manhwas.index') }}" class="px-6 py-3 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-all">
                        <i data-feather="arrow-left" class="inline w-5 h-5 mr-2"></i>Browse All Manhwa
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>

<!-- Quick Actions -->
<section class="pb-20 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="holographic-card rounded-xl p-6" data-aos="fade-up">
            <div class="flex flex-col md:flex-row items-center justify-between">
                <div class="mb-4 md:mb-0">
                    <h3 class="text-xl font-bold purple-glow-text mb-2">Explore More</h3>
                    <p class="text-gray-300">Discover manhwa through different ways</p>
                </div>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('manhwas.index') }}" 
                       class="px-6 py-3 bg-purple-500/20 hover:bg-purple-500/30 text-purple-300 border border-purple-500/30 rounded-lg transition-all">
                        <i data-feather="library" class="inline w-5 h-5 mr-2"></i>Browse All
                    </a>
                    <a href="{{ route('manhwas.index', ['sort' => 'popular']) }}" 
                       class="px-6 py-3 bg-purple-500/20 hover:bg-purple-500/30 text-purple-300 border border-purple-500/30 rounded-lg transition-all">
                        <i data-feather="trending-up" class="inline w-5 h-5 mr-2"></i>Popular
                    </a>
                    <a href="{{ route('manhwas.index', ['sort' => 'latest']) }}" 
                       class="px-6 py-3 bg-purple-500/20 hover:bg-purple-500/30 text-purple-300 border border-purple-500/30 rounded-lg transition-all">
                        <i data-feather="clock" class="inline w-5 h-5 mr-2"></i>Latest
                    </a>
                    <a href="{{ route('admin.index') }}" 
                       class="px-6 py-3 bg-gray-600/20 hover:bg-gray-600/30 text-gray-300 border border-gray-600/30 rounded-lg transition-all">
                        <i data-feather="settings" class="inline w-5 h-5 mr-2"></i>Admin
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
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
