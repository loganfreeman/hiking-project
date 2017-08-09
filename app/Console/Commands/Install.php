<?php

namespace GrahamCampbell\BootstrapCMS\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Events\Dispatcher;

class Install extends Command implements SelfHandling
{

    /**
     * The command name.
     *
     * @var string
     */
    protected $name = 'cms:install';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Install the cms';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the command.
     *
     * @return void
     */
    public function handle()
    {
        print($this->name);
    }
}
