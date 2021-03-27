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
        <h3>入力内容確認</h3>
        <?php if($login['staff'] == 'staff'):?>
        <?php echo $_SESSION['staff_name']; ?>さんログイン中
        <?php endif; ?>
        <br />
        <br />
<?php
    require_once('../common/common.php');
    $post = sanitize($_POST);
    $staff_name = $post['name'];
    $staff_pass = $post['pass'];
    $staff_pass2 = $post['pass2'];

    if ($staff_name == '') {
        echo 'スタッフ名が入力されていません。<br />';
    } else {
        echo 'スタッフ名：';
        echo $staff_name;
        echo '<br />';
    }

    if ($staff_pass == '') {
        echo 'パスワードが入力されていません。<br />';
    }
    if ($staff_pass != $staff_pass2) {
        echo 'パスワードが一致しません。<br />';
    }

    if ($staff_name == '' || $staff_pass == '' || $staff_pass != $staff_pass2)  {
        echo '<form>';
        echo '<input type="button" onclick="history.back()" value="戻る">';
        echo '<form>';
    } else {
        $staff_pass = password_hash($staff_pass, PASSWORD_DEFAULT);
        echo 'パスワード：[セキュリティのため非表示]';
        echo '<br />';
        echo '<br />';
        echo '上記内容で追加してよろしいでしょうか。';
        echo '<form method="post" action="staff_add_done.php">';
        echo '<input type="hidden" name="name" value="'.$staff_name.'">';
        echo '<input type="hidden" name="pass" value="'.$staff_pass.'">';
        echo '<input type="button" onclick="history.back()" value="戻る">';
        echo '<input type="submit" value="OK">';
        echo '</form>';
    }
?>
</div>
</body>
</html>