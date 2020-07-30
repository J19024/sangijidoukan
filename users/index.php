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
<head>
<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
</head>
<style>
.center {
  width:auto;
  margin-right: auto;
  margin-left: auto;
  margin-top: 50px;
  margin-bottom: 50px;
}
</style>
<body>
    <body>
    <div id="app">
    <v-app>
        <v-card width="750px" class="mx-auto mt-5">
        <v-card-title>ジャンル選択</v-card-title>
            <form action="" method="post">
                    <v-row>
                    <?php foreach($genres as $genre): ?>
                        <v-col>
                        <v-btn name="genre" 
                            onclick="location.href='index.php?id=<?php echo $genre['genre_id']; ?>'"/>
                            <?php echo $genre["genre_name"]; ?>
                        </v-btn>
                        </v-col>
                    <?php endforeach; ?>
                    </v-row>
                    <v-row>
                    <v-col>
                        <v-btn name="genre" value="リセット"  color="error"
                            onclick="location.href='index.php'"/>
                        リセット
                        </v-btn>
                    </v-col>
                    </v-row>
            </form>
        </v-card>
        <div>
            <?php foreach ($posts as $post): ?>
                <div class="center">
                    <v-card
                        class="mx-auto"
                        max-width="400"
                    >
                    <a href="../pdf/<?php echo $post['pdf_path'];?>">
                        <v-img
                        class="white--text align-end"
                        height="auto"
                        src="../pict/<?php echo $post['pict_path']; ?>" 
                        >
                    </a>
                        <v-card-title><?php echo $post['title']; ?></v-card-title>
                        </v-img>

                    </v-card>
                    </div>
            <?php endforeach; ?>
        </div>
        </v-app>
        </div>
        
</body>
<script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
<link rel='stylesheet' href='https://unpkg.com/v-calendar/lib/v-calendar.min.css'>
<script src='https://unpkg.com/v-calendar'></script>
<script>
    new Vue({
      el: '#app',
      vuetify: new Vuetify(),
    })
</script>
</html>