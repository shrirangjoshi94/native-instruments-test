<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\{Product, User};
use Illuminate\Console\Command;

class ImportUsersPurchasedProductsData extends Command
{
    /** @var string FILE_PATH */
    const FILE_PATH = 'data_import_files/';

    /** @var string FILE_NAME */
    const FILE_NAME = 'purchased.csv';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:users-purchased-products-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import user_purchased_products table data.';

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

        $file = fopen($path, "r");
        $header = true;

        while ($row = fgetcsv($file, null, ",")) {
            if ($header) {
                $header = false;
                continue;
            }

            $this->importData($row);
        }

        $this->info('User products data imported successfully!!');
    }

    /**
     * Import data.
     *
     * @param array $row
     * @return void
     */
    private function importData(array $row): void
    {
        $userId = $row[0];
        $sku = $row[1];

        if ($userId === '' || $sku === '') {
            $this->error(json_encode($row));
            return;
        }

        $user = User::find($userId);

        if (!$user) {
            $this->error("User with ID $userId does not exists.");
            return;
        }

        $product = Product::where('sku', $sku)->first();

        if (!$product) {
            $this->error("Product with sku number: $sku does not exists.");
            return;
        }

        $this->syncUserPurchasedProducts($user, $product);
    }

    /**
     * Insert data into user_purchased_products table.
     *
     * @param User $user
     * @param Product $product
     * @return void
     */
    private function syncUserPurchasedProducts(User $user, Product $product): void
    {
        $userPurchasedProduct = $user->products()->where('product_id', $product->id)->first();

        // If the product is already purchased by the use then just update the sku.
        if ($userPurchasedProduct) {
            $userPurchasedProduct->update(['sku' => $product->sku]);

            return;
        }

        // Insert data into user_purchased_products table.
        $user->products()->attach([$product->id => ['sku' => $product->sku]]);
    }
}
