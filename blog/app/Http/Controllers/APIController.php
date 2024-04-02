<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class APIController extends Controller
{

    /**
     * @OA\Get(
     *      path="/api/post",
     *      operationId="getPostDetails",
     *      tags={"Post"},
     *      summary="Get post details",
     *      description="Returns detail of post",
     *      security={{"bearer_token":{}}},
     *      @OA\Response(response=200, description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="resCod", type="string", example="200"),
     *                  @OA\Property(property="resDesc", type="string", example="Post details"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=400,
     *          description="Error in input"
     *         ),
     *       @OA\Response(
     *          response=403,
     *          description="Forbidden"
     *       ),
     *       @OA\Response(
     *          response=401,
     *          description="Unauthorized"
     *       ),
     * )
     */
    public function index()
    {
        $posts = Post::all();
        return response()->json(['status' => '200','message' => $posts]);
    }

    public function store(Request $request)
    {
        if(is_null($request->title))
        {
            return response()->json(['message' => 'Title is required']);
        }
        try{
            Post::create([
                'title' => $request->title,
                'description' => $request->description
            ]);
            return response()->json(['status' => 200,'message' => 'Post created successfully! ']);
        }catch(\Exception $e)
        {
            return response()->json(['status' => 200,'message' => 'Something went wrong']);
        }

    }

    public function update(Request $request,$id)
    {
        if(is_null($request->title))
        {
            return response()->json(['message' => 'Title is required']);
        }
        try{
            $post = Post::find($id);
            if(is_null($post)){
                return response()->json(['message' => 'Post not found']);
            }
            $post->update([
                'title' => $request->title,
                'description' => $request->description
            ]);
            return response()->json(['status' => 200,'message' => 'Post updated successfully! ']);
        }catch(\Exception $e){
            return response()->json(['status' => 422,'message' => 'Something went wrong ']);
        }

    }

    public function destroy($id)
    {
        try{
            $post = Post::find($id);
            if(is_null($post)){
                return response()->json(['message' => 'Post not found']);
            }
            $post->delete();
            return response()->json(['status' => 200,'message' => 'Post deleted successfully! ']);
        }catch(\Exception $e){
            return response()->json(['status' => 422,'message' => 'Something went wrong ']);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/login",
     *      operationId="loginUser",
     *      tags={"Auth"},
     *      summary="Login User",
     *      description="Login User",
     *      security={{"bearer_token":{}}},
     *      @OA\RequestBody(
     *          @OA\MediaType(mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  @OA\Property(property="email", type="string"),
     *                  @OA\Property(property="password", type="string"),
     *             )
     *         )
     *      ),
     *      @OA\Response(response=200, description="successful operation",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *              @OA\Schema(
     *                  @OA\Property(property="resCod", type="string", example="200"),
     *                  @OA\Property(property="resDesc", type="string", example="User Auth Token"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Error in input"
     *         )
     * )
     */

    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        try{
            if(Auth::attempt(['email' => $email,'password' => $password])){
                $user = User::find(Auth::user()->id);
                $token = $user->createToken('API Token')->accessToken;
                return response()->json([
                    'success' => true,
                    'token' => $token,
                    'status' => 200
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Authentication failed',
                    'status' => 401
                ]);
            }
        }catch(\Exception $e){
            return response()->json(['status' => 422,'message' => 'Invalid credentials']);
        }
    }
}
