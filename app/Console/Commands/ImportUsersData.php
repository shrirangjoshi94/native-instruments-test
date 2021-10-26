<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\{Hash, Storage};

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
     *
     * @todo test cases
     * check if firing the command before the db has 0 records and after firing it should have 10 records.
     * temp db in memory
     * DB driver sqlite //in phpunitxml
     *
     * 1) check if file path exists  then error print assert that error will come
     * 2) inital 0 after command hit these many records
     * 3) name same email
     */
    public function handle(): void
    {
        $path = self::FILE_PATH . self::FILE_NAME;

        if (!Storage::disk('local')->exists($path)) {
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
        $file = fopen(Storage::disk('local')->path($path), "r");
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
