var socitm_ratio = 5;
var socitm_popup = "";
var socitm_my_domains_tmp = "";
var socitm_popup_css = "";
var socitm_protocol = "http:";
var cookie_suffix = "3";
var iAltLimit=365;
var hasAlternate=false;
var suppresspop = false;

if (!socitm_intro_file) {
	socitm_ratio = 0;
}

if (socitm_my_domains_tmp.length > 0) { socitm_my_domains = socitm_my_domains_tmp; }
var r = Math.floor(Math.random() * (typeof(socitm_ratio) != "undefined" ? socitm_ratio : 5));

if ((document.location.search.indexOf("SocitmForcePop") > -1)||(r == 0) || (getCookie("socitm_include_me") == "true")) {
    document.write('<script type="text/javascript" src="/site/javascript/socitm_popups.js"><\/script>');
    document.write('<link rel="stylesheet" type="text/css" href="'+ socitm_protocol +'//socitm.govmetric.com/css/socitm.css" />');
    document.write('<style type="text/css">#socitm_info_box { background: #fff url(//socitm.govmetric.com/images/socitm.gif) no-repeat 1.5em 10px; }</style>');
    if ((document.location.search.indexOf("SocitmForcePop") > -1) || ((getCookie("socitm_exclude_me") != "true") && (getCookie("socitm_include_me") != "true")) && !suppresspop) {
        document.write('<script type="text/javascript" src="http://socitm.govmetric.com/popcounter.aspx?code=' + socitm_custcode + '&lang_code=' + socitm_language_opt +'"><\/script>');
    }
}

function sr_result(state){
	if (state)
	{
	    suppresspop=true;
	}
}

function getCookie(c_name)
{
	if (document.cookie.length>0)
	{
		c_start=document.cookie.indexOf(c_name+cookie_suffix+"=");
		if (c_start!=-1)
		{ 
			c_start=c_start+c_name.length+cookie_suffix.length+1; 
			c_end=document.cookie.indexOf(";",c_start);
			if (c_end==-1) c_end=document.cookie.length;
			return unescape(document.cookie.substring(c_start,c_end));
		} 
	}
	return "";
}

function setCookie(c_name,value,expiredays)
{
	var exdate=new Date();
	exdate.setDate(exdate.getDate()+expiredays);
	document.cookie=c_name+cookie_suffix+"=" +escape(value)+((expiredays==null) ? "" : ";expires="+exdate.toGMTString()) +";path=/";
}