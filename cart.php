<?php
//商品カートページ
//ログインしていなかった場合はログインページへ遷移
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

try {
      // データベースに接続
      $dbh = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
      $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    //削除データもしくはカート内の商品数を変更するポストデータがきた場合
    if($_SERVER['REQUEST_METHOD'] === 'POST'){
        $form_type = $_POST['form_type'];
        
        //カートの商品の削除指示の時
        if($form_type === 'delete'){
            $sql ='DELETE FROM ec_cart
                   WHERE id=?';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(1,$_POST['cart_id'],PDO::PARAM_INT);
            $stmt->execute();
            print 'Item deleted';
        
        //カートの商品追加指示の場合
        }else if($form_type === 'update'){
            //入力内容のチェック
            if($_POST['amount']!==0){
                //半角数字かチェック
                $subject = $_POST['amount'];
                $pattern ='/^([1-9]\d*)$/';
                if (preg_match($pattern, $subject) === 0 ) {
                    $err_msg[] = 'Enter numbers';
                }
            }else if($_POST['amount'] === 0){
                $err_msg[] = 'Enter numbers';
            }
            if(count($err_msg)===0){
                $sql ='UPDATE ec_cart SET amount=?,update_datetime=? WHERE id=?';
                $stmt = $dbh->prepare($sql);
                $stmt->bindValue(1,$_POST['amount'],PDO::PARAM_INT);
                $stmt->bindValue(2,$date,PDO::PARAM_STR);
                $stmt->bindValue(3,$_POST['cart_id'],PDO::PARAM_INT);
                $stmt->execute();
                print 'Amount changed';
            }
        }
    }
  //カート内に商品を表示
    try {
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
        $rows = $stmt->fetchAll();
        foreach ($rows as $row) {
          $data[] = $row;
        }
        if(count($data)===0){
            $err_msg[] = 'No items in this cart';
        }
    } catch (PDOException $e) {
      echo 'データの取得に失敗しました。理由：'.$e->getMessage();
    }
 }catch (PDOException $e) {
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
            <!--ヘッダー-->
            <header id="header">
                <!--サイトロゴ-->
                <div id="logo"><a href =top.php><img src="logo.png" alt ="ロゴ"></a></div>
                <!--ログイン済であれば名前とカート、ログアウトボタンを表示-->
                <?php
                if(isset($_SESSION['user_name'])===TRUE){ 
                    $user_name = $_SESSION['user_name'];
                ?>
                <!--ヘッダーメニュー-->
                <nav class = "header-menu">
                    <ul>
                        <li>Welcome <?php print $user_name; ?></li>
                        <?php if($user_name === 'admin'){ ?>
                        <li><a href =tool.php><button class="btn btn-outline-primary">ADMIN</button></a></li>
                        <?php } ?>
                        <li><a href =logout.php><button class="btn btn-outline-dark">LOGOUT</button></a></li>
                    </ul>
                </nav>
                <?php }?>
            </header>
            
            <!--メイン-->
            <main id="main">
                <h4>CART</h4>
                <!--エラー表示-->
                <?php 
                    foreach ($err_msg as $msg){ ?>
                        <h5><?php print $msg; ?></h5>
                <?php } ?>
                <!--エラーがなければカートの中身を表示-->
                <?php if(count($err_msg)===0){ ?>
                    <!--合計金額表示-->
                    <div id="total">
                        <!--カート内商品の合計金額計算-->
                        <?php foreach ($data as $value)  { 
                         $sum[] = $value['price']*$value['amount']; } ?>
                        <h5>TOTAL</h5>
                        <h5>¥<?php print array_sum($sum);?></h5>
                        <!--購入ボタン-->
                        <h5>
                            <form action="./purchase.php" method="post">
                                <input type="submit" value="BUY" class="btn btn-success">
                                <input type="hidden" name="user_id" value="$id">
                            </form>
                        </h5>
                    </div>
                    <!--カート内に商品表示-->
                    <?php foreach ($data as $value)  { ?>
                        <div class="cart_contents">
                            <!--商品画像-->
                            <div><img class="soft_pic" src="<?php print $img_dir . $value['img']; ?>"></div>
                            <!--商品名-->
                            <div>
                                <div class="soft_name"><?php print htmlspecialchars($value['soft']); ?></div>
                                <div class="price">¥<?php print $value['price'];?></div>
                            </div>
                            <!--カート内の商品数を変更するフォーム-->
                            <div class="form">
                                    <form class="form_pos" method="post" action="./cart.php">
                                        <input class="text_box"　type="text" name="amount" value="<?php print $value['amount']; ?>">
                                        <input class="btn btn-success" type="submit" value="CHANGE">
                                        <input type="hidden" name="cart_id" value="<?php print $value['id']; ?>">
                                        <input type="hidden" name="form_type" value="update">
                                    </form>
                                  <!--カートから商品を削除するフォーム-->
                                    <form class="form_pos" method="post"　action="./cart.php">
                                        <input class="btn btn-danger" type="submit" value="DELETE">
                                        <!--カートの購入IDを送信-->
                                        <input type="hidden" name="cart_id" value="<?php print $value['id']; ?>">
                                        <input type="hidden" name="form_type" value="delete">
                                    </form>
                            </div>
                        </div>
                    <?php } ?>
                <?php }?>
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