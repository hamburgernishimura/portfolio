var story, contextBG, contextText, canvasW, canvasH, pointer;
var endFlag = false;
$(function(){
	//シナリオデータを読み込む
	$.get("./senario.txt", null, function(data){
		story = data.split(String.fromCharCode(10));
		pointer = 0;
		nsFunc.main();
	});
	//Canvasを初期化し塗りつぶす
	var canvasBgObj = document.getElementById("bgCanvas");
	canvasW = canvasBgObj.width;
	canvasH = canvasBgObj.height;
	contextBG = canvasBgObj.getContext("2d");
	var canvasTextObj = document.getElementById("textCanvas");
	contextText = canvasTextObj.getContext("2d");
	nsFunc.clearText();
	$("#textCanvas").bind("tap", nsFunc.main);
});
var nsFunc = {
	//解析する
	main : function(){
		if (endFlag == true){ return; }
		var linePointer = 720;
		nsFunc.clearText();
		while(true) {
			var text = story[pointer];
			if (text.indexOf("#end") > -1){ endFlag = true; return; }
			pointer++;
			if(text.charCodeAt(0) < 32){ continue; }
			if(text.indexOf("#bgm") > -1){nsFunc.bgm(text); continue;}
			if(text.indexOf("#wait") > -1){return;}
			if(text.indexOf("#img") > -1 ){nsFunc.image(text); continue;}
			contextText.fillStyle = "white";
			contextText.font = "14px bold";
			contextText.fillText(text, 10, linePointer, 590);
			linePointer = linePointer + 18;//行間隔は18ピクセル
		}
	},
	//文字表示用のCanvasをクリア
	clearText: function(){
		contextText.save();
		contextText.clearRect(0,0, canvasW, canvasH);
		contextText.fillStyle = "black";
		contextText.globalAlpha = 0.65;
		contextText.fillRect(0, 700, canvasW, 100);
		contextText.restore();
	},
	//指定された画像を表示
	image : function(text) {
		var data = text.split(" "); //空白区切り
		var imageObj = new Image();
		imageObj.src = data[1]; //２番目が画像のＵＲＬ
		imageObj.onload = function(){
			contextBG.drawImage(this, 0, 0);
		}
	},
	//指定された曲を演奏
	bgm : function(text){
		var data = text.split(" "); //空白区切り
		var audioObj = new Audio(data[1]); //２番目がBGMのURL
		audioObj.play();
	}
}