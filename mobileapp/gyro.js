$(function() {
	window.addEventListener("devicemotion", function(e){
		var r = window.orientation; //端末の回転方向 -90, 0, 180, 90の何れかになる

		var x = e.accelerationIncludingGravity.x; //端末の横方向の傾き
		var y = e.accelerationIncludingGravity.y; //端末の縦方向の傾き
		var z = e.accelerationIncludingGravity.z; //端末の上下方向の傾き

		$("#result").html("回転角度" +r+ "<br>" + "x：" +x+ "<br>" +"y："　+y+ "<br>" + "z：" +z)
	}, false);
});