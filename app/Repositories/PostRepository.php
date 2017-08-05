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
use GrahamCampbell\BootstrapCMS\Models\Category;
use GrahamCampbell\BootstrapCMS\Models\User;
use GrahamCampbell\BootstrapCMS\Models\Post;
use GrahamCampbell\BootstrapCMS\Facades\CategoryRepository;

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
      return Category::where('name', $category_name)->first()->posts;
    }

    public function paginateWithCategory($category_name)
    {
      $category_id = CategoryRepository::findByName($category_name)->id;
      $model = $this->model;
      $paginator = $model::join('post_categories', 'posts.id', '=', 'post_categories.post_id')
      ->where('post_categories.category_id', $category_id)
      ->select('posts.*')
      ->paginate($model::$paginate);

      if (count($paginator)) {
          $this->paginateLinks = $paginator->appends(['category' => $category_name])->render();
      }

      return $paginator;
    }

    public function paginateFavorited($userId)
    {
        $model = $this->model;
        $paginator = $model::join('favorites', 'posts.id', '=', 'favorites.post_id')
                ->join('users', 'favorites.user_id', '=', 'users.id')
                ->where('favorites.user_id', $userId)->select('posts.*')
                ->paginate($model::$paginate);

        if (count($paginator)) {
            $this->paginateLinks = $paginator->appends(['favorited' => 1])->render();
        }

        return $paginator;
    }


}
