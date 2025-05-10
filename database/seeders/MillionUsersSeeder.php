<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Faker\Factory as Faker;

class MillionUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $startTime = microtime(true);
        
        DB::statement('SET FOREIGN_KEY_CHECKS=0');
        DB::connection()->disableQueryLog();

        $faker = Faker::create();
        $batchSize = 5000;
        $totalUsers = 1000000;
        $hashedPassword = Hash::make('password');
        $now = now();
        $lastUserId = DB::table('users')->max('id') ?? 0;
        
        $this->command->info('Starting to seed users and addresses...');
        $this->command->getOutput()->progressStart($totalUsers / $batchSize);
        
        for ($i = 0; $i < $totalUsers; $i += $batchSize) {
            DB::beginTransaction();
            
            try {
                $usersBatch = [];
                $addressesBatch = [];
                
                $countries = [];
                $cities = [];
                $postcodes = [];
                $streets = [];
                
                for ($k = 0; $k < 100; $k++) {
                    $countries[] = $faker->country();
                    $cities[] = $faker->city();
                    $postcodes[] = $faker->postcode();
                    $streets[] = $faker->streetAddress();
                }
                
                for ($j = 0; $j < $batchSize && ($i + $j) < $totalUsers; $j++) {
                    $userId = $lastUserId + $i + $j + 1;
                    $uniqueSuffix = Str::random(8);
                    
                    $usersBatch[] = [
                        'first_name' => $faker->firstName(),
                        'last_name' => $faker->lastName(),
                        'email' => 'user' . $userId . '_' . $uniqueSuffix . '@example.com',
                        'email_verified_at' => $now,
                        'password' => $hashedPassword,
                        'remember_token' => Str::random(10),
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                    
                    $addressesBatch[] = [
                        'user_id' => $userId,
                        'country' => $countries[array_rand($countries)],
                        'city' => $cities[array_rand($cities)],
                        'post_code' => $postcodes[array_rand($postcodes)],
                        'street' => $streets[array_rand($streets)],
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                
                DB::table('users')->insert($usersBatch);
                DB::table('addresses')->insert($addressesBatch);
                
                DB::commit();
                
                unset($usersBatch);
                unset($addressesBatch);
                
                $this->command->getOutput()->progressAdvance();
            } catch (\Exception $e) {
                DB::rollBack();
                $this->command->error('Error during batch insertion: ' . $e->getMessage());
            }
        }
        
        $this->command->getOutput()->progressFinish();
        DB::statement('SET FOREIGN_KEY_CHECKS=1');
        
        $endTime = microtime(true);
        $executionTime = round($endTime - $startTime, 2);
        
        $this->command->info('Successfully seeded 1 million users and addresses!');
        $this->command->info("Execution time: {$executionTime} seconds");
    }
}
