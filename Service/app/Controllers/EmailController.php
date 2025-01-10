<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;

class EmailController extends ResourceController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel(); // Load the UserModel
    }

    /**
     * Get user's email by ID
     *
     * @param int $id User ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function getEmail($id)
    {
        $user = $this->userModel->find($id);

        if ($user) {
            // Return the email in JSON format
            return $this->respond([
                'status' => 'success',
                'email' => $user['email']
            ], 200);
        }

        // Return error if user not found
        return $this->respond([
            'status' => 'error',
            'message' => 'User not found.'
        ], 404);
    }
}
