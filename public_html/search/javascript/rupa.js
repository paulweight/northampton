/**
* Select/deselect all children checkboxes for a particular parent.
*
* @param checkbox checkbox The checkbox that has been clicked.
*/
function selectAllChildCollections(checkbox)
{
	var collections = document.getElementById('collections').getElementsByTagName('input');
	for(var i = 0; i < collections.length; i++) {
		if (collections[i].type == 'checkbox') {
			if (checkbox.checked) {
				collections[i].checked = true;	
			}
			else {
				collections[i].checked = false;
			}
		}
	}
}

function unselectSelectAll(checkbox) {
	if (checkbox.checked == false) {
		document.getElementById('selectAll').checked = false;
	}
}