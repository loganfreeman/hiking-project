<?php

/*
 * This file is part of Bootstrap CMS.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Seeder;

/**
 * This is the database seeder class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeding.
     *
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        $this->call('App\Seeds\GroupsTableSeeder');
        $this->call('App\Seeds\UsersTableSeeder');
        $this->call('App\Seeds\UsersGroupsTableSeeder');

        $this->call('App\Seeds\PagesTableSeeder');
        $this->call('App\Seeds\PostsTableSeeder');
        $this->call('App\Seeds\CommentsTableSeeder');
        $this->call('App\Seeds\EventsTableSeeder');
    }
}
