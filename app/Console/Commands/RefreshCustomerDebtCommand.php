<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Models\CustomerCredit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RefreshCustomerDebtCommand extends Command
{
    protected $signature = 'hutang:refresh 
                            {--customer= : Refresh specific customer only (by ID)}
                            {--dry-run : Show what would change without saving}';

    protected $description = 'Refresh customer debt totals and credit statuses from actual payment records';

    public function handle()
    {
        $dryRun = $this->option('dry-run');
        $customerId = $this->option('customer');

        if ($dryRun) {
            $this->warn('🔍 DRY RUN MODE — no changes will be saved.');
            $this->newLine();
        }

        $this->info('📊 Refreshing customer debt data...');
        $this->newLine();

        // Step 1: Recalculate paid_amount + status for each active CustomerCredit
        $creditQuery = CustomerCredit::whereIn('status', ['unpaid', 'partial']);
        if ($customerId) {
            $creditQuery->where('customer_id', $customerId);
        }

        $credits = $creditQuery->get();
        $fixed = 0;

        $this->info("Checking {$credits->count()} active credit records...");

        foreach ($credits as $credit) {
            $actualPaid = (float) $credit->payments()->sum('amount');
            $actualStatus = $actualPaid >= (float) $credit->amount ? 'paid' : ($actualPaid > 0 ? 'partial' : 'unpaid');

            $needsFix = abs((float) $credit->paid_amount - $actualPaid) > 0.01 || $credit->status !== $actualStatus;

            if ($needsFix) {
                $this->line("  ⚠ Credit #{$credit->credit_number}: " .
                    "paid_amount {$credit->paid_amount} → {$actualPaid}, " .
                    "status {$credit->status} → {$actualStatus}");

                if (!$dryRun) {
                    $credit->paid_amount = $actualPaid;
                    $credit->status = $actualStatus;
                    $credit->save();
                }
                $fixed++;
            }
        }

        $this->newLine();
        $this->info($fixed > 0 ? "Fixed {$fixed} credit record(s)." : 'All credit records are correct.');
        $this->newLine();

        // Step 2: Refresh current_debt for all customers
        $customerQuery = Customer::query();
        if ($customerId) {
            $customerQuery->where('id', $customerId);
        }

        $customers = $customerQuery->get();
        $debtFixed = 0;

        $this->info("Checking {$customers->count()} customer debt balances...");

        foreach ($customers as $customer) {
            $actualDebt = $customer->credits()
                ->where('type', 'debt')
                ->whereIn('status', ['unpaid', 'partial'])
                ->get()
                ->sum(fn($c) => max(0, (float) $c->amount - (float) $c->paid_amount));

            if (abs((float) $customer->current_debt - $actualDebt) > 0.01) {
                $this->line("  ⚠ {$customer->name}: " .
                    "current_debt {$customer->current_debt} → " . number_format($actualDebt, 2, '.', ''));

                if (!$dryRun) {
                    $customer->update(['current_debt' => $actualDebt]);
                }
                $debtFixed++;
            }
        }

        $this->newLine();
        $this->info($debtFixed > 0 ? "Fixed {$debtFixed} customer balance(s)." : 'All customer balances are correct.');

        // Step 3: Count overdue
        $overdueCount = CustomerCredit::whereIn('status', ['unpaid', 'partial'])
            ->where('due_date', '<', now())
            ->count();

        $this->newLine();
        $this->info("📋 Overdue records: {$overdueCount}");

        if ($dryRun) {
            $this->newLine();
            $this->warn('🔍 Dry run complete. Run without --dry-run to apply changes.');
        } else {
            $this->newLine();
            $this->info('✅ Refresh complete!');
        }

        return Command::SUCCESS;
    }
}
