var map;
var localSearch = new GlocalSearch();
var nextLiveSearchSequence = 0;
var lastSequenceUsed = 0;

var icon = new GIcon();
icon.image = "http://www.google.com/mapfiles/marker.png";
icon.shadow = "http://www.google.com/mapfiles/shadow50.png";
icon.iconSize = new GSize(20, 34);
icon.shadowSize = new GSize(37, 34);
icon.iconAnchor = new GPoint(10, 34);

function showPostcodeOnMap()
{
	usePointFromPostcode($('postcode').value, placeMarkerAtPoint);
}

function showFurtherInfo(serviceID) 
{
	Element.toggle('service_info_' + serviceID);
	
	if ($('service_map_' + serviceID)) {
		Element.toggle('service_map_' + serviceID);
		$('toggle_map_link_' + serviceID).innerHTML = 'Show map';
	}
	
	if ($('related_forms_' + serviceID)) {
		Element.toggle('related_forms_' + serviceID);
	}

	Element.hide('map_' + serviceID);

	if ($("img_" + serviceID).src.indexOf('site/images/bllt_minus.gif') > 0) {
		$("img_" + serviceID).src = 'site/images/bllt_plus.gif';
	}
	else {
		$("img_" + serviceID).src = 'site/images/bllt_minus.gif';
	}
}

function createMapForPostcode(postcode, divID, callbackFunction)
{
	Element.toggle('map_' + divID);
	
	if (Element.visible('map_' + divID)) {
		
		$('toggle_map_link_' + divID).innerHTML = 'Hide map';

		localSearch.setSearchCompleteCallback(null, 
			function()
			{
				if (localSearch.results[0]) {
					var resultLat = localSearch.results[0].lat;
					var resultLng = localSearch.results[0].lng;
					var point = new GLatLng(resultLat, resultLng);
					callbackFunction('map_' + divID, point);
				}
				else {
					alert("Postcode not found!");
				}
			});

		localSearch.execute(postcode + ", UK");
	
	}
	else {
		$('toggle_map_link_' + divID).innerHTML = 'Show map';
	}
}

function placeMarkerAtPoint(point)
{
	var marker = new GMarker(point,icon);
	map.addOverlay(marker);
	setCenterToPoint(point);
}

function setCenterToPoint(point)
{
	map.setCenter(point, 17);
}

function showPointLatLng(point)
{
	alert("Latitude: " + point.lat() + "\nLongitude: " + point.lng());
}

function createMap(divID, startingPoint)
{
	if (GBrowserIsCompatible()) {

		map = new GMap2($(divID));

		map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
		map.setCenter(startingPoint, 17, G_NORMAL_MAP);
		placeMarkerAtPoint(startingPoint);
	}
}

function doLiveSearch (e)
{
	var searchText = $('searchText').value;
	
	if (searchText.length > 2) {

		var nonce = Math.floor(Math.random() * 100);

		nextLiveSearchSequence = nextLiveSearchSequence + 1;

		new Ajax.Request('/site/includes/az_search_results.php',
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
	if (document.getElementById('searchText')) {
		Event.observe('searchText', 'keyup', function(e) { doLiveSearch(e) });
	}
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
addUnLoadEvent(GUnload);
