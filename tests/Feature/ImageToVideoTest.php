<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageToVideoTest extends TestCase
{
    use RefreshDatabase;

    public function test_image_to_video_page_loads_successfully()
    {
        $response = $this->get(route('image_to_video.index'));

        $response->assertStatus(200);
        $response->assertSee('AI Image-to-Video Generator');
        $response->assertSee('Upload Source Image');
    }

    public function test_image_to_video_generation_with_valid_image_and_prompt()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('sample_photo.jpg', 640, 480);

        $response = $this->postJson(route('image_to_video.generate'), [
            'image' => $file,
            'prompt' => 'Animate cinematic camera zoom with soft ocean waves',
            'motion' => 'normal',
            'aspect_ratio' => '16:9',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'duration' => '5 Seconds',
            'aspect_ratio' => '16:9',
        ]);
        $response->assertJsonStructure([
            'success',
            'message',
            'video_url',
            'file_name',
            'prompt',
            'duration',
            'aspect_ratio',
        ]);
    }

    public function test_image_to_video_validation_fails_for_invalid_file()
    {
        $file = UploadedFile::fake()->create('document.pdf', 100);

        $response = $this->postJson(route('image_to_video.generate'), [
            'image' => $file,
            'prompt' => 'Animate document',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['image']);
    }
}
