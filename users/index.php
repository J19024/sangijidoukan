<?php
    require('../dbconnect.php');
    // エラーを出力させない
    ini_set('display_errors', "Off");
    
    if(isset($_REQUEST["id"])){
        $sql = 'SELECT id, title, pict_path, pdf_path, genre_id, dust_flug FROM books WHERE dust_flug NOT IN (1) AND genre_id='.$_REQUEST["id"];
    }else{
        $sql = 'SELECT id, title, pict_path, pdf_path, genre_id, dust_flug FROM books WHERE dust_flug NOT IN (1)';
    }


    $posts = $db->query($sql.' ORDER BY id ASC');
    $genres = $db->query('SELECT * FROM genre WHERE genre_id NOT IN (1)');
?>
<!DOCTYPE html>
    <h1><?php echo $_REQUEST["id"];?></h1>
    <body>
        <form action="" method="post">
            <dl>
                <?php foreach($genres as $genre): ?>
                    <input type="button" name="genre" value="<?php echo $genre["genre_name"]; ?>" 
                        onclick="location.href='index.php?id=<?php echo $genre['genre_id']; ?>'"/><br>
                <?php endforeach; ?>
                    <input type="button" name="genre" value="リセット" 
                        onclick="location.href='index.php'"/><br>
            </dl>
        </form>
        <div>
            <?php foreach ($posts as $post): ?>
                <div>
                    <a href="../pdf/<?php echo $post['pdf_path'];?>">
                        <img src="../pict/<?php echo $post['pict_path']; ?>" width="48" height="48" alt="<?php echo $post['title']; ?>" />
                    </a>
                    <p><span class="title"><?php echo $post['title']; ?></span></p>
            <?php endforeach; ?>
        </div>
</body>