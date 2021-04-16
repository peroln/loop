<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

/**
 * Trait RequestManager
 *
 * @package App\Traits
 */
trait RequestManager
{
    protected function checkRelationParamRequest(&$relations, Request $request)
    {
        if ($request->has('relations')) {
            $relations = $request->input('relations');
            $relations = explode(',', $relations);
        } else {
            $relations = [];
        }
    }

    protected function validateOderByFields(Request &$request, array $sortingFields)
    {
        $request->validate([
                               'order_by' => [
                                   'string',
                                   Rule::in($sortingFields),
                               ],
                               'order_type' => 'string | in:desc,asc',
                           ]);
    }
}
