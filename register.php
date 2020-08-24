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
            <main id="main">
                <h4>SIGN UP</h4>
                <div class="form">
                    <form method="post" action="register_complete.php">
                        <h5>1.What kind of games do you often play ?</h5>
                            <div style="text-align: left; padding-left:420px;">
                                <ul>
                                    <li><input type="radio" name="fav" value="0">RPG</li>
                                    <li><input type="radio" name="fav" value="1">Action</li>
                                    <li><input type="radio" name="fav" value="2">Shooting</li>
                                    <li><input type="radio" name="fav" value="3">Sport</li>
                                    <li><input type="radio" name="fav" value="4">Racing</li>
                                    <li><input type="radio" name="fav" value="5">Horror</li>
                                    <li><input type="radio" name="fav" value="6">Simulation</li>
                                </ul>
                            </div>
                        <h5 style="text-align: left; padding-left:400px;">2.Create your account.</h5>
                            <p>USERNAME<input type="text" name="user_name" class="text_box"></p>
                            <p>PASSWORD<input type="text" name="password" class="text_box"></p>
                            <p><input type="submit" class="btn btn-success" value="SIGNUP"></p>
                    </form>
                    <p>If you have already signed up...
                    <a href =login.php><button class="btn btn-success">LOGIN</button></a>
                    </p>
                </div>
            </main>
            <footer id="footer">
                <p>OnlineGameShop</p>
                <p><a href=top.php class="link-font">Top</a></p>
                <small>Copyright ©2020 by Masakazu Toyoyama. All Rights Reserved.</small>
            </footer>
        </div>
    </body>
</html>