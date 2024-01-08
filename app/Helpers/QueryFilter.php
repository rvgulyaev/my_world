<?php

namespace App\Helpers;

use Illuminate\Http\Request;

class QueryFilter
{
    protected $request;
    protected $builder;
    protected $filters;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;
        $this->setFilters();
        foreach ($this->filters as $filter) {
            if (method_exists($this, $filter) && session($filter) !== null) {
                $this->$filter(session($filter));
            }
        }
        return $this->builder;
    }

    public function setFilters()
    {
        foreach ($this->filters as $filter) {
            if ($this->request->has($filter)) {
                session([$filter => $this->request->get($filter)]);
            }
        }
    }
}