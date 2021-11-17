<?php

namespace App\Console\Commands;

use App\Models\Customer;
use App\Services\ImportCustomerService;
use Illuminate\Console\Command;

class ImportCustomersFromCsv extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:customers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import customers from a given CSV file';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (Customer::all()->count()) {
            return Command::SUCCESS;
        }
        $customersFile = fopen(storage_path('csv/customers.csv'), 'r');
        $customersService = new ImportCustomerService();
        $header = fgetcsv($customersFile);
        while ($row = fgetcsv($customersFile)) {
            $customersService->createCustomerFromFile(array_combine($header, $row));
        }
        return Command::SUCCESS;
    }
}
