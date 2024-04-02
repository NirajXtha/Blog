<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Group;
use App\Models\Post;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PostController extends Controller
{
    public function __construct(Post $post)
    {
        $this->model = $post;
    }

    public function view()
    {
        return view('posts.list', [
            'posts' => Post::all(),
        ]);
    }

    public function serversideview(Request $request)
    {
        if ($request->ajax()) {
            $data = $this->model->all();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('title', function ($row) {
                    return $row->title;
                })
                ->addColumn('description', function ($row) {
                    return $row->description;
                })
                ->addColumn('action', function ($row) {
                    $actionBtn = '';
                    $actionBtn .= '<a href="" class="btn btn-primary mb-4">Edit</a>';
                    $actionBtn .= '<span class="btn btn-danger mb-4 delete-button" data-id="">Delete</span>';
                    $actionBtn .= '<a href="" class="btn btn-danger mb-">Add Comment</a>';
                    return $actionBtn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('posts.serversidelist');
    }

    public function create()
    {
        return view('posts.create', [
            'groups' => Group::all(),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => ['required', 'min:5'],
            'description' => ['required', 'min:10'],
            'groups' => ['required'],
        ]);
        try {
            $postid = $this->model->create([
                'title' => $request->title,
                'description' => $request->description,
            ]);
            $postid->groups()->sync($request->groups);

            return redirect()->route('post.view')->with('success', 'Post added successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'There is an issue making post. Please contact admin']);
        }
    }

    public function edit($postid)
    {
        $post = Post::find($postid)->with('groups');
        if (!$post) {
            return redirect()->route('post.view')->with('error', 'Post not found');
        }
        return view('posts.edit', [
            'post' => $post,
            'groups' => Group::all(),
        ]);

    }

    public function update(Request $request, $postid)
    {
        $post = Post::find($postid);
        if (!$post) {
            return redirect()->route('post.view')->with('error', 'Post not found');
        }
        $request->validate([
            'title' => 'required',
            'description' => 'required',
        ]);

        $post->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'groups' => ['required'],
        ]);

        $post->groups()->attach($request->groups);

        return redirect()->route('post.view')->with('success', 'Post edited successfully');


    }

    public function destroy($postid)
    {
        $deleted = Post::find($postid)->delete();
        if ($deleted) {
            $msg = array(
                'status' => true,
                'message' => 'Post deleted'
            );
        } else {
            $msg = array(
                'status' => false,
                'message' => 'Post cannot be deleted. Please contact admin'
            );
        }

        return json_encode($msg);
    }


    public function viewpost($postid)
    {
        // print_r($this->model->with('comment')->get());die();
        // $post = Post::find($postid);
        $post = $this->model->where('id', $postid)->with('comment')->get();
        if (!$post) {
            return redirect()->route('post.view')->with('error', 'Post not found');
        }
        return view('posts.comment', [
            'post' => $post[0],
        ]);

    }

    public function commentStore(Request $request)
    {
        $request->validate([
            'comment' => ['required'],
        ]);
        try {
            Comment::create([
                'comment' => $request->comment,
                'post_id' => $request->post_id,
            ]);
            return redirect()->route('post.viewpost')->with('success', 'Commented successfully');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->withErrors(['error' => 'There is an issue making post. Please contact admin']);
        }
    }

    public function apiStore(Request $request)
    {
        if(is_null($request->title)){
            return response()->json(['message' => 'Title is required']);
        }
        try {
            $this->model->create([
                'title' => $request->title,
                'description' => $request->description,
            ]);

            return response()->json(['status' => 200, 'message' => 'Post created successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 422, 'message' => 'There is an issue making post. Please contact admin']);
        }
    }

    public function apiEdit(Request $request,$id)
    {
        $post = Post::find($id);
        if(is_null($request->title)){
            return response()->json(['message' => 'Title is required']);
        }
        if (!$post) {
            return response()->json(['message' => 'Post not found']);
        }
        try{
            $post->update([
                'title' => $request->input('title'),
                'description' => $request->input('description'),
            ]);
            return response()->json(['status' => 200, 'message' => 'Post updated successfully!']);
        } catch (\Exception $e) {
            return response()->json(['status' => 422, 'message' => 'There is an issue updating post. Please contact admin']);
        }

    }

}
