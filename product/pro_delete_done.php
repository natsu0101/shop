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
    <title>削除完了</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>

<body>
    <h2 class="my-2">管理画面</h2>
    <div class="container">
        <h3>削除完了</h3>
        <?php if($login['staff'] == 'staff'):?>
        <?php echo $_SESSION['staff_name']; ?>さんログイン中
        <?php endif; ?>
        <br />
<?php
try {
    $pro_code = $_POST['code'];
    $pro_gazou_name = $_POST['gazou_name'];
    
    $dsn ='mysql:dbname=shop;host=localhost;port=8889;charset=utf8';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn,$user,$password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'DELETE FROM mst_product WHERE code=?';
    $stmt = $dbh->prepare($sql);
    $data[] = $pro_code;
    $stmt->execute($data);

    $db = null;

    if ($pro_gazou_name != '') {
        unlink('./gazou/'.$pro_gazou_name);
    }

} catch(Exception $e) {
    echo 'ただいま障害により大変ご迷惑おかけしております。';
    exit();
}
?>
        削除しました<br />
        <br />
        <a href="pro_list.php">戻る</a>
    </div>
</body>
</html>