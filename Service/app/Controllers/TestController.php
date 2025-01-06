<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use Config\Database;

class TestController extends BaseController 
{
    public function checkDbConnection()
    {
        try {
            // Attempt to connect to the database
            $db = Database::connect();
            
            // Execute a simple query to test the connection
            $query = $db->query('SELECT 1');
            
            // If we reach here, it means the connection is successful
            echo "Database connection successful!";
        } catch (\Exception $e) {
            // If there's an exception, output the error message
            echo "Database connection failed: " . $e->getMessage();
        }
    }
}
