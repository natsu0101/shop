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
    <title>商品一覧</title>
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
        <h3 class="my-2">商品一覧</h3>
<?php

try {
    $dsn = 'mysql:dbname=shop;host=localhost;port=8889;charset=utf8';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT code, name, price FROM mst_product WHERE 1';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    $dbh = null;

    while (true) {
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($rec == false) {
            break;
        }


        echo '<a href="shop_product.php?procode='.$rec['code'].'">';
        echo $rec['name'].'---';
        echo $rec['price'],'円';
        echo '</a>';
        echo '<br />';
    }
    echo '<a class="btn btn-primary my-2" href="shop_cartlook.php">カートを見る</a><br />';

} catch(Exception $e) {
    echo 'ただいま障害により大変ご迷惑お掛けしております。';
    exit();
}
?>
    </div>
</body>
</html>