<?php

namespace Packages\Permission\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class PermissionParentResource extends JsonResource
{
    public function toArray($request)
    {

        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'title'    => $this->title,
            'children' => PermissionResource::collection($this->whenLoaded('children')),
        ];
    }
}