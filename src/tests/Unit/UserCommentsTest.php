<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\UserComment;
use App\Services\UserCommentService;
use Tests\TestCase;

class UserCommentsTest extends TestCase
{
    public function testCommentsTree()
    {
        $user = User::factory()->create();
        $commentOne = $user->comments()->create([
            'name' => 'name-one',
            'text' => 'text-one',
            'topic' => 'topic',
        ]);
        $commentTwo = $user->comments()->create([
            'name' => 'name-two',
            'text' => 'text-two',
            'topic' => 'topic',
        ]);
        $commentThree = $user->comments()->create([
            'name' => 'name-three',
            'text' => 'text-three',
            'topic' => 'topic',
            'reply_id' => $commentTwo->id,
        ]);
        $user->comments()->create([
            'name' => 'name-four',
            'text' => 'text-four',
            'topic' => 'topic',
            'reply_id' => $commentThree->id,
        ]);
        $user->comments()->create([
            'name' => 'name-four-two',
            'text' => 'text-four-two',
            'topic' => 'topic',
            'reply_id' => $commentThree->id,
        ]);

        $comments = $user->comments()->where('topic', 'topic')->get();
        $userCommentService = app(UserCommentService::class);
        $comments = $userCommentService->asTree($comments);
        $this->assertCount(2, $comments);
    }
}
