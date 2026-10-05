<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Rocniky::index/124');
$routes->get('paris-nice', 'Rocniky::index/124');
$routes->get('rocniky/(:num)', 'Rocniky::index/$1');
$routes->post('rocniky/create', 'FormulareRaceYear::create');
$routes->get('etapa/(:num)/vysledky/(:num)', 'Vysledky::etapa/$1/$2');
