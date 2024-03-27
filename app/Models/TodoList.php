<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TodoList extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'todo_lists';
    protected $dateFormat = 'U';
    protected $casts = [
        'created_at' => 'timestamp',
        'updated_at' => 'timestamp',
        'deleted_at' => 'timestamp'
    ];

    protected $fillable = [
        'title',
        'desc',
        'user_id',
    ];

    public function logs()
    {
        return $this->hasMany(TodoLog::class, 'todo_id', 'id');
    }

    public function lastLog()
    {
        return $this->hasOne(TodoLog::class, 'todo_id', 'id')
            ->orderBy('id', 'desc');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
