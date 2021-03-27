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
    <title>カートの中身</title>
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
        <h3 class="my-2">カートの中身</h3>
    <?php 
        try {
            if (isset($_SESSION['cart']) == true) {
            $cart =  $_SESSION['cart'];
            $kazu = $_SESSION['kazu'];
            $max = count($cart);
        } else {
            $max = 0;
        }

            if ($max == 0) {
                echo 'カートに商品が入っていません。';
                echo '<br />';
                echo '<a href="shop_list.php">商品一覧へ戻る</a>';
                exit();
            }

            $dsn = 'mysql:dbname=shop;host=localhost;port=8889;charset=utf8';
            $user = 'root';
            $password = 'root';
            $dbh = new PDO($dsn, $user, $password);
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            foreach ($cart as $key => $val) {
                $sql = 'SELECT code, name, price, gazou FROM mst_product WHERE code=?';
                $stmt = $dbh->prepare($sql);
                $data[0] = $val;
                $stmt->execute($data);

                $rec = $stmt->fetch(PDO::FETCH_ASSOC);

                $pro_name[] = $rec['name'];
                $pro_price[] = $rec['price'];
                if ($rec['gazou'] == '') {
                    $pro_gazou[] = '';
                } else {
                    $pro_gazou[] = '<img src="../product/gazou/'.$rec['gazou'].'">';
                }
            }
                $dbh = null;


        } catch(Exception $e) {
            echo 'ただいま障害により大変ご迷惑お掛けしております。';
            exit();
        }

?>
<form method="post" action="kazu_change.php">
    <table class="table table-bordered">
        <thead>
            <tr>
            <th scope="col">商品</th>
            <th scope="col">商品画像</th>
            <th scope="col">価格</th>
            <th scope="col">数量</th>
            <th scope="col">小計</th>
            <th scope="col">削除</th>
            </tr>
        </thead>
        <tbody>
            <?php for ($i = 0;$i < $max;$i++) { ?>
            <tr>
            <td><?php echo $pro_name[$i]; ?></td>
            <td><?php echo $pro_gazou[$i]; ?></td>
            <td><?php echo $pro_price[$i].'円'; ?></td>
            <td><input type="text" name="kazu<?php echo $i; ?>" value="<?php echo $kazu[$i];?>"></td>
            <td><?php echo $pro_price[$i] * $kazu[$i]; ?>円</td>
            <td><input type="checkbox" name="sakujo<?php echo $i; ?>"></td>
            </tr>
            <?php } ?>
    </table>
        </tbody>
    <input type="hidden" name="max" value="<?php echo $max; ?>">
    <input type="submit" value="数量変更"><br />
    <input type="button" onclick="history.back()" value="戻る">
</form>
<a class="btn btn-primary my-2" href="shop_form.html">ご購入手続きへ進む</a><br />

</body>
</html>