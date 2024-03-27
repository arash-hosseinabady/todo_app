<?php

namespace App\Http\Resources;

use App\Enums\TodoStates;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TodoListResource extends JsonResource
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
            'desc' => $this->desc,
            'state' => TodoStates::getValues()[$this->lastLog->state] ?? null,
            'user' => $this->user->name,
            'created_at' => date('Y-m-d H:i:s', $this->created_at),
        ];
    }
}
