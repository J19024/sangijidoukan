<?php require('login-check.php');

    // エラーを出力させない
    ini_set('display_errors', "Off");

    session_start();
    require('../dbconnect.php');
    
    if (isset($_POST['reviv'])){
            $db->query("UPDATE books SET dust_flug=0".$_SESSION["rect_id"]);
            $_SESSION["rest_message"] = "ゴミ箱から復元しました。";
            header('Location: dustbox.php');
            exit();
    }
    $checks = $_SESSION['rest'];
    $sql = 'SELECT title, pict_path from books';
    $id;
    $count = 0;
    foreach ($checks as $check): 
        if($count == 0){
            $id .= " WHERE id = $check";
            $count = 1;
        }else{
            $id .= " || id = $check";
            echo $sql;
        }
    endforeach;
    $sql .= $id." ORDER BY id ASC";
    $rects = $db->query($sql);
    $_SESSION["rect_id"]=$id; 
?>
<!DOCTYPE html>
    <p>復元確認画面</p>
    <form action="" method="post">     
        <body>
            <?php 
                foreach ($rects as $rect):
            ?>
               <div>
                   <img src="../pict/<?php echo $rect['pict_path']; ?>" width="48" height="48" alt="<?php echo $post['title']; ?>" />     
                   <p><span><?php echo $rect['title']; ?></span></p>
               </div>
            <?php
                endforeach;
            ?>
            <div><input type="submit" name="reviv"　value="消去する"></div>
        </body>
    </form>
</html>