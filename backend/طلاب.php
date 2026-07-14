<?php

// Include database configuration file
require_once 'db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Get input data from JSON request body
$inputData = json_decode(file_get_contents('php://input'), true);

// Handle GET request to retrieve all students
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Prepare SQL query to select all students
        $stmt = $pdo->prepare('SELECT * FROM طلاب');
        $stmt->execute();
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Return students in JSON format
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode($students);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle POST request to create a new student
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate input data
    if (!isset($inputData['name']) || !isset($inputData['email']) || !isset($inputData['phone'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }
    
    // Sanitize input data
    $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($inputData['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($inputData['phone'], FILTER_SANITIZE_NUMBER_INT);
    
    // Check if user is admin to create a new student
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    try {
        // Prepare SQL query to insert a new student
        $stmt = $pdo->prepare('INSERT INTO طلاب (name, email, phone) VALUES (:name, :email, :phone)');
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        
        // Return new student ID in JSON format
        http_response_code(201);
        header('Content-Type: application/json');
        echo json_encode(['id' => $pdo->lastInsertId()]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle PUT request to update a student
elseif ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    // Validate input data
    if (!isset($inputData['id']) || !isset($inputData['name']) || !isset($inputData['email']) || !isset($inputData['phone'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }
    
    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);
    $name = filter_var($inputData['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($inputData['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($inputData['phone'], FILTER_SANITIZE_NUMBER_INT);
    
    // Check if user is admin to update a student
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    try {
        // Prepare SQL query to update a student
        $stmt = $pdo->prepare('UPDATE طلاب SET name = :name, email = :email, phone = :phone WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':phone', $phone);
        $stmt->execute();
        
        // Return updated student ID in JSON format
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['id' => $id]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}

// Handle DELETE request to delete a student
elseif ($_SERVER['REQUEST_METHOD'] === 'DELETE') {
    // Validate input data
    if (!isset($inputData['id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid request data']);
        exit;
    }
    
    // Sanitize input data
    $id = filter_var($inputData['id'], FILTER_SANITIZE_NUMBER_INT);
    
    // Check if user is admin to delete a student
    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        echo json_encode(['error' => 'Forbidden']);
        exit;
    }
    
    try {
        // Prepare SQL query to delete a student
        $stmt = $pdo->prepare('DELETE FROM طلاب WHERE id = :id');
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        
        // Return deleted student ID in JSON format
        http_response_code(200);
        header('Content-Type: application/json');
        echo json_encode(['id' => $id]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Internal Server Error']);
    }
}