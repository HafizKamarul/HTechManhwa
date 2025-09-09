@extends('layouts.futuristic')

@section('title', $manhwa->title . ' - Chapter ' . $chapter->chapter_number . ' - HTech Manhwa')

@section('content')
<!-- Scroll Progress Bar -->
<div class="scroll-progress-container">
    <div class="scroll-progress"></div>
</div>

<!-- Chapter Header -->
<section class="pt-20 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">
        <!-- Breadcrumb -->
        <nav class="mb-6" data-aos="fade-down">
            <div class="flex items-center space-x-2 text-sm">
                <a href="{{ route('home') }}" class="text-purple-400 hover:text-purple-300">Home</a>
                <i data-feather="chevron-right" class="w-4 h-4 text-gray-400"></i>
                <a href="{{ route('manhwas.show', $manhwa) }}" class="text-purple-400 hover:text-purple-300">{{ $manhwa->title }}</a>
                <i data-feather="chevron-right" class="w-4 h-4 text-gray-400"></i>
                <span class="text-gray-300">Chapter {{ $chapter->chapter_number }}</span>
            </div>
        </nav>
        
        <!-- Chapter Info -->
        <div class="holographic-card rounded-xl p-6 mb-8" data-aos="fade-up">
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between">
                <div class="mb-4 md:mb-0">
                    <h1 class="text-2xl md:text-3xl font-bold font-orbitron purple-glow-text mb-2">{{ $manhwa->title }}</h1>
                    <p class="text-gray-300">
                        Chapter {{ $chapter->chapter_number }}: {{ $chapter->title }}
                        @if($chapter->page_count > 0)
                            • {{ $chapter->page_count }} pages
                        @endif
                    </p>
                </div>
                
                <!-- Navigation Controls -->
                <div class="flex flex-wrap gap-2">
                    <!-- Previous Chapter -->
                    @if($previousChapter)
                        <a href="{{ route('chapters.show', [$manhwa, $previousChapter]) }}" 
                           class="px-4 py-2 bg-purple-500/20 hover:bg-purple-500/30 text-purple-300 border border-purple-500/30 rounded-lg transition-all">
                            <i data-feather="chevron-left" class="inline w-4 h-4 mr-1"></i>Previous
                        </a>
                    @else
                        <button class="px-4 py-2 bg-gray-600/20 text-gray-500 border border-gray-600/30 rounded-lg cursor-not-allowed">
                            <i data-feather="chevron-left" class="inline w-4 h-4 mr-1"></i>Previous
                        </button>
                    @endif
                    
                    <!-- Back to Manhwa -->
                    <a href="{{ route('manhwas.show', $manhwa) }}" 
                       class="px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-all">
                        <i data-feather="list" class="inline w-4 h-4 mr-1"></i>Chapters
                    </a>
                    
                    <!-- Next Chapter -->
                    @if($nextChapter)
                        <a href="{{ route('chapters.show', [$manhwa, $nextChapter]) }}" 
                           class="px-4 py-2 bg-purple-500/20 hover:bg-purple-500/30 text-purple-300 border border-purple-500/30 rounded-lg transition-all">
                            Next<i data-feather="chevron-right" class="inline w-4 h-4 ml-1"></i>
                        </a>
                    @else
                        <button class="px-4 py-2 bg-gray-600/20 text-gray-500 border border-gray-600/30 rounded-lg cursor-not-allowed">
                            Next<i data-feather="chevron-right" class="inline w-4 h-4 ml-1"></i>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Chapter Pages -->
