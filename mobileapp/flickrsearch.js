var displayPanel;

var searchBox, searchButton;

var server = "https://api.flickr.com/services/rest/"
var method = "?method=flickr.photos.search&per_page=10&text=";
var api_key = "0ab72d1caa48350815aa9311013d3dd7";

var message_notfound = "画像が見つかりませんでした。";
var message_typesomething ="検索語句を入力してください。";

var itemlist;

var timer, photoindex, viewsize;

var switchView, viewtype;


window.onload = appInit;

function appInit() {

	displayPanel = document.getElementById("displayPanel");
	searchBox = document.getElementById("searchBox");
	searchButton = document.getElementById("searchButton");
	searchButton.addEventListener("click", searchPhoto, false);
	window.onresize = resizeFlipView;

	document.onkeydown = flipPage;
	searchBox.addEventListener("keydown",
		function(){event.cancelBubble = true;}, false);

	switchView = document.getElementById("switchView");
	switchView.addEventListener("click", showThumbnails, false);
}


function searchPhoto(){

	var child;
	while(child = displayPanel.firstChild){
			displayPanel.removeChild(child);
	};

	var keyword = encodeURIComponent(searchBox.value.trim())
	if (keyword.length == 0) {
		displayPanel.textContent = message_typesomething;
		return;
	}

	var uri = server + method + keyword + "&api_key=" + api_key;
	itemlist = [];
	requestSearch(uri);
}


function showThumbnails() {

	switchView.style.visibility = "hidden";
	viewtype = 0;
	viewsize = null;
	photoindex = null;

	var child;
	while(child = displayPanel.firstChild){
		displayPanel.removeChild(child);
	};

	var classNames = {
		imageview : "thumbnailview",
		photobox : "thumbnailbox",
		photo : "thumbnail",
		textbox : "thumbnailtext"
	}

	for(var i =0; i< itemlist.length; i++){
		var item = itemlist[i];
		var urls = createUrls(item);

		var imageView = createImageView(
			item.title,
			urls.thumbUrl,
			urls.pageUrl,
			classNames
		);

		displayPanel.appendChild(imageView);

		var img = imageView.querySelector("img");
		img.setAttribute("onclick", "showPhotos(" + i +")");
	}
}


function createUrls(item){
	var baseUrl = "http://farm" + item.farm + ".staticflickr.com/"
		+ item.server + "/" + item.id + "_" + item.secret;

	var thumbUrl = baseUrl + "_q.jpg";
	var photoUrl = baseUrl + ".jpg";
	var pageUrl = "http://www.flickr.com/photos/" + item.owner + "/" + item.id;

	return{
		thumbUrl:thumbUrl,
		photoUrl:photoUrl,
		pageUrl:pageUrl
	}
}


function createImageView(title, src, pageUrl, classNames){

	var imageview = document.createElement("div");
	var photobox = document.createElement("div");
	var photo = new Image();
	var textbox = document.createElement("div");

	imageview.className = classNames.imageview;
	photobox.className = classNames.photobox;
	photo.className = classNames.photo;
	textbox.className = classNames.textbox;
	photo.src = src;

	imageview.appendChild(photobox);
	photobox.appendChild(photo);
	imageview.appendChild(textbox);

	var caption = document.createElement("div");
	var text = document.createTextNode(title);
	var link = document.createElement("a");

	link.target = "_blank";
	link.textContent ="詳細";
	link.href = pageUrl;

	caption.appendChild(text);
	caption.appendChild(document.createElement("br"));
	caption.appendChild(document.createTextNode(")"));
	caption.appendChild(link);
	caption.appendChild(document.createTextNode(")"));

	textbox.appendChild(caption);

	return imageview;
}



function showPhotos(index){

	switchView.style.visibility="visible";
	viewtype = 1;
	photoindex = index;

	var child;
	while(child = displayPanel.firstChild){
		displayPanel.removeChild(child);
	};

	var classNames = {
		imageview : "photoview",
		photobox : "photobox",
		photo : "photo",
		textbox : "phototext"
	}

	var flipview = document.createElement("div");
	var photolist = document.createElement("div");
	flipview.className = "flipview";
	photolist.className ="photolist";
	flipview.appendChild(photolist);
	displayPanel.appendChild(flipview);

	for (var i = 0; i < itemlist.length; i++){
		var item = itemlist[i];
		var urls = createUrls(item);

		var imageView = createImageView(
			item.title,
			urls.photoUrl,
			urls.pageUrl,
			classNames
		);

		photolist.appendChild(imageView);
	}

	flipview.onscroll = function(event){
		clearTimeout(timer);
		timer = setTimeout(doSnap, 200);
	}

	resizeFlipView();
}


function doSnap(){

	var flipview =document.querySelector("div.flipview");
	var w = flipview.offsetWidth;
	var pos = flipview.scrollLeft;
	var index = Math.round(pos / w);
	flipview.scrollLeft = index * w;
	photoindex = index;
	viewsize = w;
}


function resizeFlipView(){

	var flipview = document.querySelector("div.flipview");
	if(flipview == null) return;
	var w = flipview.offsetWidth;

	if(!photoindex){
		viewsize = w;
		photoindex = 0;
		return;
	}
	else if (viewsize == w) return;

	if (!viewsize) viewsize = w;

	var newpos = photoindex * w;
	flipview.scrollLeft = newpos;
	viewsize = w;
}


function flipPage(event){

	var flipview = document.querySelector("div.flipview");
	if (flipview == null) return;
	var keycode = event.keyCode;
	if(keycode != 37 && keycode != 39) return;

	var w = flipview.offsetWidth;
	var pos = flipview.scrossLeft;
	var index = Math.round(pos / w);

	switch(event.keyCode){

		case 37:
			flipview.scrollLeft = w * (index - 1);
			break;

		case 39:
			flipview.scrollLeft = w * (index + 1);
	}

	photoindex = index;
}