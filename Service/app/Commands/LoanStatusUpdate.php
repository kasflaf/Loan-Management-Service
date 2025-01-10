<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\LoanModel;

class LoanStatusUpdate extends BaseCommand
{
    protected $group       = 'Loan';
    protected $name        = 'loan:update-statuses';
    protected $description = 'Update loan statuses to overdue if needed.';

    public function run(array $params)
    {
        $loanModel = new LoanModel();

        // Fetch all active loans
        $activeLoans = $loanModel->where('status', 'active')->findAll();

        if (empty($activeLoans)) {
            CLI::write('No active loans to update.', 'yellow');
            return;
        }

        $updatedLoans = 0;

        // Iterate through each active loan
        foreach ($activeLoans as $loan) {
            $loan_due_date = strtotime($loan['loan_due_date']);
            $current_date = strtotime(date('Y-m-d')); // Current date

            // Check if the loan is overdue
            if ($current_date > $loan_due_date) {
                $loanModel->update($loan['loan_id'], ['status' => 'overdue']);
                $updatedLoans++;
            }
        }

        CLI::write("$updatedLoans loans updated to overdue status.", 'green');
    }
}