<section class="pb-20 px-0">
    <div class="max-w-4xl mx-auto">
        @if(count($images) > 0)
            <div class="chapter-reader">
                @foreach($images as $index => $image)
                <div class="chapter-page-container" data-aos="fade-up" data-aos-delay="{{ ($index % 3) * 100 }}">
                    <img src="{{ $image }}" 
                         class="chapter-page w-full block" 
                         alt="Page {{ $index + 1 }}">
                </div>
                @endforeach
            </div>
            <!-- End of Chapter Message -->
            <div class="holographic-card rounded-xl p-8 mt-12 mx-4 text-center" data-aos="fade-up">
                <h3 class="text-2xl font-bold font-orbitron purple-glow-text mb-4">
                    End of Chapter {{ $chapter->chapter_number }}
                </h3>
                <p class="text-gray-300 mb-6">Thank you for reading!</p>
                
                <div class="flex flex-wrap justify-center gap-4">
                    @if($previousChapter)
                        <a href="{{ route('chapters.show', [$manhwa, $previousChapter]) }}" 
                           class="px-6 py-3 bg-purple-500/20 hover:bg-purple-500/30 text-purple-300 border border-purple-500/30 rounded-lg transition-all">
                            <i data-feather="chevron-left" class="inline w-5 h-5 mr-2"></i>Previous Chapter
                        </a>
                    @endif
                    
                    <a href="{{ route('manhwas.show', $manhwa) }}" 
                       class="px-6 py-3 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-all">
                        <i data-feather="list" class="inline w-5 h-5 mr-2"></i>All Chapters
                    </a>
                    
                    @if($nextChapter)
                        <a href="{{ route('chapters.show', [$manhwa, $nextChapter]) }}" 
                           class="px-6 py-3 bg-purple-500/20 hover:bg-purple-500/30 text-purple-300 border border-purple-500/30 rounded-lg transition-all">
                            Next Chapter<i data-feather="chevron-right" class="inline w-5 h-5 ml-2"></i>
                        </a>
                    @endif
                </div>
            </div>
        @else
            <div class="holographic-card rounded-xl p-16 mx-4 text-center" data-aos="fade-up">
                <div class="mb-6">
                    <i data-feather="image" class="w-20 h-20 text-gray-400 mx-auto mb-4"></i>
                    <h3 class="text-2xl font-bold font-orbitron text-purple-400 mb-2">No Images Found</h3>
                    <p class="text-gray-400 mb-4">
                        This chapter doesn't have any images yet.<br>
                        <code class="text-sm bg-gray-800 px-2 py-1 rounded">storage/app/public/manhwa/chapters/{{ $chapter->folder_path }}</code>
                    </p>
                </div>
                <a href="{{ route('manhwas.show', $manhwa) }}" 
                   class="px-6 py-3 bg-purple-500 hover:bg-purple-600 text-white rounded-lg transition-all">
                    <i data-feather="arrow-left" class="inline w-5 h-5 mr-2"></i>Back to Manhwa
                </a>
            </div>
        @endif
    </div>
</section>

<!-- Floating Navigation -->
<div class="chapter-navigation">
    <div class="holographic-card rounded-lg p-2 flex flex-col space-y-2">
        @if($previousChapter)
            <a href="{{ route('chapters.show', [$manhwa, $previousChapter]) }}" 
               class="p-2 text-purple-400 hover:text-purple-300 hover:bg-purple-500/20 rounded transition-all" 
               title="Previous Chapter">
                <i data-feather="chevron-up" class="w-5 h-5"></i>
            </a>
        @endif
        
        <a href="{{ route('manhwas.show', $manhwa) }}" 
           class="p-2 text-purple-400 hover:text-purple-300 hover:bg-purple-500/20 rounded transition-all" 
           title="All Chapters">
            <i data-feather="list" class="w-5 h-5"></i>
        </a>
        
        @if($nextChapter)
            <a href="{{ route('chapters.show', [$manhwa, $nextChapter]) }}" 
               class="p-2 text-purple-400 hover:text-purple-300 hover:bg-purple-500/20 rounded transition-all" 
               title="Next Chapter">
                <i data-feather="chevron-down" class="w-5 h-5"></i>
            </a>
        @endif
    </div>
</div>
@endsection

