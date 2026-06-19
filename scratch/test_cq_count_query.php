<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$qQuery = \App\Models\Question::where('question_type', 'cq')->where('status', 1);

$simpleCount = (clone $qQuery)->count();
$distinctCount = (clone $qQuery)->distinct()->count('passage_id');
$pluckCount = (clone $qQuery)->pluck('passage_id')->unique()->filter()->count();

echo "Simple count of CQ: $simpleCount\n";
echo "Distinct count of CQ: $distinctCount\n";
echo "Pluck unique count of CQ: $pluckCount\n";
