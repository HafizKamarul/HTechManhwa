@extends('layouts.futuristic')

@section('title', 'Add Chapter - ' . $manhwa->title)

@section('content')
<section class="pt-32 pb-20 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">
        <div class="text-center mb-8" data-aos="fade-up">
            <h1 class="text-3xl md:text-4xl font-bold font-orbitron purple-glow-text mb-2">
                ADD <span class="text-purple-400">CHAPTER</span>
            </h1>
            <p class="text-gray-300">to <span class="text-purple-400">{{ $manhwa->title }}</span></p>
        </div>
        
        <!-- Upload Method Tabs -->
        <div class="flex justify-center mb-8" data-aos="fade-up" data-aos-delay="50">
            <div class="holographic-card rounded-lg p-1 inline-flex">
                <button onclick="showIndividualUpload()" id="individual-tab" 
                        class="px-6 py-2 rounded-lg bg-purple-500 text-white font-semibold transition-all">
                    Individual Images
                </button>
                <button onclick="showZipUpload()" id="zip-tab" 
                        class="px-6 py-2 rounded-lg text-gray-300 hover:text-white font-semibold transition-all">
                    ZIP File
                </button>
            </div>
        </div>
        
        <!-- Individual Images Upload -->
        <div id="individual-upload" class="holographic-card rounded-xl p-8" data-aos="fade-up" data-aos-delay="100">
            <h2 class="text-xl font-bold text-purple-400 mb-6">Upload Individual Images</h2>
            <form action="{{ route('admin.chapter.store', $manhwa) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Chapter Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="title" class="block text-purple-400 font-semibold mb-2">Chapter Title *</label>
                        <input type="text" id="title" name="title" value="{{ old('title') }}" required
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        @error('title')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    
                    <div>
                        <label for="chapter_number" class="block text-purple-400 font-semibold mb-2">Chapter Number *</label>
                        <input type="number" id="chapter_number" name="chapter_number" 
                               value="{{ old('chapter_number', $manhwa->chapters->max('chapter_number') + 1) }}" 
                               step="0.1" min="0" required
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                        @error('chapter_number')
                            <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                
                <!-- Chapter Images -->
                <div>
                    <label for="chapter_images" class="block text-purple-400 font-semibold mb-2">Chapter Images *</label>
                    <input type="file" id="chapter_images" name="chapter_images[]" multiple accept="image/*" required
                           class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    <div class="mt-3 p-4 bg-yellow-500/10 border border-yellow-500/30 rounded-lg">
                        <h4 class="text-yellow-400 font-semibold mb-2">Upload Limits:</h4>
                        <ul class="text-gray-300 text-sm space-y-1">
                            <li>• <strong>Max files per upload:</strong> {{ ini_get('max_file_uploads') }} files</li>
                            <li>• <strong>Max file size:</strong> {{ ini_get('upload_max_filesize') }}</li>
                            <li>• <strong>Max total size:</strong> {{ ini_get('post_max_size') }}</li>
                            @if(ini_get('max_file_uploads') <= 20)
                                <li class="text-yellow-300">⚠️ <strong>For chapters with 20+ pages, use ZIP upload method below</strong></li>
                            @endif
                        </ul>
                    </div>
                    <p class="text-gray-400 text-sm mt-1">Select all chapter pages in order. JPG, PNG, WebP supported. Use original resolution for best quality.</p>
                    @error('chapter_images')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Image Preview -->
                <div id="image-preview" class="hidden">
                    <h3 class="text-purple-400 font-semibold mb-3">Selected Images:</h3>
                    <div id="preview-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4"></div>
                </div>
                
                <!-- Submit Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                    <button type="submit" 
                            class="px-8 py-3 bg-purple-500 hover:bg-purple-600 text-white font-bold rounded-lg transition-all transform hover:scale-105">
                        <i data-feather="upload" class="inline w-5 h-5 mr-2"></i>Upload Chapter
                    </button>
                    <a href="{{ route('admin.index') }}" 
                       class="px-8 py-3 border border-gray-600 text-gray-300 hover:border-purple-400 hover:text-purple-400 font-bold rounded-lg transition-all text-center">
                        <i data-feather="arrow-left" class="inline w-5 h-5 mr-2"></i>Back to Admin
                    </a>
                </div>
            </form>
        </div>
        
        <!-- ZIP Upload -->
        <div id="zip-upload" class="holographic-card rounded-xl p-8 hidden" data-aos="fade-up" data-aos-delay="100">
            <h2 class="text-xl font-bold text-purple-400 mb-6">Upload ZIP File</h2>
            <form action="{{ route('admin.chapter.upload-zip', $manhwa) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf
                
                <!-- Chapter Info -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="zip_title" class="block text-purple-400 font-semibold mb-2">Chapter Title *</label>
                        <input type="text" id="zip_title" name="title" value="{{ old('title') }}" required
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                    
                    <div>
                        <label for="zip_chapter_number" class="block text-purple-400 font-semibold mb-2">Chapter Number *</label>
                        <input type="number" id="zip_chapter_number" name="chapter_number" 
                               value="{{ old('chapter_number', $manhwa->chapters->max('chapter_number') + 1) }}" 
                               step="0.1" min="0" required
                               class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    </div>
                </div>
                
                <!-- ZIP File -->
                <div>
                    <label for="chapter_zip" class="block text-purple-400 font-semibold mb-2">Chapter ZIP File *</label>
                    <input type="file" id="chapter_zip" name="chapter_zip" accept=".zip" required
                           class="w-full px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-gray-100 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                    
                    <!-- File Info Display -->
                    <div id="zip-file-info" class="hidden mt-2 p-3 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                        <div class="text-blue-400 text-sm">
                            <strong>Selected file:</strong> <span id="zip-filename"></span><br>
                            <strong>Size:</strong> <span id="zip-filesize"></span><br>
                            <strong>Status:</strong> <span id="zip-status" class="text-green-400">Ready to upload</span>
                        </div>
                    </div>
                    
                    <div class="mt-3 p-4 bg-blue-500/10 border border-blue-500/30 rounded-lg">
                        <h4 class="text-blue-400 font-semibold mb-2">ZIP File Requirements:</h4>
                        <ul class="text-gray-300 text-sm space-y-1">
                            <li>• Contains only image files (JPG, JPEG, PNG, WebP)</li>
                            <li>• Images should be named in order (001.jpg, 002.jpg, etc.)</li>
                            <li>• Maximum file size: 40MB</li>
                            <li>• Images will be automatically renamed and organized</li>
                            <li>• Put images in root of ZIP (not in subfolders)</li>
                            <li>• <strong>For best quality:</strong> Use original resolution, avoid pre-compression</li>
                        </ul>
                    </div>
                    @error('chapter_zip')
                        <p class="text-red-400 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                
                <!-- Submit Buttons -->
                <div class="flex flex-col sm:flex-row gap-4 pt-6">
                    <button type="submit" id="zip-submit-btn"
                            class="px-8 py-3 bg-purple-500 hover:bg-purple-600 text-white font-bold rounded-lg transition-all transform hover:scale-105">
                        <i data-feather="upload" class="inline w-5 h-5 mr-2"></i>Upload ZIP Chapter
                    </button>
                    <a href="{{ route('admin.index') }}" 
                       class="px-8 py-3 border border-gray-600 text-gray-300 hover:border-purple-400 hover:text-purple-400 font-bold rounded-lg transition-all text-center">
                        <i data-feather="arrow-left" class="inline w-5 h-5 mr-2"></i>Back to Admin
                    </a>
                </div>
                
                <!-- Upload Progress -->
                <div id="zip-upload-progress" class="hidden mt-6">
                    <div class="bg-gray-700 rounded-lg p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-purple-400 font-semibold">Uploading ZIP file...</span>
                            <span class="text-gray-300 text-sm">This may take a while</span>
                        </div>
                        <div class="w-full bg-gray-600 rounded-full h-2">
                            <div class="bg-purple-500 h-2 rounded-full animate-pulse" style="width: 50%;"></div>
                        </div>
                        <p class="text-gray-400 text-xs mt-2">Please don't close this page while uploading.</p>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
