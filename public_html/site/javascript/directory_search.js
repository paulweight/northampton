window.onunload = function() {
    GUnload();
}

function createMarker(map, point, image, info)
{
    var baseIcon = new GIcon();
    baseIcon.shadow = image.replace('.png', '_shadow.png');
    baseIcon.iconSize = new GSize(32, 32);
    baseIcon.shadowSize = new GSize(59, 32);
    baseIcon.iconAnchor = new GPoint(8, 28);
    baseIcon.infoWindowAnchor = new GPoint(9, 2);
    baseIcon.infoShadowAnchor = new GPoint(16, 20);

    var markerIcon = new GIcon(baseIcon);
    markerIcon.image = image;

	var marker = new GMarker(point, markerIcon);

	// marker info
	if (info != '') {
		var info = unescape(info);
		info = info.replace(/\+/g, ' ');
		GEvent.addListener(marker, "click", function() {
        	marker.openInfoWindowHtml(info);
      	});
	}

    map.addOverlay(marker);
}

function createMap()
{
	if (!GBrowserIsCompatible()) {
		return;
	}

    var mapID = 'googleMap';
    var map = new GMap2(document.getElementById(mapID));
    map.addControl(new GSmallMapControl());
	map.addControl(new GMapTypeControl());
	map.setCenter(new GLatLng(0,0),0);

	var bounds = new GLatLngBounds();

	var markers = getElementsByClassAndTag('mapMarkers', 'input');
	

	for (var i = 0; i < markers.length; i++) {
		var id = markers[i].id;
		var markerLocation = markers[i].value;
		if (markerLocation != '') {
			var coords = markerLocation.split(',');
			var point = new GLatLng(coords[0], coords[1])
			bounds.extend(point);

			var imageID = 'map_marker_image_' + id;
			var image = document.getElementById(imageID).value;

			var infoID = 'map_marker_info_' + id;
			var info = document.getElementById(infoID).value;

			createMarker(map, point, image, info);
		}
	}

	if (map.getBoundsZoomLevel(bounds) <= 17) {
		map.setZoom(map.getBoundsZoomLevel(bounds));
	}
	else {
		map.setZoom(17);
	}
	map.setCenter(bounds.getCenter());
}

function getElementsByClassAndTag (className, tagname) {
	var elements = document.getElementsByTagName (tagname);
	
	var returnedElements = new Array;
	
	for (i = 0; i < elements.length; i++) {
		if (elements[i].className == className) {
			returnedElements.push(elements[i]);
		}
	}
	
	return returnedElements;
}

window.onload = createMap;