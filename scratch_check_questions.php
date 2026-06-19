<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$q = App\Models\Question::with('options')->find(22620);
if ($q) {
    echo "=== ID: " . $q->id . " ===\n";
    echo "Question (Raw): " . $q->question . "\n";
    echo "Question (Decoded): " . html_entity_decode($q->question) . "\n";
    if ($q->options) {
        echo "Options:\n";
        echo "  One: " . $q->options->option_one . "\n";
        echo "  Two: " . $q->options->option_two . "\n";
        echo "  Three: " . $q->options->option_three . "\n";
        echo "  Four: " . $q->options->option_four . "\n";
    }
}
