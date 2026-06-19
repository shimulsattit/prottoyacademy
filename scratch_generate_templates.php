<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

// 1. Generate current-affairs-mcq-demo.xlsx
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$headers = ['Question', 'Option One', 'Option Two', 'Option Three', 'Option Four', 'Option Five', 'Correct Answer', 'Job Category'];
$sheet->fromArray($headers, NULL, 'A1');

$sampleRows = [
    ['বাংলাদেশের প্রথম রাষ্ট্রপতির নাম কি?', 'বঙ্গবন্ধু শেখ মুজিবুর রহমান', 'তাজউদ্দীন আহমদ', 'সৈয়দ নজরুল ইসলাম', 'মোহাম্মদ উল্লাহ', '', '1', 'বাংলাদেশ বিষয়াবলী'],
    ['মুজিবনগর সরকার কবে গঠিত হয়?', '১০ এপ্রিল ১৯৭১', '১৭ এপ্রিল ১৯৭১', '৭ মার্চ ১৯৭১', '২৬ মার্চ ১৯৭১', '', '1', 'বাংলাদেশ বিষয়াবলী']
];
$sheet->fromArray($sampleRows, NULL, 'A2');

$writer = new Xlsx($spreadsheet);
$writer->save(public_path('current-affairs-mcq-demo.xlsx'));
echo "Created current-affairs-mcq-demo.xlsx\n";

// 2. Generate current-affairs-short-demo.xlsx
$spreadsheet2 = new Spreadsheet();
$sheet2 = $spreadsheet2->getActiveSheet();
$headers2 = ['Sub Sub Category', 'Question', 'Answer'];
$sheet2->fromArray($headers2, NULL, 'A1');

$sampleRows2 = [
    ['সাম্প্রতিক বাংলাদেশ', 'বাংলাদেশের বর্তমান রাষ্ট্রপতির নাম কি?', 'মোহাম্মদ সাহাবুদ্দিন'],
    ['সাম্প্রতিক আন্তর্জাতিক', 'বিশ্ব জলবায়ু সম্মেলন ২০২৬ কোথায় অনুষ্ঠিত হবে?', 'বাকু, আজারবাইজান']
];
$sheet2->fromArray($sampleRows2, NULL, 'A2');

$writer2 = new Xlsx($spreadsheet2);
$writer2->save(public_path('current-affairs-short-demo.xlsx'));
echo "Created current-affairs-short-demo.xlsx\n";