@endsection

@push('scripts')
<script>
function showIndividualUpload() {
    document.getElementById('individual-upload').classList.remove('hidden');
    document.getElementById('zip-upload').classList.add('hidden');
    document.getElementById('individual-tab').classList.add('bg-purple-500', 'text-white');
    document.getElementById('individual-tab').classList.remove('text-gray-300');
    document.getElementById('zip-tab').classList.remove('bg-purple-500', 'text-white');
    document.getElementById('zip-tab').classList.add('text-gray-300');
}

function showZipUpload() {
    document.getElementById('zip-upload').classList.remove('hidden');
    document.getElementById('individual-upload').classList.add('hidden');
    document.getElementById('zip-tab').classList.add('bg-purple-500', 'text-white');
    document.getElementById('zip-tab').classList.remove('text-gray-300');
    document.getElementById('individual-tab').classList.remove('bg-purple-500', 'text-white');
    document.getElementById('individual-tab').classList.add('text-gray-300');
}

// Image preview functionality
document.getElementById('chapter_images').addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const previewContainer = document.getElementById('preview-container');
    const imagePreview = document.getElementById('image-preview');
    const maxFiles = {{ ini_get('max_file_uploads') }};
    
    previewContainer.innerHTML = '';
    
    if (files.length > maxFiles) {
        alert(`⚠️ Warning: You selected ${files.length} files, but your server limit is ${maxFiles} files.\n\nOnly the first ${maxFiles} files will be uploaded.\n\nFor chapters with ${maxFiles}+ pages, please use the ZIP upload method instead.`);
    }
    
    if (files.length > 0) {
        imagePreview.classList.remove('hidden');
        
        files.forEach((file, index) => {
            if (index >= maxFiles) return; // Don't preview files that won't be uploaded
            
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'relative group';
                
                let statusClass = index < maxFiles ? '' : ' opacity-50';
                let statusText = index < maxFiles ? `Page ${index + 1}` : 'Won\'t upload';
                
                div.innerHTML = `
                    <img src="${e.target.result}" class="w-full h-24 object-cover rounded-lg border border-gray-600${statusClass}">
                    <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity rounded-lg">
                        <span class="text-white text-sm font-bold">${statusText}</span>
                    </div>
                `;
                previewContainer.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    } else {
        imagePreview.classList.add('hidden');
    }
});

