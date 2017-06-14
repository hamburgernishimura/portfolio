$(function(){
	//位置情報を表示するページの処理
	$("#position").bind("pageshow", function(evt){
		getPos();
	});
	//位置情報を取得する処理
	function getPos(){
		navigator.geolocation.watchPosition(
			//位置情報の取得に成功したときの処理
			function(position){
				var lat = position.coords.latitude;
				var lon = position.coords.longitude;
				var alt = position.coords.altitude;
				var txt = "緯度：" +lat+"<br>経度："+lon+"<br>高度："+alt;
				$("#geoData").html(txt);
				setMarker(lat, lon); //地図に現在地を表示
			},
			//位置情報の取得に失敗した場合の処理
			function(error){
				$("#geoData").text("エラー："+error.code);
			},
			//位置情報オプション
			{
				enableHighAccuracy:true,
				maximumAge:5*100,
				timeout:10*100
			}
		);
	}
	//地図を表示するページの処理
	var map,marker;
	$("#map").bind("pageshow", function(evt){
		//地図を表示
		map = new google.maps.Map(
			document.getElementById("gmap"),{
				zoom :8,
				center : new google.maps.LatLng(0, 0),
				mapTypeId : google.maps.MapTypeId.ROADMAP
			}
		);
		//マーカーを表示
		marker = new google.maps.Marker({
			position: new google.maps.LatLng(0, 0),
			map:map
		});
		//現在地を取得する関数を呼び出す
		getPos();
	});
	//地図上に現在地を表示
	function setMarker(lat, lon){
		var pos = new google.maps.LatLng(lat, lon);
		map.setCenter(pos);
		marker.setPosition(pos);
	}
});