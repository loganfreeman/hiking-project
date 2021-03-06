<?php

/*
 * This file is part of Bootstrap CMS.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace GrahamCampbell\Tests\BootstrapCMS\Facades;

use GrahamCampbell\TestBenchCore\FacadeTrait;
use GrahamCampbell\TestBenchCore\HelperTrait;
use GrahamCampbell\Tests\BootstrapCMS\AbstractTestCase;

/**
 * This is the comment repository facade test class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class CommentRepositoryTest extends AbstractTestCase
{
    use FacadeTrait, HelperTrait;

    /**
     * Get the facade accessor.
     *
     * @return string
     */
    protected function getFacadeAccessor()
    {
        return 'commentrepository';
    }

    /**
     * Get the facade class.
     *
     * @return string
     */
    protected function getFacadeClass()
    {
        return 'App\Facades\CommentRepository';
    }

    /**
     * Get the facade route.
     *
     * @return string
     */
    protected function getFacadeRoot()
    {
        return 'App\Repositories\CommentRepository';
    }
}
