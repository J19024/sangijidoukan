<?php
// エラーを出力させない
ini_set('display_errors', "Off");
require('../dbconnect.php');
session_start();

if (!empty($_POST)) {
  $TEST = "テスト";
	// ログインの処理
	if ($_POST['user_id'] != '' && $_POST['password'] != '') {
    $login = $db->prepare('SELECT * FROM users WHERE user_id=? AND password=?');
    $login->execute(array(
      $_POST['user_id'],
      //sha1($_POST['password'])
      $_POST['password']
    ));
    $member = $login->fetch();

    if ($member) {
			// ログイン成功
			$_SESSION['user_id'] = $member['user_id'];
      $_SESSION['time'] = time();
      header('Location: books.php');
        exit();
		} else {
			@$error['login'] = 'failed';
		}
	} else {
		@$error['login'] = 'blank';
	  }
  }
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<title>ログインする</title>
</head>

<body>
  <?php echo $SESSION['user_id'];$SESSION['password']; ?>
  <th>
    <?php echo $TEST;?> 
<div id="wrap">
  <div id="head">
    <h1>ログインする</h1>
  </div>
  <div id="content">
    <div id="lead">
      <p>IDとパスワードを記入してログインしてください。</p>
    </div>
    <form action="" method="post">
      <dl>
        <dt>ID</dt>
        <dd>
          <input type="text" name="user_id" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['user_id']); ?>" />
          <?php if ($error['login'] == 'blank'): ?>
          <p class="error">* メールアドレスとパスワードをご記入ください</p>
          <?php endif; ?>
          <?php if ($error['login'] == 'failed'): ?>
          <p class="error">* ログインに失敗しました。正しくご記入ください。</p>
          <?php endif; ?>
        </dd>
        <dt>パスワード</dt>
        <dd>
          <input type="password" name="password" size="35" maxlength="255" value="<?php echo htmlspecialchars($_POST['password']); ?>" />
          </dd>
      </dl>
      <div>
        <input type="submit" value="ログインする" />
      </div>
    </form>
  </div>
</div>
</body>
</html>