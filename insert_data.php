<!DOCTYPE html>
<html lang="ja">
<head>
    <title>スタジオデータ挿入</title>
  <link rel="stylesheet" href="style.css">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
</head>
  <body>
    <div id="">
    <p>データ入力</p>
    <form action="insert_data.php" method="post">
        <p><input type="text" name="name" placeholder="地点名"></p>
        <p><input type="text" name="lat" placeholder="緯度" requierd></p>
        <p><input type="text" name="lng" placeholder="経度" requierd></p>
        <p><input type="text" name="url" placeholder="ダンススタジオサイトURL"></p>
        <p>地図の中心?<br>
        <label>Yes:<input type="radio" name="type" value="1" requierd></label>
        <label>No:<input type="radio" name="type" value="0" requierd></label></p>
        <p><input type="submit" name="submit" value="入力する"></p>
    </form>
    </div>
<?php
if($_SERVER['REQUEST_METHOD']=='POST'){

$name = $_POST['name'];
$lat = $_POST['lat'];
$lng = $_POST['lng'];
$url = $_POST['url'];
$type = $_POST['type'];

$query = "insert into studio_place (
    name,
    lat,
    lng,
    url,
    type
    )
    value (
        '$name',
        '$lat',
        '$lng',
        '$url',
        '$type'
        )";
        
$link = mysqli_connect('naviiiva.work','naviiiva_user','samurai1234','satou');

if(mysqli_connect_errno()){
    die("DBに接続できません！".mysqli_connect_errno()."¥n");
}

if($result = mysqli_query($link,$query)){
    if (is_bool($result)) { 
      if ($result) {
          echo "登録しました。"."<br>";
      } else {
          echo "失敗しました。入力内容に不備があります。";
      }
    } else {
    }
} else {
  echo "<p>失敗しました。管理者に問い合わせてください。</p>";
  echo $query;
  
}

mysqli_close($link);
    
}
?>
</body>
</html>