<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Content;
use App\Enums\ContentType;
use App\Enums\ContentStatus;
use Laravel\Sanctum\Sanctum;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ContentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        Sanctum::actingAs($user);
    }

    public function test_user_can_view_list_contents(): void
    {
        Content::factory()->count(3)->create();

        $this->getJson(route('api.v1.contents.index'))
            ->assertOk();
    }

    public function test_user_can_store_a_content(): void
    {
        $payload = [
            'type' => ContentType::BANNER,
            'title' => 'New Banner',
            'payload' => ['image' => 'banner.jpg', 'link' => 'https://example.com'],
            'status' => ContentStatus::ACTIVE,
        ];

        $this->postJson(route('api.v1.contents.store'), $payload)
            ->assertCreated()
            ->assertJsonFragment(['title' => 'New Banner']);

        $this->assertDatabaseHas('contents', ['title' => 'New Banner']);
    }

    public function test_user_can_show_a_content(): void
    {
        $content = Content::factory()->create();

        $this->getJson(route('api.v1.contents.show', $content->id))
            ->assertOk()
            ->assertJsonFragment(['id' => $content->id]);
    }

    public function test_user_can_update_a_content(): void
    {
        $content = Content::factory()->create();

        $payload = [
            'type' => $content->type,
            'title' => 'Updated Title',
            'payload' => ['text' => 'Updated content'],
            'status' => $content->status,
        ];

        $this->putJson(route('api.v1.contents.update', $content->id), $payload)
            ->assertOk()
            ->assertJsonFragment(['title' => 'Updated Title']);

        $this->assertDatabaseHas('contents', ['title' => 'Updated Title']);
    }

    public function test_user_can_delete_a_content(): void
    {
        $content = Content::factory()->create();

        $this->deleteJson(route('api.v1.contents.destroy', $content->id))
            ->assertOk();

        $this->assertDatabaseMissing('contents', ['id' => $content->id]);
    }

    public function test_user_can_upload_a_file(): void
    {
        Storage::fake();

        $file = UploadedFile::fake()->image('banner.jpg', 600, 400);

        $this->postJson(route('api.v1.contents.uploadFile'), [
            'file' => $file,
        ])
            ->assertOk()
            ->assertJsonStructure([
                'result' => ['file_url']
            ]);

        Storage::assertExists($file->hashName());
    }
}
