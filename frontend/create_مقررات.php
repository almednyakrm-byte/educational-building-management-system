**create_مقررات.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
require_once '../backend/db.php';

// Check if form has been submitted
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $status = trim($_POST['status']);

    // Check for empty fields
    if (empty($name) || empty($description) || empty($status)) {
        $error = 'Please fill in all fields';
    } else {
        // Insert new record
        $sql = "INSERT INTO مقررات (name, description, status) VALUES ('$name', '$description', '$status')";
        $result = mysqli_query($conn, $sql);

        if ($result) {
            // Redirect back to list page
            header('Location: list_مقررات.php');
            exit;
        } else {
            $error = 'Failed to create new record';
        }
    }
}

// Include header
require_once '../backend/header.php';

?>

<!-- Create new record form -->
<div class="max-w-md mx-auto p-4 bg-white rounded-lg shadow-md">
    <h2 class="text-slate-900 text-lg font-bold mb-4">Create New مقررات</h2>

    <?php if (isset($error)) : ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded text-sm" role="alert">
            <?= $error ?>
        </div>
    <?php endif; ?>

    <form id="create-form" method="post">
        <div class="mb-4">
            <label for="name" class="block text-slate-900 text-sm font-bold mb-2">Name:</label>
            <input type="text" id="name" name="name" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
        </div>

        <div class="mb-4">
            <label for="description" class="block text-slate-900 text-sm font-bold mb-2">Description:</label>
            <textarea id="description" name="description" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required></textarea>
        </div>

        <div class="mb-4">
            <label for="status" class="block text-slate-900 text-sm font-bold mb-2">Status:</label>
            <select id="status" name="status" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full p-2.5" required>
                <option value="">Select Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <button type="submit" name="submit" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">Create</button>
    </form>
</div>

<!-- Include footer -->
<?php require_once '../backend/footer.php'; ?>

<script>
    // AJAX form submission
    document.getElementById('create-form').addEventListener('submit', function(event) {
        event.preventDefault();

        const formData = new FormData(this);

        fetch('../backend/مقررات.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.href = 'list_مقررات.php';
            } else {
                console.error(data.error);
            }
        })
        .catch(error => console.error(error));
    });
</script>


**مقررات.php (backend)**

<?php
// Include database connection
require_once '../backend/db.php';

// Process form data
if (isset($_POST['submit'])) {
    // Validate form data
    $name = trim($_POST['name']);
    $description = trim($_POST['description']);
    $status = trim($_POST['status']);

    // Insert new record
    $sql = "INSERT INTO مقررات (name, description, status) VALUES ('$name', '$description', '$status')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['error' => 'Failed to create new record']);
    }
}
?>