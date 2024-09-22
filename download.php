<?php
function zipData($source, $destination) {
    if (!extension_loaded('zip') || !file_exists($source)) {
        return false;
    }

    $zip = new ZipArchive();
    if (!$zip->open($destination, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
        return false;
    }

    $source = realpath($source);

    if (is_dir($source)) {
        $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::LEAVES_ONLY);
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($source) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
    } else if (is_file($source)) {
        $zip->addFromString(basename($source), file_get_contents($source));
    }

    return $zip->close();
}

$source = __DIR__; // Or specify your directory
$destination = __DIR__ . '/backup.zip';

if (zipData($source, $destination)) {
    header('Content-Type: application/zip');
    header('Content-Disposition: attachment; filename="backup.zip"');
    header('Content-Length: ' . filesize($destination));
    readfile($destination);

    // Optionally, delete the zip file after download
    unlink($destination);
} else {
    echo 'Failed to create backup.';
}
?>