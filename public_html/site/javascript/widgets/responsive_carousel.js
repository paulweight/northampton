/*! (c) Mat Marquis (@wilto). MIT License. http://wil.to/3a */
(function (e, n) {
	var o = 0;
	e.fn.getPercentage = function () {
		return this.attr("style").match(/margin\-left:(.*[0-9])/i) && parseInt(RegExp.$1, 10)
	};
	e.fn.adjRounding = function (i) {
		var a = e(this),
			i = a.find(i),
			a = a.parent().width() - e(i[0]).width();
		if(0 !== a) {
			e(i).css("position", "relative");
			for(var j = 0; j < i.length; j++) {
				e(i[j]).css("left", a * j + "px")
			}
		}
		return this
	};
	e.fn.carousel = function (i) {
		if(!this.data("carousel-initialized")) {
			this.data("carousel-initialized", !0);
			var a = e.extend({
				slider: ".slider",
				slide: ".slide",
				prevSlide: null,
				nextSlide: null,
				slideHed: null,
				addPagination: !1,
				addNav: i != n && (i.prevSlide || i.nextSlide) ? !1 : !0,
				namespace: "carousel",
				speed: 600
			}, i),
				j = this,
				k = document.body || document.documentElement,
				g = {
					init: function () {
						o++;
						j.each(function (f) {
							var c = e(this),
								b = c.find(a.slider),
								d = c.find(a.slide),
								h = d.length,
								m = "margin-left " + a.speed / 1E3 + "s ease",
								l = "carousel-" + o + "-" + f;
							1 >= d.length || (c.css({
								overflow: "hidden",
								width: "100%"
							}).attr("role", "application"), b.attr("id", b[0].id || "carousel-" + o + "-" + f).css({
								marginLeft: "0px",
								"float": "left",
								width: 100 * h + "%",
								"-webkit-transition": m,
								"-moz-transition": m,
								"-ms-transition": m,
								"-o-transition": m,
								transition: m
							}).bind("carouselmove", g.move).bind("nextprev", g.nextPrev).bind("navstate", g.navState), d.css({
								"float": "left",
								width: 100 / h + "%"
							}).each(function (b) {
								var c = e(this);
								c.attr({
									role: "tabpanel document",
									id: l + "-slide" + b
								});
								a.addPagination && c.attr("aria-labelledby", l + "-tab" + b)
							}), a.addPagination && g.addPagination(), a.addNav && g.addNav(), b.trigger("navstate", {
								current: 0
							}))
						})
					},
					addNav: function () {
						j.each(function () {
							var f = e(this),
								c = f.find(a.slider)[0].id,
								c = ['<ul class="slidecontrols" role="navigation">', '\t<li role="presentation"><a href="#' + c + '" class="' + a.namespace + '-next">Next</a></li>', '\t<li role="presentation"><a href="#' + c + '" class="' + a.namespace + '-prev">Prev</a></li>', "</ul>"].join("");
							a = e.extend(a, {
								nextSlide: "." + a.namespace + "-next",
								prevSlide: "." + a.namespace + "-prev"
							});
							f.prepend(c)
						})
					},
					addPagination: function () {
						j.each(function (f) {
							var c = e(this),
								b = e('<ol class="' + a.namespace + '-tabs" role="tablist navigation" />');
							c.find(a.slider);
							for(var d = c.find(a.slide), h = d.length, f = "carousel-" + o + "-" + f; h--;) {
								var g = e(d[h]).find(a.slideHed).text() || "Page " + (h + 1),
									g = ['<li role="presentation">', '<a href="#' + f + "-slide" + h + '"', ' aria-controls="' + f + "-slide" + h + '"', ' id="' + f + "-tab" + h + '" role="tab">' + g + "</a>", "</li>"].join("");
								b.prepend(g)
							}
							b.appendTo(c).find("li").keydown(function (a) {
								var b = e(this),
									c = b.prev().find("a"),
									b = b.next().find("a");
								switch(a.which) {
								case 37:
									;
								case 38:
									c.length && c.trigger("click").focus();
									a.preventDefault();
									break;
								case 39:
									;
								case 40:
									b.length && b.trigger("click").focus(), a.preventDefault()
								}
							}).find("a").click(function (b) {
								var d = e(this);
								"false" == d.attr("aria-selected") && (d = -(100 * d.parent().index()), c.find(a.slider).trigger("carouselmove", {
									moveTo: d
								}));
								b.preventDefault()
							})
						})
					},
					roundDown: function (a) {
						a = parseInt(a, 10);
						return 100 * Math.ceil((a - a % 100) / 100)
					},
					navState: function (f, c) {
						var b = e(this),
							d = b.find(a.slide),
							h = -(c.current / 100),
							g = e(d[h]);
						b.attr("aria-activedescendant", g[0].id);
						g.addClass(a.namespace + "-active-slide").attr("aria-hidden", !1).siblings().removeClass(a.namespace + "-active-slide").attr("aria-hidden", !0);
						if(a.prevSlide || a.nextSlide) {
							b = e('[href*="#' + this.id + '"]'), b.removeClass(a.namespace + "-disabled"), 0 == h ? b.filter(a.prevSlide).addClass(a.namespace + "-disabled") : h == d.length - 1 && b.filter(a.nextSlide).addClass(a.namespace + "-disabled")
						}
						a.addPagination && (d = g.attr("aria-labelledby"), d = e("#" + d), d.parent().addClass(a.namespace + "-active-tab").siblings().removeClass(a.namespace + "-active-tab").find("a").attr({
							"aria-selected": !1,
							tabindex: -1
						}), d.attr({
							"aria-selected": !0,
							tabindex: 0
						}))
					},
					move: function (f, c) {
						var b = e(this);
						b.trigger(a.namespace + "-beforemove").trigger("navstate", {
							current: c.moveTo
						});
						k.setAttribute("style", "transition:top 1s ease;-webkit-transition:top 1s ease;-moz-transition:top 1s ease;");
						if(k.style.transition || k.style.webkitTransition || k.style.msTransition || k.style.OTransition || k.style.MozTransition) {
							b.adjRounding(a.slide).css("marginLeft", c.moveTo + "%").one("transitionend webkitTransitionEnd OTransitionEnd", function () {
								e(this).trigger(a.namespace + "-aftermove")
							})
						} else {
							b.adjRounding(a.slide).animate({
								marginLeft: c.moveTo + "%"
							}, {
								duration: a.speed,
								queue: !1
							}, function () {
								e(this).trigger(a.namespace + "-aftermove")
							})
						}
					},
					nextPrev: function (f, c) {
						var b = e(this),
							d = b ? b.getPercentage() : 0,
							h = b.find(a.slide),
							i = "prev" === c.dir ? 0 != d : -d < 100 * (h.length - 1),
							l = e('[href="#' + this.id + '"]');
						if(!b.is(":animated") && i) {
							switch(d = "prev" === c.dir ? 0 != d % 100 ? g.roundDown(d) : d + 100 : 0 != d % 100 ? g.roundDown(d) - 100 : d - 100, b.trigger("carouselmove", {
								moveTo: d
							}), l.removeClass(a.namespace + "-disabled").removeAttr("aria-disabled"), d) {
							case 100 * -(h.length - 1):
								l.filter(a.nextSlide).addClass(a.namespace + "-disabled").attr("aria-disabled", !0);
								break;
							case 0:
								l.filter(a.prevSlide).addClass(a.namespace + "-disabled").attr("aria-disabled", !0)
							}
						} else {
							d = g.roundDown(d), b.trigger("carouselmove", {
								moveTo: d
							})
						}
					}
				};
			g.init(this);
			e(a.nextSlide + "," + a.prevSlide).bind("click", function (f) {
				var c = e(this),
					b = this.hash,
					d = c.is(a.prevSlide) ? "prev" : "next",
					b = e(b);
				if(c.is("." + a.namespace + "-disabled")) {
					return !1
				}
				b.trigger("nextprev", {
					dir: d
				});
				f.preventDefault()
			}).bind("keydown", function (a) {
				e(this);
				var c = this.hash;
				switch(a.which) {
				case 37:
					;
				case 38:
					e("#" + c).trigger("nextprev", {
						dir: "next"
					});
					a.preventDefault();
					break;
				case 39:
					;
				case 40:
					e("#" + c).trigger("nextprev", {
						dir: "prev"
					}), a.preventDefault()
				}
			});
			j.bind("dragSnap", {
				wrap: this,
				slider: a.slider
			}, function (f, c) {
				e(this).find(a.slider).trigger("nextprev", {
					dir: "left" === c.direction ? "next" : "prev"
				})
			});
			j.filter("[data-autorotate]").each(function () {
				var f, c = e(this),
					b = c.attr("data-autorotate"),
					d = c.find(a.slide).length,
					g = function () {
						var i = c.find(a.slider);
						switch(-(e(a.slider).getPercentage() / 100) + 1) {
						case d:
							clearInterval(f);
							f = setInterval(function () {
								g();
								i.trigger("nextprev", {
									dir: "prev"
								})
							}, b);
							break;
						case 1:
							clearInterval(f), f = setInterval(function () {
								g();
								i.trigger("nextprev", {
									dir: "next"
								})
							}, b)
						}
					};
				f = setInterval(g, b);
				c.attr("aria-live", "polite").bind("mouseenter click touchstart", function () {
					clearInterval(f)
				})
			});
			return this
		}
	};
	e.event.special.dragSnap = {
		setup: function (i) {
			var a = e(this),
				j = function (a, e) {
					var c = e ? "margin-left 0.3s ease" : "none";
					a.css({
						"-webkit-transition": c,
						"-moz-transition": c,
						"-ms-transition": c,
						"-o-transition": c,
						transition: c
					})
				},
				k = function (a) {
					a = parseInt(a, 10);
					return 100 * Math.ceil((a - a % 100) / 100)
				};
			a.bind("snapback", function (a, e) {
				var c = e.target,
					b = c.attr("style") != n ? c.getPercentage() : 0,
					b = !1 === e.left ? k(b) - 100 : k(b);
				j(c, !0);
				dBody.setAttribute("style", "transition:top 1s ease;-webkit-transition:top 1s ease;-moz-transition:top 1s ease;");
				dBody.style.transition || dBody.style.webkitTransition || dBody.style.MozTransition ? c.css("marginLeft", b + "%") : c.animate({
					marginLeft: b + "%"
				}, opt.speed)
			}).bind("touchstart", function (g) {
				function f(a) {
					var c = a.originalEvent.touches ? a.originalEvent.touches[0] : a;
					d = {
						time: (new Date).getTime(),
						coords: [c.pageX, c.pageY]
					};
					b && !(Math.abs(b.coords[0] - d.coords[0]) < Math.abs(b.coords[1] - d.coords[1])) && (h.css({
						"margin-left": k + 100 * ((d.coords[0] - b.coords[0]) / b.origin.width()) + "%"
					}), 10 < Math.abs(b.coords[0] - d.coords[0]) && a.preventDefault())
				}
				var c = g.originalEvent.touches ? g.originalEvent.touches[0] : g,
					b = {
						time: (new Date).getTime(),
						coords: [c.pageX, c.pageY],
						origin: e(g.target).closest(i.wrap)
					},
					d, h = e(g.target).closest(i.slider),
					k = h.attr("style") != n ? h.getPercentage() : 0;
				j(h, !1);
				a.bind("gesturestart", function () {
					a.unbind("touchmove", f).unbind("touchend", f)
				}).bind("touchmove", f).one("touchend", function (c) {
					a.unbind("touchmove", f);
					j(h, !0);
					if(b && d) {
						if(10 < Math.abs(b.coords[0] - d.coords[0]) && Math.abs(b.coords[0] - d.coords[0]) > Math.abs(b.coords[1] - d.coords[1])) {
							c.preventDefault()
						} else {
							a.trigger("snapback", {
								target: h,
								left: !0
							});
							return
						}
						1 < Math.abs(b.coords[0] - d.coords[0]) && 75 > Math.abs(b.coords[1] - d.coords[1]) && (c = b.coords[0] > d.coords[0], -(d.coords[0] - b.coords[0]) > b.origin.width() / 4 || d.coords[0] - b.coords[0] > b.origin.width() / 4 ? b.origin.trigger("dragSnap", {
							direction: c ? "left" : "right"
						}) : a.trigger("snapback", {
							target: h,
							left: c
						}))
					}
					b = d = n
				})
			})
		}
	}
})(jQuery);

$(document).ready(function() {
	$('.slidewrap').carousel({
		slider: '.slider',
		slide: '.slide',
		slideHed: '.slidehed',
		nextSlide : '.next',
		prevSlide : '.prev',
		addPagination: true,
		addNav : false
	});
	
	$('.slidewrap2').carousel({
		slider: '.slider',
		slide: '.slide',
		addNav: false,
		addPagination: true,
		speed: 300 // ms.
	});
	
	$('.slidewrap3').carousel({
		namespace: "mr-rotato" // Defaults to "carousel".
	}).bind({
		'mr-rotato-beforemove' : function() {
			$('.events').append('<li>"beforemove" event fired.</li>');
		},
		'mr-rotato-aftermove' : function() {
			$('.events').append('<li>"aftermove" event fired.</li>');
		}
	}).after('<ul class="events">Events</ul>');
});