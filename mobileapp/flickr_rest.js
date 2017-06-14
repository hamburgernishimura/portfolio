function requestSearch(url){
	var xhr = new XMLHttpRequest();
	xhr.onreadystatechange = readyStateChange;
	xhr.open('GET', url, true);
	xhr.send(null);
}


function readyStateChange(event) {

	var xhr = event.target;
	var xml = null;

	if(xhr.readyState == 4 && xhr.status == 200) {

		xml = xhr.responseXML;
		if (xml != null) getResults(xml);
	}
}


function getResults(xml) {

	var items = xml.querySelectorAll("photo");

	if(items.length == 0){
		displayPanel.textContent = message_notfound;
		return;
	};

	for(var i = 0; i < items.length; i++){
		var item = getItemData(items.item(i));
		itemlist.push(item);
	}

	showPhotos();
//	showThumbnails();
}


function getItemData(item){

	var title = item.getAttribute("title");
	var farm = item.getAttribute("farm");
	var server = item.getAttribute("server");
	var id = item.getAttribute("id");
	var secret = item.getAttribute("secret");
	var owner = item.getAttribute("owner");

	return {
		title:title,
		farm:farm,
		server:server,
		id:id,
		secret:secret,
		owner:owner,
	}
}