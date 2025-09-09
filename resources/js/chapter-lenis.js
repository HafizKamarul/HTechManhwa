import Lenis from 'lenis';

// Initialize Lenis for chapter pages only
document.addEventListener('DOMContentLoaded', function() {
    // Check if we're on a chapter page by looking for chapter-specific elements
    const chapterReader = document.querySelector('.chapter-reader');
    const chapterPages = document.querySelectorAll('.chapter-page');
    
    if (chapterReader && chapterPages.length > 0) {
        console.log('Initializing Lenis smooth scrolling for chapter page...');
        
        // Configure Lenis with proper smooth scrolling
        const lenis = new Lenis({
            duration: 1.2,                    // Smooth scrolling duration
            easing: (t) => Math.min(1, 1.001 - Math.pow(2, -10 * t)), // Exponential easing
            direction: 'vertical',
            gestureDirection: 'vertical',
            smooth: true,                     // Enable smooth scrolling
            smoothTouch: false,               // Keep native touch scrolling for mobile
            touchMultiplier: 2,               // Enhanced touch sensitivity
            wheelMultiplier: 1.5,             // Enhanced wheel scrolling
            infinite: false,
            autoResize: true,
        });

        // Animation frame loop for smooth scrolling
        function raf(time) {
            lenis.raf(time);
            requestAnimationFrame(raf);
        }
        requestAnimationFrame(raf);

        // Scroll progress indicator
        lenis.on('scroll', (e) => {
            const progress = e.progress;
            
            // Update progress bar
            const progressBar = document.querySelector('.scroll-progress');
            if (progressBar) {
                progressBar.style.width = `${progress * 100}%`;
            }
        });

        // Enhanced keyboard navigation with smooth scrolling
        document.addEventListener('keydown', function(e) {
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

        console.log('Lenis smooth scrolling initialized for chapter page');
        
        // Expose lenis instance globally for debugging
        window.lenis = lenis;
    }
});
