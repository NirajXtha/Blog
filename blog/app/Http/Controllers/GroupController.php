<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function __construct(Group $post){
        $this->model = $post;
    }

    public function view(){
        return view ('groups.list',[
            'groups' => Group::all(),
        ]);
    }


    public function create(){
        return view ('groups.create');
    }
    public function store(Request $request){
        $request->validate([
            'title' =>['required','min:5'],
        ]);
        try{
            $this->model->create([
                'title' => $request->title,
            ]);
            return redirect()->route('group.view')->with('success', 'Group added successfully');
        }catch(\Exception $e){
            return redirect()->back()->withInput()->withErrors(['error' => 'There is an issue making group. Please contact admin']);
        }
    }
}
