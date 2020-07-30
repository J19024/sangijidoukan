<?php require('login-check.php');

    // エラーを出力させない
    ini_set('display_errors', "Off");
    session_start();
    require('../dbconnect.php');

    if(!isset($_SESSION['join'])) {
        header('Location: update.php');
        exit();
    }
    if (!empty($_POST)){
        $statement = $db->prepare("UPDATE books SET title=?, author=?, publication=?, explanation=?, publisher=?,
                                    age=?, pict_path=?, pdf_path=?, genre=?, updated=NOW() WHERE id=?");
       $statement->execute(array(
            $_SESSION['join']['title'],
            $_SESSION['join']['author'],
            $_SESSION['join']['publication'],
            $_SESSION['join']['explanation'],
            $_SESSION['join']['publisher'],
            $_SESSION['join']['age'],
            $_SESSION['join']['pict'],
            $_SESSION['join']['pdf'],
            $_SESSION['join']['genre'],
            
            $_SESSION['join']['id']
        ));
        unset($_SESSION['join']);
        $_SESSION['update'] = "編集が完了しました。";
        header('Location: books.php');
        exit();
    }
?>

<form action="" method="post">
<input type="hidden" name="id" value="<?php echo $id; ?>">
    <dl>
        <dt>タイトル</dt>
        <dd>
            <?php echo htmlspecialchars($_SESSION['join']["title"], ENT_QUOTES); ?>
        </dd>
            
        <dt>著者</dt>
        <dd>
            <?php echo htmlspecialchars($_SESSION['join']["author"], ENT_QUOTES); ?>
         </dd>
            
        <dt>出版日</dt>
        <dd>
            <?php echo htmlspecialchars($_SESSION['join']["publication"], ENT_QUOTES); ?>
        </dd>
            
        <dt>説明文</dt>
        <dd>
            <?php echo htmlspecialchars($_SESSION['join']["explanation"], ENT_QUOTES); ?>
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
            <img src="../pict/<?php echo $_SESSION['join']['pict']; ?>" width="auto" height="auto" alt="<?php echo $_SESSION['join']['pict']; ?>" />    
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
    <div>
        <input  type="button" value="書き直す" onclick="location.href='update.php?action=rewrite'">&nbsp;
        <input  type="submit" value="登録する">  
    </div>
</form>