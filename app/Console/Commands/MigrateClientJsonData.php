<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateClientJsonData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clients:migrate-json';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate client JSON string fields to native BSON objects/arrays';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration of Client JSON fields...');

        $clients = \App\Models\Client::all();
        $migratedCount = 0;

        foreach ($clients as $client) {
            $updated = false;
            $dataToUpdate = [];

            $fieldsToCheck = ['services', 'business_info', 'ai_config', 'offers'];

            foreach ($fieldsToCheck as $field) {
                // Check if the attribute is literally a string that looks like JSON
                if (is_string($client->$field)) {
                    $decoded = json_decode($client->$field, true);
                    
                    if (json_last_error() === JSON_ERROR_NONE) {
                        $dataToUpdate[$field] = $decoded;
                        $updated = true;
                    }
                }
            }

            if ($updated) {
                $client->update($dataToUpdate);
                $migratedCount++;
            }
        }

        $this->info("Migration completed. Successfully migrated {$migratedCount} clients.");
    }
}
