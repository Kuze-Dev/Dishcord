<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Domain\Comments\Actions\CreateComment;
use Domain\Comments\Actions\UpdateComment;
use Spatie\RouteAttributes\Attributes\Post;
use Spatie\RouteAttributes\Attributes\Prefix;
use Spatie\RouteAttributes\Attributes\Middleware;
use Domain\Comments\DataTransferObjects\CommentDTO;
use Domain\Comments\DataTransferObjects\UpdateCommentDTO;
use Spatie\RouteAttributes\Attributes\Put;

#[Prefix('api')]
#[Middleware('auth:sanctum')]
class CommentController extends Controller
{
    public function index()
    {

    }
    #[Post('comments')]
    public function store(Request $request, CreateComment $action)
    {
      // ✅ Validate input
      $validator = Validator::make($request->all(), [
        'user_id' => 'required|exists:users,id',
        'user_post_id' => 'required|exists:user_posts,id',
        'parent_id' => 'nullable|exists:comments,id',
        'body' => 'required|string|max:1000',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors()
        ], 422);
    }

    // ✅ Create DTO
    $dto = CommentDTO::fromArray($validator->validated());

    // ✅ Pass DTO to action
    $createComment = $action->handle($dto);

    return response()->json([
        'message' => 'Comment created successfully',
        'data' => $createComment
    ], 201);
    }
    public function show($id)
    {

        //
    }
    #[Put('comments/{id}')]
    public function update(Request $request, $id, UpdateComment $action)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'user_post_id' => 'required|exists:user_posts,id',
            'parent_id' => 'nullable|exists:comments,id',
            'body' => 'required|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // ✅ Inject the route param 'id' into validated data
        $validated = $validator->validated();
        $validated['id'] = (int) $id;

        // ✅ Create DTO
        $dto = UpdateCommentDTO::fromArray($validated);

        // ✅ Pass DTO to action
        $updateComment = $action->handle($dto);

        return response()->json([
            'message' => 'Comment updated successfully',
            'data' => $updateComment
        ], 200);
    }


    public function destroy($id)
    {
        //
    }
}