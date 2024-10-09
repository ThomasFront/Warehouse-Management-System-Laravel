<?php

namespace Tests\Unit;

use App\Http\Services\ImageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use App\Http\Requests\UploadImageRequest;
use Tests\TestCase;
use Mockery;

class ImageServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ImageService $imageService;

    public function setUp(): void
    {
        parent::setUp();

        $this->imageService = new ImageService();
    }

    public function test_upload_avatar()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('avatar.png');
        $request = Mockery::mock(UploadImageRequest::class);
        $request->shouldReceive('file')->with('image')->andReturn($file);

        $path = $this->imageService->uploadAvatar($request);

        Storage::assertExists($path);
        $this->assertStringStartsWith('public/avatars/', $path);
    }

    public function test_upload_product_image()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('product.png');
        $request = Mockery::mock(UploadImageRequest::class);
        $request->shouldReceive('file')->with('image')->andReturn($file);

        $path = $this->imageService->uploadProductImage($request);

        Storage::assertExists($path);
        $this->assertStringStartsWith('public/products/', $path);
    }
}
