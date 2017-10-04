<?php

namespace GrahamCampbell\BootstrapCMS\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Events\Dispatcher;

use GrahamCampbell\BootstrapCMS\Seeds\CategoriesTableSeeder;
use GrahamCampbell\Credentials\Facades\Credentials;
use Carbon\Carbon;

class DB extends Command implements SelfHandling
{

    /**
     * The command name.
     *
     * @var string
     */
    protected $name = 'cms:db';

    /**
     * The command description.
     *
     * @var string
     */
    protected $description = 'Create admin users';


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
      try {
        $this->createMigrations();
      } catch(\Exception $e) {
        $this->error('Can not connect to the database. Error: ' . $e->getMessage());
        $this->error('Make sure your database credentials in .env are correct');

        return;
      }

      $this->createUser();
    }

    private function createMigrations()
    {
      $this->info('TRYING TO MIGRATE DATABASE');
      $this->call('migrate', ['--force' => true]);
      $this->info('MIGRATION COMPLETED');
    }

    private function createUser()
    {
      $firstname = $this->ask('Enter your admin first name');
      $lastname = $this->ask('Enter your admin last name');
      $email = $this->ask('Enter your admin email');
      $password = $this->ask('Enter your admin password');

      $user = [
          'first_name'   => $firstname,
          'last_name'    => $lastname,
          'email'        => $email,
          'password'     => $password,
          'activated'    => 1,
          'activated_at' => Carbon::now(),
      ];
      Credentials::getUserProvider()->create($user);
      Credentials::getUserProvider()->findByLogin($email)
          ->addGroup(Credentials::getGroupProvider()->findByName('Admins'));
    }
}