// ZIP file selection handler
document.getElementById('chapter_zip').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const fileInfo = document.getElementById('zip-file-info');
    
    if (file) {
        const fileName = document.getElementById('zip-filename');
        const fileSize = document.getElementById('zip-filesize');
        const status = document.getElementById('zip-status');
        
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        
        // Validate file
        if (file.size > 40 * 1024 * 1024) { // 40MB
            status.textContent = 'File too large (max 40MB)';
            status.className = 'text-red-400';
        } else if (!file.name.toLowerCase().endsWith('.zip')) {
            status.textContent = 'Invalid file type (must be ZIP)';
            status.className = 'text-red-400';
        } else {
            status.textContent = 'Ready to upload';
            status.className = 'text-green-400';
        }
        
        fileInfo.classList.remove('hidden');
    } else {
        fileInfo.classList.add('hidden');
    }
});

function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

// ZIP upload progress
document.getElementById('zip-upload').querySelector('form').addEventListener('submit', function(e) {
    const zipFile = document.getElementById('chapter_zip').files[0];
    const progress = document.getElementById('zip-upload-progress');
    const submitBtn = document.getElementById('zip-submit-btn');
    
    if (zipFile) {
        // Show progress
        progress.classList.remove('hidden');
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i data-feather="loader" class="inline w-5 h-5 mr-2 animate-spin"></i>Uploading...';
        
        // Re-initialize feather icons for the new icon
        setTimeout(() => feather.replace(), 100);
    }
});
</script>
@endpush
