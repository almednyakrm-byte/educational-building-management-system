**create_طلاب.php**

<?php
// Start session
session_start();

// Check if user is logged in
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit;
}

// Include header
include_once 'header.php';

// Include navigation
include_once 'navigation.php';

// Include form
include_once 'create_form.php';

// Include footer
include_once 'footer.php';
?>


**create_form.php**

<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <h2 class="text-2xl font-bold text-slate-900 mb-4">إضافة طالب جديد</h2>
    <form id="create-form" class="space-y-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="name" class="block text-sm font-medium text-slate-900">الإسم</label>
                <input type="text" id="name" name="name" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
            <div>
                <label for="email" class="block text-sm font-medium text-slate-900">الإيميل</label>
                <input type="email" id="email" name="email" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
            </div>
        </div>
        <div>
            <label for="phone" class="block text-sm font-medium text-slate-900">رقم الهاتف</label>
            <input type="tel" id="phone" name="phone" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required>
        </div>
        <div>
            <label for="address" class="block text-sm font-medium text-slate-900">العنوان</label>
            <textarea id="address" name="address" class="block w-full p-2 mt-1 text-sm text-slate-900 bg-white border border-slate-300 rounded-md focus:outline-none focus:ring-indigo-500 focus:border-indigo-500" required></textarea>
        </div>
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-500 hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">إضافة</button>
    </form>
</div>

<script>
    $(document).ready(function() {
        $('#create-form').submit(function(e) {
            e.preventDefault();
            $.ajax({
                type: 'POST',
                url: '../backend/طلاب.php',
                data: $(this).serialize(),
                success: function(response) {
                    if (response == 'success') {
                        window.location.href = 'list_طلاب.php';
                    } else {
                        alert('Error: ' + response);
                    }
                }
            });
        });
    });
</script>


**header.php**

<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة طلاب</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css">
</head>
<body>
    <?php include_once 'navigation.php'; ?>
    <div class="container mx-auto p-4">
        <?php include_once 'content.php'; ?>
    </div>
    <?php include_once 'footer.php'; ?>
</body>
</html>


**navigation.php**

<nav class="bg-slate-900 py-2">
    <div class="container mx-auto px-4 flex justify-between items-center">
        <a href="#" class="text-white text-lg font-bold">إدارة طلاب</a>
        <ul class="flex items-center space-x-4">
            <li><a href="#" class="text-white hover:text-indigo-500">قائمة الطلاب</a></li>
            <li><a href="#" class="text-white hover:text-indigo-500">إضافة طالب جديد</a></li>
        </ul>
    </div>
</nav>


**footer.php**

<footer class="bg-slate-900 py-4">
    <div class="container mx-auto px-4 text-center text-white">
        &copy; 2023 إدارة طلاب
    </div>
</footer>


**content.php**

<?php include_once 'create_form.php'; ?>


Note: This code assumes that you have the jQuery library included in your project. If not, you can include it by adding the following line to your header.php file:

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>