<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

$request = new \Illuminate\Http\Request();
$request->merge([
    'class_ids' => [786],
    'subject_ids' => [791],
    'chapter_ids' => [791], // Wait, is 791 a chapter category? No, chapter is 791, let's check category ID of chapter
    'types' => ['cq']
]);

// Let's find the chapter category ID for "অধ্যায়-১ম (অর্থনীতি পরিচয়)"
$chapter = \App\Models\Category::where('name', 'like', '%অধ্যায়-১ম%')->first();
echo "Chapter category: " . ($chapter ? "ID: {$chapter->id}, Name: {$chapter->name}" : "Not found") . "\n";

if ($chapter) {
    $request->merge([
        'chapter_ids' => [$chapter->id]
    ]);
}

$controller = new \App\Http\Controllers\Web\WebsiteController();
$response = $controller->academyFilter($request);
$data = json_decode($response->getContent(), true);

echo "=== Stats returned by academyFilter ===\n";
print_r($data['stats'] ?? []);
echo "Questions count in response: " . count($data['questions'] ?? []) . "\n";
foreach ($data['questions'] ?? [] as $q) {
    echo "Question ID: {$q['id']}, Type: {$q['question_type']}, Question: " . strip_tags($q['question']) . "\n";
}
