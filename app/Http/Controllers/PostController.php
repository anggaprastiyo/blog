<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index()
    {
        try {

            $data = DB::table('vw_posts')->orderBy('id', 'DESC')
                ->orderBy(request()->sortby, request()->sortbydesc)
                ->when(request()->q, function ($data) {
                    $data = $data->where('title', 'LIKE', '%' . request()->q . '%')
                        ->orWhere('author', 'LIKE', '%' . request()->q . '%');
                })->paginate(request()->per_page);
        } catch (\Exception $e) {

            Log::channel('errors')->debug($e->errorInfo[2]);
            return response()->json([
                'success' => false,
                'message' => 'Oops there is something wrong!',
                'errors'    => $e->errorInfo[2]
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data loaded!',
            'errors'    => null,
            'data' => $data
        ], 200);
    }

    public function show($id)
    {
        try {
            $data = Post::findOrFail($id);
        } catch (\Exception $e) {
            Log::channel('errors')->debug($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Oops there is something wrong!',
                'errors'    => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data Found!',
            'errors'    => null,
            'data' => $data
        ], 200);
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|min:8|max:200',
            'author' => 'required|min:8|max:200',
            'content' => 'required|min:8|max:2000',
            'category' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {

            Log::channel('errors')->debug(json_encode($validator->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Please fill the form correctly',
                'errors'    => $validator->errors()
            ], 400);
        } else {
            try {

                Post::create($request->all());
            } catch (\Exception $e) {

                Log::channel('errors')->debug($e->errorInfo[2]);
                return response()->json([
                    'success' => false,
                    'message' => 'Oops there is something wrong!',
                    'errors'    => $e->errorInfo[2]
                ], 500);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Post successfully added!',
            'errors'    => null,
            'data' => null
        ], 201);
    }

    public function update($id, Request $request)
    {

        $validator = Validator::make($request->all(), [
            'title' => 'required|min:8|max:200',
            'author' => 'required|min:8|max:200',
            'content' => 'required|min:8|max:2000',
            'category' => 'required',
            'status' => 'required',
        ]);

        if ($validator->fails()) {

            Log::channel('errors')->debug(json_encode($validator->errors()));
            return response()->json([
                'success' => false,
                'message' => 'Please fill the form correctly',
                'errors'    => $validator->errors()
            ], 400);
        } else {
            try {

                Post::findOrFail($id)->update($request->all());
            } catch (\Exception $e) {

                Log::channel('errors')->debug($e->getMessage());
                return response()->json([
                    'success' => false,
                    'message' => 'Oops there is something wrong!',
                    'errors'    => $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Post successfully updated!',
            'errors'    => null,
            'data' => null
        ], 200);
    }

    public function count()
    {
        try {

            $data['all'] = Post::count();
            $data['publish'] = Post::where('status','=',1)->count();
            $data['draft'] = Post::where('status','=',1)->count();
            $data['utama'] = Post::where('category','=',1)->count();

        } catch (\Exception $e) {

            Log::channel('errors')->debug($e->errorInfo[2]);
            return response()->json([
                'success' => false,
                'message' => 'Wah jangan diganti dong!',
                'errors'    => $e->errorInfo[2]
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data loaded!',
            'errors'    => null,
            'data' => $data
        ], 200);
    }

    public function destroy($id)
    {
        try {
            Post::findOrFail($id)->delete();
        } catch (\Exception $e) {
            Log::channel('errors')->debug($e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Oops there is something wrong!',
                'errors'    => $e->getMessage()
            ], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data deleted!',
            'errors'    => null,
            'data' => null
        ], 200);
    }
}
