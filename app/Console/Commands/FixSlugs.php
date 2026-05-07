<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FixSlugs extends Command
{
    protected $signature = 'app:fix-slugs';
    protected $description = 'Fix all existing slugs with spaces';

    public function handle()
    {
        $tables = [
            'blog_authors', 'blog_categories', 'blog_tags', 'blogs',
            'categories', 'exams', 'job_categories', 'pages', 'questions', 'years'
        ];

        foreach ($tables as $table) {
            $this->info("Fixing slugs in table: $table");
            $rows = DB::table($table)->where('slug', 'LIKE', '% %')->get();
            foreach ($rows as $row) {
                $newSlug = preg_replace('/\s+/', '-', trim($row->slug));
                $newSlug = preg_replace('/-+/', '-', $newSlug);
                
                // Ensure unique
                $originalSlug = $newSlug;
                $counter = 1;
                while (DB::table($table)->where('slug', $newSlug)->where('id', '!=', $row->id)->exists()) {
                    $newSlug = $originalSlug . '-' . time() . '-' . $counter;
                    $counter++;
                }

                DB::table($table)->where('id', $row->id)->update(['slug' => $newSlug]);
            }
        }
        $this->info('All slugs fixed.');
    }
}
