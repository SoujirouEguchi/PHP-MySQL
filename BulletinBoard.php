<?php
 // DB接続設定
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

          //テーブルの作成
    $sql = "CREATE TABLE IF NOT EXISTS m5tb"
    ."("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    ."name char(32),"
    ."str TEXT,"
    ."date char(32),"
    ."pass char(32)"
    .");";
    $stmt = $pdo->query($sql);
    

  
?>


<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_5-01</title>
</head>
<body>
  

    <form action="" method="post">
        <h1>好きなアーティストは？</h1>
        【投稿フォーム】
        <br>
        名前：　　　　<input type="text" name="name" placeholder="名前"
        value="<?php
            
            if(!empty($_POST["editnum"]) &&!empty($_POST["editpass"])){
                $editnum = $_POST["editnum"];
                $editpass = $_POST["editpass"];
                
                $sql = 'SELECT name FROM m5tb WHERE id=:editnum AND pass=:editpass';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':editnum', $editnum, PDO::PARAM_INT);
                $stmt->bindParam(':editpass', $editpass,PDO::PARAM_INT);
                $stmt->execute(); 
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    echo $row['name'];
                }
            }
         ?>"> 
         <br>
        コメント：　　<input type="text" name="str" placeholder="コメント"
        value="<?php
            if(!empty($_POST["editnum"]) &&!empty($_POST["editpass"])){
                $editnum = $_POST["editnum"];
                $editpass = $_POST["editpass"];
                
                $sql = 'SELECT str FROM m5tb WHERE id=:editnum AND pass=:editpass';
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':editnum', $editnum, PDO::PARAM_INT);
                $stmt->bindParam(':editpass', $editpass,PDO::PARAM_INT);
                $stmt->execute(); 
                $results = $stmt->fetchAll();
                foreach ($results as $row){
                    echo $row['str'];
                }
            }

         ?>"
        ><br>
        パスワード：　<input type="text" name="pass" placeholder="パスワード">
        <br>
   
        <input type="hidden" name="willeditnum" placeholder="編集予定番号" 
        value="<?php
            if(!empty($_POST["editnum"])){
                echo $editnum;
            }
        ?>"
        >
        <input type="submit" name="submit">

    
    <p>
    【削除フォーム】
    <br>
        投稿番号：　　<input type="text" name="dlt" placeholder="削除対象番号">
        <br>
        パスワード：　<input type="text" name="dltpass" placeholder="パスワード">
        <br>
        <input type="submit" name="submit" value="削除">
    
    </p>
    

   
    
    【編集フォーム】
    <br>
        投稿番号：　　<input type="text" name="editnum" placeholder="編集対象番号">
        <br>
        パスワード：　<input type="text" name="editpass" placeholder="パスワード">
        <br>
        <input type="submit" name="submit" value="編集">
    </form>
    <p>
        =====================================
        <br>
        【投稿一覧】
    </p>

    <?php
        if(!empty($_POST["name"]) && !empty($_POST["str"]) &&!empty($_POST["pass"])){
                $name = $_POST["name"];
                $str = $_POST["str"];
                $date = date("Y/m/d H:i:s");
                $pass = $_POST["pass"];
                //編集予定番号(willeditnum)に値が入っていないときは新規投稿、入っているときは編集
                if(empty($_POST["willeditnum"])){
                     //ここから新規投稿機能
                    //投稿機能
                    if(!empty($_POST["name"]) && !empty($_POST["str"]) && !empty($_POST["pass"])){
                        $sql = $pdo -> prepare("INSERT INTO m5tb (name, str, pass,date) VALUES (:name, :str, :pass,:date)");
                        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                        $sql -> bindParam(':str', $str, PDO::PARAM_STR);
                        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
                        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
                        $sql -> execute();
                    }
                }else{
                    //ここから編集機能
                    //編集予定番号の値を変数($willeditnum)に代入
                    $willeditnum = $_POST["willeditnum"];
                    $name = $_POST["name"];
                    $str = $_POST["str"];
                    $pass = $_POST["pass"];
                    $sql = 'UPDATE m5tb SET name=:name,str=:str,pass=:pass WHERE id=:willeditnum';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':str', $str, PDO::PARAM_STR);
                    $stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
                    $stmt->bindParam(':willeditnum', $willeditnum, PDO::PARAM_INT);
                    $stmt->execute();
                }
        }
        
    
        //削除機能
        if(!empty($_POST["dlt"]) && !empty($_POST["dltpass"])){
            $id = $_POST["dlt"];
            $dltpass = $_POST["dltpass"];
            $sql = 'delete from m5tb where id=:id AND pass=:dltpass';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':dltpass', $dltpass, PDO::PARAM_INT);
            $stmt->execute();
        }
        
        //編集機能

        
        //表示機能
        $sql = 'SELECT * FROM m5tb';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].'　';
            echo $row['name'].'　';
            echo $row['str'].'　';
            echo $row['date'].'<br>';
        echo "<hr>";
        }
    ?>
</body>
</html>