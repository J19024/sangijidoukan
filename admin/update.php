<?php require('login-check.php');
  // エラーを出力させない
  ini_set('display_errors', "Off");

  if(isset($_REQUEST['id']) && is_numeric($_REQUEST['id'])){
    $id = $_REQUEST['id'];
    $books = $db->prepare('SELECT * FROM books WHERE id=?');
    $books->execute(array($id));
    $book = $books->fetch();
  }else{
    header('Location: books.php');
    exit();
  }

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
            $_SESSION['join']['id'] = $id;
            //update_check.phpに移動する
            header('Location: update_check.php');
            exit();
        }
    }
    if ($_REQUEST["action"] == "rewrite") {
        $_POST = $_SESSION["join"];
        $error["rewrite"] = true;
    }
?>
<!DOCTYPE html>
    <h1>編集画面</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?php echo $id; ?>">
        <dl>
            <!--本情報の登録-->
            <dt>タイトル<span>必須</span></dt>
            <dd>
                <input type="varchar" name="title" size="35" maxlength="255" value="<?php echo $book['title'], htmlspecialchars($_POST["title"], ENT_QUOTES); ?>" />
                <?php if ($error["title"] == "blank"): ?>
                <p class ="error">*タイトルを入力してください。</p>
                <?php endif; ?>
            </dd>
            
            <dt>著者<span></span></dt>
            <dd>
                <input type="varchar" name="author" size="35" maxlength="255" value="<?php echo $book['author'], htmlspecialchars($_POST["author"], ENT_QUOTES); ?>" />
            </dd>
            
            <dt>出版日<span></span></dt>
            <dd>
                <input type="datetime" name="publication" size="35" maxlength="255" value="<?php echo $book['publication'], htmlspecialchars($_POST["publication"], ENT_QUOTES); ?>" />
            </dd>
            
            <dt>説明文<span></span></dt>
            <dd>
                <input type="text" name="explanation" size="85" maxlength="255" value="<?php echo $book['explanation'], htmlspecialchars($_POST["explanation"], ENT_QUOTES); ?>" />
            </dd>
            
            <dt>出版社<span></span></dt>
            <dd>
                <input type="varchar" name="publisher" size="35" maxlength="255" value="<?php echo $book["publisher"], htmlspecialchars($_POST["publisher"], ENT_QUOTES); ?>" />
            </dd>
            
            <dt>対象年齢<span>必須</span></dt>
            <dd>
                <input type="text" name="age" size="35" maxlength="2" value="<?php echo $book['age'], htmlspecialchars($_POST["age"], ENT_QUOTES); ?>" placeholder="4〜6歳" />
                <?php if ($error["age"] == "blank"): ?>
                <p class ="error">*対象年齢を入力してください。</p>
                <?php endif; ?>
            </dd>

            <dt>ジャンル<span></span></dt>
            <dd>
                <input type="varchar" name="genre" size="35" maxlength="255" value="<?php echo $book["genre"], htmlspecialchars($_POST["genre"], ENT_QUOTES); ?>" />
            </dd>

            <img src="../pict/<?php echo $book['pict_path']; ?>" width="48" height="64" alt="<?php echo $post['title']; ?>" />
            <dt>表紙画像<span>必須</span></dt>
            <dd>
                <input type="file" name="pict" value="<?php echo htmlspecialchars($_POST["pict"], ENT_QUOTES); ?>"required>
                <?php if($error['pict'] == 'type1'): ?>
                    <p>画像は「.jpg」または「.png」を指定してください</p>
      			<?php endif; ?>
            </dd>
            <dt>書籍PDF<span>必須</span></dt>
            <dd>
                <input type="file" name="pdf" value="<?php echo htmlspecialchars($_POST["pdf"], ENT_QUOTES); ?>" required>
                <?php if($error['pdf'] == 'type2'): ?>
                    <p>画像は「.pdf」を指定してください</p>
      			<?php endif; ?>
            </dd>
        </dl>
        <div>
            <input type="button" value="トップに戻る" onclick="location.href='books.php'">&nbsp;
            <input type="submit" value="入力内容を確認する" />
        </div>
    </form>
</body>