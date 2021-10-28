<span style="color: red"><span style="font-size: 50px;">ここは秘密の部屋</span></span>
 <?php
    $dsn = 'データベース名';
    $user = 'ユーザー名';
    $password = 'パスワード';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

    $ku = 0;
    $hb = 0;
    $filename="mission3-5.txt";
    $hn = "名前";
    $hc = "コメント";/*読込ファイルの指定*/
    //$lines = file($filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
    
    //編集
    if(isset($_POST['hsubmit'])){
    $khb = $_POST["hb"];
    $hps = $_POST["hps"];
    $sql = 'SELECT * FROM tbs';
    $stmt = $pdo->query($sql);
    $del = $stmt->fetchAll();
    foreach ($del as $row){
        if ($row['id'] == $khb && $row['ps'] == $hps){
            $hb = $row['id'];
            $hn = $row['name'];
            $hc = $row['comment'];
        }
    }
    }
    //削除
    elseif(isset($_POST['submit2'])){
    $sb = $_POST["sb"];
    $sps = $_POST["sps"];
    $sql = 'SELECT * FROM tbs';
    $stmt = $pdo->query($sql);
    $del = $stmt->fetchAll();
    foreach ($del as $row){
        if ($row['id'] == $sb && $row['ps'] == $sps){
    $id = $row['id'];
    $sql = 'delete from tbs where id=:id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
            }
        }
    }
    //編集か判断
    elseif (isset($_POST['submit1'])) {
    if(empty($_POST['str']) or empty($_POST['str'])){
        echo "名前かコメントに入力漏れがあります";
    }else{
    $hb2 = $_POST["hb2"];
    $str = $_POST["str"];
    $time = date ( "Y/m/d/ H:i:s" );
    $name = $_POST["str2"];
    $ps = $_POST["ps"];
    //投稿書き込み
    if(!empty($hb2)){
        $sql = 'SELECT * FROM tbs';
        $stmt = $pdo->query($sql);
        $del = $stmt->fetchAll();
        foreach ($del as $row){
            $id = $row['id'];
            if ($row['id'] == $hb2){
                if(empty($ps)){
                    
                    echo "確認";
                        $sql = 'UPDATE tbs SET name=:name,comment=:comment,time=:time WHERE id=:id';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                        $stmt->bindParam(':comment', $str, PDO::PARAM_STR);
                        $stmt->bindParam(':time', $time, PDO::PARAM_STR);
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt->execute();
                }else{
                        $sql = 'UPDATE tbs SET name=:name,comment=:comment,time=:time,ps=:ps WHERE id=:id';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                        $stmt->bindParam(':comment', $str, PDO::PARAM_STR);
                        $stmt->bindParam(':time', $time, PDO::PARAM_STR);
                        $stmt->bindParam(':ps', $ps, PDO::PARAM_STR);
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt->execute();
                }
        }
        }
        }else{
            if(!empty($_POST['ps'])){
                $sql = $pdo -> prepare("INSERT INTO tbs (name, comment, time, ps) VALUES (:name, :comment, :time,
                                        :ps)");
                $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                $sql -> bindParam(':comment', $str, PDO::PARAM_STR);
                $sql -> bindParam(':time', $time, PDO::PARAM_STR);
                $sql -> bindParam(':ps', $ps, PDO::PARAM_STR);  
                $sql -> execute();
            }else{
                echo "パスワードを入力してください";
            }
        }
    }

    }
}
    //echo $tou."<br>";


    //fclose($fp);
    ?>
    <!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission_3-5</title>
    <style type="text/css">
    body {
            background-color: #000000;
            color: red;
            width: 100%;
        }
    </style>
</head>
<body>
    <form action="" method="post">
    <input type="hidden" name="hb2" value="<?php echo $hb; ?>">
    <input type="password" name="ps" placeholder="パスワード">
    <br>
    <input type="text" name="str" placeholder="<?php echo $hn; ?>">
    <br>
    <input type="text" name="str2" placeholder="<?php echo $hc; ?>">
    <input type="submit" name="submit1">
    <hr>
    <input type="password" name="sps" placeholder="削除対象パスワード">
    <br>
    <input type="number" name="sb" placeholder="削除番号" >
    <input type="submit" name="submit2">
    <hr>
    <input type="password" name="hps" placeholder="編集対象パスワード">
    <br>
    <input type="number" name="hb" placeholder="編集番号" >
    <input type="submit" name="hsubmit">    
    </form>
    <?php
    $sql = 'SELECT * FROM tbs';
    $stmt = $pdo->query($sql);
    $results = $stmt->fetchAll();
    foreach ($results as $row){
        //$rowの中にはテーブルのカラム名が入る
        echo $row['id'].',';
        echo $row['name'].',';
        echo $row['comment'].',';
        echo $row['time'].'<br>';
    }
        ?>
</body>
</html>