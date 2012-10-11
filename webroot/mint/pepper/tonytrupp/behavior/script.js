if (!Mint.TT) { Mint.TT = new Object(); }
Mint.TT.behavior = {
	getURL	: function(eventName,ajaxURL){
		  w=window;
		  var sourceURL = (w.decodeURI)?w.decodeURI(document.URL):document.URL;
		  url=this.API_URL+"?eventName="+escape(eventName)+"&sourceURL="+escape(sourceURL);
		  if(ajaxURL) url+="&ajaxURL="+escape(ajaxURL);
		  return url;
	},
	record	: function(eventName,ajaxURL){
		  url=this.getURL(eventName,ajaxURL);
		  w=window;
		  if(w.XMLHttpRequest)r=new XMLHttpRequest();
		  else if(w.ActiveXObject)r=new ActiveXObject("Microsoft.XMLHTTP");
		  if(r){
			r.open("GET", url, true);
		  	r.send();
		  }
	},	
	API_URL: null
};