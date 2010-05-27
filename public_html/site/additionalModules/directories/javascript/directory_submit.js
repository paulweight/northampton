window.onunload = function() {
    GUnload();
}

var markers = {};

function createMap (id)
{
    var mapID = 'map_' + id;
    var latLongInputID = 'latlong_' + id;
    var map = new GMap2($(mapID), {googleBarOptions:{suppressInitialResultSelection:true}});
    map.addControl(new GSmallMapControl());
    map.disableDoubleClickZoom();

    if ($(latLongInputID).value == '') {
        map.setCenter(new GLatLng(53.5664141528, -2.57080078125), 5);
    }
    else {
        var coords = $(latLongInputID).value.split(',');
        map.setCenter(new GLatLng(coords[0], coords[1]), 14);
    }

    map.enableGoogleBar();

    var marker = new GMarker(map.getCenter(), {draggable:true});
    map.addOverlay(marker);

    if ($(latLongInputID).value == '') {
        marker.hide();
    }

    markers[id] = marker;

    var removeLink = document.createElement('a');
    // //console.log(removeLink);
    removeLink.innerHTML = 'Remove marker';
    removeLink.id = id;
    removeLink.observe('click', function (event) {
            var element = Event.element(event);
            markers[id].closeInfoWindow();
            markers[id].hide();
            $(latLongInputID).value = '';
         }
    );

    GEvent.addListener(marker, "click", function() {
        marker.openInfoWindow(removeLink);
    });

    GEvent.addListener(marker, "dragend", function() {
         $(latLongInputID).value = marker.getLatLng().y + ',' + map.getCenter().x;
    });
    
    GEvent.addListener(map, "dblclick", function(overlay, point) {
        if (overlay) {
            map.removeOverlay(overlay);
        }
        marker.show();
        marker.setLatLng(point);
        $(latLongInputID).value = marker.getLatLng().y + ',' + map.getCenter().x;
    });
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

function toggleImageUpload(id)
{
    Element.toggle(id);
}

window.onload = createMaps;