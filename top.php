<?php
//トップページ（商品一覧ページ）

$host     = 'localhost';
$username = 'codecamp35870';        // MySQLのユーザ名（マイページのアカウント情報を参照）
$password = 'codecamp35870';       // MySQLのパスワード（マイページのアカウント情報を参照）
$dbname   = 'codecamp35870';   // MySQLのDB名(このコースではMySQLのユーザ名と同じです）
$charset  = 'utf8';   // データベースの文字コード

// MySQL用のDSN文字列
$dsn = 'mysql:dbname='.$dbname.';host='.$host.';charset='.$charset;
$data1 = array();
$search = array();
$favorites = array();
$img_dir  = './img/';
$login = array();
$err_msg = array();
$date = date('Y-m-d H:i:s');

try {
  // データベースに接続
  $dbh = new PDO($dsn, $username, $password, array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'));
  $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
  try {
    // ゲーム一覧表示用
    $sql = 'SELECT
            soft_master.soft_id,
            soft_master.soft,
            soft_master.genre,
            soft_master.console,
            soft_master.price,
            soft_master.img,
            soft_master.status,
            soft_master.create_datetime,
            soft_stock.stock
            FROM soft_master INNER JOIN soft_stock
            ON soft_master.soft_id = soft_stock.soft_id';
    $stmt = $dbh->prepare($sql);
    $stmt->execute();
    $rows = $stmt->fetchAll();
    foreach ($rows as $row) {
      $data1[] = $row;
    }
  } catch (PDOException $e) {
      echo 'データの取得に失敗しました。理由：'.$e->getMessage();
  }
  
  // おすすめゲーム一覧表示用
      //ログイン中のユーザーのお気に入りジャンルを取得
      try {
        session_start();
        if(isset($_SESSION['user_id'])===TRUE){
            $sql = 'SELECT
                    genre
                    FROM ec_user
                    WHERE user_id = ?';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(1,$_SESSION['user_id'],PDO::PARAM_INT);
            $stmt->execute();
            $genre = $stmt->fetch();
            //お気に入りジャンルに該当するソフトを取得
              if(isset($genre)!==FALSE){
                  $sql = 'SELECT
                    soft_master.soft_id,
                    soft_master.soft,
                    soft_master.genre,
                    soft_master.console,
                    soft_master.price,
                    soft_master.img,
                    soft_master.status,
                    soft_master.create_datetime,
                    soft_stock.stock
                    FROM soft_master INNER JOIN soft_stock
                    ON soft_master.soft_id = soft_stock.soft_id
                    WHERE soft_master.genre=?';
                    $stmt = $dbh->prepare($sql);
                    $stmt->bindvalue(1,$genre[0],PDO::PARAM_INT);
                    $stmt->execute();
                    $favorites = $stmt->fetchAll();
                }
        }
      } catch (PDOException $e) {
          echo 'データの取得に失敗しました。理由：'.$e->getMessage();
      }

//ポストデータの処理を行う
  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $form_type = $_POST['form_type'];
    //カートに商品が追加された場合
    if($form_type === 'add'){
        $user_id = $_POST['user'];
        $soft_id = $_POST['soft'];
      //すでに同じ商品がカートに入っていないかをチェック
      try {
            $sql = 'SELECT
                    ec_cart.user_id,
                    ec_cart.soft_id,
                    amount
                    FROM ec_cart WHERE user_id=? AND soft_id=?';
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(1,$user_id,PDO::PARAM_STR);
            $stmt->bindValue(2,$soft_id,PDO::PARAM_STR);
            $stmt->execute();
            $add = $stmt->fetch();
    //すでにカートに同じ商品が入っていた場合はデータベースに個数を追加
            if($add !== FALSE){
                $add['amount']++;
                $sql ='UPDATE ec_cart SET amount=?,update_datetime=? WHERE user_id = ? AND soft_id = ?';
                $stmt = $dbh->prepare($sql);
                $stmt->bindValue(1,$add['amount'],PDO::PARAM_INT);
                $stmt->bindValue(2,$date,PDO::PARAM_STR);
                $stmt->bindValue(3,$user_id,PDO::PARAM_INT);
                $stmt->bindValue(4,$soft_id,PDO::PARAM_INT);
                $stmt->execute();
                print 'Added to cart';
            } else {
    // カートに同じ商品が入っていなければデータベースに新規に商品を追加
              $sql ='INSERT INTO ec_cart(user_id,soft_id,amount,create_datetime,update_datetime) VALUES(?,?,?,?,?)';
              $stmt = $dbh->prepare($sql);
              $stmt->bindValue(1,$user_id,PDO::PARAM_INT);
              $stmt->bindValue(2,$soft_id,PDO::PARAM_INT);
              $stmt->bindValue(3,1,PDO::PARAM_INT);
              $stmt->bindValue(4,$date,PDO::PARAM_STR);
              $stmt->bindValue(5,$date,PDO::PARAM_STR);
              $stmt->execute();
              print 'Added to cart';
            }
      } catch (PDOException $e) {
      echo 'データの取得に失敗しました。理由：'.$e->getMessage();
      }
    }
    //検索フォーム
    if($form_type === 'search'){
        $sql = 'SELECT 
                soft_master.soft_id,
                soft_master.soft,
                soft_master.genre,
                soft_master.console,
                soft_master.price,
                soft_master.img,
                soft_master.status,
                soft_master.create_datetime,
                soft_stock.stock
                FROM soft_master
                INNER JOIN soft_stock
                ON soft_master.soft_id = soft_stock.soft_id';
        $where = '';
        //フリーワード検索
        if(isset($_POST['freeword']) === TRUE && $_POST['freeword'] !== '' ){ //検索ワードの入力有無確認
            $where = ' WHERE soft LIKE \'%'. $_POST['freeword'].'%\'';
        }
        //ジャンル検索
        if(isset($_POST['genre']) === TRUE && $_POST['genre'] !=='99' ){
            if(empty($where)){
                $where = ' WHERE ';
            }else{
                $where .= ' AND ';
            }
            $where .= 'genre ='.$_POST['genre'];
        }
        //コンソール検索
        if(isset($_POST['console']) === TRUE){
            if(empty($where)){
                $where = ' WHERE ';
            }else{
                $where .= ' AND ';
            }
            $where .= 'console IN ('.implode(',',$_POST['console']).')';
        }
        $sql .= $where;
        $stmt = $dbh->prepare($sql);
        $stmt->execute();
        $search = $stmt->fetchAll();
    }
}
//データベースへのアクセス失敗の場合
} catch (PDOException $e) {
  $err_msg['db_connect'] = 'DBエラー：'.$e->getMessage();
}
?>
<!DOCTYPE html>
<html lang=ja>
    <head>
        <meta charset-"UTF-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <title>OnlineGameShop</title>
        <link rel="stylesheet" href="top.css">
        <script src="top.js"></script>
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
                        $user_name = $_SESSION['user_name'];
                        ?>
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
        <!--メイン-->
            <main id="main">
                <!--動画埋め込み-->
                <div>
                    <h4>WATCH</h4>
                    <div class="video">
                    <iframe width="560" height="315" src="https://www.youtube.com/embed/sMOkETmSj4s?controls=0&amp;start=8145" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
                <!--検索用フォーム-->
                <!--仕切りの棒用-->
                <div class="contents"></div>
                
                <h4>SEARCH</h4>
                        <form action="./top.php" method="post">
                            <div class="search-ptn">
                                <div>FREEWORD<input class="text_box" type="text" name="freeword"></div>
                                <div>GENRE 
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
                                <div>CONSOLE
                                    <ul class="text_box">
                                        <li><input type="checkbox" name="console[]" value=0>PS4</li>
                                        <li><input type="checkbox" name="console[]" value=1>Nintedo switch</li>
                                        <li><input type="checkbox" name="console[]" value=2>Xbox</li>
                                    <ul>
                                </div>
                                <input type="hidden" name="form_type" value="search">
                                <div><input type="submit" value="SEARCH" class="btn btn-success"></div>
                            </div>
                        </form>
                
                <!--もし検索フォームが送信された場合-->
                <?php if(isset($_POST['form_type']) === TRUE && $_POST['form_type'] === 'search'){ ?>
                <div class="contents">
                <!--検索結果を表示-->
                    <h4>RESULT</h4>
                    <div class = "container">
                            <?php foreach($search as $value){ ?> 
                            <!--ゲームソフトのステータスが公開の場合以下を表示-->         
                                <div class="item">
                                <?php if($value['status']===1){ ?>
                                    <div><a href=#><img class="soft_pic" src="<?php print $img_dir . $value['img']; ?>"></a></div>
                                    <div class="soft-name"><a href=# class="link-font"><?php print htmlspecialchars($value['soft']); ?></a></div>
                                    <div>¥<?php print htmlspecialchars($value['price']); ?></div>
                                    <?php
                                    //ユーザーがログインしている場合のみカートを表示
                                    if(isset($_SESSION['user_id'])===TRUE){ 
                                        //売り切れていない場合はカートボタンを表示
                                        if($value['stock']!==0){ ?>
                                            <div>
                                                <form action="./top.php" method="post">
                                                    <input type="submit" value="ADD" class="btn btn-success">
                                                    <input type="hidden" name="soft" value="<?php print $value['soft_id'];?>">
                                                    <input type="hidden" name="user" value="<?php print $_SESSION['user_id'];?>">
                                                    <input type="hidden" name="form_type" value="add" >
                                                </form>
                                            </div>
                                        <?php } else{ ?>
                                            <div><font color="red">SOLDOUT</font></div>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                                </div>
                            <?php } ?>
                    </div>
                </div>
                <?php } ?>
                
                <!--ユーザーがログインしていればおすすめゲームを表示-->
                <?php if((isset($_SESSION['user_id'])===TRUE) && (isset($genre)===TRUE)){ ?>
                    <div class="contents">
                        <h4>RECOMMEND</h4>
                        <div class = "container">
                                <?php foreach($favorites as $value){ ?> 
                                <!--ゲームソフトのステータスが公開の場合以下を表示-->         
                                    <div class="item">
                                    <?php if($value['status']===1){ ?>
                                        <div><a href=#><img class="soft_pic" src="<?php print $img_dir . $value['img']; ?>"></a></div>
                                        <div class="soft-name"><a href=# class="link-font"><?php print htmlspecialchars($value['soft']); ?></a></div>
                                        <div>¥<?php print htmlspecialchars($value['price']); ?></div>
                                            <!--売り切れていない場合はカートボタンを表示-->
                                            <?php if($value['stock']!==0){ ?>
                                                <div>
                                                    <form action="./top.php" method="post">
                                                        <input type="submit" value="ADD" class="btn btn-success">
                                                        <input type="hidden" name="soft" value="<?php print $value['soft_id'];?>">
                                                        <input type="hidden" name="user" value="<?php print $_SESSION['user_id'];?>">
                                                        <input type="hidden" name="form_type" value="add" >
                                                    </form>
                                                </div>
                                            <?php } else{ ?>
                                                <div><font color="red">SOLDOUT</font></div>
                                            <?php } ?>
                                    <?php } ?>
                                    </div>
                                <?php } ?>
                        </div>
                    </div>
                <?php } ?>
                <!--通常表示用のゲーム-->
                <div class="contents">
                    <h4>GAMES</h4>
                    <div class = "container">
                        <?php foreach($data1 as $value){ ?>
                        <!--ゲームソフトのステータスが公開の場合以下を表示-->
                            <div class="item">
                            <?php if($value['status']===1){ ?>
                                <div><a href=#><img class="soft_pic" src="<?php print $img_dir . $value['img']; ?>"></a></div>
                            <!--商品詳細ページに飛ぶようにページ作成する-->
                                <div class="soft-name"><a href=# class="link-font"><?php print htmlspecialchars($value['soft']); ?></a></div>
                                <div>¥<?php print htmlspecialchars($value['price']); ?></div>
                                <?php
                                //ユーザーがログインしている時のみカートボタンを表示
                                if(isset($_SESSION['user_id'])===TRUE){
                                //売り切れていない場合はカートボタンを表示
                                    if($value['stock']!==0){ ?>
                                        <div>
                                            <form action="./top.php" method="post">
                                                <input type="submit" value="ADD" class="btn btn-success">
                                                <input type="hidden" name="soft" value="<?php print $value['soft_id'];?>">
                                                <input type="hidden" name="user" value="<?php print $_SESSION['user_id'];?>">
                                                <input type="hidden" name="form_type" value="add">
                                            </form>
                                        </div>
                                    <?php } else { ?>
                                        <div><font color="red">SOLDOUT</font></div>
                                    <?php } ?>
                                <?php } ?>
                            <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
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