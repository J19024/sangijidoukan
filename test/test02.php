<?php
    echo $_POST['user_id'];
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>ログインする</title>
<link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/@mdi/font@5.x/css/materialdesignicons.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.min.css" rel="stylesheet">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">
</head>

<body>
  <div id="app">
    <v-app>
      <v-card width="400px" class="mx-auto mt-5">
        <v-card-title>
          <h1 class="display-1">ログイン</h1>
        </v-card-title>
        <v-card-text>
        <v-form v-model="valid" ref='form' action="" method="post">
          <v-container>
                  <v-text-field
                    name="user_id"
                    prepend-icon="mdi-account-circle"
                    v-model="user_id"
                    :rules="nameRules"
                    :counter="50"
                    label="ユーザ名"
                    required
                  ></v-text-field>

                  <v-text-field
                    name="password"
                    type="password"
                    v-model="lastname"
                    :rules="nameRules"
                    :counter="50"
                    label="パスワード"
                    v-bind:type="showPassword ? 'text' : 'password'" 
                    prepend-icon="mdi-lock" 
                    append-icon="mdi-eye-off"
                    v-bind:append-icon="showPassword ? 'mdi-eye' : 'mdi-eye-off'"
                    @click:append="showPassword = !showPassword"
                    required
                  ></v-text-field>
            </v-container>
            <v-btn class="info" type="submit">ログイン</v-btn>
          </v-form>
          <form action="" method="post">
            <input type="hidden" name="user_id" size="35" maxlength="255" value="test" />
            <input type="hidden" name="password" size="35" maxlength="255" value="12345" />
            <v-card-actions>
            </v-card-actions>
          </form>
        </v-card-text>
      </v-card>
    </v-app>
  </div>


<script src="https://cdn.jsdelivr.net/npm/vue@2.x/dist/vue.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vuetify@2.x/dist/vuetify.js"></script>
<script>
    new Vue({
      el: '#app',
      vuetify: new Vuetify(),
      data: () => ({
        showPassword : false
      })
    })
</script>
</body>
</html>