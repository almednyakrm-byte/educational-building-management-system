<?php
// Start session
session_start();

// Session validation
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit;
}

// Include database connection
include '../backend/db.php';

// Module slug
$mod_slug = 'مواد';

// Page title
$page_title = 'Create ' . $mod_slug;

// Include header
include 'header.php';
?>

<!-- Premium Tailwind UI form -->
<div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <h1 class="text-3xl font-bold text-slate-900 mb-4"><?= $page_title ?></h1>
    <form id="create-form" class="space-y-6">
        <div class="flex flex-col">
            <label for="name" class="text-slate-900 font-bold mb-2">Name</label>
            <input type="text" id="name" name="name" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="flex flex-col">
            <label for="description" class="text-slate-900 font-bold mb-2">Description</label>
            <textarea id="description" name="description" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
        </div>
        <div class="flex flex-col">
            <label for="quantity" class="text-slate-900 font-bold mb-2">Quantity</label>
            <input type="number" id="quantity" name="quantity" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div class="flex flex-col">
            <label for="unit_price" class="text-slate-900 font-bold mb-2">Unit Price</label>
            <input type="number" id="unit_price" name="unit_price" class="block w-full p-2 pl-10 text-sm text-gray-700 border border-gray-200 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Create</button>
    </form>
</div>

<!-- AJAX JavaScript -->
<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/<?= $mod_slug ?>.php',
                data: $(this).serialize(),
                success: function() {
                    window.location.href = 'list_<?= $mod_slug ?>.php';
                }
            });
        });
    });
</script>

<?php
// Include footer
include 'footer.php';
?>