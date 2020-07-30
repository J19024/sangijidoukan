<?php require('login-check.php');

    require('../dbconnect.php');
    // エラーを出力させない
    ini_set('display_errors', "Off");
    session_start();
    if (!empty($_POST)) {
        // エラー項目の確認
        if ($_POST["genre_name"] == '') {
            $error["genre_name"] = 'blank';
        }
        
        if(empty($error)){
            $_SESSION["genre_name"] = $_POST["genre_name"];
            //genre_add_check.phpに移動する
            header('Location: genre_add_check.php');
            exit();
        }
    }
    //書き直し処理
    if ($_REQUEST["action"] == "rewrite") {
        $_POST = $_SESSION[["genre_name"]];
        $error["rewrite"] = true;
    }
if(isset($_SESSION['genre_add'])){
    $message = $_SESSION['genre_add'];
        unset($_SESSION['genre_add']);
}
?>
<!DOCTYPE html>
<body>
    <h1>ジャンル追加画面</h1>
    <h2><?php echo $message;?></h2>
    <form action="" method="post" enctype="multipart/form-data">
        <dl>
            <!--ジャンルの登録-->
            <dt>ジャンル名<span>必須</span></dt>
            <dd>
                <input type="varchar" name="genre_name" size="35" maxlength="255" 
                 value="<?php echo htmlspecialchars($_POST["genre_name"], ENT_QUOTES); ?>" 
                />
                <?php if ($error["genre_name"] == "blank"): ?>
                    <p class ="error">*ジャンル名を入力してください。</p>
                <?php endif; ?>
            </dd>
        </dl>
        <div>
            <input type="submit" value="入力内容を確認する">
            <input type="button" value="トップに戻る" onclick="location.href='books.php'">
        </div>
    </form>
