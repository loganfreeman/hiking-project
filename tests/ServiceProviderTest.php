<?php

/*
 * This file is part of Bootstrap CMS.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\BootstrapCMS;

use GrahamCampbell\TestBenchCore\LaravelTrait;
use GrahamCampbell\TestBenchCore\ServiceProviderTrait;

/**
 * This is the service provider test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class ServiceProviderTest extends AbstractTestCase
{
    use LaravelTrait, ServiceProviderTrait;

    public function testNavigationFactoryIsInjectable()
    {
        $this->assertIsInjectable('App\Navigation\Factory');
    }

    public function testCommentRepositoryIsInjectable()
    {
        $this->assertIsInjectable('App\Repositories\CommentRepository');
    }

    public function testEventRepositoryIsInjectable()
    {
        $this->assertIsInjectable('App\Repositories\EventRepository');
    }

    public function testPageRepositoryIsInjectable()
    {
        $this->assertIsInjectable('App\Repositories\PageRepository');
    }

    public function testPostRepositoryIsInjectable()
    {
        $this->assertIsInjectable('App\Repositories\PostRepository');
    }
}
