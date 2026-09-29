@extends('layouts.app')

@section('title', 'Free AI Image-to-Image Transformer (Styles & Art Remix) - AI Price Compare')
@section('meta_description', 'Transform any image into 3D Pixar, Cyberpunk Neon, Pencil Sketch, or Oil Painting style for free. Upload source photo, select AI art style, and download transformed image.')

@section('content')

<div style="max-width: 960px; margin: 0 auto; padding-bottom: 3rem;">

    <!-- Page Header -->
    <div style="text-align: center; margin-bottom: 2.5rem;">
        <span class="badge badge-info" style="margin-bottom: 0.75rem; padding: 0.35rem 0.85rem; font-size: 0.85rem;">
            🖼️ 100% Free AI Tool
        </span>
        <h1 style="font-size: 2.5rem; margin-bottom: 0.75rem; line-height: 1.2;">
            AI Image-to-Image Style Transformer
        </h1>
        <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 650px; margin: 0 auto;">
            Upload any input image, select your desired AI artistic style (Pixar 3D, Cyberpunk, Sketch, Oil Painting), and convert your image into a new artwork in seconds.
        </p>
    </div>

    <!-- Tool Card -->
    <div class="glass-card" style="padding: 2rem;">
        
        <form id="imageToImageForm" enctype="multipart/form-data">
            @csrf

            <!-- Image Upload Dropzone -->
            <div style="margin-bottom: 2rem;">
                <label style="display: block; font-weight: 600; font-size: 1rem; margin-bottom: 0.5rem;">
                    1. Upload Source Image <span style="color: var(--danger);">*</span>
                </label>
                
                <div id="dropzone" style="border: 2px dashed var(--border-active); background: rgba(15, 23, 42, 0.6); border-radius: var(--radius-md); padding: 2.5rem 1.5rem; text-align: center; cursor: pointer; transition: all 0.3s ease; position: relative;">
                    <input type="file" id="imageInput" name="image" accept="image/png, image/jpeg, image/webp" required style="position: absolute; inset: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer;">
                    
                    <div id="dropzonePlaceholder">
                        <div style="font-size: 2.5rem; margin-bottom: 0.5rem;">🖼️</div>
                        <h3 style="font-size: 1.1rem; margin-bottom: 0.35rem;">Drag & Drop your input photo here</h3>
                        <p style="font-size: 0.85rem; color: var(--text-muted);">Supports PNG, JPG, WEBP (Up to 10MB)</p>
                        <button type="button" class="btn btn-secondary btn-sm" style="margin-top: 1rem; pointer-events: none;">
                            Browse Image File
                        </button>
                    </div>

                    <div id="imagePreviewContainer" style="display: none; align-items: center; justify-content: center; gap: 1rem; flex-direction: column;">
                        <img id="imagePreview" src="" alt="Source Preview" style="max-height: 220px; border-radius: var(--radius-sm); object-fit: contain; border: 1px solid var(--border-color); box-shadow: 0 4px 20px rgba(0,0,0,0.4);">
                        <span id="imageFileName" style="font-size: 0.85rem; color: var(--primary); font-weight: 600;"></span>
                        <span style="font-size: 0.75rem; color: var(--text-dim); text-decoration: underline;">Click or drag to change image</span>
                    </div>
                </div>
            </div>

            <!-- Transformation Prompt -->
            <div style="margin-bottom: 1.75rem;">
                <label style="display: block; font-weight: 600; font-size: 1rem; margin-bottom: 0.5rem;">
                    2. Transformation Style Prompt <span style="color: var(--danger);">*</span>
                </label>
                <textarea 
                    id="promptInput" 
                    name="prompt" 
                    rows="3" 
                    class="search-input" 
                    style="width: 100%; background: rgba(15, 23, 42, 0.8); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 0.85rem 1rem; font-size: 0.95rem; resize: vertical;" 
                    placeholder="Describe how to transform this image... (e.g. 'Convert into vibrant 3D Pixar animation style with smooth lighting')"
                    required
                >Transform into vibrant 3D Pixar animation style with smooth lighting and expressive eyes.</textarea>

                <!-- Style Presets -->
                <div style="margin-top: 0.85rem;">
                    <span style="font-size: 0.8rem; color: var(--text-dim); display: block; margin-bottom: 0.4rem;">Select Popular Art Preset:</span>
                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        @foreach($stylePresets as $preset)
                            <button 
                                type="button" 
                                class="preset-chip" 
                                onclick="selectPreset(`{{ $preset['id'] }}`, `{{ addslashes($preset['prompt']) }}`, `{{ $preset['intensity'] }}`)"
                                style="background: rgba(56, 189, 248, 0.08); border: 1px solid rgba(56, 189, 248, 0.2); color: var(--text-main); padding: 0.35rem 0.75rem; border-radius: 20px; font-size: 0.8rem; cursor: pointer; transition: all 0.2s ease;"
                            >
                                {{ $preset['title'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Options: Style & Intensity -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 2rem;">
                <input type="hidden" name="style" id="selectedStyle" value="pixar">

                <!-- Intensity Strength -->
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem;">
                        Transformation Intensity
                    </label>
                    <select name="intensity" id="intensitySelect" class="country-select" style="width: 100%;">
                        <option value="subtle">🌱 Subtle Remix (Keep Original Structure)</option>
                        <option value="moderate" selected>⚡ Moderate Redesign (Balanced AI Style)</option>
                        <option value="high">🔥 High Fantasy (Complete AI Transformation)</option>
                    </select>
                </div>

                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem;">
                        Output Format
                    </label>
                    <input type="text" value="PNG High Quality (Uncompressed)" readonly class="country-select" style="width: 100%; opacity: 0.7; cursor: not-allowed;">
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" id="transformBtn" class="btn btn-primary" style="width: 100%; padding: 0.9rem; font-size: 1.1rem;">
                ✨ Transform Image (Free)
            </button>
        </form>

        <!-- Progress Overlay -->
        <div id="progressSection" style="display: none; margin-top: 2rem; padding: 2rem 1.5rem; background: rgba(15, 23, 42, 0.9); border-radius: var(--radius-md); text-align: center; border: 1px solid var(--border-active);">
            <div style="font-size: 2.2rem; margin-bottom: 0.5rem;" class="pulse-icon">🎨</div>
            <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Transforming Your AI Image...</h3>
            <p id="progressStatus" style="font-size: 0.9rem; color: var(--primary); margin-bottom: 1.25rem;">Segmenting subject composition & layout...</p>
            
            <div style="background: rgba(255,255,255,0.1); border-radius: 9999px; height: 12px; overflow: hidden; max-width: 500px; margin: 0 auto;">
                <div id="progressBar" style="width: 5%; height: 100%; background: linear-gradient(90deg, var(--primary), var(--accent)); transition: width 0.3s ease;"></div>
            </div>
        </div>

        <!-- Before vs After Result Section -->
        <div id="resultSection" style="display: none; margin-top: 2.5rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
                <div>
                    <span class="badge badge-success">✓ Transformation Complete</span>
                    <span id="appliedStyleBadge" class="badge badge-info" style="margin-left: 0.5rem;"></span>
                    <h2 style="font-size: 1.5rem; margin-top: 0.35rem;">Before vs After Comparison</h2>
                </div>
                <div style="display: flex; gap: 0.5rem;">
                    <a id="downloadImageBtn" href="#" download class="btn btn-primary btn-sm" style="padding: 0.6rem 1.2rem;">
                        📥 Download Transformed Image
                    </a>
                    <a href="{{ route('image_to_video.index') }}" class="btn btn-secondary btn-sm" style="padding: 0.6rem 1rem;">
                        🎥 Animate to 5s Video &rarr;
                    </a>
                </div>
            </div>

            <!-- Side-by-Side Cards -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem; margin-bottom: 1.5rem;">
                <!-- Before Original -->
                <div style="background: rgba(15, 23, 42, 0.8); border: 1px solid var(--border-color); border-radius: var(--radius-md); padding: 1rem; text-align: center;">
                    <span style="font-size: 0.85rem; font-weight: 600; color: var(--text-dim); display: block; margin-bottom: 0.75rem;">Original Input Photo</span>
                    <img id="originalImg" src="" alt="Original Photo" style="width: 100%; max-height: 380px; object-fit: contain; border-radius: var(--radius-sm);">
                </div>

                <!-- After Transformed -->
                <div style="background: rgba(15, 23, 42, 0.8); border: 1px solid var(--primary); border-radius: var(--radius-md); padding: 1rem; text-align: center; box-shadow: 0 0 25px rgba(56, 189, 248, 0.15);">
                    <span style="font-size: 0.85rem; font-weight: 600; color: var(--primary); display: block; margin-bottom: 0.75rem;">✨ Transformed AI Artwork</span>
                    <img id="transformedImg" src="" alt="Transformed Artwork" style="width: 100%; max-height: 380px; object-fit: contain; border-radius: var(--radius-sm);">
                </div>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center;">
                <p style="font-size: 0.85rem; color: var(--text-muted);">
                    <strong>Prompt Used:</strong> <span id="usedPromptText"></span>
                </p>
                <button type="button" onclick="resetForm()" class="btn btn-secondary btn-sm">
                    ✨ Transform Another Image
                </button>
            </div>
        </div>

    </div>

</div>

<script>
    const dropzone = document.getElementById('dropzone');
    const imageInput = document.getElementById('imageInput');
    const dropzonePlaceholder = document.getElementById('dropzonePlaceholder');
    const imagePreviewContainer = document.getElementById('imagePreviewContainer');
    const imagePreview = document.getElementById('imagePreview');
    const imageFileName = document.getElementById('imageFileName');
    const imageToImageForm = document.getElementById('imageToImageForm');
    const transformBtn = document.getElementById('transformBtn');
    const progressSection = document.getElementById('progressSection');
    const progressBar = document.getElementById('progressBar');
    const progressStatus = document.getElementById('progressStatus');
    const resultSection = document.getElementById('resultSection');
    const originalImg = document.getElementById('originalImg');
    const transformedImg = document.getElementById('transformedImg');
    const downloadImageBtn = document.getElementById('downloadImageBtn');
    const usedPromptText = document.getElementById('usedPromptText');
    const appliedStyleBadge = document.getElementById('appliedStyleBadge');
    const selectedStyle = document.getElementById('selectedStyle');

    // Image Input Preview
    imageInput.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const file = this.files[0];
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imageFileName.textContent = file.name + ' (' + (file.size / (1024*1024)).toFixed(2) + ' MB)';
                dropzonePlaceholder.style.display = 'none';
                imagePreviewContainer.style.display = 'flex';
            };
            reader.readAsDataURL(file);
        }
    });

    function selectPreset(styleId, promptText, intensity) {
        selectedStyle.value = styleId;
        document.getElementById('promptInput').value = promptText;
        if(intensity) {
            document.getElementById('intensitySelect').value = intensity;
        }
    }

    // Form Submission
    imageToImageForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!imageInput.files || !imageInput.files[0]) {
            alert('Please select an image file first!');
            return;
        }

        transformBtn.disabled = true;
        transformBtn.textContent = '⚙️ Transforming Image...';
        progressSection.style.display = 'block';
        resultSection.style.display = 'none';

        let progress = 5;
        progressBar.style.width = progress + '%';
        progressStatus.textContent = 'Uploading photo & initializing AI diffusion model...';

        const statusMessages = [
            { pct: 25, msg: 'Segmenting subject features & composition...' },
            { pct: 55, msg: 'Applying style transfer & artistic brushwork...' },
            { pct: 85, msg: 'Rendering high-res texture & color grading...' }
        ];

        let msgIdx = 0;
        const progressInterval = setInterval(() => {
            if (progress < 92) {
                progress += Math.floor(Math.random() * 10) + 5;
                if (progress > 92) progress = 92;
                progressBar.style.width = progress + '%';

                if (msgIdx < statusMessages.length && progress >= statusMessages[msgIdx].pct) {
                    progressStatus.textContent = statusMessages[msgIdx].msg;
                    msgIdx++;
                }
            }
        }, 300);

        const formData = new FormData(imageToImageForm);

        fetch('{{ route("image_to_image.transform") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            clearInterval(progressInterval);
            progressBar.style.width = '100%';
            progressStatus.textContent = 'Done! AI Transformation ready.';

            setTimeout(() => {
                progressSection.style.display = 'none';
                transformBtn.disabled = false;
                transformBtn.textContent = '✨ Transform Image (Free)';

                if (data.success) {
                    originalImg.src = data.original_url;
                    transformedImg.src = data.transformed_url;
                    downloadImageBtn.href = data.transformed_url;
                    downloadImageBtn.download = data.file_name;
                    usedPromptText.textContent = data.prompt;
                    appliedStyleBadge.textContent = '🎨 Style: ' + data.applied_style;
                    resultSection.style.display = 'block';
                    resultSection.scrollIntoView({ behavior: 'smooth' });
                } else {
                    alert('Image transformation failed. Please try again!');
                }
            }, 500);
        })
        .catch(err => {
            clearInterval(progressInterval);
            progressSection.style.display = 'none';
            transformBtn.disabled = false;
            transformBtn.textContent = '✨ Transform Image (Free)';
            alert('Error transforming image: ' + err.message);
        });
    });

    function resetForm() {
        imageToImageForm.reset();
        dropzonePlaceholder.style.display = 'block';
        imagePreviewContainer.style.display = 'none';
        resultSection.style.display = 'none';
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
</script>

<style>
    .pulse-icon {
        animation: spinPulse 2s infinite linear;
    }
    @keyframes spinPulse {
        0% { transform: rotate(0deg) scale(1); }
        50% { transform: rotate(180deg) scale(1.15); }
        100% { transform: rotate(360deg) scale(1); }
    }
    .preset-chip:hover {
        background: rgba(56, 189, 248, 0.25) !important;
        border-color: var(--primary) !important;
    }
</style>

@endsection
