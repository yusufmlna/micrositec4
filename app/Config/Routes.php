<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');
$routes->get('/login', 'Login::index');
$routes->get('/logout', 'Logout::index');

$routes->get('/products_upload', 'ProductUpload::index');
$routes->post('/products_upload/upload', 'ProductUpload::upload');

$routes->get('/module_products', 'ModuleProduct::index');
$routes->post('/module_products', 'ModuleProduct::submit');

$routes->get('/module_setting', 'ModuleSetting::index');

$routes->get('/module_setting/top_picks', 'ModuleSetting::topPicksModule');
$routes->post('/module_setting/top_picks', 'ModuleSetting::topPicksModuleSubmit');

$routes->get('/module_setting/new_release', 'ModuleSetting::newReleaseModule');
$routes->post('/module_setting/new_release', 'ModuleSetting::newReleaseModuleSubmit');

$routes->get('/module_setting/flash_sale', 'ModuleSetting::flashSaleModule');
$routes->post('/module_setting/flash_sale', 'ModuleSetting::flashSaleModuleSubmit');

$routes->get('/module_setting/bestseller_books', 'ModuleSetting::bestsellerBooksModule');
$routes->post('/module_setting/bestseller_books', 'ModuleSetting::bestsellerBooksModuleSubmit');

$routes->get('/module_setting/bestseller_toys', 'ModuleSetting::bestsellerToysModule');
$routes->post('/module_setting/bestseller_toys', 'ModuleSetting::bestsellerToysModuleSubmit');

$routes->get('/module_setting/lpit', 'ModuleSetting::lpitModule');
$routes->post('/module_setting/lpit', 'ModuleSetting::lpitModuleSubmit');

$routes->get('/image_update', 'ImageUpdate::index');
$routes->post('/image_update', 'ImageUpdate::submit');

$routes->get('/category_disc', 'CategoryDisc::index');
$routes->post('/category_disc', 'CategoryDisc::submit');

$routes->get('/manual_disc', 'ManualDisc::index');
$routes->post('/manual_disc', 'ManualDisc::submit');

$routes->get('/exclude_product', 'ExcludeProduct::index');
$routes->post('/exclude_product', 'ExcludeProduct::submit');

$routes->get('/sphinx_index', 'SphinxIndex::index');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
