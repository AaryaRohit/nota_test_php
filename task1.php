<?php
/**
 * This script finds and lists files in the /datafiles directory with the .ixt extension, sorted by name.
 */

// Define the directory path
$directory = './datafiles';

// Check if the directory exists
if (is_dir($directory)) {
    // Create an array to store the matching file names
    $matchingFiles = [];

    // Loop through the directory and collect .ixt files
    $dirHandle = opendir($directory);
    while (false !== ($file = readdir($dirHandle))) {
        $filePath = $directory . '/' . $file;
        if (is_file($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'ixt') {
            $matchingFiles[] = $file;
        }
    }
    closedir($dirHandle);

    // Sort the matching files by name
    sort($matchingFiles);

    // Display the sorted file names
    echo "Sorted .ixt files in $directory: <br>";
    foreach ($matchingFiles as $file) {
        echo $file . "<br>";
    }
} else {
    echo "Directory $directory does not exist.<br>";
}
?>