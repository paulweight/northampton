var currentLinkEdit = -1;
var widgetLinks = new Array();
var oldsave = $('saveWidgetProperty').onclick;

if (typeof $('saveWidgetProperty').onclick != 'function') {
	$('saveWidgetProperty').onclick = commitWidgetLinks;
}
else {
	$('saveWidgetProperty').onclick = function () {
		commitWidgetLinks();
		oldsave();
	}
}

// Load nav widget links into sub table
fetchLinks();
iterateLinks();

function addWidgetLink ()
{
	currentLinkEdit = -1;
	$('nav_link_title').value = '';
	$('nav_link').value = '';
	$('lb_widget_content').getElementsByTagName('tfoot')[0].style.display = '';
	$('nav_widget_links').style.display = 'none';
	$('widgetLinkDelete').style.display = 'none';
}

function editWidgetLink (widgetLinkID)
{
	currentLinkEdit = widgetLinkID * 1;
	$('nav_link_title').value = widgetLinks[currentLinkEdit][0];
	$('nav_link').value = widgetLinks[currentLinkEdit][1];
	$('lb_widget_content').getElementsByTagName('tfoot')[0].style.display = '';
	$('nav_widget_links').style.display = 'none';
	$('widgetLinkDelete').style.display = '';
}

function saveWidgetLink ()
{
	if ($('nav_link_title').value == '') {
		return false;
	}
	
	if (currentLinkEdit == -1) {
		widgetLinks.push(new Array($('nav_link_title').value, $('nav_link').value));
	}
	else {
		widgetLinks[currentLinkEdit][0] = $('nav_link_title').value;
		widgetLinks[currentLinkEdit][1] = $('nav_link').value;
		$('widgetLinkText' + currentLinkEdit).title = $('nav_link_title').value;
		$('widgetLinkText' + currentLinkEdit).innerHTML = $('nav_link_title').value;
	}

	if ( $('nav_widget_links').hasChildNodes() ) {
		while ( $('nav_widget_links').childNodes.length >= 1 ) {
			$('nav_widget_links').removeChild( $('nav_widget_links').firstChild );
		} 
	}
	iterateLinks();

	$('lb_widget_content').getElementsByTagName('tfoot')[0].style.display = 'none';
	$('nav_widget_links').style.display = '';
}

function deleteWidgetLink ()
{
	widgetLinks[currentLinkEdit] = -1;
	widgetLinks = widgetLinks.without(-1);

	$('widgetLinkText' + currentLinkEdit).parentNode.parentNode.parentNode.removeChild($('widgetLinkText' + currentLinkEdit).parentNode.parentNode);

	$('lb_widget_content').getElementsByTagName('tfoot')[0].style.display = 'none';
	$('nav_widget_links').style.display = '';

	// update ids of links after the one deleted
	for (var i = currentLinkEdit + 1; i <= widgetLinks.length; i++) {
		$('widgetLinkText' + i).id = 'widgetLinkText' + (i - 1);
		$('widgetLinkUpText' + i).id = 'widgetLinkUpText' + (i - 1);
		$('widgetLinkDownText' + i).id = 'widgetLinkDownText' + (i - 1);
	}
}


