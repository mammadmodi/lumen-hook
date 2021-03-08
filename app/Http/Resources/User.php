<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class User extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var \App\User $user */
        $user = $this;
        return [
            'name' => $user->name,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'created_at' => $user->created_at
        ];
    }
}
