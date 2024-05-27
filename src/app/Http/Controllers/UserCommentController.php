<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserCommentRequest;
use App\Http\Requests\UpdateUserCommentRequest;
use App\Http\Resources\UserCommentResource;
use App\Models\UserComment;
use App\Services\UserCommentService;
use Illuminate\Support\Str;

class UserCommentController extends Controller
{
    public function __construct(
        protected UserCommentService $userCommentService
    )
    {

    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comments = \request()->user()->comments()
            ->where(function ($query) {
                if (\request()->has('topic')) {
                    return $query->where('topic', \request()->get('topic'));
                }

                return $query;
            })
            ->get();

        $comments = $this->userCommentService->asTree($comments->collect());

        return UserCommentResource::collection($comments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserCommentRequest $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'homepage' => 'nullable|string|max:255',
            'text' => 'required|max:1000',
            'topic' => 'required|string',
            'reply_id' => 'nullable|exists:user_comments,id',
        ]);

        $user = $request->user();
        $data = $request->only([
            'name',
            'email',
            'homepage',
            'text',
            'topic',
            'reply_id',
        ]);
        $data['id'] = Str::uuid();
        $now = now();
        $data['created_at'] = $now;
        $data['updated_at'] = $now;

        dispatch(function () use ($user, $data) {
            $user->comments()->create($data);
        });

        return UserCommentResource::make(UserComment::make($data));
    }

    /**
     * Display the specified resource.
     */
    public function show(UserComment $userComment)
    {
        return UserCommentResource::make($userComment);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserCommentRequest $request, $commentId)
    {
        $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'homepage' => 'nullable|string|max:255',
            'text' => 'required|max:1000',
            'topic' => 'required|string',
        ]);

        $user = $request->user();
        $data = $request->only([
            'name',
            'email',
            'homepage',
            'text',
            'topic',
            'reply_id',
        ]);

        dispatch(function () use ($user, $commentId, $data) {
            if (!$user->comments()->whereId($commentId)->exists()) {
                return;
            }

            $user->comments()
                ->limit(1)
                ->whereId($commentId)
                ->update($data);
        });

        return response()->json();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserComment $userComment)
    {
        if ($userComment->user_id != \request()->user()->id) {
            return response(__('Comment not found'), 404);
        }

        $userComment->delete();

        return response()->noContent();
    }
}
