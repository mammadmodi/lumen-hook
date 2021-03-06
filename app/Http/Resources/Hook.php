<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class Hook extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var \App\Hook $hook */
        $hook = $this;
        $hookArray = [
            'id' => $hook->id,
            'url' => $hook->url,
            'cron' => $hook->cron,
            'threshold' => $hook->threshold,
            'created_at' => $hook->created_at,
            'updated_at' => $hook->updated_at,
        ];

        if ($hook->deleted_at != null) {
            $hookArray = array_merge($hookArray, ['deleted_at' => $hook->deleted_at,]);
        }

        return $hookArray;
    }
}
