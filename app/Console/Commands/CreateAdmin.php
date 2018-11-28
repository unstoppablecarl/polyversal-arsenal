<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Admin';

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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->make(env('ADMIN_EMAIL_1'), env('ADMIN_NAME_1'), env('ADMIN_PASS_1'));
        $this->make(env('ADMIN_EMAIL_2'), env('ADMIN_NAME_2'), env('ADMIN_PASS_2'));
    }

    protected function make($email, $name, $password)
    {
        $where = [
            'email' => $email,
        ];

        $attributes = array_merge($where, [
            'name'              => $name,
            'password'          => Hash::make($password),
            'is_admin'          => 1,
            'email_verified_at' => Carbon::now(),
        ]);

        $user = User::query()
            ->where('email', $email)
            ->first();

        if (!$user) {
            User::query()->forceCreate($attributes);
        }
    }
}
