<?php 

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Výchozí route CI4
$routes->get('/', 'Races::index');

// -------------------------------------------------------
// Cycling – Téma 1
// -------------------------------------------------------

// Přehled závodů (stránkovaně v kartách)
$routes->get('races', 'Races::index');

// Ročníky závodu
$routes->get('races/(:num)/years', 'Races::years/$1');

// Etapy ročníku (kliknutí na počet etap)
$routes->get('raceyears/(:num)/stages', 'Races::stages/$1');

// CRUD ročníků
$routes->get('races/(:num)/years/create',        'Races::create/$1');
$routes->post('races/(:num)/years/store',         'Races::store/$1');
$routes->get('races/(:num)/years/(:num)/edit',    'Races::edit/$1/$2');
$routes->post('races/(:num)/years/(:num)/update', 'Races::update/$1/$2');
$routes->post('races/(:num)/years/(:num)/delete', 'Races::delete/$1/$2');