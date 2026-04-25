<?php 
include "genetic.php"; 
session_start();

if (!isset($_SESSION['user_id'])) { header("Location: index.php"); exit(); }
$u_id = $_SESSION['user_id'];

if (isset($_GET['act']) && isset($_GET['id'])) {
    $h = fopen("behavior.csv", "a");
    fputcsv($h, [$_GET['id'], $_GET['act'], time(), $u_id]); 
    fclose($h);
    header("Location: all_products.php"); exit();
}

$userInterests = [];
if (file_exists("behavior.csv")) {
    $h = fopen("behavior.csv", "r");
    while (($data = fgetcsv($h)) !== FALSE) {
        if (isset($data[3]) && $data[3] == $u_id) { 

            $userInterests[] = $data[0]; 
        }
    }
    fclose($h);
}

$cats = [];
foreach ($products as $p) { $cats[$p['cat']][] = $p; }
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المتجر الذكي</title>
    <style>
        :root { 
            --main: rgb(11,76,95); 
            --bg: #f8fafc; 
            --orange: #e67e22; 
        }
        
        body { font-family: 'Segoe UI', sans-serif; margin: 0; display: flex; background: var(--bg); min-height: 100vh; }
        
        
        .sidebar { width: 240px; background: white; height: 100vh; position: fixed; border-left: 2px solid rgba(11,76,95,0.1); padding: 25px; z-index: 100; box-shadow: 2px 0 10px rgba(0,0,0,0.02); }
        .sidebar a { display: block; padding: 12px; color: #555; text-decoration: none; border-radius: 12px; transition: 0.3s; margin-bottom: 8px; font-weight: 500; }
        .sidebar a:hover { background: var(--main); color: white; transform: translateX(-5px); }

        .main { margin-right: 280px; padding: 50px 30px; width: 100%; }
        
        
        .header { 
            background: white; padding: 25px 35px; display: flex; justify-content: space-between; 
            align-items: center; border-radius: 25px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); margin-bottom: 50px; flex-wrap: wrap; gap: 20px;
        }

        .welcome-text { font-size: 1.4rem; color: #333; }
        .welcome-text b { color: var(--main); font-size: 1.7rem; }

        .recommend-btn { 
            background: var(--main); color: white; padding: 15px 30px; border-radius: 40px; 
            text-decoration: none; font-size: 1.2rem; font-weight: bold; animation: pulse 2s infinite; 
            box-shadow: 0 5px 15px rgba(11,76,95,0.3);
        }

        .products-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 30px; }
        
        .card { 
            background: white; border-radius: 20px; padding: 25px; border: 1px solid rgba(0,0,0,0.05); 
            text-align: center; display: flex; flex-direction: column; justify-content: space-between; transition: 0.4s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        }
        .card:hover { transform: translateY(-10px); box-shadow: 0 15px 35px rgba(0,0,0,0.1); border-color: var(--main); }
        
        .btn { display: block; padding: 10px; border-radius: 12px; text-decoration: none; font-weight: bold; font-size: 14px; margin-bottom: 8px; border: none; transition: 0.3s; }
        .btn-view { background: #f0f2f5; color: #666; }
        .btn-cart { background: var(--orange); color: white; }
        .btn-buy { background: var(--main); color: white; }
        
        .cat-title { color: var(--main); font-size: 1.8rem; margin-top: 40px; margin-bottom: 25px; border-right: 5px solid var(--main); padding-right: 15px; }

        @keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.05); } 100% { transform: scale(1); } }

        
        @media (max-width: 992px) {
            body { flex-direction: column; }
            .sidebar { width: 100%; height: auto; position: relative; border-left: none; border-bottom: 2px solid #eee; padding: 15px; }
            .sidebar a { display: inline-block; margin: 5px; }
            .main { margin-right: 0; padding: 20px; }
            .header { flex-direction: column; text-align: center; padding: 20px; }
        }
    </style>
</head>
<body>

<div class="sidebar">
    <h3 style="color:var(--main); margin-bottom: 20px;"> التصنيفات</h3>
    <?php $idx=1; foreach(array_keys($cats) as $c): ?>
        <a href="#sec_<?php echo $idx; ?>"><?php echo $c; ?></a>
    <?php $idx++; endforeach; ?>
    <hr style="opacity: 0.1;">
    <a href="index.php" style="color:#d9534f"> تسجيل الخروج</a>
</div>

<div class="main">
    <div class="header">
    <div class="welcome-text">
        أهلاً بك، <b><?php echo $_SESSION['user_name']; ?></b> 
        <span style="font-size: 1rem; color: #777; margin-right: 10px; background: #f0f2f5; padding: 4px 12px; border-radius: 15px; border: 1px solid #e0e0e0;">
            ID: #<?php echo $u_id; ?>
        </span>
    </div>
    <a href="best_picks.php" class="recommend-btn">افضل المنتجات</a>
</div>

    <?php $idx=1; foreach($cats as $name => $items): ?>
        <div id="sec_<?php echo $idx; ?>" style="scroll-margin-top: 100px;">
            <h2 class="cat-title"><?php echo $name; ?></h2>
            <div class="products-grid">
                <?php foreach($items as $i): ?>
                    <div class="card">
                        <div>
                            <div style="background: #f8fafc; color: var(--main); padding: 5px 12px; border-radius: 15px; font-size: 0.8rem; display: inline-block; margin-bottom: 10px; font-weight: bold;">ID: #<?php echo $i['id']; ?></div>
                            <h4 style="height:45px; overflow:hidden; margin: 10px 0; color: #222;"><?php echo $i['name']; ?></h4>
                            <div style="font-size:24px; font-weight:bold; color: var(--main); margin-bottom: 20px;"><?php echo $i['price']; ?>$</div>
                        </div>
                        
                        <div style="display:flex; flex-direction:column; gap:8px;">
                            <a href="?act=cart&id=<?php echo $i['id']; ?>" class="btn btn-cart"> للسلة</a>
                            <a href="?act=buy&id=<?php echo $i['id']; ?>" class="btn btn-buy"> اشتري الآن</a>
                        </div>

                        <form method="POST" style="margin-top:15px; border-top:1px solid #f0f0f0; padding-top:15px;">
                            <input type="hidden" name="p_id" value="<?php echo $i['id']; ?>">
                            <select name="stars" style="width:100%; padding:8px; border-radius:8px; border: 1px solid #ddd; margin-bottom:8px;">
                                <option value="5">⭐⭐⭐⭐⭐</option>
                                <option value="4">⭐⭐⭐⭐</option>
                                <option value="3">⭐⭐⭐</option>
                                <option value="2">⭐⭐</option>
                                <option value="1">⭐</option>

                            </select>
                            <button name="rate" style="width:100%; cursor:pointer; background:none; border:1px solid var(--main); color: var(--main); padding:8px; border-radius:8px; font-weight: bold;">تقييم المنتج</button>
                        </form>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    <?php $idx++; endforeach; ?>
</div>

</body>
</html>
