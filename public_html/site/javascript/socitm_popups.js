
var Page_Enter;
var TimeLimit = 20;
var Page_ShowPopOnExit = false;
var Page_ExternalDest = '';
var Page_InternalDest = '';

var SOCITM_SNIPPET = "<div id=\"socitm_overlay\"></div><div style=\"left: 490px; top: 371px;display:none;\" id=\"socitm_info_box\"></div>";
var blanking = '';

if (jQuery.browser.msie && jQuery.browser.version < "7") {
    blanking = "<iframe id=\"socitm_blanking\" style=\"width:1000px; height:1000px; display:block; position:absolute; top:0;left:0; z-index:-1; filter:mask();\"></iframe>";
}

function InternalLink() {
    Page_InternalDest = (this.action != undefined) ? this.action : this;
    Page_ShowPopOnExit = false;
}

function ExternalLink() {
    Page_ExternalDest = (this.action != undefined) ? this.action : this;
    Page_ShowPopOnExit = true;
}

function PageEnter() {
    Page_Enter = new Date();
    //Check that the current domain is listed in SOCITM_MY_DOMAINS variable.
    if (isMyDomain(location.href)) {
        var bC = cookiesEnabled();
        var bP = bC && (getCookie("socitm_include_me")== "") && (getCookie("socitm_exclude_me")== "");
        var bS = bC && hasAlternate && (getCookie("socitm_exclude_alt")== "") && (getCookie("socitm_include_me")== "false");
        
        if ((document.location.search.indexOf("SocitmForcePop") > -1) || bP || bS) {
            if (socitm_popup.length > 0) {
                socitmWindow(true);
                $("#socitm_info_box").html(socitm_popup + blanking + socitm_popup_css);
            } else {
                var url = socitm_intro_file;
                var resp = jQuery.get(url, function(data) { 
                    if (data.length > 0) { 
                        socitmWindow(true);
                        $("#socitm_info_box").html(data + blanking + socitm_popup_css);
                    } 
                });
            }
        } 
    }
}

function cookiesEnabled() {
    setCookie("socitm_test_cookies", "123", 365);
    if (getCookie("socitm_test_cookies") == "123") {
        setCookie("socitm_test_cookies", "123", -365); //Delete the cookie.
        return true;
    } else {
        return false;
    }
}

function SiteExit() {
    var time_dif;
    var Page_Exit = new Date();
    time_dif = (Page_Exit.getTime() - Page_Enter.getTime()) / 1000;
    time_dif = Math.round(time_dif);
    if (time_dif <= TimeLimit && (Page_ShowPopOnExit == true || (Page_ExternalDest == '' && Page_InternalDest == ''))) {
        if (getCookie("socitm_include_me") == "true") {
            var bSeenMe = (getCookie("socitm_exclude_me")=="true") || (document.location.search.indexOf("SocitmAlt") > -1);
            var socitm_win = getPopup("http://socitm.govmetric.com/survey.aspx?dest=" + Page_ExternalDest + "&code=" + socitm_custcode + (bSeenMe?"&alt=true":""));

            //Exclude the user from being prompted again.
            setCookie("socitm_include_me", "false", 365);
            setCookie("socitm_exclude_alt", "true", (bSeenMe ? 365 : iAltLimit));
            setCookie("socitm_exclude_me", "true", 365);
            
            if (socitm_win) {
                socitm_win.focus();
                window.blur();
            }
        }
    }
}

function getPopup(sUrl) {
    return window.open(sUrl, 'socitm_win');
}

function showHoldingPage() {
    var height = ($(window).height() < 700) ? 580 : 720;
    var bSeenMe = (getCookie("socitm_exclude_me")=="true") || (document.location.search.indexOf("SocitmAlt") > -1);
    var socitm_win = open("http://socitm.govmetric.com/holding.aspx?code=" + socitm_custcode + "&lang_code=" + socitm_language_opt + (bSeenMe?"&alt=true":""), 'socitm_win', 'height=' + height + ',width=650,dependant=1,resizable=1,scrollbars=1,status=0,toolbar=0,location=0');
    if (socitm_win) socitm_win.blur();
    self.focus();
}

function LinkConvert() {
    var href;
    var anchors = document.getElementsByTagName('a');

    for (var y = 0; y < anchors.length; y++) {
        href = anchors[y].href.toLowerCase();
        if (!((href.indexOf("http://") != -1 || href.indexOf("https://") != -1) && !isMyDomain(href))) {
            anchors[y].clickhandler = InternalLink;
        } else {
            anchors[y].clickhandler = ExternalLink;
        }
        XBrowserAddHandlerPops(anchors[y], "click", "clickhandler");
    }

    var areas = document.getElementsByTagName('area');

    for (var y = 0; y < areas.length; y++) {
        href = areas[y].href.toLowerCase();
        if (!((href.indexOf("http://") != -1 || href.indexOf("https://") != -1) && !isMyDomain(href))) {
            areas[y].clickhandler = InternalLink;
        } else {
            areas[y].clickhandler = ExternalLink;
        }
        XBrowserAddHandlerPops(areas[y], "click", "clickhandler");
    }

    var forms = document.getElementsByTagName('form');
    for (var y = 0; y < forms.length; y++) {
        forms[y].submithandler = InternalLink;
        XBrowserAddHandlerPops(forms[y], "submit", "submithandler");
    }
}

