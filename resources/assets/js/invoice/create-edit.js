let discountType = null;
let momentFormat = "";

document.addEventListener("turbo:load", loadCreateEditInvoice);

function loadCreateEditInvoice() {
    $('input:text:not([readonly="readonly"])').first().blur();
    initializeSelect2CreateEditInvoice();
    loadSelect2ClientData();
    loadRecurringTextBox();

    $("#addNote").hide();
    $("#removeNote").show();
    $("#noteAdd").show();
    $("#termRemove").show();
    $("#note").val("This is a default note.");
    $("#term").val("These are default terms and conditions.");

    currentDateFormat = $('meta[name="current-date-format"]').attr("content");
    momentFormat = convertToMomentFormat(currentDateFormat);
    prepareDatePickers();

    // if ($("#invoiceNote").val() == true || $("#invoiceTerm").val() == true) {
    //     $("#addNote").hide();
    //     $("#removeNote").show();
    //     $("#noteAdd").show();
    //     $("#termRemove").show();
    // } else {
    //     $("#removeNote").hide();
    //     $("#noteAdd").hide();
    //     $("#termRemove").hide();
    // }
    if ($("#invoiceRecurring").val() == true) {
        $(".recurring").show();
    } else {
        $(".recurring").hide();
    }
    if ($("#formData_recurring-1").prop("checked")) {
        $(".recurring").hide();
    }
    if ($("#discountType").val() != 0) {
        $("#discount").removeAttr("disabled");
    } else {
        $("#discount").attr("disabled", "disabled");
    }
    calculateFinalAmount();
}
function loadSelect2ClientData() {
    if (!$("#client_id").length && !$("#discountType").length) {
        return;
    }

    $("#client_id,#status,#templateId").select2();
    $(".discount-type").select2({
        width: "200px",
    });
}

function loadRecurringTextBox() {
    let recurringStatus = $("#recurringStatusToggle").is(":checked");
    showRecurringCycle(recurringStatus);
}

function showRecurringCycle(recurringStatus) {
    if (recurringStatus) {
        $(".recurring-cycle-content").show();
    } else {
        $(".recurring-cycle-content").hide();
    }
}

listenClick("#recurringStatusToggle", function () {
    let recurringStatus = $(this).is(":checked");
    showRecurringCycle(recurringStatus);
});

