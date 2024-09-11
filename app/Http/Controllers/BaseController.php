<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BaseController extends Controller
{
    protected function getPaginationAndFilterParams(Request $request)
    {
        return [
            'pageSize' => $request->input('pageSize', 25),
            'sortField' => $request->input('sortField', 'created_at'),
            'sortOrder' => $request->input('sortOrder', 'asc'),
            'filterField' => $request->input('filterField'),
            'filterValue' => $request->input('filterValue'),
            'filterOperator' => $request->input('filterOperator'),
        ];
    }
}
