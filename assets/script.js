$(document).ready(function () {
    "use strict";
    var a = {
        init: function () {
            this.cacheDom(),
                this.bindEvents(),
                this.setResizeOptions()
        },

        cacheDom: function () {
            this.$document = $(document),
                this.$window = $(window),
                this.$html = $("html"),
                this.$body = $("body"),
                this.$html_body = $("html, body"),
                this.$solid_menus = $(".solid-menus"),
                this.$navbar = this.$body.find("#sp-menu-left .sp-column"),
                this.$tab_nav = this.$body.find(".tab-nav"),
                this.$tab_nav_links = this.$body.find(".tab-nav > li > a"),
                this.$navbar_nav = this.$body.find(".navbar-nav"),
                this.$navbar_header = this.$body.find(".navbar-header"),
                this.$dropdown = this.$body.find(".dropdown"),
                this.$dropdown_menu = this.$body.find(".dropdown-menu"),
                this.$a_w_title = this.$body.find("a[data-title]")
        },

        bindEvents: function () {
            this.$body.on("click mouseenter", ".tab-nav > li > a", this.setActiveTab),
                /*this.$body.on("click mouseenter mouseleave", "navbar-hover .dropdown", this.showDropDown),
                this.$body.on("click", "navbar-hover .dropdown", this.showDropDown),*/
                this.$body.on("click mouseenter mouseleave", ".dropdown", this.showDropDown),
                this.$body.on("click mouseenter", ".dropdown.dropdown-convey-width", this.conveyWidth),
                this.$body.on("click mouseenter", ".dropdown.dropdown-convey-width", this.conveyHeight),
                this.$document.on("click", ".navbar .dropdown-menu", this.stopPropagation),
                this.$window.on("resize", this.setResizeOptions.bind(this)),
                this.$document.on("click", "html", this.clickOutsideNav),
                this.$body.on("click", ".prev-default", function (a) {a.preventDefault()})
        },

        stopPropagation: function (a) {
            a.stopPropagation()
        },

        setActiveTab: function (a) {
            var b = $(this), c = b.attr("data-tabs"), d = b.parent(), e = d.siblings(),
                f = b.parents(".tab-nav").siblings(".tab-container"), g = f.children("div#" + c);
            "mouseenter" == a.type && b.parents(".tab-nav").hasClass("tab-nav-hover") ? (d.addClass("ui-tabs-active"), e.removeClass("ui-tabs-active"), g.addClass("l-block").siblings().removeClass("l-block")) : "click" == a.type && (d.addClass("ui-tabs-active"), e.removeClass("ui-tabs-active"), g.addClass("l-block").siblings().removeClass("l-block"))
        },

        showDropDown: function (b) {
            var c = $(this);
            "mouseenter" == b.type && a.$navbar.hasClass("navbar-hover") ? a.setDropAnimation(c) : "click" == b.type && a.$navbar.hasClass("navbar-click")  ? a.setDropAnimation(c) : "mouseleave" == b.type && a.$navbar.hasClass("navbar-hover") && (a.$dropdown.removeClass("open"))
        },

        conveyWidth: function (a) {
            var b = $(this),
                c = b.find(".dropdown-menu"),
                d = b.closest(".container-inner").outerWidth(),
                width = window.innerWidth;
                c.css("width", d);
        },

        conveyHeight: function (a) {
            var b = $(this),
                c = b.children(".dropdown-menu"),
                d = b.find(".tab-container"),
                e = window.innerHeight * 90 / 100;
            window.innerWidth > 767 ? d.css("max-height", e) && d.css("overflow", "auto") : c.css("height", "98vh") && c.css("overflow", "auto")
        },

        setResizeOptions: function () {
            window.innerWidth <= 767 ? (
                this.removeBlockSettings(),
                    this.conveyWidth(),
                    this.$navbar.removeClass("navbar-hover").addClass("navbar-click"),
                    this.$tab_nav.removeClass("tab-nav-hover"),
                    this.$tab_nav_links.removeClass("prev-default")) : (
                this.navOptions(),
                    this.tabOptions(),
                    this.$tab_nav_links.addClass("prev-default") /*this.calculateDistance(),*/
                    //this.normalizeNav()
            ),
                window.innerWidth <= 991 && window.innerWidth > 767 ? this.activateTitle() : this.$a_w_title.removeAttr("title")
        },

        navOptions: function () {
            this.$navbar.removeClass("navbar-click").addClass("navbar-hover")
        },

        tabOptions: function () {
            this.$tab_nav.addClass("tab-nav-hover")
        },

        clickOutsideNav: function (b) {
            0 == a.removeBlockSettings()
        },

        removeBlockSettings: function () {
            a.$dropdown_menu.removeClass("l-block")
        },

        setDropAnimation: function (a) {
            var b = a.attr("data-animation") ? a.attr("data-animation") : "fadeIn",
                c = a.find(".dropdown-menu").first();
            c.removeClass("animated").addClass("animated " + b)
        },

        activateTitle: function () {
            this.$a_w_title.length > 0 && this.$a_w_title.each(function () {
                var a = $(this), b = a.data("title");
                a.attr("title", b)
            })
        }

    };
    a.init()

});