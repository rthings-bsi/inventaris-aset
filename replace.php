<?php

$directories = [
    __DIR__ . '/database/migrations',
    __DIR__ . '/app',
    __DIR__ . '/resources/views',
    __DIR__ . '/routes',
];

$replacements = [
    'kode_aset' => 'asset_code',
    'nama_aset' => 'asset_name',
    'deskripsi' => 'description',
    'nilai_perolehan' => 'acquisition_cost',
    'tanggal_perolehan' => 'acquisition_date',
    'kondisi' => 'condition',
    // We must be careful not to replace the path 'storage/foto' incorrectly if needed, but 'foto' to 'photo' is correct.
    'foto' => 'photo',
    'peminjam' => 'borrower',
    'tanggal_pinjam' => 'loan_date',
    'tanggal_kembali' => 'return_date',
    'catatan' => 'notes',
    'nama_kategori' => 'category_name',
    'nama_lokasi' => 'location_name',
    'penanggung_jawab' => 'person_in_charge',
    'kategori' => 'category',
    'lokasi' => 'location',

    // Enums
    "'aktif'" => "'active'",
    "'maintenance'" => "'maintenance'",
    "'rusak'" => "'broken'",
    "'dihapuskan'" => "'disposed'",
    
    // Enums for loans
    "'dipinjam'" => "'borrowed'",
    "'dikembalikan'" => "'returned'",

    '"aktif"' => '"active"',
    '"maintenance"' => '"maintenance"',
    '"rusak"' => '"broken"',
    '"dihapuskan"' => '"disposed"',

    '"dipinjam"' => '"borrowed"',
    '"dikembalikan"' => '"returned"',

    // Capitalized versions
    'KodeAset' => 'AssetCode',
    'NamaAset' => 'AssetName',
    'Deskripsi' => 'Description',
    'NilaiPerolehan' => 'AcquisitionCost',
    'TanggalPerolehan' => 'AcquisitionDate',
    'Kondisi' => 'Condition',
    'Foto' => 'Photo',
    'Peminjam' => 'Borrower',
    'TanggalPinjam' => 'LoanDate',
    'TanggalKembali' => 'ReturnDate',
    'Catatan' => 'Notes',
    'NamaKategori' => 'CategoryName',
    'NamaLokasi' => 'LocationName',
    'PenanggungJawab' => 'PersonInCharge',
    'Kategori' => 'Category',
    'Lokasi' => 'Location',
];

// Custom method names 
$replacements['ByKategori'] = 'ByCategory';
$replacements['kategoriChart'] = 'categoryChart';
$replacements['kondisiChart'] = 'conditionChart';


foreach ($directories as $dir) {
    if (!is_dir($dir)) continue;

    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir));
    foreach ($iterator as $file) {
        if ($file->isFile() && in_array($file->getExtension(), ['php', 'js', 'css', 'html'])) {
            $path = $file->getRealPath();
            $content = file_get_contents($path);
            $originalContent = $content;

            foreach ($replacements as $search => $replace) {
                // To avoid partial replacements like in words that contain these strings, we generally use word boundaries.
                // However, for snake_case and camelCase variables in PHP/JS/Blade, standard string replace works well 
                // because these are very specific terms.
                $content = str_replace($search, $replace, $content);
            }

            if ($content !== $originalContent) {
                file_put_contents($path, $content);
                echo "Updated: $path\n";
            }
        }
    }
}
echo "Replacement complete.\n";
