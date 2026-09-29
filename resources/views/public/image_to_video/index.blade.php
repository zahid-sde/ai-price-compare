@extends('layouts.app')

@section('title', 'Free AI Image-to-Video Generator (5s HD MP4) - AI Price Compare')
@section('meta_description', 'Turn any static image into a 5-second animated AI video for free. Enter custom motion prompts, pick camera movement presets, and download HD MP4 clips.')

@section('content')

<div style="max-width: 960px; margin: 0 auto; padding-bottom: 3rem;">

    <!-- Page Header -->
    <div style="text-align: center; margin-bottom: 2.5rem;">
        <span class="badge badge-info" style="margin-bottom: 0.75rem; padding: 0.35rem 0.85rem; font-size: 0.85rem;">
            🎥 100% Free AI Tool
        </span>
        <h1 style="font-size: 2.5rem; margin-bottom: 0.75rem; line-height: 1.2;">
            AI Image-to-Video Generator
        </h1>
        <p style="color: var(--text-muted); font-size: 1.1rem; max-width: 650px; margin: 0 auto;">
            Upload any photo or AI graphic, type your motion prompt, and generate a 5-second cinematic MP4 video clip in seconds. Zero registration required.
        </p>
    </div>

    <!-- Main Tool Container -->
    <div class="glass-card" style="padding: 2rem;">
        
        <form id="imageToVideoForm" enctype="multipart/form-data">
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
                        <h3 style="font-size: 1.1rem; margin-bottom: 0.35rem;">Drag & Drop your image here</h3>
                        <p style="font-size: 0.85rem; color: var(--text-muted);">Supports PNG, JPG, WEBP (Up to 10MB)</p>
                        <button type="button" class="btn btn-secondary btn-sm" style="margin-top: 1rem; pointer-events: none;">
                            Browse Image File
                        </button>
                    </div>

                    <div id="imagePreviewContainer" style="display: none; align-items: center; justify-content: center; gap: 1rem; flex-direction: column;">
                        <img id="imagePreview" src="" alt="Selected Preview" style="max-height: 220px; border-radius: var(--radius-sm); object-fit: contain; border: 1px solid var(--border-color); box-shadow: 0 4px 20px rgba(0,0,0,0.4);">
                        <span id="imageFileName" style="font-size: 0.85rem; color: var(--primary); font-weight: 600;"></span>
                        <span style="font-size: 0.75rem; color: var(--text-dim); text-decoration: underline;">Click or drag to change image</span>
                    </div>
                </div>
            </div>

            <!-- Motion Prompt Input -->
            <div style="margin-bottom: 1.75rem;">
                <label style="display: block; font-weight: 600; font-size: 1rem; margin-bottom: 0.5rem;">
                    2. Motion Prompt & Animation Intent <span style="color: var(--danger);">*</span>
                </label>
                <textarea 
                    id="promptInput" 
                    name="prompt" 
                    rows="3" 
                    class="search-input" 
                    style="width: 100%; background: rgba(15, 23, 42, 0.8); border: 1px solid var(--border-color); border-radius: var(--radius-sm); padding: 0.85rem 1rem; font-size: 0.95rem; resize: vertical;" 
                    placeholder="Describe how you want your image to move... (e.g. 'Animate ocean waves flowing softly with slow camera zoom')"
                    required
                >Animate the scene with smooth cinematic camera motion and subtle atmospheric lighting particles.</textarea>

                <!-- Prompt Preset Chips -->
                <div style="margin-top: 0.85rem;">
                    <span style="font-size: 0.8rem; color: var(--text-dim); display: block; margin-bottom: 0.4rem;">Quick Preset Ideas:</span>
                    <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                        @foreach($presets as $p)
                            <button 
                                type="button" 
                                class="preset-chip" 
                                onclick="fillPrompt(`{{ addslashes($p['prompt']) }}`, `{{ $p['motion'] }}`)"
                                style="background: rgba(56, 189, 248, 0.08); border: 1px solid rgba(56, 189, 248, 0.2); color: var(--text-main); padding: 0.3rem 0.65rem; border-radius: 20px; font-size: 0.78rem; cursor: pointer; transition: all 0.2s ease;"
                            >
                                {{ $p['title'] }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Controls: Motion & Aspect Ratio -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; margin-bottom: 2rem;">
                <!-- Motion Strength -->
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem;">
                        Motion Dynamics
                    </label>
                    <select name="motion" id="motionSelect" class="country-select" style="width: 100%;">
                        <option value="subtle">🌱 Subtle (Soft & Natural)</option>
                        <option value="normal" selected>⚡ Normal (Balanced Cinematic)</option>
                        <option value="dynamic">🔥 Dynamic (High Action Motion)</option>
                    </select>
                </div>

                <!-- Aspect Ratio -->
                <div>
                    <label style="display: block; font-weight: 600; font-size: 0.9rem; margin-bottom: 0.5rem;">
                        Output Aspect Ratio
                    </label>
                    <select name="aspect_ratio" id="aspectRatioSelect" class="country-select" style="width: 100%;">
                        <option value="16:9" selected>📺 16:9 Landscape (YouTube / Desktop)</option>
                        <option value="9:16">📱 9:16 Portrait (Reels / TikTok)</option>
                        <option value="1:1">🔳 1:1 Square (Instagram Post)</option>
                    </select>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit" id="generateBtn" class="btn btn-primary" style="width: 100%; padding: 0.9rem; font-size: 1.1rem;">
                🚀 Generate 5s AI Video (Free)
            </button>
        </form>

        <!-- Generation Progress Bar Overlay -->
        <div id="progressSection" style="display: none; margin-top: 2rem; padding: 2rem 1.5rem; background: rgba(15, 23, 42, 0.9); border-radius: var(--radius-md); text-align: center; border: 1px solid var(--border-active);">
            <div style="font-size: 2.2rem; margin-bottom: 0.5rem;" class="pulse-icon">⚙️</div>
            <h3 style="font-size: 1.25rem; margin-bottom: 0.5rem;">Generating Your 5-Second AI Video...</h3>
            <p id="progressStatus" style="font-size: 0.9rem; color: var(--primary); margin-bottom: 1.25rem;">Analyzing image depth map...</p>
            
            <div style="background: rgba(255,255,255,0.1); border-radius: 9999px; height: 12px; overflow: hidden; max-width: 500px; margin: 0 auto;">
                <div id="progressBar" style="width: 5%; height: 100%; background: linear-gradient(90deg, var(--primary), var(--accent)); transition: width 0.3s ease;"></div>
            </div>
        </div>

        <!-- Result Video Player Container -->
        <div id="resultSection" style="display: none; margin-top: 2.5rem; padding-top: 2rem; border-top: 1px solid var(--border-color);">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                <div>
                    <span class="badge badge-success">✓ Ready for Download</span>
                    <span id="motionBadge" class="badge badge-info" style="margin-left: 0.5rem; display: none;"></span>
                    <h2 style="font-size: 1.5rem; margin-top: 0.35rem;">Generated 5s AI Video Preview</h2>
                </div>
                <a id="downloadVideoBtn" href="#" download class="btn btn-primary btn-sm" style="padding: 0.6rem 1.2rem;">
                    📥 Download 5s MP4 Video
                </a>
            </div>

            <div style="background: #000000; border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border-active); box-shadow: 0 10px 40px rgba(0,0,0,0.6); position: relative; display: flex; justify-content: center;">
                <video id="videoPlayer" controls autoplay loop style="width: 100%; max-height: 520px; object-fit: contain;">
                    <source id="videoSource" src="" type="video/mp4">
                    Your browser does not support HTML5 video tag.
                </video>
            </div>

            <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 1.25rem;">
                <p style="font-size: 0.85rem; color: var(--text-muted);">
                    <strong>Prompt:</strong> <span id="usedPromptText"></span>
                </p>
                <button type="button" onclick="resetForm()" class="btn btn-secondary btn-sm">
                    ✨ Animate Another Image
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
    const imageToVideoForm = document.getElementById('imageToVideoForm');
    const generateBtn = document.getElementById('generateBtn');
    const progressSection = document.getElementById('progressSection');
    const progressBar = document.getElementById('progressBar');
    const progressStatus = document.getElementById('progressStatus');
    const resultSection = document.getElementById('resultSection');
    const videoPlayer = document.getElementById('videoPlayer');
    const videoSource = document.getElementById('videoSource');
    const downloadVideoBtn = document.getElementById('downloadVideoBtn');
    const usedPromptText = document.getElementById('usedPromptText');

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

    function fillPrompt(text, motion) {
        document.getElementById('promptInput').value = text;
        if(motion) {
            document.getElementById('motionSelect').value = motion;
        }
    }

    // Form Submission with Progress Bar Animation
    imageToVideoForm.addEventListener('submit', function(e) {
        e.preventDefault();

        if (!imageInput.files || !imageInput.files[0]) {
            alert('Please select an image file first!');
            return;
        }

        generateBtn.disabled = true;
        generateBtn.textContent = '⚙️ Generating Video...';
        progressSection.style.display = 'block';
        resultSection.style.display = 'none';

        let progress = 5;
        progressBar.style.width = progress + '%';
        progressStatus.textContent = 'Uploading image and initializing AI motion engine...';

        const statusMessages = [
            { pct: 20, msg: 'Analyzing image depth map & focal points...' },
            { pct: 45, msg: 'Applying optical flow physics & prompt instructions...' },
            { pct: 70, msg: 'Rendering 5s 60fps HD H.264 video stream...' },
            { pct: 90, msg: 'Finalizing MP4 encoding & audio sync...' }
        ];

        let msgIdx = 0;
        const progressInterval = setInterval(() => {
            if (progress < 92) {
                progress += Math.floor(Math.random() * 8) + 4;
                if (progress > 92) progress = 92;
                progressBar.style.width = progress + '%';

                if (msgIdx < statusMessages.length && progress >= statusMessages[msgIdx].pct) {
                    progressStatus.textContent = statusMessages[msgIdx].msg;
                    msgIdx++;
                }
            }
        }, 400);

        const formData = new FormData(imageToVideoForm);

        fetch('{{ route("image_to_video.generate") }}', {
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
            progressStatus.textContent = 'Done! 5-second video ready.';

            setTimeout(() => {
                progressSection.style.display = 'none';
                generateBtn.disabled = false;
                generateBtn.textContent = '🚀 Generate 5s AI Video (Free)';

                if (data.success) {
                    videoSource.src = data.video_url;
                    videoPlayer.load();
                    downloadVideoBtn.href = data.video_url;
                    downloadVideoBtn.download = data.file_name;
                    usedPromptText.textContent = data.prompt;
                    if (data.direction_detected) {
                        const motionBadge = document.getElementById('motionBadge');
                        motionBadge.textContent = '🎯 Motion: ' + data.direction_detected;
                        motionBadge.style.display = 'inline-flex';
                    }
                    resultSection.style.display = 'block';
                    resultSection.scrollIntoView({ behavior: 'smooth' });
                } else {
                    alert('Video generation failed. Please try again!');
                }
            }, 500);
        })
        .catch(err => {
            clearInterval(progressInterval);
            progressSection.style.display = 'none';
            generateBtn.disabled = false;
            generateBtn.textContent = '🚀 Generate 5s AI Video (Free)';
            alert('Error generating video: ' + err.message);
        });
    });

    function resetForm() {
        imageToVideoForm.reset();
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
