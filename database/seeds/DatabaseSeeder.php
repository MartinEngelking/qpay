<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    const TRANSACTION_CSV = 'transactions.csv';
    const TRANSACTIONS  = 'transactions';

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->seedTransactions();  // todo: This should be defined in its own seeder class
    }

    protected function seedTransactions()
    {
        $csv_path = __DIR__ . '/' . self::TRANSACTION_CSV;
        $file = fopen($csv_path, 'r');
        $columns = fgetcsv($file);

        // For a small file, it's actually more efficient to read it all into memory and send it over to the DB
        // in a single query. This approach handles arbitrarily large datasets well.
        while($row = fgetcsv($file)) {
            // Turn row into associative array
            $transaction_attributes = array_combine($columns, $row);
            DB::table(self::TRANSACTIONS)->insert($transaction_attributes);
        }

        fclose($file);
    }

}
