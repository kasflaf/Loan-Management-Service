<?php

namespace App\Models;

use CodeIgniter\Model;

class LoanModel extends Model
{
    protected $table = 'loans'; // Table name
    protected $primaryKey = 'loan_id'; // Primary key

    // Allowed fields for insert and update
    protected $allowedFields = [
        'user_id',
        'book_id',
        'loan_start_date',
        'loan_due_date',
        'loan_returned_date',
        'status',
        'penalty',
        'created_at',
        'updated_at',
    ];

    // Use automatic timestamps
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation rules
    protected $validationRules = [
        'user_id'           => 'required|integer',
        'book_id'           => 'required|max_length[255]',
        'loan_start_date'   => 'required|valid_date',
        'loan_due_date'     => 'required|valid_date',
        'loan_returned_date'=> 'permit_empty|valid_date',
        'status'            => 'required|in_list[active,overdue,returned]',
        'penalty'           => 'permit_empty|decimal',
    ];

    protected $validationMessages = [
        'user_id' => [
            'required' => 'User ID is required.',
            'integer'  => 'User ID must be an integer.',
        ],
        'book_id' => [
            'required'   => 'Book ID is required.',
            'max_length' => 'Book ID cannot exceed 255 characters.',
        ],
        'loan_start_date' => [
            'required'   => 'Loan start date is required.',
            'valid_date' => 'Loan start date must be a valid date.',
        ],
        'loan_due_date' => [
            'required'   => 'Loan due date is required.',
            'valid_date' => 'Loan due date must be a valid date.',
        ],
        'loan_returned_date' => [
            'valid_date' => 'Loan returned date must be a valid date.',
        ],
        'status' => [
            'required' => 'Status is required.',
            'in_list'  => 'Status must be one of: active, overdue, returned.',
        ],
        'penalty' => [
            'decimal' => 'Penalty must be a valid decimal number.',
        ],
    ];

    // Soft deletes (optional)
    protected $useSoftDeletes = true;
    protected $deletedField = 'deleted_at';
}
