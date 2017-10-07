<?php

/*
 * This file is part of Bootstrap CMS.
 *
 * (c) Graham Campbell <graham@alt-three.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Subscribers;

use App\Repositories\PageRepository;
use Illuminate\Console\Command;
use Illuminate\Events\Dispatcher;

/**
 * This is the command subscriber class.
 *
 * @author Graham Campbell <graham@alt-three.com>
 */
class CommandSubscriber
{
    /**
     * The page repository instance.
     *
     * @var \App\Repositories\PageRepository
     */
    protected $pagerepository;

    /**
     * Create a new instance.
     *
     * @param \App\Repositories\PageRepository $pagerepository
     *
     * @return void
     */
    public function __construct(PageRepository $pagerepository)
    {
        $this->pagerepository = $pagerepository;
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param \Illuminate\Events\Dispatcher $events
     *
     * @return void
     */
    public function subscribe(Dispatcher $events)
    {
        $events->listen(
            'command.updatecache',
            'App\Subscribers\CommandSubscriber@onUpdateCache',
            3
        );
    }

    /**
     * Handle a command.updatecache event.
     *
     * @param \Illuminate\Console\Command $command
     *
     * @return void
     */
    public function onUpdateCache(Command $command)
    {
        $command->line('Regenerating page cache...');
        $this->pagerepository->refresh();
        $command->info('Page cache regenerated!');
    }

    /**
     * Get the page repository instance.
     *
     * @return \App\Repositories\PageRepository
     */
    public function getPageRepository()
    {
        return $this->pagerepository;
    }
}
