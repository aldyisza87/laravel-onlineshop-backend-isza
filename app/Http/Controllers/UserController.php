<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Index
    public function index(Request $request){
        // get users with pagination
        $users = DB::table('users')
        -> when($request->input('name'), function ($query, $name){
            return $query->where('name', 'like','%' . $name . '%');
        })-> paginate(5);
        return view('pages.user.index', compact('users'));
    }

    //create
    public function create(){
        return view('pages.user.create');
    }

    //store
    public function store(Request $request){

        $data = $request->all();
        $data['password'] = Hash::make($request->input('password'));
        User::create($data);
        return redirect()->route('user.index');
    }

    //show
    public function show($id){
        return view('pages.dashboard');
    }

    //edit
    public function edit($id){
        return view('pages.dashboard');
    }

    //update
    public function update(Request $request, $id){
        return view('pages.dashboard');
    }

    //destroy
    public function destroy($id){
        return view('pages.dashboard');
    }
}
