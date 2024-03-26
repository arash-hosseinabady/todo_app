<?php

namespace App\Console\Commands;

use App\Enums\TodoStates;
use App\Models\TodoList;
use App\Models\TodoLog;
use Carbon\Carbon;
use Illuminate\Console\Command;

class ChangeStateTodo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:change-state-todo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $_2daysAgo = time() - (24 * 60 * 60);
        $list = TodoList::where('created_at', '<', $_2daysAgo)
            ->get();

        $todoLogs = [];
        $timestamp = time();
        foreach ($list as $todo) {
            if ($todo->lastLog->state != TodoStates::DONE->value) {
                $todoLogs[] = [
                    'todo_id' => $todo->id,
                    'state' => TodoStates::DONE->value,
                    'created_at' => $timestamp,
                ];
            }
        }

        TodoLog::insert($todoLogs);
    }
}
