define("util", [
], function (
) {
    var util = {
        extend: function (to, from) {
            util.forEach(from, function (p) { to[p] = from[p]; });
            return to;
        },
        forEach: function (a, c) {
            var p;
            for (p in a) {
                if (a.hasOwnProperty(p)) { c(p); }
            }
        }
    };

    util.extend(util, {
        addListener: (function () {
            var setListener;

            return function (el, ev, fn) {
                if (!setListener) {
                    if (el.addEventListener) {
                        setListener = function (el, ev, fn) {
                            el.addEventListener(ev, fn, false);
                        };
                    } else if (el.attachEvent) {
                        setListener = function (el, ev, fn) {
                            el.attachEvent('on' + ev, fn);
                        };
                    } else {
                        setListener = function (el, ev, fn) {
                            el['on' + ev] =  fn;
                        };
                    }
                }
                setListener(el, ev, fn);
            };
        }()),
        getElementsByClassAndTag: function (className, tagname) {
            var elements = document.getElementsByTagName(tagname),
                returnedElements = [],
                i;

            for (i = 0; i < elements.length; i += 1) {
                if (elements[i].className === className) {
                    returnedElements.push(elements[i]);
                }
            }

            return returnedElements;
        }
    });

    return util;
});