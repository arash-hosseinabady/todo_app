<?php

namespace App\Http\Controllers;

use App\Enums\TodoStates;
use App\Http\Repositories\TodoListRepo;
use App\Http\Requests\TodoListRequest;
use App\Http\Resources\TodoListResource;
use App\Models\TodoList;
use App\Models\TodoLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class TodoListController extends Controller
{
    public function __construct(private TodoListRepo $repository)
    {
        $this->resource = TodoListResource::class;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $list = $this->repository->getList();

        return response()->json([
            'message' => __('todo_list'),
            'data' => $list ? $this->resource::collection($list) : null,
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TodoListRequest $request)
    {
        $data = $request->all();
        $msg = __('fail_register_operation');
        $res = Response::HTTP_NOT_IMPLEMENTED;

        DB::beginTransaction();
        try {
            $todo = TodoList::create($data);

            $log = new TodoLog();
            $log->todo_id = $todo->id;
            $log->state = TodoStates::TODO->value;
            $log->save();

            $msg = __('success_register_operation');
            $data = new $this->resource($todo);
            $res = Response::HTTP_CREATED;

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return response()->json([
            'message' => $msg,
            'data' => $data
        ], $res);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $todo = $this->repository->getById($id);

        return response()->json([
            'message' => $todo ? __('field_data') : __('not_found'),
            'data' => $todo ? new $this->resource($todo) : null
        ], $todo ? Response::HTTP_OK : Response::HTTP_NOT_FOUND);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TodoListRequest $request, string $id)
    {
        $data = $request->all();
        $msg = __('fail_edit_operation');
        $res = Response::HTTP_NOT_IMPLEMENTED;

        $todo = $this->repository->getById($id);

        if ($todo && $todo->update($data)) {
            $msg = __('success_edit_operation');
            $data = new $this->resource($todo);
            $res = Response::HTTP_OK;
        }

        return response()->json([
            'message' => $msg,
            'data' => $data
        ], $res);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $todo = $this->repository->getById($id);

        $msg = __('bad_request');
        $res = Response::HTTP_NOT_FOUND;

        if ($todo && $todo->delete()) {
            $msg = __('success_delete_operation');
            $res = Response::HTTP_OK;
        }

        return response()->json([
            'message' => $msg,
            'data' => null
        ], $res);
    }

    /**
     * Change state of the specified todo.
     */
    public function changeState(string $id, string $state)
    {
        $todo = $this->repository->getById($id);

        $msg = __('fail_change_state_operation');
        $res = Response::HTTP_NOT_FOUND;

        if ($todo &&
            ($todo->lastLog->state != $state) &&
            isset(TodoStates::getValues()[$state])
        ) {
            $log = new TodoLog();
            $log->todo_id = $todo->id;
            $log->state = $state;
            if ($log->save()) {
                $todo = $this->repository->getById($id);
                $msg = __('success_change_state_operation');
                $res = Response::HTTP_OK;
            }
        }

        return response()->json([
            'message' => $msg,
            'data' => new $this->resource($todo)
        ], $res);
    }
}
