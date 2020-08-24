<?php
//ログイン用ページ

$data = array();
$err_msg = array();
$host     = 'localhost';
$username = 'codecamp35870';        // MySQLのユーザ名（マイページのアカウント情報を参照）
$password = 'codecamp35870';       // MySQLのパスワード（マイページのアカウント情報を参照）
$dbname   = 'codecamp35870';   // MySQLのDB名(このコースではMySQLのユーザ名と同じです）
$charset  = 'utf8';   // データベースの文字コード

// MySQL用のDSN文字列
$dsn = 'mysql:dbname='.$dbname.';host='.$host.';charset='.$charset;

//ログインデータが間違いでないかをチェックする
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $ec_username  = $_POST['username'];  
    $ec_password = $_POST['password'];
    if ($ec_username === '') {
        $err_msg[] = 'ユーザー名は入力必須です。';
    }
    if ($ec_password === '') {
        $err_msg[] = 'パスワードは入力必須です。';
    }
    //入力内容にエラーがなかった時、データベースでデータ有無の確認
    if(count($err_msg) === 0){
      //特定のユーザー名とパスワード入力で管理画面へ移動
      if($ec_username === 'admin' && $ec_password === 'admin'){
          session_start();
          $_SESSION['user_name'] = $ec_username;
          header('location: ./tool.php');
          exit;
      } else {
        try {
            //データベース接続
            $dbh = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            try {
                $sql = 'SELECT
                        user_id,
                        user_name,
                        password
                        FROM ec_user WHERE user_name = ?';
                $stmt = $dbh->prepare($sql);
                $stmt->bindValue(1,$ec_username,PDO::PARAM_STR);
                $stmt->execute();
                $data = $stmt->fetch();
                //取得したパスワードに間違いがなければセッションにユーザー名を入れてトップページへ
                if($data['password'] === $ec_password){
                    session_start();
                    $_SESSION['user_name'] = $ec_username;
                    $_SESSION['user_id'] = $data['user_id'];
                    header('location: ./top.php');
                    exit;
                //取得したパスワードが間違っていた場合
                } else{
                    $err_msg[] = 'ユーザー名あるいはパスワードが間違っています';
                }
            } catch (PDOException $e) {
                echo 'データの取得に失敗しました。理由：'.$e->getMessage();
            }
        //データベースへのアクセス失敗の場合
        } catch (PDOException $e) {
            $err_msg['db_connect'] = 'DBエラー：'.$e->getMessage();
        }
    }
  }
}
?>
<!DOCTYPE html>
<html lang=ja>
    <head>
        <meta charset-"UTF-8">
        <title>OnlineGameShop</title>
        <link rel="stylesheet" href="login.css">
        <meta name="viewport" content="width=device-width,initial-scale=1">
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
            <main id="main">
                <!--ログイン用フォーム-->
                    <h4>LOGIN</h4>
                    <div class="form">
                        <form action="./login.php" method="post">
                            <p>USERNAME<input class="text_box" type="text" name="username"></p>
                            <p>PASSWORD<input class="text_box" type="text" name="password"></p>
                            <p><input type="submit" class="btn btn-success" value="LOGIN"></p>
                            <?php foreach($err_msg as $err){?>
                            <p><?php print $err; ?></p>
                            <?php } ?>
                        </form>
                        <p>Not Sign Up yet ?</p>
                        <p><a href =register.php><button class="btn btn-success">SIGNUP</button></a></p>
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