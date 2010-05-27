var nextLiveSearchSequence = 0;
var lastSequenceUsed = 0;

function doLiveSearch (e)
{
	var searchText = $('searchText').value;
	
	if (searchText.length > 2) {

		var nonce = Math.floor(Math.random() * 100);

		nextLiveSearchSequence = nextLiveSearchSequence + 1;

		new Ajax.Request('/site/includes/xforms_search_results.php',
						 {
							parameters:'searchText=' + searchText + '&nonce=' + nonce + '&seq=' + nextLiveSearchSequence,
							method:'post',
							onSuccess:updateSearchResults
						 }
						);
					
		Element.show('loading');
	}
	else {
		$('search_results').innerHTML = '';
	}
}

function updateSearchResults (response)
{
	Element.hide('loading');
	
	var responseFields = response.responseText.split('|');

	var sequenceNumber = parseInt(responseFields[1]);

	if (sequenceNumber >= lastSequenceUsed) {
		$('search_results').innerHTML = responseFields[0];
		lastSequenceUsed = sequenceNumber;
	}
}

function initLiveSearch ()
{
	Event.observe('searchText', 'keyup', function(e) { doLiveSearch(e) });
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

addLoadEvent(initLiveSearch);
