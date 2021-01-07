<!DOCTYPE html>
<html lang="ja">
<head>
  <link rel="stylesheet" href="style.css">
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
    <!--<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>-->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDyPde0wIo5wjHOTsWrMSmuDaB30J8fW-w&libraries=&v=weekly" async defer>
    </script>
  <title>ダンススタジオ空室情報</title>
</head>
<body>
    <header class="page-header wrap">
        <div id="liner">
	        <h1 class="title">Search Dance Studio</h>
	    </div>
            <ul class="main-nav">
                <li><a href="news.php">What's This</a></li>
                <li><a href="insert_data.php">Input New Studio</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
    </header>
        <div class="home-content wrap">
            <form action="" method="post" class="form" >
                <div>最寄り駅を選択</div>
                    <select id="select" name="menu" class="menu" onchange="selectMap();centerSelector();return false" required>
                        <option value="選択してください">選択してください</option>
                        <option value="現在地" >現在地</option>
                        
                            <!--phpでoption内容を取得-->
                            <?php
                            $link = mysqli_connect('satou.naviiiva.work','naviiiva_user','samurai1234','satou');
                            if(mysqli_connect_errno()){
                                die("DBに接続できません！".mysqli_connect_errno()."¥n");
                            }
                            $query = "select distinct name from studio_place where type = 1";
                            if($result = mysqli_query($link,$query)){
                                foreach($result as $row){
                                    echo "<option value=".$row['name'].">".$row['name']."</option>";
                                }   
                            }
                            ?>
                        
                   </select>
            </form>
                <div class="map-reserveUrl-wrapper">
                    <!--google map表示場所-->
                        <div id="map"></div>
                    <!--予約画面表示場所-->
                        <div class="reserveUrl">
                            <iframe src="" class="frame site"></iframe>
                        </div>
                </div><!--.map-reserveUrl-wrapper-->
        </div><!--home-content wrap-->
<script>

      var map;
      var center = [];
      var point = [  //centerをphpで取得
            <?php
            
            $query = "select distinct name,lat,lng,url,type from studio_place ";
            
            //mysqlに接続
            $link = mysqli_connect('satou.naviiiva.work','naviiiva_user','samurai1234','satou');
            if(mysqli_connect_errno()){
                die("DBに接続できません！".mysqli_connect_errno()."¥n");
            }
            
            if($result = mysqli_query($link,$query)){
               
                    foreach($result as $key => $value){
                        
                         echo "{ name:'".$value['name'].
                               "',lat: ".$value['lat'].
                               ", lng: ".$value['lng'].
                               ", url: '".$value['url'].
                               "',type: ".$value['type']."},\n";
                             
                    }
            }//if($result = mysqli_query($link,$query))
            
            mysqli_close($link);
            ?>
      ]; //var point閉じ
      var marker = [];
      var infoWindow = [];
      var select = document.getElementById("select");
      
      //mapの非表示設定
      document.getElementById("map").style.visibility = "hidden";
      //siteの非表示設定
      document.getElementById("site").style.visibility = "hidden";
      
//mapをブラウザ上に表示させる
function selectMap(){  
     if(select.value != '選択してください'){
         document.getElementById("map").style.visibility="visible";
     }
}//selectMap()


//中心を選択する 
function centerSelector(){  
    if(select.value == '現在地'){
       
        window.navigator.geolocation.getCurrentPosition(
            function(position){ // 成功した時
                center = {lat: position.coords.latitude, lng: position.coords.longitude};
            },
            function(){ // 失敗した時
            }
        );
        
    }else{
        for(var i=0 ; i<point.length ; i++){
            if(select.value==point[i]['name']){
               center = {
                            lat: point[i]['lat'],
                            lng: point[i]['lng']
                        };
               console.log(center);
            }
        }
    }
   initMap();
}//centerSelector()

console.log(center);


if(center==0){//center debug
    console.log("no center");
}


 // 地図の作成
function initMap() {

   map = new google.maps.Map(document.getElementById('map'), { // #mapに地図を埋め込む
      center: center,// 地図の中心を指定
        zoom: 15 ,// 地図のズームを指定
        zoomControlOptions: {
         //LEFT_BOTTOMで左下に指定
        position: google.maps.ControlPosition.BOTTOM
        },
        mapTypeControl: false
   });
 　
 　
    
 
// マーカー毎の処理
//urlがあるものだけマーカーを立てる
    for (var i = 0; i < point.length; i++) {
        if(point[i]['url']!=''){
           var markerLatLng = new google.maps.LatLng({lat: point[i]['lat'], lng: point[i]['lng']}); // 緯度経度のデータ作成
            marker[i] = new google.maps.Marker({ // マーカーの追加
            position: markerLatLng, // マーカーを立てる位置を指定
                 map: map // マーカーを立てる地図を指定
           });
          
         infoWindow[i] = new google.maps.InfoWindow({ // 吹き出しの追加
             content: '<div id="studio">' +"<a href=" + point[i]['url'] + ">" + point[i]['name'] + "</a>" + '</div>' // 吹き出しに表示する内容
           });
        
         markerEvent(i); // マーカーにクリックイベントを追加
        }
    }
}//initMap
 
// マーカーにクリックイベントを追加
function markerEvent(i) {
    marker[i].addListener('click', function() { // マーカーをクリックしたとき
      infoWindow[i].open(map, marker[i]); // 吹き出しの表示
      document.getElementById('frame').src=point[i]['url'];
      document.getElementById('site').style.visibility="visible";
  });
}//markerEvent

</script>
<!--<div id="in_studio"><a href="insert_data.php">Input New Studio</a></div>-->
</body>
</html>