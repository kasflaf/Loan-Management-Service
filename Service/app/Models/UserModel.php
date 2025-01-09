<?php
namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users'; // Specify the table name
    protected $primaryKey = 'id'; // Specify the primary key of the table

    // Specify the fields that are allowed to be inserted/updated
    protected $allowedFields = [
        'username',
        'email',
        'password_hash',
        'role',
        'created_at',
        'updated_at'
    ];

    // Enable automatic timestamps
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation rules for the model
    protected $validationRules = [
        'username' => 'required|min_length[3]|max_length[20]|is_unique[users.username]',
        'email' => 'required|valid_email|is_unique[users.email]',
        'password_hash' => 'required|min_length[8]',
        'role' => 'required|in_list[Admin,Member]',
    ];

    // Custom validation messages
    protected $validationMessages = [
        'username' => [
            'required' => 'Username is required.',
            'min_length' => 'Username must be at least 3 characters long.',
            'max_length' => 'Username cannot exceed 20 characters.',
            'is_unique' => 'This username is already taken.'
        ],
        'email' => [
            'required' => 'Email is required.',
            'valid_email' => 'Please provide a valid email address.',
            'is_unique' => 'This email is already registered.'
        ],
        'password_hash' => [
            'required' => 'Password is required.',
            'min_length' => 'Password must be at least 8 characters long.'
        ],
        'role' => [
            'required' => 'Role is required.',
            'in_list' => 'Role must be either Admin or Member.'
        ]
    ];
}