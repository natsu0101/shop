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
        <h3>スタッフ修正</h3>
        <?php if($login['staff'] == 'staff'):?>
        <?php echo $_SESSION['staff_name']; ?>さんログイン中
        <?php endif; ?>
        <br />
<?php 
try {
    $staff_code = $_GET['staffcode'];

    $dsn = 'mysql:dbname=shop;host=localhost;port=8889;charset=utf8';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = 'SELECT name, name FROM mst_staff WHERE code=?';
    $stmt = $dbh->prepare($sql);
    $data[] = $staff_code;
    $stmt->execute($data);

    $rec = $stmt->fetch(PDO::FETCH_ASSOC);
    $staff_name = $rec['name'];

    $dbh = null;

} catch(Exception $e) {
    echo 'ただいま障害により大変ご迷惑お掛けしております。';
    exit();
}
?>
        <br />
        スタッフコード<br />
        <?php echo $staff_code; ?>
        <br />
        <br />
        <form method="post" action="staff_edit_check.php">
            <input type="hidden" name="code" value="<?php echo $staff_code; ?>">
            <div class="form-group">
                <label class="my-2">スタッフ名</label>
                <input style="width:150px;" type="text" name="name" class="form-control">
            </div>
            <div class="form-group">
                <label class="my-2">パスワードを入力してください</label>
                <input style="width:250px;" type="text" name="pass" class="form-control mb-3">
            </div>
            <div class="form-group">
                <label class="my-2">パスワードをもう一度入力してください。</label>
                <input style="width:250px;" type="text" name="pass2" class="form-control mb-3">
            </div>
            <input type="button" onclick="history.back()" value="戻る">
            <input type="submit" value="OK">
        </form>
    </div>
</body>
</html>