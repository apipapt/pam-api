<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class WaterDataSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		// Reset cached roles and permissions
		// app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

		// create perissions
		$arrayOfPermissionNames = [
			// water data permission
			'water-data-list',
			'water-data-add',
			'water-data-edit',
			'water-data-delete',
		];

		$permissions = collect($arrayOfPermissionNames)->map(function ($permission) {
				return ['name' => $permission, 'guard_name' => 'api'];
		});

		Permission::insert($permissions->toArray());

		// # admin role
		$role = Role::create(['name' => 'ADMINISTRATOR', 'guard_name' => 'api'])
			->syncPermissions([
			// water data permission
			'water-data-list',
			'water-data-add',
			'water-data-edit',
			'water-data-delete'
			]);
	}
}
