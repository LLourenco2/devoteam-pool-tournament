<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IndexResponseResource extends JsonResource
{
      private $resourceClass;
      private $data;
    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     * @return void
     */
    public function __construct($data, $resourceClass)
    {
        parent::__construct($data);
        $this->resourceClass = $resourceClass;
        $this->data = $data;
      
    }
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->resourceClass::collection($this->data),
            'totalRecords' => $this->data->total(),
        ];
    }
}
