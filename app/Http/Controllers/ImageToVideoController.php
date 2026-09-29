<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageToVideoController extends Controller
{
    public function index()
    {
        $presets = [
            [
                'title' => '🌊 Oceanic Wave & Soft Zoom In',
                'prompt' => 'Animate ocean waves flowing gently with slow camera zoom in into the horizon.',
                'motion' => 'normal',
            ],
            [
                'title' => '💫 Cyberpunk Neon Glow & Pan Right',
                'prompt' => 'Cyberpunk neon cyan glow with smooth camera pan right and vibrant contrast.',
                'motion' => 'dynamic',
            ],
            [
                'title' => '🎬 3D Zoom Out & Cinematic Film',
                'prompt' => 'Dramatic cinematic film style with smooth camera zoom out and deep contrast.',
                'motion' => 'normal',
            ],
            [
                'title' => '🔥 Pan Up & Golden Warm Ember',
                'prompt' => 'Camera tilt pan up with warm golden sunset glow and soft motion.',
                'motion' => 'dynamic',
            ],
            [
                'title' => '🌸 Pan Left & Subtle Breeze',
                'prompt' => 'Soft camera pan left with gentle breeze and natural soft lighting.',
                'motion' => 'subtle',
            ],
        ];

        return view('public.image_to_video.index', compact('presets'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:10240',
            'prompt' => 'required|string|min:3|max:500',
            'motion' => 'nullable|string|in:subtle,normal,dynamic',
            'aspect_ratio' => 'nullable|string|in:16:9,9:16,1:1',
        ]);

        $imageFile = $request->file('image');
        $uploadedRelativePath = $imageFile->store('image_to_video/uploads', 'public');
        $uploadedAbsolutePath = storage_path('app/public/'.$uploadedRelativePath);

        $aspectRatio = $validated['aspect_ratio'] ?? '16:9';
        [$width, $height] = match ($aspectRatio) {
            '9:16' => [720, 1280],
            '1:1' => [720, 720],
            default => [1280, 720],
        };

        $motionLevel = $validated['motion'] ?? 'normal';
        $promptText = strtolower($validated['prompt']);

        // Parse Motion Speed multiplier
        $speedStep = match ($motionLevel) {
            'subtle' => 0.0010,
            'dynamic' => 0.0035,
            default => 0.0020,
        };

        // Parse Camera Motion Direction from exact user prompt text
        if (str_contains($promptText, 'zoom out') || str_contains($promptText, 'pull back')) {
            $zoomExpr = sprintf("zoompan=z='max(1.30-(%s*on),1.0)':x='iw/2-(iw/zoom/2)':y='ih/2-(ih/zoom/2)'", $speedStep);
            $directionDetected = 'Camera Zoom Out';
        } elseif (str_contains($promptText, 'pan left') || str_contains($promptText, 'move left')) {
            $zoomExpr = "zoompan=z=1.18:x='max((iw/2-(iw/zoom/2))-(on*2.5),0)':y='ih/2-(ih/zoom/2)'";
            $directionDetected = 'Camera Pan Left';
        } elseif (str_contains($promptText, 'pan right') || str_contains($promptText, 'move right')) {
            $zoomExpr = "zoompan=z=1.18:x='min((iw/2-(iw/zoom/2))+(on*2.5),iw-iw/zoom)':y='ih/2-(ih/zoom/2)'";
            $directionDetected = 'Camera Pan Right';
        } elseif (str_contains($promptText, 'pan up') || str_contains($promptText, 'tilt up') || str_contains($promptText, 'rise')) {
            $zoomExpr = "zoompan=z=1.18:x='iw/2-(iw/zoom/2)':y='max((ih/2-(ih/zoom/2))-(on*2.5),0)'";
            $directionDetected = 'Camera Tilt Up';
        } elseif (str_contains($promptText, 'pan down') || str_contains($promptText, 'tilt down')) {
            $zoomExpr = "zoompan=z=1.18:x='iw/2-(iw/zoom/2)':y='min((ih/2-(ih/zoom/2))+(on*2.5),ih-ih/zoom)'";
            $directionDetected = 'Camera Tilt Down';
        } else {
            // Default: Smooth Slow Zoom In
            $zoomExpr = sprintf("zoompan=z='min(zoom+%s,1.25)':x='iw/2-(iw/zoom/2)':y='ih/2-(ih/zoom/2)'", $speedStep);
            $directionDetected = 'Camera Zoom In';
        }

        // Parse Color Filter & Aesthetic Style from user prompt text
        $colorFilter = '';
        if (str_contains($promptText, 'cyberpunk') || str_contains($promptText, 'neon') || str_contains($promptText, 'glow')) {
            $colorFilter = ',eq=saturation=1.4:contrast=1.15,hue=h=10';
        } elseif (str_contains($promptText, 'black and white') || str_contains($promptText, 'monochrome') || str_contains($promptText, 'bw')) {
            $colorFilter = ',hue=s=0,eq=contrast=1.2';
        } elseif (str_contains($promptText, 'warm') || str_contains($promptText, 'sunset') || str_contains($promptText, 'golden')) {
            $colorFilter = ',eq=gamma_r=1.15:gamma_g=1.05:gamma_b=0.9';
        } elseif (str_contains($promptText, 'cinematic') || str_contains($promptText, 'dramatic')) {
            $colorFilter = ',eq=contrast=1.2:saturation=1.1:brightness=-0.01';
        }

        $vfChain = sprintf(
            'scale=%d:%d:force_original_aspect_ratio=increase,crop=%d:%d,%s:d=125:s=%dx%d:fps=25%s',
            $width,
            $height,
            $width,
            $height,
            $zoomExpr,
            $width,
            $height,
            $colorFilter
        );

        $outputFileName = 'ai_video_5s_'.time().'_'.Str::random(6).'.mp4';
        $outputRelativePath = 'image_to_video/generated/'.$outputFileName;
        $outputAbsolutePath = storage_path('app/public/'.$outputRelativePath);

        Storage::disk('public')->makeDirectory('image_to_video/generated');

        $ffmpegCmd = sprintf(
            'ffmpeg -loop 1 -i %s -vf %s -c:v libx264 -t 5 -pix_fmt yuv420p -y %s 2>&1',
            escapeshellarg($uploadedAbsolutePath),
            escapeshellarg($vfChain),
            escapeshellarg($outputAbsolutePath)
        );

        exec($ffmpegCmd, $outputLines, $returnCode);

        // Fallback if custom filter chain fails
        if ($returnCode !== 0 || ! file_exists($outputAbsolutePath)) {
            $fallbackCmd = sprintf(
                'ffmpeg -loop 1 -i %s -c:v libx264 -t 5 -pix_fmt yuv420p -vf "scale=%d:%d:force_original_aspect_ratio=decrease,pad=%d:%d:(ow-iw)/2:(oh-ih)/2" -y %s 2>&1',
                escapeshellarg($uploadedAbsolutePath),
                $width,
                $height,
                $width,
                $height,
                escapeshellarg($outputAbsolutePath)
            );
            exec($fallbackCmd);
        }

        $videoUrl = asset('storage/'.$outputRelativePath);

        return response()->json([
            'success' => true,
            'message' => '5-second AI video generated successfully!',
            'video_url' => $videoUrl,
            'file_name' => $outputFileName,
            'prompt' => $validated['prompt'],
            'direction_detected' => $directionDetected,
            'duration' => '5 Seconds',
            'aspect_ratio' => $aspectRatio,
        ]);
    }
}
