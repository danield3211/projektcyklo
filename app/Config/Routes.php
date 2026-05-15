$routes->get('races', 'Races::index');

$routes->get('races/(:num)/editions',                'RaceEditions::index/$1');
$routes->get('races/(:num)/editions/create',         'RaceEditions::create/$1');
$routes->post('races/(:num)/editions/store',         'RaceEditions::store');
$routes->get('races/(:num)/editions/(:num)/edit',    'RaceEditions::edit/$1/$2');
$routes->post('races/(:num)/editions/(:num)/update', 'RaceEditions::update/$1/$2');
$routes->post('races/(:num)/editions/(:num)/delete', 'RaceEditions::delete/$1/$2');

$routes->get('editions/(:num)/stages', 'Stages::index/$1');