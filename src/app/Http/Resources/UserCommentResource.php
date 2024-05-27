<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserCommentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => (string) $this->id,
            'email' => $this->email,
            'name' => $this->name,
            'homepage' => $this->homepage,
            'text' => $this->text,
            'topic' => $this->topic,
            'reply_id' => $this->reply_id,
            'created_at' => (string) $this->created_at->getTimestampMs(),
            'updated_at' => (string) $this->updated_at->getTimestampMs(),
            'children' => UserCommentResource::collection(!empty($this->children) ? $this->children : collect()),
        ];
    }
}