@push('styles')
<!-- Infinite Scroll CSS -->
<style>
    /* Scroll progress bar */
    .scroll-progress-container {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 3px;
        background: rgba(0, 0, 0, 0.3);
        z-index: 100;
    }
    
    .scroll-progress {
        height: 100%;
        background: linear-gradient(90deg, #a855f7, #ec4899);
        width: 0%;
        transition: width 0.1s ease;
    }
</style>
@endpush

@push('scripts')
<!-- Lenis CDN -->
<script src="https://cdn.jsdelivr.net/npm/lenis@1.3.11/dist/lenis.min.js"></script>
<script>
// Lenis Smooth Scrolling Implementation
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on a chapter page
    const chapterReader = document.querySelector('.chapter-reader');
    const chapterPages = document.querySelectorAll('.chapter-page');
    
    if (chapterReader && chapterPages.length > 0) {
        console.log('Initializing Lenis smooth scrolling for chapter page...');
        
        // Initialize Lenis with proper configuration
        const lenis = new Lenis({
            duration: 1.2,                    
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)),
            direction: 'vertical',
            gestureDirection: 'vertical',
            smooth: true,                     
            smoothTouch: false,               
            touchMultiplier: 2,               
            wheelMultiplier: 1.5,             
            infinite: false,
            autoResize: true,
        });

        // Animation frame loop
        function raf(time) {
            lenis.raf(time);
            requestAnimationFrame(raf);
        }
        requestAnimationFrame(raf);

        // Scroll progress indicator
        lenis.on('scroll', (e) => {
            const progress = e.progress;
            const progressBar = document.querySelector('.scroll-progress');
            if (progressBar) {
                progressBar.style.width = `${progress * 100}%`;
            }
        });

        // Enhanced keyboard navigation
        document.addEventListener('keydown', function(e) {
            // Skip if user is typing in an input
            if (e.target.tagName.toLowerCase() === 'input' || 
                e.target.tagName.toLowerCase() === 'textarea') {
                return;
            }
            
            switch(e.key) {
                case 'ArrowDown':
                case ' ': // Spacebar
                    e.preventDefault();
                    lenis.scrollTo(window.scrollY + window.innerHeight * 0.8, { duration: 1.0 });
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    lenis.scrollTo(window.scrollY - window.innerHeight * 0.8, { duration: 1.0 });
                    break;
                case 'Home':
                    e.preventDefault();
                    lenis.scrollTo(0, { duration: 1.5 });
                    break;
                case 'End':
                    e.preventDefault();
                    lenis.scrollTo(document.body.scrollHeight, { duration: 1.5 });
                    break;
            }
        });

        console.log('✅ Lenis smooth scrolling initialized successfully!');
        window.lenis = lenis;
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        if (e.target.tagName.toLowerCase() === 'input' || e.target.tagName.toLowerCase() === 'textarea') {
            return; // Don't interfere with form inputs
        }
        
        switch(e.key) {
            case 'ArrowLeft':
                @if($previousChapter)
                    window.location.href = "{{ route('chapters.show', [$manhwa, $previousChapter]) }}";
                @endif
                break;
            case 'ArrowRight':
                @if($nextChapter)
                    window.location.href = "{{ route('chapters.show', [$manhwa, $nextChapter]) }}";
                @endif
                break;
            case 'Escape':
                window.location.href = "{{ route('manhwas.show', $manhwa) }}";
                break;
        }
    });
    
    // Auto-scroll for better reading experience
    let isScrolling = false;
    
    function smoothScroll() {
        if (!isScrolling) {
            isScrolling = true;
            window.scrollBy({
                top: window.innerHeight * 0.8,
                behavior: 'smooth'
            });
            setTimeout(() => { isScrolling = false; }, 800);
        }
    }
    
    // Click on page to scroll
    document.querySelectorAll('.chapter-page').forEach(img => {
        img.addEventListener('click', function(e) {
            if (e.clientX > this.offsetWidth / 2) {
                smoothScroll();
            }
        });
    });
    
    // Mobile swipe navigation
    let touchStartX = 0;
    let touchStartY = 0;
    
    document.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
        touchStartY = e.changedTouches[0].screenY;
    }, { passive: true });
    
    document.addEventListener('touchend', function(e) {
        const touchEndX = e.changedTouches[0].screenX;
        const touchEndY = e.changedTouches[0].screenY;
        const diffX = touchStartX - touchEndX;
        const diffY = touchStartY - touchEndY;
        
        // Only trigger if horizontal swipe is more significant than vertical
        if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
            if (diffX > 0) {
                // Swipe left - next chapter
                @if($nextChapter)
                    window.location.href = "{{ route('chapters.show', [$manhwa, $nextChapter]) }}";
                @endif
            } else {
                // Swipe right - previous chapter
                @if($previousChapter)
                    window.location.href = "{{ route('chapters.show', [$manhwa, $previousChapter]) }}";
                @endif
            }
        }
    }, { passive: true });
});
</script>
@endpush
