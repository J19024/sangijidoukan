<?php require('login-check.php');

    // エラーを出力させない
    ini_set('display_errors', "Off");
    
    session_start();
    require('../dbconnect.php');
    //データが送られているか確認
    if(!isset($_SESSION['join'])) {
        header('Location: books.php');
        exit();
    }

    //DBに値を格納する
    if (!empty($_POST)){
        $statement = $db->prepare("INSERT INTO books SET title=?, author=?, publication=?, explanation=?, publisher=?,
                                    age=?, pict_path=?, pdf_path=?, genre=?, created=NOW(), updated=NOW()");
       $statement->execute(array(
            $_SESSION['join']['title'],
            $_SESSION['join']['author'],
            $_SESSION['join']['publication'],
            $_SESSION['join']['explanation'],
            $_SESSION['join']['publisher'],
            $_SESSION['join']['age'],
            $_SESSION['join']['pict'],
            $_SESSION['join']['pdf'],
            $_SESSION['join']['genre']
        ));
        unset($_SESSION['join']);
    
        header('Location: books.php');
        exit();
    }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<body>
    <form action="" method="post">
    <input type="hidden" name="action" value="submit">
        <dl>
            <dt>タイトル</dt>
            <dd>
                <?php 
                    echo htmlspecialchars($_SESSION['join']['title'], ENT_QUOTES);
                ?>
            </dd>
            
            <dt>著者</dt>
            <dd>
                <?php echo htmlspecialchars($_SESSION['join']['author'], ENT_QUOTES); ?>
            </dd>
            
            <dt>出版日</dt>
            <dd>
                <?php echo htmlspecialchars($_SESSION['join']['publication'], ENT_QUOTES); ?>
            </dd>
            
            <dt>説明文</dt>
            <dd>
                <?php echo htmlspecialchars($_SESSION['join']['explanation'], ENT_QUOTES); ?>
            </dd>
            
            <dt>出版社</dt>
            <dd>
                <?php echo htmlspecialchars($_SESSION['join']['publisher'], ENT_QUOTES); ?>
            </dd>
            
            <dt>対象年齢</dt>
            <dd>
                <?php 
                    echo htmlspecialchars($_SESSION['join']['age'], ENT_QUOTES);
                ?>
            </dd>
            <dt>ジャンル</dt>
            <dd>
                <?php echo htmlspecialchars($_SESSION['join']['genre'], ENT_QUOTES); ?>
            </dd>
            <dt>表紙画像</dt>
            <dd>
                <img src="../pict/<?php echo $_SESSION['join']['pict']; ?>" width="auto" height="auto" alt="" />    
                <?php 
                    echo htmlspecialchars($_SESSION['join']['pict'], ENT_QUOTES);
                ?>
            </dd>
            
            <dt>書籍PDF</dt>
            <dd>
                <?php 
                    echo htmlspecialchars($_SESSION['join']['pdf'], ENT_QUOTES); 
                ?>
            </dd>
        </dl>
        <input  type="button" value="書き直す" onclick="location.href='books.php?action=rewrite'">&nbsp;
        <input  type="submit" value="登録する">
    </form>
</body>