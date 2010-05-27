var aLinks = new Array();

// generic window.onload function
function addLoadEvent(func) {
  var oldonload = window.onload;
  if (typeof window.onload != 'function') {
    window.onload = func;
  } else {
    window.onload = function() {
      oldonload();
      func();
    }
  }
}

// Attach onclick events
function attachEvents() {
	aLinks = document.getElementById('colorselections').getElementsByTagName('label');
	for(var i = 0; i < aLinks.length; i++) {
		aLinks[i].onclick = function ()
		{
			var is_ie = ((navigator.userAgent.toLowerCase().indexOf("msie") != -1) && (navigator.userAgent.toLowerCase().indexOf("opera") == -1));
			if(!is_ie) {
				document.getElementById(this.getAttribute('for')).setAttribute('checked', 'checked');
			}
			return true;
		}
	}
}
addLoadEvent(attachEvents);