<?php
try{
    require_once('../common/common.php');
    $post = sanitize($_POST);
    $staff_code = $post['code'];
    $staff_pass = $post['pass'];

    $dsn ='mysql:dbname=shop;host=localhost;port=8889;charset=utf8';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn,$user,$password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT name, password FROM mst_staff WHERE code=?';
    $stmt = $dbh->prepare($sql);
    $data[] = $staff_code;
    $stmt->execute($data);

    $dbh = null;

    $rec = $stmt->fetch(PDO::FETCH_ASSOC);

    if (password_verify($staff_pass,$rec['password'])) {
        session_start();
        $_SESSION['login'] = 1;
        $_SESSION['staff_code'] = $staff_code;
        $_SESSION['staff_name'] = $rec['name'];
        header('Location: staff_top.php');
        exit();
    } else {
        $error['login'] = 'failed';
    }
    } catch(Exception $e) {
        echo 'ただいま障害により大変ご迷惑おかけしております。';
        exit();
    }
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログインエラー</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>

<body>
    <h2 class="my-2">管理画面</h2>
    <div class="container">
        <?php if($error['login'] == 'failed'): ?>
        <p class="my-2">スタッフコードかパスワードが間違っています</p>
        <a href="staff_login.html">戻る</a>  
        <?php endif ?>
    </div>
</body>
</html>