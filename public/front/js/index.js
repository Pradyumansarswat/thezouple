$(document).ready(function () {
    $("#wait").hide(), $(function () {
        var e = $(".fixedTopMenu");
        $(window).scroll(function () {
            $(window).scrollTop() <= 50 ? e.removeClass("navbar-scroll") : e.addClass("navbar-scroll")
        })
    }), $(function () {
        var e = $(".scrSys");
        $(window).scroll(function () {
            $(window).scrollTop() <= 400 ? e.removeClass("stickbtnposition-fixed") : e.addClass("stickbtnposition-fixed")
        })
    }), $(".btn-num-product-down").on("click", function (e) {
        e.preventDefault();
        var n = Number($(this).next().val());
        1 < n && $(this).next().val(n - 1)
    }), $(".btn-num-product-up").on("click", function (e) {
        e.preventDefault();
        var n = Number($(this).prev().val());
        $(this).prev().val(n + 1)
    }), $(".menuBtn, .closeBtn").click(function () {
        $(".meunTab").toggleClass("active")
    }), $(".userBtn, .closeBtn2").click(function () {
        $(".userTab").toggleClass("active")
    }), $(".searchBtn, .closeBtn3").click(function () {
        $(".searchContent").toggleClass("active")
    }), $(".filterBtn").click(function () {
        $(".filterSm").toggleClass("active")
    }), $(".canFltr").click(function () {
        $(".filterSm").toggleClass("active")
    }), $(".forgatPass").click(function () {
        $(".forgot").show(), $(".log_fm").hide()
    }), $(".logIN").click(function () {
        $(".log_fm").show(), $(".forgot").hide()
    }), $(".signUP").click(function () {
        $(".signUP").addClass("active"), $(".signIn").removeClass("active")
    }), $(".signIn").click(function () {
        $(".signIn").toggleClass("active"), $(".signUP").toggleClass("active")
    })
}), $(function () {
    $("#slider-range").slider({
        range: !0,
        min: 0,
        max: 20000,
        values: [0, 20000],
        slide: function (e, n) {
            $("#amount").val("" + n.values[0] + " - " + n.values[1])
        }
    }), $("#amount").val("" + $("#slider-range").slider("values", 0) + " - " + $("#slider-range").slider("values", 1))
});
var currentTab = 0;

function showTab(e) {
    var n = document.getElementsByClassName("tab");
    n[e].style.display = "block", 0 == e ? (document.getElementById("prevBtn").style.display = "none", document.getElementById("nextBtn").style.display = "inline") : (document.getElementById("prevBtn").style.display = "inline", document.getElementById("nextBtn").style.display = "none"), e == n.length - 1 ? (document.getElementById("nextBtn").innerHTML = "Pay now", document.getElementById("nextBtn").style.display = "none") : document.getElementById("nextBtn").innerHTML = "Continue", fixStepIndicator(e)
}

function nextPrev(e) {
    var n = document.getElementsByClassName("tab");
    return !(1 == e && !validateForm()) && (n[currentTab].style.display = "none", (currentTab += e) >= n.length ? (document.getElementById("regForm").submit(), !1) : void showTab(currentTab))
}

function validateForm() {
    var e, n, t = !0;
    for (e = document.getElementsByClassName("tab")[currentTab].getElementsByTagName("input"), n = 0; n < e.length; n++) "" == e[n].value && (e[n].className += " invalid", t = !1);
    return t && (document.getElementsByClassName("step")[currentTab].className += " finish"), t
}

function fixStepIndicator(e) {
    var n, t = document.getElementsByClassName("step");
    for (n = 0; n < t.length; n++) t[n].className = t[n].className.replace(" active", "");
    t[e].className += " active"
}
showTab(currentTab);
