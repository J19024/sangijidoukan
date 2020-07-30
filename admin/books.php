<?php require('login-check.php');

require('../dbconnect.php');
// エラーを出力させない
ini_set('display_errors', "Off");
session_start();
if (!empty($_POST)) {
	// エラー項目の確認
	if ($_POST['title'] == '') {
		$error['title'] = 'blank';
	}
	if ($_POST['age'] == '') {
		$error['age'] = 'blank';
    }
	$fileName = $_FILES['piture']['name'];
	if (!empty($fileName)) {
		$ext = substr($fileName, -3);
		if ($ext != 'jpg' && $ext != 'png') {
			$error['pict'] = 'type';
		}
	}
	$fileName2 = $_FILES['pdf']['name'];
	if (!empty($fileName2)) {
		$ext = substr($fileName2, -3);
		if ($ext != 'pdf') {
			$error['pdf'] = 'type2';
		}
    } 
    if(empty($error)){
        //画像をアップロードする
        $pict = date('YmdHis').$_FILES['pict']['name'];
            move_uploaded_file($_FILES['pict']['tmp_name'], '../pict/'.$pict);
        //PDFをアップロードする
        $pdf = date('YmdHis').$_FILES['pdf']['name'];
            move_uploaded_file($_FILES['pdf']['tmp_name'], '../pdf/'.$pdf);
        //セッションに各値を保存する
        $_SESSION['join'] = $_POST;
        $_SESSION['join']['pict'] = $pict;
        $_SESSION['join']['pdf'] = $pdf;
        //books_join_check.phpに移動する
        header('Location: books_join_check.php');
        exit();
    }
}
//消去フラグを立てる
if (!empty($_REQUEST['id'])){
    $statement = $db->prepare("UPDATE books SET dust_flug=1 WHERE id=?");
       $statement->execute(array(
        $_REQUEST['id']
        ));
    $message = "ゴミ箱に移動しました。";
}
//作業完了のメッセージ
if(!empty($_SESSION['update'])){
    $message = $_SESSION['update']; 
    unset($_SESSION['update']);
}
//DBから書籍情報の取得
$posts = $db->query('SELECT id, title, pict_path, dust_flug FROM books WHERE dust_flug NOT IN (1) ORDER BY id ASC');
$genres = $db->query('SELECT genre_id, genre_name FROM genre');


//書き直し処理
if ($_REQUEST["action"] == "rewrite") {
    $_POST = $_SESSION["join"];
    $error["rewrite"] = true;
}
?>
<!DOCTYPE html>
<body>
    <h1><?php echo $message; $message='';?></h1>
    <input  type="button" value="ゴミ箱へ" onclick="location.href='dustbox.php'">&nbsp;
    <input  type="button" value="ジャンル追加" onclick="location.href='genre_add.php'">
    <p>追加する書籍のデータを入力してください。</p>
    <form action="" method="post" enctype="multipart/form-data">
        <dl>
            <!--本情報の登録-->
            <dt>タイトル<span class="required">必須</span></dt>
            <dd>
                <input type="varchar" name="title" size="35" maxlength="255" 
                 value="<?php echo htmlspecialchars($_POST["title"], ENT_QUOTES); ?>" 
                />
                <?php if ($error["title"] == "blank"): ?>
                <p class ="error">*タイトルを入力してください。</p>
                <?php endif; ?>
            </dd>
            
            <dt>著者<span class="required"></span></dt>
            <dd><input type="varchar" name="author" size="35" maxlength="255" 
                 value="<?php echo htmlspecialchars($_POST["author"], ENT_QUOTES); ?>"
                />
            </dd>
            
            <dt>出版日<span class="required"></span></dt>
            <dd><input type="datetime" name="publication" size="35" maxlength="255" 
                 value="<?php echo htmlspecialchars($_POST["publication"], ENT_QUOTES); ?>"
                />
            </dd>
            
            <dt>説明文<span class="required"></span></dt>
            <dd><input type="text" name="explanation" size="85" maxlength="255"
                 value="<?php echo htmlspecialchars($_POST["explanation"], ENT_QUOTES); ?>"
                />
            </dd>
            
            <dt>出版社<span class="required"></span></dt>
            <dd><input type="varchar" name="publisher" size="35" maxlength="255"
                 value="<?php echo htmlspecialchars($_POST["publisher"], ENT_QUOTES); ?>"
                />
            </dd>
            
            <dt>対象年齢<span class="required">必須</span></dt>
            <dd>
                <input type="text" name="age" size="35" maxlength="2" 
                 value="<?php echo htmlspecialchars($_POST['age'], ENT_QUOTES); ?>" placeholder="4〜6歳"
                />
                <?php if ($error["age"] == "blank"): ?>
                <p class ="error">*対象年齢を入力してください。</p>
                <?php endif; ?>
            </dd>

            <dt>ジャンル<span class="required"></span></dt>
            <dd>
                <select name="genre_id">
                    <?php foreach($genres as $genre): ?>
                    <option value="<?php echo $genre['genre_id'];?>"><?php echo $genre['genre_name']; ?></option>
                    <?php endforeach ?>
                </select>
            </dd>
            <dt>表紙画像<span class="required">必須</span></dt>
            <dd>
                <input class="registerFile" type="file" name="pict" required>
                <?php if($error['pict'] == 'type'): ?>
                    <p class="error">画像は「.jpg」または「.png」を指定してください</p>
      			<?php endif; ?>
            </dd>
            <dt>書籍PDF<span class="required">必須</span></dt>
            <dd>
                <input class="registerFile" type="file" name="pdf" required>
                <?php if($error['pdf'] == 'type2'): ?>
                    <p class="error">画像は「.pdf」を指定してください</p>
      			<?php endif; ?>
            </dd>
        </dl>
        <div><input type="submit" value="入力内容を確認する" /></div>
    </form>
    <!--ここから本情報の表示-->
    <?php
        foreach ($posts as $post):
    ?>
    <div class="itiran">
        <img src="../pict/<?php echo $post['pict_path']; ?>" width="48" height="48" alt="<?php echo $post['title']; ?>" />
        <p><span class="title"><?php echo $post['title']; ?></span></p>
        <input  type="button" value="編集" onclick="location.href='update.php?id=<?php echo $post['id']; ?>'">&nbsp;
        <input  type="button" value="詳細" onclick="location.href='view.php?id=<?php echo $post['id']; ?>'">&nbsp;
        <input  type="button" value="消去" onclick="location.href='books.php?id=<?php echo $post['id']; ?>'">&nbsp;
    </div>
    <?php
        endforeach;
    ?>
</body>