var map;
var localSearch = new GlocalSearch();

if (typeof(scriptFileExtension) == "undefined") {
	var scriptFileExtension = 'php';
}

var icon = new GIcon();
icon.image = "https://www.google.com/mapfiles/marker.png";
icon.shadow = "https://www.google.com/mapfiles/shadow50.png";
icon.iconSize = new GSize(20, 34);
icon.shadowSize = new GSize(37, 34);
icon.iconAnchor = new GPoint(10, 34);

function toggleElement (element) {
	var item = document.getElementById(element);
	
	if (item.style.display == 'none') {
		item.style.display = 'block';
	}
	else {
		item.style.display = 'none';
	}
}

function visibleElement (element) {
	var item = document.getElementById(element);
	
	if (item.style.display == 'none') {
		return false;
	}
	else {
		return true;
	}

}

function showPostcodeOnMap()
{
	usePointFromPostcode(document.getElementById('postcode').value, placeMarkerAtPoint);
}

function showFurtherInfo(serviceID) 
{
	if (document.getElementById('service_info_' + serviceID)) {
		toggleElement('service_info_' + serviceID);
	}
	
	if (document.getElementById('service_map_' + serviceID)) {
		toggleElement('service_map_' + serviceID);
		document.getElementById('toggle_map_link_' + serviceID).innerHTML = 'Show map';
	}
	
	if (document.getElementById('related_forms_' + serviceID)) {
		toggleElement('related_forms_' + serviceID);
	}
	
	if (document.getElementById('map_' + serviceID)) {
		document.getElementById('map_' + serviceID).style.display = 'none';
	}

	if (document.getElementById("img_" + serviceID).src.indexOf('site/images/bllt_minus.gif') > 0) {
		document.getElementById("img_" + serviceID).src = 'site/images/bllt_plus.gif';
	}
	else {
		document.getElementById("img_" + serviceID).src = 'site/images/bllt_minus.gif';
	}
}

function createMapForPostcode(postcode, divID, callbackFunction)
{
	toggleElement('map_' + divID);
	
	if (visibleElement('map_' + divID)) {
		
		document.getElementById('toggle_map_link_' + divID).innerHTML = 'Hide map';

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
					document.getElementById('map_' + divID).innerHTML = 'Sorry! The map for postcode ' + postcode + ' could not be found.';
				}
			});

		localSearch.execute(postcode + ", UK");
	
	}
	else {
		document.getElementById('toggle_map_link_' + divID).innerHTML = 'Show map';
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

		map = new GMap2(document.getElementById(divID));

		map.addControl(new GLargeMapControl());
		map.addControl(new GMapTypeControl());
		map.setCenter(startingPoint, 17, G_NORMAL_MAP);
		placeMarkerAtPoint(startingPoint);
	}
}

function initLiveSearch ()
{
	var search = new LiveSearch('searchText', 'search_results', '/site/includes/az_search_results.' + scriptFileExtension, {
		'frequency': 0.4,
		'loadingIndicator': 'loading'
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

addLoadEvent(initLiveSearch);
addUnLoadEvent(GUnload);