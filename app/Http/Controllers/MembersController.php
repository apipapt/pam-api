<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;

class MembersController extends Controller
{
    public function index(Request $request){
        $data = Member::select(['*'])
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
            'address' => 'required',
        ];

        $this->validate($request, $rule);

        $action = new Member();
        $action->name = $request->name;
        $action->address = $request->address;
        $action->save();

        return response()->json(['message' => 'Saved.']);
    }

    public function show($id){
        $data = Member::findOrFail($id);

        return response()->json($data);
    }

    public function edit($id){
        //
    }

    public function update(Request $request, $id){
        $rule = [
            'name' => 'required|unique:permissions,name,'.$id,
            'address' => 'required',
        ];

        $this->validate($request, $rule);
        
        $action = Member::findOrFail($id);
        $action->name = $request->name;
        $action->address = $request->address;
        $action->save();

        return response()->json(['message' => 'Saved.']);
    }

    public function destroy($id){
        $action = Member::findOrFail($id);
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
