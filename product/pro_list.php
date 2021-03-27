<?php
session_start();
session_regenerate_id(true);
if ($_SESSION['login'] == false) {
    echo 'ログインされていません。<br />';
    echo '<a href="../staff_login/staff_login.html">ログイン画面へ</a>';
    exit();
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
    <h2 class="my-2">管理画面</h2>
    <div class="container">
        <h3>商品一覧</h3>
        <?php if($login['staff'] == 'staff'):?>
        <?php echo $_SESSION['staff_name']; ?>さんログイン中
        <?php endif; ?>
        <br /> 
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

    echo '<form method="post" action="pro_branch.php">';
    while (true) {
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($rec == false) {
            break;
        }
        echo '<div class="form-check">';
        echo '<input class="form-check-input" type="radio" name="product" id="flexRadioDefault1" value="'.$rec['code'].'">';
        echo '<label class="form-check-label" for="flexRadioDefault1">';
        echo $rec['name'].'---';
        echo $rec['price'],'円';
        echo ' </label>';
        echo '</div>';
    }

    echo '<input type="submit" class="btn btn-primary"  name="disp" value="参照">';
    echo '<input type="submit" class="btn btn-primary mx-1"  name="add" value="追加">';
    echo '<input type="submit" class="btn btn-primary"  name="edit" value="修正">';
    echo '<input type="submit" class="btn btn-danger mx-1"  name="delete" value="削除">';

    echo '</form>';

} catch(Exception $e) {
    echo 'ただいま障害により大変ご迷惑お掛けしております。';
    exit();
}
?>
        <a href="../staff_login/staff_top.php">ショップ管理トップメニューへ</a>
    </div>
</body>
</html>