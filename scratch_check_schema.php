<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$questions = DB::select("SELECT id, question_type, passage_id, question_mark FROM questions WHERE passage_id IS NOT NULL AND question_type != 'mcq' LIMIT 20");
print_r($questions);
