<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HookError extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var \App\HookError $hookError */
        $hookError = $this;
        return [
            'id' => $hookError->id,
            'hook_id' => $hookError->hook_id,
            'status_code' => $hookError->status_code,
            'response_body' => $hookError->response_body,
            'created_at' => $hookError->created_at,
        ];
    }
}
