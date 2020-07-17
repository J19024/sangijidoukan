<?php
// エラーを出力する
ini_set('display_errors', "Off");
session_start();
	require('../dbconnect.php');

	if (!isset($_SESSION['join'])) {
		header('Location: books.php'); 
		exit();
	}

if (!empty($_POST)) {
	// 登録処理をする
	$statement = $db->prepare('INSERT INTO books SET title=?, author=?, publication=?, explanation=?, publisher=?, 
                                age=?, pict_path=?, dust_flug=?,  created=NOW(),  updated=NOW(), pdf_path=?, genre=?');
	echo $ret = $statement->execute(array(
        $_SESSION['join']['title'],
        $_SESSION['join']['author'],
        $_SESSION['join']['publication'],
        $_SESSION['join']['explanation'],
        $_SESSION['join']['publisher'],
        $_SESSION['join']['age'],
        $_SESSION['join']['pict_path'],
        $_SESSION['join']['dust_flug'],
        $_SESSION['join']['created'],
        $_SESSION['join']['updated'],
        $_SESSION['join']['pdf_path'],
		$_SESSION['join']['genre'],
		$_SESSION['join']['image']
	));
	unset($_SESSION['join']);

	header('Location: thanks.php');
	exit();
}
?>
<?php require("books.php");
    session_start();
    if  (!empty($_POST))    {
        //エラー項目の確認
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
    
        if(empty($error)){
            $_SESSION["admin"] = $_POST;
            header("Location: check.php");
            exit();
        }
    }
?>