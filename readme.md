# Laravel table data grid

#### Code:

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

        $dataGrid = new Table($dataSource);
        $dataGrid->addColumn('id', 'ID')->sortable()->view(function($item, $key) {
            return '<b>'. $item[$key] .'</b>';
        });
        $dataGrid->addColumn('name', 'Name');
        $dataGrid->addColumn('phone', 'Phone');
        $dataGrid->addColumn('email', 'Email')->view(function($item, $key) {
            return sprintf('<small>%s</small>', $item[$key]);
        });

        return view('tests/data-grid', ['tableContent' => $dataGrid->render()]);
