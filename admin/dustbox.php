<?php require('login-check.php');

// エラーを出力させない
ini_set('display_errors', "Off");
require('../dbconnect.php');



if (isset($_POST['rest'])){
    $_SESSION["rest"] = $_POST["check"];  
    header('Location: rest_check.php');
    exit();
}elseif(isset($_POST['delete'])){
    $_SESSION["delete"] = $_POST["check"];
    header('Location: delete_check.php');
    exit();
}
$posts = $db->query('SELECT * FROM books WHERE dust_flug IN (1) ORDER BY id ASC');
?>

<body>
<?php
    foreach ($posts as $post):
?>
    <form action="" method="post">
    <input type="hidden" name="delete" value="button">
    <div class="itiran">
        <img src="../pict/<?php echo $post['pict_path']; ?>" width="48" height="48" alt="<?php echo $post['title']; ?>" />
        <p>
            <input  type="checkbox" name="check[]" value=<?php echo $post['id']?>>
            <span class="title"><?php echo $post['title']; ?></span>
        </p>
    <?php
    endforeach;
    ?>
        <input type="submit" name="rest" value="復元">&nbsp;
        <input type="submit" name="delete" value="消去">
    </div>
    </form>
    <input type="button" value="ホームへ戻る" onclick="location.href='books.php'">
</body>