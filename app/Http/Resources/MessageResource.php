<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'message' => $this->message,
            'priority' => $this->priority,
            'createdAt' => $this->created_at,
            'user' => [
                'id' => $this->user->id,
                'firstName' => $this->user->first_name,
                'lastName' => $this->user->last_name,
                'role' => $this->user->role,
                'createdAt' => $this->user->created_at,
                'avatar' => $this->user->avatar
            ]
        ];
    }
}
