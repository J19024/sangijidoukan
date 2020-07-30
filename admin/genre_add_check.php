<?php require('login-check.php');

    // エラーを出力させない
    ini_set('display_errors', "Off");
    
    session_start();
    require('../dbconnect.php');
    //データが送られているか確認
    if(!isset($_SESSION['genre_name'])) {
        header('Location: genre_add.php');
        exit();
    }
    //DBに値を格納する
    if (!empty($_POST)){
        $statement = $db->prepare("INSERT INTO genre SET genre_name=?, created=NOW(), updated=NOW()");
        $statement->execute(array(
            $_SESSION['genre_name']
        ));
        $_SESSION['genre_add'] = $_SESSION['genre_name']."を追加しました。";
        unset($_SESSION['genre_name']);
        header('Location: genre_add.php');
        exit();
    }
?>
    <form action="" method="post">
    <input type="hidden" name="action" value="submit">
        <dl>
            <dt>ジャンル名</dt>
            <dd>
                <?php 
                    echo htmlspecialchars($_SESSION['genre_name'], ENT_QUOTES);
                ?>
            </dd>
        <input  type="button" value="書き直す" onclick="location.href='genre_add.php?action=rewrite'">&nbsp;
        <input  type="submit" value="登録する">
    </form>