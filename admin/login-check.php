<?php
  require('../dbconnect.php');
  session_start();

  //idが存在してログインから60分が経過していなかったら
  if(isset($_SESSION['user_id']) && $_SESSION['time'] + 3600 > time()){
    //ログインしている
    //現在の時刻をセッションに格納する（ここからさらに60分ログインが継続する）
    $_SESSION['time'] = time();

  }else{
    //ログインしていない
    header('Location: login.php');
    exit();
  }
?>