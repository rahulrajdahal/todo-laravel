<?php

namespace App\Http\Controllers;

use App\Models\Todo;
use Illuminate\Http\Request;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\ResponseTrait;

/**
 * @OA\Schema(
 *  schema="Todo",
 *  title="Create a new item",
 * 	@OA\Property(
 * 		property="title",
 * 		type="string"
 * 	),
 * 	@OA\Property(
 * 		property="completed",
 * 		type="boolean",
 * 		default=false,
 * 	)
 * )
 */
class TodosController extends Controller
{

    use ValidatesRequests;
    use ResponseTrait;

    /**
     * @OA\Get(
     *     path="/api/v1/todos",
     *     summary="Get a list of todos",
     *     tags={"Todos"},
     *     @OA\Response(
     *         response=200,
     *         description="List of todos"
     *     )
     * )
     */
    public function index()
    {
        try {
            $todos = Todo::all();

            return response()->json(['data' => $todos, 'message' => 'Todos fetched!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e, 'message' => 'Internal Server Error'], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/v1/todos/{id}",
     *     summary="Get todo by id",
     *     tags={"Todos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of the todo to be updated.",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Returns the specified todo"
     *     )
     * )
     */
    public function get($id)
    {
        try {
            $todo = Todo::find($id);

            return response()->json(['data' => $todo, 'message' => 'Todo fetched!'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e, 'message' => 'Internal Server Error'], 500);
        }
    }


    /**
     * @OA\Post(
     *     path="/api/v1/todos",
     *     summary="AddTodo",
     *     tags={"Todos"},
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/Todo"),
     *         @OA\MediaType(mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(ref="#/components/schemas/Todo"))
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of todos"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

        try {
            $todo = Todo::create([
                'title' => $request->title,
                'completed' => $request->completed ?? false
            ]);
            return response()->json(['data' => $todo, 'message' => 'Todo created!'], 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error', 'error' => $e], 500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/todos/{id}",
     *     summary="UpdateTodo",
     *     tags={"Todos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of the todo to be updated.",
     *         required=true,
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/Todo"),
     *         @OA\MediaType(mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(ref="#/components/schemas/Todo"))
     *         ),
     *     @OA\Response(
     *         response=204,
     *         description="Update a todo with the specified id"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Todo not found for specified id"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function updatePUT(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'completed' => 'required'
        ]);

        try {
            $todo = Todo::find($id);

            if (!$todo) {
                return response()->json(['message' => 'Todo not found!'], 404);
            }

            $todo->title = $request->title;
            $todo->completed = $request->completed;

            $todo->update();
            return response()->json(['message' => 'Todo updated!'], 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error', 'error' => $e], 500);
        }
    }

    /**
     * @OA\Patch(
     *     path="/api/v1/todos/{id}",
     *     summary="UpdateTodo",
     *     tags={"Todos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of the todo to be updated.",
     *         required=true,
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(ref="#/components/schemas/Todo"),
     *         @OA\MediaType(mediaType="application/x-www-form-urlencoded",
     *             @OA\Schema(ref="#/components/schemas/Todo"))
     *         ),
     *     @OA\Response(
     *         response=204,
     *         description="Update a todo with the specified id"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Todo not found for specified id"
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */

    public function update(Request $request, $id)
    {
        try {
            $todo = Todo::find($id);

            if (!$todo) {
                return response()->json(['message' => 'Todo not found!'], 404);
            }
            if ($request->title) {
                $todo->title = $request->title;
            }
            if ($request->completed) {
                $todo->completed = $request->completed;
            }

            $todo->update();
            return response()->json(['message' => 'Todo updated!'], 204);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error', 'error' => $e], 500);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/v1/todos/{id}",
     *     summary="Delete Todo item",
     *     tags={"Todos"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Id of the todo to be deleted.",
     *         required=true,
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Todo with the specified id is deleted."
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error"
     *     )
     * )
     */
    public function destroy(Request $request, $id)
    {
        try {
            Todo::destroy($id);

            return response()->noContent();
        } catch (\Exception $e) {
            return response()->json(['message' => 'Internal Server Error', 'error' => $e], 500);
        }
    }
}
