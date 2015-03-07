function fixUrl(obj) {
	var url = $("#"+obj).val();
	if(url) {
		url = url.replace(/^(https?:\/\/)|\/$/gi,"");
		$("#"+obj).val(url);
	}
}

//Õœ∂Ø
function LinkDrag(dragid,ctrlid,leftmargin,topmargin) {
	var dragid=document.getElementById(dragid);
	var ctrlid=document.getElementById(ctrlid);
	dragid.style.position="absolute";
	if(!leftmargin) {
		var leftmargin=500;
	}
	if(!topmargin) {
		var topmargin=200;
	}
	if(!dragid.style.left) {
		dragid.style.left=leftmargin+"px";
	}
	if(!dragid.style.top) {
		dragid.style.top=topmargin+"px";
	}
	var posX="";
	var posY="";
	var DragZindexNumber=49;//Õœ∂Ø≤„Œª÷√
	ctrlid.onmousedown=function(e) {
		if(!e) e = window.event;
		dragid.style.zIndex=DragZindexNumber++;
		posX=e.clientX - parseInt(dragid.style.left);
		posY=e.clientY - parseInt(dragid.style.top);
		document.onmousemove=function(e) {
			if(!e) e = window.event;
			dragid.style.left = e.clientX - posX+"px";
			dragid.style.top  = e.clientY - posY+"px";
		}
	}
	ctrlid.onmouseup=function(e) {
		document.onmousemove = null;
	}
	document.onmouseup=function(e) {
		document.onmousemove = null;
	}
}