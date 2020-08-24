<?php
//ユーザー管理用ページ
//管理者がログインしていなかった場合はログインページへ遷移
session_start();
if($_SESSION['user_name'] === 'admin'){
    $user_name = $_SESSION['user_name'];
}else{
    header('location: login.php');
}

//登録したユーザーの管理を行う「ユーザー情報管理ページ」
$host     = 'localhost';
$username = 'codecamp35870';        // MySQLのユーザ名（マイページのアカウント情報を参照）
$password = 'codecamp35870';       // MySQLのパスワード（マイページのアカウント情報を参照）
$dbname   = 'codecamp35870';   // MySQLのDB名(このコースではMySQLのユーザ名と同じです）
$charset  = 'utf8';   // データベースの文字コード
 
// MySQL用のDSN文字列
$dsn = 'mysql:dbname='.$dbname.';host='.$host.';charset='.$charset;
$data=array();

try {
  // データベースに接続
  $dbh = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    try {
    // SQL文を作成
    $sql = 'SELECT
            ec_user.user_id,
            ec_user.user_name,
            ec_user.password,
            ec_user.genre,
            ec_user.create_datetime
            FROM ec_user';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    foreach ($rows as $row) {
      $data[] = $row;
    }
  } catch (PDOException $e) {
      echo 'データの取得に失敗しました。理由：'.$e->getMessage();
  }
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
        <link rel="stylesheet" href="cart.css">
        <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <script src="//code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="//stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </head>
    <body>
      <div id="wrapper">
        <header id="header">
          <!--サイトロゴ-->
          <div id="logo"><a href =top.php><img src="logo.png" alt ="ロゴ"></a></div>
            <!--ヘッダーメニュー-->
          <nav class = "header-menu">
            <ul>
              <li>Welcome <?php print $user_name; ?></li>
              <li><a href="tool.php"><button class="btn btn-outline-primary">ADMIN</button></a></li>
              <li><a href =logout.php><button class="btn btn-outline-dark">LOGOUT</button></a></li>
            </ul>
          </nav>
        </header>
        <main id="main" style="margin:0 auto;">
            <table border="1" width="960" cellspacing="0" cellpadding="0" bordercolor="#333333">
                <tr style="text-align:center;">
                    <th>ID</th>
                    <th>USERNAME</th>
                    <th>PASSWORD</th>
                    <th>GENRE</th>
                    <th>DATE OF SIGN UP</th>
                </tr>
                <?php foreach ($data as $value)  { ?>
                <tr style="text-align:center;">
                  <td><?php print htmlspecialchars($value['user_id']); ?></td>
                  <td><?php print htmlspecialchars($value['user_name']); ?></td>
                  <td><?php print htmlspecialchars($value['password']); ?></td>
                  <td><?php print htmlspecialchars($value['genre']); ?></td>
                  <td><?php print htmlspecialchars($value['create_datetime']); ?></td>
                </tr>
                <?php } ?>
            </table>
        </main>
        
        <footer id="footer">
          <p>OnlineGameShop</p>
          <p><a href=top.php class="link-font">Top</a></p>
          <small>Copyright ©2020 by Masakazu Toyoyama. All Rights Reserved.</small>
        </footer>
      </div>
    </body>
</html>