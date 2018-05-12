<?php

namespace BlackBear\DataGrid;

use Closure;

class DataTable {

	protected $headings = array();
	protected $lastKeyAdded;
	protected $callback = [];
	protected $sortable = [];

	protected $config = [];
    protected $dataSource;
	protected $visibleColumns;
	protected $columnHeaders;
	protected $renderColumnContent;
	protected $showCheckbox = true;
	protected $showContextMenu = true;
	protected $showEditDelete = true;
	protected $editLabel = "Edit";
	protected $deleteLabel = "Delete";
    protected $currentUrl; // Using this option with option showEditDelete
    protected $renderEditUrl;
    protected $renderDeleteUrl;
    protected $onCheckboxChanged;
    protected $columnStyle;
    protected $sortColumns;

	public function __construct(array $config)
	{
	    $this->config = $config;
		$this->visibleColumns = $this->getConfig('visibleColumns', []);
		$this->columnHeaders = $this->getConfig('columnHeaders', []);
        $this->renderColumnContent = $this->getConfig('renderColumnContent');
        $this->showCheckbox = $this->getConfig('showCheckbox');
        $this->showContextMenu = $this->getConfig('showContextMenu');
        $this->onCheckboxChanged = $this->getConfig('onCheckboxChanged');
        $this->dataSource = $this->getConfig('dataSource', []);
        $this->sortColumns = $this->getConfig('sortColumns', []);
        $this->showEditDelete = $this->getConfig('showEditDelete', true);
        $this->editLabel = $this->getConfig('editLabel', $this->editLabel);
        $this->deleteLabel = $this->getConfig('deleteLabel', $this->deleteLabel);
        $this->currentUrl = $this->getConfig('currentUrl', "");
        $this->renderEditUrl = $this->getConfig('renderEditUrl');
        $this->renderDeleteUrl = $this->getConfig('renderDeleteUrl');
        if($this->showEditDelete) {
            $this->visibleColumns = array_merge($this->visibleColumns, ['_edit', '_delete']);
            $this->columnHeaders = array_merge($this->columnHeaders, [
                '_edit' => $this->editLabel,
                '_delete' => $this->deleteLabel
            ]);
        }
		view()->addNamespace('data-grid', base_path() . '/packages/data-grid/src/views');
	}

	private function getConfig($key = null, $default = null) {
	    if($key) {
	        return array_key_exists($key, $this->config) ? $this->config[$key] : $default;
        }

        return $this->config;
    }

	private function addColumn($key, $label) {
		if(!$key) $key = md5(uniqid().rand(111111,999999));
		$this->headings[$key] = $label;
		// Override heading if want to sort
		foreach($this->sortColumns as $column) {
		    $this->headings[$column] = get_sort_link($this->columnHeaders[$column], $key, $this->currentUrl, []);
        }
		return $this;
	}

	private function setUpHeading() {
	    if(!$this->columnHeaders) {
	        foreach($this->visibleColumns as $field) {
	            $this->addColumn($field, $field);
            }
        } else if(is_array($this->visibleColumns)) {
            foreach($this->visibleColumns as $field) {
                if(array_key_exists($field, $this->columnHeaders)) {
                    $this->addColumn($field, $this->columnHeaders[$field]);
                } else {
                    $this->addColumn($field, $field);
                }
            }
        }
    }

	public function render()
	{
	    $this->setUpHeading();
		return view('data-grid::table', [
			'headings' => $this->headings,
			'ref' => $this
		]);
	}

	public function renderRows()
	{
		$str = "";
		foreach($this->dataSource as $index => $item) {
			$str .= '<tr data-index="'.$index.'">'.$this->renderColumns($item).'</tr>';
		}
		return $str;
	}

	protected function renderColumns($item)
	{
		$content = [];
		foreach($this->visibleColumns as $key) {
            $content[] = $this->renderSingleColumn($item, $key);
		}

		return implode("", $content);
	}

	protected function renderSingleColumn($item, $column) {
	    $content = "";
	    $defaultContent = isset($item[$column]) ? $item[$column] : "";

        if($this->showEditDelete) {
            switch ($column) {
                case '_edit':
                    $defaultContent = '<a class="btn btn-xs btn-info btn-action-edit" href="'.call_user_func_array($this->renderEditUrl, array($item)).'"><i class="fa fa-pencil"></i></a>';
                    break;

                case '_delete':
                    $defaultContent = '<a class="btn btn-xs btn-danger btn-action-delete" href="'.call_user_func_array($this->renderDeleteUrl, array($item)).'"><i class="fa fa-trash"></a>';
                    break;
            }
        }

        // Override column style if you want
	    if($this->renderColumnContent instanceof Closure) {
            $content = strval(call_user_func_array($this->renderColumnContent, array($item, $column)));
	    }

        return sprintf('<td data-key="'.$column.'">%s</td>', $content ? $content : $defaultContent);
    }
}