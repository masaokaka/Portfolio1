<?php
//商品購入後ページ

//ログイン中のアクセスかをチェック
session_start();
if(isset($_SESSION['user_id'])!==TRUE){
header('location: login.php');
}else{
    $user_id = $_SESSION['user_id'];
}

$host     = 'localhost';
$username = 'codecamp35870';        // MySQLのユーザ名（マイページのアカウント情報を参照）
$password = 'codecamp35870';       // MySQLのパスワード（マイページのアカウント情報を参照）
$dbname   = 'codecamp35870';   // MySQLのDB名(このコースではMySQLのユーザ名と同じです）
$charset  = 'utf8';   // データベースの文字コード

// MySQL用のDSN文字列
$dsn = 'mysql:dbname='.$dbname.';host='.$host.';charset='.$charset;
$data = array();
$err_msg = array();
$date = date('Y-m-d H:i:s');
$img_dir  = './img/';
$sum = array();

if($_SERVER['REQUEST_METHOD']==='POST'){
    try {
      // データベースに接続
      $dbh = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
      
      //カートのデータベースから商品情報を取得
        // SQL文を作成
        $sql = 'SELECT
                ec_cart.id,
                ec_cart.user_id,
                ec_cart.soft_id,
                ec_cart.amount,
                soft_master.soft,
                soft_master.genre,
                soft_master.price,
                soft_master.img
                FROM ec_cart INNER JOIN soft_master
                ON ec_cart.soft_id = soft_master.soft_id
                WHERE ec_cart.user_id ='.$user_id;
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $data = $stmt->fetchAll();
      //在庫データから購入分を引き去る
      try {
        $dbh->beginTransaction();
            //複数ソフト購入するのでループで回す
            //取得したストックデータから購入する量を引いてデータベースを更新
        foreach($data as $value){
            $sql ='UPDATE soft_stock SET stock=stock-?,update_datetime=? WHERE soft_id=?';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(1,$value['amount'],PDO::PARAM_INT);
            $stmt->bindValue(2,$date,PDO::PARAM_STR);
            $stmt->bindValue(3,$value['soft_id'],PDO::PARAM_INT);
            $stmt->execute();
        }
        //購入後カートのデータを削除
            $sql ='DELETE FROM ec_cart
                   WHERE user_id=?';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(1,$user_id,PDO::PARAM_INT);
            $stmt->execute();
        $dbh->commit();
      }catch (PDOException $e) {
            $dbh->rollback();
            throw $e;
      }
    }catch (PDOException $e) {
        $err_msg['db_connect'] = 'DBエラー：'.$e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang=ja>
    <head>
        <meta charset-"UTF-8">
        <title>OnlineGameShop</title>
        <link rel="stylesheet" href="cart.css">
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
                <!--ログイン済であれば名前とカート、ログアウトボタンを表示-->
                <?php
                if(isset($_SESSION['user_name'])===TRUE ){ 
                    $user_name = $_SESSION['user_name'];?>
                    <!--ヘッダーメニュー-->
                    <nav class = "header-menu">
                        <ul>
                            <li>Welcome <?php print $user_name; ?></li>
                            <?php if($user_name === 'admin'){ ?>
                            <li><a href =tool.php><button class="btn btn-outline-primary">ADMIN</button></a></li>
                            <?php } else { ?>
                            <li><a href =cart.php><img class="cart" src="cart.png" alt ="カート"></a></li>
                            <?php }  ?>
                            <li><a href =logout.php><button class="btn btn-outline-dark">LOGOUT</button></a></li>
                        </ul>
                    </nav>
                    <?php } else { ?>
                    <nav class = "header-menu">
                        <ul>
                            <li><a href =register.php><button class="btn btn-outline-dark">SIGNUP</button></a></li>
                            <li><a href =login.php><button class="btn btn-outline-dark">LOGIN</button></a></li>
                        </ul>
                    </nav>
                    <?php } ?>
            </header>
            <main id="main">
                <h4>Thank you for buying</h4>
                <!--合計金額表示-->
                <div id="total">
                    <!--カート内商品の合計金額計算-->
                    <?php foreach ($data as $value)  { 
                     $sum[] = $value['price']*$value['amount']; } ?>
                    <h5>TOTAL</h5>
                    <h5>¥<?php print array_sum($sum);?></h5>
                </div>
                
                <div class="cart_contents">
                    <p>Enjoy the Games!</p>
                     <!--購入した商品を表示-->
                    <?php foreach ($data as $value)  { ?>
                        <!--商品画像-->
                        <div><img width="120" height="180" src="<?php print $img_dir . $value['img']; ?>"></div>
                        <!--商品名-->
                        <div><?php print htmlspecialchars($value['soft']); ?></div>
                        <div><?php print $value['price'].'円'; ?></div>
                    <?php } ?>
                </div>
            </main>
        </div>
        <footer>
            <p>OnlineGameShop</p>
            <small>Copyright ©2020 by Masakazu Toyoyama. All Rights Reserved.</small>
        </footer>
        </body>
</html>