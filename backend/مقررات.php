<?php

require_once 'db.php';

// Get user role and ID from session
$userRole = $_SESSION['userRole'];
$userID = $_SESSION['userID'];

// Read inputs from JSON body
$input = json_decode(file_get_contents('php://input'), true);

// Handle GET request to retrieve all records
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Prepare SQL query to retrieve all records
        $stmt = $pdo->prepare('SELECT * FROM مقررات');
        $stmt->execute();
        $records = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return HTTP response with 200 OK status code and JSON data
        header('Content-Type: application/json');
        echo json_encode($records);
        exit;
    } catch (PDOException $e) {
        // Return HTTP response with 500 Internal Server Error status code
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

// Handle POST request to create a new record
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Validate and sanitize input data
        if (!isset($input['name']) || !isset($input['description'])) {
            throw new Exception('Invalid input data');
        }
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $description = filter_var($input['description'], FILTER_SANITIZE_STRING);
        
        // Prepare SQL query to insert a new record
        $stmt = $pdo->prepare('INSERT INTO مقررات (name, description) VALUES (:name, :description)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        
        // Return HTTP response with 201 Created status code and JSON data
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Record created successfully']);
        exit;
    } catch (PDOException $e) {
        // Return HTTP response with 500 Internal Server Error status code
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

// Handle PUT request to update an existing record
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    try {
        // Validate and sanitize input data
        if (!isset($input['id']) || !isset($input['name']) || !isset($input['description'])) {
            throw new Exception('Invalid input data');
        }
        $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
        $name = filter_var($input['name'], FILTER_SANITIZE_STRING);
        $description = filter_var($input['description'], FILTER_SANITIZE_STRING);
        
        // Check if user is admin to perform update operation
        if ($userRole !== 'admin') {
            throw new Exception('Unauthorized access');
        }
        
        // Prepare SQL query to update an existing record
        $stmt = $pdo->prepare('UPDATE مقررات SET name = :name, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
        
        // Return HTTP response with 200 OK status code and JSON data
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Record updated successfully']);
        exit;
    } catch (PDOException $e) {
        // Return HTTP response with 500 Internal Server Error status code
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

// Handle DELETE request to delete an existing record
if ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    try {
        // Validate and sanitize input data
        if (!isset($input['id'])) {
            throw new Exception('Invalid input data');
        }
        $id = filter_var($input['id'], FILTER_SANITIZE_NUMBER_INT);
        
        // Check if user is admin to perform delete operation
        if ($userRole !== 'admin') {
            throw new Exception('Unauthorized access');
        }
        
        // Prepare SQL query to delete an existing record
        $stmt = $pdo->prepare('DELETE FROM مقررات WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Return HTTP response with 200 OK status code and JSON data
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Record deleted successfully']);
        exit;
    } catch (PDOException $e) {
        // Return HTTP response with 500 Internal Server Error status code
        header('Content-Type: application/json');
        echo json_encode(['error' => $e->getMessage()]);
        exit;
    }
}

// Return HTTP response with 405 Method Not Allowed status code
header('Content-Type: application/json');
echo json_encode(['error' => 'Method not allowed']);
exit;