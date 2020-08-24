<?php

$host     = 'localhost';
$username = 'codecamp35870';        // MySQLのユーザ名（マイページのアカウント情報を参照）
$password = 'codecamp35870';       // MySQLのパスワード（マイページのアカウント情報を参照）
$dbname   = 'codecamp35870';   // MySQLのDB名(このコースではMySQLのユーザ名と同じです）
$charset  = 'utf8';   // データベースの文字コード
 
// MySQL用のDSN文字列
$dsn = 'mysql:dbname='.$dbname.';host='.$host.';charset='.$charset;
$err_msg = array();
$data = array();
$date = date('Y-m-d H:i:s'); 

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    //ユーザー名チェック
    //6文字以上の半角英数字かチェック
    $name = $_POST['user_name'];
    $pass = $_POST['password'];
    $pattern ='/^([a-zA-Z0-9]{6,})$/';
    if (preg_match($pattern, $name) === 0 ) {
        $err_msg[] = 'ユーザー名は6文字以上の半角英数字で入力してください。';
    }
    if (preg_match($pattern, $pass) === 0 ) {
        $err_msg[] = 'パスワードは6文字以上の半角英数字で入力してください。';
    }
    if(isset($_POST['fav'])!==TRUE){
        $err_msg[] = 'アンケートに回答してください。';
    }
}
    
try {
// データベースに接続
    $dbh = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    //重複するユーザーIDがないかをチェック
    try {
        $sql = 'SELECT
                COUNT(user_name)
                FROM ec_user WHERE user_name = ?';
        $stmt = $dbh->prepare($sql);
        $stmt->bindValue(1,$_POST['user_name'],PDO::PARAM_STR);
        $stmt->execute();
        $count = (int)$stmt->fetchColumn();
        if($count>=1){
            $err_msg[]='このユーザーIDはすでに使われています。';
        }
    } catch (PDOException $e) {
        echo 'データの取得に失敗しました。理由：'.$e->getMessage();
    }
    //全てのエラーがなかった場合データを登録
    if((count($err_msg))===0){
          try {
              //データベースににユーザー情報を入力
              $sql ='INSERT INTO ec_user(user_name,password,genre,create_datetime,update_datetime) VALUES(?,?,?,?,?)';
              $stmt = $dbh->prepare($sql);
              $stmt->bindValue(1,$_POST['user_name'],PDO::PARAM_STR);
              $stmt->bindValue(2,$_POST['password'],PDO::PARAM_INT);
              $stmt->bindValue(3,$_POST['fav'],PDO::PARAM_INT);
              $stmt->bindValue(4,$date,PDO::PARAM_STR);
              $stmt->bindValue(5,$date,PDO::PARAM_STR);
              $stmt->execute();
          } catch (PDOException $e) {
              print 'データの登録ができませんでした。理由：'.$e->getMessage();
          }
    }          
//DB接続エラー
} catch (PDOException $e) {
    $err_msg['db_connect'] = 'DBエラー：'.$e->getMessage();
}
?>
<!DOCTYPE html>
<html lang=ja>
    <head>
        <meta charset-"UTF-8">
        <title>OnlineGameShop</title>
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <link rel="stylesheet" href="login.css">
        <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <script src="//code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="//stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </head>
    <body>
        <div id="wrapper">
            <!--ヘッダー-->
            <header id="header">
                    <!--サイトロゴ-->
                    <div id="logo"><a href =top.php><img src="logo.png" alt ="ロゴ"></a></div>
            </header>
            <!--メイン-->
            <main id ="main">
                <h4>SING UP</h4>
                <div class="form">
                    <?php
                    if(count($err_msg)!==0){
                        foreach($err_msg as $msg){?>
                        <p style="color:red;"><?php print $msg;?></p>
                        <?php } ?>
                        <a href="register.php"><button class="btn btn-success">Back to Sign Up</button></a>
                    <?php }else { ?>
                        <p style="color:#009900;">Sign Up Completed !</p>
                        <p>USERNAME：<?php print $name; ?></p>
                        <p>PASSWORD：<?php print $pass; ?></p>
                        <a href="top.php"><button class="btn btn-success">TOP</button></a>
                    <?php } ?>
                </div>
            </main>
            <!--フッター-->
            <footer id="footer">
                <p>OnlineGameShop</p>
                <p><a href=top.php class="link-font">Top</a></p>
                <small>Copyright ©2020 by Masakazu Toyoyama. All Rights Reserved.</small>
            </footer>
        </div>
    </body>
</html>