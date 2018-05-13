# Make data table in minutes

#### Installation

    composer require blackbear/data-grid v0.0.*

#### Example with full options:

	$faker = \Faker\Factory::create();
    $dataSource = new Collection();
    for($i = 0; $i < 30; $i ++) {
        $dataSource->push([
            'id' => $faker->ean8,
            'name' => $faker->name,
            'phone' => $faker->phoneNumber,
            'email' => $faker->email
        ]);
    }

    $dataGrid = new DataTable([
        'visibleColumns' => ['id', 'name', 'phone', 'email', 'birthday'],
        'columnHeaders' => ['id' => 'ID', 'name' => 'Name'],
        'sortColumns' => ['id', 'name'],
        'showCheckbox' => true,
        'showEditDelete' => true,
        'currentUrl' => url()->full(),
        'dataSource' => $dataSource,
        'renderRowAttribute' => function($item) {
            return [
                'data-id' => $item['id']
            ];
        },
        'renderColumnAttribute' => function($item, $column) {
            switch($column) {
                case 'id':
                    return ['data-field' => 'id', 'data-id' => $item['id']];
                case 'email':
                    return ['data-field' => 'email'];
                case 'name':
                    return 'data-field="name" data-id="'.$item['id'].'"';
            }
        },
        'renderEditUrl' => function($item) {
            return url('/'.$item['id'].'/edit');
        },
        'renderDeleteUrl' => function($item) {
            return url('/'.$item['id'].'/delete');
        },
        'renderColumnContent' => function($item, $column) {
            switch($column) {
                case 'id':
                    return '<i>'.$item['id'].'</i>';
                case 'email':
                    return '<a href="mailto:'.$item['email'].'">'.$item['email'].'</a>';
            }
        }
    ]);

    return view('tests/data-grid', ['tableContent' => $dataGrid->render()]);

Template HTML

    @extends('admin/layouts/master')
    
    @section('main-content')
    	<style>
    		{{ file_get_contents(base_path().'/vendor/blackbear/data-grid/src/assets/css/data-table.css') }}
    	</style>
    	<script>
    		{!! file_get_contents(base_path().'/vendor/blackbear/data-grid/src/assets/js/data-table.js') !!}
    	</script>
    	<script>
    		$(function() {
    		   	DataTable.init({
    				onChangeItem: function(row, e) {
    				    
    				},
    				onCheckAll: function(e) {
    
    				}
    			})
    		});
    	</script>
    	<div class="panel">
    		<div class="panel-body">
    			{!! $tableContent !!}
    		</div>
    	</div>
    @stop