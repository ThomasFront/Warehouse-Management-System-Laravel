<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
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

    protected function getDropdownProvider(Model $model)
    {
        return $model::all()->map(function($item){
           return [
               'label' => $item->name,
               'value' => $item->id
           ];
        });
    }
}
