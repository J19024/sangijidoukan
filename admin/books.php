<?php require('login-check.php');?>
<?php
// エラーを出力させない
ini_set('display_errors', "Off");
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
if ($_REQUEST["action"] == "rewrite") {
    $_POST = $_SESSION["join"];
    $error["rewrite"] = true;
}
?>
<!DOCTYPE html>
<body>
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
            <dd><input type="varchar" name="genre" size="35" maxlength="255"
                 value="<?php echo htmlspecialchars($_POST["genre"], ENT_QUOTES); ?>"
                />
            </dd>
            <dt>表紙画像<span class="required">必須</span></dt>
            <dd>
                <input class="registerFile" type="file" name="pict" required>
                <?php if($error['pict'] == 'type1'): ?>
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
     <!--データの扱いが分かっていないため動作未確認-->
    <?php
        foreach ($posts as $post):
    ?>
    <div class="itiran">
        <img src="pict_path/<?php echo $post['pict_path']; ?>" width="48" height="48" alt="<?php echo $post['title']; ?>" />
        <p><span class="title"><?php echo $post['title']; ?></span></p>
        <p class="info">[<a href="update.php?id=<?php echo $post['id']; ?>"> 詳細</a>]</p>
        <p class="update">[<a href="view.php?id=<?php echo $post['id']; ?>"> 編集</a>]</p>
        <p class="delete">[<a href="delete.php?id=<?php echo $post['id']; ?>"> 削除</a>]</p>
    </div>
    <?php
        endforeach;
    ?>
</body>