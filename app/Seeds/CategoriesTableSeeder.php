<?php

/*
 * This file is part of Bootstrap CMS.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * This is the events table seeder class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeding.
     *
     * @return void
     */
    public function run()
    {
        DB::table('categories')->truncate();

        $category = [
            'name'      => 'restaurant',
            'description'   => 'restaurant category',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        $categories[] = $category;

        $category = [
            'name'      => 'hiking',
            'description'   => 'hiking category',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        $categories[] = $category;

        foreach($categories as $category) {
          DB::table('categories')->insert($category);
        }

    }
}
