<?php
include "genetic.php";
session_start();
if (isset($_POST['go'])) {
    $uid = login_or_reg($_POST['fn'], $_POST['ln'], $_POST['age'], $_POST['city'], $_POST['gen']);
    $_SESSION['user_id'] = $uid;
    $_SESSION['user_name'] = $_POST['fn'] . " " . $_POST['ln'];
    header("Location: all_products.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        body { background: rgb(11,76,95); font-family: Arial; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { background: white; padding: 30px; border-radius: 15px; width: 300px; text-align: center; }
        input, select { width: 100%; padding: 10px; margin: 5px 0; border: 1px solid #ddd; border-radius: 5px; }
        button { background: rgb(11,76,95); color: white; border: none; padding: 10px; width: 100%; cursor: pointer; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="box">
        <h1 style="color:rgb(11,76,95)">المتجر الذكي</h1>
        <form method="POST">
            <input type="text" name="fn" placeholder="الاسم الأول" required>
            <input type="text" name="ln" placeholder="الاسم الاخير " required>
            <input type="number" name="age" placeholder="العمر" required>
            <input type="text" name="city" placeholder="المدينة" required>
            <select name="gen"><option value="Male">ذكر</option><option value="Female">أنثى</option></select>
            <button name="go">دخول</button>
        </form>
    </div>
</body>
</html>