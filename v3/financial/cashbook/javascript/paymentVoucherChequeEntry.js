function showGrid(leafId, page, securityToken, offset, limit, type) {
    $.ajax({
        type: 'POST',
        url: page,
        data: {
            offset: offset,
            limit: limit,
            method: 'read',
            type: 'list',
            detail: 'body',
            from: 'paymentVoucherChequeEntry.php',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var $infoPanel = $("#infoPanel");
            $infoPanel.empty().html('').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $infoPanel = $("#infoPanel");
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $('#centerViewport').empty().html('').html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + message + "</span>");
            } else {
                $('#centerViewport').empty().html('').append(data);
                $infoPanel.empty().html('');
                if (type === 1) {
                    $infoPanel.html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                }
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }
        },
        error: function(xhr) {
            $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
        }
    });
}
function ajaxQuerySearchAll(leafId, url, securityToken) {
    $('#clearSearch').removeClass().addClass('btn');
    var queryGrid = $('#query').val();
    var queryWidget = $('#queryWidget').val();
    var queryText;
    if (queryGrid !== undefined) {
        if (queryGrid.length > 0) {
            queryText = queryGrid;
        } else {
            queryText = queryWidget;
        }
    } else {
        queryText = queryWidget;
    }
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'list',
            detail: 'body',
            query: queryText,
            from: 'paymentVoucherChequeEntry.php',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var $infoPanel = $("#infoPanel");
            var zoomIcon = './images/icons/magnifier-zoom-actual-equal.png';
            $infoPanel.empty().html('').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $infoPanel = $("#infoPanel");
            if (data.success === false) {
                $('#centerViewport').html('').empty().html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
            } else {
                $('#centerViewport').html('').empty().append(data);
                $infoPanel.empty().html('').html("&nbsp;<img src='./images/icons/magnifier-zoom-actual-equal.png'> <b>" + decodeURIComponent(t['filterTextLabel']) + '</b>: ' + queryText + "");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }
        },
        error: function(xhr) {
            $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
        }
    });
}
function ajaxQuerySearchAllCharacter(leafId, url, securityToken, character) {
    $('#clearSearch').removeClass().addClass('btn');
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'list',
            detail: 'body',
            from: 'paymentVoucherChequeEntry.php',
            securityToken: securityToken,
            leafId: leafId,
            character: character
        },
        beforeSend: function() {
            var $infoPanel = $("#infoPanel");
            $infoPanel.empty().html('').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $infoPanel = $("#infoPanel");
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $('#centerViewport').html('').empty().html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + message + "</span>");
            } else {
                $('#centerViewport').html('').empty().append(data);
                $infoPanel.empty().html('').html("&nbsp;<img src='./images/icons/magnifier-zoom-actual-equal.png'> <b>" + decodeURIComponent(t['filterTextLabel']) + "</b>: " + character + " ");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
                $(document).scrollTop();
            }
        },
        error: function(xhr) {
            $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
        }
    });
}
function ajaxQuerySearchAllDate(leafId, url, securityToken, dateRangeStart, dateRangeEnd, dateRangeType) {
    Date.prototype.getMonthName = function() {
        var m = [t['januaryTextLabel'], t['februaryTextLabel'], t['marchTextLabel'], t['aprilTextLabel'], t['mayTextLabel'], t['juneTextLabel'], t['julyTextLabel'], t['augustTextLabel'], t['septemberTextLabel'], t['octoberTextLabel'], t['novemberTextLabel'], t['decemberTextLabel']];
        return m[this.getMonth()];
    };
    Date.prototype.getDayName = function() {
        var d = [t['sundayTextLabel'], t['mondayTextLabel'], t['tuesdayTextLabel'], t['wednesdayTextLabel'], t['thursdayTextLabel'], t['fridayTextLabel'], t['saturdayTextLabel']];
        return d[this.getDay()];
    };
    var calendarPng;
    var strDate;
    var dateStart = new Date();
    var partsStart = String(dateRangeStart).split('-');
    dateStart.setFullYear(partsStart[2]);
    dateStart.setMonth(partsStart[1] - 1);
    dateStart.setDate(partsStart[0]);
    var dateEnd = new Date();
    if (dateRangeEnd.length > 0) {
        var partsEnd = String(dateRangeEnd).split('-');
        dateEnd.setFullYear(partsEnd[2]);
        dateEnd.setMonth(partsEnd[1] - 1);
        dateEnd.setDate(partsEnd[0]);
    }
    if (dateRangeStart.length === 0) {
        dateRangeStart = $('#dateRangeStart').val();
    }
    if (dateRangeEnd.length === 0) {
        dateRangeEnd = $('#dateRangeEnd').val();
    }
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'list',
            detail: 'body',
            query: $('#query').val(),
            from: 'paymentVoucherChequeEntry.php',
            securityToken: securityToken,
            leafId: leafId,
            dateRangeStart: dateRangeStart,
            dateRangeEnd: dateRangeEnd,
            dateRangeType: dateRangeType
        },
        beforeSend: function() {
            var $infoPanel = $("#infoPanel");
            $infoPanel.empty().html('').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $infoPanel = $("#infoPanel");
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $('#centerViewport').html('').empty().html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'>" + message + "</span>");
            } else {
                $('#centerViewport').html('').empty().append(data);
                $('#infoPanel').empty();
                if (dateRangeType === 'day') {
                    calendarPng = 'calendar-select-days.png';
                } else if (dateRangeType === 'week' || dateRangeType === 'between') {
                    calendarPng = 'calendar-select-week.png';
                } else if (dateRangeType === 'month') {
                    calendarPng = 'calendar-select-month.png';
                } else if (dateRangeType === 'year') {
                    calendarPng = 'calendar-select.png';
                } else {
                    calendarPng = 'calendar-select.png';
                }
                switch (dateRangeType) {
                    case 'day':
                        strDate = "<b>" + t['dayTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ", " + dateStart.getFullYear();
                        break;
                    case 'month':
                        strDate = "<b>" + t['monthTextLabel'] + "</b> : " + dateStart.getMonthName() + ", " + dateStart.getFullYear();
                        break;
                    case 'year':
                        strDate = "<b>" + t['yearTextLabel'] + "</b> : " + dateStart.getFullYear();
                        break;
                    case 'week':
                        if (dateRangeEnd.length === 0) {
                            strDate = "<b>" + t['dayTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ", " + dateStart.getFullYear();
                        } else {
                            strDate = "<b>" + t['betweenTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ", " + dateStart.getFullYear() + "&nbsp;<img src='./images/icons/arrow-curve-000-left.png'>&nbsp;" + dateEnd.getDayName() + ", " + dateEnd.getMonthName() + ", " + dateEnd.getDate() + ", " + dateEnd.getFullYear();
                        }
                        break;
                    case 'between':
                        if (dateRangeEnd.length === 0) {
                            strDate = "<b>" + t['dayTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ', ' + dateStart.getFullYear();
                        } else {
                            strDate = "<b>" + t['betweenTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ", " + dateStart.getFullYear() + "&nbsp;<img src='./images/icons/arrow-curve-000-left.png'>&nbsp;" + dateEnd.getDayName() + ", " + dateEnd.getMonthName() + ", " + dateEnd.getDate() + ", " + dateEnd.getFullYear();
                        }
                        break;
                }
                $infoPanel.empty().html("<img src='./images/icons/" + calendarPng + "'> " + strDate + " ");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
                $(document).scrollTop();
            }
        },
        error: function(xhr) {
            $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
        }
    });
}
function ajaxQuerySearchAllDateRange(leafId, url, securityToken) {
    ajaxQuerySearchAllDate(leafId, url, securityToken, $('#dateRangeStart').val(), $('#dateRangeEnd').val(), 'between', '', t['loadingTextLabel'], t['loadingCompleteTextLabel'], t['loadingErrorTextLabel']);
}
function showForm(leafId, url, securityToken) {
    sleep(500);
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            method: 'new',
            type: 'form',
            from: 'paymentVoucherChequeEntry.php',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var $infoPanel = $("#infoPanel");
            $infoPanel.empty().html('').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $infoPanel = $("#infoPanel");
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $('#centerViewport').html('').empty().html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + message + "</span>");
            } else {
                $('#centerViewport').html('').empty().append(data);
                $infoPanel.empty().html('').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
                $(document).scrollTop();
            }
        },
        error: function(xhr) {
            $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
        }
    });
}
function showModalDelete(paymentVoucherId, bankId, businessPartnerCategoryId, businessPartnerId, paymentTypeId, documentNumber, referenceNumber, paymentVoucherDescription, paymentVoucherDate, paymentVoucherAmount, paymentVoucherChequeNumber, paymentVoucherPayee, isPrinted, isConform, isChequePrinted) {
    $('#paymentVoucherIdPreview').val('').val(decodeURIComponent(paymentVoucherId));
    $('#bankIdPreview').val('').val(decodeURIComponent(bankId));
    $('#businessPartnerCategoryIdPreview').val('').val(decodeURIComponent(businessPartnerCategoryId));
    $('#businessPartnerIdPreview').val('').val(decodeURIComponent(businessPartnerId));
    $('#paymentTypeIdPreview').val('').val(decodeURIComponent(paymentTypeId));
    $('#documentNumberPreview').val('').val(decodeURIComponent(documentNumber));
    $('#referenceNumberPreview').val('').val(decodeURIComponent(referenceNumber));
    $('#paymentVoucherDescriptionPreview').val('').val(decodeURIComponent(paymentVoucherDescription));
    $('#paymentVoucherDatePreview').val('').val(decodeURIComponent(paymentVoucherDate));
    $('#paymentVoucherAmountPreview').val('').val(decodeURIComponent(paymentVoucherAmount));
    $('#paymentVoucherChequeNumberPreview').val('').val(decodeURIComponent(paymentVoucherChequeNumber));
    $('#paymentVoucherPayeePreview').val('').val(decodeURIComponent(paymentVoucherPayee));
    showMeModal('deletePreview', 1);
}
function reportRequest(leafId, url, securityToken, mode) {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            method: 'report',
            mode: mode,
            from: 'paymentVoucherChequeEntry.php',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var $infoPanel = $("#infoPanel");
            $infoPanel.empty().html('').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var folder = data.folder;
            var filename = data.filename;
            var success = data.success;
            var message = data.message;
            var $infoPanel = $("#infoPanel");
            if (success === true) {
                var path = "./v3/financial/cashbook/document/" + folder + "/" + filename;
                $infoPanel.html("<span class='label label-success'>" + decodeURIComponent(t['requestFileTextLabel']) + "</span>");
                window.open(path);
            } else {
                $infoPanel.empty();
                $infoPanel.html("<span class='label label-danger'>&nbsp;" + message + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }
        },
        error: function(xhr) {
            $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
        }
    });
}
function updateRecord(leafId, url, securityToken) {
    var css = $('#updateRecordButton').attr('class');
    var $paymentVoucherChequeDate = $('#paymentVoucherChequeDate');
    var $paymentVoucherChequeNumber = $('#paymentVoucherChequeNumber');
    if (css.search('disabled') > 0) {
    }
    else {
        if ($paymentVoucherChequeDate.val().length === 0) {
            $('#paymentVoucherChequeDateHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherChequeDateLabel'] + " </span>");
            $('#paymentVoucherChequeDateForm').addClass('form-group has-error');
            $paymentVoucherChequeDate.focus();
            return false;
        }
        if ($paymentVoucherChequeNumber.val().length === 0) {
            $('#paymentVoucherChequeNumberHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherChequeNumberLabel'] + " </span>");
            $('#paymentVoucherChequeNumberForm').addClass('form-group has-error');
            $paymentVoucherChequeDateNumber.focus();
            return false;
        }
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                method: 'updateCheque',
                output: 'json',
                paymentVoucherId: $('#paymentVoucherId').val(),
                paymentVoucherChequeDate: $paymentVoucherChequeDate.val(),
                paymentVoucherChequeNumber: $paymentVoucherChequeNumber.val(),
                from: 'paymentVoucherChequeEntry.php',
                securityToken: securityToken,
                leafId: leafId
            },
            beforeSend: function() {
                var $infoPanel = $("#infoPanel");
                $infoPanel.empty().html('').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            },
            success: function(data) {
                var $infoPanel = $("#infoPanel");
                if (data.success === true) {
                    $infoPanel.html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['updateRecordTextLabel']) + "</span>");
                } else if (data.success === false) {
                    $infoPanel.empty();
                    $infoPanel.html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                }
            },
            error: function(xhr) {
                $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid').removeClass().addClass('row');
            }
        });
    }
}
function resetRecord(leafId, url, urlList, urlPaymentVoucher, securityToken, updateAccess) {
    var $infoPanel = $("#infoPanel");
    $infoPanel.empty();
    $infoPanel.html('');
    $infoPanel.html("<span class='label label-danger'><img src='./images/icons/fruit-orange.png'> " + decodeURIComponent(t['resetRecordTextLabel']) + "</span>").delay(1000).fadeOut();
    if ($infoPanel.is(':hidden')) {
        $infoPanel.show();
    }
    $('#updateRecordButton').removeClass().addClass('btn btn-info disabled');
    $('#firstRecordButton').removeClass().addClass('btn btn-default').attr('onClick', "firstRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlPaymentVoucher + "\",\"" + securityToken + "\",\"" + updateAccess + "\")");
    $('#previousRecordButton').removeClass().addClass('btn btn-default disabled');
    $('#nextRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
    $('#endRecordButton').removeClass().addClass('btn btn-default').attr('onClick', "endRecord\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlPaymentVoucher + "\",,\"" + securityToken + "\",\"" + updateAccess + "\")");
    $("#paymentVoucherId").val('');
    $("#paymentVoucherIdHelpMe").empty().html('');
    $("#bankId").val('').trigger("chosen:updated");
    $("#bankIdHelpMe").empty().html('');
    $("#businessPartnerId").val('').trigger("chosen:updated");
    $("#businessPartnerIdHelpMe").empty().html('');
    $("#paymentTypeId").val('').trigger("chosen:updated");
    $("#paymentTypeIdHelpMe").empty().html('');
    $('#paymentTypeId').val('');
    $("#documentNumber").val('');
    $("#documentNumberHelpMe").empty().html('');
    $("#referenceNumber").val('');
    $("#referenceNumberHelpMe").empty().html('');
    $("#paymentVoucherDescriptionHelpMe").empty().html('');
    $('#paymentVoucherDescription').val('').empty().val('');
    $("#paymentVoucherDate").val('');
    $("#paymentVoucherDateHelpMe").empty().html('');
    $("#paymentVoucherChequeDate").val('');
    $("#paymentVoucherChequeDateHelpMe").empty().html('');
    $("#paymentVoucherAmount").val('');
    $("#paymentVoucherAmountHelpMe").empty().html('');
    $("#paymentVoucherChequeNumber").val('');
    $("#paymentVoucherChequeNumberHelpMe").empty().html('');
    $("#paymentVoucherPayee").val('');
    $("#paymentVoucherPayeeHelpMe").empty().html('');
    $("#tableBody").empty().html('');
}
function postRecord(leafId, url, urlList, urlPaymentVoucher, SecurityToken) {
    var css = $('#postRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        return false;
    }
}
function firstRecord(leafId, url, urlList, urlPaymentVoucher, securityToken, updateAccess) {
    var css = $('#firstRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $.ajax({
            type: 'GET',
            url: url,
            data: {
                method: 'dataNavigationRequest',
                dataNavigation: 'firstRecord',
                output: 'json',
                from: 'paymentVoucherChequeEntry.php',
                securityToken: securityToken,
                leafId: leafId
            },
            beforeSend: function() {
                var $infoPanel = $("#infoPanel");
                $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            },
            success: function(data) {
                var $infoPanel = $("#infoPanel");
                var smileyRoll = './images/icons/smiley-roll.png';
                if (firstRecord === 0) {
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['recordNotFoundLabel']) + "</span>");
                    return false;
                }
                if (data.success === true) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            method: 'read',
                            paymentVoucherId: data.firstRecord,
                            output: 'json',
                            from: 'paymentVoucherChequeEntry.php',
                            securityToken: securityToken,
                            leafId: leafId
                        },
                        beforeSend: function() {
                            var $infoPanel = $("#infoPanel");
                            $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        },
                        success: function(data) {
                            var x,
                                    output;
                            var $infoPanel = $("#infoPanel");
                            if (data.success === true) {
                                $('#paymentVoucherId').val(data.data.paymentVoucherId);
                                $('#bankId').val(data.data.bankId).trigger("chosen:updated");
                                $('#businessPartnerId').val(data.data.businessPartnerId).trigger("chosen:updated");
                                $('#paymentTypeId').val(data.data.paymentTypeId).trigger("chosen:updated");
                                $('#documentNumber').val(data.data.documentNumber);
                                $('#referenceNumber').val(data.data.referenceNumber);
                                $('#paymentVoucherDescription').data("wysihtml5").val(data.data.paymentVoucherDescription);
                                x = data.data.paymentVoucherDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#paymentVoucherVoucherDate').val(output);
                                x = data.data.paymentChequeDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#paymentVoucherChequeDate').val(output);
                                $('#paymentVoucherAmount').val(data.data.paymentVoucherAmount);
                                $('#paymentVoucherChequeNumber').val(data.data.paymentVoucherChequeNumber);
                                $('#paymentVoucherPayee').val(data.data.paymentVoucherPayee);
                                $.ajax({
                                    type: 'POST',
                                    url: urlPaymentVoucher,
                                    data: {
                                        method: 'read',
                                        paymentVoucherId: data.firstRecord,
                                        output: 'table',
                                        securityToken: securityToken,
                                        leafId: leafId
                                    },
                                    beforeSend: function() {
                                        var $infoPanel = $("#infoPanel");
                                        $infoPanel.empty().html('').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                                        if ($infoPanel.is(':hidden')) {
                                            $infoPanel.show();
                                        }
                                    },
                                    success: function(data) {
                                        var $infoPanel = $("#infoPanel");
                                        if (data.success === true) {
                                            $infoPanel.empty().html('');
                                            $('#tableBody').empty().html('').html(data.tableData);
                                            $(".chzn-select").chosen({
                                                search_contains: true
                                            });
                                            $(".chzn-select-deselect").chosen({
                                                allow_single_deselect: true
                                            });
                                        }
                                    },
                                    error: function(xhr) {
                                        $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                        $('#infoErrorRowFluid').removeClass().addClass('row');
                                    }
                                });
                                if (data.nextRecord > 0) {
                                    $('#previousRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
                                    $('#nextRecordButton').removeClass().addClass('btn btn-default').attr('onClick', '').attr('onClick', "nextRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlPaymentVoucher + "\",\"" + securityToken + "\",\"" + updateAccess + "\")");
                                    $('#firstRecordCounter').val(data.firstRecord);
                                    $('#previousRecordCounter').val(data.previousRecord);
                                    $('#nextRecordCounter').val(data.nextRecord);
                                    $('#lastRecordCounter').val(data.lastRecord);
                                    if (updateAccess === 1) {
                                        $('#updateRecordButton').removeClass().addClass('btn btn-info').attr('onClick', '').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\")");
                                    } else {
                                        $('#updateRecordButton').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                                    }
                                }
                                $infoPanel.empty();
                                $infoPanel.html("&nbsp;<img src='./images/icons/control-stop.png'> " + decodeURIComponent(t['firstButtonLabel']) + " ");
                                if ($infoPanel.is(':hidden')) {
                                    $infoPanel.show();
                                }
                            }
                        },
                        error: function(xhr) {
                            $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass().addClass('row');
                        }
                    });
                } else {
                    $infoPanel.empty();
                    $infoPanel.html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll-sweat.png'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                }
            },
            error: function(xhr) {
                $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid').removeClass().addClass('row');
            }
        });
    }
}
function endRecord(leafId, url, urlList, urlPaymentVoucher, securityToken, updateAccess) {
    var css = $('#endRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $.ajax({
            type: 'GET',
            url: url,
            data: {
                method: 'dataNavigationRequest',
                dataNavigation: 'lastRecord',
                output: 'json',
                from: 'paymentVoucherChequeEntry.php',
                securityToken: securityToken,
                leafId: leafId
            },
            beforeSend: function() {
                var $infoPanel = $("#infoPanel");
                $infoPanel.empty().html('').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            },
            success: function(data) {
                var smileyRoll = './images/icons/smiley-roll.png';
                if (data.lastRecord === 0) {
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['recordNotFoundLabel']) + "</span>");
                    return false;
                }
                if (data.success === true) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            method: 'read',
                            paymentVoucherId: data.lastRecord,
                            output: 'json',
                            from: 'paymentVoucherChequeEntry.php',
                            securityToken: securityToken,
                            leafId: leafId
                        },
                        beforeSend: function() {
                            var $infoPanel = $("#infoPanel");
                            $infoPanel.empty().html('').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        },
                        success: function(data) {
                            var x,
                                    output;
                            if (data.success === true) {
                                $('#paymentVoucherId').val(data.data.paymentVoucherId);
                                $('#bankId').val(data.data.bankId).trigger("chosen:updated");
                                $('#businessPartnerId').val(data.data.businessPartnerId).trigger("chosen:updated");
                                $('#paymentTypeId').val(data.data.paymentTypeId).trigger("chosen:updated");
                                $('#documentNumber').val(data.data.documentNumber);
                                $('#referenceNumber').val(data.data.referenceNumber);
                                $('#paymentVoucherDescription').val(data.data.paymentVoucherDescription);
                                x = data.data.paymentVoucherDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#paymentVoucherDate').val(output);
                                x = data.data.paymentChequeDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#paymentVoucherChequeDate').val(output);
                                $('#paymentVoucherAmount').val(data.data.paymentVoucherAmount);
                                $('#paymentVoucherChequeNumber').val(data.data.paymentVoucherChequeNumber);
                                $('#paymentVoucherPayee').val(data.data.paymentVoucherPayee);
                                $.ajax({
                                    type: 'POST',
                                    url: urlPaymentVoucher,
                                    data: {
                                        method: 'read',
                                        paymentVoucherId: data.lastRecord,
                                        output: 'table',
                                        from: 'paymentVoucherChequeEntry.php',
                                        securityToken: securityToken,
                                        leafId: leafId
                                    },
                                    beforeSend: function() {
                                        var $infoPanel = $("#infoPanel");
                                        $infoPanel.empty().html('').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                                        if ($infoPanel.is(':hidden')) {
                                            $infoPanel.show();
                                        }
                                    },
                                    success: function(data) {
                                        var $infoPanel = $("#infoPanel");
                                        if (data.success === true) {
                                            $infoPanel.empty().html('').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                            $('#tableBody').empty().html('').html(data.tableData);
                                            $(".chzn-select").chosen({
                                                search_contains: true
                                            });
                                            $(".chzn-select-deselect").chosen({
                                                allow_single_deselect: true
                                            });
                                        }
                                    },
                                    error: function(xhr) {
                                        $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                        $('#infoErrorRowFluid').removeClass().addClass('row');
                                    }
                                });
                                if (data.lastRecord !== 0) {
                                    $('#previousRecordButton').removeClass().addClass('btn btn-default').attr('onClick', "previousRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlPaymentVoucher + "\",\"" + securityToken + "\",\"" + updateAccess + "\")");
                                    $('#nextRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
                                    $('#firstRecordCounter').val(data.firstRecord);
                                    $('#previousRecordCounter').val(data.previousRecord);
                                    $('#nextRecordCounter').val(data.nextRecord);
                                    $('#lastRecordCounter').val(data.lastRecord);
                                    if (updateAccess === 1) {
                                        $('#updateRecordButton').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\")");
                                    } else {
                                        $('#updateRecordButton').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                                    }
                                }
                            }
                        },
                        error: function(xhr) {
                            $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass().addClass('row');
                        }
                    });
                } else {
                    $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                }
                $infoPanel.empty().html('').html("&nbsp;<img src='./images/icons/control-stop-180.png'> " + decodeURIComponent(t['endButtonLabel']) + " ");
            },
            error: function(xhr) {
                $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid').removeClass().addClass('row');
            }
        });
    }
}
function previousRecord(leafId, url, urlList, urlPaymentVoucher, securityToken, updateAccess) {
    var $infoPanel = $("#infoPanel");
    var $previousRecordCounter = $('#previousRecordCounter');
    var css = $('#previousRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        if ($previousRecordCounter.val() === '' || $previousRecordCounter.val() === undefined) {
            $infoPanel.empty().html('').html("<span class='label label-danger'>" + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }
        if (parseFloat($previousRecordCounter.val()) > 0 && parseFloat($previousRecordCounter.val()) < parseFloat($('#lastRecordCounter').val())) {
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'read',
                    paymentVoucherId: $previousRecordCounter.val(),
                    output: 'json',
                    from: 'paymentVoucherChequeEntry.php',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var $infoPanel = $("#infoPanel");
                    $infoPanel.empty().html('').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                success: function(data) {
                    var x,
                            output;
                    if (data.success === true) {
                        $('#paymentVoucherId').val(data.data.paymentVoucherId);
                        $('#bankId').val(data.data.bankId).trigger("chosen:updated");
                        $('#businessPartnerId').val(data.data.businessPartnerId).trigger("chosen:updated");
                        $('#paymentTypeId').val(data.data.paymentTypeId).trigger("chosen:updated");
                        $('#documentNumber').val(data.data.documentNumber);
                        $('#referenceNumber').val(data.data.referenceNumber);
                        $('#paymentVoucherDescription').val(data.data.paymentVoucherDescription);
                        x = data.data.paymentVoucherDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#paymentVoucherDate').val(output);
                        x = data.data.paymentChequeDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#paymentVoucherChequeDate').val(output);
                        $('#paymentVoucherAmount').val(data.data.paymentVoucherAmount);
                        $('#paymentVoucherChequeNumber').val(data.data.paymentVoucherChequeNumber);
                        $('#paymentVoucherPayee').val(data.data.paymentVoucherPayee);
                        $.ajax({
                            type: 'POST',
                            url: urlPaymentVoucher,
                            data: {
                                method: 'read',
                                paymentVoucherId: $previousRecordCounter.val(),
                                output: 'table',
                                from: 'paymentVoucherChequeEntry.php',
                                securityToken: securityToken,
                                leafId: leafId
                            },
                            beforeSend: function() {
                                var $infoPanel = $("#infoPanel");
                                $infoPanel.empty().html('').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                                if ($infoPanel.is(':hidden')) {
                                    $infoPanel.show();
                                }
                            },
                            success: function(data) {
                                var $infoPanel = $("#infoPanel");
                                var success = data.success;
                                var message = data.message;
                                if (success === true) {
                                    $infoPanel.empty().html('').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                    $('#tableBody').empty().html('').html(data.tableData);
                                    $(".chzn-select").chosen({
                                        search_contains: true
                                    });
                                    $(".chzn-select-deselect").chosen({
                                        allow_single_deselect: true
                                    });
                                }
                            },
                            error: function(xhr) {
                                $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                $('#infoErrorRowFluid').removeClass().addClass('row');
                            }
                        });
                        if (updateAccess === 1) {
                            $('#updateRecordButton').removeClass().addClass('btn btn-info').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\")");
                        } else {
                            $('#updateRecordButton').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                        }
                        $('#firstRecordCounter').val(data.firstRecord);
                        $('#previousRecordCounter').val(data.previousRecord);
                        $('#nextRecordCounter').val(data.nextRecord);
                        $('#lastRecordCounter').val(data.lastRecord);
                        if (parseFloat(data.nextRecord) <= parseFloat(data.lastRecord)) {
                            $('#nextRecordButton').removeClass().addClass('btn btn-default').attr('onClick', '').attr('onClick', "nextRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlPaymentVoucher + "\",\"" + securityToken + "\",\"" + updateAccess + "\")");
                        } else {
                            $('#nextRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
                        }
                        if (parseFloat(data.previousRecord) === 0) {
                            $infoPanel.empty().html("&nbsp;<img src='./images/icons/exclamation.png'> " + decodeURIComponent(t['firstButtonLabel']) + " ");
                            $('#previousRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
                        } else {
                            $infoPanel.empty().html('').html("&nbsp;<img src='./images/icons/control-180.png'> " + decodeURIComponent(t['previousButtonLabel']) + " ");
                        }
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                        $(document).scrollTop();
                    }
                },
                error: function(xhr) {
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row');
                }
            });
        } else {
        }

    }
}
function nextRecord(leafId, url, urlList, urlPaymentVoucher, securityToken, updateAccess) {
    var $nextRecordCounter = $('#nextRecordCounter');
    var css = $('#nextRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        var $infoPanel = $("#infoPanel");
        if ($nextRecordCounter.val() === '' || $nextRecordCounter.val() === undefined) {
            $infoPanel.empty().html('').html("<span class='label label-danger'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }
        if (parseFloat($nextRecordCounter.val()) <= parseFloat($('#lastRecordCounter').val())) {
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'read',
                    paymentVoucherId: $nextRecordCounter.val(),
                    output: 'json',
                    from: 'paymentVoucherChequeEntry.php',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var $infoPanel = $("#infoPanel");
                    $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                success: function(data) {
                    var $infoPanel = $("#infoPanel");
                    var x,
                            output;
                    var success = data.success;
                    var message = data.message;
                    if (success === true) {
                        $('#paymentVoucherId').val(data.data.paymentVoucherId);
                        $('#bankId').val(data.data.bankId).trigger("chosen:updated");
                        $('#businessPartnerId').val(data.data.businessPartnerId).trigger("chosen:updated");
                        $('#paymentTypeId').val(data.data.paymentTypeId).trigger("chosen:updated");
                        $('#documentNumber').val(data.data.documentNumber);
                        $('#referenceNumber').val(data.data.referenceNumber);
                        $('#paymentVoucherDescription').val(data.data.paymentVoucherDescription);
                        x = data.data.paymentVoucherDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#paymentVoucherDate').val(output);
                        x = data.data.paymentChequeDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#paymentVoucherChequeDate').val(output);
                        $('#paymentVoucherAmount').val(data.data.paymentVoucherAmount);
                        $('#paymentVoucherChequeNumber').val(data.data.paymentVoucherChequeNumber);
                        $('#paymentVoucherPayee').val(data.data.paymentVoucherPayee);
                        if (updateAccess === 1) {
                            $('#updateRecordButton').removeClass().addClass('btn btn-info').attr('onClick', '').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\")");
                        } else {
                            $('#updateRecordButton').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
                        }
                        $.ajax({
                            type: 'POST',
                            url: urlPaymentVoucher,
                            data: {
                                method: 'read',
                                paymentVoucherId: $nextRecordCounter.val(),
                                output: 'table',
                                from: 'paymentVoucherChequeEntry.php',
                                securityToken: securityToken,
                                leafId: leafId
                            },
                            beforeSend: function() {
                                var $infoPanel = $("#infoPanel");
                                $infoPanel.empty().html('').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                                if ($infoPanel.is(':hidden')) {
                                    $infoPanel.show();
                                }
                            },
                            success: function(data) {
                                var $infoPanel = $("#infoPanel");
                                var success = data.success;
                                if (success === true) {
                                    $('#tableBody').empty().html('').html(data.tableData);
                                    $(".chzn-select").chosen({
                                        search_contains: true
                                    });
                                    $(".chzn-select-deselect").chosen({
                                        allow_single_deselect: true
                                    });
                                    $infoPanel.empty().html('').html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                    if ($infoPanel.is(':hidden')) {
                                        $infoPanel.show();
                                    }
                                }
                            },
                            error: function(xhr) {
                                $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                $('#infoErrorRowFluid').removeClass().addClass('row');
                            }
                        });
                        $('#firstRecordCounter').val(data.firstRecord);
                        $('#previousRecordCounter').val(data.previousRecord);
                        $('#nextRecordCounter').val(data.nextRecord);
                        $('#lastRecordCounter').val(data.lastRecord);
                        if (parseFloat(data.previousRecord) > 0) {
                            $('#previousRecordButton').removeClass().addClass('btn btn-default').attr('onClick', "previousRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlPaymentVoucher + "\",\"" + securityToken + "\",\"" + updateAccess + "\")");
                        } else {
                            $('#previousRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
                        }
                        if (parseFloat(data.nextRecord) === 0) {
                            $('#nextRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
                            $infoPanel.html("&nbsp;<img src='./images/icons/exclamation.png'> " + decodeURIComponent(t['endButtonLabel']) + " ");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        } else {
                            $infoPanel.empty().html('').html("&nbsp;<img src='./images/icons/control.png'> " + decodeURIComponent(t['nextButtonLabel']) + " ");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        }
                    }
                },
                error: function(xhr) {
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row');
                }
            });
        } else {
        }

    }
}
