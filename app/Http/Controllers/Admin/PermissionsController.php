<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionsController extends Controller
{
    public function index(Request $request){
        $data = Permission::select(['*'])
            ->when($request->sort, function($query, $sort) use($request){
                $query->orderBy($sort, $request->order);
            }, function($query){
                // default sort
                $query->orderBy('id', 'ASC');
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

    public function store(Request $request){
        $rule = [
            'name' => 'required|unique:permissions,name',
        ];

        $this->validate($request, $rule);

        $action = new Permission();
        $action->name = $request->name;
        $action->guard_name = 'api';
        $action->save();

        return response()->json(['message' => 'Saved.']);
    }

    public function show($id){
        $data = Permission::findOrFail($id);

        return response()->json($data);
    }

    public function edit($id){
        //
    }

    public function update(Request $request, $id){
        $rule = [
            'name' => 'required|unique:permissions,name,'.$id,
        ];

        $this->validate($request, $rule);
        
        $action = Permission::findOrFail($id);
        $action->name = $request->name;
        $action->guard_name = 'api';
        $action->save();

        return response()->json(['message' => 'Saved.']);
    }

    public function destroy($id){
        $action = Permission::findOrFail($id);
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
