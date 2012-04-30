/**
* Add a new event to the window.onload
*
* @param function func	The new function to add to the loading
*/
function addLoadEvent(func)
{
	var oldonload = window.onload;
	if (typeof window.onload != 'function') {
		window.onload = func;
	}
	else {
		window.onload = function() {
			oldonload();
			func();
		}
	}
}