function isMyDomain(href) {
    var found = false;
    if (socitm_my_domains.indexOf(",") > 0) {
        var domains = socitm_my_domains.split(",");
        for (var i = 0; i <= domains.length; i++) {
            if (href.indexOf(jQuery.trim(domains[i])) >= 0) found = true;
        }
    } else if (href.indexOf(socitm_my_domains) >= 0) { found = true; }
    return found;
}

function socitmWindow(show) {
    if (show) {
        //Stick the snippet at the top of the body (overlay needs to go here).
        $('body').prepend(SOCITM_SNIPPET);

        var arrayPageSize = getPageSize();
        $('#socitm_overlay').width(arrayPageSize[0]);
        $('#socitm_overlay').height(arrayPageSize[1]);

        $('#socitm_info_box').css({ position: 'absolute',
            left: ($(window).width() - $('#socitm_info_box').width()) / 2 + "px",
            top: ($(window).height() - $('#socitm_info_box').height()) / 2 + "px"
        });

        $('#socitm_info_box').fadeIn('slow');
    } else {
        if ($('#socitm_info_box')) $('#socitm_info_box').fadeOut(500);
        if ($('#socitm_overlay')) $('#socitm_overlay').fadeOut(900);
    }
}

function socitmParticipate(shallI) {
    if (shallI) {
        //Open a holding window - we'll use this later on to display the questionnaire to the user.
        showHoldingPage();

        setCookie("socitm_include_me", true, 365);
        socitmWindow(false);
    } else {
        setCookie("socitm_exclude_me", true, 365);
        socitmWindow(false);
    }
}

function socitmParticipateLang(shallI, _lang) {
    socitm_language_opt = _lang;
    socitmParticipate(shallI);
}

function hideSocitmBox() {
    socitmWindow(false);
}

function XBrowserAddHandlerPops(target, eventName, handlerName) {
    if (target.addEventListener) {
        target.addEventListener(eventName, function(e) { target[handlerName](e); }, false);
    } else if (target.attachEvent) {
        target.attachEvent("on" + eventName, function(e) { target[handlerName](e); });
    } else {
        var originalHandler = target["on" + eventName];
        if (originalHandler) {
            target["on" + eventName] = function(e) { originalHandler(e); target[handlerName](e); };
        } else {
            target["on" + eventName] = target[handlerName];
        }
    }
}

function getPageScroll() {

    var yScroll;

    if (self.pageYOffset) {
        yScroll = self.pageYOffset;
    } else if (document.documentElement && document.documentElement.scrollTop) {	 // Explorer 6 Strict
        yScroll = document.documentElement.scrollTop;
    } else if (document.body) {// all other Explorers
        yScroll = document.body.scrollTop;
    }

    arrayPageScroll = new Array('', yScroll)
    return arrayPageScroll;
}

function getPageSize() {

    var xScroll, yScroll;

    if (window.innerHeight && window.scrollMaxY) {
        xScroll = document.body.scrollWidth;
        yScroll = window.innerHeight + window.scrollMaxY;
    } else if (document.body.scrollHeight > document.body.offsetHeight) { // all but Explorer Mac
        xScroll = document.body.scrollWidth;
        yScroll = document.body.scrollHeight;
    } else { // Explorer Mac...would also work in Explorer 6 Strict, Mozilla and Safari
        xScroll = document.body.offsetWidth;
        yScroll = document.body.offsetHeight;
    }

    var windowWidth, windowHeight;
    if (self.innerHeight) {	// all except Explorer
        windowWidth = self.innerWidth;
        windowHeight = self.innerHeight;
    } else if (document.documentElement && document.documentElement.clientHeight) { // Explorer 6 Strict Mode
        windowWidth = document.documentElement.clientWidth;
        windowHeight = document.documentElement.clientHeight;
    } else if (document.body) { // other Explorers
        windowWidth = document.body.clientWidth;
        windowHeight = document.body.clientHeight;
    }

    // for small pages with total height less then height of the viewport
    if (yScroll < windowHeight) {
        pageHeight = windowHeight;
    } else {
        pageHeight = yScroll;
    }

    // for small pages with total width less then width of the viewport
    if (xScroll < windowWidth) {
        pageWidth = windowWidth;
    } else {
        pageWidth = xScroll;
    }


    arrayPageSize = new Array(pageWidth, pageHeight, windowWidth, windowHeight)
    return arrayPageSize;
}

jQuery(document).ready(PageEnter);
jQuery(document).ready(LinkConvert);
window.onunload = function () { SiteExit(); }

Page_ShowPopOnExit = true;
