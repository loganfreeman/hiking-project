<?php

/*
 * This file is part of Bootstrap CMS.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\BootstrapCMS\Repositories;

use GrahamCampbell\Credentials\Repositories\AbstractRepository;
use GrahamCampbell\Credentials\Repositories\PaginateRepositoryTrait;
use GrahamCampbell\BootstrapCMS\Facades\CategoryRepository;
use Illuminate\Support\Facades\Log;

/**
 * This is the post repository class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class PostRepository extends AbstractRepository
{
    use PaginateRepositoryTrait;

    public function search($term) {
      $model = $this->model;
      return $model::search($term);
    }

    public function findByCategory($category_name)
    {
      $category_id = CategoryRepository::findByName($category_name)->id;
      $model = $this->model;
      return $model::join('post_categories', 'posts.id', '=', 'post_categories.post_id')->where('post_categories.category_id', $category_id)->get();
    }


}
