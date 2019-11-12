<!DOCTYPE html>
<html lang="ja">

<head>
    <title>掲示板</title>
    <meta charset="utf-8">
</head>

<body>
    <?php

$dsn = 'データベース名';
$username = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $username, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

$sql = "CREATE TABLE IF NOT EXISTS yuji"
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "comment TEXT,"
. "updated_at DATETIME"
.");";
$stmt = $pdo->query($sql);

$commentkey = "*****";
$deletekey = "*****";
$editkey = "*****";

if (isset($_POST["delete"])) {
    if (isset($_POST["deletenumber"])) {
        if(isset($_POST["deletepass"])){
            $deletenumber = $_POST["deletenumber"];
            $deletepass = $_POST["deletepass"];
                if ($deletepass == $deletekey) {
                    $sql = "DELETE FROM yuji WHERE id=:deletenumber";/* */
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam("deletenumber", $deletenumber, PDO::PARAM_INT);
                    $stmt->execute();
                    } else {
                        echo "パスワードが違うよ！";
                    }
        }
    }
}
if (isset($_POST["send"])) {
    if (isset($_POST["name"], $_POST["comment"],$_POST["commentpass"])) {
        $name = $_POST["name"];
        $newdate = date("Y/m/d H:i:s");
        $comment = $_POST["comment"];
        $commentpass = $_POST["commentpass"];
            if ($commentpass == $commentkey) {
                if (isset($_POST["editnumberhidden"])) {
                    $id = $_POST["editnumberhidden"];
                    $sqle = "update yuji set name=:name, comment=:comment, updated_at=:updated_at where id=:id";
                    $sql = $pdo->prepare($sqle);
                    $sql->bindParam(':name', $name, PDO::PARAM_STR);
                    $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $sql->bindParam(':updated_at', $newdate, PDO::PARAM_STR);
                    $sql->bindParam(':id', $id, PDO::PARAM_INT);
                    $sql->execute();
                } else {
                    $sqli = "INSERT INTO yuji (name, comment, updated_at) VALUES(:name, :comment, :updated_at);";
                    $sql = $pdo->prepare($sqli);
                    $sql->bindParam(':name', $name, PDO::PARAM_STR);
                    $sql->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $sql->bindParam(':updated_at', $newdate, PDO::PARAM_STR);
                    $sql->execute();
                }
            } else {
                echo "パスワードが違うよ！";
            }
    }
}
?>
    <h1>掲示板</h1>
    <form action="" method="post">
        <p>
            <input type="text" name="name" placeholder="名前" value=<?php
        if (isset($_POST["editnumber"], $_POST["editpass"])) {
            $editnumber = $_POST["editnumber"];
            $editpass = $_POST["editpass"];
                if (isset($_POST["edit"])) {
                    if ($editpass == $editkey) {
                        $stmt = $pdo->prepare("SELECT name FROM yuji WHERE id=:editnumber");
                        $stmt->bindValue(":editnumber", $editnumber, PDO::PARAM_STR);
                        $stmt->execute();
                        $result = $stmt->fetchColumn();
                            echo $result;
                    }
                }
        } ?>>
            <input type="text" name="comment" placeholder="コメント" value=<?php
        if (isset($_POST["editnumber"], $_POST["editpass"])) {
            $editnumber = $_POST["editnumber"];
            $editpass = $_POST["editpass"];
                if (isset($_POST["edit"])) {
                    if ($editpass == $editkey) {
                        $stmt = $pdo->prepare("SELECT comment FROM yuji WHERE id=:editnumber");
                        $stmt->bindValue(":editnumber", $editnumber, PDO::PARAM_STR);
                        $stmt->execute();
                        $result = $stmt->fetchColumn();
                            echo $result;
                    }
                }
        } ?>>
            <input type="text" name="commentpass" placeholder="パスワード">
            <?php if (isset($_POST["editnumber"], $_POST["edit"])) {
            $editnumber = $_POST["editnumber"];
            $editpass = $_POST["editpass"]; ?>
            <input type="hidden" name="editnumberhidden" value=<?php if (isset($_POST["editpass"])) {
                if ($editpass == $editkey) {
                    echo $editnumber;
                }
            } ?>> <?php
        }
    ?>
            <input type="submit" name="send" value="送信"><br><br>
            <input type="text" name="deletenumber" placeholder="削除番号">
            <input type="text" name="deletepass" placeholder="パスワード">
            <input type="submit" name="delete" value="削除"><br><br>
            <input type="text" name="editnumber" placeholder="編集番号">
            <input type="text" name="editpass" placeholder="パスワード">
            <input type="submit" name="edit" value="編集">
        </p>
    </form>
    <?php
$sql = 'SELECT * FROM yuji';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row) {
    echo $row["id"]."  ";
    echo $row["name"]."  ";
    echo $row["comment"]."  ";
    echo $row["updated_at"]."<br>";
    echo "<hr>";
}
    
$pdo = null;
?>

</body>

</html>