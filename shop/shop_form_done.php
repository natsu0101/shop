<?php
    session_start();
    session_regenerate_id(true);
?>

<!DOCTYPE html>
<html lang="jp">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EC試作サイト</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>

<body>
<h2 class="my-2">Shop Portfolio</h2>
    <div class="container">
        <h3>ご注文完了</h3>
<?php
try {

    require_once('../common/common.php');

    $post = sanitize($_POST);
    $onamae = $post['onamae'];
    $email =  $post['email'];
    $postal1 = $post['postal1'];
    $postal2 = $post['postal2'];
    $address = $post['address'];
    $tel = $post['tel'];

    echo $onamae.'様<br />';
    echo 'ご注文ありがとうございました。<br />';
    echo $email.'にメールをお送り致しましたのでご確認ください。<br />';
    echo '商品は下記の住所に発送させて頂きます。<br />';
    echo $postal1.'-'.$postal2.'<br />';
    echo $address.'<br />';
    echo $tel.'<br />';
    
    $honbun = '';
    $honbun .= $onamae."様\n\nこの度はご注文ありがとうございました。";
    $honbun .= "\n"; 
    $honbun .= "ご注文商品\n";
    $honbun .= "-------------------\n";

    $cart = $_SESSION['cart'];
    $kazu = $_SESSION['kazu'];
    $max = count($cart);

    $dsn = 'mysql:dbname=shop;host=localhost;port=8889;charset=utf8';
    $user = 'root';
    $password = 'root';
    $dbh = new PDO($dsn, $user, $password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    for ($i = 0;$i < $max;$i++) {
        $sql = 'SELECT name, price FROM mst_product WHERE code=?';
        $stmt = $dbh->prepare($sql);
        $data[0] = $cart[$i];
        $stmt->execute($data);

        $rec = $stmt->fetch(PDO::FETCH_ASSOC);

        $name = $rec['name'];
        $price = $rec['price'];
        $kakaku[] = $price;
        $suryo = $kazu[$i];
        $shokei = $price * $suryo;

        $honbun .= $name.' ';
        $honbun .= $price.'円';
        $honbun .= $suryo.'個=';
        $honbun .= $shokei."円\n";
    }
    $sql = 'LOCK TABLES dat_sales WRITE,dat_sales_product WRITE';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    $sql = 'INSERT INTO dat_sales(code_member, name, email, postal1, postal2, address, tel)
    VALUES(?,?,?,?,?,?,?)';
    $stmt = $dbh->prepare($sql);
    $data = array();
    $data[] = 0;
    $data[] = $onamae;
    $data[] = $email;
    $data[] = $postal1;
    $data[] = $postal2;
    $data[] = $address;
    $data[] = $tel;
    $stmt->execute($data); 

    $sql = 'SELECT LAST_INSERT_ID()';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rec =  $stmt->fetch(PDO::FETCH_ASSOC);
    $lastcode = $rec['LAST_INSERT_ID()'];

    for ($i = 0;$i < $max;$i++) {
        $sql = 'INSERT INTO dat_sales_product(code_sales, code_product, price, quantity)
        VALUES(?,?,?,?)';
        $stmt = $dbh->prepare($sql);
        $data = array();
        $data[] = $lastcode;
        $data[] = $cart[$i];
        $data[] = $kakaku[$i];
        $data[] = $kazu[$i];
        $stmt->execute($data);
    }
        
    $sql = 'UNLOCK TABLES';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();

    $dbh = null;

    $honbun .= "送料は無料です。\n";
    $honbun .= "-------------------\n";
    $honbun .= "\n";
    $honbun .= "代金は下記口座にお振込みください。\n";
    $honbun .= "○○銀行 ××支店 普通口座 1234567\n"; 
    $honbun .= "入金確認が取れ次第､梱包､発送させていただきます。\n";
    $honbun .= "\n";
    $honbun .= "□□□□□□□□□□□□□□□□□□□\n";
    $honbun .= "～△△shop～\n";
    $honbun .= "\n";
    $honbun .= "北海道●●市●●区 123-4\n";
    $honbun .= "電話　0120-0000-××××\n";
    $honbun .= "メール info@×××.co.jp\n";
    $honbun .= "□□□□□□□□□□□□□□□□□□□\n";

    $title = 'ご注文ありがとうございます';
    $header = 'Form: info@×××.co.jp';
    $honbun = 'html_entity_decode($honbun, ENT_QUOTES, UTF-8)';
    mb_language('Japanese');
    mb_internal_encoding('UTF-8');
    mb_send_mail($email, $title, $honbun, $header);

        
    $title = 'お客様からご注文がありました。';
    $header = 'From: '.$email;
    $honbun = html_entity_decode($honbun, ENT_QUOTES, 'UTF-8');
    mb_language('Japanese');
    mb_internal_encoding('UTF-8');
    mb_send_mail('info@×××.co.jp', $title, $honbun, $header);

    session_destroy();

} catch(Exception $e) {
    echo 'ただいま障害により大変ご迷惑お掛けしております。';
     exit();
}
?>
        <br />
        <a href="shop_list.php">商品一覧へ</a>
    </div>
</body>
</html>