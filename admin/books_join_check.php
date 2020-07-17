<?php
    session_start();

    if(!isset($_SESSION['join'])) {
        header('Location: books.php');
        exit();
    }
?>

<form　action="" method="post">
    <dl>
            <dt>タイトル</dt>
            <dd>
                <?php echo htmlspecialchars($_SESSION['join']["title"], ENT_QUOTES); ?>" 
            </dd>
            
            <dt>著者</dt>
            <dd>
                <?php echo htmlspecialchars($_POST["author"], ENT_QUOTES); ?>"
            </dd>
            
            <dt>出版日</dt>
            <dd>
                <?php echo htmlspecialchars($_POST["publication"], ENT_QUOTES); ?>"
            </dd>
            
            <dt>説明文</dt>
            <dd>
                <?php echo htmlspecialchars($_POST["explanation"], ENT_QUOTES); ?>"
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