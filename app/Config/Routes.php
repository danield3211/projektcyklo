<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->get('races', 'Races::index');

$routes->get('races/(:num)/years',                'RaceYears::index/$1');
$routes->get('races/(:num)/years/create',         'RaceYears::create/$1');
$routes->post('races/(:num)/years/store',         'RaceYears::store');
$routes->get('races/(:num)/years/(:num)/edit',    'RaceYears::edit/$1/$2');
$routes->post('races/(:num)/years/(:num)/update', 'RaceYears::update/$1/$2');
$routes->post('races/(:num)/years/(:num)/delete', 'RaceYears::delete/$1/$2');

$routes->get('years/(:num)/stages', 'Stages::index/$1');