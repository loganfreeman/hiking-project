<?php

/*
 * This file is part of Bootstrap CMS.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\BootstrapCMS\Seeds;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use GrahamCampbell\BootstrapCMS\Models\Post;
use GrahamCampbell\BootstrapCMS\Models\PostCategory;
use GrahamCampbell\BootstrapCMS\Facades\CategoryRepository;


/**
 * This is the posts table seeder class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeding.
     *
     * @return void
     */
    public function run()
    {
        DB::table('posts')->truncate();

        $post = [
            'title'      => 'Hello World',
            'summary'    => 'This is the first blog post.',
            'body'       => 'This is an example blog post.',
            'user_id'    => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];

        DB::table('posts')->insert($post);
    }


    public function generateFakePosts($count)
    {
      $faker = \Faker\Factory::create();
      $categories = CategoryRepository::index();

      for ($i = 1; $i <= $count; $i++) {
        $post = new Post;
        $post->title = $faker->realText(10);
        $post->summary = $faker->realText(50);
        $post->body = implode('   ', $faker->paragraphs(3));
        $post->user_id = 1;
        $post->save();
        $post->categories()->sync([rand(1, count($categories))]);

      }

    }
}
