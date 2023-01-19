<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesController extends Controller
{
    public function index(Request $request){
        $data = Role::select(['*'])
            ->when($request->sort, function($query, $sort) use($request){
                $query->orderBy($sort, $request->order);
            }, function($query){
                // default sort
                $query->orderBy('created_at', 'DESC');
            })
            ->when($request->global_search, function($query, $value) {
                $query->where('name', 'like', '%'.$value.'%');
            })
            ->when($request->name, function($query, $value) use($request) {
                $query->where('name', 'like', '%'.$value.'%');
            })
            ->paginate($request->per_page);
        
        return response()->json($data);
    }

    public function getPermissions(){
        $data = Permission::select(['id', 'name'])->orderBy('id', 'ASC')->get();

        return response()->json($data);
    }

    public function store(Request $request){
        $rule = [
            'name' => 'required|unique:roles,name',
            'permission_id' => 'required',
        ];

        $this->validate($request, $rule);

        $action = new Role();
        $action->name = $request->name;
        $action->guard_name = 'api';
        $action->save();

        $action->syncPermissions($request->permission_id);

        return response()->json(['message' => 'Saved.']);
    }

    public function show($id){
        $data = Role::with('permissions:id,name')->findOrFail($id);
        $data->permission_id = $data->permissions()->pluck('id');

        return response()->json($data);
    }

    public function edit($id){
        //
    }

    public function update(Request $request, $id){
        $rule = [
            'name' => 'required|unique:roles,name,'.$id,
            'permission_id' => 'required',
        ];

        $this->validate($request, $rule);
        
        $action = Role::findOrFail($id);
        $action->name = $request->name;
        $action->guard_name = 'api';
        $action->save();

        $action->syncPermissions([$request->permission_id]);

        return response()->json(['message' => 'Saved.']);
    }

    public function destroy($id){
        $action = Role::findOrFail($id);
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
