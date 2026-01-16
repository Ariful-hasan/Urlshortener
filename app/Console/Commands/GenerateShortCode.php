<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Services\KeyGenerationService;

class GenerateShortCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-short-code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate a short code';

    /**
     * Execute the console command.
     */
    public function handle(KeyGenerationService $keyGenerationService): void
    {
        $this->info('Start short code generation');

        try {
            $targetPoolSize = config('constants.CODE_POOL_TARGET_SIZE');
            $currentCount = DB::table('code_pool')->count();

            // 1. Check if we need to refill
            if ($currentCount < $targetPoolSize) {
                $needed = $targetPoolSize - $currentCount;
                $newCodes = [];

                // Use a for loop so $needed stays constant
                for ($i = 0; $i < $needed; $i++) {
                    $newCodes[] = ['code' => $keyGenerationService->generate()];

                    // Batch insert to save memory and DB pressure
                    if (count($newCodes) >= config('constants.CODE_POOL_BATCH_SIZE')) {
                        DB::table('code_pool')->insert($newCodes);
                        $newCodes = [];
                    }
                }

                // Insert remaining
                if (!empty($newCodes)) {
                    DB::table('code_pool')->insert($newCodes);
                }
            }

            $this->info('Short code generation completed successfully.');
        } catch (\Throwable $th) {
            $this->error('Error generating short code: ' . $th->getMessage());

            return;
        }
    }
}
