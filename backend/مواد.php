<?php
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data
$input = json_decode(file_get_contents('php://input'), true);

// Check if input data is valid
if (empty($input)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid input']);
    exit;
}

// Check if user is admin
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    http_response_code(403);
    echo json_encode(['error' => 'Forbidden']);
    exit;
}

// Handle GET request
if (isset($_GET['action']) && $_GET['action'] == 'get_all') {
    try {
        // Prepare statement for selecting all rows
        $stmt = $pdo->prepare('SELECT * FROM مواد');
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($rows);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'get_one') {
    try {
        // Prepare statement for selecting one row by ID
        $stmt = $pdo->prepare('SELECT * FROM مواد WHERE id = :id');
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            http_response_code(200);
            header('Content-Type: application/json');
            echo json_encode($row);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Not Found']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle POST request
if (isset($_GET['action']) && $_GET['action'] == 'create') {
    try {
        // Validate input data
        if (!isset($input['name']) || !isset($input['description'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
            exit;
        }

        // Prepare statement for inserting a new row
        $stmt = $pdo->prepare('INSERT INTO مواد (name, description) VALUES (:name, :description)');
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':description', $input['description']);
        $stmt->execute();
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Created successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle PUT request
if (isset($_GET['action']) && $_GET['action'] == 'update') {
    try {
        // Validate input data
        if (!isset($input['id']) || !isset($input['name']) || !isset($input['description'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
            exit;
        }

        // Prepare statement for updating an existing row
        $stmt = $pdo->prepare('UPDATE مواد SET name = :name, description = :description WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->bindParam(':name', $input['name']);
        $stmt->bindParam(':description', $input['description']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Updated successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle DELETE request
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    try {
        // Validate input data
        if (!isset($input['id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid input']);
            exit;
        }

        // Prepare statement for deleting an existing row
        $stmt = $pdo->prepare('DELETE FROM مواد WHERE id = :id');
        $stmt->bindParam(':id', $input['id']);
        $stmt->execute();
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['message' => 'Deleted successfully']);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}