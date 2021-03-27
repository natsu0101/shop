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
    <h2 class="my-2">Shop Portfolio</h2>
    <div class="container">
        <h3>お客様情報の入力確認</h3>
<?php
require_once('../common/common.php');

$post = sanitize($_POST);

$onamae = $post['onamae'];
$email = $post['email'];
$postal1 = $post['postal1'];
$postal2 = $post['postal2'];
$address = $post['address'];
$tel = $post['tel'];

$okflg = true;

if ($onamae == '') {
    echo 'お名前が入力されていません。<br /><br />';
    $okflg = false;
} else {
    echo 'お名前<br />';
    echo $onamae;
    echo '<br /><br />';
}
if (preg_match('/\A[\w\-\.]+\@[\w\-\.]+\.([a-z]+)\z/',$email) == 0) {
    echo 'メールアドレスを正確に入力してください。<br /><br />';
    $okflg = false;
} else {
    echo 'メールアドレス<br />';
    echo $email;
    echo '<br /><br />';
}
if (preg_match('/\A[0-9]+\z/', $postal1) == 0) {
    echo '郵便番号は半角数字で入力してください。<br /><br />';
    $okflg = false;
} else {
    echo '郵便番号<br />';
    echo $postal1;
    echo '-';
    echo $postal2
    ;
    echo '<br /><br />';
}
if (preg_match('/\A[0-9]+\z/', $postal2) == 0) {
    echo '郵便番号は半角数字で入力してください。<br /><br />';
    $okflg = false;
}
if ($address == '') {
    echo '住所が入力されていません。';
    $okflg = false;
} else {
    echo '住所<br />';
    echo $address;
    echo '<br /><br />';
}
if (preg_match('/\A\d{2,5}-?\d{2,5}-?\d{4,5}\z/', $tel) == 0) {
    echo '電話番号を正確に入力してください。<br /><br />';
    $okflg = false;
} else {
    echo '電話番号<br />';
    echo $tel;
    echo '<br /><br />';
}

if ($okflg == true) {
    echo '上記内容でよろしければOKボタンを押してください。';
    echo'<form method="post" action="shop_form_done.php">';
    echo '<input type="hidden" name="onamae" value="'.$onamae.'">';
    echo '<input type="hidden" name="email" value="'.$email.'">';
    echo '<input type="hidden" name="postal1" value="'.$postal1.'">';
    echo '<input type="hidden" name="postal2" value="'.$postal2.'">';
    echo '<input type="hidden" name="address" value="'.$address.'">';
    echo '<input type="hidden" name="tel" value="'.$tel.'">';
    echo '<input type="button" onclick="history.back()" value="戻る">';
    echo '<input type="submit" value="OK"><br/>';
    echo '</form>';
} else {
    echo '<form>';
    echo '<input type="button" onclick="history.back()" value="戻る">';
    echo '</form>';
}
?>
</div>
</body>
</html>