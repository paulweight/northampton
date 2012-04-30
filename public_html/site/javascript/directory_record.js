window.onunload = function() {
    GUnload();
}

function createMap (id)
{
    var mapID = 'map_' + id;
    var map = new GMap2(document.getElementById(mapID));
    map.addControl(new GSmallMapControl());
	map.addControl(new GMapTypeControl());

	// map centre
	var latLongInputID = 'map_marker_location_' + id;
    if (document.getElementById(latLongInputID).value == '') {
        map.setCenter(new GLatLng(53.5664141528, -2.57080078125), 5);
		return;
    }
    else {
        var coords = document.getElementById(latLongInputID).value.split(',');
        map.setCenter(new GLatLng(coords[0], coords[1]), 14);
    }

	// marker image
	var imageID = 'map_marker_image_' + id;
	var image = document.getElementById(imageID).value;

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
	if (document.getElementById(infoID).value != '') {
		var info = unescape(document.getElementById(infoID).value);
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
        var mapDivs = getElementsByClassAndTag('googleMap', 'div');
        for (var i = 0; i < mapDivs.length; i++) {
            var id = mapDivs[i].id.replace(/[a-zA-Z]+_/, "");
            createMap(id);
        }
    }
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

window.onload = createMaps;