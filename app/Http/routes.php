<?php

/*
 * This file is part of Bootstrap CMS.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// send users to the home page
$router->get('/', ['as' => 'base', function () {
    Session::flash('', ''); // work around laravel bug if there is no session yet
    Session::reflash();

    return Redirect::to('blog/posts');
}]);

// send users to the posts page
if (Config::get('cms.blogging')) {
    $router->get('blog', ['as' => 'blog', function () {
        Session::flash('', ''); // work around laravel bug if there is no session yet
        Session::reflash();

        return Redirect::route('blog.posts.index');
    }]);
}

$router->get('news', ['as' => 'news', 'uses' => 'PostController@news']);

$router->post('search/posts', ['as' => 'searchPosts', 'uses' => 'PostController@search']);

$router->get('search/posts', ['as' => 'searchPosts', 'uses' => 'PostController@search']);

$router->get('post/{id}/islikedbyme', ['as' => 'isLikedByMe', 'uses' => 'PostController@isLikedByMe']);
$router->post('post/like',  ['as' => 'like', 'uses' => 'PostController@like']);

$router->get('post/{id}/isfavoritedbyme', ['as' => 'isFavoriteByMe', 'uses' => 'PostController@isFavoritedByMe']);
$router->post('post/favorite',  ['as' => 'favorite', 'uses' => 'PostController@favorite']);

// page routes
$router->resource('pages', 'PageController');

// blog routes
if (Config::get('cms.blogging')) {
    $router->resource('blog/posts', 'PostController');
    $router->resource('blog/posts.comments', 'CommentController');
}

// event routes
if (Config::get('cms.events')) {
    $router->resource('events', 'EventController');
    $router->post('event/signup',  ['as' => 'event_signup', 'uses' => 'EventController@signup']);
    $router->get('event/{id}/isSignupedbyme', ['as' => 'isSignupedbyme', 'uses' => 'EventController@isSignupedbyme']);
}

$router->resource('movies', 'MoviesController');
