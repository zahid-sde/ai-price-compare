<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ImageToImageTest extends TestCase
{
    use RefreshDatabase;

    public function test_image_to_image_page_loads_successfully()
    {
        $response = $this->get(route('image_to_image.index'));

        $response->assertStatus(200);
        $response->assertSee('AI Image-to-Image Style Transformer');
        $response->assertSee('Upload Source Image');
    }

    public function test_image_to_image_transformation_with_valid_image_and_style()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('source_photo.jpg', 640, 480);

        $response = $this->postJson(route('image_to_image.transform'), [
            'image' => $file,
            'prompt' => 'Transform into vibrant 3D Pixar animation style with smooth lighting',
            'style' => 'pixar',
            'intensity' => 'moderate',
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'applied_style' => '3D Pixar & Anime Art',
        ]);
        $response->assertJsonStructure([
            'success',
            'message',
            'original_url',
            'transformed_url',
            'file_name',
            'applied_style',
            'prompt',
            'intensity',
        ]);
    }

    public function test_image_to_image_validation_fails_for_invalid_file()
    {
        $file = UploadedFile::fake()->create('archive.zip', 100);

        $response = $this->postJson(route('image_to_image.transform'), [
            'image' => $file,
            'prompt' => 'Transform file',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['image']);
    }
}
