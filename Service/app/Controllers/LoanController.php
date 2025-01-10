<?php

namespace App\Controllers;

use App\Models\LoanModel;
use CodeIgniter\RESTful\ResourceController;

class LoanController extends ResourceController
{
    protected $loanModel;

    public function __construct()
    {
        $this->loanModel = new LoanModel();
    }

    // Create a new loan (POST /loans)
    public function create()
    {
        // Get JSON data from the request body
        $requestData = $this->request->getJSON(true); // The 'true' argument returns JSON as an associative array.
    
        // Define the data structure explicitly
        $data = [
            'user_id' => $this->request->user['id'], // Get user ID from the request
            'book_id' => $requestData['book_id'] ?? null, // Ensure book_id is set, or use null as a fallback
            'loan_start_date' => $requestData['loan_start_date'] ?? date('Y-m-d'), // Use current date if not provided            
            'loan_due_date' => $requestData['loan_due_date'] ?? null, // Ensure loan_due_date is set
            'loan_returned_date' => null, // Defaults to null
            'status' => 'active', // Default status
            'penalty' => 0, // Defaults to null
        ];

        // // print data as resoponse
        // return $this->respond($data);

        // Check if the book is already borrowed (active or overdue status)
        $existingLoan = $this->loanModel
                            ->where('book_id', $data['book_id'])
                            ->whereIn('status', ['active', 'overdue'])
                            ->first();
    
        if ($existingLoan) {
            return $this->failResourceExists('This book is already borrowed and cannot be loaned again.');
        }
    
        // Save the new loan
        if ($this->loanModel->save($data)) {
            return $this->respondCreated(['message' => 'Loan successfully created.']);
        }
    
        return $this->fail('Failed to create the loan.');
    }
    
    


    // Update loan statuses if overdue (PUT /loans/update-status)
    public function updateAllLoanStatuses()
    {
        // Fetch all active loans
        $activeLoans = $this->loanModel->where('status', 'active')->findAll();

        if (empty($activeLoans)) {
            return $this->respond(['message' => 'No active loans to update.']);
        }

        $updatedLoans = 0;

        // Iterate through each active loan
        foreach ($activeLoans as $loan) {
            $loan_due_date = strtotime($loan['loan_due_date']);
            $current_date = strtotime(date('Y-m-d')); // Current date

            // Check if the loan is overdue
            if ($current_date > $loan_due_date) {
                $this->loanModel->update($loan['loan_id'], ['status' => 'overdue']);
                $updatedLoans++;
            }
        }

        return $this->respondUpdated(['message' => "$updatedLoans loans updated to overdue status."]);
    }


    // Return a book and update penalty (PUT /loans/return/{loan_id})
    public function returnBook($loan_id)
    {
        // Get the loan record by ID
        $loan = $this->loanModel->find($loan_id);

        // Check if the loan exists
        if (!$loan) {
            return $this->failNotFound('Loan not found.');
        }

        // Ensure the logged-in user is the one who borrowed the book
        if ($loan['user_id'] !== $this->request->user['id']) {
            return $this->failForbidden('You are not authorized to return this book.');
        }

        // Check if the book was already returned
        if ($loan['status'] === 'returned') {
            return $this->fail('The book has already been returned.');
        }

        // Calculate penalty if overdue
        $penalty = 0;
        $loan_due_date = strtotime($loan['loan_due_date']);
        $return_date = strtotime(date('Y-m-d')); // Current date (or you can use actual return date)

        if ($return_date > $loan_due_date) {
            $penalty = ceil(($return_date - $loan_due_date) / 86400) * 0.5;  // Example penalty of 0.5 per day
        }

        // Update loan status to 'returned' and set the penalty
        $updatedData = [
            'loan_returned_date' => date('Y-m-d'),
            'status' => 'returned',
            'penalty' => $penalty,
        ];

        // Update the loan in the database
        $this->loanModel->update($loan_id, $updatedData);

        // Return a response indicating the book was returned and penalty updated
        return $this->respondUpdated(['message' => 'Book returned and penalty updated.']);
    }

    // Get all loans for a specific user (GET /loans/user/{user_id})
    public function getLoansByUser($user_id)
    {
        $loans = $this->loanModel->where('user_id', $user_id)
                                 ->findAll();

        if (!$loans) {
            return $this->failNotFound('No loans found for this user.');
        }

        return $this->respond($loans);
    }

    // Get all active loans for a user (GET /loans/active/{user_id})
    public function getActiveLoans($user_id)
    {
        $loans = $this->loanModel->where('user_id', $user_id)
                                  ->where('status', 'active')
                                  ->findAll();

        if (!$loans) {
            return $this->failNotFound('No active loans found for this user.');
        }

        return $this->respond($loans);
    }

    // Soft delete a loan (DELETE /loans/delete/{loan_id})
    public function deleteLoan($loan_id)
    {
        $loan = $this->loanModel->find($loan_id);

        if (!$loan) {
            return $this->failNotFound('Loan not found.');
        }

        $this->loanModel->delete($loan_id);

        return $this->respondDeleted(['message' => 'Loan successfully deleted.']);
    }
    // Get all active loans (GET /loans/active)
    public function getAllActiveLoans()
    {
        $loans = $this->loanModel->where('status', 'active')->findAll();

        if (!$loans) {
            return $this->failNotFound('No active loans found.');
        }

        return $this->respond($loans);
    }
}
