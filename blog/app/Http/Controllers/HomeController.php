<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct(){
        
    }

    public function index(){
        //posts->comment
        $posts= Post::with('comment')->get();
        return view ('index',[
            'posts' => $posts,
        ]);
    }

    public function commentOnPost(Request $request){
        $request->validate([
            'comment' =>['required'],
        ]);
        try{
            Comment::create([
                'comment' => $request->comment,
                'post_id' => $request->post,
            ]);

            $result = array(
                'status' => true,
                'message' => 'Comment Added'
            );
            return json_encode($result);
        }catch(\Exception $e){
            $result = array(
                'status' => false,
                'message' => 'Comment Failed'
            );
            return json_encode($result);
        }
    }

    public function apicommentOnPost(Request $request){
        $request->validate([
            'comment' =>['required'],
        ]);
        try{
            Comment::create([
                'comment' => $request->comment,
                'post_id' => $request->post,
            ]);
            
        }catch(\Exception $e){
        }
    }
}
