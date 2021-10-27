<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ImportUsersData extends Command
{
    /** @var string FILE_PATH */
    const FILE_PATH = 'data_import_files/';

    /** @var string FILE_NAME */
    const FILE_NAME = 'users.csv';

    /** @var string DEFAULT_PASSWORD */
    const DEFAULT_PASSWORD = 'secret';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:users-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import users table data.';

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

            $email = $row[2];

            // If the user email is not present then skip the row and show it as error on the console.
            if ($email === '') {
                $this->error(json_encode($row));
                continue;
            }

            // User already exists in database.
            if (User::where('email', $email)->exists()) {
                continue;
            }

            $user = [
                'name' => $row[1],
                'email' => $email,
                'password' => $row[3] === '' ? Hash::make(self::DEFAULT_PASSWORD) : Hash::make($row[3]),
            ];

            $id = $row[0];

            // If the id is not present then create the user or else add it to users array for bulk insert.
            if ($id !== '') {
                $user['id'] = $id;
            }

            User::create($user);
        }

        $this->info('Users imported successfully!!');
    }
}
