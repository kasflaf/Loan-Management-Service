<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;
use App\Models\UserModel;

class AuthFilter implements FilterInterface
{
    private $jwtKey;

    public function __construct()
    {
        $this->jwtKey = getenv('JWT_SECRET_KEY') ?: 'your-secret-key-here';
    }

    public function before(RequestInterface $request, $arguments = null)
    {
        try {
            $token = $request->getHeaderLine('Authorization');
            if (empty($token)) {
                return service('response')
                    ->setStatusCode(401)
                    ->setJSON([
                        'error' => 'No token provided.'
                    ]);
            }
            
            $token = str_replace('Bearer ', '', $token);
            $decoded = JWT::decode($token, new Key($this->jwtKey, 'HS256'));
            
            // Check if user exists in the database
            $userModel = new UserModel();
            $user = $userModel->find($decoded->user->id);
            
            if (!$user) {
                return service('response')
                    ->setStatusCode(401)
                    ->setJSON([
                        'error' => 'User does not exist.'
                    ]);
            }
            
            // Attach user data to request for use in controllers
            $request->user = $user;

        } catch (Exception $e) {
            return service('response')
                ->setStatusCode(401)
                ->setJSON([
                    'error' => 'Invalid or expired token.'
                ]);
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // No processing needed after the response
    }
}