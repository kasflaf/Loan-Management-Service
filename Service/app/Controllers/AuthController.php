<?php
namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\RESTful\ResourceController;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthController extends ResourceController
{
    protected $userModel;
    private $jwtKey;
    private $tokenExpiration;

    public function __construct()
    {
        $this->userModel = new UserModel();
        helper(['url', 'form']);
        
        // Get JWT key from environment variable or config
        $this->jwtKey = getenv('JWT_SECRET_KEY') ?: 'your-secret-key-here';
        // Token expiration time (e.g., 1 hour)
        $this->tokenExpiration = 3600;
    }

    private function generateJWTToken($userData)
    {
        $issuedAt = time();
        $expire = $issuedAt + $this->tokenExpiration;

        $payload = [
            'iat' => $issuedAt,
            'exp' => $expire,
            'user' => [
                'id' => $userData['id'],
                'username' => $userData['username'],
                'email' => $userData['email'],
                'role' => $userData['role']
            ]
        ];

        return JWT::encode($payload, $this->jwtKey, 'HS256');
    }

    public function register()
    {
        /*{
            "username":"testusername",
            "email":"testemail@mail.com",
            "password":"testpassword"
        }*/
        
        $data = $this->request->getJSON(true);
        $data['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT);
        $data['role'] = 'Member';

        if ($this->userModel->insert($data)) {
            // Get the inserted user data
            $userData = $this->userModel->find($this->userModel->getInsertID());
            
            // Generate JWT token
            $token = $this->generateJWTToken($userData);

            return $this->respondCreated([
                'message' => 'Registration successful.',
                'token' => $token
            ]);
        } else {
            return $this->failValidationErrors($this->userModel->errors());
        }
    }

    public function login()
    {
        $data = $this->request->getJSON(true);
        $email = $data['email'];
        $password = $data['password'];

        $user = $this->userModel->where('email', $email)->first();

        if ($user && password_verify($password, $user['password_hash'])) {
            // Generate JWT token
            $token = $this->generateJWTToken($user);

            return $this->respond([
                'message' => 'Login successful.',
                'token' => $token,
                'user' => [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'role' => $user['role']
                ]
            ]);
        } else {
            return $this->failUnauthorized('Invalid email or password.');
        }
    }

    public function deleteAccount()
    {
        try {
            $userId = $this->request->user['id'];

            if ($this->userModel->delete($userId)) {
                return $this->respondDeleted(['message' => 'Account deleted successfully.']);
            } else {
                return $this->failServerError('Unable to delete account.');
            }
        } catch (\Exception $e) {
            return $this->failUnauthorized('Invalid or expired token.serv');
        }
    }
}