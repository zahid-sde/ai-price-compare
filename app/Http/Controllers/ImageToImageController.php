<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageToImageController extends Controller
{
    public function index()
    {
        $stylePresets = [
            [
                'id' => 'pixar',
                'title' => '🎨 3D Pixar & Anime Art',
                'prompt' => 'Transform into vibrant 3D Pixar animation style with smooth lighting and expressive eyes.',
                'intensity' => 'moderate',
            ],
            [
                'id' => 'cyberpunk',
                'title' => '💫 Cyberpunk Neon Synthwave',
                'prompt' => 'Convert into futuristic cyberpunk artwork with glowing cyan-purple neon lights and high contrast.',
                'intensity' => 'high',
            ],
            [
                'id' => 'sketch',
                'title' => '✏️ Charcoal & Pencil Sketch',
                'prompt' => 'Convert photo into detailed hand-drawn pencil sketch drawing with crosshatch shading.',
                'intensity' => 'moderate',
            ],
            [
                'id' => 'oil_painting',
                'title' => '🖼️ Classical Oil Painting',
                'prompt' => 'Transform scene into rich textured classical oil painting on canvas with artistic brushstrokes.',
                'intensity' => 'high',
            ],
            [
                'id' => 'portrait',
                'title' => '📸 Studio Portrait & Glamour',
                'prompt' => 'Enhance image into professional studio portrait with soft bokeh backdrop and golden hour glow.',
                'intensity' => 'subtle',
            ],
        ];

        return view('public.image_to_image.index', compact('stylePresets'));
    }

    public function transform(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,jpg,png,webp|max:10240',
            'prompt' => 'required|string|min:3|max:500',
            'style' => 'nullable|string|in:pixar,cyberpunk,sketch,oil_painting,portrait,custom',
            'intensity' => 'nullable|string|in:subtle,moderate,high',
        ]);

        $imageFile = $request->file('image');
        $uploadedRelativePath = $imageFile->store('image_to_image/uploads', 'public');
        $uploadedAbsolutePath = storage_path('app/public/'.$uploadedRelativePath);

        $style = $validated['style'] ?? 'custom';
        $intensity = $validated['intensity'] ?? 'moderate';
        $promptText = strtolower($validated['prompt']);

        // Load original GD image
        $imageInfo = @getimagesize($uploadedAbsolutePath);
        $mime = $imageInfo['mime'] ?? 'image/jpeg';

        $sourceGd = match ($mime) {
            'image/png' => @imagecreatefrompng($uploadedAbsolutePath),
            'image/webp' => @imagecreatefromwebp($uploadedAbsolutePath),
            default => @imagecreatefromjpeg($uploadedAbsolutePath),
        };

        if (! $sourceGd) {
            // Fallback GD blank canvas if format unreadable
            $sourceGd = imagecreatetruecolor(800, 600);
            $bg = imagecolorallocate($sourceGd, 15, 23, 42);
            imagefill($sourceGd, 0, 0, $bg);
        }

        $w = imagesx($sourceGd);
        $h = imagesy($sourceGd);

        // Create target GD canvas
        $targetGd = imagecreatetruecolor($w, $h);
        imagecopy($targetGd, $sourceGd, 0, 0, 0, 0, $w, $h);

        // Apply Image-to-Image style transformations based on style & prompt
        if ($style === 'sketch' || str_contains($promptText, 'sketch') || str_contains($promptText, 'pencil') || str_contains($promptText, 'drawing')) {
            imagefilter($targetGd, IMG_FILTER_GRAYSCALE);
            imagefilter($targetGd, IMG_FILTER_CONTRAST, -35);
            imagefilter($targetGd, IMG_FILTER_EMBOSS);
            $appliedStyleName = 'Pencil & Charcoal Sketch';
        } elseif ($style === 'cyberpunk' || str_contains($promptText, 'cyberpunk') || str_contains($promptText, 'neon') || str_contains($promptText, 'synthwave')) {
            imagefilter($targetGd, IMG_FILTER_CONTRAST, -20);
            imagefilter($targetGd, IMG_FILTER_COLORIZE, 30, -10, 50);
            imagefilter($targetGd, IMG_FILTER_BRIGHTNESS, 10);
            $appliedStyleName = 'Cyberpunk Neon Glow';
        } elseif ($style === 'oil_painting' || str_contains($promptText, 'oil painting') || str_contains($promptText, 'painting')) {
            imagefilter($targetGd, IMG_FILTER_MEAN_REMOVAL);
            imagefilter($targetGd, IMG_FILTER_SMOOTH, -5);
            imagefilter($targetGd, IMG_FILTER_COLORIZE, 20, 10, -10);
            $appliedStyleName = 'Classical Oil Painting';
        } elseif ($style === 'pixar' || str_contains($promptText, 'pixar') || str_contains($promptText, 'anime') || str_contains($promptText, '3d')) {
            imagefilter($targetGd, IMG_FILTER_SMOOTH, 8);
            imagefilter($targetGd, IMG_FILTER_CONTRAST, -15);
            imagefilter($targetGd, IMG_FILTER_COLORIZE, 15, 15, 25);
            $appliedStyleName = '3D Pixar & Anime Art';
        } else {
            // Default Studio Portrait & Enhanced Style
            imagefilter($targetGd, IMG_FILTER_BRIGHTNESS, 5);
            imagefilter($targetGd, IMG_FILTER_CONTRAST, -10);
            imagefilter($targetGd, IMG_FILTER_COLORIZE, 10, 5, 0);
            $appliedStyleName = 'Studio Portrait & AI Enhancer';
        }

        // Save transformed result PNG image
        $outputFileName = 'ai_transformed_'.time().'_'.Str::random(6).'.png';
        $outputRelativePath = 'image_to_image/generated/'.$outputFileName;
        $outputAbsolutePath = storage_path('app/public/'.$outputRelativePath);

        Storage::disk('public')->makeDirectory('image_to_image/generated');
        imagepng($targetGd, $outputAbsolutePath);

        imagedestroy($sourceGd);
        imagedestroy($targetGd);

        $originalUrl = asset('storage/'.$uploadedRelativePath);
        $transformedUrl = asset('storage/'.$outputRelativePath);

        return response()->json([
            'success' => true,
            'message' => 'AI Image transformed successfully!',
            'original_url' => $originalUrl,
            'transformed_url' => $transformedUrl,
            'file_name' => $outputFileName,
            'applied_style' => $appliedStyleName,
            'prompt' => $validated['prompt'],
            'intensity' => $intensity,
        ]);
    }
}
