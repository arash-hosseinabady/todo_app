<?php

namespace App\Http\Repositories;

use App\Models\TodoList;

class TodoListRepo
{
    public function getList()
    {
        return TodoList::where('user_id', auth()->id())
            ->get();
    }

    public function getById($id)
    {
        return TodoList::where('id', $id)
            ->where('user_id', auth()->id())
            ->first();
    }
}
