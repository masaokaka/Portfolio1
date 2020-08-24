<?php
//商品管理ページ
//管理者がログインしていなかった場合はログアウト処理ページへ遷移
session_start();
if($_SESSION['user_name'] === 'admin'){
    $user_name = $_SESSION['user_name'];
}else{
    header('location: login.php');
}

$host     = 'localhost';
$username = 'codecamp35870';        // MySQLのユーザ名（マイページのアカウント情報を参照）
$password = 'codecamp35870';       // MySQLのパスワード（マイページのアカウント情報を参照）
$dbname   = 'codecamp35870';   // MySQLのDB名(このコースではMySQLのユーザ名と同じです）
$charset  = 'utf8';   // データベースの文字コード
 
// MySQL用のDSN文字列
$dsn = 'mysql:dbname='.$dbname.';host='.$host.';charset='.$charset;

$data = array();
$date = date('Y-m-d H:i:s'); 
$img_dir  = './img/';    // アップロードした画像ファイルの保存ディレクトリ
$err_msg  = array();     // エラーメッセージ
$new_img_filename = '';   // アップロードした新しい画像ファイル名
$soft_name_len = 0;
$price_len = 0;
$stock_len = 0;
 
//送られてきたフォームごとで対応を変える
$form_type = "";
if ( isset($_POST['form_type']) ) {
  $form_type = $_POST['form_type'];
}
try {
  // データベースに接続
  $dbh = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  
  if($_SERVER['REQUEST_METHOD'] === 'POST'){
        //商品を新たに追加する場合
    if ($form_type === 'insert') {
      $soft_name_len = mb_strlen ($_POST['soft']);
      $price_len = mb_strlen ($_POST['price']);
      $stock_len= mb_strlen ($_POST['stock']);
      $pattern_blank="^(\s|　)+$";  //正規表現のパターン
          //飲料名チェック
        if($soft_name_len ===0 || mb_ereg_match($pattern_blank,$_POST['soft'])){
            $err_msg[] = '名前を入力してください。';
        }
        //ゲーム機がチェックされているかを確認
        if(isset($_POST['console'])!==TRUE){
            $err_msg[] = '対応ゲーム機を選択してください。';
        }
        //値段チェック
        if($price_len!==0){
            //半角数字かチェック
            $subject = $_POST['price'];
            $pattern ='/^([1-9]\d*|0)$/';
            if (preg_match($pattern, $subject) === 0 ) {
                $err_msg[] = '値段は半角数字を入力してください。';
            }
        } else {
            $err_msg[] =  '値段を入力してください。';
        }
        
        //個数チェック
        if($stock_len!==0){
            //半角数字かチェック
            $subject = $_POST['stock'];
            $pattern ='/^([1-9]\d*|0)$/';
            if (preg_match($pattern, $subject) === 0 ) {
                $err_msg[] = '個数は半角数字を入力してください。';
            }
        } else {
            $err_msg[] =  '個数を入力してください。';
        }
       
        //画像ファイルのチェック
          // HTTP POST でファイルがアップロードされたかどうかチェック
        if (is_uploaded_file($_FILES['img']['tmp_name']) === TRUE) {
          // 画像の拡張子を取得
          $extension = pathinfo($_FILES['img']['name'], PATHINFO_EXTENSION);
          // 指定の拡張子であるかどうかチェック
          if ($extension === 'jpeg'|| $extension === 'png' || $extension === 'jpg') {
            // 保存する新しいファイル名の生成（ユニークな値を設定する）
            $new_img_filename = sha1(uniqid(mt_rand(), true)). '.' . $extension;
            // 同名ファイルが存在するかどうかチェック
            if (is_file($img_dir . $new_img_filename) !== TRUE) {
              // アップロードされたファイルを指定ディレクトリに移動して保存
              if (move_uploaded_file($_FILES['img']['tmp_name'], $img_dir . $new_img_filename) !== TRUE) {
                  $err_msg[] = 'ファイルアップロードに失敗しました';
              }
            } else {
              $err_msg[] = 'ファイルアップロードに失敗しました。再度お試しください。';
            }
          } else {
            $err_msg[] = 'ファイル形式が異なります。画像ファイルはJPEGもしくはpngのみ利用可能です。';
          }
        } else {
          $err_msg[] = 'ファイルを選択してください';
        }
        //ステータスが0か1かをチェック
        if($_POST['status']!=='0'){
          if($_POST['status']!=='1'){
            $err_msg[] = '不正な処理です';
          }
        }
        //アップロード内容にエラーがなければデータベースにデータをアップロード
        if((count($err_msg)) === 0){
          $dbh->beginTransaction();
          try {
              //データベースににデータを入力
              $sql ='INSERT INTO soft_master(soft,genre,console,price,img,status,create_datetime,update_datetime) VALUES(?,?,?,?,?,?,?,?)';
              $stmt = $dbh->prepare($sql);
              $stmt->bindValue(1,$_POST['soft'],PDO::PARAM_STR);
              $stmt->bindValue(2,$_POST['genre'],PDO::PARAM_INT);
              $stmt->bindValue(3,$_POST['console'],PDO::PARAM_INT);
              $stmt->bindValue(4,$_POST['price'],PDO::PARAM_INT);
              $stmt->bindValue(5,$new_img_filename,PDO::PARAM_STR);
              $stmt->bindValue(6,$_POST['status'],PDO::PARAM_INT);
              $stmt->bindValue(7,$date,PDO::PARAM_STR);
              $stmt->bindValue(8,$date,PDO::PARAM_STR);
              $stmt->execute();
              $soft_id=$dbh->lastInsertId();
              $sql ='INSERT INTO soft_stock(soft_id,stock,create_datetime,update_datetime) VALUES(?,?,?,?)';
              $stmt = $dbh->prepare($sql);
              $stmt->bindValue(1,$soft_id,PDO::PARAM_INT);
              $stmt->bindValue(2,$_POST['stock'],PDO::PARAM_INT);
              $stmt->bindValue(3,$date,PDO::PARAM_STR);
              $stmt->bindValue(4,$date,PDO::PARAM_STR);
              $stmt->execute();
              // コミット処理
              $dbh->commit();
              print '追加成功';
          } catch (PDOException $e) {
            echo 'データの登録ができませんでした。理由：'.$e->getMessage();
            // ロールバック処理
            $dbh->rollback();
          }
        }
    
    //商品の在庫数のみを更新する場合
    }else if($form_type === 'update') {
        $stock_len= mb_strlen ($_POST['stock']);
      //個数チェック
        if($stock_len!==0){
            //半角数字かチェック
            $subject = $_POST['stock'];
            $pattern ='/^([1-9]\d*|0)$/';
            if (preg_match($pattern, $subject) === 0 ) {
                $err_msg[] = '個数は半角数字を入力してください。';
            } else {
              $sql ='UPDATE soft_stock SET stock=?,update_datetime=? WHERE soft_id=? ';
                $stmt = $dbh->prepare($sql);
                $stmt->bindValue(1,$_POST['stock'],PDO::PARAM_STR);
                $stmt->bindValue(2,$date,PDO::PARAM_STR);
                $stmt->bindValue(3,$_POST['soft_id'],PDO::PARAM_STR);
                $stmt->execute();
                print '在庫数変更成功';
            }
        } else  {
            $err_msg[] =  '個数を入力してください。';
        }
        
    //商品の公開、非公開設定を変更する場合
    }else if($form_type === 'status_update'){
        $sql ='UPDATE soft_master SET status=? WHERE soft_id=? ';
                $stmt = $dbh->prepare($sql);
                $stmt->bindValue(1,$_POST['status'],PDO::PARAM_STR);
                $stmt->bindValue(2,$_POST['soft_id'],PDO::PARAM_STR);
                $stmt->execute();
                print 'ステータス変更成功';
    //商品の削除をする場合
    }else if($form_type === 'delete'){
        $sql ='DELETE soft_master,soft_stock 
              FROM soft_master LEFT OUTER JOIN soft_stock 
              ON soft_master.soft_id = soft_stock.soft_id
              WHERE soft_master.soft_id ='.$_POST['soft_id'];
                $stmt = $dbh->prepare($sql);
                $stmt->execute();
    }
  }
  // アップロードされた各項目（画像、ソフト名、価格、在庫数）の取得
try {
    // SQL文を作成
    $sql = 'SELECT
            soft_master.soft,
            soft_master.genre,
            soft_master.console,
            soft_master.price,
            soft_master.img,
            soft_master.soft_id,
            soft_master.status,
            soft_stock.stock
            FROM soft_master INNER JOIN soft_stock
            ON soft_master.soft_id = soft_stock.soft_id';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    foreach ($rows as $row) {
      $data[] = $row;
    }
  } catch (PDOException $e) {
      echo 'データの取得に失敗しました。理由：'.$e->getMessage();
  }

