<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    public function toArray($request): array
    {
        $result = [
            'id' => $this['id'],
            'title' => $this['title'],
            'author_id' => $this['author_id'],
            'author_name' => $this['author']['name'],
            'category_id' => $this['category_id'],
            'category_name' => $this['category']['name'],
            'release_date' => $this['release_date'],
            'price_huf' => $this['price_huf'],
            'created_at' => $this['created_at'],
            'updated_at' => $this['updated_at'],
        ];

        if(isset($this['price_eur'])) {
            $result['price_eur'] = $this['price_eur'];
        }

        return $result;
    }
}