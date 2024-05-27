<?php

namespace Tests\Feature;

use App\Models\User;
use Tests\TestCase;

class UserCommentsTest extends TestCase
{
    public function test_the_application_returns_a_successful_response(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);
        $response = $this->get('/api/user/comments');

        $response->assertSuccessful();
    }
}
