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
    <title>試作ECサイト</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>

<body>
    <h2 class="my-2">管理画面</h2>
    <div class="container">
        <?php if($login['staff'] == 'staff'):?>
        ようこそ
        <?php echo $_SESSION['staff_name']; ?>様
        <a href="member_logout.html">ログアウト</a><br />
        <?php endif; ?>
        <br />

        <?php require_once('../common/common.php'); ?>
        ダウンロードしたい注文日を選んでください<br />
        <form method="post" action="order_download_done.php">
            <?php pulldown_year(); ?>年
            <?php pulldown_month(); ?>月
            <?php pulldown_day(); ?>日
            <br />
            <br />
            <input type="submit" value="ダウンロードへ">
            <br />
            <a href="../staff_login/staff_top.php">ショップ管理トップメニューへ</a>
        </form>
    </div>
</body>
</html>