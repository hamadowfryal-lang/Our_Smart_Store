<?php 
include "genetic.php"; 
session_start(); 
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <style>
        :root { --main: rgb(11,76,95); --bg: #f8fafc; --orange: #e67e22; }
        body { font-family: 'Segoe UI', Tahoma, sans-serif; background: var(--bg); margin: 0; padding: 50px 20px; }
        .container { max-width: 1200px; margin: 0 auto; text-align: center; }
        
        h1 { color: var(--main); font-size: 3rem; margin-bottom: 10px; }
        .subtitle { color: #666; font-size: 1.2rem; margin-bottom: 50px; }

        .grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 30px; }

        .pick-card { 
            background: white; border-radius: 25px; padding: 25px; 
            box-shadow: 0 15px 35px rgba(0,0,0,0.05); transition: 0.4s; 
            border: 1px solid rgba(11,76,95,0.1); position: relative; 
            animation: fadeInUp 0.6s ease backwards;
        }
        .pick-card:hover { transform: translateY(-15px); box-shadow: 0 25px 50px rgba(0,0,0,0.1); }
        
        .pick-card::after { content: ' مرشح لك'; position: absolute; top: -10px; right: 20px; background: var(--main); color: white; padding: 5px 15px; border-radius: 10px; font-size: 0.8rem; }

        .item-cat { color: var(--main); font-weight: bold; font-size: 0.9rem; background: #eef2f3; padding: 5px 12px; border-radius: 15px; display: inline-block; }
        .item-name { font-size: 1.4rem; margin: 15px 0; color: #333; height: 50px; overflow: hidden; }
        .item-price { font-size: 1.6rem; color: #27ae60; font-weight: bold; margin-bottom: 20px; }

        .btn { display: block; padding: 10px; border-radius: 10px; text-decoration: none; margin-bottom: 8px; font-weight: bold; font-size: 0.9rem; }
        
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

<div class="container">
    <h1> اختياراتنا </h1>
    <p class="subtitle">لقد قمنا بتقديم أفضل 7 منتجات في المتجر لحضرتكم</p>

<div class="grid">
    <?php 
    $best = afdal_she($products, $_SESSION['user_id']); 
    
    if (empty($best)) {
        $best = array_column(array_slice($products, 0, 7), 'id');
    }

    $delay = 0;
    foreach($best as $id) {
        foreach($products as $p) {
            if($p['id'] == $id) {
                echo "<div class='pick-card' style='animation-delay: {$delay}s'>
                        <div class='item-cat'>{$p['cat']}</div>
                        <div class='item-name'>{$p['name']}</div>
                        <div class='item-price'>{$p['price']}$</div>
                        
                        <a href='all_products.php?act=cart&id={$id}' style='background:var(--orange); color:white;' class='btn'> للسلة</a>
                        <a href='all_products.php?act=buy&id={$id}' style='background:var(--main); color:white;' class='btn'> شراء الآن</a>
                      </div>";
                $delay += 0.1;
                break; 
            }
        }
    }
    ?>
    </div>
    <br><br>
    <a href="all_products.php" style="color:var(--main); font-weight:bold; text-decoration:none; border-bottom: 2px solid;">← العودة للمتجر</a>
</div>

</body>
</html>
