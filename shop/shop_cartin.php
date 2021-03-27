<?php
session_start();
session_regenerate_id(true);
if ($_SESSION['login'] == false) {
    $login['staff'] = 'guest';
} else {
    $login['staff'] = 'staff';
}
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>カート追加</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>

<body>
    <h2 class="my-2">Shop Portfolio</h2>    
    <?php if($login['staff'] == 'staff'):?>
    ようこそ <?php echo $_SESSION['staff_name']; ?>様
    <a href="member_logout.html">ログアウト</a><br />';
    <?php endif; ?>

    <?php if($login['staff'] == 'guest'):?>
    ようこそゲスト様
    <a href="member_login.html">会員ログイン</a><br />
    <?php endif; ?>
    <div class="container">        
        <?php 
try {
    $pro_code = $_GET['procode'];

    if (isset($_SESSION['cart']) == true) {
        $cart = $_SESSION['cart'];
        $kazu = $_SESSION['kazu'];
        if (in_array($pro_code, $cart)== true) {
            echo 'その商品はすでにカートに入っています<br />';
            echo '<a href ="shop_list.php">商品一覧に戻る</a>';
        exit();
        }
    }

    $cart[] = $pro_code;
    $kazu[] = 1;
    $_SESSION['cart'] = $cart;
    $_SESSION['kazu'] = $kazu;

} catch(Exception $e) {
    echo 'ただいま障害により大変ご迷惑お掛けしております。';
    exit();
}
?>
<br />
        カートに追加しました。<br />
        <a href="shop_list.php">商品一覧に戻る</a>
    </div>
</body>
</html>