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
    <title>試作ECサイト</title>
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

    $dsn = 'mysql:dbname=shop;host=localhost;port=8889;charset=utf8';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT name, price, gazou FROM mst_product WHERE code=?';
    $stmt = $dbh->prepare($sql);
    $data[] = $pro_code;
    $stmt->execute($data);

    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    $pro_name = $rec['name'];
    $pro_price = $rec['price'];
    $pro_gazou_name = $rec['gazou'];

    $dbh = null;

    if ($pro_gazou_name == '') {
        $disp_gazou = '';
    } else {
        $disp_gazou='<img src="../product/gazou/'.$pro_gazou_name.'">';
    }

    echo '<a class="btn btn-primary my-2" href="shop_cartin.php?procode='.$pro_code.'">カートに入れる</a><br />';
    
} catch(Exception $e) {
    echo 'ただいま障害により大変ご迷惑お掛けしております。';
    exit();
}
?>       
        <h3>商品情報参照</h3>
        商品名<br />
        <?php echo $pro_name; ?>
        <br />
        <br />
        価格<br />
        <?php echo $pro_price; ?>円
        <br />
        <br />
        <?php echo $disp_gazou; ?>
        <br />
        <br />
        商品コード<br />
        <?php echo $pro_code; ?>
        <br />
        <form>
            <input type="button" onclick="history.back()" value="戻る">
        </form>
    </div>
</body>
</html>