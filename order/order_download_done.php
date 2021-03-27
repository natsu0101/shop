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
        <?php echo $_SESSION['staff_name']; ?>さんログイン中
        <?php endif; ?>
        <br />
<?php
try {
    $year = $_POST['year'];
    $month = $_POST['month'];
    $day = $_POST['day'];
    
    $dsn = 'mysql:dbname=shop;host=localhost;port=8889;charset=utf8';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql ='SELECT 
        dat_sales.code,
        dat_sales.date,
        dat_sales.code_member,
        dat_sales.name AS dat_sales_name,
        dat_sales.email,
        dat_sales.postal1,
        dat_sales.postal2,
        dat_sales.address,
        dat_sales.tel,
        dat_sales_product.code_product,
        mst_product.name AS mst_product_name,
        dat_sales_product.price,
        dat_sales_product.quantity
    FROM 
        dat_sales, dat_sales_product, mst_product
    WHERE
        dat_sales.code = dat_sales_product.code_sales
        AND dat_sales_product.code_sales = mst_product.code
        AND substr(dat_sales.date,1,4)=?
        AND substr(dat_sales.date,6,2)=?
        AND substr(dat_sales.date,9,2)=?';

        $stmt = $dbh->prepare($sql);
        $data[] = $year;
        $data[] = $month;
        $data[] = $day;
        $stmt->execute($data);

    $dbh = null;

    $csv = '注文コード, 注文日時, 会員番号, お名前, メール, 郵便番号, 住所, TEL, 商品コード, 商品名, 価格, 数量';
    $csv .= "\n";
    while(true) {
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($rec == false) {
            break;
        }
        $csv .= $rec['code'];
        $csv .= ',';
        $csv .= $rec['date'];
        $csv .= ',';
        $csv .= $rec['code_member'];
        $csv .= ',';
        $csv .= $rec['dat_sales_name'];
        $csv .= ',';
        $csv .=$rec['email'];
        $csv .= ',';
        $csv .=$rec['postal1'].'-'.$rec['postal2'];
        $csv .= ',';
        $csv .=$rec['address'];
        $csv .= ',';
        $csv .=$rec['tel'];
        $csv .= ',';
        $csv .=$rec['code_product'];
        $csv .= ',';
        $csv .=$rec['mst_product_name'];
        $csv .= ',';
        $csv .=$rec['price'];
        $csv .= ',';
        $csv .=$rec['quantity'];
        $csv .="\n";
    }
    //echo nl2br($csv);
    $file = fopen('./chumon.csv','w');
    $csv = mb_convert_encoding($csv, 'SjiS', 'UTF-8');
    fputs($file, $csv);
    fclose($file);
    
} catch(Exception $e) {
    echo 'ただいま障害により大変ご迷惑お掛けしております。';
    exit();
}
?>
        <a class="btn btn-primary my-2" href="chumon.csv">注文データのダウンロード</a>
        <br />
        <a class="btn btn-primary my-2" href="order_download">日時選択へ</a>
        <br />
        <a href="../staff_login/staff_top.php">ショップ管理トップメニューへ</a>
    </div>
</body>
</html>