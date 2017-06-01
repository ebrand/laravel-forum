<?php

namespace App\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
	protected $request, $builder;
	protected $filters = [];

	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	public function apply($builder)
	{
		$this->builder = $builder;
		
		// start with the filters that our sub-class supports
		$this->getFilters()
			
			// filter those to only the ones for which a method
			// is implemented
			->filter(function ($filter) {
				return method_exists($this, $filter);
			})

			// execute each method
			->each(function ($filter, $value) {
				$this->$filter($value);
			});

		return $this->builder;
	}

	protected function getFilters()
	{
		return collect($this->request->intersect($this->filters))->flip();
	}
}
