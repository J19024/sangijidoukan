<?php require('login-check.php');

    // エラーを出力させない
    ini_set('display_errors', "Off");

    session_start();
    require('../dbconnect.php');
    
    if (isset($_POST['erasure'])){
          $db->query("DELETE FROM books".$_SESSION["delete_id"]);
        header('Location: dustbox.php');
        exit();
    }
    $checks = $_SESSION['delete'];
    $sql = 'SELECT title, pict_path from books';
    $id;
    $count = 0;
    foreach ($checks as $check): 
        if($count == 0){
            $id .= " WHERE id = $check";
            $count = 1;
            echo $sql;
        }else{
            $id .= " || id = $check";
            echo $sql;
        }
    endforeach;
    $sql .= $id." ORDER BY id ASC";
    $deletes = $db->query($sql);
    $_SESSION["delete_id"]=$id; 
?>
<!DOCTYPE html>
    <form action="" method="post">     
        <body>
            <?php 
                foreach ($deletes as $delete):
            ?>
               <div>
                   <img src="../pict/<?php echo $delete['pict_path']; ?>" width="48" height="48" alt="<?php echo $post['title']; ?>" />     
                   <p><span><?php echo $delete['title']; ?></span></p>
               </div>
            <?php
                endforeach;
            ?>
            <div><input type="submit" name="erasure"　value="消去する"></div>
        </body>
    </form>
</html>