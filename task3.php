<?php

/**
 * The TableCreator class creates and manages a table named "Test" and provides methods for data operations.
 * It's marked as final to prevent inheritance.
 */
final class TableCreator {
    /**
     * Constructor that creates the "Test" table and fills it with random data.
     */
    public function __construct() {
        $this->create();
        $this->fill();
    }

    /**
     * Creates the "Test" table with specified fields.
     * This method is only accessible within the class.
     */
    private function create() {
        // Database connection and creation of the "Test" table
        $pdo = new PDO("mysql:host=localhost;dbname=nota_test_php", "root", "");
        $pdo->exec("CREATE TABLE Test (
            id INT AUTO_INCREMENT PRIMARY KEY,
            script_name VARCHAR(25),
            start_time DATETIME,
            end_time DATETIME,
            result ENUM('normal', 'illegal', 'failed', 'success')
        )");
    }

    /**
     * Fills the "Test" table with random data.
     * This method is only accessible within the class.
     */
    private function fill() {
        // Insert random data into the "Test" table
        $pdo = new PDO("mysql:host=localhost;dbname=nota_test_php", "root", "");
        for ($i = 1; $i <= 100; $i++) {
            $scriptName = "Script " . $i;
            $startTime = date("Y-m-d H:i:s", rand(strtotime("2022-01-01"), strtotime("2023-01-01")));
            $endTime = date("Y-m-d H:i:s", strtotime($startTime) + rand(60, 3600));
            $result = ['normal', 'illegal', 'failed', 'success'][rand(0, 3)];

            $stmt = $pdo->prepare("INSERT INTO Test (script_name, start_time, end_time, result) VALUES (?, ?, ?, ?)");
            $stmt->execute([$scriptName, $startTime, $endTime, $result]);
        }
    }

    /**
     * Selects data from the "Test" table based on the "result" field.
     * This method is accessible from outside the class.
     *
     * @return array An array of rows matching the specified criterion.
     */
    public function get() {
        $pdo = new PDO("mysql:host=localhost;dbname=nota_test_php", "root", "");
        $stmt = $pdo->prepare("SELECT * FROM Test WHERE result IN ('normal', 'success')");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

// Example usage:
$tableCreator = new TableCreator();
$data = $tableCreator->get();
print_r($data);