<?php

// Script to properly swap admin3 and admin4 logic and UI text
$files_to_swap = [
    'resources/views/layouts/app.blade.php',
    'resources/views/components/app-layout.blade.php',
    'routes/web.php',
    'app/Http/Controllers/DashboardController.php',
    'resources/views/pembelian/order/index.blade.php',
    'resources/views/pembelian/order/show.blade.php',
    'resources/views/pengaturan/pengguna/index.blade.php',
    'resources/views/pengaturan/pengguna/create.blade.php',
    'resources/views/pengaturan/pengguna/edit.blade.php'
];

$basePath = "C:/xampp8.2/htdocs/dodpos/";

foreach ($files_to_swap as $file) {
    $fullPath = $basePath . $file;
    if (file_exists($fullPath)) {
        $content = file_get_contents($fullPath);
        
        // --- 1. Swap Display TEXT mapping to roles first! ---
        // This makes sure we don't accidentally mess up the words by swapping admin3/4 next.
        // As defined in the original files:
        // admin3 is 'Admin 3 (Gudang Keluar)' or 'Admin 3 — Gudang Keluar & Loading Armada'
        // admin4 is 'Admin 4 (Gudang Masuk)' or 'Admin 4 — Gudang Masuk, Opname & Master Data'
        //
        // AFTER SWAP, we want:
        // admin3 is now Masuk (since it gets admin4's old logic)
        // admin4 is now Keluar
        
        // Temporary placeholder replacement
        $content = str_replace('Admin 3 (Gudang Keluar)', '__TEMP_LBL1__', $content);
        $content = str_replace('Admin 4 (Gudang Masuk)', '__TEMP_LBL2__', $content);
        $content = str_replace('Admin 3 — Gudang Keluar & Loading Armada', '__TEMP_LBL3__', $content);
        $content = str_replace('Admin 4 — Gudang Masuk, Opname & Master Data', '__TEMP_LBL4__', $content);
        
        // Put the swapped values back in
        // Role admin3 (which WAS Keluar) is now Masuk. Its label should be "Admin 3 (Gudang Masuk)"
        $content = str_replace('__TEMP_LBL1__', 'Admin 3 (Gudang Masuk)', $content);
        // Role admin4 (which WAS Masuk) is now Keluar. Its label should be "Admin 4 (Gudang Keluar)"
        $content = str_replace('__TEMP_LBL2__', 'Admin 4 (Gudang Keluar)', $content);
        // And the long variants
        $content = str_replace('__TEMP_LBL3__', 'Admin 3 — Gudang Masuk, Opname & Master Data', $content);
        $content = str_replace('__TEMP_LBL4__', 'Admin 4 — Gudang Keluar & Loading Armada', $content);

        // --- 2. Swap Hardcoded Single Quoted Roles ---
        $content = str_replace("'admin3'", "'__TEMP_ROLE__'", $content);
        $content = str_replace("'admin4'", "'admin3'", $content);
        $content = str_replace("'__TEMP_ROLE__'", "'admin4'", $content);

        // --- 3. Swap Hardcoded Double Quoted Roles (e.g. value="admin3") ---
        $content = str_replace('"admin3"', '"__TEMP_ROLE2__"', $content);
        $content = str_replace('"admin4"', '"admin3"', $content);
        $content = str_replace('"__TEMP_ROLE2__"', '"admin4"', $content);

        file_put_contents($fullPath, $content);
        echo "Swapped logic in $file\n";
    } else {
        echo "File NOT FOUND: $file\n";
    }
}

// Now swap the physical dashboard views by renaming them
$dash3 = $basePath . 'resources/views/dashboard/admin3.blade.php';
$dash4 = $basePath . 'resources/views/dashboard/admin4.blade.php';
$dashTemp = $basePath . 'resources/views/dashboard/_admin3_temp.blade.php';

if (file_exists($dash3) && file_exists($dash4)) {
    rename($dash3, $dashTemp);
    rename($dash4, $dash3);
    rename($dashTemp, $dash4);
    echo "Swapped dashboard views physically.\n";
    
    // In dashboard views, fix up their internal role assignments if they reference their own filename
    $dash3Content = file_get_contents($dash3);
    $dash3Content = str_replace("admin4", "admin3", $dash3Content); // it used to be admin4 but now named admin3
    file_put_contents($dash3, $dash3Content);

    $dash4Content = file_get_contents($dash4);
    $dash4Content = str_replace("admin3", "admin4", $dash4Content); // it used to be admin3 but now named admin4
    file_put_contents($dash4, $dash4Content);
    echo "Adjusted text inside the swapped dashboard files.\n";
}

echo "Done.\n";
