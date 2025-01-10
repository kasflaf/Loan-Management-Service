<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
// In app/Config/Routes.php

// Group routes for authentication
$routes->group('auth', function ($routes) {
    $routes->post('register', 'AuthController::register');
    $routes->post('login', 'AuthController::login');
    
    // Apply 'auth' filter for logout and delete account routes
    $routes->delete('delete', 'AuthController::deleteAccount', ['filter' => 'auth']);
});

// Loan-related routes
$routes->group('loans', function ($routes) {
    $routes->post('create', 'LoanController::create', ['filter' => 'auth']); // Create a loan
    $routes->put('update-status', 'LoanController::updateAllLoanStatuses'); // Update overdue loans
    $routes->put('return/(:num)', 'LoanController::returnBook/$1', ['filter' => 'auth']); // Return a book
    $routes->get('user/(:num)', 'LoanController::getLoansByUser/$1'); // Get loans by user
    $routes->get('active/(:num)', 'LoanController::getActiveLoans/$1'); // Get active loans for user
    $routes->get('activeAll', 'LoanController::getAllActiveLoans'); // Get a loan
    $routes->delete('delete/(:num)', 'LoanController::deleteLoan/$1', ['filter' => 'auth']); // Delete a loan
});

$routes->get('user/email/(:num)', 'EmailController::getEmail/$1');