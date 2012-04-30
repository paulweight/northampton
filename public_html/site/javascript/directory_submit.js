function createMap (id)
{
    var mapID = 'map_' + id;
    var latLongInputID = 'latlong_' + id;
    var map = new GMap2(document.getElementById(mapID), {googleBarOptions:{suppressInitialResultSelection:true}});
    map.addControl(new GSmallMapControl());
    map.disableDoubleClickZoom();

    if (document.getElementById(latLongInputID).value == '') {
        map.setCenter(new GLatLng(53.5664141528, -2.57080078125), 5);
    }
    else {
        var coords = document.getElementById(latLongInputID).value.split(',');
        map.setCenter(new GLatLng(coords[0], coords[1]), 14);
    }

    map.enableGoogleBar();

    var marker = new GMarker(map.getCenter(), {draggable:true});
    
    map.addOverlay(marker);

    markers[id] = marker;

    var removeLink = document.createElement('a');
    	
    removeLink.innerHTML = 'Remove marker';
    removeLink.id = id;
    
   	GEvent.addListener(removeLink, 'click', function () {
            markers[id].closeInfoWindow();
            markers[id].style.display = 'none';
            document.getElementById(latLongInputID).value = '';
         }
    );

    GEvent.addListener(marker, "click", function() {
        marker.openInfoWindow(removeLink);
    });

    GEvent.addListener(marker, "dragend", function() {
         document.getElementById(latLongInputID).value = marker.getLatLng().y + ',' + map.getCenter().x;
    });
    
    GEvent.addListener(map, "dblclick", function(overlay, point) {
        if (overlay) {
            map.removeOverlay(overlay);
        }
        marker.setLatLng(point);
        document.getElementById(latLongInputID).value = marker.getLatLng().y + ',' + map.getCenter().x;
    });
}

function createMaps ()
{
	if (typeof GBrowserIsCompatible == 'function') { 
	    if (GBrowserIsCompatible()) {
	        var mapDivs = getElementsByClassAndTag('googleMap', 'div');
	        for (var i = 0; i < mapDivs.length; i++) {
	            var id = mapDivs[i].id.replace(/[a-zA-Z]+_/, "");
	            createMap(id);
	        }
	    }
	}
}

function toggleImageUpload(id)
{
	if (document.getElementById(id).style.display == "none") {
		document.getElementById(id).style.display = "block";
	}
	else {
		document.getElementById(id).style.display = "none";
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

var markers = {};

window.onunload = function() {
	if (typeof GUnload == 'function') {	
		GUnload();
	}
}

window.onload = createMaps;