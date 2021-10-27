<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class ImportProductsData extends Command
{
    /** @var string FILE_PATH */
    const FILE_PATH = 'data_import_files/';

    /** @var string FILE_NAME */
    const FILE_NAME = 'products.csv';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:products-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import products table data.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle(): void
    {
        $path = self::FILE_PATH . self::FILE_NAME;

        if (!file_exists($path)) {
            $this->error('File does not exists');

            return;
        }

        $this->importData($path);
    }

    /**
     * Import data.
     *
     * @param string $path
     * @return void
     */
    private function importData(string $path): void
    {
        $file = fopen($path, "r");
        $header = true;
        while ($row = fgetcsv($file, null, ",")) {
            if ($header) {
                $header = false;
                continue;
            }

            $sku = $row[0];
            $name = $row[1];

            if ($sku === '' || $name === '') {
                $this->error(json_encode($row));
                continue;
            }

            // Sku already exists in database.
            if (Product::where('sku', $sku)->exists()) {
                $this->error("$sku already exists.");
                continue;
            }

            Product::create(['sku' => $sku, 'name' => $name]);
        }

        $this->info('Products imported successfully!!');
    }
}
