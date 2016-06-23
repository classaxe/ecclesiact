/*! jQuery UI - v1.9.2 - 2014-10-04
 * http://jqueryui.com
 * Includes: jquery.ui.core.js, jquery.ui.widget.js, jquery.ui.mouse.js, jquery.ui.position.js, jquery.ui.draggable.js, jquery.ui.droppable.js, jquery.ui.resizable.js, jquery.ui.selectable.js, jquery.ui.sortable.js, jquery.ui.accordion.js, jquery.ui.autocomplete.js, jquery.ui.button.js, jquery.ui.datepicker.js, jquery.ui.dialog.js, jquery.ui.menu.js, jquery.ui.progressbar.js, jquery.ui.slider.js, jquery.ui.spinner.js, jquery.ui.tabs.js, jquery.ui.tooltip.js, jquery.ui.effect.js, jquery.ui.effect-blind.js, jquery.ui.effect-bounce.js, jquery.ui.effect-clip.js, jquery.ui.effect-drop.js, jquery.ui.effect-explode.js, jquery.ui.effect-fade.js, jquery.ui.effect-fold.js, jquery.ui.effect-highlight.js, jquery.ui.effect-pulsate.js, jquery.ui.effect-scale.js, jquery.ui.effect-shake.js, jquery.ui.effect-slide.js, jquery.ui.effect-transfer.js
 * Copyright 2014 jQuery Foundation and other contributors; Licensed MIT */

(function (e, t) {
    function i(t, i) {
        var n, a, o, r = t.nodeName.toLowerCase();
        return "area" === r ? (n = t.parentNode, a = n.name, t.href && a && "map" === n.nodeName.toLowerCase() ? (o = e("img[usemap=#" + a + "]")[0], !!o && s(o)) : !1) : (/input|select|textarea|button|object/.test(r) ? !t.disabled : "a" === r ? t.href || i : i) && s(t)
    }

    function s(t) {
        return e.expr.filters.visible(t) && !e(t).parents().andSelf().filter(function () {
                return "hidden" === e.css(this, "visibility")
            }).length
    }

    var n = 0, a = /^ui-id-\d+$/;
    e.ui = e.ui || {}, e.ui.version || (e.extend(e.ui, {
        version: "1.9.2",
        keyCode: {
            BACKSPACE: 8,
            COMMA: 188,
            DELETE: 46,
            DOWN: 40,
            END: 35,
            ENTER: 13,
            ESCAPE: 27,
            HOME: 36,
            LEFT: 37,
            NUMPAD_ADD: 107,
            NUMPAD_DECIMAL: 110,
            NUMPAD_DIVIDE: 111,
            NUMPAD_ENTER: 108,
            NUMPAD_MULTIPLY: 106,
            NUMPAD_SUBTRACT: 109,
            PAGE_DOWN: 34,
            PAGE_UP: 33,
            PERIOD: 190,
            RIGHT: 39,
            SPACE: 32,
            TAB: 9,
            UP: 38
        }
    }), e.fn.extend({
        _focus: e.fn.focus, focus: function (t, i) {
            return "number" == typeof t ? this.each(function () {
                var s = this;
                setTimeout(function () {
                    e(s).focus(), i && i.call(s)
                }, t)
            }) : this._focus.apply(this, arguments)
        }, scrollParent: function () {
            var t;
            return t = e.ui.ie && /(static|relative)/.test(this.css("position")) || /absolute/.test(this.css("position")) ? this.parents().filter(function () {
                return /(relative|absolute|fixed)/.test(e.css(this, "position")) && /(auto|scroll)/.test(e.css(this, "overflow") + e.css(this, "overflow-y") + e.css(this, "overflow-x"))
            }).eq(0) : this.parents().filter(function () {
                return /(auto|scroll)/.test(e.css(this, "overflow") + e.css(this, "overflow-y") + e.css(this, "overflow-x"))
            }).eq(0), /fixed/.test(this.css("position")) || !t.length ? e(document) : t
        }, zIndex: function (i) {
            if (i !== t)return this.css("zIndex", i);
            if (this.length)for (var s, n, a = e(this[0]); a.length && a[0] !== document;) {
                if (s = a.css("position"), ("absolute" === s || "relative" === s || "fixed" === s) && (n = parseInt(a.css("zIndex"), 10), !isNaN(n) && 0 !== n))return n;
                a = a.parent()
            }
            return 0
        }, uniqueId: function () {
            return this.each(function () {
                this.id || (this.id = "ui-id-" + ++n)
            })
        }, removeUniqueId: function () {
            return this.each(function () {
                a.test(this.id) && e(this).removeAttr("id")
            })
        }
    }), e.extend(e.expr[":"], {
        data: e.expr.createPseudo ? e.expr.createPseudo(function (t) {
            return function (i) {
                return !!e.data(i, t)
            }
        }) : function (t, i, s) {
            return !!e.data(t, s[3])
        }, focusable: function (t) {
            return i(t, !isNaN(e.attr(t, "tabindex")))
        }, tabbable: function (t) {
            var s = e.attr(t, "tabindex"), n = isNaN(s);
            return (n || s >= 0) && i(t, !n)
        }
    }), e(function () {
        var t = document.body, i = t.appendChild(i = document.createElement("div"));
        i.offsetHeight, e.extend(i.style, {
            minHeight: "100px",
            height: "auto",
            padding: 0,
            borderWidth: 0
        }), e.support.minHeight = 100 === i.offsetHeight, e.support.selectstart = "onselectstart"in i, t.removeChild(i).style.display = "none"
    }), e("<a>").outerWidth(1).jquery || e.each(["Width", "Height"], function (i, s) {
        function n(t, i, s, n) {
            return e.each(a, function () {
                i -= parseFloat(e.css(t, "padding" + this)) || 0, s && (i -= parseFloat(e.css(t, "border" + this + "Width")) || 0), n && (i -= parseFloat(e.css(t, "margin" + this)) || 0)
            }), i
        }

        var a = "Width" === s ? ["Left", "Right"] : ["Top", "Bottom"], o = s.toLowerCase(), r = {
            innerWidth: e.fn.innerWidth,
            innerHeight: e.fn.innerHeight,
            outerWidth: e.fn.outerWidth,
            outerHeight: e.fn.outerHeight
        };
        e.fn["inner" + s] = function (i) {
            return i === t ? r["inner" + s].call(this) : this.each(function () {
                e(this).css(o, n(this, i) + "px")
            })
        }, e.fn["outer" + s] = function (t, i) {
            return "number" != typeof t ? r["outer" + s].call(this, t) : this.each(function () {
                e(this).css(o, n(this, t, !0, i) + "px")
            })
        }
    }), e("<a>").data("a-b", "a").removeData("a-b").data("a-b") && (e.fn.removeData = function (t) {
        return function (i) {
            return arguments.length ? t.call(this, e.camelCase(i)) : t.call(this)
        }
    }(e.fn.removeData)), function () {
        var t = /msie ([\w.]+)/.exec(navigator.userAgent.toLowerCase()) || [];
        e.ui.ie = t.length ? !0 : !1, e.ui.ie6 = 6 === parseFloat(t[1], 10)
    }(), e.fn.extend({
        disableSelection: function () {
            return this.bind((e.support.selectstart ? "selectstart" : "mousedown") + ".ui-disableSelection", function (e) {
                e.preventDefault()
            })
        }, enableSelection: function () {
            return this.unbind(".ui-disableSelection")
        }
    }), e.extend(e.ui, {
        plugin: {
            add: function (t, i, s) {
                var n, a = e.ui[t].prototype;
                for (n in s)a.plugins[n] = a.plugins[n] || [], a.plugins[n].push([i, s[n]])
            }, call: function (e, t, i) {
                var s, n = e.plugins[t];
                if (n && e.element[0].parentNode && 11 !== e.element[0].parentNode.nodeType)for (s = 0; n.length > s; s++)e.options[n[s][0]] && n[s][1].apply(e.element, i)
            }
        }, contains: e.contains, hasScroll: function (t, i) {
            if ("hidden" === e(t).css("overflow"))return !1;
            var s = i && "left" === i ? "scrollLeft" : "scrollTop", n = !1;
            return t[s] > 0 ? !0 : (t[s] = 1, n = t[s] > 0, t[s] = 0, n)
        }, isOverAxis: function (e, t, i) {
            return e > t && t + i > e
        }, isOver: function (t, i, s, n, a, o) {
            return e.ui.isOverAxis(t, s, a) && e.ui.isOverAxis(i, n, o)
        }
    }))
})(jQuery);
(function (e, t) {
    var i = 0, s = Array.prototype.slice, n = e.cleanData;
    e.cleanData = function (t) {
        for (var i, s = 0; null != (i = t[s]); s++)try {
            e(i).triggerHandler("remove")
        } catch (a) {
        }
        n(t)
    }, e.widget = function (i, s, n) {
        var a, o, r, h, l = i.split(".")[0];
        i = i.split(".")[1], a = l + "-" + i, n || (n = s, s = e.Widget), e.expr[":"][a.toLowerCase()] = function (t) {
            return !!e.data(t, a)
        }, e[l] = e[l] || {}, o = e[l][i], r = e[l][i] = function (e, i) {
            return this._createWidget ? (arguments.length && this._createWidget(e, i), t) : new r(e, i)
        }, e.extend(r, o, {
            version: n.version,
            _proto: e.extend({}, n),
            _childConstructors: []
        }), h = new s, h.options = e.widget.extend({}, h.options), e.each(n, function (t, i) {
            e.isFunction(i) && (n[t] = function () {
                var e = function () {
                    return s.prototype[t].apply(this, arguments)
                }, n = function (e) {
                    return s.prototype[t].apply(this, e)
                };
                return function () {
                    var t, s = this._super, a = this._superApply;
                    return this._super = e, this._superApply = n, t = i.apply(this, arguments), this._super = s, this._superApply = a, t
                }
            }())
        }), r.prototype = e.widget.extend(h, {widgetEventPrefix: o ? h.widgetEventPrefix : i}, n, {
            constructor: r,
            namespace: l,
            widgetName: i,
            widgetBaseClass: a,
            widgetFullName: a
        }), o ? (e.each(o._childConstructors, function (t, i) {
            var s = i.prototype;
            e.widget(s.namespace + "." + s.widgetName, r, i._proto)
        }), delete o._childConstructors) : s._childConstructors.push(r), e.widget.bridge(i, r)
    }, e.widget.extend = function (i) {
        for (var n, a, o = s.call(arguments, 1), r = 0, h = o.length; h > r; r++)for (n in o[r])a = o[r][n], o[r].hasOwnProperty(n) && a !== t && (i[n] = e.isPlainObject(a) ? e.isPlainObject(i[n]) ? e.widget.extend({}, i[n], a) : e.widget.extend({}, a) : a);
        return i
    }, e.widget.bridge = function (i, n) {
        var a = n.prototype.widgetFullName || i;
        e.fn[i] = function (o) {
            var r = "string" == typeof o, h = s.call(arguments, 1), l = this;
            return o = !r && h.length ? e.widget.extend.apply(null, [o].concat(h)) : o, r ? this.each(function () {
                var s, n = e.data(this, a);
                return n ? e.isFunction(n[o]) && "_" !== o.charAt(0) ? (s = n[o].apply(n, h), s !== n && s !== t ? (l = s && s.jquery ? l.pushStack(s.get()) : s, !1) : t) : e.error("no such method '" + o + "' for " + i + " widget instance") : e.error("cannot call methods on " + i + " prior to initialization; " + "attempted to call method '" + o + "'")
            }) : this.each(function () {
                var t = e.data(this, a);
                t ? t.option(o || {})._init() : e.data(this, a, new n(o, this))
            }), l
        }
    }, e.Widget = function () {
    }, e.Widget._childConstructors = [], e.Widget.prototype = {
        widgetName: "widget",
        widgetEventPrefix: "",
        defaultElement: "<div>",
        options: {disabled: !1, create: null},
        _createWidget: function (t, s) {
            s = e(s || this.defaultElement || this)[0], this.element = e(s), this.uuid = i++, this.eventNamespace = "." + this.widgetName + this.uuid, this.options = e.widget.extend({}, this.options, this._getCreateOptions(), t), this.bindings = e(), this.hoverable = e(), this.focusable = e(), s !== this && (e.data(s, this.widgetName, this), e.data(s, this.widgetFullName, this), this._on(!0, this.element, {
                remove: function (e) {
                    e.target === s && this.destroy()
                }
            }), this.document = e(s.style ? s.ownerDocument : s.document || s), this.window = e(this.document[0].defaultView || this.document[0].parentWindow)), this._create(), this._trigger("create", null, this._getCreateEventData()), this._init()
        },
        _getCreateOptions: e.noop,
        _getCreateEventData: e.noop,
        _create: e.noop,
        _init: e.noop,
        destroy: function () {
            this._destroy(), this.element.unbind(this.eventNamespace).removeData(this.widgetName).removeData(this.widgetFullName).removeData(e.camelCase(this.widgetFullName)), this.widget().unbind(this.eventNamespace).removeAttr("aria-disabled").removeClass(this.widgetFullName + "-disabled " + "ui-state-disabled"), this.bindings.unbind(this.eventNamespace), this.hoverable.removeClass("ui-state-hover"), this.focusable.removeClass("ui-state-focus")
        },
        _destroy: e.noop,
        widget: function () {
            return this.element
        },
        option: function (i, s) {
            var n, a, o, r = i;
            if (0 === arguments.length)return e.widget.extend({}, this.options);
            if ("string" == typeof i)if (r = {}, n = i.split("."), i = n.shift(), n.length) {
                for (a = r[i] = e.widget.extend({}, this.options[i]), o = 0; n.length - 1 > o; o++)a[n[o]] = a[n[o]] || {}, a = a[n[o]];
                if (i = n.pop(), s === t)return a[i] === t ? null : a[i];
                a[i] = s
            } else {
                if (s === t)return this.options[i] === t ? null : this.options[i];
                r[i] = s
            }
            return this._setOptions(r), this
        },
        _setOptions: function (e) {
            var t;
            for (t in e)this._setOption(t, e[t]);
            return this
        },
        _setOption: function (e, t) {
            return this.options[e] = t, "disabled" === e && (this.widget().toggleClass(this.widgetFullName + "-disabled ui-state-disabled", !!t).attr("aria-disabled", t), this.hoverable.removeClass("ui-state-hover"), this.focusable.removeClass("ui-state-focus")), this
        },
        enable: function () {
            return this._setOption("disabled", !1)
        },
        disable: function () {
            return this._setOption("disabled", !0)
        },
        _on: function (i, s, n) {
            var a, o = this;
            "boolean" != typeof i && (n = s, s = i, i = !1), n ? (s = a = e(s), this.bindings = this.bindings.add(s)) : (n = s, s = this.element, a = this.widget()), e.each(n, function (n, r) {
                function h() {
                    return i || o.options.disabled !== !0 && !e(this).hasClass("ui-state-disabled") ? ("string" == typeof r ? o[r] : r).apply(o, arguments) : t
                }

                "string" != typeof r && (h.guid = r.guid = r.guid || h.guid || e.guid++);
                var l = n.match(/^(\w+)\s*(.*)$/), u = l[1] + o.eventNamespace, d = l[2];
                d ? a.delegate(d, u, h) : s.bind(u, h)
            })
        },
        _off: function (e, t) {
            t = (t || "").split(" ").join(this.eventNamespace + " ") + this.eventNamespace, e.unbind(t).undelegate(t)
        },
        _delay: function (e, t) {
            function i() {
                return ("string" == typeof e ? s[e] : e).apply(s, arguments)
            }

            var s = this;
            return setTimeout(i, t || 0)
        },
        _hoverable: function (t) {
            this.hoverable = this.hoverable.add(t), this._on(t, {
                mouseenter: function (t) {
                    e(t.currentTarget).addClass("ui-state-hover")
                }, mouseleave: function (t) {
                    e(t.currentTarget).removeClass("ui-state-hover")
                }
            })
        },
        _focusable: function (t) {
            this.focusable = this.focusable.add(t), this._on(t, {
                focusin: function (t) {
                    e(t.currentTarget).addClass("ui-state-focus")
                }, focusout: function (t) {
                    e(t.currentTarget).removeClass("ui-state-focus")
                }
            })
        },
        _trigger: function (t, i, s) {
            var n, a, o = this.options[t];
            if (s = s || {}, i = e.Event(i), i.type = (t === this.widgetEventPrefix ? t : this.widgetEventPrefix + t).toLowerCase(), i.target = this.element[0], a = i.originalEvent)for (n in a)n in i || (i[n] = a[n]);
            return this.element.trigger(i, s), !(e.isFunction(o) && o.apply(this.element[0], [i].concat(s)) === !1 || i.isDefaultPrevented())
        }
    }, e.each({show: "fadeIn", hide: "fadeOut"}, function (t, i) {
        e.Widget.prototype["_" + t] = function (s, n, a) {
            "string" == typeof n && (n = {effect: n});
            var o, r = n ? n === !0 || "number" == typeof n ? i : n.effect || i : t;
            n = n || {}, "number" == typeof n && (n = {duration: n}), o = !e.isEmptyObject(n), n.complete = a, n.delay && s.delay(n.delay), o && e.effects && (e.effects.effect[r] || e.uiBackCompat !== !1 && e.effects[r]) ? s[t](n) : r !== t && s[r] ? s[r](n.duration, n.easing, a) : s.queue(function (i) {
                e(this)[t](), a && a.call(s[0]), i()
            })
        }
    }), e.uiBackCompat !== !1 && (e.Widget.prototype._getCreateOptions = function () {
        return e.metadata && e.metadata.get(this.element[0])[this.widgetName]
    })
})(jQuery);
(function (e) {
    var t = !1;
    e(document).mouseup(function () {
        t = !1
    }), e.widget("ui.mouse", {
        version: "1.9.2",
        options: {cancel: "input,textarea,button,select,option", distance: 1, delay: 0},
        _mouseInit: function () {
            var t = this;
            this.element.bind("mousedown." + this.widgetName, function (e) {
                return t._mouseDown(e)
            }).bind("click." + this.widgetName, function (i) {
                return !0 === e.data(i.target, t.widgetName + ".preventClickEvent") ? (e.removeData(i.target, t.widgetName + ".preventClickEvent"), i.stopImmediatePropagation(), !1) : undefined
            }), this.started = !1
        },
        _mouseDestroy: function () {
            this.element.unbind("." + this.widgetName), this._mouseMoveDelegate && e(document).unbind("mousemove." + this.widgetName, this._mouseMoveDelegate).unbind("mouseup." + this.widgetName, this._mouseUpDelegate)
        },
        _mouseDown: function (i) {
            if (!t) {
                this._mouseStarted && this._mouseUp(i), this._mouseDownEvent = i;
                var s = this, n = 1 === i.which, a = "string" == typeof this.options.cancel && i.target.nodeName ? e(i.target).closest(this.options.cancel).length : !1;
                return n && !a && this._mouseCapture(i) ? (this.mouseDelayMet = !this.options.delay, this.mouseDelayMet || (this._mouseDelayTimer = setTimeout(function () {
                    s.mouseDelayMet = !0
                }, this.options.delay)), this._mouseDistanceMet(i) && this._mouseDelayMet(i) && (this._mouseStarted = this._mouseStart(i) !== !1, !this._mouseStarted) ? (i.preventDefault(), !0) : (!0 === e.data(i.target, this.widgetName + ".preventClickEvent") && e.removeData(i.target, this.widgetName + ".preventClickEvent"), this._mouseMoveDelegate = function (e) {
                    return s._mouseMove(e)
                }, this._mouseUpDelegate = function (e) {
                    return s._mouseUp(e)
                }, e(document).bind("mousemove." + this.widgetName, this._mouseMoveDelegate).bind("mouseup." + this.widgetName, this._mouseUpDelegate), i.preventDefault(), t = !0, !0)) : !0
            }
        },
        _mouseMove: function (t) {
            return !e.ui.ie || document.documentMode >= 9 || t.button ? this._mouseStarted ? (this._mouseDrag(t), t.preventDefault()) : (this._mouseDistanceMet(t) && this._mouseDelayMet(t) && (this._mouseStarted = this._mouseStart(this._mouseDownEvent, t) !== !1, this._mouseStarted ? this._mouseDrag(t) : this._mouseUp(t)), !this._mouseStarted) : this._mouseUp(t)
        },
        _mouseUp: function (t) {
            return e(document).unbind("mousemove." + this.widgetName, this._mouseMoveDelegate).unbind("mouseup." + this.widgetName, this._mouseUpDelegate), this._mouseStarted && (this._mouseStarted = !1, t.target === this._mouseDownEvent.target && e.data(t.target, this.widgetName + ".preventClickEvent", !0), this._mouseStop(t)), !1
        },
        _mouseDistanceMet: function (e) {
            return Math.max(Math.abs(this._mouseDownEvent.pageX - e.pageX), Math.abs(this._mouseDownEvent.pageY - e.pageY)) >= this.options.distance
        },
        _mouseDelayMet: function () {
            return this.mouseDelayMet
        },
        _mouseStart: function () {
        },
        _mouseDrag: function () {
        },
        _mouseStop: function () {
        },
        _mouseCapture: function () {
            return !0
        }
    })
})(jQuery);
(function (e, t) {
    function i(e, t, i) {
        return [parseInt(e[0], 10) * (c.test(e[0]) ? t / 100 : 1), parseInt(e[1], 10) * (c.test(e[1]) ? i / 100 : 1)]
    }

    function s(t, i) {
        return parseInt(e.css(t, i), 10) || 0
    }

    e.ui = e.ui || {};
    var n, a = Math.max, o = Math.abs, r = Math.round, l = /left|center|right/, h = /top|center|bottom/, u = /[\+\-]\d+%?/, d = /^\w+/, c = /%$/, p = e.fn.position;
    e.position = {
        scrollbarWidth: function () {
            if (n !== t)return n;
            var i, s, a = e("<div style='display:block;width:50px;height:50px;overflow:hidden;'><div style='height:100px;width:auto;'></div></div>"), o = a.children()[0];
            return e("body").append(a), i = o.offsetWidth, a.css("overflow", "scroll"), s = o.offsetWidth, i === s && (s = a[0].clientWidth), a.remove(), n = i - s
        }, getScrollInfo: function (t) {
            var i = t.isWindow ? "" : t.element.css("overflow-x"), s = t.isWindow ? "" : t.element.css("overflow-y"), n = "scroll" === i || "auto" === i && t.width < t.element[0].scrollWidth, a = "scroll" === s || "auto" === s && t.height < t.element[0].scrollHeight;
            return {width: n ? e.position.scrollbarWidth() : 0, height: a ? e.position.scrollbarWidth() : 0}
        }, getWithinInfo: function (t) {
            var i = e(t || window), s = e.isWindow(i[0]);
            return {
                element: i,
                isWindow: s,
                offset: i.offset() || {left: 0, top: 0},
                scrollLeft: i.scrollLeft(),
                scrollTop: i.scrollTop(),
                width: s ? i.width() : i.outerWidth(),
                height: s ? i.height() : i.outerHeight()
            }
        }
    }, e.fn.position = function (t) {
        if (!t || !t.of)return p.apply(this, arguments);
        t = e.extend({}, t);
        var n, c, f, m, g, v = e(t.of), y = e.position.getWithinInfo(t.within), b = e.position.getScrollInfo(y), _ = v[0], x = (t.collision || "flip").split(" "), w = {};
        return 9 === _.nodeType ? (c = v.width(), f = v.height(), m = {
            top: 0,
            left: 0
        }) : e.isWindow(_) ? (c = v.width(), f = v.height(), m = {
            top: v.scrollTop(),
            left: v.scrollLeft()
        }) : _.preventDefault ? (t.at = "left top", c = f = 0, m = {
            top: _.pageY,
            left: _.pageX
        }) : (c = v.outerWidth(), f = v.outerHeight(), m = v.offset()), g = e.extend({}, m), e.each(["my", "at"], function () {
            var e, i, s = (t[this] || "").split(" ");
            1 === s.length && (s = l.test(s[0]) ? s.concat(["center"]) : h.test(s[0]) ? ["center"].concat(s) : ["center", "center"]), s[0] = l.test(s[0]) ? s[0] : "center", s[1] = h.test(s[1]) ? s[1] : "center", e = u.exec(s[0]), i = u.exec(s[1]), w[this] = [e ? e[0] : 0, i ? i[0] : 0], t[this] = [d.exec(s[0])[0], d.exec(s[1])[0]]
        }), 1 === x.length && (x[1] = x[0]), "right" === t.at[0] ? g.left += c : "center" === t.at[0] && (g.left += c / 2), "bottom" === t.at[1] ? g.top += f : "center" === t.at[1] && (g.top += f / 2), n = i(w.at, c, f), g.left += n[0], g.top += n[1], this.each(function () {
            var l, h, u = e(this), d = u.outerWidth(), p = u.outerHeight(), _ = s(this, "marginLeft"), k = s(this, "marginTop"), D = d + _ + s(this, "marginRight") + b.width, T = p + k + s(this, "marginBottom") + b.height, S = e.extend({}, g), M = i(w.my, u.outerWidth(), u.outerHeight());
            "right" === t.my[0] ? S.left -= d : "center" === t.my[0] && (S.left -= d / 2), "bottom" === t.my[1] ? S.top -= p : "center" === t.my[1] && (S.top -= p / 2), S.left += M[0], S.top += M[1], e.support.offsetFractions || (S.left = r(S.left), S.top = r(S.top)), l = {
                marginLeft: _,
                marginTop: k
            }, e.each(["left", "top"], function (i, s) {
                e.ui.position[x[i]] && e.ui.position[x[i]][s](S, {
                    targetWidth: c,
                    targetHeight: f,
                    elemWidth: d,
                    elemHeight: p,
                    collisionPosition: l,
                    collisionWidth: D,
                    collisionHeight: T,
                    offset: [n[0] + M[0], n[1] + M[1]],
                    my: t.my,
                    at: t.at,
                    within: y,
                    elem: u
                })
            }), e.fn.bgiframe && u.bgiframe(), t.using && (h = function (e) {
                var i = m.left - S.left, s = i + c - d, n = m.top - S.top, r = n + f - p, l = {
                    target: {
                        element: v,
                        left: m.left,
                        top: m.top,
                        width: c,
                        height: f
                    },
                    element: {element: u, left: S.left, top: S.top, width: d, height: p},
                    horizontal: 0 > s ? "left" : i > 0 ? "right" : "center",
                    vertical: 0 > r ? "top" : n > 0 ? "bottom" : "middle"
                };
                d > c && c > o(i + s) && (l.horizontal = "center"), p > f && f > o(n + r) && (l.vertical = "middle"), l.important = a(o(i), o(s)) > a(o(n), o(r)) ? "horizontal" : "vertical", t.using.call(this, e, l)
            }), u.offset(e.extend(S, {using: h}))
        })
    }, e.ui.position = {
        fit: {
            left: function (e, t) {
                var i, s = t.within, n = s.isWindow ? s.scrollLeft : s.offset.left, o = s.width, r = e.left - t.collisionPosition.marginLeft, l = n - r, h = r + t.collisionWidth - o - n;
                t.collisionWidth > o ? l > 0 && 0 >= h ? (i = e.left + l + t.collisionWidth - o - n, e.left += l - i) : e.left = h > 0 && 0 >= l ? n : l > h ? n + o - t.collisionWidth : n : l > 0 ? e.left += l : h > 0 ? e.left -= h : e.left = a(e.left - r, e.left)
            }, top: function (e, t) {
                var i, s = t.within, n = s.isWindow ? s.scrollTop : s.offset.top, o = t.within.height, r = e.top - t.collisionPosition.marginTop, l = n - r, h = r + t.collisionHeight - o - n;
                t.collisionHeight > o ? l > 0 && 0 >= h ? (i = e.top + l + t.collisionHeight - o - n, e.top += l - i) : e.top = h > 0 && 0 >= l ? n : l > h ? n + o - t.collisionHeight : n : l > 0 ? e.top += l : h > 0 ? e.top -= h : e.top = a(e.top - r, e.top)
            }
        }, flip: {
            left: function (e, t) {
                var i, s, n = t.within, a = n.offset.left + n.scrollLeft, r = n.width, l = n.isWindow ? n.scrollLeft : n.offset.left, h = e.left - t.collisionPosition.marginLeft, u = h - l, d = h + t.collisionWidth - r - l, c = "left" === t.my[0] ? -t.elemWidth : "right" === t.my[0] ? t.elemWidth : 0, p = "left" === t.at[0] ? t.targetWidth : "right" === t.at[0] ? -t.targetWidth : 0, f = -2 * t.offset[0];
                0 > u ? (i = e.left + c + p + f + t.collisionWidth - r - a, (0 > i || o(u) > i) && (e.left += c + p + f)) : d > 0 && (s = e.left - t.collisionPosition.marginLeft + c + p + f - l, (s > 0 || d > o(s)) && (e.left += c + p + f))
            }, top: function (e, t) {
                var i, s, n = t.within, a = n.offset.top + n.scrollTop, r = n.height, l = n.isWindow ? n.scrollTop : n.offset.top, h = e.top - t.collisionPosition.marginTop, u = h - l, d = h + t.collisionHeight - r - l, c = "top" === t.my[1], p = c ? -t.elemHeight : "bottom" === t.my[1] ? t.elemHeight : 0, f = "top" === t.at[1] ? t.targetHeight : "bottom" === t.at[1] ? -t.targetHeight : 0, m = -2 * t.offset[1];
                0 > u ? (s = e.top + p + f + m + t.collisionHeight - r - a, e.top + p + f + m > u && (0 > s || o(u) > s) && (e.top += p + f + m)) : d > 0 && (i = e.top - t.collisionPosition.marginTop + p + f + m - l, e.top + p + f + m > d && (i > 0 || d > o(i)) && (e.top += p + f + m))
            }
        }, flipfit: {
            left: function () {
                e.ui.position.flip.left.apply(this, arguments), e.ui.position.fit.left.apply(this, arguments)
            }, top: function () {
                e.ui.position.flip.top.apply(this, arguments), e.ui.position.fit.top.apply(this, arguments)
            }
        }
    }, function () {
        var t, i, s, n, a, o = document.getElementsByTagName("body")[0], r = document.createElement("div");
        t = document.createElement(o ? "div" : "body"), s = {
            visibility: "hidden",
            width: 0,
            height: 0,
            border: 0,
            margin: 0,
            background: "none"
        }, o && e.extend(s, {position: "absolute", left: "-1000px", top: "-1000px"});
        for (a in s)t.style[a] = s[a];
        t.appendChild(r), i = o || document.documentElement, i.insertBefore(t, i.firstChild), r.style.cssText = "position: absolute; left: 10.7432222px;", n = e(r).offset().left, e.support.offsetFractions = n > 10 && 11 > n, t.innerHTML = "", i.removeChild(t)
    }(), e.uiBackCompat !== !1 && function (e) {
        var i = e.fn.position;
        e.fn.position = function (s) {
            if (!s || !s.offset)return i.call(this, s);
            var n = s.offset.split(" "), a = s.at.split(" ");
            return 1 === n.length && (n[1] = n[0]), /^\d/.test(n[0]) && (n[0] = "+" + n[0]), /^\d/.test(n[1]) && (n[1] = "+" + n[1]), 1 === a.length && (/left|center|right/.test(a[0]) ? a[1] = "center" : (a[1] = a[0], a[0] = "center")), i.call(this, e.extend(s, {
                at: a[0] + n[0] + " " + a[1] + n[1],
                offset: t
            }))
        }
    }(jQuery)
})(jQuery);
(function (e) {
    e.widget("ui.draggable", e.ui.mouse, {
        version: "1.9.2",
        widgetEventPrefix: "drag",
        options: {
            addClasses: !0,
            appendTo: "parent",
            axis: !1,
            connectToSortable: !1,
            containment: !1,
            cursor: "auto",
            cursorAt: !1,
            grid: !1,
            handle: !1,
            helper: "original",
            iframeFix: !1,
            opacity: !1,
            refreshPositions: !1,
            revert: !1,
            revertDuration: 500,
            scope: "default",
            scroll: !0,
            scrollSensitivity: 20,
            scrollSpeed: 20,
            snap: !1,
            snapMode: "both",
            snapTolerance: 20,
            stack: !1,
            zIndex: !1
        },
        _create: function () {
            "original" != this.options.helper || /^(?:r|a|f)/.test(this.element.css("position")) || (this.element[0].style.position = "relative"), this.options.addClasses && this.element.addClass("ui-draggable"), this.options.disabled && this.element.addClass("ui-draggable-disabled"), this._mouseInit()
        },
        _destroy: function () {
            this.element.removeClass("ui-draggable ui-draggable-dragging ui-draggable-disabled"), this._mouseDestroy()
        },
        _mouseCapture: function (t) {
            var i = this.options;
            return this.helper || i.disabled || e(t.target).is(".ui-resizable-handle") ? !1 : (this.handle = this._getHandle(t), this.handle ? (e(i.iframeFix === !0 ? "iframe" : i.iframeFix).each(function () {
                e('<div class="ui-draggable-iframeFix" style="background: #fff;"></div>').css({
                    width: this.offsetWidth + "px",
                    height: this.offsetHeight + "px",
                    position: "absolute",
                    opacity: "0.001",
                    zIndex: 1e3
                }).css(e(this).offset()).appendTo("body")
            }), !0) : !1)
        },
        _mouseStart: function (t) {
            var i = this.options;
            return this.helper = this._createHelper(t), this.helper.addClass("ui-draggable-dragging"), this._cacheHelperProportions(), e.ui.ddmanager && (e.ui.ddmanager.current = this), this._cacheMargins(), this.cssPosition = this.helper.css("position"), this.scrollParent = this.helper.scrollParent(), this.offset = this.positionAbs = this.element.offset(), this.offset = {
                top: this.offset.top - this.margins.top,
                left: this.offset.left - this.margins.left
            }, e.extend(this.offset, {
                click: {left: t.pageX - this.offset.left, top: t.pageY - this.offset.top},
                parent: this._getParentOffset(),
                relative: this._getRelativeOffset()
            }), this.originalPosition = this.position = this._generatePosition(t), this.originalPageX = t.pageX, this.originalPageY = t.pageY, i.cursorAt && this._adjustOffsetFromHelper(i.cursorAt), i.containment && this._setContainment(), this._trigger("start", t) === !1 ? (this._clear(), !1) : (this._cacheHelperProportions(), e.ui.ddmanager && !i.dropBehaviour && e.ui.ddmanager.prepareOffsets(this, t), this._mouseDrag(t, !0), e.ui.ddmanager && e.ui.ddmanager.dragStart(this, t), !0)
        },
        _mouseDrag: function (t, i) {
            if (this.position = this._generatePosition(t), this.positionAbs = this._convertPositionTo("absolute"), !i) {
                var s = this._uiHash();
                if (this._trigger("drag", t, s) === !1)return this._mouseUp({}), !1;
                this.position = s.position
            }
            return this.options.axis && "y" == this.options.axis || (this.helper[0].style.left = this.position.left + "px"), this.options.axis && "x" == this.options.axis || (this.helper[0].style.top = this.position.top + "px"), e.ui.ddmanager && e.ui.ddmanager.drag(this, t), !1
        },
        _mouseStop: function (t) {
            var i = !1;
            e.ui.ddmanager && !this.options.dropBehaviour && (i = e.ui.ddmanager.drop(this, t)), this.dropped && (i = this.dropped, this.dropped = !1);
            for (var s = this.element[0], n = !1; s && (s = s.parentNode);)s == document && (n = !0);
            if (!n && "original" === this.options.helper)return !1;
            if ("invalid" == this.options.revert && !i || "valid" == this.options.revert && i || this.options.revert === !0 || e.isFunction(this.options.revert) && this.options.revert.call(this.element, i)) {
                var a = this;
                e(this.helper).animate(this.originalPosition, parseInt(this.options.revertDuration, 10), function () {
                    a._trigger("stop", t) !== !1 && a._clear()
                })
            } else this._trigger("stop", t) !== !1 && this._clear();
            return !1
        },
        _mouseUp: function (t) {
            return e("div.ui-draggable-iframeFix").each(function () {
                this.parentNode.removeChild(this)
            }), e.ui.ddmanager && e.ui.ddmanager.dragStop(this, t), e.ui.mouse.prototype._mouseUp.call(this, t)
        },
        cancel: function () {
            return this.helper.is(".ui-draggable-dragging") ? this._mouseUp({}) : this._clear(), this
        },
        _getHandle: function (t) {
            var i = this.options.handle && e(this.options.handle, this.element).length ? !1 : !0;
            return e(this.options.handle, this.element).find("*").andSelf().each(function () {
                this == t.target && (i = !0)
            }), i
        },
        _createHelper: function (t) {
            var i = this.options, s = e.isFunction(i.helper) ? e(i.helper.apply(this.element[0], [t])) : "clone" == i.helper ? this.element.clone().removeAttr("id") : this.element;
            return s.parents("body").length || s.appendTo("parent" == i.appendTo ? this.element[0].parentNode : i.appendTo), s[0] == this.element[0] || /(fixed|absolute)/.test(s.css("position")) || s.css("position", "absolute"), s
        },
        _adjustOffsetFromHelper: function (t) {
            "string" == typeof t && (t = t.split(" ")), e.isArray(t) && (t = {
                left: +t[0],
                top: +t[1] || 0
            }), "left"in t && (this.offset.click.left = t.left + this.margins.left), "right"in t && (this.offset.click.left = this.helperProportions.width - t.right + this.margins.left), "top"in t && (this.offset.click.top = t.top + this.margins.top), "bottom"in t && (this.offset.click.top = this.helperProportions.height - t.bottom + this.margins.top)
        },
        _getParentOffset: function () {
            this.offsetParent = this.helper.offsetParent();
            var t = this.offsetParent.offset();
            return "absolute" == this.cssPosition && this.scrollParent[0] != document && e.contains(this.scrollParent[0], this.offsetParent[0]) && (t.left += this.scrollParent.scrollLeft(), t.top += this.scrollParent.scrollTop()), (this.offsetParent[0] == document.body || this.offsetParent[0].tagName && "html" == this.offsetParent[0].tagName.toLowerCase() && e.ui.ie) && (t = {
                top: 0,
                left: 0
            }), {
                top: t.top + (parseInt(this.offsetParent.css("borderTopWidth"), 10) || 0),
                left: t.left + (parseInt(this.offsetParent.css("borderLeftWidth"), 10) || 0)
            }
        },
        _getRelativeOffset: function () {
            if ("relative" == this.cssPosition) {
                var e = this.element.position();
                return {
                    top: e.top - (parseInt(this.helper.css("top"), 10) || 0) + this.scrollParent.scrollTop(),
                    left: e.left - (parseInt(this.helper.css("left"), 10) || 0) + this.scrollParent.scrollLeft()
                }
            }
            return {top: 0, left: 0}
        },
        _cacheMargins: function () {
            this.margins = {
                left: parseInt(this.element.css("marginLeft"), 10) || 0,
                top: parseInt(this.element.css("marginTop"), 10) || 0,
                right: parseInt(this.element.css("marginRight"), 10) || 0,
                bottom: parseInt(this.element.css("marginBottom"), 10) || 0
            }
        },
        _cacheHelperProportions: function () {
            this.helperProportions = {width: this.helper.outerWidth(), height: this.helper.outerHeight()}
        },
        _setContainment: function () {
            var t = this.options;
            if ("parent" == t.containment && (t.containment = this.helper[0].parentNode), ("document" == t.containment || "window" == t.containment) && (this.containment = ["document" == t.containment ? 0 : e(window).scrollLeft() - this.offset.relative.left - this.offset.parent.left, "document" == t.containment ? 0 : e(window).scrollTop() - this.offset.relative.top - this.offset.parent.top, ("document" == t.containment ? 0 : e(window).scrollLeft()) + e("document" == t.containment ? document : window).width() - this.helperProportions.width - this.margins.left, ("document" == t.containment ? 0 : e(window).scrollTop()) + (e("document" == t.containment ? document : window).height() || document.body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top]), /^(document|window|parent)$/.test(t.containment) || t.containment.constructor == Array)t.containment.constructor == Array && (this.containment = t.containment); else {
                var i = e(t.containment), s = i[0];
                if (!s)return;
                i.offset();
                var n = "hidden" != e(s).css("overflow");
                this.containment = [(parseInt(e(s).css("borderLeftWidth"), 10) || 0) + (parseInt(e(s).css("paddingLeft"), 10) || 0), (parseInt(e(s).css("borderTopWidth"), 10) || 0) + (parseInt(e(s).css("paddingTop"), 10) || 0), (n ? Math.max(s.scrollWidth, s.offsetWidth) : s.offsetWidth) - (parseInt(e(s).css("borderLeftWidth"), 10) || 0) - (parseInt(e(s).css("paddingRight"), 10) || 0) - this.helperProportions.width - this.margins.left - this.margins.right, (n ? Math.max(s.scrollHeight, s.offsetHeight) : s.offsetHeight) - (parseInt(e(s).css("borderTopWidth"), 10) || 0) - (parseInt(e(s).css("paddingBottom"), 10) || 0) - this.helperProportions.height - this.margins.top - this.margins.bottom], this.relative_container = i
            }
        },
        _convertPositionTo: function (t, i) {
            i || (i = this.position);
            var s = "absolute" == t ? 1 : -1, n = (this.options, "absolute" != this.cssPosition || this.scrollParent[0] != document && e.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent : this.offsetParent), a = /(html|body)/i.test(n[0].tagName);
            return {
                top: i.top + this.offset.relative.top * s + this.offset.parent.top * s - ("fixed" == this.cssPosition ? -this.scrollParent.scrollTop() : a ? 0 : n.scrollTop()) * s,
                left: i.left + this.offset.relative.left * s + this.offset.parent.left * s - ("fixed" == this.cssPosition ? -this.scrollParent.scrollLeft() : a ? 0 : n.scrollLeft()) * s
            }
        },
        _generatePosition: function (t) {
            var i = this.options, s = "absolute" != this.cssPosition || this.scrollParent[0] != document && e.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent : this.offsetParent, n = /(html|body)/i.test(s[0].tagName), a = t.pageX, o = t.pageY;
            if (this.originalPosition) {
                var r;
                if (this.containment) {
                    if (this.relative_container) {
                        var l = this.relative_container.offset();
                        r = [this.containment[0] + l.left, this.containment[1] + l.top, this.containment[2] + l.left, this.containment[3] + l.top]
                    } else r = this.containment;
                    t.pageX - this.offset.click.left < r[0] && (a = r[0] + this.offset.click.left), t.pageY - this.offset.click.top < r[1] && (o = r[1] + this.offset.click.top), t.pageX - this.offset.click.left > r[2] && (a = r[2] + this.offset.click.left), t.pageY - this.offset.click.top > r[3] && (o = r[3] + this.offset.click.top)
                }
                if (i.grid) {
                    var h = i.grid[1] ? this.originalPageY + Math.round((o - this.originalPageY) / i.grid[1]) * i.grid[1] : this.originalPageY;
                    o = r ? h - this.offset.click.top < r[1] || h - this.offset.click.top > r[3] ? h - this.offset.click.top < r[1] ? h + i.grid[1] : h - i.grid[1] : h : h;
                    var u = i.grid[0] ? this.originalPageX + Math.round((a - this.originalPageX) / i.grid[0]) * i.grid[0] : this.originalPageX;
                    a = r ? u - this.offset.click.left < r[0] || u - this.offset.click.left > r[2] ? u - this.offset.click.left < r[0] ? u + i.grid[0] : u - i.grid[0] : u : u
                }
            }
            return {
                top: o - this.offset.click.top - this.offset.relative.top - this.offset.parent.top + ("fixed" == this.cssPosition ? -this.scrollParent.scrollTop() : n ? 0 : s.scrollTop()),
                left: a - this.offset.click.left - this.offset.relative.left - this.offset.parent.left + ("fixed" == this.cssPosition ? -this.scrollParent.scrollLeft() : n ? 0 : s.scrollLeft())
            }
        },
        _clear: function () {
            this.helper.removeClass("ui-draggable-dragging"), this.helper[0] == this.element[0] || this.cancelHelperRemoval || this.helper.remove(), this.helper = null, this.cancelHelperRemoval = !1
        },
        _trigger: function (t, i, s) {
            return s = s || this._uiHash(), e.ui.plugin.call(this, t, [i, s]), "drag" == t && (this.positionAbs = this._convertPositionTo("absolute")), e.Widget.prototype._trigger.call(this, t, i, s)
        },
        plugins: {},
        _uiHash: function () {
            return {
                helper: this.helper,
                position: this.position,
                originalPosition: this.originalPosition,
                offset: this.positionAbs
            }
        }
    }), e.ui.plugin.add("draggable", "connectToSortable", {
        start: function (t, i) {
            var s = e(this).data("draggable"), n = s.options, a = e.extend({}, i, {item: s.element});
            s.sortables = [], e(n.connectToSortable).each(function () {
                var i = e.data(this, "sortable");
                i && !i.options.disabled && (s.sortables.push({
                    instance: i,
                    shouldRevert: i.options.revert
                }), i.refreshPositions(), i._trigger("activate", t, a))
            })
        }, stop: function (t, i) {
            var s = e(this).data("draggable"), n = e.extend({}, i, {item: s.element});
            e.each(s.sortables, function () {
                this.instance.isOver ? (this.instance.isOver = 0, s.cancelHelperRemoval = !0, this.instance.cancelHelperRemoval = !1, this.shouldRevert && (this.instance.options.revert = !0), this.instance._mouseStop(t), this.instance.options.helper = this.instance.options._helper, "original" == s.options.helper && this.instance.currentItem.css({
                    top: "auto",
                    left: "auto"
                })) : (this.instance.cancelHelperRemoval = !1, this.instance._trigger("deactivate", t, n))
            })
        }, drag: function (t, i) {
            var s = e(this).data("draggable"), n = this;
            e.each(s.sortables, function () {
                var a = !1, o = this;
                this.instance.positionAbs = s.positionAbs, this.instance.helperProportions = s.helperProportions, this.instance.offset.click = s.offset.click, this.instance._intersectsWith(this.instance.containerCache) && (a = !0, e.each(s.sortables, function () {
                    return this.instance.positionAbs = s.positionAbs, this.instance.helperProportions = s.helperProportions, this.instance.offset.click = s.offset.click, this != o && this.instance._intersectsWith(this.instance.containerCache) && e.ui.contains(o.instance.element[0], this.instance.element[0]) && (a = !1), a
                })), a ? (this.instance.isOver || (this.instance.isOver = 1, this.instance.currentItem = e(n).clone().removeAttr("id").appendTo(this.instance.element).data("sortable-item", !0), this.instance.options._helper = this.instance.options.helper, this.instance.options.helper = function () {
                    return i.helper[0]
                }, t.target = this.instance.currentItem[0], this.instance._mouseCapture(t, !0), this.instance._mouseStart(t, !0, !0), this.instance.offset.click.top = s.offset.click.top, this.instance.offset.click.left = s.offset.click.left, this.instance.offset.parent.left -= s.offset.parent.left - this.instance.offset.parent.left, this.instance.offset.parent.top -= s.offset.parent.top - this.instance.offset.parent.top, s._trigger("toSortable", t), s.dropped = this.instance.element, s.currentItem = s.element, this.instance.fromOutside = s), this.instance.currentItem && this.instance._mouseDrag(t)) : this.instance.isOver && (this.instance.isOver = 0, this.instance.cancelHelperRemoval = !0, this.instance.options.revert = !1, this.instance._trigger("out", t, this.instance._uiHash(this.instance)), this.instance._mouseStop(t, !0), this.instance.options.helper = this.instance.options._helper, this.instance.currentItem.remove(), this.instance.placeholder && this.instance.placeholder.remove(), s._trigger("fromSortable", t), s.dropped = !1)
            })
        }
    }), e.ui.plugin.add("draggable", "cursor", {
        start: function () {
            var t = e("body"), i = e(this).data("draggable").options;
            t.css("cursor") && (i._cursor = t.css("cursor")), t.css("cursor", i.cursor)
        }, stop: function () {
            var t = e(this).data("draggable").options;
            t._cursor && e("body").css("cursor", t._cursor)
        }
    }), e.ui.plugin.add("draggable", "opacity", {
        start: function (t, i) {
            var s = e(i.helper), n = e(this).data("draggable").options;
            s.css("opacity") && (n._opacity = s.css("opacity")), s.css("opacity", n.opacity)
        }, stop: function (t, i) {
            var s = e(this).data("draggable").options;
            s._opacity && e(i.helper).css("opacity", s._opacity)
        }
    }), e.ui.plugin.add("draggable", "scroll", {
        start: function () {
            var t = e(this).data("draggable");
            t.scrollParent[0] != document && "HTML" != t.scrollParent[0].tagName && (t.overflowOffset = t.scrollParent.offset())
        }, drag: function (t) {
            var i = e(this).data("draggable"), s = i.options, n = !1;
            i.scrollParent[0] != document && "HTML" != i.scrollParent[0].tagName ? (s.axis && "x" == s.axis || (i.overflowOffset.top + i.scrollParent[0].offsetHeight - t.pageY < s.scrollSensitivity ? i.scrollParent[0].scrollTop = n = i.scrollParent[0].scrollTop + s.scrollSpeed : t.pageY - i.overflowOffset.top < s.scrollSensitivity && (i.scrollParent[0].scrollTop = n = i.scrollParent[0].scrollTop - s.scrollSpeed)), s.axis && "y" == s.axis || (i.overflowOffset.left + i.scrollParent[0].offsetWidth - t.pageX < s.scrollSensitivity ? i.scrollParent[0].scrollLeft = n = i.scrollParent[0].scrollLeft + s.scrollSpeed : t.pageX - i.overflowOffset.left < s.scrollSensitivity && (i.scrollParent[0].scrollLeft = n = i.scrollParent[0].scrollLeft - s.scrollSpeed))) : (s.axis && "x" == s.axis || (t.pageY - e(document).scrollTop() < s.scrollSensitivity ? n = e(document).scrollTop(e(document).scrollTop() - s.scrollSpeed) : e(window).height() - (t.pageY - e(document).scrollTop()) < s.scrollSensitivity && (n = e(document).scrollTop(e(document).scrollTop() + s.scrollSpeed))), s.axis && "y" == s.axis || (t.pageX - e(document).scrollLeft() < s.scrollSensitivity ? n = e(document).scrollLeft(e(document).scrollLeft() - s.scrollSpeed) : e(window).width() - (t.pageX - e(document).scrollLeft()) < s.scrollSensitivity && (n = e(document).scrollLeft(e(document).scrollLeft() + s.scrollSpeed)))), n !== !1 && e.ui.ddmanager && !s.dropBehaviour && e.ui.ddmanager.prepareOffsets(i, t)
        }
    }), e.ui.plugin.add("draggable", "snap", {
        start: function () {
            var t = e(this).data("draggable"), i = t.options;
            t.snapElements = [], e(i.snap.constructor != String ? i.snap.items || ":data(draggable)" : i.snap).each(function () {
                var i = e(this), s = i.offset();
                this != t.element[0] && t.snapElements.push({
                    item: this,
                    width: i.outerWidth(),
                    height: i.outerHeight(),
                    top: s.top,
                    left: s.left
                })
            })
        }, drag: function (t, i) {
            for (var s = e(this).data("draggable"), n = s.options, a = n.snapTolerance, o = i.offset.left, r = o + s.helperProportions.width, l = i.offset.top, h = l + s.helperProportions.height, u = s.snapElements.length - 1; u >= 0; u--) {
                var d = s.snapElements[u].left, c = d + s.snapElements[u].width, p = s.snapElements[u].top, f = p + s.snapElements[u].height;
                if (o > d - a && c + a > o && l > p - a && f + a > l || o > d - a && c + a > o && h > p - a && f + a > h || r > d - a && c + a > r && l > p - a && f + a > l || r > d - a && c + a > r && h > p - a && f + a > h) {
                    if ("inner" != n.snapMode) {
                        var m = a >= Math.abs(p - h), g = a >= Math.abs(f - l), v = a >= Math.abs(d - r), y = a >= Math.abs(c - o);
                        m && (i.position.top = s._convertPositionTo("relative", {
                                top: p - s.helperProportions.height,
                                left: 0
                            }).top - s.margins.top), g && (i.position.top = s._convertPositionTo("relative", {
                                top: f,
                                left: 0
                            }).top - s.margins.top), v && (i.position.left = s._convertPositionTo("relative", {
                                top: 0,
                                left: d - s.helperProportions.width
                            }).left - s.margins.left), y && (i.position.left = s._convertPositionTo("relative", {
                                top: 0,
                                left: c
                            }).left - s.margins.left)
                    }
                    var b = m || g || v || y;
                    if ("outer" != n.snapMode) {
                        var m = a >= Math.abs(p - l), g = a >= Math.abs(f - h), v = a >= Math.abs(d - o), y = a >= Math.abs(c - r);
                        m && (i.position.top = s._convertPositionTo("relative", {
                                top: p,
                                left: 0
                            }).top - s.margins.top), g && (i.position.top = s._convertPositionTo("relative", {
                                top: f - s.helperProportions.height,
                                left: 0
                            }).top - s.margins.top), v && (i.position.left = s._convertPositionTo("relative", {
                                top: 0,
                                left: d
                            }).left - s.margins.left), y && (i.position.left = s._convertPositionTo("relative", {
                                top: 0,
                                left: c - s.helperProportions.width
                            }).left - s.margins.left)
                    }
                    !s.snapElements[u].snapping && (m || g || v || y || b) && s.options.snap.snap && s.options.snap.snap.call(s.element, t, e.extend(s._uiHash(), {snapItem: s.snapElements[u].item})), s.snapElements[u].snapping = m || g || v || y || b
                } else s.snapElements[u].snapping && s.options.snap.release && s.options.snap.release.call(s.element, t, e.extend(s._uiHash(), {snapItem: s.snapElements[u].item})), s.snapElements[u].snapping = !1
            }
        }
    }), e.ui.plugin.add("draggable", "stack", {
        start: function () {
            var t = e(this).data("draggable").options, i = e.makeArray(e(t.stack)).sort(function (t, i) {
                return (parseInt(e(t).css("zIndex"), 10) || 0) - (parseInt(e(i).css("zIndex"), 10) || 0)
            });
            if (i.length) {
                var s = parseInt(i[0].style.zIndex) || 0;
                e(i).each(function (e) {
                    this.style.zIndex = s + e
                }), this[0].style.zIndex = s + i.length
            }
        }
    }), e.ui.plugin.add("draggable", "zIndex", {
        start: function (t, i) {
            var s = e(i.helper), n = e(this).data("draggable").options;
            s.css("zIndex") && (n._zIndex = s.css("zIndex")), s.css("zIndex", n.zIndex)
        }, stop: function (t, i) {
            var s = e(this).data("draggable").options;
            s._zIndex && e(i.helper).css("zIndex", s._zIndex)
        }
    })
})(jQuery);
(function (e) {
    e.widget("ui.droppable", {
        version: "1.9.2",
        widgetEventPrefix: "drop",
        options: {
            accept: "*",
            activeClass: !1,
            addClasses: !0,
            greedy: !1,
            hoverClass: !1,
            scope: "default",
            tolerance: "intersect"
        },
        _create: function () {
            var t = this.options, i = t.accept;
            this.isover = 0, this.isout = 1, this.accept = e.isFunction(i) ? i : function (e) {
                return e.is(i)
            }, this.proportions = {
                width: this.element[0].offsetWidth,
                height: this.element[0].offsetHeight
            }, e.ui.ddmanager.droppables[t.scope] = e.ui.ddmanager.droppables[t.scope] || [], e.ui.ddmanager.droppables[t.scope].push(this), t.addClasses && this.element.addClass("ui-droppable")
        },
        _destroy: function () {
            for (var t = e.ui.ddmanager.droppables[this.options.scope], i = 0; t.length > i; i++)t[i] == this && t.splice(i, 1);
            this.element.removeClass("ui-droppable ui-droppable-disabled")
        },
        _setOption: function (t, i) {
            "accept" == t && (this.accept = e.isFunction(i) ? i : function (e) {
                return e.is(i)
            }), e.Widget.prototype._setOption.apply(this, arguments)
        },
        _activate: function (t) {
            var i = e.ui.ddmanager.current;
            this.options.activeClass && this.element.addClass(this.options.activeClass), i && this._trigger("activate", t, this.ui(i))
        },
        _deactivate: function (t) {
            var i = e.ui.ddmanager.current;
            this.options.activeClass && this.element.removeClass(this.options.activeClass), i && this._trigger("deactivate", t, this.ui(i))
        },
        _over: function (t) {
            var i = e.ui.ddmanager.current;
            i && (i.currentItem || i.element)[0] != this.element[0] && this.accept.call(this.element[0], i.currentItem || i.element) && (this.options.hoverClass && this.element.addClass(this.options.hoverClass), this._trigger("over", t, this.ui(i)))
        },
        _out: function (t) {
            var i = e.ui.ddmanager.current;
            i && (i.currentItem || i.element)[0] != this.element[0] && this.accept.call(this.element[0], i.currentItem || i.element) && (this.options.hoverClass && this.element.removeClass(this.options.hoverClass), this._trigger("out", t, this.ui(i)))
        },
        _drop: function (t, i) {
            var s = i || e.ui.ddmanager.current;
            if (!s || (s.currentItem || s.element)[0] == this.element[0])return !1;
            var n = !1;
            return this.element.find(":data(droppable)").not(".ui-draggable-dragging").each(function () {
                var t = e.data(this, "droppable");
                return t.options.greedy && !t.options.disabled && t.options.scope == s.options.scope && t.accept.call(t.element[0], s.currentItem || s.element) && e.ui.intersect(s, e.extend(t, {offset: t.element.offset()}), t.options.tolerance) ? (n = !0, !1) : undefined
            }), n ? !1 : this.accept.call(this.element[0], s.currentItem || s.element) ? (this.options.activeClass && this.element.removeClass(this.options.activeClass), this.options.hoverClass && this.element.removeClass(this.options.hoverClass), this._trigger("drop", t, this.ui(s)), this.element) : !1
        },
        ui: function (e) {
            return {
                draggable: e.currentItem || e.element,
                helper: e.helper,
                position: e.position,
                offset: e.positionAbs
            }
        }
    }), e.ui.intersect = function (t, i, s) {
        if (!i.offset)return !1;
        var n = (t.positionAbs || t.position.absolute).left, a = n + t.helperProportions.width, o = (t.positionAbs || t.position.absolute).top, r = o + t.helperProportions.height, l = i.offset.left, h = l + i.proportions.width, u = i.offset.top, d = u + i.proportions.height;
        switch (s) {
            case"fit":
                return n >= l && h >= a && o >= u && d >= r;
            case"intersect":
                return n + t.helperProportions.width / 2 > l && h > a - t.helperProportions.width / 2 && o + t.helperProportions.height / 2 > u && d > r - t.helperProportions.height / 2;
            case"pointer":
                var c = (t.positionAbs || t.position.absolute).left + (t.clickOffset || t.offset.click).left, p = (t.positionAbs || t.position.absolute).top + (t.clickOffset || t.offset.click).top, f = e.ui.isOver(p, c, u, l, i.proportions.height, i.proportions.width);
                return f;
            case"touch":
                return (o >= u && d >= o || r >= u && d >= r || u > o && r > d) && (n >= l && h >= n || a >= l && h >= a || l > n && a > h);
            default:
                return !1
        }
    }, e.ui.ddmanager = {
        current: null, droppables: {"default": []}, prepareOffsets: function (t, i) {
            var s = e.ui.ddmanager.droppables[t.options.scope] || [], n = i ? i.type : null, a = (t.currentItem || t.element).find(":data(droppable)").andSelf();
            e:for (var o = 0; s.length > o; o++)if (!(s[o].options.disabled || t && !s[o].accept.call(s[o].element[0], t.currentItem || t.element))) {
                for (var r = 0; a.length > r; r++)if (a[r] == s[o].element[0]) {
                    s[o].proportions.height = 0;
                    continue e
                }
                s[o].visible = "none" != s[o].element.css("display"), s[o].visible && ("mousedown" == n && s[o]._activate.call(s[o], i), s[o].offset = s[o].element.offset(), s[o].proportions = {
                    width: s[o].element[0].offsetWidth,
                    height: s[o].element[0].offsetHeight
                })
            }
        }, drop: function (t, i) {
            var s = !1;
            return e.each(e.ui.ddmanager.droppables[t.options.scope] || [], function () {
                this.options && (!this.options.disabled && this.visible && e.ui.intersect(t, this, this.options.tolerance) && (s = this._drop.call(this, i) || s), !this.options.disabled && this.visible && this.accept.call(this.element[0], t.currentItem || t.element) && (this.isout = 1, this.isover = 0, this._deactivate.call(this, i)))
            }), s
        }, dragStart: function (t, i) {
            t.element.parentsUntil("body").bind("scroll.droppable", function () {
                t.options.refreshPositions || e.ui.ddmanager.prepareOffsets(t, i)
            })
        }, drag: function (t, i) {
            t.options.refreshPositions && e.ui.ddmanager.prepareOffsets(t, i), e.each(e.ui.ddmanager.droppables[t.options.scope] || [], function () {
                if (!this.options.disabled && !this.greedyChild && this.visible) {
                    var s = e.ui.intersect(t, this, this.options.tolerance), n = s || 1 != this.isover ? s && 0 == this.isover ? "isover" : null : "isout";
                    if (n) {
                        var a;
                        if (this.options.greedy) {
                            var o = this.options.scope, r = this.element.parents(":data(droppable)").filter(function () {
                                return e.data(this, "droppable").options.scope === o
                            });
                            r.length && (a = e.data(r[0], "droppable"), a.greedyChild = "isover" == n ? 1 : 0)
                        }
                        a && "isover" == n && (a.isover = 0, a.isout = 1, a._out.call(a, i)), this[n] = 1, this["isout" == n ? "isover" : "isout"] = 0, this["isover" == n ? "_over" : "_out"].call(this, i), a && "isout" == n && (a.isout = 0, a.isover = 1, a._over.call(a, i))
                    }
                }
            })
        }, dragStop: function (t, i) {
            t.element.parentsUntil("body").unbind("scroll.droppable"), t.options.refreshPositions || e.ui.ddmanager.prepareOffsets(t, i)
        }
    }
})(jQuery);
(function (e) {
    e.widget("ui.resizable", e.ui.mouse, {
        version: "1.9.2",
        widgetEventPrefix: "resize",
        options: {
            alsoResize: !1,
            animate: !1,
            animateDuration: "slow",
            animateEasing: "swing",
            aspectRatio: !1,
            autoHide: !1,
            containment: !1,
            ghost: !1,
            grid: !1,
            handles: "e,s,se",
            helper: !1,
            maxHeight: null,
            maxWidth: null,
            minHeight: 10,
            minWidth: 10,
            zIndex: 1e3
        },
        _create: function () {
            var t = this, i = this.options;
            if (this.element.addClass("ui-resizable"), e.extend(this, {
                    _aspectRatio: !!i.aspectRatio,
                    aspectRatio: i.aspectRatio,
                    originalElement: this.element,
                    _proportionallyResizeElements: [],
                    _helper: i.helper || i.ghost || i.animate ? i.helper || "ui-resizable-helper" : null
                }), this.element[0].nodeName.match(/canvas|textarea|input|select|button|img/i) && (this.element.wrap(e('<div class="ui-wrapper" style="overflow: hidden;"></div>').css({
                    position: this.element.css("position"),
                    width: this.element.outerWidth(),
                    height: this.element.outerHeight(),
                    top: this.element.css("top"),
                    left: this.element.css("left")
                })), this.element = this.element.parent().data("resizable", this.element.data("resizable")), this.elementIsWrapper = !0, this.element.css({
                    marginLeft: this.originalElement.css("marginLeft"),
                    marginTop: this.originalElement.css("marginTop"),
                    marginRight: this.originalElement.css("marginRight"),
                    marginBottom: this.originalElement.css("marginBottom")
                }), this.originalElement.css({
                    marginLeft: 0,
                    marginTop: 0,
                    marginRight: 0,
                    marginBottom: 0
                }), this.originalResizeStyle = this.originalElement.css("resize"), this.originalElement.css("resize", "none"), this._proportionallyResizeElements.push(this.originalElement.css({
                    position: "static",
                    zoom: 1,
                    display: "block"
                })), this.originalElement.css({margin: this.originalElement.css("margin")}), this._proportionallyResize()), this.handles = i.handles || (e(".ui-resizable-handle", this.element).length ? {
                        n: ".ui-resizable-n",
                        e: ".ui-resizable-e",
                        s: ".ui-resizable-s",
                        w: ".ui-resizable-w",
                        se: ".ui-resizable-se",
                        sw: ".ui-resizable-sw",
                        ne: ".ui-resizable-ne",
                        nw: ".ui-resizable-nw"
                    } : "e,s,se"), this.handles.constructor == String) {
                "all" == this.handles && (this.handles = "n,e,s,w,se,sw,ne,nw");
                var s = this.handles.split(",");
                this.handles = {};
                for (var n = 0; s.length > n; n++) {
                    var a = e.trim(s[n]), o = "ui-resizable-" + a, r = e('<div class="ui-resizable-handle ' + o + '"></div>');
                    r.css({zIndex: i.zIndex}), "se" == a && r.addClass("ui-icon ui-icon-gripsmall-diagonal-se"), this.handles[a] = ".ui-resizable-" + a, this.element.append(r)
                }
            }
            this._renderAxis = function (t) {
                t = t || this.element;
                for (var i in this.handles) {
                    if (this.handles[i].constructor == String && (this.handles[i] = e(this.handles[i], this.element).show()), this.elementIsWrapper && this.originalElement[0].nodeName.match(/textarea|input|select|button/i)) {
                        var s = e(this.handles[i], this.element), n = 0;
                        n = /sw|ne|nw|se|n|s/.test(i) ? s.outerHeight() : s.outerWidth();
                        var a = ["padding", /ne|nw|n/.test(i) ? "Top" : /se|sw|s/.test(i) ? "Bottom" : /^e$/.test(i) ? "Right" : "Left"].join("");
                        t.css(a, n), this._proportionallyResize()
                    }
                    e(this.handles[i]).length
                }
            }, this._renderAxis(this.element), this._handles = e(".ui-resizable-handle", this.element).disableSelection(), this._handles.mouseover(function () {
                if (!t.resizing) {
                    if (this.className)var e = this.className.match(/ui-resizable-(se|sw|ne|nw|n|e|s|w)/i);
                    t.axis = e && e[1] ? e[1] : "se"
                }
            }), i.autoHide && (this._handles.hide(), e(this.element).addClass("ui-resizable-autohide").mouseenter(function () {
                i.disabled || (e(this).removeClass("ui-resizable-autohide"), t._handles.show())
            }).mouseleave(function () {
                i.disabled || t.resizing || (e(this).addClass("ui-resizable-autohide"), t._handles.hide())
            })), this._mouseInit()
        },
        _destroy: function () {
            this._mouseDestroy();
            var t = function (t) {
                e(t).removeClass("ui-resizable ui-resizable-disabled ui-resizable-resizing").removeData("resizable").removeData("ui-resizable").unbind(".resizable").find(".ui-resizable-handle").remove()
            };
            if (this.elementIsWrapper) {
                t(this.element);
                var i = this.element;
                this.originalElement.css({
                    position: i.css("position"),
                    width: i.outerWidth(),
                    height: i.outerHeight(),
                    top: i.css("top"),
                    left: i.css("left")
                }).insertAfter(i), i.remove()
            }
            return this.originalElement.css("resize", this.originalResizeStyle), t(this.originalElement), this
        },
        _mouseCapture: function (t) {
            var i = !1;
            for (var s in this.handles)e(this.handles[s])[0] == t.target && (i = !0);
            return !this.options.disabled && i
        },
        _mouseStart: function (i) {
            var s = this.options, n = this.element.position(), a = this.element;
            this.resizing = !0, this.documentScroll = {
                top: e(document).scrollTop(),
                left: e(document).scrollLeft()
            }, (a.is(".ui-draggable") || /absolute/.test(a.css("position"))) && a.css({
                position: "absolute",
                top: n.top,
                left: n.left
            }), this._renderProxy();
            var o = t(this.helper.css("left")), r = t(this.helper.css("top"));
            s.containment && (o += e(s.containment).scrollLeft() || 0, r += e(s.containment).scrollTop() || 0), this.offset = this.helper.offset(), this.position = {
                left: o,
                top: r
            }, this.size = this._helper ? {width: a.outerWidth(), height: a.outerHeight()} : {
                width: a.width(),
                height: a.height()
            }, this.originalSize = this._helper ? {width: a.outerWidth(), height: a.outerHeight()} : {
                width: a.width(),
                height: a.height()
            }, this.originalPosition = {left: o, top: r}, this.sizeDiff = {
                width: a.outerWidth() - a.width(),
                height: a.outerHeight() - a.height()
            }, this.originalMousePosition = {
                left: i.pageX,
                top: i.pageY
            }, this.aspectRatio = "number" == typeof s.aspectRatio ? s.aspectRatio : this.originalSize.width / this.originalSize.height || 1;
            var h = e(".ui-resizable-" + this.axis).css("cursor");
            return e("body").css("cursor", "auto" == h ? this.axis + "-resize" : h), a.addClass("ui-resizable-resizing"), this._propagate("start", i), !0
        },
        _mouseDrag: function (e) {
            var t = this.helper, i = (this.options, this.originalMousePosition), s = this.axis, n = e.pageX - i.left || 0, a = e.pageY - i.top || 0, o = this._change[s];
            if (!o)return !1;
            var r = o.apply(this, [e, n, a]);
            return this._updateVirtualBoundaries(e.shiftKey), (this._aspectRatio || e.shiftKey) && (r = this._updateRatio(r, e)), r = this._respectSize(r, e), this._propagate("resize", e), t.css({
                top: this.position.top + "px",
                left: this.position.left + "px",
                width: this.size.width + "px",
                height: this.size.height + "px"
            }), !this._helper && this._proportionallyResizeElements.length && this._proportionallyResize(), this._updateCache(r), this._trigger("resize", e, this.ui()), !1
        },
        _mouseStop: function (t) {
            this.resizing = !1;
            var i = this.options, s = this;
            if (this._helper) {
                var n = this._proportionallyResizeElements, a = n.length && /textarea/i.test(n[0].nodeName), o = a && e.ui.hasScroll(n[0], "left") ? 0 : s.sizeDiff.height, r = a ? 0 : s.sizeDiff.width, h = {
                    width: s.helper.width() - r,
                    height: s.helper.height() - o
                }, l = parseInt(s.element.css("left"), 10) + (s.position.left - s.originalPosition.left) || null, u = parseInt(s.element.css("top"), 10) + (s.position.top - s.originalPosition.top) || null;
                i.animate || this.element.css(e.extend(h, {
                    top: u,
                    left: l
                })), s.helper.height(s.size.height), s.helper.width(s.size.width), this._helper && !i.animate && this._proportionallyResize()
            }
            return e("body").css("cursor", "auto"), this.element.removeClass("ui-resizable-resizing"), this._propagate("stop", t), this._helper && this.helper.remove(), !1
        },
        _updateVirtualBoundaries: function (e) {
            var t, s, n, a, o, r = this.options;
            o = {
                minWidth: i(r.minWidth) ? r.minWidth : 0,
                maxWidth: i(r.maxWidth) ? r.maxWidth : 1 / 0,
                minHeight: i(r.minHeight) ? r.minHeight : 0,
                maxHeight: i(r.maxHeight) ? r.maxHeight : 1 / 0
            }, (this._aspectRatio || e) && (t = o.minHeight * this.aspectRatio, n = o.minWidth / this.aspectRatio, s = o.maxHeight * this.aspectRatio, a = o.maxWidth / this.aspectRatio, t > o.minWidth && (o.minWidth = t), n > o.minHeight && (o.minHeight = n), o.maxWidth > s && (o.maxWidth = s), o.maxHeight > a && (o.maxHeight = a)), this._vBoundaries = o
        },
        _updateCache: function (e) {
            this.options, this.offset = this.helper.offset(), i(e.left) && (this.position.left = e.left), i(e.top) && (this.position.top = e.top), i(e.height) && (this.size.height = e.height), i(e.width) && (this.size.width = e.width)
        },
        _updateRatio: function (e) {
            var t = (this.options, this.position), s = this.size, n = this.axis;
            return i(e.height) ? e.width = e.height * this.aspectRatio : i(e.width) && (e.height = e.width / this.aspectRatio), "sw" == n && (e.left = t.left + (s.width - e.width), e.top = null), "nw" == n && (e.top = t.top + (s.height - e.height), e.left = t.left + (s.width - e.width)), e
        },
        _respectSize: function (e, t) {
            var s = (this.helper, this._vBoundaries), n = (this._aspectRatio || t.shiftKey, this.axis), a = i(e.width) && s.maxWidth && s.maxWidth < e.width, o = i(e.height) && s.maxHeight && s.maxHeight < e.height, r = i(e.width) && s.minWidth && s.minWidth > e.width, h = i(e.height) && s.minHeight && s.minHeight > e.height;
            r && (e.width = s.minWidth), h && (e.height = s.minHeight), a && (e.width = s.maxWidth), o && (e.height = s.maxHeight);
            var l = this.originalPosition.left + this.originalSize.width, u = this.position.top + this.size.height, d = /sw|nw|w/.test(n), c = /nw|ne|n/.test(n);
            r && d && (e.left = l - s.minWidth), a && d && (e.left = l - s.maxWidth), h && c && (e.top = u - s.minHeight), o && c && (e.top = u - s.maxHeight);
            var p = !e.width && !e.height;
            return p && !e.left && e.top ? e.top = null : p && !e.top && e.left && (e.left = null), e
        },
        _proportionallyResize: function () {
            if (this.options, this._proportionallyResizeElements.length)for (var t = this.helper || this.element, i = 0; this._proportionallyResizeElements.length > i; i++) {
                var s = this._proportionallyResizeElements[i];
                if (!this.borderDif) {
                    var n = [s.css("borderTopWidth"), s.css("borderRightWidth"), s.css("borderBottomWidth"), s.css("borderLeftWidth")], a = [s.css("paddingTop"), s.css("paddingRight"), s.css("paddingBottom"), s.css("paddingLeft")];
                    this.borderDif = e.map(n, function (e, t) {
                        var i = parseInt(e, 10) || 0, s = parseInt(a[t], 10) || 0;
                        return i + s
                    })
                }
                s.css({
                    height: t.height() - this.borderDif[0] - this.borderDif[2] || 0,
                    width: t.width() - this.borderDif[1] - this.borderDif[3] || 0
                })
            }
        },
        _renderProxy: function () {
            var t = this.element, i = this.options;
            if (this.elementOffset = t.offset(), this._helper) {
                this.helper = this.helper || e('<div style="overflow:hidden;"></div>');
                var s = e.ui.ie6 ? 1 : 0, n = e.ui.ie6 ? 2 : -1;
                this.helper.addClass(this._helper).css({
                    width: this.element.outerWidth() + n,
                    height: this.element.outerHeight() + n,
                    position: "absolute",
                    left: this.elementOffset.left - s + "px",
                    top: this.elementOffset.top - s + "px",
                    zIndex: ++i.zIndex
                }), this.helper.appendTo("body").disableSelection()
            } else this.helper = this.element
        },
        _change: {
            e: function (e, t) {
                return {width: this.originalSize.width + t}
            }, w: function (e, t) {
                var i = (this.options, this.originalSize), s = this.originalPosition;
                return {left: s.left + t, width: i.width - t}
            }, n: function (e, t, i) {
                var s = (this.options, this.originalSize), n = this.originalPosition;
                return {top: n.top + i, height: s.height - i}
            }, s: function (e, t, i) {
                return {height: this.originalSize.height + i}
            }, se: function (t, i, s) {
                return e.extend(this._change.s.apply(this, arguments), this._change.e.apply(this, [t, i, s]))
            }, sw: function (t, i, s) {
                return e.extend(this._change.s.apply(this, arguments), this._change.w.apply(this, [t, i, s]))
            }, ne: function (t, i, s) {
                return e.extend(this._change.n.apply(this, arguments), this._change.e.apply(this, [t, i, s]))
            }, nw: function (t, i, s) {
                return e.extend(this._change.n.apply(this, arguments), this._change.w.apply(this, [t, i, s]))
            }
        },
        _propagate: function (t, i) {
            e.ui.plugin.call(this, t, [i, this.ui()]), "resize" != t && this._trigger(t, i, this.ui())
        },
        plugins: {},
        ui: function () {
            return {
                originalElement: this.originalElement,
                element: this.element,
                helper: this.helper,
                position: this.position,
                size: this.size,
                originalSize: this.originalSize,
                originalPosition: this.originalPosition
            }
        }
    }), e.ui.plugin.add("resizable", "alsoResize", {
        start: function () {
            var t = e(this).data("resizable"), i = t.options, s = function (t) {
                e(t).each(function () {
                    var t = e(this);
                    t.data("resizable-alsoresize", {
                        width: parseInt(t.width(), 10),
                        height: parseInt(t.height(), 10),
                        left: parseInt(t.css("left"), 10),
                        top: parseInt(t.css("top"), 10)
                    })
                })
            };
            "object" != typeof i.alsoResize || i.alsoResize.parentNode ? s(i.alsoResize) : i.alsoResize.length ? (i.alsoResize = i.alsoResize[0], s(i.alsoResize)) : e.each(i.alsoResize, function (e) {
                s(e)
            })
        }, resize: function (t, i) {
            var s = e(this).data("resizable"), n = s.options, a = s.originalSize, o = s.originalPosition, r = {
                height: s.size.height - a.height || 0,
                width: s.size.width - a.width || 0,
                top: s.position.top - o.top || 0,
                left: s.position.left - o.left || 0
            }, h = function (t, s) {
                e(t).each(function () {
                    var t = e(this), n = e(this).data("resizable-alsoresize"), a = {}, o = s && s.length ? s : t.parents(i.originalElement[0]).length ? ["width", "height"] : ["width", "height", "top", "left"];
                    e.each(o, function (e, t) {
                        var i = (n[t] || 0) + (r[t] || 0);
                        i && i >= 0 && (a[t] = i || null)
                    }), t.css(a)
                })
            };
            "object" != typeof n.alsoResize || n.alsoResize.nodeType ? h(n.alsoResize) : e.each(n.alsoResize, function (e, t) {
                h(e, t)
            })
        }, stop: function () {
            e(this).removeData("resizable-alsoresize")
        }
    }), e.ui.plugin.add("resizable", "animate", {
        stop: function (t) {
            var i = e(this).data("resizable"), s = i.options, n = i._proportionallyResizeElements, a = n.length && /textarea/i.test(n[0].nodeName), o = a && e.ui.hasScroll(n[0], "left") ? 0 : i.sizeDiff.height, r = a ? 0 : i.sizeDiff.width, h = {
                width: i.size.width - r,
                height: i.size.height - o
            }, l = parseInt(i.element.css("left"), 10) + (i.position.left - i.originalPosition.left) || null, u = parseInt(i.element.css("top"), 10) + (i.position.top - i.originalPosition.top) || null;
            i.element.animate(e.extend(h, u && l ? {top: u, left: l} : {}), {
                duration: s.animateDuration,
                easing: s.animateEasing,
                step: function () {
                    var s = {
                        width: parseInt(i.element.css("width"), 10),
                        height: parseInt(i.element.css("height"), 10),
                        top: parseInt(i.element.css("top"), 10),
                        left: parseInt(i.element.css("left"), 10)
                    };
                    n && n.length && e(n[0]).css({
                        width: s.width,
                        height: s.height
                    }), i._updateCache(s), i._propagate("resize", t)
                }
            })
        }
    }), e.ui.plugin.add("resizable", "containment", {
        start: function () {
            var i = e(this).data("resizable"), s = i.options, n = i.element, a = s.containment, o = a instanceof e ? a.get(0) : /parent/.test(a) ? n.parent().get(0) : a;
            if (o)if (i.containerElement = e(o), /document/.test(a) || a == document)i.containerOffset = {
                left: 0,
                top: 0
            }, i.containerPosition = {left: 0, top: 0}, i.parentData = {
                element: e(document),
                left: 0,
                top: 0,
                width: e(document).width(),
                height: e(document).height() || document.body.parentNode.scrollHeight
            }; else {
                var r = e(o), h = [];
                e(["Top", "Right", "Left", "Bottom"]).each(function (e, i) {
                    h[e] = t(r.css("padding" + i))
                }), i.containerOffset = r.offset(), i.containerPosition = r.position(), i.containerSize = {
                    height: r.innerHeight() - h[3],
                    width: r.innerWidth() - h[1]
                };
                var l = i.containerOffset, u = i.containerSize.height, d = i.containerSize.width, c = e.ui.hasScroll(o, "left") ? o.scrollWidth : d, p = e.ui.hasScroll(o) ? o.scrollHeight : u;
                i.parentData = {element: o, left: l.left, top: l.top, width: c, height: p}
            }
        }, resize: function (t) {
            var i = e(this).data("resizable"), s = i.options, n = (i.containerSize, i.containerOffset), a = (i.size, i.position), o = i._aspectRatio || t.shiftKey, r = {
                top: 0,
                left: 0
            }, h = i.containerElement;
            h[0] != document && /static/.test(h.css("position")) && (r = n), a.left < (i._helper ? n.left : 0) && (i.size.width = i.size.width + (i._helper ? i.position.left - n.left : i.position.left - r.left), o && (i.size.height = i.size.width / i.aspectRatio), i.position.left = s.helper ? n.left : 0), a.top < (i._helper ? n.top : 0) && (i.size.height = i.size.height + (i._helper ? i.position.top - n.top : i.position.top), o && (i.size.width = i.size.height * i.aspectRatio), i.position.top = i._helper ? n.top : 0), i.offset.left = i.parentData.left + i.position.left, i.offset.top = i.parentData.top + i.position.top;
            var l = Math.abs((i._helper ? i.offset.left - r.left : i.offset.left - r.left) + i.sizeDiff.width), u = Math.abs((i._helper ? i.offset.top - r.top : i.offset.top - n.top) + i.sizeDiff.height), d = i.containerElement.get(0) == i.element.parent().get(0), c = /relative|absolute/.test(i.containerElement.css("position"));
            d && c && (l -= i.parentData.left), l + i.size.width >= i.parentData.width && (i.size.width = i.parentData.width - l, o && (i.size.height = i.size.width / i.aspectRatio)), u + i.size.height >= i.parentData.height && (i.size.height = i.parentData.height - u, o && (i.size.width = i.size.height * i.aspectRatio))
        }, stop: function () {
            var t = e(this).data("resizable"), i = t.options, s = (t.position, t.containerOffset), n = t.containerPosition, a = t.containerElement, o = e(t.helper), r = o.offset(), h = o.outerWidth() - t.sizeDiff.width, l = o.outerHeight() - t.sizeDiff.height;
            t._helper && !i.animate && /relative/.test(a.css("position")) && e(this).css({
                left: r.left - n.left - s.left,
                width: h,
                height: l
            }), t._helper && !i.animate && /static/.test(a.css("position")) && e(this).css({
                left: r.left - n.left - s.left,
                width: h,
                height: l
            })
        }
    }), e.ui.plugin.add("resizable", "ghost", {
        start: function () {
            var t = e(this).data("resizable"), i = t.options, s = t.size;
            t.ghost = t.originalElement.clone(), t.ghost.css({
                opacity: .25,
                display: "block",
                position: "relative",
                height: s.height,
                width: s.width,
                margin: 0,
                left: 0,
                top: 0
            }).addClass("ui-resizable-ghost").addClass("string" == typeof i.ghost ? i.ghost : ""), t.ghost.appendTo(t.helper)
        }, resize: function () {
            var t = e(this).data("resizable");
            t.options, t.ghost && t.ghost.css({position: "relative", height: t.size.height, width: t.size.width})
        }, stop: function () {
            var t = e(this).data("resizable");
            t.options, t.ghost && t.helper && t.helper.get(0).removeChild(t.ghost.get(0))
        }
    }), e.ui.plugin.add("resizable", "grid", {
        resize: function (t) {
            var i = e(this).data("resizable"), s = i.options, n = i.size, a = i.originalSize, o = i.originalPosition, r = i.axis;
            s._aspectRatio || t.shiftKey, s.grid = "number" == typeof s.grid ? [s.grid, s.grid] : s.grid;
            var h = Math.round((n.width - a.width) / (s.grid[0] || 1)) * (s.grid[0] || 1), l = Math.round((n.height - a.height) / (s.grid[1] || 1)) * (s.grid[1] || 1);
            /^(se|s|e)$/.test(r) ? (i.size.width = a.width + h, i.size.height = a.height + l) : /^(ne)$/.test(r) ? (i.size.width = a.width + h, i.size.height = a.height + l, i.position.top = o.top - l) : /^(sw)$/.test(r) ? (i.size.width = a.width + h, i.size.height = a.height + l, i.position.left = o.left - h) : (i.size.width = a.width + h, i.size.height = a.height + l, i.position.top = o.top - l, i.position.left = o.left - h)
        }
    });
    var t = function (e) {
        return parseInt(e, 10) || 0
    }, i = function (e) {
        return !isNaN(parseInt(e, 10))
    }
})(jQuery);
(function (e) {
    e.widget("ui.selectable", e.ui.mouse, {
        version: "1.9.2",
        options: {appendTo: "body", autoRefresh: !0, distance: 0, filter: "*", tolerance: "touch"},
        _create: function () {
            var t = this;
            this.element.addClass("ui-selectable"), this.dragged = !1;
            var i;
            this.refresh = function () {
                i = e(t.options.filter, t.element[0]), i.addClass("ui-selectee"), i.each(function () {
                    var t = e(this), i = t.offset();
                    e.data(this, "selectable-item", {
                        element: this,
                        $element: t,
                        left: i.left,
                        top: i.top,
                        right: i.left + t.outerWidth(),
                        bottom: i.top + t.outerHeight(),
                        startselected: !1,
                        selected: t.hasClass("ui-selected"),
                        selecting: t.hasClass("ui-selecting"),
                        unselecting: t.hasClass("ui-unselecting")
                    })
                })
            }, this.refresh(), this.selectees = i.addClass("ui-selectee"), this._mouseInit(), this.helper = e("<div class='ui-selectable-helper'></div>")
        },
        _destroy: function () {
            this.selectees.removeClass("ui-selectee").removeData("selectable-item"), this.element.removeClass("ui-selectable ui-selectable-disabled"), this._mouseDestroy()
        },
        _mouseStart: function (t) {
            var i = this;
            if (this.opos = [t.pageX, t.pageY], !this.options.disabled) {
                var s = this.options;
                this.selectees = e(s.filter, this.element[0]), this._trigger("start", t), e(s.appendTo).append(this.helper), this.helper.css({
                    left: t.clientX,
                    top: t.clientY,
                    width: 0,
                    height: 0
                }), s.autoRefresh && this.refresh(), this.selectees.filter(".ui-selected").each(function () {
                    var s = e.data(this, "selectable-item");
                    s.startselected = !0, t.metaKey || t.ctrlKey || (s.$element.removeClass("ui-selected"), s.selected = !1, s.$element.addClass("ui-unselecting"), s.unselecting = !0, i._trigger("unselecting", t, {unselecting: s.element}))
                }), e(t.target).parents().andSelf().each(function () {
                    var s = e.data(this, "selectable-item");
                    if (s) {
                        var n = !t.metaKey && !t.ctrlKey || !s.$element.hasClass("ui-selected");
                        return s.$element.removeClass(n ? "ui-unselecting" : "ui-selected").addClass(n ? "ui-selecting" : "ui-unselecting"), s.unselecting = !n, s.selecting = n, s.selected = n, n ? i._trigger("selecting", t, {selecting: s.element}) : i._trigger("unselecting", t, {unselecting: s.element}), !1
                    }
                })
            }
        },
        _mouseDrag: function (t) {
            var i = this;
            if (this.dragged = !0, !this.options.disabled) {
                var s = this.options, n = this.opos[0], a = this.opos[1], o = t.pageX, r = t.pageY;
                if (n > o) {
                    var h = o;
                    o = n, n = h
                }
                if (a > r) {
                    var h = r;
                    r = a, a = h
                }
                return this.helper.css({
                    left: n,
                    top: a,
                    width: o - n,
                    height: r - a
                }), this.selectees.each(function () {
                    var h = e.data(this, "selectable-item");
                    if (h && h.element != i.element[0]) {
                        var l = !1;
                        "touch" == s.tolerance ? l = !(h.left > o || n > h.right || h.top > r || a > h.bottom) : "fit" == s.tolerance && (l = h.left > n && o > h.right && h.top > a && r > h.bottom), l ? (h.selected && (h.$element.removeClass("ui-selected"), h.selected = !1), h.unselecting && (h.$element.removeClass("ui-unselecting"), h.unselecting = !1), h.selecting || (h.$element.addClass("ui-selecting"), h.selecting = !0, i._trigger("selecting", t, {selecting: h.element}))) : (h.selecting && ((t.metaKey || t.ctrlKey) && h.startselected ? (h.$element.removeClass("ui-selecting"), h.selecting = !1, h.$element.addClass("ui-selected"), h.selected = !0) : (h.$element.removeClass("ui-selecting"), h.selecting = !1, h.startselected && (h.$element.addClass("ui-unselecting"), h.unselecting = !0), i._trigger("unselecting", t, {unselecting: h.element}))), h.selected && (t.metaKey || t.ctrlKey || h.startselected || (h.$element.removeClass("ui-selected"), h.selected = !1, h.$element.addClass("ui-unselecting"), h.unselecting = !0, i._trigger("unselecting", t, {unselecting: h.element}))))
                    }
                }), !1
            }
        },
        _mouseStop: function (t) {
            var i = this;
            return this.dragged = !1, this.options, e(".ui-unselecting", this.element[0]).each(function () {
                var s = e.data(this, "selectable-item");
                s.$element.removeClass("ui-unselecting"), s.unselecting = !1, s.startselected = !1, i._trigger("unselected", t, {unselected: s.element})
            }), e(".ui-selecting", this.element[0]).each(function () {
                var s = e.data(this, "selectable-item");
                s.$element.removeClass("ui-selecting").addClass("ui-selected"), s.selecting = !1, s.selected = !0, s.startselected = !0, i._trigger("selected", t, {selected: s.element})
            }), this._trigger("stop", t), this.helper.remove(), !1
        }
    })
})(jQuery);
(function (e) {
    e.widget("ui.sortable", e.ui.mouse, {
        version: "1.9.2",
        widgetEventPrefix: "sort",
        ready: !1,
        options: {
            appendTo: "parent",
            axis: !1,
            connectWith: !1,
            containment: !1,
            cursor: "auto",
            cursorAt: !1,
            dropOnEmpty: !0,
            forcePlaceholderSize: !1,
            forceHelperSize: !1,
            grid: !1,
            handle: !1,
            helper: "original",
            items: "> *",
            opacity: !1,
            placeholder: !1,
            revert: !1,
            scroll: !0,
            scrollSensitivity: 20,
            scrollSpeed: 20,
            scope: "default",
            tolerance: "intersect",
            zIndex: 1e3
        },
        _create: function () {
            var e = this.options;
            this.containerCache = {}, this.element.addClass("ui-sortable"), this.refresh(), this.floating = this.items.length ? "x" === e.axis || /left|right/.test(this.items[0].item.css("float")) || /inline|table-cell/.test(this.items[0].item.css("display")) : !1, this.offset = this.element.offset(), this._mouseInit(), this.ready = !0
        },
        _destroy: function () {
            this.element.removeClass("ui-sortable ui-sortable-disabled"), this._mouseDestroy();
            for (var e = this.items.length - 1; e >= 0; e--)this.items[e].item.removeData(this.widgetName + "-item");
            return this
        },
        _setOption: function (t, i) {
            "disabled" === t ? (this.options[t] = i, this.widget().toggleClass("ui-sortable-disabled", !!i)) : e.Widget.prototype._setOption.apply(this, arguments)
        },
        _mouseCapture: function (t, i) {
            var s = this;
            if (this.reverting)return !1;
            if (this.options.disabled || "static" == this.options.type)return !1;
            this._refreshItems(t);
            var n = null;
            if (e(t.target).parents().each(function () {
                    return e.data(this, s.widgetName + "-item") == s ? (n = e(this), !1) : undefined
                }), e.data(t.target, s.widgetName + "-item") == s && (n = e(t.target)), !n)return !1;
            if (this.options.handle && !i) {
                var a = !1;
                if (e(this.options.handle, n).find("*").andSelf().each(function () {
                        this == t.target && (a = !0)
                    }), !a)return !1
            }
            return this.currentItem = n, this._removeCurrentsFromItems(), !0
        },
        _mouseStart: function (t, i, s) {
            var n = this.options;
            if (this.currentContainer = this, this.refreshPositions(), this.helper = this._createHelper(t), this._cacheHelperProportions(), this._cacheMargins(), this.scrollParent = this.helper.scrollParent(), this.offset = this.currentItem.offset(), this.offset = {
                    top: this.offset.top - this.margins.top,
                    left: this.offset.left - this.margins.left
                }, e.extend(this.offset, {
                    click: {left: t.pageX - this.offset.left, top: t.pageY - this.offset.top},
                    parent: this._getParentOffset(),
                    relative: this._getRelativeOffset()
                }), this.helper.css("position", "absolute"), this.cssPosition = this.helper.css("position"), this.originalPosition = this._generatePosition(t), this.originalPageX = t.pageX, this.originalPageY = t.pageY, n.cursorAt && this._adjustOffsetFromHelper(n.cursorAt), this.domPosition = {
                    prev: this.currentItem.prev()[0],
                    parent: this.currentItem.parent()[0]
                }, this.helper[0] != this.currentItem[0] && this.currentItem.hide(), this._createPlaceholder(), n.containment && this._setContainment(), n.cursor && (e("body").css("cursor") && (this._storedCursor = e("body").css("cursor")), e("body").css("cursor", n.cursor)), n.opacity && (this.helper.css("opacity") && (this._storedOpacity = this.helper.css("opacity")), this.helper.css("opacity", n.opacity)), n.zIndex && (this.helper.css("zIndex") && (this._storedZIndex = this.helper.css("zIndex")), this.helper.css("zIndex", n.zIndex)), this.scrollParent[0] != document && "HTML" != this.scrollParent[0].tagName && (this.overflowOffset = this.scrollParent.offset()), this._trigger("start", t, this._uiHash()), this._preserveHelperProportions || this._cacheHelperProportions(), !s)for (var a = this.containers.length - 1; a >= 0; a--)this.containers[a]._trigger("activate", t, this._uiHash(this));
            return e.ui.ddmanager && (e.ui.ddmanager.current = this), e.ui.ddmanager && !n.dropBehaviour && e.ui.ddmanager.prepareOffsets(this, t), this.dragging = !0, this.helper.addClass("ui-sortable-helper"), this._mouseDrag(t), !0
        },
        _mouseDrag: function (t) {
            if (this.position = this._generatePosition(t), this.positionAbs = this._convertPositionTo("absolute"), this.lastPositionAbs || (this.lastPositionAbs = this.positionAbs), this.options.scroll) {
                var i = this.options, s = !1;
                this.scrollParent[0] != document && "HTML" != this.scrollParent[0].tagName ? (this.overflowOffset.top + this.scrollParent[0].offsetHeight - t.pageY < i.scrollSensitivity ? this.scrollParent[0].scrollTop = s = this.scrollParent[0].scrollTop + i.scrollSpeed : t.pageY - this.overflowOffset.top < i.scrollSensitivity && (this.scrollParent[0].scrollTop = s = this.scrollParent[0].scrollTop - i.scrollSpeed), this.overflowOffset.left + this.scrollParent[0].offsetWidth - t.pageX < i.scrollSensitivity ? this.scrollParent[0].scrollLeft = s = this.scrollParent[0].scrollLeft + i.scrollSpeed : t.pageX - this.overflowOffset.left < i.scrollSensitivity && (this.scrollParent[0].scrollLeft = s = this.scrollParent[0].scrollLeft - i.scrollSpeed)) : (t.pageY - e(document).scrollTop() < i.scrollSensitivity ? s = e(document).scrollTop(e(document).scrollTop() - i.scrollSpeed) : e(window).height() - (t.pageY - e(document).scrollTop()) < i.scrollSensitivity && (s = e(document).scrollTop(e(document).scrollTop() + i.scrollSpeed)), t.pageX - e(document).scrollLeft() < i.scrollSensitivity ? s = e(document).scrollLeft(e(document).scrollLeft() - i.scrollSpeed) : e(window).width() - (t.pageX - e(document).scrollLeft()) < i.scrollSensitivity && (s = e(document).scrollLeft(e(document).scrollLeft() + i.scrollSpeed))), s !== !1 && e.ui.ddmanager && !i.dropBehaviour && e.ui.ddmanager.prepareOffsets(this, t)
            }
            this.positionAbs = this._convertPositionTo("absolute"), this.options.axis && "y" == this.options.axis || (this.helper[0].style.left = this.position.left + "px"), this.options.axis && "x" == this.options.axis || (this.helper[0].style.top = this.position.top + "px");
            for (var n = this.items.length - 1; n >= 0; n--) {
                var a = this.items[n], o = a.item[0], r = this._intersectsWithPointer(a);
                if (r && a.instance === this.currentContainer && o != this.currentItem[0] && this.placeholder[1 == r ? "next" : "prev"]()[0] != o && !e.contains(this.placeholder[0], o) && ("semi-dynamic" == this.options.type ? !e.contains(this.element[0], o) : !0)) {
                    if (this.direction = 1 == r ? "down" : "up", "pointer" != this.options.tolerance && !this._intersectsWithSides(a))break;
                    this._rearrange(t, a), this._trigger("change", t, this._uiHash());
                    break
                }
            }
            return this._contactContainers(t), e.ui.ddmanager && e.ui.ddmanager.drag(this, t), this._trigger("sort", t, this._uiHash()), this.lastPositionAbs = this.positionAbs, !1
        },
        _mouseStop: function (t, i) {
            if (t) {
                if (e.ui.ddmanager && !this.options.dropBehaviour && e.ui.ddmanager.drop(this, t), this.options.revert) {
                    var s = this, n = this.placeholder.offset();
                    this.reverting = !0, e(this.helper).animate({
                        left: n.left - this.offset.parent.left - this.margins.left + (this.offsetParent[0] == document.body ? 0 : this.offsetParent[0].scrollLeft),
                        top: n.top - this.offset.parent.top - this.margins.top + (this.offsetParent[0] == document.body ? 0 : this.offsetParent[0].scrollTop)
                    }, parseInt(this.options.revert, 10) || 500, function () {
                        s._clear(t)
                    })
                } else this._clear(t, i);
                return !1
            }
        },
        cancel: function () {
            if (this.dragging) {
                this._mouseUp({target: null}), "original" == this.options.helper ? this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper") : this.currentItem.show();
                for (var t = this.containers.length - 1; t >= 0; t--)this.containers[t]._trigger("deactivate", null, this._uiHash(this)), this.containers[t].containerCache.over && (this.containers[t]._trigger("out", null, this._uiHash(this)), this.containers[t].containerCache.over = 0)
            }
            return this.placeholder && (this.placeholder[0].parentNode && this.placeholder[0].parentNode.removeChild(this.placeholder[0]), "original" != this.options.helper && this.helper && this.helper[0].parentNode && this.helper.remove(), e.extend(this, {
                helper: null,
                dragging: !1,
                reverting: !1,
                _noFinalSort: null
            }), this.domPosition.prev ? e(this.domPosition.prev).after(this.currentItem) : e(this.domPosition.parent).prepend(this.currentItem)), this
        },
        serialize: function (t) {
            var i = this._getItemsAsjQuery(t && t.connected), s = [];
            return t = t || {}, e(i).each(function () {
                var i = (e(t.item || this).attr(t.attribute || "id") || "").match(t.expression || /(.+)[-=_](.+)/);
                i && s.push((t.key || i[1] + "[]") + "=" + (t.key && t.expression ? i[1] : i[2]))
            }), !s.length && t.key && s.push(t.key + "="), s.join("&")
        },
        toArray: function (t) {
            var i = this._getItemsAsjQuery(t && t.connected), s = [];
            return t = t || {}, i.each(function () {
                s.push(e(t.item || this).attr(t.attribute || "id") || "")
            }), s
        },
        _intersectsWith: function (e) {
            var t = this.positionAbs.left, i = t + this.helperProportions.width, s = this.positionAbs.top, n = s + this.helperProportions.height, a = e.left, o = a + e.width, r = e.top, h = r + e.height, l = this.offset.click.top, u = this.offset.click.left, d = s + l > r && h > s + l && t + u > a && o > t + u;
            return "pointer" == this.options.tolerance || this.options.forcePointerForContainers || "pointer" != this.options.tolerance && this.helperProportions[this.floating ? "width" : "height"] > e[this.floating ? "width" : "height"] ? d : t + this.helperProportions.width / 2 > a && o > i - this.helperProportions.width / 2 && s + this.helperProportions.height / 2 > r && h > n - this.helperProportions.height / 2
        },
        _intersectsWithPointer: function (t) {
            var i = "x" === this.options.axis || e.ui.isOverAxis(this.positionAbs.top + this.offset.click.top, t.top, t.height), s = "y" === this.options.axis || e.ui.isOverAxis(this.positionAbs.left + this.offset.click.left, t.left, t.width), n = i && s, a = this._getDragVerticalDirection(), o = this._getDragHorizontalDirection();
            return n ? this.floating ? o && "right" == o || "down" == a ? 2 : 1 : a && ("down" == a ? 2 : 1) : !1
        },
        _intersectsWithSides: function (t) {
            var i = e.ui.isOverAxis(this.positionAbs.top + this.offset.click.top, t.top + t.height / 2, t.height), s = e.ui.isOverAxis(this.positionAbs.left + this.offset.click.left, t.left + t.width / 2, t.width), n = this._getDragVerticalDirection(), a = this._getDragHorizontalDirection();
            return this.floating && a ? "right" == a && s || "left" == a && !s : n && ("down" == n && i || "up" == n && !i)
        },
        _getDragVerticalDirection: function () {
            var e = this.positionAbs.top - this.lastPositionAbs.top;
            return 0 != e && (e > 0 ? "down" : "up")
        },
        _getDragHorizontalDirection: function () {
            var e = this.positionAbs.left - this.lastPositionAbs.left;
            return 0 != e && (e > 0 ? "right" : "left")
        },
        refresh: function (e) {
            return this._refreshItems(e), this.refreshPositions(), this
        },
        _connectWith: function () {
            var e = this.options;
            return e.connectWith.constructor == String ? [e.connectWith] : e.connectWith
        },
        _getItemsAsjQuery: function (t) {
            var i = [], s = [], n = this._connectWith();
            if (n && t)for (var a = n.length - 1; a >= 0; a--)for (var o = e(n[a]), r = o.length - 1; r >= 0; r--) {
                var h = e.data(o[r], this.widgetName);
                h && h != this && !h.options.disabled && s.push([e.isFunction(h.options.items) ? h.options.items.call(h.element) : e(h.options.items, h.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"), h])
            }
            s.push([e.isFunction(this.options.items) ? this.options.items.call(this.element, null, {
                options: this.options,
                item: this.currentItem
            }) : e(this.options.items, this.element).not(".ui-sortable-helper").not(".ui-sortable-placeholder"), this]);
            for (var a = s.length - 1; a >= 0; a--)s[a][0].each(function () {
                i.push(this)
            });
            return e(i)
        },
        _removeCurrentsFromItems: function () {
            var t = this.currentItem.find(":data(" + this.widgetName + "-item)");
            this.items = e.grep(this.items, function (e) {
                for (var i = 0; t.length > i; i++)if (t[i] == e.item[0])return !1;
                return !0
            })
        },
        _refreshItems: function (t) {
            this.items = [], this.containers = [this];
            var i = this.items, s = [[e.isFunction(this.options.items) ? this.options.items.call(this.element[0], t, {item: this.currentItem}) : e(this.options.items, this.element), this]], n = this._connectWith();
            if (n && this.ready)for (var a = n.length - 1; a >= 0; a--)for (var o = e(n[a]), r = o.length - 1; r >= 0; r--) {
                var h = e.data(o[r], this.widgetName);
                h && h != this && !h.options.disabled && (s.push([e.isFunction(h.options.items) ? h.options.items.call(h.element[0], t, {item: this.currentItem}) : e(h.options.items, h.element), h]), this.containers.push(h))
            }
            for (var a = s.length - 1; a >= 0; a--)for (var l = s[a][1], u = s[a][0], r = 0, d = u.length; d > r; r++) {
                var c = e(u[r]);
                c.data(this.widgetName + "-item", l), i.push({
                    item: c,
                    instance: l,
                    width: 0,
                    height: 0,
                    left: 0,
                    top: 0
                })
            }
        },
        refreshPositions: function (t) {
            this.offsetParent && this.helper && (this.offset.parent = this._getParentOffset());
            for (var i = this.items.length - 1; i >= 0; i--) {
                var s = this.items[i];
                if (s.instance == this.currentContainer || !this.currentContainer || s.item[0] == this.currentItem[0]) {
                    var n = this.options.toleranceElement ? e(this.options.toleranceElement, s.item) : s.item;
                    t || (s.width = n.outerWidth(), s.height = n.outerHeight());
                    var a = n.offset();
                    s.left = a.left, s.top = a.top
                }
            }
            if (this.options.custom && this.options.custom.refreshContainers)this.options.custom.refreshContainers.call(this); else for (var i = this.containers.length - 1; i >= 0; i--) {
                var a = this.containers[i].element.offset();
                this.containers[i].containerCache.left = a.left, this.containers[i].containerCache.top = a.top, this.containers[i].containerCache.width = this.containers[i].element.outerWidth(), this.containers[i].containerCache.height = this.containers[i].element.outerHeight()
            }
            return this
        },
        _createPlaceholder: function (t) {
            t = t || this;
            var i = t.options;
            if (!i.placeholder || i.placeholder.constructor == String) {
                var s = i.placeholder;
                i.placeholder = {
                    element: function () {
                        var i = e(document.createElement(t.currentItem[0].nodeName)).addClass(s || t.currentItem[0].className + " ui-sortable-placeholder").removeClass("ui-sortable-helper")[0];
                        return s || (i.style.visibility = "hidden"), i
                    }, update: function (e, n) {
                        (!s || i.forcePlaceholderSize) && (n.height() || n.height(t.currentItem.innerHeight() - parseInt(t.currentItem.css("paddingTop") || 0, 10) - parseInt(t.currentItem.css("paddingBottom") || 0, 10)), n.width() || n.width(t.currentItem.innerWidth() - parseInt(t.currentItem.css("paddingLeft") || 0, 10) - parseInt(t.currentItem.css("paddingRight") || 0, 10)))
                    }
                }
            }
            t.placeholder = e(i.placeholder.element.call(t.element, t.currentItem)), t.currentItem.after(t.placeholder), i.placeholder.update(t, t.placeholder)
        },
        _contactContainers: function (t) {
            for (var i = null, s = null, n = this.containers.length - 1; n >= 0; n--)if (!e.contains(this.currentItem[0], this.containers[n].element[0]))if (this._intersectsWith(this.containers[n].containerCache)) {
                if (i && e.contains(this.containers[n].element[0], i.element[0]))continue;
                i = this.containers[n], s = n
            } else this.containers[n].containerCache.over && (this.containers[n]._trigger("out", t, this._uiHash(this)), this.containers[n].containerCache.over = 0);
            if (i)if (1 === this.containers.length)this.containers[s]._trigger("over", t, this._uiHash(this)), this.containers[s].containerCache.over = 1; else {
                for (var a = 1e4, o = null, r = this.containers[s].floating ? "left" : "top", h = this.containers[s].floating ? "width" : "height", l = this.positionAbs[r] + this.offset.click[r], u = this.items.length - 1; u >= 0; u--)if (e.contains(this.containers[s].element[0], this.items[u].item[0]) && this.items[u].item[0] != this.currentItem[0]) {
                    var d = this.items[u].item.offset()[r], c = !1;
                    Math.abs(d - l) > Math.abs(d + this.items[u][h] - l) && (c = !0, d += this.items[u][h]), a > Math.abs(d - l) && (a = Math.abs(d - l), o = this.items[u], this.direction = c ? "up" : "down")
                }
                if (!o && !this.options.dropOnEmpty)return;
                this.currentContainer = this.containers[s], o ? this._rearrange(t, o, null, !0) : this._rearrange(t, null, this.containers[s].element, !0), this._trigger("change", t, this._uiHash()), this.containers[s]._trigger("change", t, this._uiHash(this)), this.options.placeholder.update(this.currentContainer, this.placeholder), this.containers[s]._trigger("over", t, this._uiHash(this)), this.containers[s].containerCache.over = 1
            }
        },
        _createHelper: function (t) {
            var i = this.options, s = e.isFunction(i.helper) ? e(i.helper.apply(this.element[0], [t, this.currentItem])) : "clone" == i.helper ? this.currentItem.clone() : this.currentItem;
            return s.parents("body").length || e("parent" != i.appendTo ? i.appendTo : this.currentItem[0].parentNode)[0].appendChild(s[0]), s[0] == this.currentItem[0] && (this._storedCSS = {
                width: this.currentItem[0].style.width,
                height: this.currentItem[0].style.height,
                position: this.currentItem.css("position"),
                top: this.currentItem.css("top"),
                left: this.currentItem.css("left")
            }), ("" == s[0].style.width || i.forceHelperSize) && s.width(this.currentItem.width()), ("" == s[0].style.height || i.forceHelperSize) && s.height(this.currentItem.height()), s
        },
        _adjustOffsetFromHelper: function (t) {
            "string" == typeof t && (t = t.split(" ")), e.isArray(t) && (t = {
                left: +t[0],
                top: +t[1] || 0
            }), "left"in t && (this.offset.click.left = t.left + this.margins.left), "right"in t && (this.offset.click.left = this.helperProportions.width - t.right + this.margins.left), "top"in t && (this.offset.click.top = t.top + this.margins.top), "bottom"in t && (this.offset.click.top = this.helperProportions.height - t.bottom + this.margins.top)
        },
        _getParentOffset: function () {
            this.offsetParent = this.helper.offsetParent();
            var t = this.offsetParent.offset();
            return "absolute" == this.cssPosition && this.scrollParent[0] != document && e.contains(this.scrollParent[0], this.offsetParent[0]) && (t.left += this.scrollParent.scrollLeft(), t.top += this.scrollParent.scrollTop()), (this.offsetParent[0] == document.body || this.offsetParent[0].tagName && "html" == this.offsetParent[0].tagName.toLowerCase() && e.ui.ie) && (t = {
                top: 0,
                left: 0
            }), {
                top: t.top + (parseInt(this.offsetParent.css("borderTopWidth"), 10) || 0),
                left: t.left + (parseInt(this.offsetParent.css("borderLeftWidth"), 10) || 0)
            }
        },
        _getRelativeOffset: function () {
            if ("relative" == this.cssPosition) {
                var e = this.currentItem.position();
                return {
                    top: e.top - (parseInt(this.helper.css("top"), 10) || 0) + this.scrollParent.scrollTop(),
                    left: e.left - (parseInt(this.helper.css("left"), 10) || 0) + this.scrollParent.scrollLeft()
                }
            }
            return {top: 0, left: 0}
        },
        _cacheMargins: function () {
            this.margins = {
                left: parseInt(this.currentItem.css("marginLeft"), 10) || 0,
                top: parseInt(this.currentItem.css("marginTop"), 10) || 0
            }
        },
        _cacheHelperProportions: function () {
            this.helperProportions = {width: this.helper.outerWidth(), height: this.helper.outerHeight()}
        },
        _setContainment: function () {
            var t = this.options;
            if ("parent" == t.containment && (t.containment = this.helper[0].parentNode), ("document" == t.containment || "window" == t.containment) && (this.containment = [0 - this.offset.relative.left - this.offset.parent.left, 0 - this.offset.relative.top - this.offset.parent.top, e("document" == t.containment ? document : window).width() - this.helperProportions.width - this.margins.left, (e("document" == t.containment ? document : window).height() || document.body.parentNode.scrollHeight) - this.helperProportions.height - this.margins.top]), !/^(document|window|parent)$/.test(t.containment)) {
                var i = e(t.containment)[0], s = e(t.containment).offset(), n = "hidden" != e(i).css("overflow");
                this.containment = [s.left + (parseInt(e(i).css("borderLeftWidth"), 10) || 0) + (parseInt(e(i).css("paddingLeft"), 10) || 0) - this.margins.left, s.top + (parseInt(e(i).css("borderTopWidth"), 10) || 0) + (parseInt(e(i).css("paddingTop"), 10) || 0) - this.margins.top, s.left + (n ? Math.max(i.scrollWidth, i.offsetWidth) : i.offsetWidth) - (parseInt(e(i).css("borderLeftWidth"), 10) || 0) - (parseInt(e(i).css("paddingRight"), 10) || 0) - this.helperProportions.width - this.margins.left, s.top + (n ? Math.max(i.scrollHeight, i.offsetHeight) : i.offsetHeight) - (parseInt(e(i).css("borderTopWidth"), 10) || 0) - (parseInt(e(i).css("paddingBottom"), 10) || 0) - this.helperProportions.height - this.margins.top]
            }
        },
        _convertPositionTo: function (t, i) {
            i || (i = this.position);
            var s = "absolute" == t ? 1 : -1, n = (this.options, "absolute" != this.cssPosition || this.scrollParent[0] != document && e.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent : this.offsetParent), a = /(html|body)/i.test(n[0].tagName);
            return {
                top: i.top + this.offset.relative.top * s + this.offset.parent.top * s - ("fixed" == this.cssPosition ? -this.scrollParent.scrollTop() : a ? 0 : n.scrollTop()) * s,
                left: i.left + this.offset.relative.left * s + this.offset.parent.left * s - ("fixed" == this.cssPosition ? -this.scrollParent.scrollLeft() : a ? 0 : n.scrollLeft()) * s
            }
        },
        _generatePosition: function (t) {
            var i = this.options, s = "absolute" != this.cssPosition || this.scrollParent[0] != document && e.contains(this.scrollParent[0], this.offsetParent[0]) ? this.scrollParent : this.offsetParent, n = /(html|body)/i.test(s[0].tagName);
            "relative" != this.cssPosition || this.scrollParent[0] != document && this.scrollParent[0] != this.offsetParent[0] || (this.offset.relative = this._getRelativeOffset());
            var a = t.pageX, o = t.pageY;
            if (this.originalPosition && (this.containment && (t.pageX - this.offset.click.left < this.containment[0] && (a = this.containment[0] + this.offset.click.left), t.pageY - this.offset.click.top < this.containment[1] && (o = this.containment[1] + this.offset.click.top), t.pageX - this.offset.click.left > this.containment[2] && (a = this.containment[2] + this.offset.click.left), t.pageY - this.offset.click.top > this.containment[3] && (o = this.containment[3] + this.offset.click.top)), i.grid)) {
                var r = this.originalPageY + Math.round((o - this.originalPageY) / i.grid[1]) * i.grid[1];
                o = this.containment ? r - this.offset.click.top < this.containment[1] || r - this.offset.click.top > this.containment[3] ? r - this.offset.click.top < this.containment[1] ? r + i.grid[1] : r - i.grid[1] : r : r;
                var h = this.originalPageX + Math.round((a - this.originalPageX) / i.grid[0]) * i.grid[0];
                a = this.containment ? h - this.offset.click.left < this.containment[0] || h - this.offset.click.left > this.containment[2] ? h - this.offset.click.left < this.containment[0] ? h + i.grid[0] : h - i.grid[0] : h : h
            }
            return {
                top: o - this.offset.click.top - this.offset.relative.top - this.offset.parent.top + ("fixed" == this.cssPosition ? -this.scrollParent.scrollTop() : n ? 0 : s.scrollTop()),
                left: a - this.offset.click.left - this.offset.relative.left - this.offset.parent.left + ("fixed" == this.cssPosition ? -this.scrollParent.scrollLeft() : n ? 0 : s.scrollLeft())
            }
        },
        _rearrange: function (e, t, i, s) {
            i ? i[0].appendChild(this.placeholder[0]) : t.item[0].parentNode.insertBefore(this.placeholder[0], "down" == this.direction ? t.item[0] : t.item[0].nextSibling), this.counter = this.counter ? ++this.counter : 1;
            var n = this.counter;
            this._delay(function () {
                n == this.counter && this.refreshPositions(!s)
            })
        },
        _clear: function (t, i) {
            this.reverting = !1;
            var s = [];
            if (!this._noFinalSort && this.currentItem.parent().length && this.placeholder.before(this.currentItem), this._noFinalSort = null, this.helper[0] == this.currentItem[0]) {
                for (var n in this._storedCSS)("auto" == this._storedCSS[n] || "static" == this._storedCSS[n]) && (this._storedCSS[n] = "");
                this.currentItem.css(this._storedCSS).removeClass("ui-sortable-helper")
            } else this.currentItem.show();
            this.fromOutside && !i && s.push(function (e) {
                this._trigger("receive", e, this._uiHash(this.fromOutside))
            }), !this.fromOutside && this.domPosition.prev == this.currentItem.prev().not(".ui-sortable-helper")[0] && this.domPosition.parent == this.currentItem.parent()[0] || i || s.push(function (e) {
                this._trigger("update", e, this._uiHash())
            }), this !== this.currentContainer && (i || (s.push(function (e) {
                this._trigger("remove", e, this._uiHash())
            }), s.push(function (e) {
                return function (t) {
                    e._trigger("receive", t, this._uiHash(this))
                }
            }.call(this, this.currentContainer)), s.push(function (e) {
                return function (t) {
                    e._trigger("update", t, this._uiHash(this))
                }
            }.call(this, this.currentContainer))));
            for (var n = this.containers.length - 1; n >= 0; n--)i || s.push(function (e) {
                return function (t) {
                    e._trigger("deactivate", t, this._uiHash(this))
                }
            }.call(this, this.containers[n])), this.containers[n].containerCache.over && (s.push(function (e) {
                return function (t) {
                    e._trigger("out", t, this._uiHash(this))
                }
            }.call(this, this.containers[n])), this.containers[n].containerCache.over = 0);
            if (this._storedCursor && e("body").css("cursor", this._storedCursor), this._storedOpacity && this.helper.css("opacity", this._storedOpacity), this._storedZIndex && this.helper.css("zIndex", "auto" == this._storedZIndex ? "" : this._storedZIndex), this.dragging = !1, this.cancelHelperRemoval) {
                if (!i) {
                    this._trigger("beforeStop", t, this._uiHash());
                    for (var n = 0; s.length > n; n++)s[n].call(this, t);
                    this._trigger("stop", t, this._uiHash())
                }
                return this.fromOutside = !1, !1
            }
            if (i || this._trigger("beforeStop", t, this._uiHash()), this.placeholder[0].parentNode.removeChild(this.placeholder[0]), this.helper[0] != this.currentItem[0] && this.helper.remove(), this.helper = null, !i) {
                for (var n = 0; s.length > n; n++)s[n].call(this, t);
                this._trigger("stop", t, this._uiHash())
            }
            return this.fromOutside = !1, !0
        },
        _trigger: function () {
            e.Widget.prototype._trigger.apply(this, arguments) === !1 && this.cancel()
        },
        _uiHash: function (t) {
            var i = t || this;
            return {
                helper: i.helper,
                placeholder: i.placeholder || e([]),
                position: i.position,
                originalPosition: i.originalPosition,
                offset: i.positionAbs,
                item: i.currentItem,
                sender: t ? t.element : null
            }
        }
    })
})(jQuery);
(function (e) {
    var t = 0, i = {}, s = {};
    i.height = i.paddingTop = i.paddingBottom = i.borderTopWidth = i.borderBottomWidth = "hide", s.height = s.paddingTop = s.paddingBottom = s.borderTopWidth = s.borderBottomWidth = "show", e.widget("ui.accordion", {
        version: "1.9.2",
        options: {
            active: 0,
            animate: {},
            collapsible: !1,
            event: "click",
            header: "> li > :first-child,> :not(li):even",
            heightStyle: "auto",
            icons: {activeHeader: "ui-icon-triangle-1-s", header: "ui-icon-triangle-1-e"},
            activate: null,
            beforeActivate: null
        },
        _create: function () {
            var i = this.accordionId = "ui-accordion-" + (this.element.attr("id") || ++t), s = this.options;
            this.prevShow = this.prevHide = e(), this.element.addClass("ui-accordion ui-widget ui-helper-reset"), this.headers = this.element.find(s.header).addClass("ui-accordion-header ui-helper-reset ui-state-default ui-corner-all"), this._hoverable(this.headers), this._focusable(this.headers), this.headers.next().addClass("ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom").hide(), s.collapsible || s.active !== !1 && null != s.active || (s.active = 0), 0 > s.active && (s.active += this.headers.length), this.active = this._findActive(s.active).addClass("ui-accordion-header-active ui-state-active").toggleClass("ui-corner-all ui-corner-top"), this.active.next().addClass("ui-accordion-content-active").show(), this._createIcons(), this.refresh(), this.element.attr("role", "tablist"), this.headers.attr("role", "tab").each(function (t) {
                var s = e(this), n = s.attr("id"), a = s.next(), o = a.attr("id");
                n || (n = i + "-header-" + t, s.attr("id", n)), o || (o = i + "-panel-" + t, a.attr("id", o)), s.attr("aria-controls", o), a.attr("aria-labelledby", n)
            }).next().attr("role", "tabpanel"), this.headers.not(this.active).attr({
                "aria-selected": "false",
                tabIndex: -1
            }).next().attr({
                "aria-expanded": "false",
                "aria-hidden": "true"
            }).hide(), this.active.length ? this.active.attr({
                "aria-selected": "true",
                tabIndex: 0
            }).next().attr({
                "aria-expanded": "true",
                "aria-hidden": "false"
            }) : this.headers.eq(0).attr("tabIndex", 0), this._on(this.headers, {keydown: "_keydown"}), this._on(this.headers.next(), {keydown: "_panelKeyDown"}), this._setupEvents(s.event)
        },
        _getCreateEventData: function () {
            return {header: this.active, content: this.active.length ? this.active.next() : e()}
        },
        _createIcons: function () {
            var t = this.options.icons;
            t && (e("<span>").addClass("ui-accordion-header-icon ui-icon " + t.header).prependTo(this.headers), this.active.children(".ui-accordion-header-icon").removeClass(t.header).addClass(t.activeHeader), this.headers.addClass("ui-accordion-icons"))
        },
        _destroyIcons: function () {
            this.headers.removeClass("ui-accordion-icons").children(".ui-accordion-header-icon").remove()
        },
        _destroy: function () {
            var e;
            this.element.removeClass("ui-accordion ui-widget ui-helper-reset").removeAttr("role"), this.headers.removeClass("ui-accordion-header ui-accordion-header-active ui-helper-reset ui-state-default ui-corner-all ui-state-active ui-state-disabled ui-corner-top").removeAttr("role").removeAttr("aria-selected").removeAttr("aria-controls").removeAttr("tabIndex").each(function () {
                /^ui-accordion/.test(this.id) && this.removeAttribute("id")
            }), this._destroyIcons(), e = this.headers.next().css("display", "").removeAttr("role").removeAttr("aria-expanded").removeAttr("aria-hidden").removeAttr("aria-labelledby").removeClass("ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content ui-accordion-content-active ui-state-disabled").each(function () {
                /^ui-accordion/.test(this.id) && this.removeAttribute("id")
            }), "content" !== this.options.heightStyle && e.css("height", "")
        },
        _setOption: function (e, t) {
            return "active" === e ? (this._activate(t), undefined) : ("event" === e && (this.options.event && this._off(this.headers, this.options.event), this._setupEvents(t)), this._super(e, t), "collapsible" !== e || t || this.options.active !== !1 || this._activate(0), "icons" === e && (this._destroyIcons(), t && this._createIcons()), "disabled" === e && this.headers.add(this.headers.next()).toggleClass("ui-state-disabled", !!t), undefined)
        },
        _keydown: function (t) {
            if (!t.altKey && !t.ctrlKey) {
                var i = e.ui.keyCode, s = this.headers.length, n = this.headers.index(t.target), a = !1;
                switch (t.keyCode) {
                    case i.RIGHT:
                    case i.DOWN:
                        a = this.headers[(n + 1) % s];
                        break;
                    case i.LEFT:
                    case i.UP:
                        a = this.headers[(n - 1 + s) % s];
                        break;
                    case i.SPACE:
                    case i.ENTER:
                        this._eventHandler(t);
                        break;
                    case i.HOME:
                        a = this.headers[0];
                        break;
                    case i.END:
                        a = this.headers[s - 1]
                }
                a && (e(t.target).attr("tabIndex", -1), e(a).attr("tabIndex", 0), a.focus(), t.preventDefault())
            }
        },
        _panelKeyDown: function (t) {
            t.keyCode === e.ui.keyCode.UP && t.ctrlKey && e(t.currentTarget).prev().focus()
        },
        refresh: function () {
            var t, i, s = this.options.heightStyle, n = this.element.parent();
            "fill" === s ? (e.support.minHeight || (i = n.css("overflow"), n.css("overflow", "hidden")), t = n.height(), this.element.siblings(":visible").each(function () {
                var i = e(this), s = i.css("position");
                "absolute" !== s && "fixed" !== s && (t -= i.outerHeight(!0))
            }), i && n.css("overflow", i), this.headers.each(function () {
                t -= e(this).outerHeight(!0)
            }), this.headers.next().each(function () {
                e(this).height(Math.max(0, t - e(this).innerHeight() + e(this).height()))
            }).css("overflow", "auto")) : "auto" === s && (t = 0, this.headers.next().each(function () {
                t = Math.max(t, e(this).css("height", "").height())
            }).height(t))
        },
        _activate: function (t) {
            var i = this._findActive(t)[0];
            i !== this.active[0] && (i = i || this.active[0], this._eventHandler({
                target: i,
                currentTarget: i,
                preventDefault: e.noop
            }))
        },
        _findActive: function (t) {
            return "number" == typeof t ? this.headers.eq(t) : e()
        },
        _setupEvents: function (t) {
            var i = {};
            t && (e.each(t.split(" "), function (e, t) {
                i[t] = "_eventHandler"
            }), this._on(this.headers, i))
        },
        _eventHandler: function (t) {
            var i = this.options, s = this.active, n = e(t.currentTarget), a = n[0] === s[0], o = a && i.collapsible, r = o ? e() : n.next(), h = s.next(), l = {
                oldHeader: s,
                oldPanel: h,
                newHeader: o ? e() : n,
                newPanel: r
            };
            t.preventDefault(), a && !i.collapsible || this._trigger("beforeActivate", t, l) === !1 || (i.active = o ? !1 : this.headers.index(n), this.active = a ? e() : n, this._toggle(l), s.removeClass("ui-accordion-header-active ui-state-active"), i.icons && s.children(".ui-accordion-header-icon").removeClass(i.icons.activeHeader).addClass(i.icons.header), a || (n.removeClass("ui-corner-all").addClass("ui-accordion-header-active ui-state-active ui-corner-top"), i.icons && n.children(".ui-accordion-header-icon").removeClass(i.icons.header).addClass(i.icons.activeHeader), n.next().addClass("ui-accordion-content-active")))
        },
        _toggle: function (t) {
            var i = t.newPanel, s = this.prevShow.length ? this.prevShow : t.oldPanel;
            this.prevShow.add(this.prevHide).stop(!0, !0), this.prevShow = i, this.prevHide = s, this.options.animate ? this._animate(i, s, t) : (s.hide(), i.show(), this._toggleComplete(t)), s.attr({
                "aria-expanded": "false",
                "aria-hidden": "true"
            }), s.prev().attr("aria-selected", "false"), i.length && s.length ? s.prev().attr("tabIndex", -1) : i.length && this.headers.filter(function () {
                return 0 === e(this).attr("tabIndex")
            }).attr("tabIndex", -1), i.attr({
                "aria-expanded": "true",
                "aria-hidden": "false"
            }).prev().attr({"aria-selected": "true", tabIndex: 0})
        },
        _animate: function (e, t, n) {
            var a, o, r, h = this, l = 0, u = e.length && (!t.length || e.index() < t.index()), d = this.options.animate || {}, c = u && d.down || d, p = function () {
                h._toggleComplete(n)
            };
            return "number" == typeof c && (r = c), "string" == typeof c && (o = c), o = o || c.easing || d.easing, r = r || c.duration || d.duration, t.length ? e.length ? (a = e.show().outerHeight(), t.animate(i, {
                duration: r,
                easing: o,
                step: function (e, t) {
                    t.now = Math.round(e)
                }
            }), e.hide().animate(s, {
                duration: r, easing: o, complete: p, step: function (e, i) {
                    i.now = Math.round(e), "height" !== i.prop ? l += i.now : "content" !== h.options.heightStyle && (i.now = Math.round(a - t.outerHeight() - l), l = 0)
                }
            }), undefined) : t.animate(i, r, o, p) : e.animate(s, r, o, p)
        },
        _toggleComplete: function (e) {
            var t = e.oldPanel;
            t.removeClass("ui-accordion-content-active").prev().removeClass("ui-corner-top").addClass("ui-corner-all"), t.length && (t.parent()[0].className = t.parent()[0].className), this._trigger("activate", null, e)
        }
    }), e.uiBackCompat !== !1 && (function (e, t) {
        e.extend(t.options, {
            navigation: !1, navigationFilter: function () {
                return this.href.toLowerCase() === location.href.toLowerCase()
            }
        });
        var i = t._create;
        t._create = function () {
            if (this.options.navigation) {
                var t = this, s = this.element.find(this.options.header), n = s.next(), a = s.add(n).find("a").filter(this.options.navigationFilter)[0];
                a && s.add(n).each(function (i) {
                    return e.contains(this, a) ? (t.options.active = Math.floor(i / 2), !1) : undefined
                })
            }
            i.call(this)
        }
    }(jQuery, jQuery.ui.accordion.prototype), function (e, t) {
        e.extend(t.options, {heightStyle: null, autoHeight: !0, clearStyle: !1, fillSpace: !1});
        var i = t._create, s = t._setOption;
        e.extend(t, {
            _create: function () {
                this.options.heightStyle = this.options.heightStyle || this._mergeHeightStyle(), i.call(this)
            }, _setOption: function (e) {
                ("autoHeight" === e || "clearStyle" === e || "fillSpace" === e) && (this.options.heightStyle = this._mergeHeightStyle()), s.apply(this, arguments)
            }, _mergeHeightStyle: function () {
                var e = this.options;
                return e.fillSpace ? "fill" : e.clearStyle ? "content" : e.autoHeight ? "auto" : undefined
            }
        })
    }(jQuery, jQuery.ui.accordion.prototype), function (e, t) {
        e.extend(t.options.icons, {activeHeader: null, headerSelected: "ui-icon-triangle-1-s"});
        var i = t._createIcons;
        t._createIcons = function () {
            this.options.icons && (this.options.icons.activeHeader = this.options.icons.activeHeader || this.options.icons.headerSelected), i.call(this)
        }
    }(jQuery, jQuery.ui.accordion.prototype), function (e, t) {
        t.activate = t._activate;
        var i = t._findActive;
        t._findActive = function (e) {
            return -1 === e && (e = !1), e && "number" != typeof e && (e = this.headers.index(this.headers.filter(e)), -1 === e && (e = !1)), i.call(this, e)
        }
    }(jQuery, jQuery.ui.accordion.prototype), jQuery.ui.accordion.prototype.resize = jQuery.ui.accordion.prototype.refresh, function (e, t) {
        e.extend(t.options, {change: null, changestart: null});
        var i = t._trigger;
        t._trigger = function (e, t, s) {
            var n = i.apply(this, arguments);
            return n ? ("beforeActivate" === e ? n = i.call(this, "changestart", t, {
                oldHeader: s.oldHeader,
                oldContent: s.oldPanel,
                newHeader: s.newHeader,
                newContent: s.newPanel
            }) : "activate" === e && (n = i.call(this, "change", t, {
                oldHeader: s.oldHeader,
                oldContent: s.oldPanel,
                newHeader: s.newHeader,
                newContent: s.newPanel
            })), n) : !1
        }
    }(jQuery, jQuery.ui.accordion.prototype), function (e, t) {
        e.extend(t.options, {animate: null, animated: "slide"});
        var i = t._create;
        t._create = function () {
            var e = this.options;
            null === e.animate && (e.animate = e.animated ? "slide" === e.animated ? 300 : "bounceslide" === e.animated ? {
                duration: 200,
                down: {easing: "easeOutBounce", duration: 1e3}
            } : e.animated : !1), i.call(this)
        }
    }(jQuery, jQuery.ui.accordion.prototype))
})(jQuery);
(function (e) {
    var t = 0;
    e.widget("ui.autocomplete", {
        version: "1.9.2",
        defaultElement: "<input>",
        options: {
            appendTo: "body",
            autoFocus: !1,
            delay: 300,
            minLength: 1,
            position: {my: "left top", at: "left bottom", collision: "none"},
            source: null,
            change: null,
            close: null,
            focus: null,
            open: null,
            response: null,
            search: null,
            select: null
        },
        pending: 0,
        _create: function () {
            var t, i, s;
            this.isMultiLine = this._isMultiLine(), this.valueMethod = this.element[this.element.is("input,textarea") ? "val" : "text"], this.isNewMenu = !0, this.element.addClass("ui-autocomplete-input").attr("autocomplete", "off"), this._on(this.element, {
                keydown: function (n) {
                    if (this.element.prop("readOnly"))return t = !0, s = !0, i = !0, undefined;
                    t = !1, s = !1, i = !1;
                    var a = e.ui.keyCode;
                    switch (n.keyCode) {
                        case a.PAGE_UP:
                            t = !0, this._move("previousPage", n);
                            break;
                        case a.PAGE_DOWN:
                            t = !0, this._move("nextPage", n);
                            break;
                        case a.UP:
                            t = !0, this._keyEvent("previous", n);
                            break;
                        case a.DOWN:
                            t = !0, this._keyEvent("next", n);
                            break;
                        case a.ENTER:
                        case a.NUMPAD_ENTER:
                            this.menu.active && (t = !0, n.preventDefault(), this.menu.select(n));
                            break;
                        case a.TAB:
                            this.menu.active && this.menu.select(n);
                            break;
                        case a.ESCAPE:
                            this.menu.element.is(":visible") && (this._value(this.term), this.close(n), n.preventDefault());
                            break;
                        default:
                            i = !0, this._searchTimeout(n)
                    }
                }, keypress: function (s) {
                    if (t)return t = !1, s.preventDefault(), undefined;
                    if (!i) {
                        var n = e.ui.keyCode;
                        switch (s.keyCode) {
                            case n.PAGE_UP:
                                this._move("previousPage", s);
                                break;
                            case n.PAGE_DOWN:
                                this._move("nextPage", s);
                                break;
                            case n.UP:
                                this._keyEvent("previous", s);
                                break;
                            case n.DOWN:
                                this._keyEvent("next", s)
                        }
                    }
                }, input: function (e) {
                    return s ? (s = !1, e.preventDefault(), undefined) : (this._searchTimeout(e), undefined)
                }, focus: function () {
                    this.selectedItem = null, this.previous = this._value()
                }, blur: function (e) {
                    return this.cancelBlur ? (delete this.cancelBlur, undefined) : (clearTimeout(this.searching), this.close(e), this._change(e), undefined)
                }
            }), this._initSource(), this.menu = e("<ul>").addClass("ui-autocomplete").appendTo(this.document.find(this.options.appendTo || "body")[0]).menu({
                input: e(),
                role: null
            }).zIndex(this.element.zIndex() + 1).hide().data("menu"), this._on(this.menu.element, {
                mousedown: function (t) {
                    t.preventDefault(), this.cancelBlur = !0, this._delay(function () {
                        delete this.cancelBlur
                    });
                    var i = this.menu.element[0];
                    e(t.target).closest(".ui-menus-item").length || this._delay(function () {
                        var t = this;
                        this.document.one("mousedown", function (s) {
                            s.target === t.element[0] || s.target === i || e.contains(i, s.target) || t.close()
                        })
                    })
                }, menufocus: function (t, i) {
                    if (this.isNewMenu && (this.isNewMenu = !1, t.originalEvent && /^mouse/.test(t.originalEvent.type)))return this.menu.blur(), this.document.one("mousemove", function () {
                        e(t.target).trigger(t.originalEvent)
                    }), undefined;
                    var s = i.item.data("ui-autocomplete-item") || i.item.data("item.autocomplete");
                    !1 !== this._trigger("focus", t, {item: s}) ? t.originalEvent && /^key/.test(t.originalEvent.type) && this._value(s.value) : this.liveRegion.text(s.value)
                }, menuselect: function (e, t) {
                    var i = t.item.data("ui-autocomplete-item") || t.item.data("item.autocomplete"), s = this.previous;
                    this.element[0] !== this.document[0].activeElement && (this.element.focus(), this.previous = s, this._delay(function () {
                        this.previous = s, this.selectedItem = i
                    })), !1 !== this._trigger("select", e, {item: i}) && this._value(i.value), this.term = this._value(), this.close(e), this.selectedItem = i
                }
            }), this.liveRegion = e("<span>", {
                role: "status",
                "aria-live": "polite"
            }).addClass("ui-helper-hidden-accessible").insertAfter(this.element), e.fn.bgiframe && this.menu.element.bgiframe(), this._on(this.window, {
                beforeunload: function () {
                    this.element.removeAttr("autocomplete")
                }
            })
        },
        _destroy: function () {
            clearTimeout(this.searching), this.element.removeClass("ui-autocomplete-input").removeAttr("autocomplete"), this.menu.element.remove(), this.liveRegion.remove()
        },
        _setOption: function (e, t) {
            this._super(e, t), "source" === e && this._initSource(), "appendTo" === e && this.menu.element.appendTo(this.document.find(t || "body")[0]), "disabled" === e && t && this.xhr && this.xhr.abort()
        },
        _isMultiLine: function () {
            return this.element.is("textarea") ? !0 : this.element.is("input") ? !1 : this.element.prop("isContentEditable")
        },
        _initSource: function () {
            var t, i, s = this;
            e.isArray(this.options.source) ? (t = this.options.source, this.source = function (i, s) {
                s(e.ui.autocomplete.filter(t, i.term))
            }) : "string" == typeof this.options.source ? (i = this.options.source, this.source = function (t, n) {
                s.xhr && s.xhr.abort(), s.xhr = e.ajax({
                    url: i, data: t, dataType: "json", success: function (e) {
                        n(e)
                    }, error: function () {
                        n([])
                    }
                })
            }) : this.source = this.options.source
        },
        _searchTimeout: function (e) {
            clearTimeout(this.searching), this.searching = this._delay(function () {
                this.term !== this._value() && (this.selectedItem = null, this.search(null, e))
            }, this.options.delay)
        },
        search: function (e, t) {
            return e = null != e ? e : this._value(), this.term = this._value(), e.length < this.options.minLength ? this.close(t) : this._trigger("search", t) !== !1 ? this._search(e) : undefined
        },
        _search: function (e) {
            this.pending++, this.element.addClass("ui-autocomplete-loading"), this.cancelSearch = !1, this.source({term: e}, this._response())
        },
        _response: function () {
            var e = this, i = ++t;
            return function (s) {
                i === t && e.__response(s), e.pending--, e.pending || e.element.removeClass("ui-autocomplete-loading")
            }
        },
        __response: function (e) {
            e && (e = this._normalize(e)), this._trigger("response", null, {content: e}), !this.options.disabled && e && e.length && !this.cancelSearch ? (this._suggest(e), this._trigger("open")) : this._close()
        },
        close: function (e) {
            this.cancelSearch = !0, this._close(e)
        },
        _close: function (e) {
            this.menu.element.is(":visible") && (this.menu.element.hide(), this.menu.blur(), this.isNewMenu = !0, this._trigger("close", e))
        },
        _change: function (e) {
            this.previous !== this._value() && this._trigger("change", e, {item: this.selectedItem})
        },
        _normalize: function (t) {
            return t.length && t[0].label && t[0].value ? t : e.map(t, function (t) {
                return "string" == typeof t ? {label: t, value: t} : e.extend({
                    label: t.label || t.value,
                    value: t.value || t.label
                }, t)
            })
        },
        _suggest: function (t) {
            var i = this.menu.element.empty().zIndex(this.element.zIndex() + 1);
            this._renderMenu(i, t), this.menu.refresh(), i.show(), this._resizeMenu(), i.position(e.extend({of: this.element}, this.options.position)), this.options.autoFocus && this.menu.next()
        },
        _resizeMenu: function () {
            var e = this.menu.element;
            e.outerWidth(Math.max(e.width("").outerWidth() + 1, this.element.outerWidth()))
        },
        _renderMenu: function (t, i) {
            var s = this;
            e.each(i, function (e, i) {
                s._renderItemData(t, i)
            })
        },
        _renderItemData: function (e, t) {
            return this._renderItem(e, t).data("ui-autocomplete-item", t)
        },
        _renderItem: function (t, i) {
            return e("<li>").append(e("<a>").text(i.label)).appendTo(t)
        },
        _move: function (e, t) {
            return this.menu.element.is(":visible") ? this.menu.isFirstItem() && /^previous/.test(e) || this.menu.isLastItem() && /^next/.test(e) ? (this._value(this.term), this.menu.blur(), undefined) : (this.menu[e](t), undefined) : (this.search(null, t), undefined)
        },
        widget: function () {
            return this.menu.element
        },
        _value: function () {
            return this.valueMethod.apply(this.element, arguments)
        },
        _keyEvent: function (e, t) {
            (!this.isMultiLine || this.menu.element.is(":visible")) && (this._move(e, t), t.preventDefault())
        }
    }), e.extend(e.ui.autocomplete, {
        escapeRegex: function (e) {
            return e.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&")
        }, filter: function (t, i) {
            var s = RegExp(e.ui.autocomplete.escapeRegex(i), "i");
            return e.grep(t, function (e) {
                return s.test(e.label || e.value || e)
            })
        }
    }), e.widget("ui.autocomplete", e.ui.autocomplete, {
        options: {
            messages: {
                noResults: "No search results.",
                results: function (e) {
                    return e + (e > 1 ? " results are" : " result is") + " available, use up and down arrow keys to navigate."
                }
            }
        }, __response: function (e) {
            var t;
            this._superApply(arguments), this.options.disabled || this.cancelSearch || (t = e && e.length ? this.options.messages.results(e.length) : this.options.messages.noResults, this.liveRegion.text(t))
        }
    })
})(jQuery);
(function (e) {
    var t, i, s, n, a = "ui-button ui-widget ui-state-default ui-corner-all", o = "ui-state-hover ui-state-active ", r = "ui-button-icons-only ui-button-icon-only ui-button-text-icons ui-button-text-icon-primary ui-button-text-icon-secondary ui-button-text-only", h = function () {
        var t = e(this).find(":ui-button");
        setTimeout(function () {
            t.button("refresh")
        }, 1)
    }, l = function (t) {
        var i = t.name, s = t.form, n = e([]);
        return i && (n = s ? e(s).find("[name='" + i + "']") : e("[name='" + i + "']", t.ownerDocument).filter(function () {
            return !this.form
        })), n
    };
    e.widget("ui.button", {
        version: "1.9.2",
        defaultElement: "<button>",
        options: {disabled: null, text: !0, label: null, icons: {primary: null, secondary: null}},
        _create: function () {
            this.element.closest("form").unbind("reset" + this.eventNamespace).bind("reset" + this.eventNamespace, h), "boolean" != typeof this.options.disabled ? this.options.disabled = !!this.element.prop("disabled") : this.element.prop("disabled", this.options.disabled), this._determineButtonType(), this.hasTitle = !!this.buttonElement.attr("title");
            var o = this, r = this.options, u = "checkbox" === this.type || "radio" === this.type, d = u ? "" : "ui-state-active", c = "ui-state-focus";
            null === r.label && (r.label = "input" === this.type ? this.buttonElement.val() : this.buttonElement.html()), this._hoverable(this.buttonElement), this.buttonElement.addClass(a).attr("role", "button").bind("mouseenter" + this.eventNamespace, function () {
                r.disabled || this === t && e(this).addClass("ui-state-active")
            }).bind("mouseleave" + this.eventNamespace, function () {
                r.disabled || e(this).removeClass(d)
            }).bind("click" + this.eventNamespace, function (e) {
                r.disabled && (e.preventDefault(), e.stopImmediatePropagation())
            }), this.element.bind("focus" + this.eventNamespace, function () {
                o.buttonElement.addClass(c)
            }).bind("blur" + this.eventNamespace, function () {
                o.buttonElement.removeClass(c)
            }), u && (this.element.bind("change" + this.eventNamespace, function () {
                n || o.refresh()
            }), this.buttonElement.bind("mousedown" + this.eventNamespace, function (e) {
                r.disabled || (n = !1, i = e.pageX, s = e.pageY)
            }).bind("mouseup" + this.eventNamespace, function (e) {
                r.disabled || (i !== e.pageX || s !== e.pageY) && (n = !0)
            })), "checkbox" === this.type ? this.buttonElement.bind("click" + this.eventNamespace, function () {
                return r.disabled || n ? !1 : (e(this).toggleClass("ui-state-active"), o.buttonElement.attr("aria-pressed", o.element[0].checked), undefined)
            }) : "radio" === this.type ? this.buttonElement.bind("click" + this.eventNamespace, function () {
                if (r.disabled || n)return !1;
                e(this).addClass("ui-state-active"), o.buttonElement.attr("aria-pressed", "true");
                var t = o.element[0];
                l(t).not(t).map(function () {
                    return e(this).button("widget")[0]
                }).removeClass("ui-state-active").attr("aria-pressed", "false")
            }) : (this.buttonElement.bind("mousedown" + this.eventNamespace, function () {
                return r.disabled ? !1 : (e(this).addClass("ui-state-active"), t = this, o.document.one("mouseup", function () {
                    t = null
                }), undefined)
            }).bind("mouseup" + this.eventNamespace, function () {
                return r.disabled ? !1 : (e(this).removeClass("ui-state-active"), undefined)
            }).bind("keydown" + this.eventNamespace, function (t) {
                return r.disabled ? !1 : ((t.keyCode === e.ui.keyCode.SPACE || t.keyCode === e.ui.keyCode.ENTER) && e(this).addClass("ui-state-active"), undefined)
            }).bind("keyup" + this.eventNamespace, function () {
                e(this).removeClass("ui-state-active")
            }), this.buttonElement.is("a") && this.buttonElement.keyup(function (t) {
                t.keyCode === e.ui.keyCode.SPACE && e(this).click()
            })), this._setOption("disabled", r.disabled), this._resetButton()
        },
        _determineButtonType: function () {
            var e, t, i;
            this.type = this.element.is("[type=checkbox]") ? "checkbox" : this.element.is("[type=radio]") ? "radio" : this.element.is("input") ? "input" : "button", "checkbox" === this.type || "radio" === this.type ? (e = this.element.parents().last(), t = "label[for='" + this.element.attr("id") + "']", this.buttonElement = e.find(t), this.buttonElement.length || (e = e.length ? e.siblings() : this.element.siblings(), this.buttonElement = e.filter(t), this.buttonElement.length || (this.buttonElement = e.find(t))), this.element.addClass("ui-helper-hidden-accessible"), i = this.element.is(":checked"), i && this.buttonElement.addClass("ui-state-active"), this.buttonElement.prop("aria-pressed", i)) : this.buttonElement = this.element
        },
        widget: function () {
            return this.buttonElement
        },
        _destroy: function () {
            this.element.removeClass("ui-helper-hidden-accessible"), this.buttonElement.removeClass(a + " " + o + " " + r).removeAttr("role").removeAttr("aria-pressed").html(this.buttonElement.find(".ui-button-text").html()), this.hasTitle || this.buttonElement.removeAttr("title")
        },
        _setOption: function (e, t) {
            return this._super(e, t), "disabled" === e ? (t ? this.element.prop("disabled", !0) : this.element.prop("disabled", !1), undefined) : (this._resetButton(), undefined)
        },
        refresh: function () {
            var t = this.element.is("input, button") ? this.element.is(":disabled") : this.element.hasClass("ui-button-disabled");
            t !== this.options.disabled && this._setOption("disabled", t), "radio" === this.type ? l(this.element[0]).each(function () {
                e(this).is(":checked") ? e(this).button("widget").addClass("ui-state-active").attr("aria-pressed", "true") : e(this).button("widget").removeClass("ui-state-active").attr("aria-pressed", "false")
            }) : "checkbox" === this.type && (this.element.is(":checked") ? this.buttonElement.addClass("ui-state-active").attr("aria-pressed", "true") : this.buttonElement.removeClass("ui-state-active").attr("aria-pressed", "false"))
        },
        _resetButton: function () {
            if ("input" === this.type)return this.options.label && this.element.val(this.options.label), undefined;
            var t = this.buttonElement.removeClass(r), i = e("<span></span>", this.document[0]).addClass("ui-button-text").html(this.options.label).appendTo(t.empty()).text(), s = this.options.icons, n = s.primary && s.secondary, a = [];
            s.primary || s.secondary ? (this.options.text && a.push("ui-button-text-icon" + (n ? "s" : s.primary ? "-primary" : "-secondary")), s.primary && t.prepend("<span class='ui-button-icon-primary ui-icon " + s.primary + "'></span>"), s.secondary && t.append("<span class='ui-button-icon-secondary ui-icon " + s.secondary + "'></span>"), this.options.text || (a.push(n ? "ui-button-icons-only" : "ui-button-icon-only"), this.hasTitle || t.attr("title", e.trim(i)))) : a.push("ui-button-text-only"), t.addClass(a.join(" "))
        }
    }), e.widget("ui.buttonset", {
        version: "1.9.2",
        options: {items: "button, input[type=button], input[type=submit], input[type=reset], input[type=checkbox], input[type=radio], a, :data(button)"},
        _create: function () {
            this.element.addClass("ui-buttonset")
        },
        _init: function () {
            this.refresh()
        },
        _setOption: function (e, t) {
            "disabled" === e && this.buttons.button("option", e, t), this._super(e, t)
        },
        refresh: function () {
            var t = "rtl" === this.element.css("direction");
            this.buttons = this.element.find(this.options.items).filter(":ui-button").button("refresh").end().not(":ui-button").button().end().map(function () {
                return e(this).button("widget")[0]
            }).removeClass("ui-corner-all ui-corner-left ui-corner-right").filter(":first").addClass(t ? "ui-corner-right" : "ui-corner-left").end().filter(":last").addClass(t ? "ui-corner-left" : "ui-corner-right").end().end()
        },
        _destroy: function () {
            this.element.removeClass("ui-buttonset"), this.buttons.map(function () {
                return e(this).button("widget")[0]
            }).removeClass("ui-corner-left ui-corner-right").end().button("destroy")
        }
    })
})(jQuery);
(function ($, undefined) {
    function Datepicker() {
        this.debug = !1, this._curInst = null, this._keyEvent = !1, this._disabledInputs = [], this._datepickerShowing = !1, this._inDialog = !1, this._mainDivId = "ui-datepicker-div", this._inlineClass = "ui-datepicker-inline", this._appendClass = "ui-datepicker-append", this._triggerClass = "ui-datepicker-trigger", this._dialogClass = "ui-datepicker-dialog", this._disableClass = "ui-datepicker-disabled", this._unselectableClass = "ui-datepicker-unselectable", this._currentClass = "ui-datepicker-current-day", this._dayOverClass = "ui-datepicker-days-cell-over", this.regional = [], this.regional[""] = {
            closeText: "Done",
            prevText: "Prev",
            nextText: "Next",
            currentText: "Today",
            monthNames: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
            monthNamesShort: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
            dayNames: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
            dayNamesShort: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
            dayNamesMin: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"],
            weekHeader: "Wk",
            dateFormat: "mm/dd/yy",
            firstDay: 0,
            isRTL: !1,
            showMonthAfterYear: !1,
            yearSuffix: ""
        }, this._defaults = {
            showOn: "focus",
            showAnim: "fadeIn",
            showOptions: {},
            defaultDate: null,
            appendText: "",
            buttonText: "...",
            buttonImage: "",
            buttonImageOnly: !1,
            hideIfNoPrevNext: !1,
            navigationAsDateFormat: !1,
            gotoCurrent: !1,
            changeMonth: !1,
            changeYear: !1,
            yearRange: "c-10:c+10",
            showOtherMonths: !1,
            selectOtherMonths: !1,
            showWeek: !1,
            calculateWeek: this.iso8601Week,
            shortYearCutoff: "+10",
            minDate: null,
            maxDate: null,
            duration: "fast",
            beforeShowDay: null,
            beforeShow: null,
            onSelect: null,
            onChangeMonthYear: null,
            onClose: null,
            numberOfMonths: 1,
            showCurrentAtPos: 0,
            stepMonths: 1,
            stepBigMonths: 12,
            altField: "",
            altFormat: "",
            constrainInput: !0,
            showButtonPanel: !1,
            autoSize: !1,
            disabled: !1
        }, $.extend(this._defaults, this.regional[""]), this.dpDiv = bindHover($('<div id="' + this._mainDivId + '" class="ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"></div>'))
    }

    function bindHover(e) {
        var t = "button, .ui-datepicker-prev, .ui-datepicker-next, .ui-datepicker-calendar td a";
        return e.delegate(t, "mouseout", function () {
            $(this).removeClass("ui-state-hover"), -1 != this.className.indexOf("ui-datepicker-prev") && $(this).removeClass("ui-datepicker-prev-hover"), -1 != this.className.indexOf("ui-datepicker-next") && $(this).removeClass("ui-datepicker-next-hover")
        }).delegate(t, "mouseover", function () {
            $.datepicker._isDisabledDatepicker(instActive.inline ? e.parent()[0] : instActive.input[0]) || ($(this).parents(".ui-datepicker-calendar").find("a").removeClass("ui-state-hover"), $(this).addClass("ui-state-hover"), -1 != this.className.indexOf("ui-datepicker-prev") && $(this).addClass("ui-datepicker-prev-hover"), -1 != this.className.indexOf("ui-datepicker-next") && $(this).addClass("ui-datepicker-next-hover"))
        })
    }

    function extendRemove(e, t) {
        $.extend(e, t);
        for (var i in t)(null == t[i] || t[i] == undefined) && (e[i] = t[i]);
        return e
    }

    $.extend($.ui, {datepicker: {version: "1.9.2"}});
    var PROP_NAME = "datepicker", dpuuid = (new Date).getTime(), instActive;
    $.extend(Datepicker.prototype, {
        markerClassName: "hasDatepicker",
        maxRows: 4,
        log: function () {
            this.debug && console.log.apply("", arguments)
        },
        _widgetDatepicker: function () {
            return this.dpDiv
        },
        setDefaults: function (e) {
            return extendRemove(this._defaults, e || {}), this
        },
        _attachDatepicker: function (target, settings) {
            var inlineSettings = null;
            for (var attrName in this._defaults) {
                var attrValue = target.getAttribute("date:" + attrName);
                if (attrValue) {
                    inlineSettings = inlineSettings || {};
                    try {
                        inlineSettings[attrName] = eval(attrValue)
                    } catch (err) {
                        inlineSettings[attrName] = attrValue
                    }
                }
            }
            var nodeName = target.nodeName.toLowerCase(), inline = "div" == nodeName || "span" == nodeName;
            target.id || (this.uuid += 1, target.id = "dp" + this.uuid);
            var inst = this._newInst($(target), inline);
            inst.settings = $.extend({}, settings || {}, inlineSettings || {}), "input" == nodeName ? this._connectDatepicker(target, inst) : inline && this._inlineDatepicker(target, inst)
        },
        _newInst: function (e, t) {
            var i = e[0].id.replace(/([^A-Za-z0-9_-])/g, "\\\\$1");
            return {
                id: i,
                input: e,
                selectedDay: 0,
                selectedMonth: 0,
                selectedYear: 0,
                drawMonth: 0,
                drawYear: 0,
                inline: t,
                dpDiv: t ? bindHover($('<div class="' + this._inlineClass + ' ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"></div>')) : this.dpDiv
            }
        },
        _connectDatepicker: function (e, t) {
            var i = $(e);
            t.append = $([]), t.trigger = $([]), i.hasClass(this.markerClassName) || (this._attachments(i, t), i.addClass(this.markerClassName).keydown(this._doKeyDown).keypress(this._doKeyPress).keyup(this._doKeyUp).bind("setData.datepicker", function (e, i, s) {
                t.settings[i] = s
            }).bind("getData.datepicker", function (e, i) {
                return this._get(t, i)
            }), this._autoSize(t), $.data(e, PROP_NAME, t), t.settings.disabled && this._disableDatepicker(e))
        },
        _attachments: function (e, t) {
            var i = this._get(t, "appendText"), s = this._get(t, "isRTL");
            t.append && t.append.remove(), i && (t.append = $('<span class="' + this._appendClass + '">' + i + "</span>"), e[s ? "before" : "after"](t.append)), e.unbind("focus", this._showDatepicker), t.trigger && t.trigger.remove();
            var n = this._get(t, "showOn");
            if (("focus" == n || "both" == n) && e.focus(this._showDatepicker), "button" == n || "both" == n) {
                var a = this._get(t, "buttonText"), r = this._get(t, "buttonImage");
                t.trigger = $(this._get(t, "buttonImageOnly") ? $("<img/>").addClass(this._triggerClass).attr({
                    src: r,
                    alt: a,
                    title: a
                }) : $('<button type="button"></button>').addClass(this._triggerClass).html("" == r ? a : $("<img/>").attr({
                    src: r,
                    alt: a,
                    title: a
                }))), e[s ? "before" : "after"](t.trigger), t.trigger.click(function () {
                    return $.datepicker._datepickerShowing && $.datepicker._lastInput == e[0] ? $.datepicker._hideDatepicker() : $.datepicker._datepickerShowing && $.datepicker._lastInput != e[0] ? ($.datepicker._hideDatepicker(), $.datepicker._showDatepicker(e[0])) : $.datepicker._showDatepicker(e[0]), !1
                })
            }
        },
        _autoSize: function (e) {
            if (this._get(e, "autoSize") && !e.inline) {
                var t = new Date(2009, 11, 20), i = this._get(e, "dateFormat");
                if (i.match(/[DM]/)) {
                    var s = function (e) {
                        for (var t = 0, i = 0, s = 0; e.length > s; s++)e[s].length > t && (t = e[s].length, i = s);
                        return i
                    };
                    t.setMonth(s(this._get(e, i.match(/MM/) ? "monthNames" : "monthNamesShort"))), t.setDate(s(this._get(e, i.match(/DD/) ? "dayNames" : "dayNamesShort")) + 20 - t.getDay())
                }
                e.input.attr("size", this._formatDate(e, t).length)
            }
        },
        _inlineDatepicker: function (e, t) {
            var i = $(e);
            i.hasClass(this.markerClassName) || (i.addClass(this.markerClassName).append(t.dpDiv).bind("setData.datepicker", function (e, i, s) {
                t.settings[i] = s
            }).bind("getData.datepicker", function (e, i) {
                return this._get(t, i)
            }), $.data(e, PROP_NAME, t), this._setDate(t, this._getDefaultDate(t), !0), this._updateDatepicker(t), this._updateAlternate(t), t.settings.disabled && this._disableDatepicker(e), t.dpDiv.css("display", "block"))
        },
        _dialogDatepicker: function (e, t, i, s, n) {
            var a = this._dialogInst;
            if (!a) {
                this.uuid += 1;
                var r = "dp" + this.uuid;
                this._dialogInput = $('<input type="text" id="' + r + '" style="position: absolute; top: -100px; width: 0px;"/>'), this._dialogInput.keydown(this._doKeyDown), $("body").append(this._dialogInput), a = this._dialogInst = this._newInst(this._dialogInput, !1), a.settings = {}, $.data(this._dialogInput[0], PROP_NAME, a)
            }
            if (extendRemove(a.settings, s || {}), t = t && t.constructor == Date ? this._formatDate(a, t) : t, this._dialogInput.val(t), this._pos = n ? n.length ? n : [n.pageX, n.pageY] : null, !this._pos) {
                var o = document.documentElement.clientWidth, h = document.documentElement.clientHeight, l = document.documentElement.scrollLeft || document.body.scrollLeft, u = document.documentElement.scrollTop || document.body.scrollTop;
                this._pos = [o / 2 - 100 + l, h / 2 - 150 + u]
            }
            return this._dialogInput.css("left", this._pos[0] + 20 + "px").css("top", this._pos[1] + "px"), a.settings.onSelect = i, this._inDialog = !0, this.dpDiv.addClass(this._dialogClass), this._showDatepicker(this._dialogInput[0]), $.blockUI && $.blockUI(this.dpDiv), $.data(this._dialogInput[0], PROP_NAME, a), this
        },
        _destroyDatepicker: function (e) {
            var t = $(e), i = $.data(e, PROP_NAME);
            if (t.hasClass(this.markerClassName)) {
                var s = e.nodeName.toLowerCase();
                $.removeData(e, PROP_NAME), "input" == s ? (i.append.remove(), i.trigger.remove(), t.removeClass(this.markerClassName).unbind("focus", this._showDatepicker).unbind("keydown", this._doKeyDown).unbind("keypress", this._doKeyPress).unbind("keyup", this._doKeyUp)) : ("div" == s || "span" == s) && t.removeClass(this.markerClassName).empty()
            }
        },
        _enableDatepicker: function (e) {
            var t = $(e), i = $.data(e, PROP_NAME);
            if (t.hasClass(this.markerClassName)) {
                var s = e.nodeName.toLowerCase();
                if ("input" == s)e.disabled = !1, i.trigger.filter("button").each(function () {
                    this.disabled = !1
                }).end().filter("img").css({opacity: "1.0", cursor: ""}); else if ("div" == s || "span" == s) {
                    var n = t.children("." + this._inlineClass);
                    n.children().removeClass("ui-state-disabled"), n.find("select.ui-datepicker-month, select.ui-datepicker-year").prop("disabled", !1)
                }
                this._disabledInputs = $.map(this._disabledInputs, function (t) {
                    return t == e ? null : t
                })
            }
        },
        _disableDatepicker: function (e) {
            var t = $(e), i = $.data(e, PROP_NAME);
            if (t.hasClass(this.markerClassName)) {
                var s = e.nodeName.toLowerCase();
                if ("input" == s)e.disabled = !0, i.trigger.filter("button").each(function () {
                    this.disabled = !0
                }).end().filter("img").css({opacity: "0.5", cursor: "default"}); else if ("div" == s || "span" == s) {
                    var n = t.children("." + this._inlineClass);
                    n.children().addClass("ui-state-disabled"), n.find("select.ui-datepicker-month, select.ui-datepicker-year").prop("disabled", !0)
                }
                this._disabledInputs = $.map(this._disabledInputs, function (t) {
                    return t == e ? null : t
                }), this._disabledInputs[this._disabledInputs.length] = e
            }
        },
        _isDisabledDatepicker: function (e) {
            if (!e)return !1;
            for (var t = 0; this._disabledInputs.length > t; t++)if (this._disabledInputs[t] == e)return !0;
            return !1
        },
        _getInst: function (e) {
            try {
                return $.data(e, PROP_NAME)
            } catch (t) {
                throw"Missing instance data for this datepicker"
            }
        },
        _optionDatepicker: function (e, t, i) {
            var s = this._getInst(e);
            if (2 == arguments.length && "string" == typeof t)return "defaults" == t ? $.extend({}, $.datepicker._defaults) : s ? "all" == t ? $.extend({}, s.settings) : this._get(s, t) : null;
            var n = t || {};
            if ("string" == typeof t && (n = {}, n[t] = i), s) {
                this._curInst == s && this._hideDatepicker();
                var a = this._getDateDatepicker(e, !0), r = this._getMinMaxDate(s, "min"), o = this._getMinMaxDate(s, "max");
                extendRemove(s.settings, n), null !== r && n.dateFormat !== undefined && n.minDate === undefined && (s.settings.minDate = this._formatDate(s, r)), null !== o && n.dateFormat !== undefined && n.maxDate === undefined && (s.settings.maxDate = this._formatDate(s, o)), this._attachments($(e), s), this._autoSize(s), this._setDate(s, a), this._updateAlternate(s), this._updateDatepicker(s)
            }
        },
        _changeDatepicker: function (e, t, i) {
            this._optionDatepicker(e, t, i)
        },
        _refreshDatepicker: function (e) {
            var t = this._getInst(e);
            t && this._updateDatepicker(t)
        },
        _setDateDatepicker: function (e, t) {
            var i = this._getInst(e);
            i && (this._setDate(i, t), this._updateDatepicker(i), this._updateAlternate(i))
        },
        _getDateDatepicker: function (e, t) {
            var i = this._getInst(e);
            return i && !i.inline && this._setDateFromField(i, t), i ? this._getDate(i) : null
        },
        _doKeyDown: function (e) {
            var t = $.datepicker._getInst(e.target), i = !0, s = t.dpDiv.is(".ui-datepicker-rtl");
            if (t._keyEvent = !0, $.datepicker._datepickerShowing)switch (e.keyCode) {
                case 9:
                    $.datepicker._hideDatepicker(), i = !1;
                    break;
                case 13:
                    var n = $("td." + $.datepicker._dayOverClass + ":not(." + $.datepicker._currentClass + ")", t.dpDiv);
                    n[0] && $.datepicker._selectDay(e.target, t.selectedMonth, t.selectedYear, n[0]);
                    var a = $.datepicker._get(t, "onSelect");
                    if (a) {
                        var r = $.datepicker._formatDate(t);
                        a.apply(t.input ? t.input[0] : null, [r, t])
                    } else $.datepicker._hideDatepicker();
                    return !1;
                case 27:
                    $.datepicker._hideDatepicker();
                    break;
                case 33:
                    $.datepicker._adjustDate(e.target, e.ctrlKey ? -$.datepicker._get(t, "stepBigMonths") : -$.datepicker._get(t, "stepMonths"), "M");
                    break;
                case 34:
                    $.datepicker._adjustDate(e.target, e.ctrlKey ? +$.datepicker._get(t, "stepBigMonths") : +$.datepicker._get(t, "stepMonths"), "M");
                    break;
                case 35:
                    (e.ctrlKey || e.metaKey) && $.datepicker._clearDate(e.target), i = e.ctrlKey || e.metaKey;
                    break;
                case 36:
                    (e.ctrlKey || e.metaKey) && $.datepicker._gotoToday(e.target), i = e.ctrlKey || e.metaKey;
                    break;
                case 37:
                    (e.ctrlKey || e.metaKey) && $.datepicker._adjustDate(e.target, s ? 1 : -1, "D"), i = e.ctrlKey || e.metaKey, e.originalEvent.altKey && $.datepicker._adjustDate(e.target, e.ctrlKey ? -$.datepicker._get(t, "stepBigMonths") : -$.datepicker._get(t, "stepMonths"), "M");
                    break;
                case 38:
                    (e.ctrlKey || e.metaKey) && $.datepicker._adjustDate(e.target, -7, "D"), i = e.ctrlKey || e.metaKey;
                    break;
                case 39:
                    (e.ctrlKey || e.metaKey) && $.datepicker._adjustDate(e.target, s ? -1 : 1, "D"), i = e.ctrlKey || e.metaKey, e.originalEvent.altKey && $.datepicker._adjustDate(e.target, e.ctrlKey ? +$.datepicker._get(t, "stepBigMonths") : +$.datepicker._get(t, "stepMonths"), "M");
                    break;
                case 40:
                    (e.ctrlKey || e.metaKey) && $.datepicker._adjustDate(e.target, 7, "D"), i = e.ctrlKey || e.metaKey;
                    break;
                default:
                    i = !1
            } else 36 == e.keyCode && e.ctrlKey ? $.datepicker._showDatepicker(this) : i = !1;
            i && (e.preventDefault(), e.stopPropagation())
        },
        _doKeyPress: function (e) {
            var t = $.datepicker._getInst(e.target);
            if ($.datepicker._get(t, "constrainInput")) {
                var i = $.datepicker._possibleChars($.datepicker._get(t, "dateFormat")), s = String.fromCharCode(e.charCode == undefined ? e.keyCode : e.charCode);
                return e.ctrlKey || e.metaKey || " " > s || !i || i.indexOf(s) > -1
            }
        },
        _doKeyUp: function (e) {
            var t = $.datepicker._getInst(e.target);
            if (t.input.val() != t.lastVal)try {
                var i = $.datepicker.parseDate($.datepicker._get(t, "dateFormat"), t.input ? t.input.val() : null, $.datepicker._getFormatConfig(t));
                i && ($.datepicker._setDateFromField(t), $.datepicker._updateAlternate(t), $.datepicker._updateDatepicker(t))
            } catch (s) {
                $.datepicker.log(s)
            }
            return !0
        },
        _showDatepicker: function (e) {
            if (e = e.target || e, "input" != e.nodeName.toLowerCase() && (e = $("input", e.parentNode)[0]), !$.datepicker._isDisabledDatepicker(e) && $.datepicker._lastInput != e) {
                var t = $.datepicker._getInst(e);
                $.datepicker._curInst && $.datepicker._curInst != t && ($.datepicker._curInst.dpDiv.stop(!0, !0), t && $.datepicker._datepickerShowing && $.datepicker._hideDatepicker($.datepicker._curInst.input[0]));
                var i = $.datepicker._get(t, "beforeShow"), s = i ? i.apply(e, [e, t]) : {};
                if (s !== !1) {
                    extendRemove(t.settings, s), t.lastVal = null, $.datepicker._lastInput = e, $.datepicker._setDateFromField(t), $.datepicker._inDialog && (e.value = ""), $.datepicker._pos || ($.datepicker._pos = $.datepicker._findPos(e), $.datepicker._pos[1] += e.offsetHeight);
                    var n = !1;
                    $(e).parents().each(function () {
                        return n |= "fixed" == $(this).css("position"), !n
                    });
                    var a = {left: $.datepicker._pos[0], top: $.datepicker._pos[1]};
                    if ($.datepicker._pos = null, t.dpDiv.empty(), t.dpDiv.css({
                            position: "absolute",
                            display: "block",
                            top: "-1000px"
                        }), $.datepicker._updateDatepicker(t), a = $.datepicker._checkOffset(t, a, n), t.dpDiv.css({
                            position: $.datepicker._inDialog && $.blockUI ? "static" : n ? "fixed" : "absolute",
                            display: "none",
                            left: a.left + "px",
                            top: a.top + "px"
                        }), !t.inline) {
                        var r = $.datepicker._get(t, "showAnim"), o = $.datepicker._get(t, "duration"), h = function () {
                            var e = t.dpDiv.find("iframe.ui-datepicker-cover");
                            if (e.length) {
                                var i = $.datepicker._getBorders(t.dpDiv);
                                e.css({
                                    left: -i[0],
                                    top: -i[1],
                                    width: t.dpDiv.outerWidth(),
                                    height: t.dpDiv.outerHeight()
                                })
                            }
                        };
                        t.dpDiv.zIndex($(e).zIndex() + 1), $.datepicker._datepickerShowing = !0, $.effects && ($.effects.effect[r] || $.effects[r]) ? t.dpDiv.show(r, $.datepicker._get(t, "showOptions"), o, h) : t.dpDiv[r || "show"](r ? o : null, h), r && o || h(), t.input.is(":visible") && !t.input.is(":disabled") && t.input.focus(), $.datepicker._curInst = t
                    }
                }
            }
        },
        _updateDatepicker: function (e) {
            this.maxRows = 4;
            var t = $.datepicker._getBorders(e.dpDiv);
            instActive = e, e.dpDiv.empty().append(this._generateHTML(e)), this._attachHandlers(e);
            var i = e.dpDiv.find("iframe.ui-datepicker-cover");
            i.length && i.css({
                left: -t[0],
                top: -t[1],
                width: e.dpDiv.outerWidth(),
                height: e.dpDiv.outerHeight()
            }), e.dpDiv.find("." + this._dayOverClass + " a").mouseover();
            var s = this._getNumberOfMonths(e), n = s[1], a = 17;
            if (e.dpDiv.removeClass("ui-datepicker-multi-2 ui-datepicker-multi-3 ui-datepicker-multi-4").width(""), n > 1 && e.dpDiv.addClass("ui-datepicker-multi-" + n).css("width", a * n + "em"), e.dpDiv[(1 != s[0] || 1 != s[1] ? "add" : "remove") + "Class"]("ui-datepicker-multi"), e.dpDiv[(this._get(e, "isRTL") ? "add" : "remove") + "Class"]("ui-datepicker-rtl"), e == $.datepicker._curInst && $.datepicker._datepickerShowing && e.input && e.input.is(":visible") && !e.input.is(":disabled") && e.input[0] != document.activeElement && e.input.focus(), e.yearshtml) {
                var r = e.yearshtml;
                setTimeout(function () {
                    r === e.yearshtml && e.yearshtml && e.dpDiv.find("select.ui-datepicker-year:first").replaceWith(e.yearshtml), r = e.yearshtml = null
                }, 0)
            }
        },
        _getBorders: function (e) {
            var t = function (e) {
                return {thin: 1, medium: 2, thick: 3}[e] || e
            };
            return [parseFloat(t(e.css("border-left-width"))), parseFloat(t(e.css("border-top-width")))]
        },
        _checkOffset: function (e, t, i) {
            var s = e.dpDiv.outerWidth(), n = e.dpDiv.outerHeight(), a = e.input ? e.input.outerWidth() : 0, r = e.input ? e.input.outerHeight() : 0, o = document.documentElement.clientWidth + (i ? 0 : $(document).scrollLeft()), h = document.documentElement.clientHeight + (i ? 0 : $(document).scrollTop());
            return t.left -= this._get(e, "isRTL") ? s - a : 0, t.left -= i && t.left == e.input.offset().left ? $(document).scrollLeft() : 0, t.top -= i && t.top == e.input.offset().top + r ? $(document).scrollTop() : 0, t.left -= Math.min(t.left, t.left + s > o && o > s ? Math.abs(t.left + s - o) : 0), t.top -= Math.min(t.top, t.top + n > h && h > n ? Math.abs(n + r) : 0), t
        },
        _findPos: function (e) {
            for (var t = this._getInst(e), i = this._get(t, "isRTL"); e && ("hidden" == e.type || 1 != e.nodeType || $.expr.filters.hidden(e));)e = e[i ? "previousSibling" : "nextSibling"];
            var s = $(e).offset();
            return [s.left, s.top]
        },
        _hideDatepicker: function (e) {
            var t = this._curInst;
            if (t && (!e || t == $.data(e, PROP_NAME)) && this._datepickerShowing) {
                var i = this._get(t, "showAnim"), s = this._get(t, "duration"), n = function () {
                    $.datepicker._tidyDialog(t)
                };
                $.effects && ($.effects.effect[i] || $.effects[i]) ? t.dpDiv.hide(i, $.datepicker._get(t, "showOptions"), s, n) : t.dpDiv["slideDown" == i ? "slideUp" : "fadeIn" == i ? "fadeOut" : "hide"](i ? s : null, n), i || n(), this._datepickerShowing = !1;
                var a = this._get(t, "onClose");
                a && a.apply(t.input ? t.input[0] : null, [t.input ? t.input.val() : "", t]), this._lastInput = null, this._inDialog && (this._dialogInput.css({
                    position: "absolute",
                    left: "0",
                    top: "-100px"
                }), $.blockUI && ($.unblockUI(), $("body").append(this.dpDiv))), this._inDialog = !1
            }
        },
        _tidyDialog: function (e) {
            e.dpDiv.removeClass(this._dialogClass).unbind(".ui-datepicker-calendar")
        },
        _checkExternalClick: function (e) {
            if ($.datepicker._curInst) {
                var t = $(e.target), i = $.datepicker._getInst(t[0]);
                (t[0].id != $.datepicker._mainDivId && 0 == t.parents("#" + $.datepicker._mainDivId).length && !t.hasClass($.datepicker.markerClassName) && !t.closest("." + $.datepicker._triggerClass).length && $.datepicker._datepickerShowing && (!$.datepicker._inDialog || !$.blockUI) || t.hasClass($.datepicker.markerClassName) && $.datepicker._curInst != i) && $.datepicker._hideDatepicker()
            }
        },
        _adjustDate: function (e, t, i) {
            var s = $(e), n = this._getInst(s[0]);
            this._isDisabledDatepicker(s[0]) || (this._adjustInstDate(n, t + ("M" == i ? this._get(n, "showCurrentAtPos") : 0), i), this._updateDatepicker(n))
        },
        _gotoToday: function (e) {
            var t = $(e), i = this._getInst(t[0]);
            if (this._get(i, "gotoCurrent") && i.currentDay)i.selectedDay = i.currentDay, i.drawMonth = i.selectedMonth = i.currentMonth, i.drawYear = i.selectedYear = i.currentYear; else {
                var s = new Date;
                i.selectedDay = s.getDate(), i.drawMonth = i.selectedMonth = s.getMonth(), i.drawYear = i.selectedYear = s.getFullYear()
            }
            this._notifyChange(i), this._adjustDate(t)
        },
        _selectMonthYear: function (e, t, i) {
            var s = $(e), n = this._getInst(s[0]);
            n["selected" + ("M" == i ? "Month" : "Year")] = n["draw" + ("M" == i ? "Month" : "Year")] = parseInt(t.options[t.selectedIndex].value, 10), this._notifyChange(n), this._adjustDate(s)
        },
        _selectDay: function (e, t, i, s) {
            var n = $(e);
            if (!$(s).hasClass(this._unselectableClass) && !this._isDisabledDatepicker(n[0])) {
                var a = this._getInst(n[0]);
                a.selectedDay = a.currentDay = $("a", s).html(), a.selectedMonth = a.currentMonth = t, a.selectedYear = a.currentYear = i, this._selectDate(e, this._formatDate(a, a.currentDay, a.currentMonth, a.currentYear))
            }
        },
        _clearDate: function (e) {
            var t = $(e);
            this._getInst(t[0]), this._selectDate(t, "")
        },
        _selectDate: function (e, t) {
            var i = $(e), s = this._getInst(i[0]);
            t = null != t ? t : this._formatDate(s), s.input && s.input.val(t), this._updateAlternate(s);
            var n = this._get(s, "onSelect");
            n ? n.apply(s.input ? s.input[0] : null, [t, s]) : s.input && s.input.trigger("change"), s.inline ? this._updateDatepicker(s) : (this._hideDatepicker(), this._lastInput = s.input[0], "object" != typeof s.input[0] && s.input.focus(), this._lastInput = null)
        },
        _updateAlternate: function (e) {
            var t = this._get(e, "altField");
            if (t) {
                var i = this._get(e, "altFormat") || this._get(e, "dateFormat"), s = this._getDate(e), n = this.formatDate(i, s, this._getFormatConfig(e));
                $(t).each(function () {
                    $(this).val(n)
                })
            }
        },
        noWeekends: function (e) {
            var t = e.getDay();
            return [t > 0 && 6 > t, ""]
        },
        iso8601Week: function (e) {
            var t = new Date(e.getTime());
            t.setDate(t.getDate() + 4 - (t.getDay() || 7));
            var i = t.getTime();
            return t.setMonth(0), t.setDate(1), Math.floor(Math.round((i - t) / 864e5) / 7) + 1
        },
        parseDate: function (e, t, i) {
            if (null == e || null == t)throw"Invalid arguments";
            if (t = "object" == typeof t ? "" + t : t + "", "" == t)return null;
            var s = (i ? i.shortYearCutoff : null) || this._defaults.shortYearCutoff;
            s = "string" != typeof s ? s : (new Date).getFullYear() % 100 + parseInt(s, 10);
            for (var n = (i ? i.dayNamesShort : null) || this._defaults.dayNamesShort, a = (i ? i.dayNames : null) || this._defaults.dayNames, r = (i ? i.monthNamesShort : null) || this._defaults.monthNamesShort, o = (i ? i.monthNames : null) || this._defaults.monthNames, h = -1, l = -1, u = -1, d = -1, c = !1, p = function (t) {
                var i = e.length > y + 1 && e.charAt(y + 1) == t;
                return i && y++, i
            }, f = function (e) {
                var i = p(e), s = "@" == e ? 14 : "!" == e ? 20 : "y" == e && i ? 4 : "o" == e ? 3 : 2, n = RegExp("^\\d{1," + s + "}"), a = t.substring(v).match(n);
                if (!a)throw"Missing number at position " + v;
                return v += a[0].length, parseInt(a[0], 10)
            }, m = function (e, i, s) {
                var n = $.map(p(e) ? s : i, function (e, t) {
                    return [[t, e]]
                }).sort(function (e, t) {
                    return -(e[1].length - t[1].length)
                }), a = -1;
                if ($.each(n, function (e, i) {
                        var s = i[1];
                        return t.substr(v, s.length).toLowerCase() == s.toLowerCase() ? (a = i[0], v += s.length, !1) : undefined
                    }), -1 != a)return a + 1;
                throw"Unknown name at position " + v
            }, g = function () {
                if (t.charAt(v) != e.charAt(y))throw"Unexpected literal at position " + v;
                v++
            }, v = 0, y = 0; e.length > y; y++)if (c)"'" != e.charAt(y) || p("'") ? g() : c = !1; else switch (e.charAt(y)) {
                case"d":
                    u = f("d");
                    break;
                case"D":
                    m("D", n, a);
                    break;
                case"o":
                    d = f("o");
                    break;
                case"m":
                    l = f("m");
                    break;
                case"M":
                    l = m("M", r, o);
                    break;
                case"y":
                    h = f("y");
                    break;
                case"@":
                    var b = new Date(f("@"));
                    h = b.getFullYear(), l = b.getMonth() + 1, u = b.getDate();
                    break;
                case"!":
                    var b = new Date((f("!") - this._ticksTo1970) / 1e4);
                    h = b.getFullYear(), l = b.getMonth() + 1, u = b.getDate();
                    break;
                case"'":
                    p("'") ? g() : c = !0;
                    break;
                default:
                    g()
            }
            if (t.length > v) {
                var _ = t.substr(v);
                if (!/^\s+/.test(_))throw"Extra/unparsed characters found in date: " + _
            }
            if (-1 == h ? h = (new Date).getFullYear() : 100 > h && (h += (new Date).getFullYear() - (new Date).getFullYear() % 100 + (s >= h ? 0 : -100)), d > -1)for (l = 1, u = d; ;) {
                var x = this._getDaysInMonth(h, l - 1);
                if (x >= u)break;
                l++, u -= x
            }
            var b = this._daylightSavingAdjust(new Date(h, l - 1, u));
            if (b.getFullYear() != h || b.getMonth() + 1 != l || b.getDate() != u)throw"Invalid date";
            return b
        },
        ATOM: "yy-mm-dd",
        COOKIE: "D, dd M yy",
        ISO_8601: "yy-mm-dd",
        RFC_822: "D, d M y",
        RFC_850: "DD, dd-M-y",
        RFC_1036: "D, d M y",
        RFC_1123: "D, d M yy",
        RFC_2822: "D, d M yy",
        RSS: "D, d M y",
        TICKS: "!",
        TIMESTAMP: "@",
        W3C: "yy-mm-dd",
        _ticksTo1970: 1e7 * 60 * 60 * 24 * (718685 + Math.floor(492.5) - Math.floor(19.7) + Math.floor(4.925)),
        formatDate: function (e, t, i) {
            if (!t)return "";
            var s = (i ? i.dayNamesShort : null) || this._defaults.dayNamesShort, n = (i ? i.dayNames : null) || this._defaults.dayNames, a = (i ? i.monthNamesShort : null) || this._defaults.monthNamesShort, r = (i ? i.monthNames : null) || this._defaults.monthNames, o = function (t) {
                var i = e.length > c + 1 && e.charAt(c + 1) == t;
                return i && c++, i
            }, h = function (e, t, i) {
                var s = "" + t;
                if (o(e))for (; i > s.length;)s = "0" + s;
                return s
            }, l = function (e, t, i, s) {
                return o(e) ? s[t] : i[t]
            }, u = "", d = !1;
            if (t)for (var c = 0; e.length > c; c++)if (d)"'" != e.charAt(c) || o("'") ? u += e.charAt(c) : d = !1; else switch (e.charAt(c)) {
                case"d":
                    u += h("d", t.getDate(), 2);
                    break;
                case"D":
                    u += l("D", t.getDay(), s, n);
                    break;
                case"o":
                    u += h("o", Math.round((new Date(t.getFullYear(), t.getMonth(), t.getDate()).getTime() - new Date(t.getFullYear(), 0, 0).getTime()) / 864e5), 3);
                    break;
                case"m":
                    u += h("m", t.getMonth() + 1, 2);
                    break;
                case"M":
                    u += l("M", t.getMonth(), a, r);
                    break;
                case"y":
                    u += o("y") ? t.getFullYear() : (10 > t.getYear() % 100 ? "0" : "") + t.getYear() % 100;
                    break;
                case"@":
                    u += t.getTime();
                    break;
                case"!":
                    u += 1e4 * t.getTime() + this._ticksTo1970;
                    break;
                case"'":
                    o("'") ? u += "'" : d = !0;
                    break;
                default:
                    u += e.charAt(c)
            }
            return u
        },
        _possibleChars: function (e) {
            for (var t = "", i = !1, s = function (t) {
                var i = e.length > n + 1 && e.charAt(n + 1) == t;
                return i && n++, i
            }, n = 0; e.length > n; n++)if (i)"'" != e.charAt(n) || s("'") ? t += e.charAt(n) : i = !1; else switch (e.charAt(n)) {
                case"d":
                case"m":
                case"y":
                case"@":
                    t += "0123456789";
                    break;
                case"D":
                case"M":
                    return null;
                case"'":
                    s("'") ? t += "'" : i = !0;
                    break;
                default:
                    t += e.charAt(n)
            }
            return t
        },
        _get: function (e, t) {
            return e.settings[t] !== undefined ? e.settings[t] : this._defaults[t]
        },
        _setDateFromField: function (e, t) {
            if (e.input.val() != e.lastVal) {
                var i, s, n = this._get(e, "dateFormat"), a = e.lastVal = e.input ? e.input.val() : null;
                i = s = this._getDefaultDate(e);
                var r = this._getFormatConfig(e);
                try {
                    i = this.parseDate(n, a, r) || s
                } catch (o) {
                    this.log(o), a = t ? "" : a
                }
                e.selectedDay = i.getDate(), e.drawMonth = e.selectedMonth = i.getMonth(), e.drawYear = e.selectedYear = i.getFullYear(), e.currentDay = a ? i.getDate() : 0, e.currentMonth = a ? i.getMonth() : 0, e.currentYear = a ? i.getFullYear() : 0, this._adjustInstDate(e)
            }
        },
        _getDefaultDate: function (e) {
            return this._restrictMinMax(e, this._determineDate(e, this._get(e, "defaultDate"), new Date))
        },
        _determineDate: function (e, t, i) {
            var s = function (e) {
                var t = new Date;
                return t.setDate(t.getDate() + e), t
            }, n = function (t) {
                try {
                    return $.datepicker.parseDate($.datepicker._get(e, "dateFormat"), t, $.datepicker._getFormatConfig(e))
                } catch (i) {
                }
                for (var s = (t.toLowerCase().match(/^c/) ? $.datepicker._getDate(e) : null) || new Date, n = s.getFullYear(), a = s.getMonth(), r = s.getDate(), o = /([+-]?[0-9]+)\s*(d|D|w|W|m|M|y|Y)?/g, h = o.exec(t); h;) {
                    switch (h[2] || "d") {
                        case"d":
                        case"D":
                            r += parseInt(h[1], 10);
                            break;
                        case"w":
                        case"W":
                            r += 7 * parseInt(h[1], 10);
                            break;
                        case"m":
                        case"M":
                            a += parseInt(h[1], 10), r = Math.min(r, $.datepicker._getDaysInMonth(n, a));
                            break;
                        case"y":
                        case"Y":
                            n += parseInt(h[1], 10), r = Math.min(r, $.datepicker._getDaysInMonth(n, a))
                    }
                    h = o.exec(t)
                }
                return new Date(n, a, r)
            }, a = null == t || "" === t ? i : "string" == typeof t ? n(t) : "number" == typeof t ? isNaN(t) ? i : s(t) : new Date(t.getTime());
            return a = a && "Invalid Date" == "" + a ? i : a, a && (a.setHours(0), a.setMinutes(0), a.setSeconds(0), a.setMilliseconds(0)), this._daylightSavingAdjust(a)
        },
        _daylightSavingAdjust: function (e) {
            return e ? (e.setHours(e.getHours() > 12 ? e.getHours() + 2 : 0), e) : null
        },
        _setDate: function (e, t, i) {
            var s = !t, n = e.selectedMonth, a = e.selectedYear, r = this._restrictMinMax(e, this._determineDate(e, t, new Date));
            e.selectedDay = e.currentDay = r.getDate(), e.drawMonth = e.selectedMonth = e.currentMonth = r.getMonth(), e.drawYear = e.selectedYear = e.currentYear = r.getFullYear(), n == e.selectedMonth && a == e.selectedYear || i || this._notifyChange(e), this._adjustInstDate(e), e.input && e.input.val(s ? "" : this._formatDate(e))
        },
        _getDate: function (e) {
            var t = !e.currentYear || e.input && "" == e.input.val() ? null : this._daylightSavingAdjust(new Date(e.currentYear, e.currentMonth, e.currentDay));
            return t
        },
        _attachHandlers: function (e) {
            var t = this._get(e, "stepMonths"), i = "#" + e.id.replace(/\\\\/g, "\\");
            e.dpDiv.find("[data-handler]").map(function () {
                var e = {
                    prev: function () {
                        window["DP_jQuery_" + dpuuid].datepicker._adjustDate(i, -t, "M")
                    }, next: function () {
                        window["DP_jQuery_" + dpuuid].datepicker._adjustDate(i, +t, "M")
                    }, hide: function () {
                        window["DP_jQuery_" + dpuuid].datepicker._hideDatepicker()
                    }, today: function () {
                        window["DP_jQuery_" + dpuuid].datepicker._gotoToday(i)
                    }, selectDay: function () {
                        return window["DP_jQuery_" + dpuuid].datepicker._selectDay(i, +this.getAttribute("data-month"), +this.getAttribute("data-year"), this), !1
                    }, selectMonth: function () {
                        return window["DP_jQuery_" + dpuuid].datepicker._selectMonthYear(i, this, "M"), !1
                    }, selectYear: function () {
                        return window["DP_jQuery_" + dpuuid].datepicker._selectMonthYear(i, this, "Y"), !1
                    }
                };
                $(this).bind(this.getAttribute("data-event"), e[this.getAttribute("data-handler")])
            })
        },
        _generateHTML: function (e) {
            var t = new Date;
            t = this._daylightSavingAdjust(new Date(t.getFullYear(), t.getMonth(), t.getDate()));
            var i = this._get(e, "isRTL"), s = this._get(e, "showButtonPanel"), n = this._get(e, "hideIfNoPrevNext"), a = this._get(e, "navigationAsDateFormat"), r = this._getNumberOfMonths(e), o = this._get(e, "showCurrentAtPos"), h = this._get(e, "stepMonths"), l = 1 != r[0] || 1 != r[1], u = this._daylightSavingAdjust(e.currentDay ? new Date(e.currentYear, e.currentMonth, e.currentDay) : new Date(9999, 9, 9)), d = this._getMinMaxDate(e, "min"), c = this._getMinMaxDate(e, "max"), p = e.drawMonth - o, f = e.drawYear;
            if (0 > p && (p += 12, f--), c) {
                var m = this._daylightSavingAdjust(new Date(c.getFullYear(), c.getMonth() - r[0] * r[1] + 1, c.getDate()));
                for (m = d && d > m ? d : m; this._daylightSavingAdjust(new Date(f, p, 1)) > m;)p--, 0 > p && (p = 11, f--)
            }
            e.drawMonth = p, e.drawYear = f;
            var g = this._get(e, "prevText");
            g = a ? this.formatDate(g, this._daylightSavingAdjust(new Date(f, p - h, 1)), this._getFormatConfig(e)) : g;
            var v = this._canAdjustMonth(e, -1, f, p) ? '<a class="ui-datepicker-prev ui-corner-all" data-handler="prev" data-event="click" title="' + g + '"><span class="ui-icon ui-icon-circle-triangle-' + (i ? "e" : "w") + '">' + g + "</span></a>" : n ? "" : '<a class="ui-datepicker-prev ui-corner-all ui-state-disabled" title="' + g + '"><span class="ui-icon ui-icon-circle-triangle-' + (i ? "e" : "w") + '">' + g + "</span></a>", y = this._get(e, "nextText");
            y = a ? this.formatDate(y, this._daylightSavingAdjust(new Date(f, p + h, 1)), this._getFormatConfig(e)) : y;
            var b = this._canAdjustMonth(e, 1, f, p) ? '<a class="ui-datepicker-next ui-corner-all" data-handler="next" data-event="click" title="' + y + '"><span class="ui-icon ui-icon-circle-triangle-' + (i ? "w" : "e") + '">' + y + "</span></a>" : n ? "" : '<a class="ui-datepicker-next ui-corner-all ui-state-disabled" title="' + y + '"><span class="ui-icon ui-icon-circle-triangle-' + (i ? "w" : "e") + '">' + y + "</span></a>", _ = this._get(e, "currentText"), x = this._get(e, "gotoCurrent") && e.currentDay ? u : t;
            _ = a ? this.formatDate(_, x, this._getFormatConfig(e)) : _;
            var w = e.inline ? "" : '<button type="button" class="ui-datepicker-close ui-state-default ui-priority-primary ui-corner-all" data-handler="hide" data-event="click">' + this._get(e, "closeText") + "</button>", k = s ? '<div class="ui-datepicker-buttonpane ui-widget-content">' + (i ? w : "") + (this._isInRange(e, x) ? '<button type="button" class="ui-datepicker-current ui-state-default ui-priority-secondary ui-corner-all" data-handler="today" data-event="click">' + _ + "</button>" : "") + (i ? "" : w) + "</div>" : "", D = parseInt(this._get(e, "firstDay"), 10);
            D = isNaN(D) ? 0 : D;
            var T = this._get(e, "showWeek"), S = this._get(e, "dayNames");
            this._get(e, "dayNamesShort");
            var M = this._get(e, "dayNamesMin"), N = this._get(e, "monthNames"), C = this._get(e, "monthNamesShort"), A = this._get(e, "beforeShowDay"), P = this._get(e, "showOtherMonths"), I = this._get(e, "selectOtherMonths");
            this._get(e, "calculateWeek") || this.iso8601Week;
            for (var H = this._getDefaultDate(e), F = "", z = 0; r[0] > z; z++) {
                var E = "";
                this.maxRows = 4;
                for (var O = 0; r[1] > O; O++) {
                    var j = this._daylightSavingAdjust(new Date(f, p, e.selectedDay)), W = " ui-corner-all", L = "";
                    if (l) {
                        if (L += '<div class="ui-datepicker-group', r[1] > 1)switch (O) {
                            case 0:
                                L += " ui-datepicker-group-first", W = " ui-corner-" + (i ? "right" : "left");
                                break;
                            case r[1] - 1:
                                L += " ui-datepicker-group-last", W = " ui-corner-" + (i ? "left" : "right");
                                break;
                            default:
                                L += " ui-datepicker-group-middle", W = ""
                        }
                        L += '">'
                    }
                    L += '<div class="ui-datepicker-header ui-widget-header ui-helper-clearfix' + W + '">' + (/all|left/.test(W) && 0 == z ? i ? b : v : "") + (/all|right/.test(W) && 0 == z ? i ? v : b : "") + this._generateMonthYearHeader(e, p, f, d, c, z > 0 || O > 0, N, C) + '</div><table class="ui-datepicker-calendar"><thead>' + "<tr>";
                    for (var R = T ? '<th class="ui-datepicker-week-col">' + this._get(e, "weekHeader") + "</th>" : "", Y = 0; 7 > Y; Y++) {
                        var J = (Y + D) % 7;
                        R += "<th" + ((Y + D + 6) % 7 >= 5 ? ' class="ui-datepicker-week-end"' : "") + ">" + '<span title="' + S[J] + '">' + M[J] + "</span></th>"
                    }
                    L += R + "</tr></thead><tbody>";
                    var B = this._getDaysInMonth(f, p);
                    f == e.selectedYear && p == e.selectedMonth && (e.selectedDay = Math.min(e.selectedDay, B));
                    var K = (this._getFirstDayOfMonth(f, p) - D + 7) % 7, U = Math.ceil((K + B) / 7), V = l ? this.maxRows > U ? this.maxRows : U : U;
                    this.maxRows = V;
                    for (var q = this._daylightSavingAdjust(new Date(f, p, 1 - K)), G = 0; V > G; G++) {
                        L += "<tr>";
                        for (var Q = T ? '<td class="ui-datepicker-week-col">' + this._get(e, "calculateWeek")(q) + "</td>" : "", Y = 0; 7 > Y; Y++) {
                            var X = A ? A.apply(e.input ? e.input[0] : null, [q]) : [!0, ""], Z = q.getMonth() != p, et = Z && !I || !X[0] || d && d > q || c && q > c;
                            Q += '<td class="' + ((Y + D + 6) % 7 >= 5 ? " ui-datepicker-week-end" : "") + (Z ? " ui-datepicker-other-month" : "") + (q.getTime() == j.getTime() && p == e.selectedMonth && e._keyEvent || H.getTime() == q.getTime() && H.getTime() == j.getTime() ? " " + this._dayOverClass : "") + (et ? " " + this._unselectableClass + " ui-state-disabled" : "") + (Z && !P ? "" : " " + X[1] + (q.getTime() == u.getTime() ? " " + this._currentClass : "") + (q.getTime() == t.getTime() ? " ui-datepicker-today" : "")) + '"' + (Z && !P || !X[2] ? "" : ' title="' + X[2] + '"') + (et ? "" : ' data-handler="selectDay" data-event="click" data-month="' + q.getMonth() + '" data-year="' + q.getFullYear() + '"') + ">" + (Z && !P ? "&#xa0;" : et ? '<span class="ui-state-default">' + q.getDate() + "</span>" : '<a class="ui-state-default' + (q.getTime() == t.getTime() ? " ui-state-highlight" : "") + (q.getTime() == u.getTime() ? " ui-state-active" : "") + (Z ? " ui-priority-secondary" : "") + '" href="#">' + q.getDate() + "</a>") + "</td>", q.setDate(q.getDate() + 1), q = this._daylightSavingAdjust(q)
                        }
                        L += Q + "</tr>"
                    }
                    p++, p > 11 && (p = 0, f++), L += "</tbody></table>" + (l ? "</div>" + (r[0] > 0 && O == r[1] - 1 ? '<div class="ui-datepicker-row-break"></div>' : "") : ""), E += L
                }
                F += E
            }
            return F += k + ($.ui.ie6 && !e.inline ? '<iframe src="javascript:false;" class="ui-datepicker-cover" frameborder="0"></iframe>' : ""), e._keyEvent = !1, F
        },
        _generateMonthYearHeader: function (e, t, i, s, n, a, r, o) {
            var h = this._get(e, "changeMonth"), l = this._get(e, "changeYear"), u = this._get(e, "showMonthAfterYear"), d = '<div class="ui-datepicker-title">', c = "";
            if (a || !h)c += '<span class="ui-datepicker-month">' + r[t] + "</span>"; else {
                var p = s && s.getFullYear() == i, f = n && n.getFullYear() == i;
                c += '<select class="ui-datepicker-month" data-handler="selectMonth" data-event="change">';
                for (var m = 0; 12 > m; m++)(!p || m >= s.getMonth()) && (!f || n.getMonth() >= m) && (c += '<option value="' + m + '"' + (m == t ? ' selected="selected"' : "") + ">" + o[m] + "</option>");
                c += "</select>"
            }
            if (u || (d += c + (!a && h && l ? "" : "&#xa0;")), !e.yearshtml)if (e.yearshtml = "", a || !l)d += '<span class="ui-datepicker-year">' + i + "</span>"; else {
                var g = this._get(e, "yearRange").split(":"), v = (new Date).getFullYear(), y = function (e) {
                    var t = e.match(/c[+-].*/) ? i + parseInt(e.substring(1), 10) : e.match(/[+-].*/) ? v + parseInt(e, 10) : parseInt(e, 10);
                    return isNaN(t) ? v : t
                }, b = y(g[0]), _ = Math.max(b, y(g[1] || ""));
                for (b = s ? Math.max(b, s.getFullYear()) : b, _ = n ? Math.min(_, n.getFullYear()) : _, e.yearshtml += '<select class="ui-datepicker-year" data-handler="selectYear" data-event="change">'; _ >= b; b++)e.yearshtml += '<option value="' + b + '"' + (b == i ? ' selected="selected"' : "") + ">" + b + "</option>";
                e.yearshtml += "</select>", d += e.yearshtml, e.yearshtml = null
            }
            return d += this._get(e, "yearSuffix"), u && (d += (!a && h && l ? "" : "&#xa0;") + c), d += "</div>"
        },
        _adjustInstDate: function (e, t, i) {
            var s = e.drawYear + ("Y" == i ? t : 0), n = e.drawMonth + ("M" == i ? t : 0), a = Math.min(e.selectedDay, this._getDaysInMonth(s, n)) + ("D" == i ? t : 0), r = this._restrictMinMax(e, this._daylightSavingAdjust(new Date(s, n, a)));
            e.selectedDay = r.getDate(), e.drawMonth = e.selectedMonth = r.getMonth(), e.drawYear = e.selectedYear = r.getFullYear(), ("M" == i || "Y" == i) && this._notifyChange(e)
        },
        _restrictMinMax: function (e, t) {
            var i = this._getMinMaxDate(e, "min"), s = this._getMinMaxDate(e, "max"), n = i && i > t ? i : t;
            return n = s && n > s ? s : n
        },
        _notifyChange: function (e) {
            var t = this._get(e, "onChangeMonthYear");
            t && t.apply(e.input ? e.input[0] : null, [e.selectedYear, e.selectedMonth + 1, e])
        },
        _getNumberOfMonths: function (e) {
            var t = this._get(e, "numberOfMonths");
            return null == t ? [1, 1] : "number" == typeof t ? [1, t] : t
        },
        _getMinMaxDate: function (e, t) {
            return this._determineDate(e, this._get(e, t + "Date"), null)
        },
        _getDaysInMonth: function (e, t) {
            return 32 - this._daylightSavingAdjust(new Date(e, t, 32)).getDate()
        },
        _getFirstDayOfMonth: function (e, t) {
            return new Date(e, t, 1).getDay()
        },
        _canAdjustMonth: function (e, t, i, s) {
            var n = this._getNumberOfMonths(e), a = this._daylightSavingAdjust(new Date(i, s + (0 > t ? t : n[0] * n[1]), 1));
            return 0 > t && a.setDate(this._getDaysInMonth(a.getFullYear(), a.getMonth())), this._isInRange(e, a)
        },
        _isInRange: function (e, t) {
            var i = this._getMinMaxDate(e, "min"), s = this._getMinMaxDate(e, "max");
            return (!i || t.getTime() >= i.getTime()) && (!s || t.getTime() <= s.getTime())
        },
        _getFormatConfig: function (e) {
            var t = this._get(e, "shortYearCutoff");
            return t = "string" != typeof t ? t : (new Date).getFullYear() % 100 + parseInt(t, 10), {
                shortYearCutoff: t,
                dayNamesShort: this._get(e, "dayNamesShort"),
                dayNames: this._get(e, "dayNames"),
                monthNamesShort: this._get(e, "monthNamesShort"),
                monthNames: this._get(e, "monthNames")
            }
        },
        _formatDate: function (e, t, i, s) {
            t || (e.currentDay = e.selectedDay, e.currentMonth = e.selectedMonth, e.currentYear = e.selectedYear);
            var n = t ? "object" == typeof t ? t : this._daylightSavingAdjust(new Date(s, i, t)) : this._daylightSavingAdjust(new Date(e.currentYear, e.currentMonth, e.currentDay));
            return this.formatDate(this._get(e, "dateFormat"), n, this._getFormatConfig(e))
        }
    }), $.fn.datepicker = function (e) {
        if (!this.length)return this;
        $.datepicker.initialized || ($(document).mousedown($.datepicker._checkExternalClick).find(document.body).append($.datepicker.dpDiv), $.datepicker.initialized = !0);
        var t = Array.prototype.slice.call(arguments, 1);
        return "string" != typeof e || "isDisabled" != e && "getDate" != e && "widget" != e ? "option" == e && 2 == arguments.length && "string" == typeof arguments[1] ? $.datepicker["_" + e + "Datepicker"].apply($.datepicker, [this[0]].concat(t)) : this.each(function () {
            "string" == typeof e ? $.datepicker["_" + e + "Datepicker"].apply($.datepicker, [this].concat(t)) : $.datepicker._attachDatepicker(this, e)
        }) : $.datepicker["_" + e + "Datepicker"].apply($.datepicker, [this[0]].concat(t))
    }, $.datepicker = new Datepicker, $.datepicker.initialized = !1, $.datepicker.uuid = (new Date).getTime(), $.datepicker.version = "1.9.2", window["DP_jQuery_" + dpuuid] = $
})(jQuery);
(function (e, t) {
    var i = "ui-dialog ui-widget ui-widget-content ui-corner-all ", s = {
        buttons: !0,
        height: !0,
        maxHeight: !0,
        maxWidth: !0,
        minHeight: !0,
        minWidth: !0,
        width: !0
    }, n = {maxHeight: !0, maxWidth: !0, minHeight: !0, minWidth: !0};
    e.widget("ui.dialog", {
        version: "1.9.2",
        options: {
            autoOpen: !0,
            buttons: {},
            closeOnEscape: !0,
            closeText: "close",
            dialogClass: "",
            draggable: !0,
            hide: null,
            height: "auto",
            maxHeight: !1,
            maxWidth: !1,
            minHeight: 150,
            minWidth: 150,
            modal: !1,
            position: {
                my: "center", at: "center", of: window, collision: "fit", using: function (t) {
                    var i = e(this).css(t).offset().top;
                    0 > i && e(this).css("top", t.top - i)
                }
            },
            resizable: !0,
            show: null,
            stack: !0,
            title: "",
            width: 300,
            zIndex: 1e3
        },
        _create: function () {
            this.originalTitle = this.element.attr("title"), "string" != typeof this.originalTitle && (this.originalTitle = ""), this.oldPosition = {
                parent: this.element.parent(),
                index: this.element.parent().children().index(this.element)
            }, this.options.title = this.options.title || this.originalTitle;
            var s, n, a, r, o, h = this, l = this.options, u = l.title || "&#160;";
            s = (this.uiDialog = e("<div>")).addClass(i + l.dialogClass).css({
                display: "none",
                outline: 0,
                zIndex: l.zIndex
            }).attr("tabIndex", -1).keydown(function (t) {
                l.closeOnEscape && !t.isDefaultPrevented() && t.keyCode && t.keyCode === e.ui.keyCode.ESCAPE && (h.close(t), t.preventDefault())
            }).mousedown(function (e) {
                h.moveToTop(!1, e)
            }).appendTo("body"), this.element.show().removeAttr("title").addClass("ui-dialog-content ui-widget-content").appendTo(s), n = (this.uiDialogTitlebar = e("<div>")).addClass("ui-dialog-titlebar  ui-widget-header  ui-corner-all  ui-helper-clearfix").bind("mousedown", function () {
                s.focus()
            }).prependTo(s), a = e("<a href='#'></a>").addClass("ui-dialog-titlebar-close  ui-corner-all").attr("role", "button").click(function (e) {
                e.preventDefault(), h.close(e)
            }).appendTo(n), (this.uiDialogTitlebarCloseText = e("<span>")).addClass("ui-icon ui-icon-closethick").text(l.closeText).appendTo(a), r = e("<span>").uniqueId().addClass("ui-dialog-title").html(u).prependTo(n), o = (this.uiDialogButtonPane = e("<div>")).addClass("ui-dialog-buttonpane ui-widget-content ui-helper-clearfix"), (this.uiButtonSet = e("<div>")).addClass("ui-dialog-buttonset").appendTo(o), s.attr({
                role: "dialog",
                "aria-labelledby": r.attr("id")
            }), n.find("*").add(n).disableSelection(), this._hoverable(a), this._focusable(a), l.draggable && e.fn.draggable && this._makeDraggable(), l.resizable && e.fn.resizable && this._makeResizable(), this._createButtons(l.buttons), this._isOpen = !1, e.fn.bgiframe && s.bgiframe(), this._on(s, {
                keydown: function (i) {
                    if (l.modal && i.keyCode === e.ui.keyCode.TAB) {
                        var n = e(":tabbable", s), a = n.filter(":first"), r = n.filter(":last");
                        return i.target !== r[0] || i.shiftKey ? i.target === a[0] && i.shiftKey ? (r.focus(1), !1) : t : (a.focus(1), !1)
                    }
                }
            })
        },
        _init: function () {
            this.options.autoOpen && this.open()
        },
        _destroy: function () {
            var e, t = this.oldPosition;
            this.overlay && this.overlay.destroy(), this.uiDialog.hide(), this.element.removeClass("ui-dialog-content ui-widget-content").hide().appendTo("body"), this.uiDialog.remove(), this.originalTitle && this.element.attr("title", this.originalTitle), e = t.parent.children().eq(t.index), e.length && e[0] !== this.element[0] ? e.before(this.element) : t.parent.append(this.element)
        },
        widget: function () {
            return this.uiDialog
        },
        close: function (t) {
            var i, s, n = this;
            if (this._isOpen && !1 !== this._trigger("beforeClose", t))return this._isOpen = !1, this.overlay && this.overlay.destroy(), this.options.hide ? this._hide(this.uiDialog, this.options.hide, function () {
                n._trigger("close", t)
            }) : (this.uiDialog.hide(), this._trigger("close", t)), e.ui.dialog.overlay.resize(), this.options.modal && (i = 0, e(".ui-dialog").each(function () {
                this !== n.uiDialog[0] && (s = e(this).css("z-index"), isNaN(s) || (i = Math.max(i, s)))
            }), e.ui.dialog.maxZ = i), this
        },
        isOpen: function () {
            return this._isOpen
        },
        moveToTop: function (t, i) {
            var s, n = this.options;
            return n.modal && !t || !n.stack && !n.modal ? this._trigger("focus", i) : (n.zIndex > e.ui.dialog.maxZ && (e.ui.dialog.maxZ = n.zIndex), this.overlay && (e.ui.dialog.maxZ += 1, e.ui.dialog.overlay.maxZ = e.ui.dialog.maxZ, this.overlay.$el.css("z-index", e.ui.dialog.overlay.maxZ)), s = {
                scrollTop: this.element.scrollTop(),
                scrollLeft: this.element.scrollLeft()
            }, e.ui.dialog.maxZ += 1, this.uiDialog.css("z-index", e.ui.dialog.maxZ), this.element.attr(s), this._trigger("focus", i), this)
        },
        open: function () {
            if (!this._isOpen) {
                var t, i = this.options, s = this.uiDialog;
                return this._size(), this._position(i.position), s.show(i.show), this.overlay = i.modal ? new e.ui.dialog.overlay(this) : null, this.moveToTop(!0), t = this.element.find(":tabbable"), t.length || (t = this.uiDialogButtonPane.find(":tabbable"), t.length || (t = s)), t.eq(0).focus(), this._isOpen = !0, this._trigger("open"), this
            }
        },
        _createButtons: function (t) {
            var i = this, s = !1;
            this.uiDialogButtonPane.remove(), this.uiButtonSet.empty(), "object" == typeof t && null !== t && e.each(t, function () {
                return !(s = !0)
            }), s ? (e.each(t, function (t, s) {
                var n, a;
                s = e.isFunction(s) ? {
                    click: s,
                    text: t
                } : s, s = e.extend({type: "button"}, s), a = s.click, s.click = function () {
                    a.apply(i.element[0], arguments)
                }, n = e("<button></button>", s).appendTo(i.uiButtonSet), e.fn.button && n.button()
            }), this.uiDialog.addClass("ui-dialog-buttons"), this.uiDialogButtonPane.appendTo(this.uiDialog)) : this.uiDialog.removeClass("ui-dialog-buttons")
        },
        _makeDraggable: function () {
            function t(e) {
                return {position: e.position, offset: e.offset}
            }

            var i = this, s = this.options;
            this.uiDialog.draggable({
                cancel: ".ui-dialog-content, .ui-dialog-titlebar-close",
                handle: ".ui-dialog-titlebar",
                containment: "document",
                start: function (s, n) {
                    e(this).addClass("ui-dialog-dragging"), i._trigger("dragStart", s, t(n))
                },
                drag: function (e, s) {
                    i._trigger("drag", e, t(s))
                },
                stop: function (n, a) {
                    s.position = [a.position.left - i.document.scrollLeft(), a.position.top - i.document.scrollTop()], e(this).removeClass("ui-dialog-dragging"), i._trigger("dragStop", n, t(a)), e.ui.dialog.overlay.resize()
                }
            })
        },
        _makeResizable: function (i) {
            function s(e) {
                return {
                    originalPosition: e.originalPosition,
                    originalSize: e.originalSize,
                    position: e.position,
                    size: e.size
                }
            }

            i = i === t ? this.options.resizable : i;
            var n = this, a = this.options, r = this.uiDialog.css("position"), o = "string" == typeof i ? i : "n,e,s,w,se,sw,ne,nw";
            this.uiDialog.resizable({
                cancel: ".ui-dialog-content",
                containment: "document",
                alsoResize: this.element,
                maxWidth: a.maxWidth,
                maxHeight: a.maxHeight,
                minWidth: a.minWidth,
                minHeight: this._minHeight(),
                handles: o,
                start: function (t, i) {
                    e(this).addClass("ui-dialog-resizing"), n._trigger("resizeStart", t, s(i))
                },
                resize: function (e, t) {
                    n._trigger("resize", e, s(t))
                },
                stop: function (t, i) {
                    e(this).removeClass("ui-dialog-resizing"), a.height = e(this).height(), a.width = e(this).width(), n._trigger("resizeStop", t, s(i)), e.ui.dialog.overlay.resize()
                }
            }).css("position", r).find(".ui-resizable-se").addClass("ui-icon ui-icon-grip-diagonal-se")
        },
        _minHeight: function () {
            var e = this.options;
            return "auto" === e.height ? e.minHeight : Math.min(e.minHeight, e.height)
        },
        _position: function (t) {
            var i, s = [], n = [0, 0];
            t ? (("string" == typeof t || "object" == typeof t && "0"in t) && (s = t.split ? t.split(" ") : [t[0], t[1]], 1 === s.length && (s[1] = s[0]), e.each(["left", "top"], function (e, t) {
                +s[e] === s[e] && (n[e] = s[e], s[e] = t)
            }), t = {
                my: s[0] + (0 > n[0] ? n[0] : "+" + n[0]) + " " + s[1] + (0 > n[1] ? n[1] : "+" + n[1]),
                at: s.join(" ")
            }), t = e.extend({}, e.ui.dialog.prototype.options.position, t)) : t = e.ui.dialog.prototype.options.position, i = this.uiDialog.is(":visible"), i || this.uiDialog.show(), this.uiDialog.position(t), i || this.uiDialog.hide()
        },
        _setOptions: function (t) {
            var i = this, a = {}, r = !1;
            e.each(t, function (e, t) {
                i._setOption(e, t), e in s && (r = !0), e in n && (a[e] = t)
            }), r && this._size(), this.uiDialog.is(":data(resizable)") && this.uiDialog.resizable("option", a)
        },
        _setOption: function (t, s) {
            var n, a, r = this.uiDialog;
            switch (t) {
                case"buttons":
                    this._createButtons(s);
                    break;
                case"closeText":
                    this.uiDialogTitlebarCloseText.text("" + s);
                    break;
                case"dialogClass":
                    r.removeClass(this.options.dialogClass).addClass(i + s);
                    break;
                case"disabled":
                    s ? r.addClass("ui-dialog-disabled") : r.removeClass("ui-dialog-disabled");
                    break;
                case"draggable":
                    n = r.is(":data(draggable)"), n && !s && r.draggable("destroy"), !n && s && this._makeDraggable();
                    break;
                case"position":
                    this._position(s);
                    break;
                case"resizable":
                    a = r.is(":data(resizable)"), a && !s && r.resizable("destroy"), a && "string" == typeof s && r.resizable("option", "handles", s), a || s === !1 || this._makeResizable(s);
                    break;
                case"title":
                    e(".ui-dialog-title", this.uiDialogTitlebar).html("" + (s || "&#160;"))
            }
            this._super(t, s)
        },
        _size: function () {
            var t, i, s, n = this.options, a = this.uiDialog.is(":visible");
            this.element.show().css({
                width: "auto",
                minHeight: 0,
                height: 0
            }), n.minWidth > n.width && (n.width = n.minWidth), t = this.uiDialog.css({
                height: "auto",
                width: n.width
            }).outerHeight(), i = Math.max(0, n.minHeight - t), "auto" === n.height ? e.support.minHeight ? this.element.css({
                minHeight: i,
                height: "auto"
            }) : (this.uiDialog.show(), s = this.element.css("height", "auto").height(), a || this.uiDialog.hide(), this.element.height(Math.max(s, i))) : this.element.height(Math.max(n.height - t, 0)), this.uiDialog.is(":data(resizable)") && this.uiDialog.resizable("option", "minHeight", this._minHeight())
        }
    }), e.extend(e.ui.dialog, {
        uuid: 0, maxZ: 0, getTitleId: function (e) {
            var t = e.attr("id");
            return t || (this.uuid += 1, t = this.uuid), "ui-dialog-title-" + t
        }, overlay: function (t) {
            this.$el = e.ui.dialog.overlay.create(t)
        }
    }), e.extend(e.ui.dialog.overlay, {
        instances: [],
        oldInstances: [],
        maxZ: 0,
        events: e.map("focus,mousedown,mouseup,keydown,keypress,click".split(","), function (e) {
            return e + ".dialog-overlay"
        }).join(" "),
        create: function (i) {
            0 === this.instances.length && (setTimeout(function () {
                e.ui.dialog.overlay.instances.length && e(document).bind(e.ui.dialog.overlay.events, function (i) {
                    return e(i.target).zIndex() < e.ui.dialog.overlay.maxZ ? !1 : t
                })
            }, 1), e(window).bind("resize.dialog-overlay", e.ui.dialog.overlay.resize));
            var s = this.oldInstances.pop() || e("<div>").addClass("ui-widget-overlay");
            return e(document).bind("keydown.dialog-overlay", function (t) {
                var n = e.ui.dialog.overlay.instances;
                0 !== n.length && n[n.length - 1] === s && i.options.closeOnEscape && !t.isDefaultPrevented() && t.keyCode && t.keyCode === e.ui.keyCode.ESCAPE && (i.close(t), t.preventDefault())
            }), s.appendTo(document.body).css({
                width: this.width(),
                height: this.height()
            }), e.fn.bgiframe && s.bgiframe(), this.instances.push(s), s
        },
        destroy: function (t) {
            var i = e.inArray(t, this.instances), s = 0;
            -1 !== i && this.oldInstances.push(this.instances.splice(i, 1)[0]), 0 === this.instances.length && e([document, window]).unbind(".dialog-overlay"), t.height(0).width(0).remove(), e.each(this.instances, function () {
                s = Math.max(s, this.css("z-index"))
            }), this.maxZ = s
        },
        height: function () {
            var t, i;
            return e.ui.ie ? (t = Math.max(document.documentElement.scrollHeight, document.body.scrollHeight), i = Math.max(document.documentElement.offsetHeight, document.body.offsetHeight), i > t ? e(window).height() + "px" : t + "px") : e(document).height() + "px"
        },
        width: function () {
            var t, i;
            return e.ui.ie ? (t = Math.max(document.documentElement.scrollWidth, document.body.scrollWidth), i = Math.max(document.documentElement.offsetWidth, document.body.offsetWidth), i > t ? e(window).width() + "px" : t + "px") : e(document).width() + "px"
        },
        resize: function () {
            var t = e([]);
            e.each(e.ui.dialog.overlay.instances, function () {
                t = t.add(this)
            }), t.css({width: 0, height: 0}).css({
                width: e.ui.dialog.overlay.width(),
                height: e.ui.dialog.overlay.height()
            })
        }
    }), e.extend(e.ui.dialog.overlay.prototype, {
        destroy: function () {
            e.ui.dialog.overlay.destroy(this.$el)
        }
    })
})(jQuery);
(function (e) {
    var t = !1;
    e.widget("ui.menu", {
        version: "1.9.2",
        defaultElement: "<ul>",
        delay: 300,
        options: {
            icons: {submenu: "ui-icon-carat-1-e"},
            menus: "ul",
            position: {my: "left top", at: "right top"},
            role: "menu",
            blur: null,
            focus: null,
            select: null
        },
        _create: function () {
            this.activeMenu = this.element, this.element.uniqueId().addClass("ui-menus ui-widget ui-widget-content ui-corner-all").toggleClass("ui-menus-icons", !!this.element.find(".ui-icon").length).attr({
                role: this.options.role,
                tabIndex: 0
            }).bind("click" + this.eventNamespace, e.proxy(function (e) {
                this.options.disabled && e.preventDefault()
            }, this)), this.options.disabled && this.element.addClass("ui-state-disabled").attr("aria-disabled", "true"), this._on({
                "mousedown .ui-menu-item > a": function (e) {
                    e.preventDefault()
                }, "click .ui-state-disabled > a": function (e) {
                    e.preventDefault()
                }, "click .ui-menu-item:has(a)": function (i) {
                    var s = e(i.target).closest(".ui-menus-item");
                    !t && s.not(".ui-state-disabled").length && (t = !0, this.select(i), s.has(".ui-menus").length ? this.expand(i) : this.element.is(":focus") || (this.element.trigger("focus", [!0]), this.active && 1 === this.active.parents(".ui-menus").length && clearTimeout(this.timer)))
                }, "mouseenter .ui-menu-item": function (t) {
                    var i = e(t.currentTarget);
                    i.siblings().children(".ui-state-active").removeClass("ui-state-active"), this.focus(t, i)
                }, mouseleave: "collapseAll", "mouseleave .ui-menu": "collapseAll", focus: function (e, t) {
                    var i = this.active || this.element.children(".ui-menus-item").eq(0);
                    t || this.focus(e, i)
                }, blur: function (t) {
                    this._delay(function () {
                        e.contains(this.element[0], this.document[0].activeElement) || this.collapseAll(t)
                    })
                }, keydown: "_keydown"
            }), this.refresh(), this._on(this.document, {
                click: function (i) {
                    e(i.target).closest(".ui-menus").length || this.collapseAll(i), t = !1
                }
            })
        },
        _destroy: function () {
            this.element.removeAttr("aria-activedescendant").find(".ui-menus").andSelf().removeClass("ui-menus ui-widget ui-widget-content ui-corner-all ui-menus-icons").removeAttr("role").removeAttr("tabIndex").removeAttr("aria-labelledby").removeAttr("aria-expanded").removeAttr("aria-hidden").removeAttr("aria-disabled").removeUniqueId().show(), this.element.find(".ui-menus-item").removeClass("ui-menus-item").removeAttr("role").removeAttr("aria-disabled").children("a").removeUniqueId().removeClass("ui-corner-all ui-state-hover").removeAttr("tabIndex").removeAttr("role").removeAttr("aria-haspopup").children().each(function () {
                var t = e(this);
                t.data("ui-menus-submenu-carat") && t.remove()
            }), this.element.find(".ui-menus-divider").removeClass("ui-menus-divider ui-widget-content")
        },
        _keydown: function (t) {
            function i(e) {
                return e.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, "\\$&")
            }

            var s, n, a, o, r, h = !0;
            switch (t.keyCode) {
                case e.ui.keyCode.PAGE_UP:
                    this.previousPage(t);
                    break;
                case e.ui.keyCode.PAGE_DOWN:
                    this.nextPage(t);
                    break;
                case e.ui.keyCode.HOME:
                    this._move("first", "first", t);
                    break;
                case e.ui.keyCode.END:
                    this._move("last", "last", t);
                    break;
                case e.ui.keyCode.UP:
                    this.previous(t);
                    break;
                case e.ui.keyCode.DOWN:
                    this.next(t);
                    break;
                case e.ui.keyCode.LEFT:
                    this.collapse(t);
                    break;
                case e.ui.keyCode.RIGHT:
                    this.active && !this.active.is(".ui-state-disabled") && this.expand(t);
                    break;
                case e.ui.keyCode.ENTER:
                case e.ui.keyCode.SPACE:
                    this._activate(t);
                    break;
                case e.ui.keyCode.ESCAPE:
                    this.collapse(t);
                    break;
                default:
                    h = !1, n = this.previousFilter || "", a = String.fromCharCode(t.keyCode), o = !1, clearTimeout(this.filterTimer), a === n ? o = !0 : a = n + a, r = RegExp("^" + i(a), "i"), s = this.activeMenu.children(".ui-menus-item").filter(function () {
                        return r.test(e(this).children("a").text())
                    }), s = o && -1 !== s.index(this.active.next()) ? this.active.nextAll(".ui-menus-item") : s, s.length || (a = String.fromCharCode(t.keyCode), r = RegExp("^" + i(a), "i"), s = this.activeMenu.children(".ui-menus-item").filter(function () {
                        return r.test(e(this).children("a").text())
                    })), s.length ? (this.focus(t, s), s.length > 1 ? (this.previousFilter = a, this.filterTimer = this._delay(function () {
                        delete this.previousFilter
                    }, 1e3)) : delete this.previousFilter) : delete this.previousFilter
            }
            h && t.preventDefault()
        },
        _activate: function (e) {
            this.active.is(".ui-state-disabled") || (this.active.children("a[aria-haspopup='true']").length ? this.expand(e) : this.select(e))
        },
        refresh: function () {
            var t, i = this.options.icons.submenu, s = this.element.find(this.options.menus);
            s.filter(":not(.ui-menus)").addClass("ui-menus ui-widget ui-widget-content ui-corner-all").hide().attr({
                role: this.options.role,
                "aria-hidden": "true",
                "aria-expanded": "false"
            }).each(function () {
                var t = e(this), s = t.prev("a"), n = e("<span>").addClass("ui-menus-icon ui-icon " + i).data("ui-menus-submenu-carat", !0);
                s.attr("aria-haspopup", "true").prepend(n), t.attr("aria-labelledby", s.attr("id"))
            }), t = s.add(this.element), t.children(":not(.ui-menus-item):has(a)").addClass("ui-menus-item").attr("role", "presentation").children("a").uniqueId().addClass("ui-corner-all").attr({
                tabIndex: -1,
                role: this._itemRole()
            }), t.children(":not(.ui-menus-item)").each(function () {
                var t = e(this);
                /[^\-—–\s]/.test(t.text()) || t.addClass("ui-widget-content ui-menus-divider")
            }), t.children(".ui-state-disabled").attr("aria-disabled", "true"), this.active && !e.contains(this.element[0], this.active[0]) && this.blur()
        },
        _itemRole: function () {
            return {menu: "menuitem", listbox: "option"}[this.options.role]
        },
        focus: function (e, t) {
            var i, s;
            this.blur(e, e && "focus" === e.type), this._scrollIntoView(t), this.active = t.first(), s = this.active.children("a").addClass("ui-state-focus"), this.options.role && this.element.attr("aria-activedescendant", s.attr("id")), this.active.parent().closest(".ui-menus-item").children("a:first").addClass("ui-state-active"), e && "keydown" === e.type ? this._close() : this.timer = this._delay(function () {
                this._close()
            }, this.delay), i = t.children(".ui-menus"), i.length && /^mouse/.test(e.type) && this._startOpening(i), this.activeMenu = t.parent(), this._trigger("focus", e, {item: t})
        },
        _scrollIntoView: function (t) {
            var i, s, n, a, o, r;
            this._hasScroll() && (i = parseFloat(e.css(this.activeMenu[0], "borderTopWidth")) || 0, s = parseFloat(e.css(this.activeMenu[0], "paddingTop")) || 0, n = t.offset().top - this.activeMenu.offset().top - i - s, a = this.activeMenu.scrollTop(), o = this.activeMenu.height(), r = t.height(), 0 > n ? this.activeMenu.scrollTop(a + n) : n + r > o && this.activeMenu.scrollTop(a + n - o + r))
        },
        blur: function (e, t) {
            t || clearTimeout(this.timer), this.active && (this.active.children("a").removeClass("ui-state-focus"), this.active = null, this._trigger("blur", e, {item: this.active}))
        },
        _startOpening: function (e) {
            clearTimeout(this.timer), "true" === e.attr("aria-hidden") && (this.timer = this._delay(function () {
                this._close(), this._open(e)
            }, this.delay))
        },
        _open: function (t) {
            var i = e.extend({of: this.active}, this.options.position);
            clearTimeout(this.timer), this.element.find(".ui-menus").not(t.parents(".ui-menus")).hide().attr("aria-hidden", "true"), t.show().removeAttr("aria-hidden").attr("aria-expanded", "true").position(i)
        },
        collapseAll: function (t, i) {
            clearTimeout(this.timer), this.timer = this._delay(function () {
                var s = i ? this.element : e(t && t.target).closest(this.element.find(".ui-menus"));
                s.length || (s = this.element), this._close(s), this.blur(t), this.activeMenu = s
            }, this.delay)
        },
        _close: function (e) {
            e || (e = this.active ? this.active.parent() : this.element), e.find(".ui-menus").hide().attr("aria-hidden", "true").attr("aria-expanded", "false").end().find("a.ui-state-active").removeClass("ui-state-active")
        },
        collapse: function (e) {
            var t = this.active && this.active.parent().closest(".ui-menus-item", this.element);
            t && t.length && (this._close(), this.focus(e, t))
        },
        expand: function (e) {
            var t = this.active && this.active.children(".ui-menus ").children(".ui-menus-item").first();
            t && t.length && (this._open(t.parent()), this._delay(function () {
                this.focus(e, t)
            }))
        },
        next: function (e) {
            this._move("next", "first", e)
        },
        previous: function (e) {
            this._move("prev", "last", e)
        },
        isFirstItem: function () {
            return this.active && !this.active.prevAll(".ui-menus-item").length
        },
        isLastItem: function () {
            return this.active && !this.active.nextAll(".ui-menus-item").length
        },
        _move: function (e, t, i) {
            var s;
            this.active && (s = "first" === e || "last" === e ? this.active["first" === e ? "prevAll" : "nextAll"](".ui-menus-item").eq(-1) : this.active[e + "All"](".ui-menus-item").eq(0)), s && s.length && this.active || (s = this.activeMenu.children(".ui-menus-item")[t]()), this.focus(i, s)
        },
        nextPage: function (t) {
            var i, s, n;
            return this.active ? (this.isLastItem() || (this._hasScroll() ? (s = this.active.offset().top, n = this.element.height(), this.active.nextAll(".ui-menus-item").each(function () {
                return i = e(this), 0 > i.offset().top - s - n
            }), this.focus(t, i)) : this.focus(t, this.activeMenu.children(".ui-menus-item")[this.active ? "last" : "first"]())), undefined) : (this.next(t), undefined)
        },
        previousPage: function (t) {
            var i, s, n;
            return this.active ? (this.isFirstItem() || (this._hasScroll() ? (s = this.active.offset().top, n = this.element.height(), this.active.prevAll(".ui-menus-item").each(function () {
                return i = e(this), i.offset().top - s + n > 0
            }), this.focus(t, i)) : this.focus(t, this.activeMenu.children(".ui-menus-item").first())), undefined) : (this.next(t), undefined)
        },
        _hasScroll: function () {
            return this.element.outerHeight() < this.element.prop("scrollHeight")
        },
        select: function (t) {
            this.active = this.active || e(t.target).closest(".ui-menus-item");
            var i = {item: this.active};
            this.active.has(".ui-menus").length || this.collapseAll(t, !0), this._trigger("select", t, i)
        }
    })
})(jQuery);
(function (e, t) {
    e.widget("ui.progressbar", {
        version: "1.9.2", options: {value: 0, max: 100}, min: 0, _create: function () {
            this.element.addClass("ui-progressbar ui-widget ui-widget-content ui-corner-all").attr({
                role: "progressbar",
                "aria-valuemin": this.min,
                "aria-valuemax": this.options.max,
                "aria-valuenow": this._value()
            }), this.valueDiv = e("<div class='ui-progressbar-value ui-widget-header ui-corner-left'></div>").appendTo(this.element), this.oldValue = this._value(), this._refreshValue()
        }, _destroy: function () {
            this.element.removeClass("ui-progressbar ui-widget ui-widget-content ui-corner-all").removeAttr("role").removeAttr("aria-valuemin").removeAttr("aria-valuemax").removeAttr("aria-valuenow"), this.valueDiv.remove()
        }, value: function (e) {
            return e === t ? this._value() : (this._setOption("value", e), this)
        }, _setOption: function (e, t) {
            "value" === e && (this.options.value = t, this._refreshValue(), this._value() === this.options.max && this._trigger("complete")), this._super(e, t)
        }, _value: function () {
            var e = this.options.value;
            return "number" != typeof e && (e = 0), Math.min(this.options.max, Math.max(this.min, e))
        }, _percentage: function () {
            return 100 * this._value() / this.options.max
        }, _refreshValue: function () {
            var e = this.value(), t = this._percentage();
            this.oldValue !== e && (this.oldValue = e, this._trigger("change")), this.valueDiv.toggle(e > this.min).toggleClass("ui-corner-right", e === this.options.max).width(t.toFixed(0) + "%"), this.element.attr("aria-valuenow", e)
        }
    })
})(jQuery);
(function (e) {
    var t = 5;
    e.widget("ui.slider", e.ui.mouse, {
        version: "1.9.2",
        widgetEventPrefix: "slide",
        options: {
            animate: !1,
            distance: 0,
            max: 100,
            min: 0,
            orientation: "horizontal",
            range: !1,
            step: 1,
            value: 0,
            values: null
        },
        _create: function () {
            var i, s, n = this.options, a = this.element.find(".ui-slider-handle").addClass("ui-state-default ui-corner-all"), o = "<a class='ui-slider-handle ui-state-default ui-corner-all' href='#'></a>", r = [];
            for (this._keySliding = !1, this._mouseSliding = !1, this._animateOff = !0, this._handleIndex = null, this._detectOrientation(), this._mouseInit(), this.element.addClass("ui-slider ui-slider-" + this.orientation + " ui-widget" + " ui-widget-content" + " ui-corner-all" + (n.disabled ? " ui-slider-disabled ui-disabled" : "")), this.range = e([]), n.range && (n.range === !0 && (n.values || (n.values = [this._valueMin(), this._valueMin()]), n.values.length && 2 !== n.values.length && (n.values = [n.values[0], n.values[0]])), this.range = e("<div></div>").appendTo(this.element).addClass("ui-slider-range ui-widget-header" + ("min" === n.range || "max" === n.range ? " ui-slider-range-" + n.range : ""))), s = n.values && n.values.length || 1, i = a.length; s > i; i++)r.push(o);
            this.handles = a.add(e(r.join("")).appendTo(this.element)), this.handle = this.handles.eq(0), this.handles.add(this.range).filter("a").click(function (e) {
                e.preventDefault()
            }).mouseenter(function () {
                n.disabled || e(this).addClass("ui-state-hover")
            }).mouseleave(function () {
                e(this).removeClass("ui-state-hover")
            }).focus(function () {
                n.disabled ? e(this).blur() : (e(".ui-slider .ui-state-focus").removeClass("ui-state-focus"), e(this).addClass("ui-state-focus"))
            }).blur(function () {
                e(this).removeClass("ui-state-focus")
            }), this.handles.each(function (t) {
                e(this).data("ui-slider-handle-index", t)
            }), this._on(this.handles, {
                keydown: function (i) {
                    var s, n, a, o, r = e(i.target).data("ui-slider-handle-index");
                    switch (i.keyCode) {
                        case e.ui.keyCode.HOME:
                        case e.ui.keyCode.END:
                        case e.ui.keyCode.PAGE_UP:
                        case e.ui.keyCode.PAGE_DOWN:
                        case e.ui.keyCode.UP:
                        case e.ui.keyCode.RIGHT:
                        case e.ui.keyCode.DOWN:
                        case e.ui.keyCode.LEFT:
                            if (i.preventDefault(), !this._keySliding && (this._keySliding = !0, e(i.target).addClass("ui-state-active"), s = this._start(i, r), s === !1))return
                    }
                    switch (o = this.options.step, n = a = this.options.values && this.options.values.length ? this.values(r) : this.value(), i.keyCode) {
                        case e.ui.keyCode.HOME:
                            a = this._valueMin();
                            break;
                        case e.ui.keyCode.END:
                            a = this._valueMax();
                            break;
                        case e.ui.keyCode.PAGE_UP:
                            a = this._trimAlignValue(n + (this._valueMax() - this._valueMin()) / t);
                            break;
                        case e.ui.keyCode.PAGE_DOWN:
                            a = this._trimAlignValue(n - (this._valueMax() - this._valueMin()) / t);
                            break;
                        case e.ui.keyCode.UP:
                        case e.ui.keyCode.RIGHT:
                            if (n === this._valueMax())return;
                            a = this._trimAlignValue(n + o);
                            break;
                        case e.ui.keyCode.DOWN:
                        case e.ui.keyCode.LEFT:
                            if (n === this._valueMin())return;
                            a = this._trimAlignValue(n - o)
                    }
                    this._slide(i, r, a)
                }, keyup: function (t) {
                    var i = e(t.target).data("ui-slider-handle-index");
                    this._keySliding && (this._keySliding = !1, this._stop(t, i), this._change(t, i), e(t.target).removeClass("ui-state-active"))
                }
            }), this._refreshValue(), this._animateOff = !1
        },
        _destroy: function () {
            this.handles.remove(), this.range.remove(), this.element.removeClass("ui-slider ui-slider-horizontal ui-slider-vertical ui-slider-disabled ui-widget ui-widget-content ui-corner-all"), this._mouseDestroy()
        },
        _mouseCapture: function (t) {
            var i, s, n, a, o, r, h, l, u = this, d = this.options;
            return d.disabled ? !1 : (this.elementSize = {
                width: this.element.outerWidth(),
                height: this.element.outerHeight()
            }, this.elementOffset = this.element.offset(), i = {
                x: t.pageX,
                y: t.pageY
            }, s = this._normValueFromMouse(i), n = this._valueMax() - this._valueMin() + 1, this.handles.each(function (t) {
                var i = Math.abs(s - u.values(t));
                n > i && (n = i, a = e(this), o = t)
            }), d.range === !0 && this.values(1) === d.min && (o += 1, a = e(this.handles[o])), r = this._start(t, o), r === !1 ? !1 : (this._mouseSliding = !0, this._handleIndex = o, a.addClass("ui-state-active").focus(), h = a.offset(), l = !e(t.target).parents().andSelf().is(".ui-slider-handle"), this._clickOffset = l ? {
                left: 0,
                top: 0
            } : {
                left: t.pageX - h.left - a.width() / 2,
                top: t.pageY - h.top - a.height() / 2 - (parseInt(a.css("borderTopWidth"), 10) || 0) - (parseInt(a.css("borderBottomWidth"), 10) || 0) + (parseInt(a.css("marginTop"), 10) || 0)
            }, this.handles.hasClass("ui-state-hover") || this._slide(t, o, s), this._animateOff = !0, !0))
        },
        _mouseStart: function () {
            return !0
        },
        _mouseDrag: function (e) {
            var t = {x: e.pageX, y: e.pageY}, i = this._normValueFromMouse(t);
            return this._slide(e, this._handleIndex, i), !1
        },
        _mouseStop: function (e) {
            return this.handles.removeClass("ui-state-active"), this._mouseSliding = !1, this._stop(e, this._handleIndex), this._change(e, this._handleIndex), this._handleIndex = null, this._clickOffset = null, this._animateOff = !1, !1
        },
        _detectOrientation: function () {
            this.orientation = "vertical" === this.options.orientation ? "vertical" : "horizontal"
        },
        _normValueFromMouse: function (e) {
            var t, i, s, n, a;
            return "horizontal" === this.orientation ? (t = this.elementSize.width, i = e.x - this.elementOffset.left - (this._clickOffset ? this._clickOffset.left : 0)) : (t = this.elementSize.height, i = e.y - this.elementOffset.top - (this._clickOffset ? this._clickOffset.top : 0)), s = i / t, s > 1 && (s = 1), 0 > s && (s = 0), "vertical" === this.orientation && (s = 1 - s), n = this._valueMax() - this._valueMin(), a = this._valueMin() + s * n, this._trimAlignValue(a)
        },
        _start: function (e, t) {
            var i = {handle: this.handles[t], value: this.value()};
            return this.options.values && this.options.values.length && (i.value = this.values(t), i.values = this.values()), this._trigger("start", e, i)
        },
        _slide: function (e, t, i) {
            var s, n, a;
            this.options.values && this.options.values.length ? (s = this.values(t ? 0 : 1), 2 === this.options.values.length && this.options.range === !0 && (0 === t && i > s || 1 === t && s > i) && (i = s), i !== this.values(t) && (n = this.values(), n[t] = i, a = this._trigger("slide", e, {
                handle: this.handles[t],
                value: i,
                values: n
            }), s = this.values(t ? 0 : 1), a !== !1 && this.values(t, i, !0))) : i !== this.value() && (a = this._trigger("slide", e, {
                handle: this.handles[t],
                value: i
            }), a !== !1 && this.value(i))
        },
        _stop: function (e, t) {
            var i = {handle: this.handles[t], value: this.value()};
            this.options.values && this.options.values.length && (i.value = this.values(t), i.values = this.values()), this._trigger("stop", e, i)
        },
        _change: function (e, t) {
            if (!this._keySliding && !this._mouseSliding) {
                var i = {handle: this.handles[t], value: this.value()};
                this.options.values && this.options.values.length && (i.value = this.values(t), i.values = this.values()), this._trigger("change", e, i)
            }
        },
        value: function (e) {
            return arguments.length ? (this.options.value = this._trimAlignValue(e), this._refreshValue(), this._change(null, 0), undefined) : this._value()
        },
        values: function (t, i) {
            var s, n, a;
            if (arguments.length > 1)return this.options.values[t] = this._trimAlignValue(i), this._refreshValue(), this._change(null, t), undefined;
            if (!arguments.length)return this._values();
            if (!e.isArray(arguments[0]))return this.options.values && this.options.values.length ? this._values(t) : this.value();
            for (s = this.options.values, n = arguments[0], a = 0; s.length > a; a += 1)s[a] = this._trimAlignValue(n[a]), this._change(null, a);
            this._refreshValue()
        },
        _setOption: function (t, i) {
            var s, n = 0;
            switch (e.isArray(this.options.values) && (n = this.options.values.length), e.Widget.prototype._setOption.apply(this, arguments), t) {
                case"disabled":
                    i ? (this.handles.filter(".ui-state-focus").blur(), this.handles.removeClass("ui-state-hover"), this.handles.prop("disabled", !0), this.element.addClass("ui-disabled")) : (this.handles.prop("disabled", !1), this.element.removeClass("ui-disabled"));
                    break;
                case"orientation":
                    this._detectOrientation(), this.element.removeClass("ui-slider-horizontal ui-slider-vertical").addClass("ui-slider-" + this.orientation), this._refreshValue();
                    break;
                case"value":
                    this._animateOff = !0, this._refreshValue(), this._change(null, 0), this._animateOff = !1;
                    break;
                case"values":
                    for (this._animateOff = !0, this._refreshValue(), s = 0; n > s; s += 1)this._change(null, s);
                    this._animateOff = !1;
                    break;
                case"min":
                case"max":
                    this._animateOff = !0, this._refreshValue(), this._animateOff = !1
            }
        },
        _value: function () {
            var e = this.options.value;
            return e = this._trimAlignValue(e)
        },
        _values: function (e) {
            var t, i, s;
            if (arguments.length)return t = this.options.values[e], t = this._trimAlignValue(t);
            for (i = this.options.values.slice(), s = 0; i.length > s; s += 1)i[s] = this._trimAlignValue(i[s]);
            return i
        },
        _trimAlignValue: function (e) {
            if (this._valueMin() >= e)return this._valueMin();
            if (e >= this._valueMax())return this._valueMax();
            var t = this.options.step > 0 ? this.options.step : 1, i = (e - this._valueMin()) % t, s = e - i;
            return 2 * Math.abs(i) >= t && (s += i > 0 ? t : -t), parseFloat(s.toFixed(5))
        },
        _valueMin: function () {
            return this.options.min
        },
        _valueMax: function () {
            return this.options.max
        },
        _refreshValue: function () {
            var t, i, s, n, a, o = this.options.range, r = this.options, h = this, l = this._animateOff ? !1 : r.animate, u = {};
            this.options.values && this.options.values.length ? this.handles.each(function (s) {
                i = 100 * ((h.values(s) - h._valueMin()) / (h._valueMax() - h._valueMin())), u["horizontal" === h.orientation ? "left" : "bottom"] = i + "%", e(this).stop(1, 1)[l ? "animate" : "css"](u, r.animate), h.options.range === !0 && ("horizontal" === h.orientation ? (0 === s && h.range.stop(1, 1)[l ? "animate" : "css"]({left: i + "%"}, r.animate), 1 === s && h.range[l ? "animate" : "css"]({width: i - t + "%"}, {
                    queue: !1,
                    duration: r.animate
                })) : (0 === s && h.range.stop(1, 1)[l ? "animate" : "css"]({bottom: i + "%"}, r.animate), 1 === s && h.range[l ? "animate" : "css"]({height: i - t + "%"}, {
                    queue: !1,
                    duration: r.animate
                }))), t = i
            }) : (s = this.value(), n = this._valueMin(), a = this._valueMax(), i = a !== n ? 100 * ((s - n) / (a - n)) : 0, u["horizontal" === this.orientation ? "left" : "bottom"] = i + "%", this.handle.stop(1, 1)[l ? "animate" : "css"](u, r.animate), "min" === o && "horizontal" === this.orientation && this.range.stop(1, 1)[l ? "animate" : "css"]({width: i + "%"}, r.animate), "max" === o && "horizontal" === this.orientation && this.range[l ? "animate" : "css"]({width: 100 - i + "%"}, {
                queue: !1,
                duration: r.animate
            }), "min" === o && "vertical" === this.orientation && this.range.stop(1, 1)[l ? "animate" : "css"]({height: i + "%"}, r.animate), "max" === o && "vertical" === this.orientation && this.range[l ? "animate" : "css"]({height: 100 - i + "%"}, {
                queue: !1,
                duration: r.animate
            }))
        }
    })
})(jQuery);
(function (e) {
    function t(e) {
        return function () {
            var t = this.element.val();
            e.apply(this, arguments), this._refresh(), t !== this.element.val() && this._trigger("change")
        }
    }

    e.widget("ui.spinner", {
        version: "1.9.2",
        defaultElement: "<input>",
        widgetEventPrefix: "spin",
        options: {
            culture: null,
            icons: {down: "ui-icon-triangle-1-s", up: "ui-icon-triangle-1-n"},
            incremental: !0,
            max: null,
            min: null,
            numberFormat: null,
            page: 10,
            step: 1,
            change: null,
            spin: null,
            start: null,
            stop: null
        },
        _create: function () {
            this._setOption("max", this.options.max), this._setOption("min", this.options.min), this._setOption("step", this.options.step), this._value(this.element.val(), !0), this._draw(), this._on(this._events), this._refresh(), this._on(this.window, {
                beforeunload: function () {
                    this.element.removeAttr("autocomplete")
                }
            })
        },
        _getCreateOptions: function () {
            var t = {}, i = this.element;
            return e.each(["min", "max", "step"], function (e, s) {
                var n = i.attr(s);
                void 0 !== n && n.length && (t[s] = n)
            }), t
        },
        _events: {
            keydown: function (e) {
                this._start(e) && this._keydown(e) && e.preventDefault()
            }, keyup: "_stop", focus: function () {
                this.previous = this.element.val()
            }, blur: function (e) {
                return this.cancelBlur ? (delete this.cancelBlur, void 0) : (this._refresh(), this.previous !== this.element.val() && this._trigger("change", e), void 0)
            }, mousewheel: function (e, t) {
                if (t) {
                    if (!this.spinning && !this._start(e))return !1;
                    this._spin((t > 0 ? 1 : -1) * this.options.step, e), clearTimeout(this.mousewheelTimer), this.mousewheelTimer = this._delay(function () {
                        this.spinning && this._stop(e)
                    }, 100), e.preventDefault()
                }
            }, "mousedown .ui-spinner-button": function (t) {
                function i() {
                    var e = this.element[0] === this.document[0].activeElement;
                    e || (this.element.focus(), this.previous = s, this._delay(function () {
                        this.previous = s
                    }))
                }

                var s;
                s = this.element[0] === this.document[0].activeElement ? this.previous : this.element.val(), t.preventDefault(), i.call(this), this.cancelBlur = !0, this._delay(function () {
                    delete this.cancelBlur, i.call(this)
                }), this._start(t) !== !1 && this._repeat(null, e(t.currentTarget).hasClass("ui-spinner-up") ? 1 : -1, t)
            }, "mouseup .ui-spinner-button": "_stop", "mouseenter .ui-spinner-button": function (t) {
                return e(t.currentTarget).hasClass("ui-state-active") ? this._start(t) === !1 ? !1 : (this._repeat(null, e(t.currentTarget).hasClass("ui-spinner-up") ? 1 : -1, t), void 0) : void 0
            }, "mouseleave .ui-spinner-button": "_stop"
        },
        _draw: function () {
            var e = this.uiSpinner = this.element.addClass("ui-spinner-input").attr("autocomplete", "off").wrap(this._uiSpinnerHtml()).parent().append(this._buttonHtml());
            this.element.attr("role", "spinbutton"), this.buttons = e.find(".ui-spinner-button").attr("tabIndex", -1).button().removeClass("ui-corner-all"), this.buttons.height() > Math.ceil(.5 * e.height()) && e.height() > 0 && e.height(e.height()), this.options.disabled && this.disable()
        },
        _keydown: function (t) {
            var i = this.options, s = e.ui.keyCode;
            switch (t.keyCode) {
                case s.UP:
                    return this._repeat(null, 1, t), !0;
                case s.DOWN:
                    return this._repeat(null, -1, t), !0;
                case s.PAGE_UP:
                    return this._repeat(null, i.page, t), !0;
                case s.PAGE_DOWN:
                    return this._repeat(null, -i.page, t), !0
            }
            return !1
        },
        _uiSpinnerHtml: function () {
            return "<span class='ui-spinner ui-widget ui-widget-content ui-corner-all'></span>"
        },
        _buttonHtml: function () {
            return "<a class='ui-spinner-button ui-spinner-up ui-corner-tr'><span class='ui-icon " + this.options.icons.up + "'>&#9650;</span>" + "</a>" + "<a class='ui-spinner-button ui-spinner-down ui-corner-br'>" + "<span class='ui-icon " + this.options.icons.down + "'>&#9660;</span>" + "</a>"
        },
        _start: function (e) {
            return this.spinning || this._trigger("start", e) !== !1 ? (this.counter || (this.counter = 1), this.spinning = !0, !0) : !1
        },
        _repeat: function (e, t, i) {
            e = e || 500, clearTimeout(this.timer), this.timer = this._delay(function () {
                this._repeat(40, t, i)
            }, e), this._spin(t * this.options.step, i)
        },
        _spin: function (e, t) {
            var i = this.value() || 0;
            this.counter || (this.counter = 1), i = this._adjustValue(i + e * this._increment(this.counter)), this.spinning && this._trigger("spin", t, {value: i}) === !1 || (this._value(i), this.counter++)
        },
        _increment: function (t) {
            var i = this.options.incremental;
            return i ? e.isFunction(i) ? i(t) : Math.floor(t * t * t / 5e4 - t * t / 500 + 17 * t / 200 + 1) : 1
        },
        _precision: function () {
            var e = this._precisionOf(this.options.step);
            return null !== this.options.min && (e = Math.max(e, this._precisionOf(this.options.min))), e
        },
        _precisionOf: function (e) {
            var t = "" + e, i = t.indexOf(".");
            return -1 === i ? 0 : t.length - i - 1
        },
        _adjustValue: function (e) {
            var t, i, s = this.options;
            return t = null !== s.min ? s.min : 0, i = e - t, i = Math.round(i / s.step) * s.step, e = t + i, e = parseFloat(e.toFixed(this._precision())), null !== s.max && e > s.max ? s.max : null !== s.min && s.min > e ? s.min : e
        },
        _stop: function (e) {
            this.spinning && (clearTimeout(this.timer), clearTimeout(this.mousewheelTimer), this.counter = 0, this.spinning = !1, this._trigger("stop", e))
        },
        _setOption: function (e, t) {
            if ("culture" === e || "numberFormat" === e) {
                var i = this._parse(this.element.val());
                return this.options[e] = t, this.element.val(this._format(i)), void 0
            }
            ("max" === e || "min" === e || "step" === e) && "string" == typeof t && (t = this._parse(t)), this._super(e, t), "disabled" === e && (t ? (this.element.prop("disabled", !0), this.buttons.button("disable")) : (this.element.prop("disabled", !1), this.buttons.button("enable")))
        },
        _setOptions: t(function (e) {
            this._super(e), this._value(this.element.val())
        }),
        _parse: function (e) {
            return "string" == typeof e && "" !== e && (e = window.Globalize && this.options.numberFormat ? Globalize.parseFloat(e, 10, this.options.culture) : +e), "" === e || isNaN(e) ? null : e
        },
        _format: function (e) {
            return "" === e ? "" : window.Globalize && this.options.numberFormat ? Globalize.format(e, this.options.numberFormat, this.options.culture) : e
        },
        _refresh: function () {
            this.element.attr({
                "aria-valuemin": this.options.min,
                "aria-valuemax": this.options.max,
                "aria-valuenow": this._parse(this.element.val())
            })
        },
        _value: function (e, t) {
            var i;
            "" !== e && (i = this._parse(e), null !== i && (t || (i = this._adjustValue(i)), e = this._format(i))), this.element.val(e), this._refresh()
        },
        _destroy: function () {
            this.element.removeClass("ui-spinner-input").prop("disabled", !1).removeAttr("autocomplete").removeAttr("role").removeAttr("aria-valuemin").removeAttr("aria-valuemax").removeAttr("aria-valuenow"), this.uiSpinner.replaceWith(this.element)
        },
        stepUp: t(function (e) {
            this._stepUp(e)
        }),
        _stepUp: function (e) {
            this._spin((e || 1) * this.options.step)
        },
        stepDown: t(function (e) {
            this._stepDown(e)
        }),
        _stepDown: function (e) {
            this._spin((e || 1) * -this.options.step)
        },
        pageUp: t(function (e) {
            this._stepUp((e || 1) * this.options.page)
        }),
        pageDown: t(function (e) {
            this._stepDown((e || 1) * this.options.page)
        }),
        value: function (e) {
            return arguments.length ? (t(this._value).call(this, e), void 0) : this._parse(this.element.val())
        },
        widget: function () {
            return this.uiSpinner
        }
    })
})(jQuery);
(function (e, t) {
    function i() {
        return ++n
    }

    function s(e) {
        return e.hash.length > 1 && e.href.replace(a, "") === location.href.replace(a, "").replace(/\s/g, "%20")
    }

    var n = 0, a = /#.*$/;
    e.widget("ui.tabs", {
        version: "1.9.2",
        delay: 300,
        options: {
            active: null,
            collapsible: !1,
            event: "click",
            heightStyle: "content",
            hide: null,
            show: null,
            activate: null,
            beforeActivate: null,
            beforeLoad: null,
            load: null
        },
        _create: function () {
            var i = this, s = this.options, n = s.active, a = location.hash.substring(1);
            this.running = !1, this.element.addClass("ui-tabs ui-widget ui-widget-content ui-corner-all").toggleClass("ui-tabs-collapsible", s.collapsible).delegate(".ui-tabs-nav > li", "mousedown" + this.eventNamespace, function (t) {
                e(this).is(".ui-state-disabled") && t.preventDefault()
            }).delegate(".ui-tabs-anchor", "focus" + this.eventNamespace, function () {
                e(this).closest("li").is(".ui-state-disabled") && this.blur()
            }), this._processTabs(), null === n && (a && this.tabs.each(function (i, s) {
                return e(s).attr("aria-controls") === a ? (n = i, !1) : t
            }), null === n && (n = this.tabs.index(this.tabs.filter(".ui-tabs-active"))), (null === n || -1 === n) && (n = this.tabs.length ? 0 : !1)), n !== !1 && (n = this.tabs.index(this.tabs.eq(n)), -1 === n && (n = s.collapsible ? !1 : 0)), s.active = n, !s.collapsible && s.active === !1 && this.anchors.length && (s.active = 0), e.isArray(s.disabled) && (s.disabled = e.unique(s.disabled.concat(e.map(this.tabs.filter(".ui-state-disabled"), function (e) {
                return i.tabs.index(e)
            }))).sort()), this.active = this.options.active !== !1 && this.anchors.length ? this._findActive(this.options.active) : e(), this._refresh(), this.active.length && this.load(s.active)
        },
        _getCreateEventData: function () {
            return {tab: this.active, panel: this.active.length ? this._getPanelForTab(this.active) : e()}
        },
        _tabKeydown: function (i) {
            var s = e(this.document[0].activeElement).closest("li"), n = this.tabs.index(s), a = !0;
            if (!this._handlePageNav(i)) {
                switch (i.keyCode) {
                    case e.ui.keyCode.RIGHT:
                    case e.ui.keyCode.DOWN:
                        n++;
                        break;
                    case e.ui.keyCode.UP:
                    case e.ui.keyCode.LEFT:
                        a = !1, n--;
                        break;
                    case e.ui.keyCode.END:
                        n = this.anchors.length - 1;
                        break;
                    case e.ui.keyCode.HOME:
                        n = 0;
                        break;
                    case e.ui.keyCode.SPACE:
                        return i.preventDefault(), clearTimeout(this.activating), this._activate(n), t;
                    case e.ui.keyCode.ENTER:
                        return i.preventDefault(), clearTimeout(this.activating), this._activate(n === this.options.active ? !1 : n), t;
                    default:
                        return
                }
                i.preventDefault(), clearTimeout(this.activating), n = this._focusNextTab(n, a), i.ctrlKey || (s.attr("aria-selected", "false"), this.tabs.eq(n).attr("aria-selected", "true"), this.activating = this._delay(function () {
                    this.option("active", n)
                }, this.delay))
            }
        },
        _panelKeydown: function (t) {
            this._handlePageNav(t) || t.ctrlKey && t.keyCode === e.ui.keyCode.UP && (t.preventDefault(), this.active.focus())
        },
        _handlePageNav: function (i) {
            return i.altKey && i.keyCode === e.ui.keyCode.PAGE_UP ? (this._activate(this._focusNextTab(this.options.active - 1, !1)), !0) : i.altKey && i.keyCode === e.ui.keyCode.PAGE_DOWN ? (this._activate(this._focusNextTab(this.options.active + 1, !0)), !0) : t
        },
        _findNextTab: function (t, i) {
            function s() {
                return t > n && (t = 0), 0 > t && (t = n), t
            }

            for (var n = this.tabs.length - 1; -1 !== e.inArray(s(), this.options.disabled);)t = i ? t + 1 : t - 1;
            return t
        },
        _focusNextTab: function (e, t) {
            return e = this._findNextTab(e, t), this.tabs.eq(e).focus(), e
        },
        _setOption: function (e, i) {
            return "active" === e ? (this._activate(i), t) : "disabled" === e ? (this._setupDisabled(i), t) : (this._super(e, i), "collapsible" === e && (this.element.toggleClass("ui-tabs-collapsible", i), i || this.options.active !== !1 || this._activate(0)), "event" === e && this._setupEvents(i), "heightStyle" === e && this._setupHeightStyle(i), t)
        },
        _tabId: function (e) {
            return e.attr("aria-controls") || "ui-tabs-" + i()
        },
        _sanitizeSelector: function (e) {
            return e ? e.replace(/[!"$%&'()*+,.\/:;<=>?@\[\]\^`{|}~]/g, "\\$&") : ""
        },
        refresh: function () {
            var t = this.options, i = this.tablist.children(":has(a[href])");
            t.disabled = e.map(i.filter(".ui-state-disabled"), function (e) {
                return i.index(e)
            }), this._processTabs(), t.active !== !1 && this.anchors.length ? this.active.length && !e.contains(this.tablist[0], this.active[0]) ? this.tabs.length === t.disabled.length ? (t.active = !1, this.active = e()) : this._activate(this._findNextTab(Math.max(0, t.active - 1), !1)) : t.active = this.tabs.index(this.active) : (t.active = !1, this.active = e()), this._refresh()
        },
        _refresh: function () {
            this._setupDisabled(this.options.disabled), this._setupEvents(this.options.event), this._setupHeightStyle(this.options.heightStyle), this.tabs.not(this.active).attr({
                "aria-selected": "false",
                tabIndex: -1
            }), this.panels.not(this._getPanelForTab(this.active)).hide().attr({
                "aria-expanded": "false",
                "aria-hidden": "true"
            }), this.active.length ? (this.active.addClass("ui-tabs-active ui-state-active").attr({
                "aria-selected": "true",
                tabIndex: 0
            }), this._getPanelForTab(this.active).show().attr({
                "aria-expanded": "true",
                "aria-hidden": "false"
            })) : this.tabs.eq(0).attr("tabIndex", 0)
        },
        _processTabs: function () {
            var t = this;
            this.tablist = this._getList().addClass("ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all").attr("role", "tablist"), this.tabs = this.tablist.find("> li:has(a[href])").addClass("ui-state-default ui-corner-top").attr({
                role: "tab",
                tabIndex: -1
            }), this.anchors = this.tabs.map(function () {
                return e("a", this)[0]
            }).addClass("ui-tabs-anchor").attr({
                role: "presentation",
                tabIndex: -1
            }), this.panels = e(), this.anchors.each(function (i, n) {
                var a, o, r, h = e(n).uniqueId().attr("id"), l = e(n).closest("li"), u = l.attr("aria-controls");
                s(n) ? (a = n.hash, o = t.element.find(t._sanitizeSelector(a))) : (r = t._tabId(l), a = "#" + r, o = t.element.find(a), o.length || (o = t._createPanel(r), o.insertAfter(t.panels[i - 1] || t.tablist)), o.attr("aria-live", "polite")), o.length && (t.panels = t.panels.add(o)), u && l.data("ui-tabs-aria-controls", u), l.attr({
                    "aria-controls": a.substring(1),
                    "aria-labelledby": h
                }), o.attr("aria-labelledby", h)
            }), this.panels.addClass("ui-tabs-panel ui-widget-content ui-corner-bottom").attr("role", "tabpanel")
        },
        _getList: function () {
            return this.element.find("ol,ul").eq(0)
        },
        _createPanel: function (t) {
            return e("<div>").attr("id", t).addClass("ui-tabs-panel ui-widget-content ui-corner-bottom").data("ui-tabs-destroy", !0)
        },
        _setupDisabled: function (t) {
            e.isArray(t) && (t.length ? t.length === this.anchors.length && (t = !0) : t = !1);
            for (var i, s = 0; i = this.tabs[s]; s++)t === !0 || -1 !== e.inArray(s, t) ? e(i).addClass("ui-state-disabled").attr("aria-disabled", "true") : e(i).removeClass("ui-state-disabled").removeAttr("aria-disabled");
            this.options.disabled = t
        },
        _setupEvents: function (t) {
            var i = {
                click: function (e) {
                    e.preventDefault()
                }
            };
            t && e.each(t.split(" "), function (e, t) {
                i[t] = "_eventHandler"
            }), this._off(this.anchors.add(this.tabs).add(this.panels)), this._on(this.anchors, i), this._on(this.tabs, {keydown: "_tabKeydown"}), this._on(this.panels, {keydown: "_panelKeydown"}), this._focusable(this.tabs), this._hoverable(this.tabs)
        },
        _setupHeightStyle: function (t) {
            var i, s, n = this.element.parent();
            "fill" === t ? (e.support.minHeight || (s = n.css("overflow"), n.css("overflow", "hidden")), i = n.height(), this.element.siblings(":visible").each(function () {
                var t = e(this), s = t.css("position");
                "absolute" !== s && "fixed" !== s && (i -= t.outerHeight(!0))
            }), s && n.css("overflow", s), this.element.children().not(this.panels).each(function () {
                i -= e(this).outerHeight(!0)
            }), this.panels.each(function () {
                e(this).height(Math.max(0, i - e(this).innerHeight() + e(this).height()))
            }).css("overflow", "auto")) : "auto" === t && (i = 0, this.panels.each(function () {
                i = Math.max(i, e(this).height("").height())
            }).height(i))
        },
        _eventHandler: function (t) {
            var i = this.options, s = this.active, n = e(t.currentTarget), a = n.closest("li"), o = a[0] === s[0], r = o && i.collapsible, h = r ? e() : this._getPanelForTab(a), l = s.length ? this._getPanelForTab(s) : e(), u = {
                oldTab: s,
                oldPanel: l,
                newTab: r ? e() : a,
                newPanel: h
            };
            t.preventDefault(), a.hasClass("ui-state-disabled") || a.hasClass("ui-tabs-loading") || this.running || o && !i.collapsible || this._trigger("beforeActivate", t, u) === !1 || (i.active = r ? !1 : this.tabs.index(a), this.active = o ? e() : a, this.xhr && this.xhr.abort(), l.length || h.length || e.error("jQuery UI Tabs: Mismatching fragment identifier."), h.length && this.load(this.tabs.index(a), t), this._toggle(t, u))
        },
        _toggle: function (t, i) {
            function s() {
                a.running = !1, a._trigger("activate", t, i)
            }

            function n() {
                i.newTab.closest("li").addClass("ui-tabs-active ui-state-active"), o.length && a.options.show ? a._show(o, a.options.show, s) : (o.show(), s())
            }

            var a = this, o = i.newPanel, r = i.oldPanel;
            this.running = !0, r.length && this.options.hide ? this._hide(r, this.options.hide, function () {
                i.oldTab.closest("li").removeClass("ui-tabs-active ui-state-active"), n()
            }) : (i.oldTab.closest("li").removeClass("ui-tabs-active ui-state-active"), r.hide(), n()), r.attr({
                "aria-expanded": "false",
                "aria-hidden": "true"
            }), i.oldTab.attr("aria-selected", "false"), o.length && r.length ? i.oldTab.attr("tabIndex", -1) : o.length && this.tabs.filter(function () {
                return 0 === e(this).attr("tabIndex")
            }).attr("tabIndex", -1), o.attr({
                "aria-expanded": "true",
                "aria-hidden": "false"
            }), i.newTab.attr({"aria-selected": "true", tabIndex: 0})
        },
        _activate: function (t) {
            var i, s = this._findActive(t);
            s[0] !== this.active[0] && (s.length || (s = this.active), i = s.find(".ui-tabs-anchor")[0], this._eventHandler({
                target: i,
                currentTarget: i,
                preventDefault: e.noop
            }))
        },
        _findActive: function (t) {
            return t === !1 ? e() : this.tabs.eq(t)
        },
        _getIndex: function (e) {
            return "string" == typeof e && (e = this.anchors.index(this.anchors.filter("[href$='" + e + "']"))), e
        },
        _destroy: function () {
            this.xhr && this.xhr.abort(), this.element.removeClass("ui-tabs ui-widget ui-widget-content ui-corner-all ui-tabs-collapsible"), this.tablist.removeClass("ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all").removeAttr("role"), this.anchors.removeClass("ui-tabs-anchor").removeAttr("role").removeAttr("tabIndex").removeData("href.tabs").removeData("load.tabs").removeUniqueId(), this.tabs.add(this.panels).each(function () {
                e.data(this, "ui-tabs-destroy") ? e(this).remove() : e(this).removeClass("ui-state-default ui-state-active ui-state-disabled ui-corner-top ui-corner-bottom ui-widget-content ui-tabs-active ui-tabs-panel").removeAttr("tabIndex").removeAttr("aria-live").removeAttr("aria-busy").removeAttr("aria-selected").removeAttr("aria-labelledby").removeAttr("aria-hidden").removeAttr("aria-expanded").removeAttr("role")
            }), this.tabs.each(function () {
                var t = e(this), i = t.data("ui-tabs-aria-controls");
                i ? t.attr("aria-controls", i) : t.removeAttr("aria-controls")
            }), this.panels.show(), "content" !== this.options.heightStyle && this.panels.css("height", "")
        },
        enable: function (i) {
            var s = this.options.disabled;
            s !== !1 && (i === t ? s = !1 : (i = this._getIndex(i), s = e.isArray(s) ? e.map(s, function (e) {
                return e !== i ? e : null
            }) : e.map(this.tabs, function (e, t) {
                return t !== i ? t : null
            })), this._setupDisabled(s))
        },
        disable: function (i) {
            var s = this.options.disabled;
            if (s !== !0) {
                if (i === t)s = !0; else {
                    if (i = this._getIndex(i), -1 !== e.inArray(i, s))return;
                    s = e.isArray(s) ? e.merge([i], s).sort() : [i]
                }
                this._setupDisabled(s)
            }
        },
        load: function (t, i) {
            t = this._getIndex(t);
            var n = this, a = this.tabs.eq(t), o = a.find(".ui-tabs-anchor"), r = this._getPanelForTab(a), h = {
                tab: a,
                panel: r
            };
            s(o[0]) || (this.xhr = e.ajax(this._ajaxSettings(o, i, h)), this.xhr && "canceled" !== this.xhr.statusText && (a.addClass("ui-tabs-loading"), r.attr("aria-busy", "true"), this.xhr.success(function (e) {
                setTimeout(function () {
                    r.html(e), n._trigger("load", i, h)
                }, 1)
            }).complete(function (e, t) {
                setTimeout(function () {
                    "abort" === t && n.panels.stop(!1, !0), a.removeClass("ui-tabs-loading"), r.removeAttr("aria-busy"), e === n.xhr && delete n.xhr
                }, 1)
            })))
        },
        _ajaxSettings: function (t, i, s) {
            var n = this;
            return {
                url: t.attr("href"), beforeSend: function (t, a) {
                    return n._trigger("beforeLoad", i, e.extend({jqXHR: t, ajaxSettings: a}, s))
                }
            }
        },
        _getPanelForTab: function (t) {
            var i = e(t).attr("aria-controls");
            return this.element.find(this._sanitizeSelector("#" + i))
        }
    }), e.uiBackCompat !== !1 && (e.ui.tabs.prototype._ui = function (e, t) {
        return {tab: e, panel: t, index: this.anchors.index(e)}
    }, e.widget("ui.tabs", e.ui.tabs, {
        url: function (e, t) {
            this.anchors.eq(e).attr("href", t)
        }
    }), e.widget("ui.tabs", e.ui.tabs, {
        options: {ajaxOptions: null, cache: !1}, _create: function () {
            this._super();
            var i = this;
            this._on({
                tabsbeforeload: function (s, n) {
                    return e.data(n.tab[0], "cache.tabs") ? (s.preventDefault(), t) : (n.jqXHR.success(function () {
                        i.options.cache && e.data(n.tab[0], "cache.tabs", !0)
                    }), t)
                }
            })
        }, _ajaxSettings: function (t, i, s) {
            var n = this.options.ajaxOptions;
            return e.extend({}, n, {
                error: function (e, t) {
                    try {
                        n.error(e, t, s.tab.closest("li").index(), s.tab[0])
                    } catch (i) {
                    }
                }
            }, this._superApply(arguments))
        }, _setOption: function (e, t) {
            "cache" === e && t === !1 && this.anchors.removeData("cache.tabs"), this._super(e, t)
        }, _destroy: function () {
            this.anchors.removeData("cache.tabs"), this._super()
        }, url: function (e) {
            this.anchors.eq(e).removeData("cache.tabs"), this._superApply(arguments)
        }
    }), e.widget("ui.tabs", e.ui.tabs, {
        abort: function () {
            this.xhr && this.xhr.abort()
        }
    }), e.widget("ui.tabs", e.ui.tabs, {
        options: {spinner: "<em>Loading&#8230;</em>"}, _create: function () {
            this._super(), this._on({
                tabsbeforeload: function (e, t) {
                    if (e.target === this.element[0] && this.options.spinner) {
                        var i = t.tab.find("span"), s = i.html();
                        i.html(this.options.spinner), t.jqXHR.complete(function () {
                            i.html(s)
                        })
                    }
                }
            })
        }
    }), e.widget("ui.tabs", e.ui.tabs, {
        options: {enable: null, disable: null}, enable: function (t) {
            var i, s = this.options;
            (t && s.disabled === !0 || e.isArray(s.disabled) && -1 !== e.inArray(t, s.disabled)) && (i = !0), this._superApply(arguments), i && this._trigger("enable", null, this._ui(this.anchors[t], this.panels[t]))
        }, disable: function (t) {
            var i, s = this.options;
            (t && s.disabled === !1 || e.isArray(s.disabled) && -1 === e.inArray(t, s.disabled)) && (i = !0), this._superApply(arguments), i && this._trigger("disable", null, this._ui(this.anchors[t], this.panels[t]))
        }
    }), e.widget("ui.tabs", e.ui.tabs, {
        options: {
            add: null,
            remove: null,
            tabTemplate: "<li><a href='#{href}'><span>#{label}</span></a></li>"
        }, add: function (i, s, n) {
            n === t && (n = this.anchors.length);
            var a, o, r = this.options, h = e(r.tabTemplate.replace(/#\{href\}/g, i).replace(/#\{label\}/g, s)), l = i.indexOf("#") ? this._tabId(h) : i.replace("#", "");
            return h.addClass("ui-state-default ui-corner-top").data("ui-tabs-destroy", !0), h.attr("aria-controls", l), a = n >= this.tabs.length, o = this.element.find("#" + l), o.length || (o = this._createPanel(l), a ? n > 0 ? o.insertAfter(this.panels.eq(-1)) : o.appendTo(this.element) : o.insertBefore(this.panels[n])), o.addClass("ui-tabs-panel ui-widget-content ui-corner-bottom").hide(), a ? h.appendTo(this.tablist) : h.insertBefore(this.tabs[n]), r.disabled = e.map(r.disabled, function (e) {
                return e >= n ? ++e : e
            }), this.refresh(), 1 === this.tabs.length && r.active === !1 && this.option("active", 0), this._trigger("add", null, this._ui(this.anchors[n], this.panels[n])), this
        }, remove: function (t) {
            t = this._getIndex(t);
            var i = this.options, s = this.tabs.eq(t).remove(), n = this._getPanelForTab(s).remove();
            return s.hasClass("ui-tabs-active") && this.anchors.length > 2 && this._activate(t + (this.anchors.length > t + 1 ? 1 : -1)), i.disabled = e.map(e.grep(i.disabled, function (e) {
                return e !== t
            }), function (e) {
                return e >= t ? --e : e
            }), this.refresh(), this._trigger("remove", null, this._ui(s.find("a")[0], n[0])), this
        }
    }), e.widget("ui.tabs", e.ui.tabs, {
        length: function () {
            return this.anchors.length
        }
    }), e.widget("ui.tabs", e.ui.tabs, {
        options: {idPrefix: "ui-tabs-"}, _tabId: function (t) {
            var s = t.is("li") ? t.find("a[href]") : t;
            return s = s[0], e(s).closest("li").attr("aria-controls") || s.title && s.title.replace(/\s/g, "_").replace(/[^\w\u00c0-\uFFFF\-]/g, "") || this.options.idPrefix + i()
        }
    }), e.widget("ui.tabs", e.ui.tabs, {
        options: {panelTemplate: "<div></div>"}, _createPanel: function (t) {
            return e(this.options.panelTemplate).attr("id", t).addClass("ui-tabs-panel ui-widget-content ui-corner-bottom").data("ui-tabs-destroy", !0)
        }
    }), e.widget("ui.tabs", e.ui.tabs, {
        _create: function () {
            var e = this.options;
            null === e.active && e.selected !== t && (e.active = -1 === e.selected ? !1 : e.selected), this._super(), e.selected = e.active, e.selected === !1 && (e.selected = -1)
        }, _setOption: function (e, t) {
            if ("selected" !== e)return this._super(e, t);
            var i = this.options;
            this._super("active", -1 === t ? !1 : t), i.selected = i.active, i.selected === !1 && (i.selected = -1)
        }, _eventHandler: function () {
            this._superApply(arguments), this.options.selected = this.options.active, this.options.selected === !1 && (this.options.selected = -1)
        }
    }), e.widget("ui.tabs", e.ui.tabs, {
        options: {show: null, select: null}, _create: function () {
            this._super(), this.options.active !== !1 && this._trigger("show", null, this._ui(this.active.find(".ui-tabs-anchor")[0], this._getPanelForTab(this.active)[0]))
        }, _trigger: function (e, t, i) {
            var s, n, a = this._superApply(arguments);
            return a ? ("beforeActivate" === e ? (s = i.newTab.length ? i.newTab : i.oldTab, n = i.newPanel.length ? i.newPanel : i.oldPanel, a = this._super("select", t, {
                tab: s.find(".ui-tabs-anchor")[0],
                panel: n[0],
                index: s.closest("li").index()
            })) : "activate" === e && i.newTab.length && (a = this._super("show", t, {
                tab: i.newTab.find(".ui-tabs-anchor")[0],
                panel: i.newPanel[0],
                index: i.newTab.closest("li").index()
            })), a) : !1
        }
    }), e.widget("ui.tabs", e.ui.tabs, {
        select: function (e) {
            if (e = this._getIndex(e), -1 === e) {
                if (!this.options.collapsible || -1 === this.options.selected)return;
                e = this.options.selected
            }
            this.anchors.eq(e).trigger(this.options.event + this.eventNamespace)
        }
    }), function () {
        var t = 0;
        e.widget("ui.tabs", e.ui.tabs, {
            options: {cookie: null}, _create: function () {
                var e, t = this.options;
                null == t.active && t.cookie && (e = parseInt(this._cookie(), 10), -1 === e && (e = !1), t.active = e), this._super()
            }, _cookie: function (i) {
                var s = [this.cookie || (this.cookie = this.options.cookie.name || "ui-tabs-" + ++t)];
                return arguments.length && (s.push(i === !1 ? -1 : i), s.push(this.options.cookie)), e.cookie.apply(null, s)
            }, _refresh: function () {
                this._super(), this.options.cookie && this._cookie(this.options.active, this.options.cookie)
            }, _eventHandler: function () {
                this._superApply(arguments), this.options.cookie && this._cookie(this.options.active, this.options.cookie)
            }, _destroy: function () {
                this._super(), this.options.cookie && this._cookie(null, this.options.cookie)
            }
        })
    }(), e.widget("ui.tabs", e.ui.tabs, {
        _trigger: function (t, i, s) {
            var n = e.extend({}, s);
            return "load" === t && (n.panel = n.panel[0], n.tab = n.tab.find(".ui-tabs-anchor")[0]), this._super(t, i, n)
        }
    }), e.widget("ui.tabs", e.ui.tabs, {
        options: {fx: null}, _getFx: function () {
            var t, i, s = this.options.fx;
            return s && (e.isArray(s) ? (t = s[0], i = s[1]) : t = i = s), s ? {show: i, hide: t} : null
        }, _toggle: function (e, i) {
            function s() {
                a.running = !1, a._trigger("activate", e, i)
            }

            function n() {
                i.newTab.closest("li").addClass("ui-tabs-active ui-state-active"), o.length && h.show ? o.animate(h.show, h.show.duration, function () {
                    s()
                }) : (o.show(), s())
            }

            var a = this, o = i.newPanel, r = i.oldPanel, h = this._getFx();
            return h ? (a.running = !0, r.length && h.hide ? r.animate(h.hide, h.hide.duration, function () {
                i.oldTab.closest("li").removeClass("ui-tabs-active ui-state-active"), n()
            }) : (i.oldTab.closest("li").removeClass("ui-tabs-active ui-state-active"), r.hide(), n()), t) : this._super(e, i)
        }
    }))
})(jQuery);
(function (e) {
    function t(t, i) {
        var s = (t.attr("aria-describedby") || "").split(/\s+/);
        s.push(i), t.data("ui-tooltip-id", i).attr("aria-describedby", e.trim(s.join(" ")))
    }

    function i(t) {
        var i = t.data("ui-tooltip-id"), s = (t.attr("aria-describedby") || "").split(/\s+/), n = e.inArray(i, s);
        -1 !== n && s.splice(n, 1), t.removeData("ui-tooltip-id"), s = e.trim(s.join(" ")), s ? t.attr("aria-describedby", s) : t.removeAttr("aria-describedby")
    }

    var s = 0;
    e.widget("ui.tooltip", {
        version: "1.9.2", options: {
            content: function () {
                return e(this).attr("title")
            },
            hide: !0,
            items: "[title]:not([disabled])",
            position: {my: "left top+15", at: "left bottom", collision: "flipfit flip"},
            show: !0,
            tooltipClass: null,
            track: !1,
            close: null,
            open: null
        }, _create: function () {
            this._on({
                mouseover: "open",
                focusin: "open"
            }), this.tooltips = {}, this.parents = {}, this.options.disabled && this._disable()
        }, _setOption: function (t, i) {
            var s = this;
            return "disabled" === t ? (this[i ? "_disable" : "_enable"](), this.options[t] = i, void 0) : (this._super(t, i), "content" === t && e.each(this.tooltips, function (e, t) {
                s._updateContent(t)
            }), void 0)
        }, _disable: function () {
            var t = this;
            e.each(this.tooltips, function (i, s) {
                var n = e.Event("blur");
                n.target = n.currentTarget = s[0], t.close(n, !0)
            }), this.element.find(this.options.items).andSelf().each(function () {
                var t = e(this);
                t.is("[title]") && t.data("ui-tooltip-title", t.attr("title")).attr("title", "")
            })
        }, _enable: function () {
            this.element.find(this.options.items).andSelf().each(function () {
                var t = e(this);
                t.data("ui-tooltip-title") && t.attr("title", t.data("ui-tooltip-title"))
            })
        }, open: function (t) {
            var i = this, s = e(t ? t.target : this.element).closest(this.options.items);
            s.length && !s.data("ui-tooltip-id") && (s.attr("title") && s.data("ui-tooltip-title", s.attr("title")), s.data("ui-tooltip-open", !0), t && "mouseover" === t.type && s.parents().each(function () {
                var t, s = e(this);
                s.data("ui-tooltip-open") && (t = e.Event("blur"), t.target = t.currentTarget = this, i.close(t, !0)), s.attr("title") && (s.uniqueId(), i.parents[this.id] = {
                    element: this,
                    title: s.attr("title")
                }, s.attr("title", ""))
            }), this._updateContent(s, t))
        }, _updateContent: function (e, t) {
            var i, s = this.options.content, n = this, a = t ? t.type : null;
            return "string" == typeof s ? this._open(t, e, s) : (i = s.call(e[0], function (i) {
                e.data("ui-tooltip-open") && n._delay(function () {
                    t && (t.type = a), this._open(t, e, i)
                })
            }), i && this._open(t, e, i), void 0)
        }, _open: function (i, s, n) {
            function a(e) {
                l.of = e, o.is(":hidden") || o.position(l)
            }

            var o, r, h, l = e.extend({}, this.options.position);
            if (n) {
                if (o = this._find(s), o.length)return o.find(".ui-tooltip-content").html(n), void 0;
                s.is("[title]") && (i && "mouseover" === i.type ? s.attr("title", "") : s.removeAttr("title")), o = this._tooltip(s), t(s, o.attr("id")), o.find(".ui-tooltip-content").html(n), this.options.track && i && /^mouse/.test(i.type) ? (this._on(this.document, {mousemove: a}), a(i)) : o.position(e.extend({of: s}, this.options.position)), o.hide(), this._show(o, this.options.show), this.options.show && this.options.show.delay && (h = setInterval(function () {
                    o.is(":visible") && (a(l.of), clearInterval(h))
                }, e.fx.interval)), this._trigger("open", i, {tooltip: o}), r = {
                    keyup: function (t) {
                        if (t.keyCode === e.ui.keyCode.ESCAPE) {
                            var i = e.Event(t);
                            i.currentTarget = s[0], this.close(i, !0)
                        }
                    }, remove: function () {
                        this._removeTooltip(o)
                    }
                }, i && "mouseover" !== i.type || (r.mouseleave = "close"), i && "focusin" !== i.type || (r.focusout = "close"), this._on(!0, s, r)
            }
        }, close: function (t) {
            var s = this, n = e(t ? t.currentTarget : this.element), a = this._find(n);
            this.closing || (n.data("ui-tooltip-title") && n.attr("title", n.data("ui-tooltip-title")), i(n), a.stop(!0), this._hide(a, this.options.hide, function () {
                s._removeTooltip(e(this))
            }), n.removeData("ui-tooltip-open"), this._off(n, "mouseleave focusout keyup"), n[0] !== this.element[0] && this._off(n, "remove"), this._off(this.document, "mousemove"), t && "mouseleave" === t.type && e.each(this.parents, function (t, i) {
                e(i.element).attr("title", i.title), delete s.parents[t]
            }), this.closing = !0, this._trigger("close", t, {tooltip: a}), this.closing = !1)
        }, _tooltip: function (t) {
            var i = "ui-tooltip-" + s++, n = e("<div>").attr({
                id: i,
                role: "tooltip"
            }).addClass("ui-tooltip ui-widget ui-corner-all ui-widget-content " + (this.options.tooltipClass || ""));
            return e("<div>").addClass("ui-tooltip-content").appendTo(n), n.appendTo(this.document[0].body), e.fn.bgiframe && n.bgiframe(), this.tooltips[i] = t, n
        }, _find: function (t) {
            var i = t.data("ui-tooltip-id");
            return i ? e("#" + i) : e()
        }, _removeTooltip: function (e) {
            e.remove(), delete this.tooltips[e.attr("id")]
        }, _destroy: function () {
            var t = this;
            e.each(this.tooltips, function (i, s) {
                var n = e.Event("blur");
                n.target = n.currentTarget = s[0], t.close(n, !0), e("#" + i).remove(), s.data("ui-tooltip-title") && (s.attr("title", s.data("ui-tooltip-title")), s.removeData("ui-tooltip-title"))
            })
        }
    })
})(jQuery);
jQuery.effects || function (e, t) {
    var i = e.uiBackCompat !== !1, s = "ui-effects-";
    e.effects = {effect: {}}, function (t, i) {
        function s(e, t, i) {
            var s = c[t.type] || {};
            return null == e ? i || !t.def ? null : t.def : (e = s.floor ? ~~e : parseFloat(e), isNaN(e) ? t.def : s.mod ? (e + s.mod) % s.mod : 0 > e ? 0 : e > s.max ? s.max : e)
        }

        function n(e) {
            var s = u(), n = s._rgba = [];
            return e = e.toLowerCase(), m(l, function (t, a) {
                var o, r = a.re.exec(e), h = r && a.parse(r), l = a.space || "rgba";
                return h ? (o = s[l](h), s[d[l].cache] = o[d[l].cache], n = s._rgba = o._rgba, !1) : i
            }), n.length ? ("0,0,0,0" === n.join() && t.extend(n, o.transparent), s) : o[e]
        }

        function a(e, t, i) {
            return i = (i + 1) % 1, 1 > 6 * i ? e + 6 * (t - e) * i : 1 > 2 * i ? t : 2 > 3 * i ? e + 6 * (t - e) * (2 / 3 - i) : e
        }

        var o, r = "backgroundColor borderBottomColor borderLeftColor borderRightColor borderTopColor color columnRuleColor outlineColor textDecorationColor textEmphasisColor".split(" "), h = /^([\-+])=\s*(\d+\.?\d*)/, l = [{
            re: /rgba?\(\s*(\d{1,3})\s*,\s*(\d{1,3})\s*,\s*(\d{1,3})\s*(?:,\s*(\d+(?:\.\d+)?)\s*)?\)/,
            parse: function (e) {
                return [e[1], e[2], e[3], e[4]]
            }
        }, {
            re: /rgba?\(\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d+(?:\.\d+)?)\s*)?\)/,
            parse: function (e) {
                return [2.55 * e[1], 2.55 * e[2], 2.55 * e[3], e[4]]
            }
        }, {
            re: /#([a-f0-9]{2})([a-f0-9]{2})([a-f0-9]{2})/, parse: function (e) {
                return [parseInt(e[1], 16), parseInt(e[2], 16), parseInt(e[3], 16)]
            }
        }, {
            re: /#([a-f0-9])([a-f0-9])([a-f0-9])/, parse: function (e) {
                return [parseInt(e[1] + e[1], 16), parseInt(e[2] + e[2], 16), parseInt(e[3] + e[3], 16)]
            }
        }, {
            re: /hsla?\(\s*(\d+(?:\.\d+)?)\s*,\s*(\d+(?:\.\d+)?)\%\s*,\s*(\d+(?:\.\d+)?)\%\s*(?:,\s*(\d+(?:\.\d+)?)\s*)?\)/,
            space: "hsla",
            parse: function (e) {
                return [e[1], e[2] / 100, e[3] / 100, e[4]]
            }
        }], u = t.Color = function (e, i, s, n) {
            return new t.Color.fn.parse(e, i, s, n)
        }, d = {
            rgba: {
                props: {
                    red: {idx: 0, type: "byte"},
                    green: {idx: 1, type: "byte"},
                    blue: {idx: 2, type: "byte"}
                }
            },
            hsla: {
                props: {
                    hue: {idx: 0, type: "degrees"},
                    saturation: {idx: 1, type: "percent"},
                    lightness: {idx: 2, type: "percent"}
                }
            }
        }, c = {
            "byte": {floor: !0, max: 255},
            percent: {max: 1},
            degrees: {mod: 360, floor: !0}
        }, p = u.support = {}, f = t("<p>")[0], m = t.each;
        f.style.cssText = "background-color:rgba(1,1,1,.5)", p.rgba = f.style.backgroundColor.indexOf("rgba") > -1, m(d, function (e, t) {
            t.cache = "_" + e, t.props.alpha = {idx: 3, type: "percent", def: 1}
        }), u.fn = t.extend(u.prototype, {
            parse: function (a, r, h, l) {
                if (a === i)return this._rgba = [null, null, null, null], this;
                (a.jquery || a.nodeType) && (a = t(a).css(r), r = i);
                var c = this, p = t.type(a), f = this._rgba = [];
                return r !== i && (a = [a, r, h, l], p = "array"), "string" === p ? this.parse(n(a) || o._default) : "array" === p ? (m(d.rgba.props, function (e, t) {
                    f[t.idx] = s(a[t.idx], t)
                }), this) : "object" === p ? (a instanceof u ? m(d, function (e, t) {
                    a[t.cache] && (c[t.cache] = a[t.cache].slice())
                }) : m(d, function (t, i) {
                    var n = i.cache;
                    m(i.props, function (e, t) {
                        if (!c[n] && i.to) {
                            if ("alpha" === e || null == a[e])return;
                            c[n] = i.to(c._rgba)
                        }
                        c[n][t.idx] = s(a[e], t, !0)
                    }), c[n] && 0 > e.inArray(null, c[n].slice(0, 3)) && (c[n][3] = 1, i.from && (c._rgba = i.from(c[n])))
                }), this) : i
            }, is: function (e) {
                var t = u(e), s = !0, n = this;
                return m(d, function (e, a) {
                    var o, r = t[a.cache];
                    return r && (o = n[a.cache] || a.to && a.to(n._rgba) || [], m(a.props, function (e, t) {
                        return null != r[t.idx] ? s = r[t.idx] === o[t.idx] : i
                    })), s
                }), s
            }, _space: function () {
                var e = [], t = this;
                return m(d, function (i, s) {
                    t[s.cache] && e.push(i)
                }), e.pop()
            }, transition: function (e, t) {
                var i = u(e), n = i._space(), a = d[n], o = 0 === this.alpha() ? u("transparent") : this, r = o[a.cache] || a.to(o._rgba), h = r.slice();
                return i = i[a.cache], m(a.props, function (e, n) {
                    var a = n.idx, o = r[a], l = i[a], u = c[n.type] || {};
                    null !== l && (null === o ? h[a] = l : (u.mod && (l - o > u.mod / 2 ? o += u.mod : o - l > u.mod / 2 && (o -= u.mod)), h[a] = s((l - o) * t + o, n)))
                }), this[n](h)
            }, blend: function (e) {
                if (1 === this._rgba[3])return this;
                var i = this._rgba.slice(), s = i.pop(), n = u(e)._rgba;
                return u(t.map(i, function (e, t) {
                    return (1 - s) * n[t] + s * e
                }))
            }, toRgbaString: function () {
                var e = "rgba(", i = t.map(this._rgba, function (e, t) {
                    return null == e ? t > 2 ? 1 : 0 : e
                });
                return 1 === i[3] && (i.pop(), e = "rgb("), e + i.join() + ")"
            }, toHslaString: function () {
                var e = "hsla(", i = t.map(this.hsla(), function (e, t) {
                    return null == e && (e = t > 2 ? 1 : 0), t && 3 > t && (e = Math.round(100 * e) + "%"), e
                });
                return 1 === i[3] && (i.pop(), e = "hsl("), e + i.join() + ")"
            }, toHexString: function (e) {
                var i = this._rgba.slice(), s = i.pop();
                return e && i.push(~~(255 * s)), "#" + t.map(i, function (e) {
                    return e = (e || 0).toString(16), 1 === e.length ? "0" + e : e
                }).join("")
            }, toString: function () {
                return 0 === this._rgba[3] ? "transparent" : this.toRgbaString()
            }
        }), u.fn.parse.prototype = u.fn, d.hsla.to = function (e) {
            if (null == e[0] || null == e[1] || null == e[2])return [null, null, null, e[3]];
            var t, i, s = e[0] / 255, n = e[1] / 255, a = e[2] / 255, o = e[3], r = Math.max(s, n, a), h = Math.min(s, n, a), l = r - h, u = r + h, d = .5 * u;
            return t = h === r ? 0 : s === r ? 60 * (n - a) / l + 360 : n === r ? 60 * (a - s) / l + 120 : 60 * (s - n) / l + 240, i = 0 === d || 1 === d ? d : .5 >= d ? l / u : l / (2 - u), [Math.round(t) % 360, i, d, null == o ? 1 : o]
        }, d.hsla.from = function (e) {
            if (null == e[0] || null == e[1] || null == e[2])return [null, null, null, e[3]];
            var t = e[0] / 360, i = e[1], s = e[2], n = e[3], o = .5 >= s ? s * (1 + i) : s + i - s * i, r = 2 * s - o;
            return [Math.round(255 * a(r, o, t + 1 / 3)), Math.round(255 * a(r, o, t)), Math.round(255 * a(r, o, t - 1 / 3)), n]
        }, m(d, function (e, n) {
            var a = n.props, o = n.cache, r = n.to, l = n.from;
            u.fn[e] = function (e) {
                if (r && !this[o] && (this[o] = r(this._rgba)), e === i)return this[o].slice();
                var n, h = t.type(e), d = "array" === h || "object" === h ? e : arguments, c = this[o].slice();
                return m(a, function (e, t) {
                    var i = d["object" === h ? e : t.idx];
                    null == i && (i = c[t.idx]), c[t.idx] = s(i, t)
                }), l ? (n = u(l(c)), n[o] = c, n) : u(c)
            }, m(a, function (i, s) {
                u.fn[i] || (u.fn[i] = function (n) {
                    var a, o = t.type(n), r = "alpha" === i ? this._hsla ? "hsla" : "rgba" : e, l = this[r](), u = l[s.idx];
                    return "undefined" === o ? u : ("function" === o && (n = n.call(this, u), o = t.type(n)), null == n && s.empty ? this : ("string" === o && (a = h.exec(n), a && (n = u + parseFloat(a[2]) * ("+" === a[1] ? 1 : -1))), l[s.idx] = n, this[r](l)))
                })
            })
        }), m(r, function (e, i) {
            t.cssHooks[i] = {
                set: function (e, s) {
                    var a, o, r = "";
                    if ("string" !== t.type(s) || (a = n(s))) {
                        if (s = u(a || s), !p.rgba && 1 !== s._rgba[3]) {
                            for (o = "backgroundColor" === i ? e.parentNode : e; ("" === r || "transparent" === r) && o && o.style;)try {
                                r = t.css(o, "backgroundColor"), o = o.parentNode
                            } catch (h) {
                            }
                            s = s.blend(r && "transparent" !== r ? r : "_default")
                        }
                        s = s.toRgbaString()
                    }
                    try {
                        e.style[i] = s
                    } catch (l) {
                    }
                }
            }, t.fx.step[i] = function (e) {
                e.colorInit || (e.start = u(e.elem, i), e.end = u(e.end), e.colorInit = !0), t.cssHooks[i].set(e.elem, e.start.transition(e.end, e.pos))
            }
        }), t.cssHooks.borderColor = {
            expand: function (e) {
                var t = {};
                return m(["Top", "Right", "Bottom", "Left"], function (i, s) {
                    t["border" + s + "Color"] = e
                }), t
            }
        }, o = t.Color.names = {
            aqua: "#00ffff",
            black: "#000000",
            blue: "#0000ff",
            fuchsia: "#ff00ff",
            gray: "#808080",
            green: "#008000",
            lime: "#00ff00",
            maroon: "#800000",
            navy: "#000080",
            olive: "#808000",
            purple: "#800080",
            red: "#ff0000",
            silver: "#c0c0c0",
            teal: "#008080",
            white: "#ffffff",
            yellow: "#ffff00",
            transparent: [null, null, null, 0],
            _default: "#ffffff"
        }
    }(jQuery), function () {
        function i() {
            var t, i, s = this.ownerDocument.defaultView ? this.ownerDocument.defaultView.getComputedStyle(this, null) : this.currentStyle, n = {};
            if (s && s.length && s[0] && s[s[0]])for (i = s.length; i--;)t = s[i], "string" == typeof s[t] && (n[e.camelCase(t)] = s[t]); else for (t in s)"string" == typeof s[t] && (n[t] = s[t]);
            return n
        }

        function s(t, i) {
            var s, n, o = {};
            for (s in i)n = i[s], t[s] !== n && (a[s] || (e.fx.step[s] || !isNaN(parseFloat(n))) && (o[s] = n));
            return o
        }

        var n = ["add", "remove", "toggle"], a = {
            border: 1,
            borderBottom: 1,
            borderColor: 1,
            borderLeft: 1,
            borderRight: 1,
            borderTop: 1,
            borderWidth: 1,
            margin: 1,
            padding: 1
        };
        e.each(["borderLeftStyle", "borderRightStyle", "borderBottomStyle", "borderTopStyle"], function (t, i) {
            e.fx.step[i] = function (e) {
                ("none" !== e.end && !e.setAttr || 1 === e.pos && !e.setAttr) && (jQuery.style(e.elem, i, e.end), e.setAttr = !0)
            }
        }), e.effects.animateClass = function (t, a, o, r) {
            var h = e.speed(a, o, r);
            return this.queue(function () {
                var a, o = e(this), r = o.attr("class") || "", l = h.children ? o.find("*").andSelf() : o;
                l = l.map(function () {
                    var t = e(this);
                    return {el: t, start: i.call(this)}
                }), a = function () {
                    e.each(n, function (e, i) {
                        t[i] && o[i + "Class"](t[i])
                    })
                }, a(), l = l.map(function () {
                    return this.end = i.call(this.el[0]), this.diff = s(this.start, this.end), this
                }), o.attr("class", r), l = l.map(function () {
                    var t = this, i = e.Deferred(), s = jQuery.extend({}, h, {
                        queue: !1, complete: function () {
                            i.resolve(t)
                        }
                    });
                    return this.el.animate(this.diff, s), i.promise()
                }), e.when.apply(e, l.get()).done(function () {
                    a(), e.each(arguments, function () {
                        var t = this.el;
                        e.each(this.diff, function (e) {
                            t.css(e, "")
                        })
                    }), h.complete.call(o[0])
                })
            })
        }, e.fn.extend({
            _addClass: e.fn.addClass, addClass: function (t, i, s, n) {
                return i ? e.effects.animateClass.call(this, {add: t}, i, s, n) : this._addClass(t)
            }, _removeClass: e.fn.removeClass, removeClass: function (t, i, s, n) {
                return i ? e.effects.animateClass.call(this, {remove: t}, i, s, n) : this._removeClass(t)
            }, _toggleClass: e.fn.toggleClass, toggleClass: function (i, s, n, a, o) {
                return "boolean" == typeof s || s === t ? n ? e.effects.animateClass.call(this, s ? {add: i} : {remove: i}, n, a, o) : this._toggleClass(i, s) : e.effects.animateClass.call(this, {toggle: i}, s, n, a)
            }, switchClass: function (t, i, s, n, a) {
                return e.effects.animateClass.call(this, {add: i, remove: t}, s, n, a)
            }
        })
    }(), function () {
        function n(t, i, s, n) {
            return e.isPlainObject(t) && (i = t, t = t.effect), t = {effect: t}, null == i && (i = {}), e.isFunction(i) && (n = i, s = null, i = {}), ("number" == typeof i || e.fx.speeds[i]) && (n = s, s = i, i = {}), e.isFunction(s) && (n = s, s = null), i && e.extend(t, i), s = s || i.duration, t.duration = e.fx.off ? 0 : "number" == typeof s ? s : s in e.fx.speeds ? e.fx.speeds[s] : e.fx.speeds._default, t.complete = n || i.complete, t
        }

        function a(t) {
            return !t || "number" == typeof t || e.fx.speeds[t] ? !0 : "string" != typeof t || e.effects.effect[t] ? !1 : i && e.effects[t] ? !1 : !0
        }

        e.extend(e.effects, {
            version: "1.9.2", save: function (e, t) {
                for (var i = 0; t.length > i; i++)null !== t[i] && e.data(s + t[i], e[0].style[t[i]])
            }, restore: function (e, i) {
                var n, a;
                for (a = 0; i.length > a; a++)null !== i[a] && (n = e.data(s + i[a]), n === t && (n = ""), e.css(i[a], n))
            }, setMode: function (e, t) {
                return "toggle" === t && (t = e.is(":hidden") ? "show" : "hide"), t
            }, getBaseline: function (e, t) {
                var i, s;
                switch (e[0]) {
                    case"top":
                        i = 0;
                        break;
                    case"middle":
                        i = .5;
                        break;
                    case"bottom":
                        i = 1;
                        break;
                    default:
                        i = e[0] / t.height
                }
                switch (e[1]) {
                    case"left":
                        s = 0;
                        break;
                    case"center":
                        s = .5;
                        break;
                    case"right":
                        s = 1;
                        break;
                    default:
                        s = e[1] / t.width
                }
                return {x: s, y: i}
            }, createWrapper: function (t) {
                if (t.parent().is(".ui-effects-wrapper"))return t.parent();
                var i = {
                    width: t.outerWidth(!0),
                    height: t.outerHeight(!0),
                    "float": t.css("float")
                }, s = e("<div></div>").addClass("ui-effects-wrapper").css({
                    fontSize: "100%",
                    background: "transparent",
                    border: "none",
                    margin: 0,
                    padding: 0
                }), n = {width: t.width(), height: t.height()}, a = document.activeElement;
                try {
                    a.id
                } catch (o) {
                    a = document.body
                }
                return t.wrap(s), (t[0] === a || e.contains(t[0], a)) && e(a).focus(), s = t.parent(), "static" === t.css("position") ? (s.css({position: "relative"}), t.css({position: "relative"})) : (e.extend(i, {
                    position: t.css("position"),
                    zIndex: t.css("z-index")
                }), e.each(["top", "left", "bottom", "right"], function (e, s) {
                    i[s] = t.css(s), isNaN(parseInt(i[s], 10)) && (i[s] = "auto")
                }), t.css({
                    position: "relative",
                    top: 0,
                    left: 0,
                    right: "auto",
                    bottom: "auto"
                })), t.css(n), s.css(i).show()
            }, removeWrapper: function (t) {
                var i = document.activeElement;
                return t.parent().is(".ui-effects-wrapper") && (t.parent().replaceWith(t), (t[0] === i || e.contains(t[0], i)) && e(i).focus()), t
            }, setTransition: function (t, i, s, n) {
                return n = n || {}, e.each(i, function (e, i) {
                    var a = t.cssUnit(i);
                    a[0] > 0 && (n[i] = a[0] * s + a[1])
                }), n
            }
        }), e.fn.extend({
            effect: function () {
                function t(t) {
                    function i() {
                        e.isFunction(a) && a.call(n[0]), e.isFunction(t) && t()
                    }

                    var n = e(this), a = s.complete, o = s.mode;
                    (n.is(":hidden") ? "hide" === o : "show" === o) ? i() : r.call(n[0], s, i)
                }

                var s = n.apply(this, arguments), a = s.mode, o = s.queue, r = e.effects.effect[s.effect], h = !r && i && e.effects[s.effect];
                return e.fx.off || !r && !h ? a ? this[a](s.duration, s.complete) : this.each(function () {
                    s.complete && s.complete.call(this)
                }) : r ? o === !1 ? this.each(t) : this.queue(o || "fx", t) : h.call(this, {
                    options: s,
                    duration: s.duration,
                    callback: s.complete,
                    mode: s.mode
                })
            }, _show: e.fn.show, show: function (e) {
                if (a(e))return this._show.apply(this, arguments);
                var t = n.apply(this, arguments);
                return t.mode = "show", this.effect.call(this, t)
            }, _hide: e.fn.hide, hide: function (e) {
                if (a(e))return this._hide.apply(this, arguments);
                var t = n.apply(this, arguments);
                return t.mode = "hide", this.effect.call(this, t)
            }, __toggle: e.fn.toggle, toggle: function (t) {
                if (a(t) || "boolean" == typeof t || e.isFunction(t))return this.__toggle.apply(this, arguments);
                var i = n.apply(this, arguments);
                return i.mode = "toggle", this.effect.call(this, i)
            }, cssUnit: function (t) {
                var i = this.css(t), s = [];
                return e.each(["em", "px", "%", "pt"], function (e, t) {
                    i.indexOf(t) > 0 && (s = [parseFloat(i), t])
                }), s
            }
        })
    }(), function () {
        var t = {};
        e.each(["Quad", "Cubic", "Quart", "Quint", "Expo"], function (e, i) {
            t[i] = function (t) {
                return Math.pow(t, e + 2)
            }
        }), e.extend(t, {
            Sine: function (e) {
                return 1 - Math.cos(e * Math.PI / 2)
            }, Circ: function (e) {
                return 1 - Math.sqrt(1 - e * e)
            }, Elastic: function (e) {
                return 0 === e || 1 === e ? e : -Math.pow(2, 8 * (e - 1)) * Math.sin((80 * (e - 1) - 7.5) * Math.PI / 15)
            }, Back: function (e) {
                return e * e * (3 * e - 2)
            }, Bounce: function (e) {
                for (var t, i = 4; ((t = Math.pow(2, --i)) - 1) / 11 > e;);
                return 1 / Math.pow(4, 3 - i) - 7.5625 * Math.pow((3 * t - 2) / 22 - e, 2)
            }
        }), e.each(t, function (t, i) {
            e.easing["easeIn" + t] = i, e.easing["easeOut" + t] = function (e) {
                return 1 - i(1 - e)
            }, e.easing["easeInOut" + t] = function (e) {
                return .5 > e ? i(2 * e) / 2 : 1 - i(-2 * e + 2) / 2
            }
        })
    }()
}(jQuery);
(function (e) {
    var t = /up|down|vertical/, i = /up|left|vertical|horizontal/;
    e.effects.effect.blind = function (s, n) {
        var a, o, r, l = e(this), h = ["position", "top", "bottom", "left", "right", "height", "width"], u = e.effects.setMode(l, s.mode || "hide"), d = s.direction || "up", c = t.test(d), p = c ? "height" : "width", f = c ? "top" : "left", m = i.test(d), g = {}, v = "show" === u;
        l.parent().is(".ui-effects-wrapper") ? e.effects.save(l.parent(), h) : e.effects.save(l, h), l.show(), a = e.effects.createWrapper(l).css({overflow: "hidden"}), o = a[p](), r = parseFloat(a.css(f)) || 0, g[p] = v ? o : 0, m || (l.css(c ? "bottom" : "right", 0).css(c ? "top" : "left", "auto").css({position: "absolute"}), g[f] = v ? r : o + r), v && (a.css(p, 0), m || a.css(f, r + o)), a.animate(g, {
            duration: s.duration,
            easing: s.easing,
            queue: !1,
            complete: function () {
                "hide" === u && l.hide(), e.effects.restore(l, h), e.effects.removeWrapper(l), n()
            }
        })
    }
})(jQuery);
(function (e) {
    e.effects.effect.bounce = function (t, i) {
        var s, n, a, o = e(this), r = ["position", "top", "bottom", "left", "right", "height", "width"], l = e.effects.setMode(o, t.mode || "effect"), h = "hide" === l, u = "show" === l, d = t.direction || "up", c = t.distance, p = t.times || 5, f = 2 * p + (u || h ? 1 : 0), m = t.duration / f, g = t.easing, v = "up" === d || "down" === d ? "top" : "left", y = "up" === d || "left" === d, b = o.queue(), _ = b.length;
        for ((u || h) && r.push("opacity"), e.effects.save(o, r), o.show(), e.effects.createWrapper(o), c || (c = o["top" === v ? "outerHeight" : "outerWidth"]() / 3), u && (a = {opacity: 1}, a[v] = 0, o.css("opacity", 0).css(v, y ? 2 * -c : 2 * c).animate(a, m, g)), h && (c /= Math.pow(2, p - 1)), a = {}, a[v] = 0, s = 0; p > s; s++)n = {}, n[v] = (y ? "-=" : "+=") + c, o.animate(n, m, g).animate(a, m, g), c = h ? 2 * c : c / 2;
        h && (n = {opacity: 0}, n[v] = (y ? "-=" : "+=") + c, o.animate(n, m, g)), o.queue(function () {
            h && o.hide(), e.effects.restore(o, r), e.effects.removeWrapper(o), i()
        }), _ > 1 && b.splice.apply(b, [1, 0].concat(b.splice(_, f + 1))), o.dequeue()
    }
})(jQuery);
(function (e) {
    e.effects.effect.clip = function (t, i) {
        var s, n, a, o = e(this), r = ["position", "top", "bottom", "left", "right", "height", "width"], l = e.effects.setMode(o, t.mode || "hide"), h = "show" === l, u = t.direction || "vertical", d = "vertical" === u, c = d ? "height" : "width", p = d ? "top" : "left", f = {};
        e.effects.save(o, r), o.show(), s = e.effects.createWrapper(o).css({overflow: "hidden"}), n = "IMG" === o[0].tagName ? s : o, a = n[c](), h && (n.css(c, 0), n.css(p, a / 2)), f[c] = h ? a : 0, f[p] = h ? 0 : a / 2, n.animate(f, {
            queue: !1,
            duration: t.duration,
            easing: t.easing,
            complete: function () {
                h || o.hide(), e.effects.restore(o, r), e.effects.removeWrapper(o), i()
            }
        })
    }
})(jQuery);
(function (e) {
    e.effects.effect.drop = function (t, i) {
        var s, n = e(this), a = ["position", "top", "bottom", "left", "right", "opacity", "height", "width"], o = e.effects.setMode(n, t.mode || "hide"), r = "show" === o, l = t.direction || "left", h = "up" === l || "down" === l ? "top" : "left", u = "up" === l || "left" === l ? "pos" : "neg", d = {opacity: r ? 1 : 0};
        e.effects.save(n, a), n.show(), e.effects.createWrapper(n), s = t.distance || n["top" === h ? "outerHeight" : "outerWidth"](!0) / 2, r && n.css("opacity", 0).css(h, "pos" === u ? -s : s), d[h] = (r ? "pos" === u ? "+=" : "-=" : "pos" === u ? "-=" : "+=") + s, n.animate(d, {
            queue: !1,
            duration: t.duration,
            easing: t.easing,
            complete: function () {
                "hide" === o && n.hide(), e.effects.restore(n, a), e.effects.removeWrapper(n), i()
            }
        })
    }
})(jQuery);
(function (e) {
    e.effects.effect.explode = function (t, i) {
        function s() {
            b.push(this), b.length === d * c && n()
        }

        function n() {
            p.css({visibility: "visible"}), e(b).remove(), m || p.hide(), i()
        }

        var a, o, r, l, h, u, d = t.pieces ? Math.round(Math.sqrt(t.pieces)) : 3, c = d, p = e(this), f = e.effects.setMode(p, t.mode || "hide"), m = "show" === f, g = p.show().css("visibility", "hidden").offset(), v = Math.ceil(p.outerWidth() / c), y = Math.ceil(p.outerHeight() / d), b = [];
        for (a = 0; d > a; a++)for (l = g.top + a * y, u = a - (d - 1) / 2, o = 0; c > o; o++)r = g.left + o * v, h = o - (c - 1) / 2, p.clone().appendTo("body").wrap("<div></div>").css({
            position: "absolute",
            visibility: "visible",
            left: -o * v,
            top: -a * y
        }).parent().addClass("ui-effects-explode").css({
            position: "absolute",
            overflow: "hidden",
            width: v,
            height: y,
            left: r + (m ? h * v : 0),
            top: l + (m ? u * y : 0),
            opacity: m ? 0 : 1
        }).animate({
            left: r + (m ? 0 : h * v),
            top: l + (m ? 0 : u * y),
            opacity: m ? 1 : 0
        }, t.duration || 500, t.easing, s)
    }
})(jQuery);
(function (e) {
    e.effects.effect.fade = function (t, i) {
        var s = e(this), n = e.effects.setMode(s, t.mode || "toggle");
        s.animate({opacity: n}, {queue: !1, duration: t.duration, easing: t.easing, complete: i})
    }
})(jQuery);
(function (e) {
    e.effects.effect.fold = function (t, i) {
        var s, n, a = e(this), o = ["position", "top", "bottom", "left", "right", "height", "width"], r = e.effects.setMode(a, t.mode || "hide"), l = "show" === r, h = "hide" === r, u = t.size || 15, d = /([0-9]+)%/.exec(u), c = !!t.horizFirst, p = l !== c, f = p ? ["width", "height"] : ["height", "width"], m = t.duration / 2, g = {}, v = {};
        e.effects.save(a, o), a.show(), s = e.effects.createWrapper(a).css({overflow: "hidden"}), n = p ? [s.width(), s.height()] : [s.height(), s.width()], d && (u = parseInt(d[1], 10) / 100 * n[h ? 0 : 1]), l && s.css(c ? {
            height: 0,
            width: u
        } : {
            height: u,
            width: 0
        }), g[f[0]] = l ? n[0] : u, v[f[1]] = l ? n[1] : 0, s.animate(g, m, t.easing).animate(v, m, t.easing, function () {
            h && a.hide(), e.effects.restore(a, o), e.effects.removeWrapper(a), i()
        })
    }
})(jQuery);
(function (e) {
    e.effects.effect.highlight = function (t, i) {
        var s = e(this), n = ["backgroundImage", "backgroundColor", "opacity"], a = e.effects.setMode(s, t.mode || "show"), o = {backgroundColor: s.css("backgroundColor")};
        "hide" === a && (o.opacity = 0), e.effects.save(s, n), s.show().css({
            backgroundImage: "none",
            backgroundColor: t.color || "#ffff99"
        }).animate(o, {
            queue: !1, duration: t.duration, easing: t.easing, complete: function () {
                "hide" === a && s.hide(), e.effects.restore(s, n), i()
            }
        })
    }
})(jQuery);
(function (e) {
    e.effects.effect.pulsate = function (t, i) {
        var s, n = e(this), a = e.effects.setMode(n, t.mode || "show"), o = "show" === a, r = "hide" === a, l = o || "hide" === a, h = 2 * (t.times || 5) + (l ? 1 : 0), u = t.duration / h, d = 0, c = n.queue(), p = c.length;
        for ((o || !n.is(":visible")) && (n.css("opacity", 0).show(), d = 1), s = 1; h > s; s++)n.animate({opacity: d}, u, t.easing), d = 1 - d;
        n.animate({opacity: d}, u, t.easing), n.queue(function () {
            r && n.hide(), i()
        }), p > 1 && c.splice.apply(c, [1, 0].concat(c.splice(p, h + 1))), n.dequeue()
    }
})(jQuery);
(function (e) {
    e.effects.effect.puff = function (t, i) {
        var s = e(this), n = e.effects.setMode(s, t.mode || "hide"), a = "hide" === n, o = parseInt(t.percent, 10) || 150, r = o / 100, h = {
            height: s.height(),
            width: s.width(),
            outerHeight: s.outerHeight(),
            outerWidth: s.outerWidth()
        };
        e.extend(t, {
            effect: "scale",
            queue: !1,
            fade: !0,
            mode: n,
            complete: i,
            percent: a ? o : 100,
            from: a ? h : {
                height: h.height * r,
                width: h.width * r,
                outerHeight: h.outerHeight * r,
                outerWidth: h.outerWidth * r
            }
        }), s.effect(t)
    }, e.effects.effect.scale = function (t, i) {
        var s = e(this), n = e.extend(!0, {}, t), a = e.effects.setMode(s, t.mode || "effect"), o = parseInt(t.percent, 10) || (0 === parseInt(t.percent, 10) ? 0 : "hide" === a ? 0 : 100), r = t.direction || "both", h = t.origin, l = {
            height: s.height(),
            width: s.width(),
            outerHeight: s.outerHeight(),
            outerWidth: s.outerWidth()
        }, u = {y: "horizontal" !== r ? o / 100 : 1, x: "vertical" !== r ? o / 100 : 1};
        n.effect = "size", n.queue = !1, n.complete = i, "effect" !== a && (n.origin = h || ["middle", "center"], n.restore = !0), n.from = t.from || ("show" === a ? {
                height: 0,
                width: 0,
                outerHeight: 0,
                outerWidth: 0
            } : l), n.to = {
            height: l.height * u.y,
            width: l.width * u.x,
            outerHeight: l.outerHeight * u.y,
            outerWidth: l.outerWidth * u.x
        }, n.fade && ("show" === a && (n.from.opacity = 0, n.to.opacity = 1), "hide" === a && (n.from.opacity = 1, n.to.opacity = 0)), s.effect(n)
    }, e.effects.effect.size = function (t, i) {
        var s, n, a, o = e(this), r = ["position", "top", "bottom", "left", "right", "width", "height", "overflow", "opacity"], h = ["position", "top", "bottom", "left", "right", "overflow", "opacity"], l = ["width", "height", "overflow"], u = ["fontSize"], d = ["borderTopWidth", "borderBottomWidth", "paddingTop", "paddingBottom"], c = ["borderLeftWidth", "borderRightWidth", "paddingLeft", "paddingRight"], p = e.effects.setMode(o, t.mode || "effect"), f = t.restore || "effect" !== p, m = t.scale || "both", g = t.origin || ["middle", "center"], v = o.css("position"), y = f ? r : h, b = {
            height: 0,
            width: 0,
            outerHeight: 0,
            outerWidth: 0
        };
        "show" === p && o.show(), s = {
            height: o.height(),
            width: o.width(),
            outerHeight: o.outerHeight(),
            outerWidth: o.outerWidth()
        }, "toggle" === t.mode && "show" === p ? (o.from = t.to || b, o.to = t.from || s) : (o.from = t.from || ("show" === p ? b : s), o.to = t.to || ("hide" === p ? b : s)), a = {
            from: {
                y: o.from.height / s.height,
                x: o.from.width / s.width
            }, to: {y: o.to.height / s.height, x: o.to.width / s.width}
        }, ("box" === m || "both" === m) && (a.from.y !== a.to.y && (y = y.concat(d), o.from = e.effects.setTransition(o, d, a.from.y, o.from), o.to = e.effects.setTransition(o, d, a.to.y, o.to)), a.from.x !== a.to.x && (y = y.concat(c), o.from = e.effects.setTransition(o, c, a.from.x, o.from), o.to = e.effects.setTransition(o, c, a.to.x, o.to))), ("content" === m || "both" === m) && a.from.y !== a.to.y && (y = y.concat(u).concat(l), o.from = e.effects.setTransition(o, u, a.from.y, o.from), o.to = e.effects.setTransition(o, u, a.to.y, o.to)), e.effects.save(o, y), o.show(), e.effects.createWrapper(o), o.css("overflow", "hidden").css(o.from), g && (n = e.effects.getBaseline(g, s), o.from.top = (s.outerHeight - o.outerHeight()) * n.y, o.from.left = (s.outerWidth - o.outerWidth()) * n.x, o.to.top = (s.outerHeight - o.to.outerHeight) * n.y, o.to.left = (s.outerWidth - o.to.outerWidth) * n.x), o.css(o.from), ("content" === m || "both" === m) && (d = d.concat(["marginTop", "marginBottom"]).concat(u), c = c.concat(["marginLeft", "marginRight"]), l = r.concat(d).concat(c), o.find("*[width]").each(function () {
            var i = e(this), s = {
                height: i.height(),
                width: i.width(),
                outerHeight: i.outerHeight(),
                outerWidth: i.outerWidth()
            };
            f && e.effects.save(i, l), i.from = {
                height: s.height * a.from.y,
                width: s.width * a.from.x,
                outerHeight: s.outerHeight * a.from.y,
                outerWidth: s.outerWidth * a.from.x
            }, i.to = {
                height: s.height * a.to.y,
                width: s.width * a.to.x,
                outerHeight: s.height * a.to.y,
                outerWidth: s.width * a.to.x
            }, a.from.y !== a.to.y && (i.from = e.effects.setTransition(i, d, a.from.y, i.from), i.to = e.effects.setTransition(i, d, a.to.y, i.to)), a.from.x !== a.to.x && (i.from = e.effects.setTransition(i, c, a.from.x, i.from), i.to = e.effects.setTransition(i, c, a.to.x, i.to)), i.css(i.from), i.animate(i.to, t.duration, t.easing, function () {
                f && e.effects.restore(i, l)
            })
        })), o.animate(o.to, {
            queue: !1, duration: t.duration, easing: t.easing, complete: function () {
                0 === o.to.opacity && o.css("opacity", o.from.opacity), "hide" === p && o.hide(), e.effects.restore(o, y), f || ("static" === v ? o.css({
                    position: "relative",
                    top: o.to.top,
                    left: o.to.left
                }) : e.each(["top", "left"], function (e, t) {
                    o.css(t, function (t, i) {
                        var s = parseInt(i, 10), n = e ? o.to.left : o.to.top;
                        return "auto" === i ? n + "px" : s + n + "px"
                    })
                })), e.effects.removeWrapper(o), i()
            }
        })
    }
})(jQuery);
(function (e) {
    e.effects.effect.shake = function (t, i) {
        var s, n = e(this), a = ["position", "top", "bottom", "left", "right", "height", "width"], o = e.effects.setMode(n, t.mode || "effect"), r = t.direction || "left", h = t.distance || 20, l = t.times || 3, u = 2 * l + 1, d = Math.round(t.duration / u), c = "up" === r || "down" === r ? "top" : "left", p = "up" === r || "left" === r, f = {}, m = {}, g = {}, v = n.queue(), y = v.length;
        for (e.effects.save(n, a), n.show(), e.effects.createWrapper(n), f[c] = (p ? "-=" : "+=") + h, m[c] = (p ? "+=" : "-=") + 2 * h, g[c] = (p ? "-=" : "+=") + 2 * h, n.animate(f, d, t.easing), s = 1; l > s; s++)n.animate(m, d, t.easing).animate(g, d, t.easing);
        n.animate(m, d, t.easing).animate(f, d / 2, t.easing).queue(function () {
            "hide" === o && n.hide(), e.effects.restore(n, a), e.effects.removeWrapper(n), i()
        }), y > 1 && v.splice.apply(v, [1, 0].concat(v.splice(y, u + 1))), n.dequeue()
    }
})(jQuery);
(function (e) {
    e.effects.effect.slide = function (t, i) {
        var s, n = e(this), a = ["position", "top", "bottom", "left", "right", "width", "height"], o = e.effects.setMode(n, t.mode || "show"), r = "show" === o, h = t.direction || "left", l = "up" === h || "down" === h ? "top" : "left", u = "up" === h || "left" === h, d = {};
        e.effects.save(n, a), n.show(), s = t.distance || n["top" === l ? "outerHeight" : "outerWidth"](!0), e.effects.createWrapper(n).css({overflow: "hidden"}), r && n.css(l, u ? isNaN(s) ? "-" + s : -s : s), d[l] = (r ? u ? "+=" : "-=" : u ? "-=" : "+=") + s, n.animate(d, {
            queue: !1,
            duration: t.duration,
            easing: t.easing,
            complete: function () {
                "hide" === o && n.hide(), e.effects.restore(n, a), e.effects.removeWrapper(n), i()
            }
        })
    }
})(jQuery);
(function (e) {
    e.effects.effect.transfer = function (t, i) {
        var s = e(this), n = e(t.to), a = "fixed" === n.css("position"), o = e("body"), r = a ? o.scrollTop() : 0, h = a ? o.scrollLeft() : 0, l = n.offset(), u = {
            top: l.top - r,
            left: l.left - h,
            height: n.innerHeight(),
            width: n.innerWidth()
        }, d = s.offset(), c = e('<div class="ui-effects-transfer"></div>').appendTo(document.body).addClass(t.className).css({
            top: d.top - r,
            left: d.left - h,
            height: s.innerHeight(),
            width: s.innerWidth(),
            position: a ? "fixed" : "absolute"
        }).animate(u, t.duration, t.easing, function () {
            c.remove(), i()
        })
    }
})(jQuery);
;