if (typeof (scriptFileExtension) == "undefined") {
    var scriptFileExtension = 'php';
}

function initLiveXFormsSearch ()
{
    var search = new LiveSearch('xforms_searchText', 'xforms_search_results', '/site/includes/xforms_search_results.' + scriptFileExtension, {
		'frequency': 0.4,
		'loadingIndicator': 'xforms_loading'
	});
}

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

function addUnLoadEvent(func)
{
	var oldonunload = window.onunload;
	if (typeof window.onunload != 'function') {
		window.onunload = func;
	}
	else {
		window.onunload = function() {
			oldonunload();
			func();
		}
	}
}

addLoadEvent(initLiveXFormsSearch);
