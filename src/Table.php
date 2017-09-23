<?php

namespace BlackBear\DataGrid;

use Closure;

class Table {

	protected $headings = array();
	protected $dataSource;
	protected $lastKeyAdded;
	protected $callback = [];
	protected $sortable = [];

	public function __construct($dataSource)
	{
		$this->dataSource = $dataSource;
		view()->addNamespace('data-grid', base_path() . '/packages/data-grid/src/views');
	}

	public function addColumn($key, $label) {
		if(!$key) $key = md5(uniqid().rand(111111,999999));
		$this->headings[$key] = $label;
		$this->lastKeyAdded = $key;
		return $this;
	}

	public function render()
	{
		return view('data-grid::table', [
			'headings' => $this->headings,
			'table' => $this
		]);
	}

	public function renderRows()
	{
		$str = "";
		foreach($this->dataSource as $item) {
			$str .= '<tr>'.$this->renderColumn($item).'</tr>';
		}
		return $str;
	}

	protected function renderColumn($item)
	{
		$str = "";
		foreach($this->headings as $key => $heading) {
			if(isset($this->callback[$key])) {
				$str .= '<td>'.call_user_func_array($this->callback[$key], array($item, $key)).'</td>';
			} else {
				$str .= '<td>'.array_get($item, $key).'</td>';
			}

		}

		return $str;
	}

	public function heading(Closure $callback) {
		$heading = strval(call_user_func_array($callback, array()));
		$this->headings[$this->lastKeyAdded] = $heading;
		return $this;
	}

	public function view(Closure $callback) {
		$this->callback[$this->lastKeyAdded] = $callback;
		return $this;
	}

	public function sortable($link = null, array $urlParams = array()) {
		$key = $this->lastKeyAdded;

		// Flag sortable
		$this->sortable[$key] = true;

		// Set default link
		if(is_null($link)) $link = url()->full();

		// Set default url params
		if(empty($urlParams)) $urlParams = request()->all();

		// Override heading
		$this->headings[$key] = get_sort_link($key, $link, $urlParams);

		return $this;
	}
}