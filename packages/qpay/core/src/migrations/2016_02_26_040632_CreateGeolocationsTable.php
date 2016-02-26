<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGeolocationsTable extends Migration
{
    // Free database from:
    // http://www.unitedstateszipcodes.org/zip-code-database/
    const ZIP_CSV = 'zip_code_database.csv';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('geolocations', function (Blueprint $table) {
            $table->string('zip', 5)->unique();
            $table->string('city');
            $table->string('state', 2);
            $table->string('county');
            $table->string('timezone');
            $table->decimal('lat', 6, 3);
            $table->decimal('long', 6, 3);
            $table->primary('zip');
        });

        // Note from Martin: I'm not sure how to publish database seeds from a Laravel package, so I chose to perform
        // the seed during migration; this isn't sample data, it's needed for city/state lookups.
        $this->_seedGeocodeData();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('geolocations');
    }

    private function _seedGeocodeData()
    {
        echo "Loading ZIP code database. This will take a minute or two; thanks for your patience.\n";
        $csv_path = __DIR__ . '/' . self::ZIP_CSV;
        $file = fopen($csv_path, 'r');
        $columns = fgetcsv($file);

        // Given more time, this could be made more efficient, at least by batching groups of records per query - Martin
        while ($input_row = fgetcsv($file)) {
            $input_row = array_combine($columns, $input_row);
            $db_row = [
                'zip' => $input_row['zip'],
                'city' => $input_row['primary_city'],
                'state' => $input_row['state'],
                'county' => $input_row['county'],
                'timezone' => $input_row['timezone'],
                'lat' => $input_row['latitude'],
                'long' => $input_row['longitude'],
            ];

            DB::table('geolocations')->insert($db_row);
        }

        fclose($file);
    }
}
