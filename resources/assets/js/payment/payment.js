let paymentTableName = "";

document.addEventListener("turbo:load", loadPayment);

function loadPayment() {
    initializeSelect2Payment();
    paymentDateFilter();
    paymentTableName = "#tblPayments";
}

function initializeSelect2Payment() {
    if (!select2NotExists("#invoice_id")) {
        return false;
    }
    if ($("#invoice_id").hasClass("select2-hidden-accessible")) {
        $("#invoice_id .select2-container").remove();
    }

    $("#invoice_id").select2({
        dropdownParent: $("#paymentModal"),
    });
}

listenClick(".payment-delete-btn", function (event) {
    let id = $(event.currentTarget).attr("data-id");
    deleteItem(
        route("payments.destroy", id),
        "Payments",
        Lang.get("messages.invoice.payment")
    );
});

listenClick(".addPayment", function () {
    let currentDtFormat = currentDateFormat;
    $.ajax({
        url: route("get-current-date-format"),
        type: "get",
        success: function (data) {
            currentDtFormat = data;
            $("#payment_date").flatpickr({
                defaultDate: new Date(),
                dateFormat: data,
                maxDate: new Date(),
                locale: getUserLanguages,
            });
            $("#paymentModal").appendTo("body").modal("show");
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });

    setTimeout(function () {
        $("#invoice_id").select2({
            dropdownParent: $("#paymentModal"),
        });
    }, 200);
});

listenHiddenBsModal("#paymentModal", function () {
    $("#adminPaymentInvoiceId").val(null).trigger("change");
    resetModalForm("#paymentForm");
});

listenSubmit("#paymentForm", function (e) {
    e.preventDefault();

    if ($("#payment_note").val().trim().length == 0) {
        displayErrorMessage("Note field is Required");
        return false;
    }

    let btnSubmitEle = $(this).find("#btnPay");
    setAdminBtnLoader(btnSubmitEle);
    $.ajax({
        url: route("payments.store"),
        type: "POST",
        data: $(this).serialize(),
        success: function (result) {
            if (result.success) {
                $("#paymentModal").modal("hide");
                displaySuccessMessage(result.message);
                window.livewire.emit("refreshDatatable");
                window.livewire.emit("resetPageTable");
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            setAdminBtnLoader(btnSubmitEle);
        },
    });
});

listenChange(".invoice", function () {
    let invoiceId = $(this).val();
    if (isEmpty(invoiceId)) {
        $("#due_amount").val(0);
        $("#paid_amount").val(0);
        return false;
    }
    $.ajax({
        url: route("payments.get-invoiceAmount", invoiceId),
        type: "get",
        dataType: "json",
        success: function (result) {
            if (result.success) {
                $(".invoice-currency-code").text(result.data.currencyCode);
                $("#due_amount").val(number_format(result.data.totalDueAmount));
                $("#paid_amount").val(
                    number_format(result.data.totalPaidAmount)
                );
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
});

listenClick(".payment-edit-btn", function (event) {
    let paymentId = $(event.currentTarget).attr("data-id");
    paymentRenderData(paymentId);
});

function paymentRenderData(paymentId) {
    $.ajax({
        url: route("payments.edit", paymentId),
        type: "GET",
        beforeSend: function () {
            startLoader();
        },
        success: function (result) {
            if (result.success) {
                $("#edit_invoice_id").val(result.data.invoice.invoice_id);
                $("#edit_amount").val(result.data.amount);
                $("#edit_payment_date").flatpickr({
                    defaultDate: result.data.payment_date,
                    dateFormat: currentDateFormat,
                    maxDate: new Date(),
                    locale: getUserLanguages,
                });
                $(".edit-invoice-currency-code").text(result.data.currencyCode);
                $("#edit_payment_note").val(result.data.notes);
                $("#paymentId").val(result.data.id);
                $("#transactionId").val(result.data.payment_id);
                $("#invoice").val(result.data.invoice_id);
                $("#totalDue_amount").val(
                    number_format(
                        result.data.DueAmount.original.data.totalDueAmount
                    )
                );
                $("#totalPaid_amount").val(
                    number_format(
                        result.data.DueAmount.original.data.totalPaidAmount
                    )
                );
                $("#editPaymentModal").appendTo("body").modal("show");
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            stopLoader();
        },
    });
}

listenSubmit("#editPaymentForm", function (event) {
    event.preventDefault();

    if ($("#edit_payment_note").val().trim().length == 0) {
        displayErrorMessage("Note field is Required");
        return false;
    }

    const paymentId = $("#paymentId").val();
    $.ajax({
        url: route("payments.update", { payment: paymentId }),
        type: "put",
        data: $(this).serialize(),
        beforeSend: function () {
            startLoader();
        },
        success: function (result) {
            if (result.success) {
                displaySuccessMessage(result.message);
                livewire.emit("refreshDatatable");
                livewire.emit("resetPageTable");
                $("#editPaymentModal").modal("hide");
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            stopLoader();
        },
    });
});

listenChange(".transaction-approve", function () {
    let id = $(this).attr("data-id");
    let status = $(this).val();

    $.ajax({
        url: route("change-transaction-status", id),
        type: "GET",
        data: { id: id, status: status },
        success: function (result) {
            displaySuccessMessage(result.message);
            setTimeout(function () {
                Turbo.visit(route("transactions.index"));
            }, 1500);
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
});

window.paymentDateFilter = function () {
    var start = moment().startOf("month");
    var end = moment().endOf("month");

    if (!$("#paymentDateFilter").length) {
        return;
    }

    let dateRange = $("#paymentDateFilter");

    function cb(start, end) {
        window.livewire.emit(
            "dateFilter",
            start.format("MM/DD/YYYY") + " - " + end.format("MM/DD/YYYY")
        );
        $("#paymentDateFilter").val(
            start.format("MM/DD/YYYY") + " - " + end.format("MM/DD/YYYY")
        );
    }

    dateRange.daterangepicker(
        {
            startDate: start,
            endDate: end,
            showDropdowns: true,
            ranges: {
                Today: [moment(), moment()],
                Yesterday: [
                    moment().subtract(1, "days"),
                    moment().subtract(1, "days"),
                ],
                "Last 7 Days": [moment().subtract(6, "days"), moment()],
                "Last 30 Days": [moment().subtract(29, "days"), moment()],
                "This Month": [
                    moment().startOf("month"),
                    moment().endOf("month"),
                ],
                "Last Month": [
                    moment().subtract(1, "month").startOf("month"),
                    moment().subtract(1, "month").endOf("month"),
                ],
            },
        },
        cb
    );

    cb(start, end);

    dateRange.on("apply.daterangepicker", function (ev, picker) {
        isPickerApply = true;
        start = picker.startDate.format("YYYY-MM-DD");
        end = picker.endDate.format("YYYY-MM-DD");
        window.livewire.emit("dateFilter", start + " - " + end);
    });
};

listenClick("#adminPaymentExcelExport", function (e) {
    e.preventDefault();

    $.ajax({
        url: route("admin.paymentsExcel"),
        type: "GET",
        data: { date: $("#paymentDateFilter").val() },
        xhrFields: {
            responseType: "blob",
        },
        success: function (response) {
            let blob = new Blob([response]);
            let link = document.createElement("a");
            link.href = window.URL.createObjectURL(blob);
            link.download = "Payment-Excel" + ".xlsx";
            link.click();
        },
        error: function (blob) {
            console.log(blob);
        },
    });
});

listenClick("#adminPaymentPdfExport", function (e) {
    e.preventDefault();

    $.ajax({
        url: route("admin.payments.pdf"),
        type: "GET",
        data: { date: $("#paymentDateFilter").val() },
        xhrFields: {
            responseType: "blob",
        },
        success: function (response) {
            let blob = new Blob([response]);
            let link = document.createElement("a");
            link.href = window.URL.createObjectURL(blob);
            link.download = "payments" + ".pdf";
            link.click();
        },
        error: function (blob) {
            console.log(blob);
        },
    });
});
