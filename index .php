<?php
include "genetic.php";
session_start();
if (isset($_POST['go'])) {
    $fn = $_POST['fn'];
    $ln = $_POST['ln'];
    $age = isset($_POST['age']) ? $_POST['age'] : 0;
    $city = isset($_POST['city']) ? $_POST['city'] : '';
    $gen = isset($_POST['gen']) ? $_POST['gen'] : '';

    $uid = login_or_reg($fn, $ln, $age, $city, $gen);
    
    $_SESSION['user_id'] = $uid;
    $_SESSION['user_name'] = $fn . " " . $ln;
    header("Location: all_products.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المتجر الذكي - دخول</title>
    <style>
        body { background: rgb(11,76,95); font-family: Arial; display: flex; justify-content: center; align-items: center; height: 100vh; margin: 0; }
        .box { background: white; padding: 30px; border-radius: 15px; width: 320px; text-align: center; box-shadow: 0 4px 15px rgba(0,0,0,0.2); }
        .tabs { display: flex; margin-bottom: 20px; border-bottom: 2px solid #ddd; }
        .tab { flex: 1; padding: 10px; cursor: pointer; color: #777; font-weight: bold; }
        .tab.active { color: rgb(11,76,95); border-bottom: 3px solid rgb(11,76,95); }
        input, select { width: 100%; padding: 10px; margin: 8px 0; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; }
        button { background: rgb(11,76,95); color: white; border: none; padding: 12px; width: 100%; cursor: pointer; border-radius: 5px; font-size: 16px; margin-top: 10px; }
        .hidden { display: none; }
    </style>
</head>
<body>
    <div class="box">
        <h1 style="color:rgb(11,76,95); margin-bottom: 10px;">المتجر الذكي</h1>
        
        <div class="tabs">
            <div id="oldTab" class="tab active" onclick="showForm('old')">مستخدم قديم</div>
            <div id="newTab" class="tab" onclick="showForm('new')">مستخدم جديد</div>
        </div>

        <form method="POST" id="loginForm">
            <input type="text" name="fn" placeholder="الاسم الأول" required>
            <input type="text" name="ln" placeholder="الاسم الأخير" required>

            <div id="extraFields" class="hidden">
                <input type="number" id="age" name="age" placeholder="العمر">
                <input type="text" id="city" name="city" placeholder="المدينة">
                <select id="gen" name="gen">
                    <option value="Male">ذكر</option>
                    <option value="Female">أنثى</option>
                </select>
            </div>

            <button name="go" type="submit">دخول</button>
        </form>
    </div>

    <script>
        function showForm(type) {
            const extraFields = document.getElementById('extraFields');
            const ageInput = document.getElementById('age');
            const cityInput = document.getElementById('city');
            const oldTab = document.getElementById('oldTab');
            const newTab = document.getElementById('newTab');

            if (type === 'new') {
                extraFields.classList.remove('hidden');
                ageInput.required = true;
                cityInput.required = true;
                newTab.classList.add('active');
                oldTab.classList.remove('active');
            } else {
                extraFields.classList.add('hidden');
                ageInput.required = false;
                cityInput.required = false;
                oldTab.classList.add('active');
                newTab.classList.remove('active');
            }
        }
    </script>
</body>
</html>
