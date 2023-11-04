<?php
// Database configuration
$host = "localhost";
$username = "root";
$password = "";
$database = "nota_test_php";

// Connect to the database
$mysqli = new mysqli($host, $username, $password, $database);
if ($mysqli->connect_error) {
    die("Database connection failed: " . $mysqli->connect_error);
}

// Function to save data to the database
function saveToDatabase($title, $url, $picture, $abstract) {
    global $mysqli;

    $dateCreated = date("Y-m-d H:i:s");
    
    // Prevent SQL injection by using prepared statements
    $stmt = $mysqli->prepare("INSERT INTO wiki_sections (date_created, title, url, picture, abstract) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $dateCreated, $title, $url, $picture, $abstract);
    
    if ($stmt->execute()) {
        echo "Data saved to the database.\n";
    } else {
        echo "Error saving data to the database: " . $stmt->error;
    }
    
    $stmt->close();
}

// URL of the Wikipedia page
$url = "https://www.wikipedia.org/";

// Download the page and create a DOMDocument
$pageContent = file_get_contents($url);
$doc = new DOMDocument();
libxml_use_internal_errors(true); // Disable libxml errors
$doc->loadHTML($pageContent);
libxml_clear_errors();

// Use DOMXPath to navigate the document
$xpath = new DOMXPath($doc);

// Find sections containing headings, abstracts, pictures, and links
$sections = $xpath->query('//span[@class="mw-headline"] | //p | //img | //a');

$title = $url = $picture = $abstract = '';

foreach ($sections as $section) {
    $tagName = $section->tagName;
    $nodeValue = trim($section->nodeValue);
    
    if ($tagName === 'span') {
        $title = $nodeValue;
    } elseif ($tagName === 'a') {
        $url = $section->getAttribute('href');
    } elseif ($tagName === 'img') {
        $picture = $section->getAttribute('src');
    } elseif ($tagName === 'p') {
        $abstract = $nodeValue;
        saveToDatabase($title, $url, $picture, $abstract);
        $title = $url = $picture = $abstract = '';
    }
}

// Close the database connection
$mysqli->close();
?>