listenChange(".invoice-currency-type", function () {
    let currencyId = $(this).val();
    $.ajax({
        url: route("invoices.get-currency", currencyId),
        type: "get",
        dataType: "json",
        success: function (result) {
            if (result.success) {
                $(".invoice-selected-currency").text(result.data);
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
});

function prepareDatePickers() {
    let dueDateFlatPicker = $("#due_date").flatpickr({
        defaultDate: moment().add(1, "days").toDate(),
        dateFormat: currentDateFormat,
        locale: getUserLanguages,
    });

    let editDueDateFlatPicker = $("#editDueDate").flatpickr({
        dateFormat: currentDateFormat,
        defaultDate: moment($("#editDueDate").val()).format(momentFormat),
        locale: getUserLanguages,
    });

    $("#invoice_date").flatpickr({
        defaultDate: new Date(),
        dateFormat: currentDateFormat,
        locale: getUserLanguages,
        onChange: function (selectedDates, dateStr, instance) {
            let minDate = moment($("#invoice_date").val(), momentFormat)
                .add(1, "days")
                .format(momentFormat);
            if (typeof dueDateFlatPicker != "undefined") {
                dueDateFlatPicker.set("minDate", minDate);
            }
        },
        onReady: function () {
            let minDate = moment(new Date())
                .add(1, "days")
                .format(momentFormat);
            if (typeof dueDateFlatPicker != "undefined") {
                dueDateFlatPicker.set("minDate", minDate);
            }
        },
    });

    $("#editInvoiceDate").flatpickr({
        dateFormat: currentDateFormat,
        defaultDate: moment($("#editInvoiceDate").val()).format(momentFormat),
        locale: getUserLanguages,
        onChange: function () {
            let minDate = moment($("#editInvoiceDate").val(), momentFormat)
                .add(1, "days")
                .format(momentFormat);
            if (typeof editDueDateFlatPicker != "undefined") {
                editDueDateFlatPicker.set("minDate", minDate);
            }
        },
        onReady: function () {
            let minDate = moment($("#editInvoiceDate").val(), momentFormat)
                .add(1, "days")
                .format(momentFormat);
            if (typeof editDueDateFlatPicker != "undefined") {
                editDueDateFlatPicker.set("minDate", minDate);
            }
        },
    });
}

function initializeSelect2CreateEditInvoice() {
    if (!select2NotExists(".product")) {
        return false;
    }
    removeSelect2Container([".product"]);

    $(".product").select2({
        tags: true,
    });

    $(".tax").select2({
        placeholder: "Select TAX",
    });
    $(".invoice-taxes").select2({
        placeholder: "Select TAX",
    });
    $(".payment-qr-code").select2();

    $("#client_id").focus();
}

listenKeyup("#invoiceId", function () {
    return $("#invoiceId").val(this.value.toUpperCase());
});

listenClick("#addNote", function () {
    console.log('fsdasd');
    $("#addNote").hide();
    $("#removeNote").show();
    $("#noteAdd").show();
    $("#termRemove").show();
});
listenClick("#removeNote", function () {
    console.log('fsdasd2');
    $("#addNote").show();
    $("#removeNote").hide();
    $("#noteAdd").hide();
    $("#termRemove").hide();
});

listenClick("#formData_recurring-0", function () {
    if ($("#formData_recurring-0").prop("checked")) {
        $(".recurring").show();
    } else {
        $(".recurring").hide();
    }
});
listenClick("#formData_recurring-1", function () {
    if ($("#formData_recurring-1").prop("checked")) {
        $(".recurring").hide();
    }
});

listenChange("#discountType", function () {
    discountType = $(this).val();
    $("#discount").val(0);
    if (discountType == 1 || discountType == 2) {
        $("#discount").removeAttr("disabled");
        if (discountType == 2) {
            let value = $("#discount").val();
            $("#discount").val(value.substring(0, 2));
        }
    } else {
        $("#discount").attr("disabled", "disabled");
        $("#discount").val(0);
        $("#discountAmount").text("0");
    }
    calculateFinalAmount();
});

window.isNumberKey = (evt, element) => {
    let charCode = evt.which ? evt.which : event.keyCode;

    return !(
        (charCode !== 46 || $(element).val().indexOf(".") !== -1) &&
        (charCode < 48 || charCode > 57)
    );
};

listenClick("#addItem", function () {
    let data = {
        products: JSON.parse($("#products").val()),
        taxes: JSON.parse($("#taxes").val()),
    };

    let invoiceItemHtml = prepareTemplateRender("#invoiceItemTemplate", data);

    $(".invoice-item-container").append(invoiceItemHtml);
    $(".taxId").select2({
        placeholder: "Select TAX",
        multiple: true,
    });

    $(".productId").select2({
        placeholder: "Select Product or Enter free text",
        tags: true,
    });
    resetInvoiceItemIndex();
});

const resetInvoiceItemIndex = () => {
    let index = 1;
    $(".invoice-item-container>tr").each(function () {
        $(this).find(".item-number").text(index);
        index++;
    });
    if (index - 1 == 0) {
        let data = {
            products: JSON.parse($("#products").val()),
            taxes: JSON.parse($("#taxes").val()),
        };
        let invoiceItemHtml = prepareTemplateRender(
            "#invoiceItemTemplate",
            data
        );
        $(".invoice-item-container").append(invoiceItemHtml);
        $(".productId").select2();
        $(".taxId").select2({
            placeholder: "Select TAX",
            multiple: true,
        });
    }
    calculateFinalAmount();
};

listenClick(".delete-invoice-item", function () {
    $(this).parents("tr").remove();
    resetInvoiceItemIndex();
    calculateFinalAmount();
});

listenChange(".product", function () {
    let productId = $(this).val();
    if (isEmpty(productId)) {
        productId = 0;
    }
    let element = $(this);
    $.ajax({
        url: route("invoices.get-product", productId),
        type: "get",
        dataType: "json",
        success: function (result) {
            if (result.success) {
                let price = "";
                $.each(result.data, function (id, productPrice) {
                    if (id === productId) price = productPrice;
                });
                element.parent().parent().find("td .price").val(price);
                element.parent().parent().find("td .qty").val(1);
                $(".price").trigger("keyup");
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
    });
});

listenChange(".tax", function () {
    calculateFinalAmount();
});

listenKeyup(".qty", function () {
    let qty = $(this).val();
    let rate = $(this).parent().siblings().find(".price").val();
    rate = parseFloat(removeCommas(rate));
    let amount = calculateAmountWithoutTax(qty, rate);
    $(this)
        .parent()
        .siblings(".item-total")
        .text(addCommas(amount.toFixed(2).toString()));
    calculateFinalAmount();
});

listenKeyup(".price", function () {
    let rate = $(this).val();
    rate = parseFloat(removeCommas(rate));
    let qty = $(this).parent().siblings().find(".qty").val();
    let amount = calculateAmountWithoutTax(qty, rate);
    $(this)
        .parent()
        .siblings(".item-total")
        .text(addCommas(amount.toFixed(2).toString()));
    calculateFinalAmount();
});

const calculateAmount = (qty, rate, tax) => {
    if (qty > 0 && rate > 0) {
        let price = qty * rate;
        let allTax = price + (price * tax) / 100;
        if (isNaN(allTax)) {
            return price;
        }
        return allTax;
    } else {
        return 0;
    }
};

const calculateAmountWithoutTax = (qty, rate) => {
    if (qty > 0 && rate > 0) {
        let price = qty * rate;
        if (isNaN(price)) {
            return 0;
        }
        return price;
    } else {
        return 0;
    }
};

const calculateFinalAmount = () => {
    let taxData = [];

    let amount = 0;
    let itemWiseTaxes = 0;
    $(".invoice-item-container>tr").each(function () {
        let itemTotal = $(this).find(".item-total").text();
        itemTotal = removeCommas(itemTotal);
        itemTotal = isEmpty($.trim(itemTotal)) ? 0 : parseFloat(itemTotal);
        amount += itemTotal;

        $(this)
            .find(".tax")
            .each(function (i, element) {
                let collection = element.selectedOptions;

                let itemWiseTax = 0;
                for (let i = 0; i < collection.length; i++) {
                    let tax = collection[i].value;
                    if (tax > 0) {
                        itemWiseTax += parseFloat(tax);
                    }
                }

                itemWiseTaxes += parseFloat((itemWiseTax * itemTotal) / 100);

                taxData.push(itemWiseTaxes);
            });
    });

    let totalAmount = amount;
    $("#total").text(number_format(totalAmount));
    $("#finalAmount").text(number_format(totalAmount));

    //set hidden amount input value
    $("#total_amount").val(totalAmount.toFixed(2));

    // total amount with products taxes
    let finalTotalAmt = parseFloat(totalAmount) + parseFloat(itemWiseTaxes);

    $("#totalTax").empty();
    $("#totalTax").text(number_format(itemWiseTaxes));

    // add invoice taxes
    let totalInvoiceTax = 0;
    $("option:selected", ".invoice-taxes").each(function (index, val) {
        totalInvoiceTax += parseFloat(val.getAttribute("data-tax"));
    });

    if (totalInvoiceTax > 0) {
        let amountWithTaxes =
            (finalTotalAmt * parseFloat(totalInvoiceTax)) / 100;
        let finalTotalTaxes =
            parseFloat(itemWiseTaxes) + parseFloat(amountWithTaxes);
        $("#totalTax").text(number_format(finalTotalTaxes));
        finalTotalAmt = finalTotalAmt + parseFloat(amountWithTaxes);
    }

    // add discount amount
    let discount = $("#discount").val();
    discountType = $("#discountType").val();

    if (isEmpty(discount) || isEmpty(totalAmount)) {
        discount = 0;
    }

    let discountAmount = 0;
    if (discountType == 1) {
        discountAmount = discount;
        finalTotalAmt = finalTotalAmt - discountAmount;
    } else if (discountType == 2) {
        discountAmount = (finalTotalAmt * discount) / 100;
        finalTotalAmt = finalTotalAmt - discountAmount;
    }

    $("#discountAmount").text(number_format(discountAmount));

    // final amount calculation
    $("#finalAmount").text(number_format(finalTotalAmt));
    $("#finalTotalAmt").val(finalTotalAmt.toFixed(2));
};

listen("keyup", "#discount", function () {
    let value = $(this).val();
    if (discountType == 2 && value > 100) {
        displayErrorMessage(
            "On Percentage you can only give maximum 100% discount"
        );
        $(this).val(value.slice(0, -1));

        return false;
    }

    calculateFinalAmount();
});

listenChange(".invoice-taxes", function () {
    calculateFinalAmount();
});

listenClick("#saveAsDraft,#saveAndSend", function (event) {
    event.preventDefault();
    let tax_id = [];
    let i = 0;
    let tax = [];
    let j = 0;
    $(".tax-tr").each(function () {
        let data = $(this)
            .find(".tax option:selected")
            .map(function () {
                return $(this).data("id");
            })
            .get();
        if (data != "") {
            tax_id[i++] = data;
        } else {
            tax_id[i++] = 0;
        }

        let val = $(this)
            .find(".tax option:selected")
            .map(function () {
                return $(this).val();
            })
            .get();

        if (val != "") {
            tax[j++] = val;
        } else {
            tax[j++] = 0;
        }
    });

    let invoiceStates = $(this).data("status");
    let myForm = document.getElementById("invoiceForm");
    let formData = new FormData(myForm);
    formData.append("status", invoiceStates);
    formData.append("tax_id", JSON.stringify(tax_id));
    formData.append("tax", JSON.stringify(tax));

    if (invoiceStates == 1) {
        swal({
            title: "Send Invoice !",
            text: "Are you sure want to send this invoice to client ?",
            icon: "warning",
            buttons: {
                confirm: "Yes, Send",
                cancel: "No, Cancel",
            },
        }).then(function (willSend) {
            if (willSend) {
                screenLock();
                $.ajax({
                    url: route("invoices.store"),
                    type: "POST",
                    dataType: "json",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        startLoader();
                    },
                    success: function (result) {
                        displaySuccessMessage(result.message);
                        Turbo.visit(route("invoices.index"));
                    },
                    error: function (result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function () {
                        stopLoader();
                        screenUnLock();
                    },
                });
            }
        });
    } else {
        screenLock();
        $.ajax({
            url: route("invoices.store"),
            type: "POST",
            dataType: "json",
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                startLoader();
            },
            success: function (result) {
                displaySuccessMessage(result.message);
                Turbo.visit(route("invoices.index"));
            },
            error: function (result) {
                displayErrorMessage(result.responseJSON.message);
            },
            complete: function () {
                stopLoader();
                screenUnLock();
            },
        });
    }
});

listenClick("#editSaveAndSend,#editSave", function (event) {
    event.preventDefault();
    let invoiceStatus = $(this).data("status");
    let tax_id = [];
    let i = 0;
    let tax = [];
    let j = 0;
    $(".tax-tr").each(function () {
        let data = $(this)
            .find(".tax option:selected")
            .map(function () {
                return $(this).data("id");
            })
            .get();
        if (data != "") {
            tax_id[i++] = data;
        } else {
            tax_id[i++] = 0;
        }

        let val = $(this)
            .find(".tax option:selected")
            .map(function () {
                return $(this).val();
            })
            .get();

        if (val != "") {
            tax[j++] = val;
        } else {
            tax[j++] = 0;
        }
    });

    let formData =
        $("#invoiceEditForm").serialize() +
        "&invoiceStatus=" +
        invoiceStatus +
        "&tax_id=" +
        JSON.stringify(tax_id) +
        "&tax=" +
        JSON.stringify(tax);
    if (invoiceStatus == 1) {
        swal({
            title: "Send Invoice !",
            text: "Are you sure want to send this invoice to client ?",
            icon: "warning",
            buttons: ["Yes, Send", "No, Cancel"],
        }).then(function (willSend) {
            if (!willSend) {
                screenLock();
                $.ajax({
                    url: $("#invoiceUpdateUrl").val(),
                    type: "PUT",
                    dataType: "json",
                    data: formData,
                    beforeSend: function () {
                        startLoader();
                    },
                    success: function (result) {
                        displaySuccessMessage(result.message);
                        Turbo.visit(route("invoices.index"));
                    },
                    error: function (result) {
                        displayErrorMessage(result.responseJSON.message);
                    },
                    complete: function () {
                        stopLoader();
                        screenUnLock();
                    },
                });
            }
        });
    } else if (invoiceStatus == 0) {
        screenLock();
        $.ajax({
            url: $("#invoiceUpdateUrl").val(),
            type: "PUT",
            dataType: "json",
            data: formData,
            beforeSend: function () {
                startLoader();
            },
            success: function (result) {
                displaySuccessMessage(result.message);
                Turbo.visit(route("invoices.index"));
            },
            error: function (result) {
                displayErrorMessage(result.responseJSON.message);
            },
            complete: function () {
                stopLoader();
                screenUnLock();
            },
        });
    }
});

