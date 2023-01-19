<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    public function index(Request $request){
        $data = User::select(['*'])
            ->with('roles:id,name')
            ->when($request->sort, function($query, $sort) use($request){
                $query->orderBy($sort, $request->order);
            }, function($query){
                // default sort
                $query->orderBy('created_at', 'DESC');
            })
            ->when($request->global_search, function($query, $value) {
                $query->where('name', 'like', '%'.$value.'%')
                    ->orWhere('email', 'like', '%'.$value.'%');
            })
            ->when($request->name, function($query, $value) use($request) {
                $query->where('name', 'like', '%'.$value.'%');
            })
            ->when($request->role_id, function($query, $value) use($request) {
                $query->role($value);
            })
            ->paginate($request->per_page);
        
        return response()->json($data);
    }

    public function getRoles(){
        $data = Role::select(['id', 'name'])->orderBy('name', 'ASC')->get();

        return response()->json($data);
    }

    public function store(Request $request){
        $rule = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'role_id' => 'required',
            'password' => 'required|min:6|confirmed',
            'password_confirmation' => 'required',
        ];

        $this->validate($request, $rule);

        $action = new User();
        $action->name = $request->name;
        $action->email = $request->email;
        $action->password = bcrypt($request->password);
        $action->save();

        $action->syncRoles($request->role_id);

        return response()->json(['message' => 'Saved.']);
    }

    public function show($id){
        $data = User::with('roles:id,name')->findOrFail($id);
        $data->role_id = $data->roles()->pluck('id');

        return response()->json($data);
    }

    public function edit($id){
        //
    }

    public function update(Request $request, $id){
        $rule = [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'role_id' => 'required',
            'password' => ($request->password)?'required|min:6|confirmed':'',
            'password_confirmation' => ($request->password)?'required':'',
        ];

        $this->validate($request, $rule);
        
        $action = User::findOrFail($id);
        $action->name = $request->name;
        $action->email = $request->email;
        ($request->password) ? $action->password = bcrypt($request->password) : null;
        $action->save();

        $action->syncRoles($request->role_id);

        return response()->json(['message' => 'Saved.']);
    }

    public function destroy($id){
        $action = User::findOrFail($id);
        $action->delete();

        return response()->json(['message' => 'Deleted.']);
    }

    public function multiDestroy(Request $request){
        foreach ($request->id as $row) {
            $this->destroy($row);
        }

        return response()->json(['message' => 'Deleted.']);
    }
}
