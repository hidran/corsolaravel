<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Album;
use App\Models\Photo;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        \DB::statement('SET FOREIGN_KEY_CHECKS=0');
        User::truncate();
        Album::truncate();
        Photo::truncate();
        User::factory(20)->has(
            Album::factory(10)->has(
                Photo::factory(200)
            )
        )->create();
        /*  $this->call(UserSeeder::class);
          $this->call(AlbumSeeder::class);
          $this->call(PhotoSeeder::class);
  */
    }
}
