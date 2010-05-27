//	The currently selected top level item in the track search
var selectedTrack = "";
//	The currently active tab
var currentTab = "focus_on";



/*
*	
*/
function showSite(sitenum)
{
	$('prevBlog').style.display = "block";
	if(sitenum == 0) sitenum = 1;
	Effect.BlindUp('homepage');
	$('wrapper').style.display = "none";
	$('body').style.background = "url(<?php print RUPA_HOME_URL; ?>images/site"+sitenum+") top center no-repeat";
	if(sitenum == 1) $('body').style.backgroundColor = "#AABBAA";
	if(sitenum == 2) $('body').style.backgroundColor = "#000000";
	if(sitenum == 3) $('body').style.backgroundColor = "#3E76B1";
	if(sitenum == 4) $('body').style.backgroundColor = "#113355";
	if(sitenum == 5) $('body').style.backgroundColor = "#BBCCAA";
}


/*
*	
*/
function showConnect()
{
	$('body').style.background = "";
	Effect.Appear('wrapper');
	$('prevBlog').style.display = "none";
}


/*
*	
*/
function selectTab(tabid)
{
	if(tabid != "current") {
		$('current').id = currentTab;
		$(currentTab + "Div").style.display = "none";
		currentTab = tabid;
		$(tabid).id = "current";
		$(tabid + "Div").style.display = "";
	}
	//otherwise current tab does not need to be changed
}



/*
*	Open the track search, with the "val" item selected
*/
function openTrack(val)
{
	if(val == "") {
		var value = document.getElementById('selectCollection');
		value = value.options[value.selectedIndex].value;
	} else {
		value = val;
	}
	if(document.getElementById('track_'+selectedTrack) && selectedTrack != "all") {
		document.getElementById('track_'+selectedTrack).style.display = "none";
	}
	selectedTrack = value;
	if(selectedTrack != "all") {
		document.getElementById('track_'+selectedTrack).style.display = "block";
	}
	else {
	}
	document.getElementById('topLevel').style.display = "block";
}



/*
*	If top level category checked the (de-)select all sub folders
*	Otherwise just select the checkbox
*
*	String elem		The id of the element to check
*	Int id			The id of the option
*/
function clickCheck(elem, id)
{
	var newVal = false;
	if(!document.getElementById(elem).checked) {
		newVal = true;
	}
	if(id != "") {
		var i = 1;
		while(true) {
			if(document.getElementById('innerOption' + id + i)) {
				document.getElementById('innerOption' + id + i).checked = newVal;
				i++;
			}
			else {
				break;
			}
		}
		document.getElementById(elem).checked = !document.getElementById(elem).checked;
	}
}

/************* RICH ADDED THESE - ARE THE ABOVE NEEDED? ****************/

/**
* Open and close the preferences pane.
*/
function togglePreferences()
{
	Effect.toggle('trackrefine', 'blind');
	
	if ($('PrefsLink').className == 'arrw_down') {
		$('PrefsLink').className = 'arrw_up';
	}
	else {
		$('PrefsLink').className = 'arrw_down';
	}
}

/**
* A top level collection checkbox has been ticked. If the checkbox is selected
* show the child collections. If the checkbox has been unselected hide the
* child collection.
*
* @param link link The checkbox that has been clicked.
* @param integer collection The id of the collection to show/hide.
*/
function selectTopLevelCollection(link, collectionID)
{
	if (link.className == 'unselected') {

		link.className = 'selected';
		$('children_of_' + collectionID).style.display = '';
		//new Ajax.Updater('children_of_' + collectionID, '/rupa/rupa/scripts/getChildCollections.php?collectionID=' + collectionID, {asynchronous:true});
	}
	else {

		link.className = 'unselected';
		$('children_of_' + collectionID).style.display = 'none';
	}
}

/**
* Select/deselect all children checkboxes for a particular parent.
*
* @param checkbox checkbox The checkbox that has been clicked.
* @param integer parentCollectionID The id of the collection check/uncheck all children of.
*/
function selectAllChildCollections(checkbox, parentCollectionID)
{
	childCheckboxes = document.getElementsByClassName('child_of_' + parentCollectionID);

	for (var i = 0; i < childCheckboxes.length; i++) {

		childCheckboxes[i].checked = checkbox.checked;
	}
}

/**
* Save the selected preferences as the users defaults.
*
* Gets all of the checkboxes on the form, notes the ticked ones and makes
* a http request to do the saving.
*/
function saveCollectionPreferences()
{
	collectionCheckboxes = document.getElementsByClassName('collection');

	query = '';

	for (var i = 0; i < collectionCheckboxes.length; i++) {
		if (collectionCheckboxes[i].checked) {
			query = query + '&collections[]=' + collectionCheckboxes[i].value;
		}
	}
	
	query = query + '&search_type=' + $('search_type').value;

	requestOptions = {
		method:'post', 
		postBody:query.substring(1),
		onSuccess: function(t) {
			$('savePreferencesMessage').innerHTML = 'Your preferences have been saved';

			Element.show('savePreferencesMessage');
	    },
		onFailure: function(t) {
			$('savePreferencesMessage').innerHTML = 'There was a problem saving your preferences';
        	Element.show('savePreferencesMessage');
	    }
	}
	
	new Ajax.Request('/rupa/scripts/saveCollectionPreferences.php?', requestOptions);
}



    function showWebResults (div_id, url) {
    	if ($('searchResults').className == 'full_screen') {
			Effect.toggle($('searchKey'), 'blind', { afterFinish: function(){
		    	$('searchResults').className = 'half_screen';
				Effect.toggle($(div_id), 'blind');
			}});


			//$(div_id+'_frame').style.display = 'block';
        //$('searchKey').style.display = 'none';

	    }
	    else {
		//	//$(div_id+'_frame').style.display = 'none';
        //$('searchKey').style.display = 'block';
			Effect.toggle($(div_id), 'blind', { afterFinish: function(){
				Effect.toggle($('searchKey'), 'blind');
			    $('searchResults').className = 'full_screen';

			}});
	    }
    

		
		if($(div_id+'_frame').src != url){
			$(div_id+'_frame').src = url;
		}

        web_current = div_id;

    }

