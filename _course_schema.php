<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
foreach (Illuminate\Support\Facades\Schema::getTableListing() as $t) {
    if (preg_match('/course|lesson|curric|video|media|chapter|section/i', $t)) {
        echo "== $t ==\n";
        foreach (Illuminate\Support\Facades\Schema::getColumnListing($t) as $c) echo "  $c\n";
    }
}