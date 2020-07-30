<?php require('login-check.php');
    // エラーを出力させない
    ini_set('display_errors', "Off");

    if(isset($_REQUEST["id"]) && is_numeric($_REQUEST['id'])){
        $id = $_REQUEST['id'];
        $books = $db->prepare('SELECT * FROM books WHERE id=?');
        $books->execute(array($id));
        $book = $books->fetch();
    }else{
    header('Location: books.php');
    exit();
    }
$genre_id = 'SELECT * FROM genre WHERE genre_id = '.$book['genre_id'];    
$genres = $db->query($genre_id);
?>
<DOCTYPE html>
    <h1>詳細画面</h1>
    <body>
        <dl>
            <dt>タイトル</dt>
            <dd>
                <?php 
                    echo $book['title'];
                ?>
            </dd>
            
            <dt>著者</dt>
            <dd>
                <?php echo $book['author']; ?>
            </dd>
            
            <dt>出版日</dt>
            <dd>
                <?php echo $book['publication']; ?>
            </dd>
            
            <dt>説明文</dt>
            <dd>
                <?php echo $book['explanation']; ?>
            </dd>
            
            <dt>出版社</dt>
            <dd>
                <?php echo $book['publisher']; ?>
            </dd>
            
            <dt>対象年齢</dt>
            <dd>
                <?php 
                    echo $book['age'];
                ?>
            </dd>
            <dt>ジャンル</dt>
            <dd>
                <?php 
                    $genre = $genres->fetch();
                    echo $genre["genre_name"]; 
                ?>
            </dd>
            <dt>表紙画像</dt>
            <dd>
                <img src="../pict/<?php echo $book['pict_path']; ?>" width="auto" height="auto" alt="" />
                <input type="button" value="閲覧" onclick="location.href='../pdf/<?php echo $book['pdf_path']; ?>'"/>
            </dd>  
            <dl>
                <input type="button" value="ホームへ戻る" onclick="location.href='books.php'">
            </dl>
        </dl>
    </body>
</html>