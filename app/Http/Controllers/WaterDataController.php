<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\WaterData;

class WaterDataController extends Controller
{
	public function index(Request $request) {
		$data = WaterData::select(['*'])
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

	public function store(Request $request) {
		$m = $request->volume_awal;
		$request->validate([
			'volume_akhir' => 'required|numeric|min:'.$m.'|not_in:0',
		]);
		$member = Member::find($request->member_id);
		$status = $member->status_warga;
		$m = $request->volume_akhir - $request->volume_awal;

		if($status == 1){
			$harga = $m * 1000;
			$totalHarga = $harga + 2000;
		}
		elseif ($m <= 20) {
			$harga = $m * 1000;
			$totalHarga = $harga + 2000;
		}
		elseif ($m <= 40) {
			$harga1 = 20 * 1000; // 20.000
			$h2 = $m - 20;
			$harga2 = $h2 * 1300;
			$totalHarga = $harga1 + $harga2 + 2000;
		}
		elseif ($m <= 60) {
			$harga1 = 20 * 1000;
			$harga2 = 20 * 1300;
			$s = $m - 40;
			$harga3 = $s * 1500;
			$totalHarga = $harga1 + $harga2 + $harga3 + 2000;
		} else {
			$harga1 = 20 * 1000;
			$harga2 = 20 * 1300;
			$harga3 = 20 * 1500;
			$s = $m - 60;
			$harga4 = $s * 2000;
			$totalHarga = $harga1 + $harga2 + $harga3 + $harga4 + 2000;
		}


		$waterData = new WaterData;
		$waterData->member_id = $request->member_id;
		$waterData->order_input = $request->order_input+1;
		$waterData->volume_awal  = $request->volume_awal;
		$waterData->volume_akhir = $request->volume_akhir;
		$waterData->price = $totalHarga;
		$waterData->date = $request->date;
		$waterData->status = false;
		$waterData->save();

		return response()->json(['message' => 'Saved.']);
	}

	public function show($id) {
		$data = WaterData::findOrFail($id);

		return response()->json($data);
	}


	public function update(Request $request, $id)
	{
		$m = $request->volume_awal;
		$request->validate([
				'volume_akhir' => 'required|numeric|min:'.$m.'|not_in:0',
		]);
		$member = Member::find($request->member_id);
		$status = $member->status_warga;
		$m = $request->volume_akhir - $request->volume_awal;

		if($status == 1){
				$harga = $m * 1000;
				$totalHarga = $harga + 2000;
		}
		elseif ($m <= 20) {
				$harga = $m * 1000;
				$totalHarga = $harga + 2000;
		}
		elseif ($m <= 40) {
				$harga1 = 20 * 1000; // 20.000
				$h2 = $m - 20;
				$harga2 = $h2 * 1300;
				$totalHarga = $harga1 + $harga2 + 2000;
		}
		elseif ($m <= 60) {
				$harga1 = 20 * 1000;
				$harga2 = 20 * 1300;
				$s = $m - 40;
				$harga3 = $s * 1500;
				$totalHarga = $harga1 + $harga2 + $harga3 + 2000;
		} else {
				$harga1 = 20 * 1000;
				$harga2 = 20 * 1300;
				$harga3 = 20 * 1500;
				$s = $m - 60;
				$harga4 = $s * 2000;
				$totalHarga = $harga1 + $harga2 + $harga3 + $harga4 + 2000;
		}

		$waterData = WaterData::find($id);
		$waterData->volume_akhir = $request->volume_akhir;
		$waterData->price = $totalHarga;
		$waterData->status = false;
		$waterData->save();

		return response()->json(['message' => 'Saved.']);
	}

	public function destroy($id)
	{
		$waterData = WaterData::find($id);
		$waterData->delete();

		return response()->json(['message' => 'Deleted.']);
	}

	public function multiDestroy(Request $request){
		foreach ($request->id as $row) {
				$this->destroy($row);
		}

		return response()->json(['message' => 'Deleted.']);
	}
}
