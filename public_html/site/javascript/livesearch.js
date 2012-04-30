function LiveSearch (searchElement, resultElement, url, options) {
	
	this.searchElement = document.getElementById(searchElement);
	this.resultElement = document.getElementById(resultElement);
	this.url = url;
	this.options = options;
	
	
	if (!this.options.minChars) {
		this.options.minChars = 3;
	}
	
	if (!this.options.frequency) {
		this.options.frequency = 0.4;
	}	
	
	var search = this;
	
	this.fetchResults = function() {
	
		if (this.searchElement.value.length >= this.options.minChars) {

			
			if (this.options.loadingIndicator) {
				document.getElementById(this.options.loadingIndicator).style.display = 'inline';
			}
		
			var ajax = new nonLibraryAjax(this.url, 'searchText=' + this.searchElement.value, search);
			ajax.Request();
		}
	}
	
	this.updateResults = function(transport) {
		if (this.options.loadingIndicator) {
			document.getElementById(this.options.loadingIndicator).style.display = 'none';
		}
				
		this.resultElement.innerHTML = transport;
	}	

	
	if (window['addEventListener']) {
		search.searchElement.addEventListener('keypress', function () { 
			if (search.observer) {
				clearTimeout(search.observer);
			}
			search.observer = setTimeout(function () { search.fetchResults(); }, search.options.frequency*1000);	
			
		}, false);
	}
	else {
	

		search.searchElement.attachEvent('onkeypress', function () { 

			if (search.observer) {
				clearTimeout(search.observer);
			}
			search.observer = setTimeout(function () { search.fetchResults(); }, search.options.frequency*1000);			
		});
	}	
}

function nonLibraryAjax (url, parameters, success) {

	this.url = url+'?'+parameters;
	this.success = success;

	this.options = {
		method:       'post',
		asynchronous: true,
		contentType:  'application/x-www-form-urlencoded',
		encoding:     'UTF-8',
		evalJSON:     true,
		evalJS:       true
	};
	
	this.transport = new XMLHttpRequest();

	this.Request = function () {

		this.transport.open(this.options.method.toUpperCase(), this.url, this.options.asynchronous);
		this.transport.send(null);  
		oXHR = this;
		this.transport.onreadystatechange = function (oEvent) {  
			if (oXHR.transport.readyState === 4) {  
				if (oXHR.transport.status === 200) {  
					oXHR.success.updateResults(oXHR.transport.responseText)  
				} 
				else {  
					console.log("Error", oXHR.transport.statusText);  
				}  
			}  
		}; 

	}
}