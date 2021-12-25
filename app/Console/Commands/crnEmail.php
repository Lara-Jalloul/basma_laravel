<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class crnEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send emails for all every day.';

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
     * @return int
     */
    
     public function handle()
     {
        $UsersCount = User::count();
        $users = User::all();
        
        foreach ($users as $user){
            Mail::raw("The total number of new registeration: ". $UsersCount, function($message) use ($user){
                $message->from('laraadjalloul@gmail.com');
                $message->to($user->email)->subject('Daily New Messages!');
            });
        }
        $this->info('Emails sent daily to everyone successfully.');
    }
}
