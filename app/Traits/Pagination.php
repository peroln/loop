<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;

/**
 * Trait Pagination
 *
 * @package App\Traits
 */
trait Pagination
{
    /**
     * @var int
     */
    protected $perPage = 15;

    /**
     * @var int
     */
    protected $currentPage = 1;

    /**
     * @param array $args
     * @param $perPage
     * @param int $currentPage
     */
    private function multiplePaginate(array $args, int &$perPage, int &$currentPage = 1)
    {
        if (isset($args['perPage'])) {
            $perPage = $args['perPage'];
        }
        if (isset($args['currentPage'])) {
            $currentPage = $args['currentPage'];
        }
        Paginator::currentPageResolver(function () use ($currentPage) {
            return $currentPage;
        });
    }

    protected function resolvePagination(Request $request)
    {
        $this->currentPage = (int) $request->input('current_page', 1);
        $this->perPage = (int) $request->input('per_page', 15);

        Paginator::currentPageResolver(function () {
            return $this->currentPage;
        });
    }

    protected function loadMacroPaginateForCollections()
    {
        if (!Collection::hasMacro('paginate')) {
            Collection::macro('paginate', function ($perPage = 15, $page = null, $options = []) {
                $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

                return (new LengthAwarePaginator(
                    $this->forPage($page, $perPage)->values(),
                    $this->count(),
                    $perPage,
                    $page,
                    $options
                ))
                    ->withPath('');
            });
        }
    }

    /**
     * @param array $resource
     * @param string $name
     * @return array
     */
    protected function prettyPaginatedData(array $resource, string $name): array
    {
        $data = [];

        $data['total']        = $resource['total'] ?? 0;
        $data['per_page']     = $resource['per_page'] ?? 0;
        $data['last_page']    = $resource['last_page'] ?? 0;
        $data['current_page'] = $resource['current_page'] ?? 0;
        $data[$name]          = $resource['data'];

        return $data;
    }
}
