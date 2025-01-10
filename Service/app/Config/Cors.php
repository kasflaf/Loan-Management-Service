<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Cors extends BaseConfig
{
    public bool $enabled = true;

    public array $allowedOrigins = ['*'];
    
    public array $allowedHeaders = [
        'X-API-KEY',
        'Origin',
        'X-Requested-With',
        'Content-Type',
        'Accept',
        'Access-Control-Request-Method',
        'Authorization'
    ];
    
    public array $allowedMethods = ['GET', 'POST', 'PUT', 'DELETE', 'OPTIONS'];
    
    public bool $allowCredentials = true;
    
    public int $maxAge = 7200;  // 2 hours
    
    // Add these for Private Network Access
    public array $exposedHeaders = [
        'Access-Control-Allow-Private-Network',
        'Private-Network-Access-Id'
    ];
    
    public array $additionalHeaders = [
        'Access-Control-Allow-Private-Network' => 'true',
        'Private-Network-Access-Id' => 'test'
    ];
}