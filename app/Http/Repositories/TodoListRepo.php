<?php

namespace App\Http\Repositories;

use App\Models\TodoList;

class TodoListRepo
{
    public function getList()
    {
        return TodoList::get();
    }

    public function getById($id)
    {
        return TodoList::where('id', $id)
            ->first();
    }
}
