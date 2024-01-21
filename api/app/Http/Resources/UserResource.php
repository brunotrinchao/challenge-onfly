<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
    * @OA\Schema(
    *     schema="UserResource",
    *     title="User Resource",
    *     @OA\Property(property="id", type="integer", example="1"),
    *     @OA\Property(property="name", type="string", example="User name"),
    *     @OA\Property(property="email", type="string", example="email@email.com"),
    *    @OA\Property(property="created_at", type="string", format="date-time", example="2022-01-01T12:00:00Z"),
    *     @OA\Property(property="updated_at", type="string", format="date-time", example="2022-01-01T12:00:00Z")
    * )
    * Transform the resource into an array.
    *
    * @return array<string, mixed>
    */
    public function toArray($request): array
    {
        return [
             'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
