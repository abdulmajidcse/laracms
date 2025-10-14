<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Content;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ContentReorderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::factory()->create();
        Sanctum::actingAs($user);
    }

    public function test_user_can_reorder_contents(): void
    {
        $contents = Content::factory()
            ->count(4)
            ->sequence(
                ['order' => 1],
                ['order' => 2],
                ['order' => 3],
                ['order' => 4],
            )
            ->create();

        $payload = [
            'prev_content_id' => $contents[3]->id, // after content 4
            'reorder_content_ids' => [$contents[1]->id, $contents[2]->id],
            'next_content_id' => null,
        ];

        $this->patchJson(route('api.v1.contents.reorder'), $payload)
            ->assertOk();

        $contents[1]->refresh();
        $contents[2]->refresh();

        $this->assertTrue($contents[1]->order > $contents[3]->order);
        $this->assertTrue($contents[2]->order > $contents[1]->order);
    }

    public function test_validation_fails_for_invalid_ids(): void
    {
        $payload = [
            'reorder_content_ids' => [999, 1000],
        ];

        $this->patchJson(route('api.v1.contents.reorder'), $payload)
            ->assertStatus(422)
            ->assertJsonValidationErrors(['reorder_content_ids.0']);
    }

    public function test_prev_and_next_content_logic_works(): void
    {
        $contents = Content::factory()
            ->count(4)
            ->sequence(
                ['order' => 1],
                ['order' => 2],
                ['order' => 3],
                ['order' => 4],
            )
            ->create();

        // Reorder content 1 to be between content 2 and 3
        $payload = [
            'prev_content_id' => $contents[1]->id,
            'reorder_content_ids' => [$contents[0]->id],
            'next_content_id' => $contents[2]->id,
        ];

        $this->patchJson(route('api.v1.contents.reorder'), $payload)
            ->assertOk();
    }
}
