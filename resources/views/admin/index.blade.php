@extends('layouts.futuristic')

@section('title', 'Admin Panel - HTech Manhwa')

@section('content')
<!-- Admin Header -->
<section class="pt-32 pb-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-8" data-aos="fade-up">
            <h1 class="text-4xl md:text-5xl font-bold font-orbitron purple-glow-text mb-4">
                ADMIN <span class="text-purple-400">PANEL</span>
            </h1>
            <p class="text-xl text-gray-300">Manage your manhwa collection and chapters</p>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex flex-wrap justify-center gap-4 mb-8" data-aos="fade-up" data-aos-delay="100">
            <a href="{{ route('admin.manhwa.create') }}" 
               class="px-6 py-3 bg-purple-500 hover:bg-purple-600 text-white font-bold rounded-lg transition-all transform hover:scale-105">
                <i data-feather="plus" class="inline w-5 h-5 mr-2"></i>Add New Manhwa
            </a>
        </div>
    </div>
</section>

<!-- Success/Error Messages -->
@if(session('success'))
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-8">
        <div class="holographic-card border-green-500/30 bg-green-500/10 rounded-xl p-4" data-aos="fade-down">
            <div class="flex items-center">
                <i data-feather="check-circle" class="w-6 h-6 text-green-400 mr-3"></i>
                <span class="text-green-300">{{ session('success') }}</span>
            </div>
        </div>
    </div>
@endif

<!-- Manhwa List -->
<section class="pb-20 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        @if($manhwas->count() > 0)
            <div class="grid gap-6">
                @foreach($manhwas as $manhwa)
                <div class="holographic-card rounded-xl overflow-hidden" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row gap-6">
                            <!-- Cover Image -->
                            <div class="lg:w-48 flex-shrink-0">
                                @if($manhwa->cover_image)
                                    <img src="{{ asset('storage/' . $manhwa->cover_image) }}" 
                                         class="w-full h-64 lg:h-72 object-cover rounded-lg" alt="{{ $manhwa->title }}">
                                @else
                                    <div class="w-full h-64 lg:h-72 bg-gray-700 rounded-lg flex items-center justify-center">
                                        <i data-feather="image" class="w-16 h-16 text-gray-400"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Manhwa Info -->
                            <div class="flex-1">
                                <div class="flex justify-between items-start mb-4">
                                    <h2 class="text-2xl font-bold font-orbitron text-purple-400">{{ $manhwa->title }}</h2>
                                    <div class="flex space-x-2">
                                        <a href="{{ route('manhwas.show', $manhwa) }}" 
                                           class="px-3 py-1 bg-blue-500/20 text-blue-300 border border-blue-500/30 rounded-lg text-sm hover:bg-blue-500/30 transition-all">
                                            <i data-feather="eye" class="inline w-4 h-4 mr-1"></i>View
                                        </a>
                                        <form action="{{ route('admin.manhwa.delete', $manhwa) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this manhwa and all its chapters?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" 
                                                    class="px-3 py-1 bg-red-500/20 text-red-300 border border-red-500/30 rounded-lg text-sm hover:bg-red-500/30 transition-all">
                                                <i data-feather="trash-2" class="inline w-4 h-4 mr-1"></i>Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                
                                <!-- Info Grid -->
                                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                                    <div class="text-center p-3 bg-purple-500/10 rounded-lg border border-purple-500/20">
                                        <p class="text-purple-400 font-semibold text-sm">Status</p>
                                        <p class="text-gray-300">{{ ucfirst($manhwa->status) }}</p>
                                    </div>
                                    <div class="text-center p-3 bg-purple-500/10 rounded-lg border border-purple-500/20">
                                        <p class="text-purple-400 font-semibold text-sm">Chapters</p>
                                        <p class="text-gray-300">{{ $manhwa->chapters->count() }}</p>
                                    </div>
                                    <div class="text-center p-3 bg-purple-500/10 rounded-lg border border-purple-500/20">
                                        <p class="text-purple-400 font-semibold text-sm">Views</p>
                                        <p class="text-gray-300">{{ number_format($manhwa->views) }}</p>
                                    </div>
                                    <div class="text-center p-3 bg-purple-500/10 rounded-lg border border-purple-500/20">
                                        <p class="text-purple-400 font-semibold text-sm">Genres</p>
                                        <p class="text-gray-300">{{ $manhwa->genres->count() }}</p>
                                    </div>
                                </div>
                                
                                <!-- Description -->
                                @if($manhwa->description)
                                <p class="text-gray-300 mb-4 line-clamp-3">{{ $manhwa->description }}</p>
                                @endif
                                
                                <!-- Chapter Actions -->
                                <div class="flex flex-wrap gap-2">
                                    <a href="{{ route('admin.chapter.create', $manhwa) }}" 
                                       class="px-4 py-2 bg-green-500/20 text-green-300 border border-green-500/30 rounded-lg text-sm hover:bg-green-500/30 transition-all">
                                        <i data-feather="plus" class="inline w-4 h-4 mr-1"></i>Add Chapter
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Chapters List -->
                        @if($manhwa->chapters->count() > 0)
                        <div class="mt-6 pt-6 border-t border-gray-700">
                            <h3 class="text-lg font-semibold text-purple-400 mb-3">
                                <i data-feather="list" class="inline w-5 h-5 mr-2"></i>Chapters ({{ $manhwa->chapters->count() }})
                            </h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                @foreach($manhwa->chapters->sortBy('chapter_number') as $chapter)
                                <div class="flex justify-between items-center p-3 bg-gray-800/50 rounded-lg border border-gray-700">
                                    <div>
                                        <p class="text-gray-200 font-medium">Ch. {{ $chapter->chapter_number }}: {{ $chapter->title }}</p>
                                        <p class="text-sm text-gray-400">{{ $chapter->page_count }} pages • {{ number_format($chapter->views) }} views</p>
                                    </div>
                                    <div class="flex space-x-1">
                                        <a href="{{ route('chapters.show', [$manhwa, $chapter]) }}" 
                                           class="p-1 text-blue-400 hover:text-blue-300" title="View Chapter">
                                            <i data-feather="eye" class="w-4 h-4"></i>
                                        </a>
                                        <form action="{{ route('admin.chapter.delete', [$manhwa, $chapter]) }}" method="POST" 
                                              onsubmit="return confirm('Are you sure you want to delete this chapter?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1 text-red-400 hover:text-red-300" title="Delete Chapter">
                                                <i data-feather="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
        @else
            <div class="holographic-card rounded-xl text-center py-16" data-aos="fade-up">
                <div class="mb-6">
                    <i data-feather="inbox" class="w-20 h-20 text-gray-400 mx-auto mb-4"></i>
                    <h3 class="text-2xl font-bold font-orbitron text-purple-400 mb-2">No Manhwa Found</h3>
                    <p class="text-gray-400 mb-6">Start building your collection by adding your first manhwa.</p>
                    <a href="{{ route('admin.manhwa.create') }}" 
                       class="px-6 py-3 bg-purple-500 hover:bg-purple-600 text-white font-bold rounded-lg transition-all transform hover:scale-105">
                        <i data-feather="plus" class="inline w-5 h-5 mr-2"></i>Add First Manhwa
                    </a>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
