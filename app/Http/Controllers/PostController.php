<?php

/*
 * This file is part of Bootstrap CMS.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\BootstrapCMS\Http\Controllers;

use GrahamCampbell\Binput\Facades\Binput;
use GrahamCampbell\BootstrapCMS\Facades\PostRepository;
use GrahamCampbell\Credentials\Facades\Credentials;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Request;
use GrahamCampbell\BootstrapCMS\Models\Like;
use GrahamCampbell\BootstrapCMS\Models\PostCategory;
use Illuminate\Support\Facades\Response;
use Exception;
use Illuminate\Support\Facades\Input;
use GrahamCampbell\BootstrapCMS\Facades\CategoryRepository;
use Illuminate\Support\Facades\Log;


/**
 * This is the post controller class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class PostController extends AbstractController
{
    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setPermissions([
            'create'  => 'blog',
            'store'   => 'blog',
            'edit'    => 'blog',
            'update'  => 'blog',
            'destroy' => 'blog',
        ]);

        parent::__construct();
    }

    public function search(Request $request) {
      $term = $request->input('srch-term');
      $posts = PostRepository::search($term);
      return View::make('posts.index', ['posts' => $posts]);
    }

    /**
     * Display a listing of the posts.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {

        $categories = CategoryRepository::index();

        $category = $request->input('category');

        $posts = PostRepository::paginate();
        $links = PostRepository::links();

        return View::make('posts.index', ['posts' => $posts, 'links' => $links, 'categories' => $categories]);
    }

    /**
     * Show the form for creating a new post.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $categories = CategoryRepository::index();
        return View::make('posts.create', ['categories' => $categories]);
    }

    /**
     * Store a new post.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = array_merge(['user_id' => Credentials::getuser()->id], Binput::only([
            'title', 'summary', 'body',
        ]));

        $categories = $request->input('categories');

        if(is_null($categories)) {
          $categories = [];
        }

        Log::debug('Create categories: '.implode(',', $categories));

        $val = PostRepository::validate($input, array_keys($input));
        if ($val->fails()) {
            return Redirect::route('blog.posts.create')->withInput()->withErrors($val->errors());
        }

        $post = PostRepository::create($input);

        $this->addPostToCategory($post, $categories);

        return Redirect::route('blog.posts.show', ['posts' => $post->id])
            ->with('success', trans('messages.post.store_success'));
    }

    /**
     * Show the specified post.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        $post = PostRepository::find($id);
        $this->checkPost($post);

        $comments = $post->comments()->orderBy('id', 'desc')->get();

        $likes = count($post->likes()->get());

        return View::make('posts.show', ['post' => $post, 'comments' => $comments, 'likes' => $likes]);
    }

    /**
     * Show the form for editing the specified post.
     *
     * @param int $id
     *
     * @return \Illuminate\View\View
     */
    public function edit($id)
    {
        $post = PostRepository::find($id);
        $this->checkPost($post);
        $categories = CategoryRepository::index();

        $postCategories = PostCategory::wherePostId($post->id)->get();

        foreach ($categories as $category) {

          $category->checked = false;

          foreach ($postCategories as $postCategory) {
            if($category->id == $postCategory->category_id){
              $category->checked = true;
              break;
            }
          }
        }

        return View::make('posts.edit', ['post' => $post, 'categories' => $categories]);
    }

    /**
     * Update an existing post.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $input = Binput::only(['title', 'summary', 'body']);

        $val = PostRepository::validate($input, array_keys($input));
        if ($val->fails()) {
            return Redirect::route('blog.posts.edit', ['posts' => $id])->withInput()->withErrors($val->errors());
        }

        $post = PostRepository::find($id);
        $this->checkPost($post);

        $post->update($input);

        $categories = $request->input('categories');

        if(is_null($categories)) {
          $categories = [];
        }

        Log::debug('Update categories: '.implode(',', $categories));

        $this->addPostToCategory($post, $categories);

        return Redirect::route('blog.posts.show', ['posts' => $post->id])
            ->with('success', trans('messages.post.update_success'));
    }

    /**
     * Delete an existing post.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = PostRepository::find($id);
        $this->checkPost($post);

        $post->delete();

        return Redirect::route('blog.posts.index')
            ->with('success', trans('messages.post.delete_success'));
    }

    /**
     * Check the post model.
     *
     * @param mixed $post
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     *
     * @return void
     */
    protected function checkPost($post)
    {
        if (!$post) {
            throw new NotFoundHttpException('Post Not Found');
        }
    }


    public function isLikedByMe($id)
    {

        if(is_null($id)) {
          throw new Exception('No post ID provided');
        }
        $post = PostRepository::find($id);
        if(is_null($post)) {
          throw new Exception('No post found with ID ' . $id);
        }
        $data = false;
        $userId = Credentials::getuser()->id;
        if (Like::whereUserId($userId)->wherePostId($post->id)->exists()){
            $data = true;
        }
        return Response::json($data);
    }

    public function like()
    {
        $id = Input::get('id');
        $post = PostRepository::find($id);
        $userId = Credentials::getuser()->id;
        $existing_like = Like::wherePostId($post->id)->whereUserId($userId)->first();

        if (is_null($existing_like)) {
            $existing_like = Like::create([
                'post_id' => $post->id,
                'user_id' => $userId
            ]);
        } else {
            if (is_null($existing_like->deleted_at)) {
                $existing_like->delete();
            } else {
                $existing_like->restore();
            }
        }

        return Response::json($existing_like);
    }

    public function addPostToCategory($post, $categories)
    {
      foreach ($categories as $category_id) {
        $existing = PostCategory::wherePostId($post->id)->whereCategoryId($category_id)->first();
        if(is_null($existing)) {
          PostCategory::create(['post_id' => $post->id, 'category_id' => $category_id]);
        }
      }

      foreach ($post->categories as $post_category) {
        $category_id = $post_category['id'];
        Log::debug('Existing category ID ' . $category_id);
        if (!in_array($category_id, $categories)) {
            Log::debug('Deleting category ' . $category_id);
            $existing = PostCategory::wherePostId($post->id)->whereCategoryId($category_id)->first();
            if(!is_null($existing)) {
              $existing->delete();
            }
        }
      }
    }
}
