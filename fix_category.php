<?php
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

DB::statement("ALTER TABLE customers MODIFY COLUMN category ENUM('pos','eceran','grosir','pasgar','minyak') NOT NULL DEFAULT 'pos'");
echo "Done! ENUM updated to include 'eceran' and 'grosir'.\n";
