<?php
// Session check
session_start();
if (!isset($_SESSION['authenticated'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>نظام إدارة المباني التعليمية</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-slate-900 text-white">
    <div class="container mx-auto p-4 pt-6 md:p-6 lg:p-12 xl:p-24">
        <div class="flex justify-between">
            <h1 class="text-3xl font-bold">نظام إدارة المباني التعليمية</h1>
            <button class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded" onclick="logout()">تسجيل الخروج</button>
        </div>
        <div class="mt-12">
            <h2 class="text-2xl font-bold">مرحباً!</h2>
            <p class="text-lg">مرحباً بك في نظام إدارة المباني التعليمية.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-12">
            <div class="bg-white bg-opacity-10 rounded-lg p-4 shadow-md">
                <h3 class="text-lg font-bold">إجمالي الطلاب</h3>
                <p id="total-students" class="text-3xl font-bold">0</p>
            </div>
            <div class="bg-white bg-opacity-10 rounded-lg p-4 shadow-md">
                <h3 class="text-lg font-bold">إجمالي المواد</h3>
                <p id="total-courses" class="text-3xl font-bold">0</p>
            </div>
            <div class="bg-white bg-opacity-10 rounded-lg p-4 shadow-md">
                <h3 class="text-lg font-bold">إجمالي المقررات</h3>
                <p id="total-classes" class="text-3xl font-bold">0</p>
            </div>
            <div class="bg-white bg-opacity-10 rounded-lg p-4 shadow-md">
                <h3 class="text-lg font-bold">إجمالي الأساتذة</h3>
                <p id="total-teachers" class="text-3xl font-bold">0</p>
            </div>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-12">
            <a href="students.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إدارة الطلاب</a>
            <a href="courses.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إدارة المواد</a>
            <a href="classes.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إدارة المقررات</a>
            <a href="teachers.php" class="bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">إدارة الأساتذة</a>
        </div>
    </div>

    <script>
        function logout() {
            window.location.href = 'logout.php';
        }

        // Fetch stats dynamically via Javascript API calls from the backend files
        fetch('api/students.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-students').innerHTML = data.total;
            });

        fetch('api/courses.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-courses').innerHTML = data.total;
            });

        fetch('api/classes.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-classes').innerHTML = data.total;
            });

        fetch('api/teachers.php')
            .then(response => response.json())
            .then(data => {
                document.getElementById('total-teachers').innerHTML = data.total;
            });
    </script>
</body>
</html>