<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission5_2</title>
</head>
<body>
<?php
                    //DB接続設定
             $dsn = "mysql:dbname=****;host=localhost";
             $user = "****";
             $password = "****";
             $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
             //テーブル作成
             $sql = "CREATE TABLE IF NOT EXISTS board"
             ." ("
             . "id INT AUTO_INCREMENT PRIMARY KEY,"
             . "name char(32),"
             . "comment TEXT,"
             . "date TEXT,"
             . "pass TEXT"
             .");";
             $stmt = $pdo -> query($sql);
    ?>
    
    <?php
             
             if (isset($_POST['submit1'])){ //送信の時の処理
                $name=$_POST['name'];
                $comment=$_POST['comment'];
                $date = date("Y年m月d日 H時i分s秒");
                $pass = $_POST['pass1'];
             
                if($name!='' && $comment!='' && $pass!=''){
                    if($_POST['edit_post']==''){//新規投稿
                      //データ登録
                        $sql = $pdo -> prepare("INSERT INTO board (name, comment, date, pass) VALUES(:name, :comment, :date, :pass)");
                        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
                        $sql -> execute();
                    }
                    else{//編集投稿
                        $id = $_POST['edit_post'];
                        $sql = 'UPDATE board SET name=:name, comment=:comment,
                        date=:date, pass=:pass WHERE id=:id';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                        $stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
                        $stmt -> bindParam(':date', $date, PDO::PARAM_STR);
                        $stmt -> bindParam(':pass', $pass, PDO::PARAM_STR);
                        $stmt -> bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt -> execute();
                      }
                   
                }
             }

            if(isset($_POST['submit2'])){//削除の時の処理
                $del_id = $_POST['del'];
                $pass2 = $_POST['pass2'];
                if($del_id!='' && $pass2!=''){//where句で指定したidとパスワードのデータだけ実行
                    $sql = 'delete from board where id=:id && pass=:pass';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $del_id, PDO::PARAM_INT);
                    $stmt->bindParam(':pass', $pass2, PDO::PARAM_STR);
                    $stmt->execute();
                }
            }
            if(isset($_POST['submit3'])){
                $edit_num = $_POST['edit'];//編集フォームに登録した番号
                $pass3 = $_POST['pass3'];
                if($edit_num!='' && $pass3!=''){
                   //データ取得
                    $sql = 'SELECT * FROM board where id=:id && pass=:pass';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':id', $edit_num, PDO::PARAM_INT);
                    $stmt->bindParam(':pass', $pass3, PDO::PARAM_STR);
                    $stmt->execute();
                    $results = $stmt->fetchAll();
                    foreach($results as $row){
                            //編集フォームに入力した番号、パスワードがテーブルに登録したデータと一致するとき
                            $name2 = $row['name'];
                            $com2 = $row['comment'];
                            $edit_id = $_POST['edit'];
                    }
                    
                }
            }
    


                //表示
                $sql = 'SELECT * FROM board';
                $stmt = $pdo->query($sql);
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    //$rowの中にはテーブルのカラム名が入る
                    echo $row['id'].'<>';
                    echo $row['name'].'<>';
                    echo $row['comment'].'<>';
                    echo $row['date'].'<br>';
                    echo "<hr>";
                }
                
    ?>
    
   
    <form action="" method="post">
        <input type='hidden' name='edit_post' value='<?php
        if(!empty($edit_id)){echo $edit_id;}?>'>
        <input type="text"  name="name" value="<?php if(!empty($name2))
        {echo $name2;}?>"
        placeholder='名前' >
        <input type="text" name="comment" value='<?php 
        if(!empty($com2)){echo $com2;}?>' 
        placeholder='コメント'>
        <input type="password" name="pass1" placeholder='パスワード'>
        <input type="submit" name="submit1" value='送信'>
        <input type="text" name='del' placeholder='削除対象番号'>
        <input type='password' name='pass2' placeholder='パスワード'>
        <input type='submit' name='submit2' value='削除'>
        <input type="text" name='edit' placeholder='編集番号'>
        <input type='password' name='pass3' placeholder='パスワード'>
        <input type='submit' name='submit3' value='編集'>
        
    </form>
   
</body>
</html>

