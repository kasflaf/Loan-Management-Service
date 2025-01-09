<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// In app/Config/Routes.php

$routes->post('/email', 'UserController::getEmailById');

// Group routes for authentication
$routes->group('auth', function ($routes) {
    $routes->post('register', 'AuthController::register');
    $routes->post('login', 'AuthController::login');
    
    // Apply 'auth' filter for logout and delete account routes
    $routes->delete('delete', 'AuthController::deleteAccount', ['filter' => 'auth']);
});

// Loan-related routes
$routes->group('loans', function ($routes) {
    $routes->post('create', 'LoanController::create'); // Create a loan
    $routes->put('update-status', 'LoanController::updateAllLoanStatuses'); // Update overdue loans
    $routes->put('return/(:num)', 'LoanController::returnBook/$1'); // Return a book
    $routes->get('user/(:num)', 'LoanController::getLoansByUser/$1'); // Get loans by user
    $routes->get('active/(:num)', 'LoanController::getActiveLoans/$1'); // Get active loans for user
    $routes->delete('delete/(:num)', 'LoanController::deleteLoan/$1'); // Delete a loan
});


$routes->get('/testdb', 'TestController::checkDbConnection');
$routes->get('/', 'Home::index');