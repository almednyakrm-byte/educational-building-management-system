**edit_مواد.php**

<?php
// Session validation
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Fetch existing record details
$data = json_decode(file_get_contents('../backend/مواد.php?id=' . $id), true);

// Check if data exists
if (empty($data)) {
    echo 'Error: Record not found.';
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit مواد</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="max-w-md mx-auto p-4 bg-white rounded-md shadow-md">
        <h1 class="text-2xl font-bold text-slate-900 mb-4">Edit مواد</h1>
        <form id="edit-form" class="space-y-4">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">Name</label>
                <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" value="<?= $data['name'] ?>">
            </div>
            <div>
                <label for="description" class="block text-sm font-medium text-slate-900">Description</label>
                <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-900 rounded-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500" rows="4"><?= $data['description'] ?></textarea>
            </div>
            <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-indigo-500 rounded-md hover:bg-indigo-700 focus:ring-indigo-500">Save Changes</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#edit-form').submit(function(e) {
                e.preventDefault();
                var formData = $(this).serialize();
                $.ajax({
                    type: 'PUT',
                    url: '../backend/مواد.php',
                    data: formData,
                    success: function(response) {
                        if (response.success) {
                            window.location.href = 'list_<?= $_SESSION['mod_slug'] ?>.php';
                        } else {
                            alert('Error: ' + response.message);
                        }
                    }
                });
            });
        });
    </script>
</body>
</html>


**backend/مواد.php**

<?php
// Check if ID is set
if (!isset($_GET['id'])) {
    echo json_encode(array('success' => false, 'message' => 'Error: ID not set.'));
    exit;
}

// Get ID from URL
$id = $_GET['id'];

// Connect to database
$conn = new mysqli('localhost', 'username', 'password', 'database');

// Check connection
if ($conn->connect_error) {
    echo json_encode(array('success' => false, 'message' => 'Error: Connection failed.'));
    exit;
}

// Get existing record details
$stmt = $conn->prepare("SELECT * FROM materials WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

// Fetch data
$data = $result->fetch_assoc();

// Close connection
$conn->close();

// Check if data exists
if (empty($data)) {
    echo json_encode(array('success' => false, 'message' => 'Error: Record not found.'));
    exit;
}

// Update record
if ($_SERVER['REQUEST_METHOD'] === 'PUT') {
    parse_str(file_get_contents('php://input'), $putData);
    $name = $putData['name'];
    $description = $putData['description'];

    $stmt = $conn->prepare("UPDATE materials SET name = ?, description = ? WHERE id = ?");
    $stmt->bind_param("ssi", $name, $description, $id);
    $stmt->execute();

    // Check if update was successful
    if ($stmt->affected_rows === 1) {
        echo json_encode(array('success' => true, 'message' => 'Record updated successfully.'));
    } else {
        echo json_encode(array('success' => false, 'message' => 'Error: Update failed.'));
    }
}

// Output existing record details
echo json_encode($data);
?>