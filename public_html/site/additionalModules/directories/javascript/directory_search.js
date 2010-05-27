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
    var map = new GMap2($(mapID));
    map.addControl(new GSmallMapControl());
	map.addControl(new GMapTypeControl());
	map.setCenter(new GLatLng(0,0),0);

	var bounds = new GLatLngBounds();

	var markers = document.getElementsByClassName('mapMarkers');

	for (var i = 0; i < markers.length; i++) {
		var id = markers[i].id;
		var markerLocation = markers[i].value;
		if (markerLocation != '') {
			var coords = markerLocation.split(',');
			var point = new GLatLng(coords[0], coords[1])
			bounds.extend(point);

			var imageID = 'map_marker_image_' + id;
			var image = $(imageID).value;

			var infoID = 'map_marker_info_' + id;
			var info = $(infoID).value;

			createMarker(map, point, image, info);
		}
	}

	map.setZoom(map.getBoundsZoomLevel(bounds));
	map.setCenter(bounds.getCenter());
}

window.onload = createMap;