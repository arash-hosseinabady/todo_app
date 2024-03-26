<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TodoLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'todo_logs';
    protected $dateFormat = 'U';
    protected $casts = [
        'created_at' => 'timestamp',
        'deleted_at' => 'timestamp'
    ];

    protected $fillable = [
        'todo_id',
        'state',
    ];

    public function todo()
    {
        return $this->hasOne(TodoList::class, 'id', 'todo_id');
    }
}
