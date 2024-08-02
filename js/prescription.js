$(document).ready(function () {
    Date.prototype.calcDate = function (days) {
        let date = new Date(this.valueOf());
        date.setDate(date.getDate() + days);
        return `Until ${date.getUTCDate()}-${date.getUTCMonth() + 1}-${date.getUTCFullYear()}`;
    };

    let timeout;

    function snackSaving() {
        let snack = document.getElementById("snacking");
        snack.className = "show";
        timeout = setTimeout(() => {
            alert("ERR: Connection timeout.");
        }, 8000);
    }

    function snackSaved() {
        clearTimeout(timeout);
        let snack = document.getElementById("snacking");
        snack.className = snack.className.replace("show", "");
        let snacked = document.getElementById("snacked");
        snacked.className = "show";
        setTimeout(function () {
            snacked.className = snacked.className.replace("show", "");
        }, 1500);
    }

    $("[data-toggle=tooltip]").tooltip("show");
    setTimeout(function () {
        $("[data-toggle=tooltip]").tooltip("hide");
    }, 5000); //hide tooltips after 5sec

    $(document).keyup(function () {
        $("[data-toggle=tooltip]").tooltip("hide");
    }); //hide tooltip while typing

    $(document).on("focusin keypress", ".med_name", function (e) {
        let x = $(this).siblings("div.med_name_action");
        if (e.type == "focusin") {
            x.css("display", "block");
        }
        if (e.type == "keypress") {
            if (e.keyCode == 13) x.children("button.save").click();
        }
    });

    $(document).on("click", ".cancel-btn", function () {
        $(this).parent().css("display", "none"); //hides save/cancel btn
    });

    $(document).on("click", ".med_name_action button.save", function (event) {
        event.preventDefault();
        // Your save functionality code here
        $(this).parent().css("display", "none");
        $(".sc_time").removeClass("folded");
    });

    $(".med_name").keypress(function (e) {
        if (e.which == 13) {
            $("#symp_save").click();
        }
    });

    $(document).on("mousedown", ".sc", function (e) {
        let x = $(this).siblings("div.med_when_action");
        x.css("display", "block");
    });

    $(document).on("click", ".med_when_action button.save", function (event) {
        event.preventDefault();
        $(this).parent().css("display", "none");
        $(".select").removeClass("folded");
    });

    $("select.sc").change(function () {
        let x = $(this).siblings("div.med_when_action");
        x.css("display", "none");
    });

    $(document).on("mousedown", ".meal", function () {
        let x = $(this).siblings("div.med_meal_action");
        x.css("display", "block");
    });

    $(document).on("click", ".med_meal_action button.save", function (event) {
        event.preventDefault();
        $(this).parent().css("display", "none");
        $(".period").removeClass("folded");
    });

    $(document).on("focusin keypress", ".med_period", function (e) {
        let x = $(this).siblings("div.med_period_action");
        if (e.type == "focusin") {
            x.css("display", "block");
        }
        if (e.type == "keypress") {
            if (e.keyCode == 13) x.children("button.save").click();
        }
    });

    $(document).on("click", ".med_period_action button.save", function (event) {
        event.preventDefault();
        $(this).parent().css("display", "none");
    });

    $(document).on("keyup", ".med_period", function () {
        let period = $(this).val();
        let num = +period.match(/\d+/g)[0];
        let type = period.match(/\b(\w)/g)[1];
        let days = null;
        if (type == "d") days = num;
        else if (type == "w") days = num * 7;
        else if (type == "m") days = num * 30;
        else if (type == "y") days = num * 365;
        let span = $(this).siblings("span.date");
        if (days) {
            let date = new Date().calcDate(days);
            span.html(date);
            // Update med_period value with date
            $(this).data("med_period", date);
        } else {
            span.html("(Invalid time period)");
        }
    });

    $(".sc").keyup(function (e) {
        if (isNaN(e.key)) return;
        let el = $(this);
        el = el.val().split("-").join("");
        let finalVal = el.match(/.{1,1}/g).join("-");
        $(this).val(finalVal);
    });

    $(document).on("click", ".delete", function () {
        let parent = $(this).closest(".med");
        parent.remove();
    });

    let med_id = 1;

    $("#add_med").click(function () {
        med_id++;
        let sourceTemplate = $("#new_medicine").html();
        Mustache.parse(sourceTemplate);
        let sourceHTML = Mustache.render(sourceTemplate, {
            med_id
        });
        let medicine = $(".med_list");
        medicine.append(sourceHTML);
    });

    // Collect medication data before form submission
    $("#PatientInfo").submit(function () {
        let medications = [];

        $(".med_list .med").each(function () {
            let med_name = $(this).find(".med_name").val();
            let sc_time = $(this).find(".sc").val();
            let meal = $(this).find(".meal").val();
            // Get med_period value from data attribute
            let med_period = $(this).find(".med_period").data("med_period");

            medications.push({
                med_name: med_name,
                sc_time: sc_time,
                meal: meal,
                med_period: med_period
            });
        });

        // Convert medications array to JSON and assign to hidden input field
        $("#Medications").val(JSON.stringify(medications));
    });
});
