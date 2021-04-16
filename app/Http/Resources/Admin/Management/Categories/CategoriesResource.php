<?php

namespace App\Http\Resources\Admin\Management\Categories;

use App\Enums\BaseAppEnum;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoriesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param $request
     *
     * @return array
     */
    public function toArray($request): array
    {
        $pagination = $request->pagination ?? BaseAppEnum::DEFAULT_PAGINATION;

        if (is_null($request->category_id)) {
            return [
                'categories' => $this->paginate($pagination),
            ];
        }

        return [
            'fee' => $this->current_fee ?? null,
            'parent' => $this->findOrFail($request->category_id),
            'categories' => $this->categories()->with('fees')->paginate($pagination),
        ];
    }
}
