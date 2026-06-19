<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->handle(Illuminate\Http\Request::capture());

$q = App\Models\Question::find(22610);
if ($q) {
    echo "Before: " . $q->question . "\n";
    $q->question = '<math xmlns="http://www.w3.org/1998/Math/MathML"><msub><mi>log</mi><mn>2</mn></msub><mo>&#xA0;</mo><mn>16</math> এর মান কত?';
    $q->save();
    echo "After: " . $q->question . "\n";
} else {
    echo "Question not found\n";
}