function addLinkRow (linkID, linkObj)
{
	var tr = document.createElement('tr');
	var td = document.createElement('td');
	
	td.className = 'generic_row_link';
	
	var aLink = document.createElement('a');
	aLink.id = 'widgetLinkText' + linkID;
	aLink.href = '#';
	aLink.onclick = function ()
	{
		editWidgetLink(this.id.replace(/widgetLinkText/gi, ''));
		return false;
	}
	aLink.innerHTML = linkObj[0];
	aLink.title = linkObj[0];
	
	
	
	td.appendChild(aLink);
	tr.appendChild(td);
	
	td = document.createElement('td');
	td.className = 'position_down';
	
	if (linkID != (widgetLinks.length-1)) {
		var moveButton = document.createElement('a');
		moveButton.href = '#';
		moveButton.id = 'widgetLinkDownText' + linkID;
		moveButton.onclick = function ()
		{
			moveDown(this.id.replace(/widgetLinkDownText/gi, ''));
			return false;
		}

		var moveSpan = document.createElement('span');
		moveSpan.innerHTML = 'Move down';
		moveButton.appendChild(moveSpan);
		td.appendChild(moveButton);
	}

	tr.appendChild(td);
	
	td = document.createElement('td');
	td.className = 'position_up';
	
	if (linkID != 0) {
		moveButton = document.createElement('a');
		moveButton.href = '#';
		moveButton.id = 'widgetLinkUpText' + linkID;
		moveButton.onclick = function ()
		{
			moveUp(this.id.replace(/widgetLinkUpText/gi, ''));
			return false;
		}

		moveSpan = document.createElement('span');
		moveSpan.innerHTML = 'Move up';
		moveButton.appendChild(moveSpan);
		td.appendChild(moveButton);
	}

	tr.appendChild(td);
	
	$('nav_widget_links').appendChild(tr);
}


function iterateLinks ()
{
	for (var i = 0; i < widgetLinks.length; i++) {
		addLinkRow(i, widgetLinks[i]);
	}
}


function fetchLinks ()
{
	var tempLinks = new Array();
	var tempTitles = new Array();

	var tempLink = '';
	var linkIndex = 0;

	widgetLinks.clear();
   
	//sort the links
	for (var wLink in widgetItems[activeWidget].settings) {
		if (wLink.indexOf('link') >= 0 && wLink.indexOf('url') >= 0) {
			linkIndex = wLink.replace(/url/gi, '');
			linkIndex = linkIndex.replace(/link/gi, '');
			tempLinks[linkIndex] = wLink;
		}
	}
	
	for (j = 0; j < tempLinks.length; j++) {
		widgetLinks.push(new Array(widgetItems[activeWidget].settings[tempLinks[j].replace(/url/gi, 'title')], widgetItems[activeWidget].settings[tempLinks[j]]));
	}
}


function commitWidgetLinks ()
{
	widgetItems[activeWidget].settings = new Object();
	widgetItems[activeWidget].settings['nav_widget_title'] = $('nav_widget_title').value;
	
	for (var i = 0; i < widgetLinks.length; i++) {
		widgetItems[activeWidget].settings['link' + i + 'url'] = widgetLinks[i][1];
		widgetItems[activeWidget].settings['link' + i + 'title'] = widgetLinks[i][0];
	}
	
	$('nav_link').parentNode.removeChild($('nav_link'));
	$('nav_link_title').parentNode.removeChild($('nav_link_title'));
}


function moveUp (widgetLinkID)
{
	var tempLink = null;

	if (widgetLinkID > 0) {
		tempLink = widgetLinks[widgetLinkID - 1];
		widgetLinks[widgetLinkID - 1] = widgetLinks[widgetLinkID];
		widgetLinks[widgetLinkID] = tempLink;
		if ( $('nav_widget_links').hasChildNodes() ) {
			while ( $('nav_widget_links').childNodes.length >= 1 ) {
				$('nav_widget_links').removeChild( $('nav_widget_links').firstChild );		 
			} 
		}
		iterateLinks();
	}
}


function moveDown (widgetLinkID)
{
	var tempLink = null;
 
	widgetLinkID = parseInt(widgetLinkID);
	
	if (widgetLinkID < widgetLinks.length - 1) {
		tempLink = widgetLinks[widgetLinkID + 1];
		widgetLinks[widgetLinkID + 1] = widgetLinks[widgetLinkID];
		widgetLinks[widgetLinkID] = tempLink;
		if ($('nav_widget_links').hasChildNodes()) {
			while ($('nav_widget_links').childNodes.length >= 1) {
				$('nav_widget_links').removeChild($('nav_widget_links').firstChild);
		 	} 
		}
		iterateLinks();
	}
}