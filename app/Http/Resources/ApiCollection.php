<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ApiCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'data' => $this->collection,
            'links' => [
                'path' => $this->path(),
                'nextPageUrl' => $this->nextPageUrl(),
                'previousPageUrl' => $this->previousPageUrl()
            ],            
            'meta' => [                
                'perPage' => $this->perPage(),
                'total' => $this->total(),
                'count' => $this->count(),
                'perPage' => $this->perPage(),
                'currentPage' => $this->currentPage(),
                'totalPages' => $this->lastPage(),
                'lastPage' => $this->lastPage()
            ]
        ];
    }    
}