//データベースへのアクセス失敗の場合
} catch (PDOException $e) {
  $err_msg['db_connect'] = 'DBエラー：'.$e->getMessage();
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset = "UTF-8">
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
                <li><a href="user.php"><button class="btn btn-outline-primary">USERS</button></a></li>
                <li><a href =logout.php><button class="btn btn-outline-dark">LOGOUT</button></a></li>
              </ul>
            </nav>
        </header>
        
        <!--メイン-->
        <main id="main">
          <!--エラーメッセージ-->
          <div>
            <?php foreach($err_msg as $msg){ ?>
              <p><?php print $msg;?></p>
            <?php }?>
          </div>
              <h2>NEW SOFT</h2>
                <!--商品追加用フォーム-->
                <form method="post" enctype="multipart/form-data" class="container">
                    <div style="margin-right:30px;">
                      <div>NAME</div>
                      <div><input type="text" name="soft" class="text_box"></div>
                      <div style="margin-top:20px;">GENRE</div>
                          <select name="genre" class="text_box">
                            <option value=99>SELECT</option>
                            <option value=0>ロールプレイング</option>
                            <option value=1>アクション</option>
                            <option value=2>シューティング</option>
                            <option value=3>スポーツ</option>
                            <option value=4>レーシング</option>
                            <option value=5>ホラー</option>
                            <option value=6>シュミレーション</option>
                          </select>
                    </div>
                    <div style="margin-right:30px;">
                      <div>CONSOLE</div>
                          <input type="checkbox" name="console" value=0>PS4
                          <input type="checkbox" name="console" value=1>Nintedo switch
                          <input type="checkbox" name="console" value=2>Xbox
                      <div style="margin-top:20px;">PRICE</div>
                        <input type="text" name="price" class="text_box">
                    </div>
                    <div>
                      <div>AMOUNT</div>
                        <input type="text" name="stock" class="text_box">
                      <div style="margin-top:20px;"><input type="file" name="img"></div>
                      <div style="margin-top:20px;">STATUS</div>
                        <select name="status">
                          <option value=0>PRIVATE</option>
                          <option value=1>PUBLIC</option>
                        </select>
                      </div>
                    <div style="margin-top:50px; padding-right:30px;">
                      <input type="submit" value="ADD NEW SOFT" class="btn btn-success">
                      <!-- フォームが二種類あるので区別するために隠し属性-->
                      <input type="hidden" name="form_type" value="insert">
                    </div>
                </form>
              <!--商品一覧-->
              <h2 style="margin-top:20px;">INVENTORY LIST</h2>
              <table border="1" cellspacing="0" cellpadding="0" bordercolor="#333333">
                  <tr style="text-align:center;">
                      <th>Soft</th>
                      <th>Name</th>
                      <th>Genre</th>
                      <th>Console</th>
                      <th>Price</th>
                      <th>Stock</th>
                      <th>Status</th>
                      <th>Delete</th>
                  </tr>
                  <?php foreach ($data as $value)  { ?>
                  <tr <?php if($value['status']===0){ print 'class="private"';}?>>
                    <td><img width="120" height="180" src="<?php print $img_dir . $value['img']; ?>"></td>
                    <td style=" margin:5px; text-align:center;"><?php print htmlspecialchars($value['soft']); ?></td>
                    <td style="margin:5px; text-align:center;"><?php print htmlspecialchars($value['genre']); ?></td>
                    <td style="margin:5px; text-align:center;"><?php print htmlspecialchars($value['console']); ?></td>
                    <td style="margin:5px; text-align:center;">¥<?php print $value['price']; ?></td>
                    <!--在庫数変更用フォーム-->
                    <td>
                      <form method="post">
                        <div style="margin:0px 10px;"><input type="text" name="stock" value="<?php print $value['stock']; ?>" class="text_box"></div>
                        <div style="margin-top:20px; text-align:center;"><input type="submit" value="CHANGE" class="btn btn-success"></div>
                        <input type="hidden" name="soft_id" value="<?php print $value['soft_id']?>">
                        <input type="hidden" name="form_type" value="update">
                      </form>
                    </td>
                    <!--公開←→非公開変更用フォーム-->
                    <td>
                      <form method="post" style=" margin:5px; text-align:center;">
                        <?php if($value['status']===0){?>
                        <button type="submit" name="status" value=1 class="btn btn-success">PUBLIC</button>
                        <?php } else if($value['status']===1){?>
                        <button type="submit" name="status" value=0 class="btn btn-dark">PRIVATE</button>
                        <?php } ?>
                        <input type="hidden" name="soft_id" value="<?php print $value['soft_id']?>">
                        <input type="hidden" name="form_type" value="status_update">
                      </form>
                    </td>
                     <!--削除ボタンフォーム-->
                    <td>
                      <form method="post" style=" margin:5px; text-align:center;">
                        <button type="submit" name="delete" class="btn btn-danger">DELETE</button>
                        <input type="hidden" name="soft_id" value="<?php print $value['soft_id']?>">
                        <input type="hidden" name="form_type" value="delete">
                      </form>
                    </td>
                  </tr>
                  <?php } ?>
              </table>
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