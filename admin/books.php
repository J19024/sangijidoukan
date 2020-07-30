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
	$fileName = $_FILES['pict']['name'];
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
<head>
<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
</head>
<style>
* {
  margin:0; padding:0;
}

body {
  min-height: 1500px;
}

header {
  height: 100px;
  width: 100%;
  padding: 15px 0;
  background-color: #337079;
  color: white;
}

.unchi {
  width:auto;
  margin-right: auto;
  margin-left: auto;
  margin-top: 50px;
  margin-bottom: 50px;
}

.unchii {
  width:auto;
  margin-right: auto;
  margin-left: auto;
}

.ccard {
  margin-bottom: 50px;
}

.usagi {
  width: 300px;
  height: auto;
}
</style>
<body>
<header>
  	<h1 class="headline">
    <div class="unchii">
      <a>サンギジドウカン</a>
    </div>
    </h1>
</header>
      <h1><?php echo $message; $message='';?></h1>
    <input  type="button" value="ゴミ箱へ" onclick="location.href='dustbox.php'">&nbsp;
    <input  type="button" value="ジャンル追加" onclick="location.href='genre_add.php'">
<div id="app">
    <v-app>
    <?php if ($error['login'] == 'blank'): ?>
            <p class="error">* メールアドレスとパスワードをご記入ください</p>
            <?php endif; ?>
            <?php if ($error['login'] == 'failed'): ?>
            <p class="error">* ログインに失敗しました。正しくご記入ください。</p>
    <?php endif; ?>
    <v-row>
          <v-col>
      <v-card width="700px" class="mx-auto mt-5">
        <v-card-title>
          <h1 class="display-1">本の追加</h1>
        </v-card-title>
        
        <v-card-text>
        <v-form v-model="valid" ref='form' action="" method="post" enctype="multipart/form-data">
          <v-container>
            <v-row>
            <v-col>
                  <v-text-field
                    name="title"
                    prepend-icon="mdi-account-circle"
                    v-model="title"
                    :rules="nameRules"
                    :counter="35"
                    label="タイトル"
                    required
                  ></v-text-field>

                  <v-text-field
                    name="author"
                    prepend-icon="mdi-account-circle"
                    v-model="author"
                    :rules="nameRules"
                    :counter="35"
                    label="著者"
                    required
                  ></v-text-field>
                  
                  <v-text-field
                    name="publisher"
                    prepend-icon="mdi-account-circle"
                    v-model="publisher"
                    :rules="nameRules"
                    :counter="35"
                    label="出版社"
                    required
                  ></v-text-field>

                 <dt>ジャンル<span class="required"></span></dt>
            <dd>
                <select name="genre_id">
                    <?php foreach($genres as $genre): ?>
                    <option value="<?php echo $genre['genre_id'];?>"><?php echo $genre['genre_name']; ?></option>
                    <?php endforeach ?>
                </select>
            </dd>

                  <v-text-field
                    type="date"
                    name="publication"
                    label="出版日"
                 >
                 </v-text-field>
        </v-col>
        <v-col>
                  <v-file-input
                    type="file"
                    name="pict"
                    v-model="input_image"
                    accept="image/*"
                    show-size
                    label="画像ファイルを選択"
                    prepend-icon="mdi-image"
                    @change="onImagePicked"
                  ></v-file-input>

                  <v-file-input 
                    type="file"
                    show-size 
                    name="pdf"
                    label="pdfファイルを選択">
                  </v-file-input>

                  <v-row>
                    <v-col >
                        <v-textarea
                        filled
                        auto-grow
                        name="explanation"
                        label="説明文"
                        rows="4"
                        row-height="30"
                        shaped
                        class="align-center"
                        ></v-textarea>
                    </v-col>
                  </v-row>

                  <v-row>
                    <v-col class="pr-4">
                    <v-slider
                        label="対象年齢"
                        v-model="slider"
                        thumb-label="always"
                        name="age"
                        class="align-center"
                        :max="18"
                        :min="0"
                        hide-details
                    >
                        <template v-slot:append>
                        <v-text-field
                            v-model="slider"
                            class="mt-0 pt-0"
                            hide-details
                            single-line
                            type="number"
                            style="width: 60px"
                        ></v-text-field>
                        </template>
                    </v-slider>
                </v-row>
                </v-col>
            </v-row>
            </v-container>
            <v-card-actions>
              <v-btn class="info" type="submit">確認</v-btn>
            </v-card-actions>
          </v-form>
        </v-card-text>
      </v-card>
      </v-col>
      <v-col>
        <v-card 
          width="300px" 
          class="mx-auto mt-5">
        <img 
          v-if="uploadImageUrl" 
          :src="uploadImageUrl" >
        </v-card>
      </v-col>
      </v-row>
          <!--ここから本情報の表示-->
      <div class="unchi">
              <h1>一覧</h1>
      </div>
      <?php
          foreach ($posts as $post):
      ?>
      <div class="ccard">
            <v-card
            class="mx-auto"
            max-width="400"
            maring-bottom="50px"
          >
          <v-img
            class="white--text align-end"
            height="auto"
            src="../pict/<?php echo $post['pict_path']; ?>"
          >
            <v-card-title><?php echo $post['title']; ?></v-card-title>
          </v-img>
          <v-row
            justify="center" align-content="center"
          >
            <v-card-actions aligin="center">
              <v-btn
                color="orange"
                onclick="location.href='update.php?id=<?php echo $post['id']; ?>'"
              >
                編集
              </v-btn>

              <v-btn
                color="primary"
                onclick="location.href='view.php?id=<?php echo $post['id']; ?>'"
              >
                詳細
              </v-btn>

              <v-btn
                color="error"
                onclick="location.href='books.php?id=<?php echo $post['id']; ?>'"
              >
                削除
              </v-btn>
            </v-card-actions>
          </v-row>
        </v-card>
        </div>
      <?php
          endforeach;
      ?>
      </v-app>
  </div>

<script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
<link rel='stylesheet' href='https://unpkg.com/v-calendar/lib/v-calendar.min.css'>
<script src='https://unpkg.com/v-calendar'></script>
<script>
    new Vue({
      el: '#app',
      vuetify: new Vuetify(),
      data: { 
        attrs: [
            {
            key: 'today',
            highlight: {
                backgroundColor: '#ff8080',
            },
            dates: new Date(),
            popover: {
                label: 'メッセージを表示できます',
            },
            }
        ],
        input_image: null,
        uploadImageUrl: ''
      },
      methods: {
        onImagePicked(file) {
          if (file !== undefined && file !== null) {
            if (file.name.lastIndexOf('.') <= 0) {
              return
            }
            const fr = new FileReader()
            fr.readAsDataURL(file)
            fr.addEventListener('load', () => {
              this.uploadImageUrl = fr.result
            })
          } else {
            this.uploadImageUrl = ''
          }
        }
      }
    })
</script>
</body>
</html>