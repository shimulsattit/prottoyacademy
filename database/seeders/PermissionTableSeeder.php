<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Carbon\Carbon;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [

			// System Optimizer
			['name' => 'system-optimizer'],

			// Category
			['name' => 'category.view'],
			['name' => 'category.create'],
			['name' => 'category.update'],
			['name' => 'category.delete'],

			// Job Category
			['name' => 'job_category.view'],
			['name' => 'job_category.create'],
			['name' => 'job_category.update'],
			['name' => 'job_category.delete'],

			// Year
			['name' => 'year.view'],
			['name' => 'year.create'],
			['name' => 'year.update'],
			['name' => 'year.delete'],

			// Exam
			['name' => 'exam.view'],
			['name' => 'exam.create'],
			['name' => 'exam.update'],
			['name' => 'exam.delete'],

			// Question
			['name' => 'question.view'],
			['name' => 'question.create'],
			['name' => 'question.update'],
			['name' => 'question.delete'],
			['name' => 'question.import'],

			// Passage
			['name' => 'passage.view'],
			['name' => 'passage.create'],
			['name' => 'passage.update'],
			['name' => 'passage.delete'],

			['name' => 'job_solution.update'],
			['name' => 'admission.update'],

			// Stuff
			['name' => 'stuff.view'],
			['name' => 'stuff.create'],
			['name' => 'stuff.update'],
			['name' => 'stuff.delete'],

			// Roles & Permission
			['name' => 'roles.view'],
			['name' => 'roles.create'],
			['name' => 'roles.update'],
			['name' => 'roles.delete'],

			// Settings
			['name' => 'settings.view'],
			['name' => 'settings.update'],

			// Recycle Bin
			['name' => 'recycle_bin.view'],
		];

		$insert_data = [];
		$time_stamp = Carbon::now();
		foreach ($data as $d) {
			$d['guard_name'] = 'admin';
			$d['created_at'] = $time_stamp;
			$insert_data[] = $d;
		}

		Permission::insert($insert_data);

    }
}
