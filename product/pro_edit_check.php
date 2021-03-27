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
    <title>入力内容確認</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>

<body>
    <h2 class="my-2">管理画面</h2>
    <div class="container">
        <h3>入力内容確認</h3>
        <?php if($login['staff'] == 'staff'):?>
        <?php echo $_SESSION['staff_name']; ?>さんログイン中
        <?php endif; ?>
        <br />
        <br />

<?php
require_once('../common/common.php');
$post = sanitize($_POST);
$pro_code = $post['code'];
$pro_name = $post['name'];
$pro_price = $post['price'];
$pro_gazou_name_old = $post['gazou_name_old'];
$pro_gazou = $_FILES['gazou'];

if ($pro_name == '') {
    echo '商品名が入力されていません。<br />';
} else {
    echo '商品名:';
    echo $pro_name;
    echo '<br />';
}

if (preg_match('/\A[0-9]+\z/', $pro_price) == 0) {
    echo '価格をきちんと入力してください。<br />';
} else {
    echo '価格:';
    echo $pro_price;
    echo '円<br />';   
}

if ($pro_gazou['size'] > 0) {
    if ($pro_gazou['size'] > 100000) {
        echo '画像が大き過ぎます';
    } else {
        move_uploaded_file($pro_gazou['tmp_name'],'./gazou/'.$pro_gazou['name']);
        echo '<img src="./gazou/'.$pro_gazou['name'].'">';
        echo '<br />';
    }
}

if ($pro_name == '' ||preg_match('/\A[0-9]+\z/', $pro_price) == 0 || $pro_gazou['size'] > 1000000) {
    echo '<form>';
    echo '<input type="button" onclick="history.back()" value="戻る">';
    echo '</form>';
} else {
    echo '<br />';
    echo '上記のように変更します。<br />';
    echo '<form method="post" action="pro_edit_done.php">';
    echo '<input type="hidden" name="code" value="'.$pro_code.'">';
    echo '<input type="hidden" name="name" value="'.$pro_name.'">';
    echo '<input type="hidden" name="price" value="'.$pro_price.'">';
    echo '<input type="hidden" name="gazou_name_old" value="'.$pro_gazou_name_old.'">';
    echo '<input type="hidden" name="gazou_name" value="'.$pro_gazou['name'].'">';

    echo '<input type="button" onclick="history.back()" value="戻る">';
    echo '<input type="submit" value="OK">';
    echo '</form>';
}
?>
    </div>
</body>
</html>