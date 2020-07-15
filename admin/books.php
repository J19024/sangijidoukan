<?php 
// エラーを出力させない
ini_set('display_errors', "Off");

session_start();
require('../dbconnect.php');
if (!empty($_POST)) {
	// エラー項目の確認
	if ($_POST['title'] == '') {
		$error['title'] = 'blank';
	}
	if ($_POST['age'] == '') {
		$error['age'] = 'blank';
    }
	if ($_POST['pict_path'] == '') {
		$error['pict_path'] = 'blank';
    }
	$fileName = $_FILES['image']['name'];
	if (!empty($fileName)) {
		$ext = substr($fileName, -4);
		if ($ext != '*jpg' && $ext != 'jpeg') {
			$error['image'] = 'type';
		}
	}
	$fileName = $_FILES['image']['name'];
	if (!empty($fileName)) {
		$ext = substr($fileName, -3);
		if ($ext != 'pdf') {
			$error['image'] = 'type';
		}
	}
	// 重複アカウントのチェック
	if (empty($error)) {
		$member = $db->prepare('SELECT COUNT(*) AS cnt FROM members WHERE email=?');
		$member->execute(array($_POST['email']));
		$record = $member->fetch();
		if (@$record['cnt'] > 0) {
			$error['email'] = 'duplicate';
		}
	}
    //データの扱いがよくわかっていない
	if (empty($error)) {
		// 画像をアップロードする
		$image = date('YmdHis') . $_FILES['image']['name'];
		move_uploaded_file($_FILES['image']['tmp_name'], '../member_picture/' . $image);
		
		$_SESSION['join'] = $_POST;
		$_SESSION['join']['image'] = $image;
        header('Location: check.php'); 
        exit();
	}
}
?>
<!DOCTYPE html>
<body>
    <p>追加する書籍のデータを入力してください。</p>
    <from action="" method="post" enctype="multipart/form-data">
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
            <dd><input type="text" name="explanation" size="35" maxlength="255"
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
                 value="<?php echo htmlspecialchars($_POST["age"], ENT_QUOTES); ?>"
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
            <dd><input type="file" name="pict_path" size="255"
                 value="<?php echo htmlspecialchars($_POST["pict_path"], ENT_QUOTES); ?>"
                />
            </dd>

            <dt>書籍PDF<span class="required">必須</span></dt>
            <dd><input type="file" name="pdf_path" size="255"
                 value="<?php echo htmlspecialchars($_POST["pdf_path"], ENT_QUOTES); ?>"
                />
            </dd>
        </dl>
        <div><input type="submit" value="入力内容を確認する" /></div>
    </from>
    <!--ここから本情報の表示-->
     <!--データの扱いが分かっていないため動作未確認-->
    <?php
        foreach ($posts as $post):
    ?>
    <div class="itiran">
        <img src="pict_path/<?php echo h($post['pict_path']); ?>" width="48" height="48" alt="<?php echo h($post['title']); ?>" />
        <p><span class="title"><?php echo h($post['title']); ?></span></p>
        <p class="info">[<a href="update.php?id=<?php echo h($post['id']); ?>"> 詳細</a>]</p>
        <p class="update">[<a href="view.php?id=<?php echo h($post['id']); ?>"> 編集</a>]</p>
        <p class="delete">[<a href="delete.php?id=<?php echo h($post['id']); ?>">　削除</a>]</p>
    </div>
    <?php
        endforeach;
    ?>
</body>