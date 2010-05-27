window.onunload = function() {
    GUnload();
}

function createMap (id)
{
    var mapID = 'map_' + id;
    var map = new GMap2($(mapID));
    map.addControl(new GSmallMapControl());
	map.addControl(new GMapTypeControl());

	// map centre
	var latLongInputID = 'map_marker_location_' + id;
    if ($(latLongInputID).value == '') {
        map.setCenter(new GLatLng(53.5664141528, -2.57080078125), 5);
		return;
    }
    else {
        var coords = $(latLongInputID).value.split(',');
        map.setCenter(new GLatLng(coords[0], coords[1]), 14);
    }

	// marker image
	var imageID = 'map_marker_image_' + id;
	var image = $(imageID).value;

    var baseIcon = new GIcon();
    baseIcon.shadow = image.replace('.png', '_shadow.png');
    baseIcon.iconSize = new GSize(32, 32);
    baseIcon.shadowSize = new GSize(59, 32);
    baseIcon.iconAnchor = new GPoint(8, 28);
    baseIcon.infoWindowAnchor = new GPoint(9, 2);
    baseIcon.infoShadowAnchor = new GPoint(16, 20);

    var markerIcon = new GIcon(baseIcon);
    markerIcon.image = image;

	var marker = new GMarker(map.getCenter(), markerIcon);

	// marker info
	var infoID = 'map_marker_info_' + id;
	if ($(infoID).value != '') {
		var info = unescape($(infoID).value);
		info = info.replace(/\+/g, ' ');
		GEvent.addListener(marker, "click", function() {
        	marker.openInfoWindowHtml(info);
      	});
	}

    map.addOverlay(marker);
}

function createMaps ()
{
    if (GBrowserIsCompatible()) {
        var mapDivs = document.getElementsByClassName('googleMap');
        for (var i = 0; i < mapDivs.length; i++) {
            var id = mapDivs[i].id.replace(/[a-zA-Z]+_/, "");
            createMap(id);
        }
    }
}

window.onload = createMaps;