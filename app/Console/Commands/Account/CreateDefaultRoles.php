<?php

namespace App\Console\Commands\Account;

use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class CreateDefaultRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'account:create-default-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create default roles';

    /**
     * Execute the console command.
     */
    public function handle()
    {

    }
}
