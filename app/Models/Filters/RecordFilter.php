<?php

namespace App\Models\Filters;

use App\Helpers\QueryFilter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class RecordFilter extends QueryFilter
{

    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->filters = ['date', 'checkedUser'];
    }

    public function date($value)
    {
        if ($value == null) $value = Date('Y-m-d');
        $this->builder->where('educationDate', '=', "'%$value%'");
    }

    public function checkedUser($value)
    {
        if ($value > 0) $this->builder->where('user_id', '=', $value);
    }
}