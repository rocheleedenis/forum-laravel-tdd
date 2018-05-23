<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
    protected $request;
    protected $builder;

    protected $filters = [];

    /**
     * Filters constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function apply($builder)
    {
        $this->builder = $builder;

        foreach ($this->filters as $filter) {
            if (!$this->hasFilter($filter)) {
                return;
            }

            $this->$filter($this->request->$filter);
        }

        return $this->builder;
    }

    /**
     * A pesquisa tem um filtro.
     *
     * @param $filter
     * @return bool
     */
    protected function hasFilter($filter) : bool
    {
        return method_exists($this, $filter) && $this->request->has($filter);
    }
}
