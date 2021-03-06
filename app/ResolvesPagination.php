<?php

namespace App;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

trait ResolvesPagination
{
    /**
     * @var int
     */
    protected $perPage = 15;

    /**
     * @var int
     */
    protected $currentPage = 1;

    protected function resolvePagination(Request $request): void
    {
        $this->currentPage = (int)$request->input('current_page', 1) ?: 1;
        $this->perPage = (int)$request->input('per_page', 15) ?: 15;

        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }

    protected function getDataForResponse(Collection $collection): LengthAwarePaginator
    {
        return new LengthAwarePaginator(
            $collection->forPage($this->currentPage, $this->perPage)->values(),
            $collection->count(),
            $this->perPage,
            $this->currentPage
        );
    }
}
