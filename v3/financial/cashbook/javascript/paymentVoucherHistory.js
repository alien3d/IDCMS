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
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $centerViewPort = $('#centerViewport');
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $centerViewPort.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
            } else {
                $centerViewPort.html('').empty().append(data);
            }
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty();
            if (type === 1) {
                $infoPanel.html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
            } else if (type === 2) {
                $infoPanel.html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['deleteRecordTextLabel']) + "</span>").delay(1000).fadeOut();
            }
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
            $(document).scrollTop();
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
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
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $centerViewPort = $('#centerViewport');
            var smileyRoll = './images/icons/smiley-roll.png';
            var zoomIcon = './images/icons/magnifier-zoom-actual-equal.png';
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $centerViewPort.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
            } else {
                $centerViewPort.html('').empty().append(data);
            }
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("&nbsp;<img src='" + zoomIcon + "'> <b>" + decodeURIComponent(t['filterTextLabel']) + '</b>: ' + queryText + "");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
            $(document).scrollTop();
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-md-12 col-sm-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function ajaxQuerySearchAllCharacter(leafId, url, securityToken, character) {
    $('#clearSearch').removeClass().addClass('btn btn-primary');
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'list',
            detail: 'body',
            securityToken: securityToken,
            leafId: leafId,
            character: character
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $centerViewPort = $('#centerViewport');
            var smileyRoll = './images/icons/smiley-roll.png';
            var zoomIcon = './images/icons/magnifier-zoom-actual-equal.png';
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $centerViewPort.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
            } else {
                $centerViewPort.html('').empty().append(data);
            }
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("&nbsp;<img src='" + zoomIcon + "'> <b>" + decodeURIComponent(t['filterTextLabel']) + "</b>: " + character + " ");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
            $(document).scrollTop();
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html('').html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
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
            securityToken: securityToken,
            leafId: leafId,
            dateRangeStart: dateRangeStart,
            dateRangeEnd: dateRangeEnd,
            dateRangeType: dateRangeType
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $centerViewPort = $('#centerViewport');
            var betweenIcon = './images/icons/arrow-curve-000-left.png';
            var smileyRoll = './images/icons/smiley-roll.png';
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $centerViewPort.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</span>");
            } else {
                $centerViewPort.html('').empty().append(data);
            }
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty();
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
                        strDate = "<b>" + t['betweenTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ", " + dateStart.getFullYear() + "&nbsp;<img src='" + betweenIcon + "'>&nbsp;" + dateEnd.getDayName() + ", " + dateEnd.getMonthName() + ", " + dateEnd.getDate() + ", " + dateEnd.getFullYear();
                    }
                    break;
                case 'between':
                    if (dateRangeEnd.length === 0) {
                        strDate = "<b>" + t['dayTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ', ' + dateStart.getFullYear();
                    } else {
                        strDate = "<b>" + t['betweenTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ", " + dateStart.getFullYear() + "&nbsp;<img src='" + betweenIcon + "'>&nbsp;" + dateEnd.getDayName() + ", " + dateEnd.getMonthName() + ", " + dateEnd.getDate() + ", " + dateEnd.getFullYear();
                    }
                    break;
            }
            var imageCalendarPath = "./images/icons/" + calendarPng;
            $infoPanel.html('').empty().html("<img src='" + imageCalendarPath + "'> " + strDate + " ");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
            $(document).scrollTop();
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function ajaxQuerySearchAllDateRange(leafId, url, securityToken) {
    ajaxQuerySearchAllDate(leafId, url, securityToken, $('#dateRangeStart').val(), $('#dateRangeEnd').val(), 'between');
}
function showForm(leafId, url, securityToken) {
    sleep(500);
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            method: 'new',
            type: 'form',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $centerViewPort = $('#centerViewport');
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $centerViewPort.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
            } else {
                $centerViewPort.html('').empty().append(data);
                var $infoPanel = $('#infoPanel');
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
                $(document).scrollTop();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function showFormUpdate(leafId, url, urlList, securityToken, paymentVoucherId, updateAccess, deleteAccess) {
    sleep(500);
    $('a[rel=tooltip]').tooltip('hide');
    $.ajax({
        type: 'POST',
        url: urlList,
        data: {
            method: 'read',
            type: 'form',
            paymentVoucherId: paymentVoucherId,
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $infoPanel = $('#infoPanel');
            var $centerViewPort = $('#centerViewport');
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $centerViewPort.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
            } else {
                $centerViewPort.html('').empty().append(data);
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
                $('#newRecordButton1').removeClass().addClass('btn btn-success disabled');
                $('#newRecordButton2').removeClass().addClass('btn  dropdown-toggle btn-success disabled');
                $('#newRecordButton3').attr('onClick', '');
                $('#newRecordButton4').attr('onClick', '');
                $('#newRecordButton5').attr('onClick', '');
                $('#newRecordButton6').attr('onClick', '');
                $('#newRecordButton7').attr('onClick', '');
                if (updateAccess === 1) {
                    $('#updateRecordButton1').removeClass().addClass('btn btn-info');
                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info');
                    $('#updateRecordButton3').attr('onClick', "updateRecord(" + leafId + ",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",1," + deleteAccess + ")");
                    $('#updateRecordButton4').attr('onClick', "updateRecord(" + leafId + ",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",2," + deleteAccess + ")");
                    $('#updateRecordButton5').attr('onClick', "updateRecord(" + leafId + ",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",3," + deleteAccess + ")");
                } else {
                    $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled');
                    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled');
                    $('#updateRecordButton3').attr('onClick', '');
                    $('#updateRecordButton4').attr('onClick', '');
                    $('#updateRecordButton5').attr('onClick', '');
                }
                if (deleteAccess === 1) {
                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(" + leafId + ",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\"," + deleteAccess + ")");
                } else {
                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', '');
                }
                $(document).scrollTop();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-md-12 col-sm-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}
function showModalDelete(paymentVoucherId, bankId, businessPartnerCategoryId, businessPartnerId, paymentTypeId, documentNumber, referenceNumber, paymentVoucherDescription, paymentVoucherDate, paymentVoucherChequeDate, paymentVoucherTextAmount, paymentVoucherAmount, paymentVoucherChequeNumber, paymentVoucherPayee, from, isPrinted, isConform, isChequePrinted, isCollectedByHand, isSentByDespatch, isBalance) {
    $('#paymentVoucherIdPreview').val('').val(decodeURIComponent(paymentVoucherId));
    $('#bankIdPreview').val('').val(decodeURIComponent(bankId));
    $('#businessPartnerCategoryIdPreview').val('').val(decodeURIComponent(businessPartnerCategoryId));
    $('#businessPartnerIdPreview').val('').val(decodeURIComponent(businessPartnerId));
    $('#paymentTypeIdPreview').val('').val(decodeURIComponent(paymentTypeId));
    $('#documentNumberPreview').val('').val(decodeURIComponent(documentNumber));
    $('#referenceNumberPreview').val('').val(decodeURIComponent(referenceNumber));
    $('#paymentVoucherDescriptionPreview').val('').val(decodeURIComponent(paymentVoucherDescription));
    $('#paymentVoucherDatePreview').val('').val(decodeURIComponent(paymentVoucherDate));
    $('#paymentVoucherChequeDatePreview').val('').val(decodeURIComponent(paymentVoucherChequeDate));
    $('#paymentVoucherTextAmountPreview').val('').val(decodeURIComponent(paymentVoucherTextAmount));
    $('#paymentVoucherAmountPreview').val('').val(decodeURIComponent(paymentVoucherAmount));
    $('#paymentVoucherChequeNumberPreview').val('').val(decodeURIComponent(paymentVoucherChequeNumber));
    $('#paymentVoucherPayeePreview').val('').val(decodeURIComponent(paymentVoucherPayee));
    $('#fromPreview').val('').val(decodeURIComponent(from));
    $('#isPrintedPreview').val('').val(decodeURIComponent(isPrinted));
    $('#isConformPreview').val('').val(decodeURIComponent(isConform));
    $('#isChequePrintedPreview').val('').val(decodeURIComponent(isChequePrinted));
    $('#isCollectedByHandPreview').val('').val(decodeURIComponent(isCollectedByHand));
    $('#isSentByDespatchPreview').val('').val(decodeURIComponent(isSentByDespatch));
    $('#isBalancePreview').val('').val(decodeURIComponent(isBalance));
    showMeModal('deletePreview', 1);
}
function deleteGridRecord(leafId, url, urlList, securityToken) {
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            method: 'delete',
            output: 'json',
            paymentVoucherId: $('#paymentVoucherIdPreview').val(),
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === true) {
                showMeModal('deletePreview', 0);
                showGrid(leafId, urlList, securityToken, 0, 10, 2);
            } else if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;" + message + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}




function reportRequest(leafId, url, securityToken, mode) {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            method: 'report',
            mode: mode,
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $infoPanel = $('#infoPanel');
            var folder = data.folder;
            var filename = data.filename;
            var success = data.success;
            var message = data.message;
            if (success === true) {
                var path = "./v3/financial/cashbook/document/" + folder + "/" + filename;
                $infoPanel.html('').empty().html("<span class='label label-success'>" + decodeURIComponent(t['requestFileTextLabel']) + "</span>");
                window.open(path);
            } else {
                $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;" + message + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }
    });
}

function resetRecord(leafId, url, urlList, urlPaymentVoucherDetail, securityToken, createAccess, updateAccess, deleteAccess) {
    var $infoPanel = $('#infoPanel');
    var resetIcon = './images/icons/fruit-orange.png';
    $infoPanel.html('').empty().html("<span class='label label-danger'><img src='" + resetIcon + "'> " + decodeURIComponent(t['resetRecordTextLabel']) + "</span>").delay(1000).fadeOut();
    if ($infoPanel.is(':hidden')) {
        $infoPanel.show();
    }
    $('#postRecordButton').removeClass().addClass('btn btn-info').attr('onClick', '');
    $('#firstRecordButton').removeClass().addClass('btn btn-default').attr('onClick', "firstRecord(" + leafId + ",'" + url + "','" + urlList + "','" + urlPaymentVoucherDetail + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
    $('#previousRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
    $('#nextRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
    $('#endRecordButton').removeClass().addClass('btn btn-default').attr('onClick', "endRecord(" + leafId + ",'" + url + "','" + urlList + "','" + urlPaymentVoucherDetail + "','" + securityToken + "'," + updateAccess + ")");
    $("#paymentVoucherId").val('');
    $("#paymentVoucherIdHelpMe").empty().html('');
    $("#bankId").val('');
    $("#bankIdHelpMe").empty().html('');
    $('#bankId').trigger("chosen:updated");
    $("#businessPartnerCategoryId").val('');
    $("#businessPartnerCategoryIdHelpMe").empty().html('');
    $('#businessPartnerCategoryId').trigger("chosen:updated");
    $("#businessPartnerId").val('');
    $("#businessPartnerIdHelpMe").empty().html('');
    $('#businessPartnerId').trigger("chosen:updated");
    $("#paymentTypeId").val('');
    $("#paymentTypeIdHelpMe").empty().html('');
    $('#paymentTypeId').trigger("chosen:updated");
    $("#documentNumber").val('');
    $("#documentNumberHelpMe").empty().html('');
    $("#referenceNumber").val('');
    $("#referenceNumberHelpMe").empty().html('');
    $("#paymentVoucherDescription").val('');
    $("#paymentVoucherDescriptionHelpMe").empty().html('');
    $("#paymentVoucherDate").val('');
    $("#paymentVoucherDateHelpMe").empty().html('');
    $("#paymentVoucherChequeDate").val('');
    $("#paymentVoucherChequeDateHelpMe").empty().html('');
    $("#paymentVoucherTextAmount").val('');
    $("#paymentVoucherTextAmountHelpMe").empty().html('');
    $("#paymentVoucherAmount").val('');
    $("#paymentVoucherAmountHelpMe").empty().html('');
    $("#paymentVoucherChequeNumber").val('');
    $("#paymentVoucherChequeNumberHelpMe").empty().html('');
    $("#paymentVoucherPayee").val('');
    $("#paymentVoucherPayeeHelpMe").empty().html('');
    $("#from").val('');
    $("#fromHelpMe").empty().html('');
    $("#isPrinted").val('');
    $("#isPrintedHelpMe").empty().html('');
    $("#isPrinted").removeAttr("checked");
    $('input[name="isPrinted"]').bootstrapSwitch('state', false);
    $("#isConform").val('');
    $("#isConformHelpMe").empty().html('');
    $("#isConform").removeAttr("checked");
    $('input[name="isConform"]').bootstrapSwitch('state', false);
    $("#isChequePrinted").val('');
    $("#isChequePrintedHelpMe").empty().html('');
    $("#isChequePrinted").removeAttr("checked");
    $('input[name="isChequePrinted"]').bootstrapSwitch('state', false);
    $("#isCollectedByHand").val('');
    $("#isCollectedByHandHelpMe").empty().html('');
    $("#isCollectedByHand").removeAttr("checked");
    $('input[name="isCollectedByHand"]').bootstrapSwitch('state', false);
    $("#isSentByDespatch").val('');
    $("#isSentByDespatchHelpMe").empty().html('');
    $("#isSentByDespatch").removeAttr("checked");
    $('input[name="isSentByDespatch"]').bootstrapSwitch('state', false);
    $("#isBalance").val('');
    $("#isBalanceHelpMe").empty().html('');
    $("#isBalance").removeAttr("checked");
    $('input[name="isBalance"]').bootstrapSwitch('state', false);
    $("#paymentVoucherDetailId9999").prop("disabled", "true").attr("disabled", "disabled").val('');
    $("#paymentVoucherId9999").prop("disabled", "true").attr("disabled", "disabled").val('').trigger("chosen:updated");
    $("#businessPartnerId9999").prop("disabled", "true").attr("disabled", "disabled").val('').trigger("chosen:updated");
    $("#chartOfAccountId9999").prop("disabled", "true").attr("disabled", "disabled").val('').trigger("chosen:updated");
    $("#countryId9999").prop("disabled", "true").attr("disabled", "disabled").val('').trigger("chosen:updated");
    $("#documentNumber9999").prop("disabled", "true").attr("disabled", "disabled").val('');
    $("#journalNumber9999").prop("disabled", "true").attr("disabled", "disabled").val('');
    $("#paymentVoucherDetailTextAmount9999").prop("disabled", "true").attr("disabled", "disabled").val('');
    $("#paymentVoucherDetailAmount9999").prop("disabled", "true").attr("disabled", "disabled").val('');
    $("#executeBy9999").prop("disabled", "true").attr("disabled", "disabled").val('');
    $("#tableBody").html('').empty();
}
function firstRecord(leafId, url, urlList, urlPaymentVoucherDetail, securityToken, updateAccess, deleteAccess) {
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
                securityToken: securityToken,
                leafId: leafId
            },
            beforeSend: function() {
                var smileyRoll = './images/icons/smiley-roll.png';
                var $infoPanel = $('#infoPanel');
                $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            },
            success: function(data) {
                var smileyRoll = './images/icons/smiley-roll.png';
                var $infoPanel = $('#infoPanel');
                var success = data.success;
                var firstRecord = data.firstRecord;
                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                if (firstRecord === 0) {
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['recordNotFoundLabel']) + "</span>");
                    return false;
                }
                if (success === true) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            method: 'read',
                            paymentVoucherId: firstRecord,
                            output: 'json',
                            securityToken: securityToken,
                            leafId: leafId
                        },
                        beforeSend: function() {
                            var smileyRoll = './images/icons/smiley-roll.png';
                            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        },
                        success: function(data) {
                            var x,
                                    output;
                            var success = data.success;
                            var $infoPanel = $('#infoPanel');
                            var lastRecord = data.lastRecord;
                            var nextRecord = data.nextRecord;
                            var previousRecord = data.previousRecord;
                            if (success === true) {
                                $('#paymentVoucherId').val(data.data.paymentVoucherId);
                                $('#bankId').val(data.data.bankId).trigger("chosen:updated");
                                $('#businessPartnerCategoryId').val(data.data.businessPartnerCategoryId).trigger("chosen:updated");
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
                                x = data.data.paymentVoucherChequeDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#paymentVoucherChequeDate').val(output);
                                $('#paymentVoucherTextAmount').val(data.data.paymentVoucherTextAmount);
                                $('#paymentVoucherAmount').val(data.data.paymentVoucherAmount);
                                $('#paymentVoucherChequeNumber').val(data.data.paymentVoucherChequeNumber);
                                $('#paymentVoucherPayee').val(data.data.paymentVoucherPayee);
                                $('#from').val(data.data.from);
                                if (data.data.isPrinted == true || data.data.isPrinted == 1) {
                                    $("#isPrinted").attr("checked", "checked");
                                    $('input[name="isPrinted"]').bootstrapSwitch('state', true);
                                } else {
                                    $("#isPrinted").removeAttr("checked");
                                    $('input[name="isPrinted"]').bootstrapSwitch('state', false);
                                }
                                $("#isPrinted").val(data.data.isPrinted);
                                ;
                                if (data.data.isConform == true || data.data.isConform == 1) {
                                    $("#isConform").attr("checked", "checked");
                                    $('input[name="isConform"]').bootstrapSwitch('state', true);
                                } else {
                                    $("#isConform").removeAttr("checked");
                                    $('input[name="isConform"]').bootstrapSwitch('state', false);
                                }
                                $("#isConform").val(data.data.isConform);
                                ;
                                if (data.data.isChequePrinted == true || data.data.isChequePrinted == 1) {
                                    $("#isChequePrinted").attr("checked", "checked");
                                    $('input[name="isChequePrinted"]').bootstrapSwitch('state', true);
                                } else {
                                    $("#isChequePrinted").removeAttr("checked");
                                    $('input[name="isChequePrinted"]').bootstrapSwitch('state', false);
                                }
                                $("#isChequePrinted").val(data.data.isChequePrinted);
                                ;
                                if (data.data.isCollectedByHand == true || data.data.isCollectedByHand == 1) {
                                    $("#isCollectedByHand").attr("checked", "checked");
                                    $('input[name="isCollectedByHand"]').bootstrapSwitch('state', true);
                                } else {
                                    $("#isCollectedByHand").removeAttr("checked");
                                    $('input[name="isCollectedByHand"]').bootstrapSwitch('state', false);
                                }
                                $("#isCollectedByHand").val(data.data.isCollectedByHand);
                                ;
                                if (data.data.isSentByDespatch == true || data.data.isSentByDespatch == 1) {
                                    $("#isSentByDespatch").attr("checked", "checked");
                                    $('input[name="isSentByDespatch"]').bootstrapSwitch('state', true);
                                } else {
                                    $("#isSentByDespatch").removeAttr("checked");
                                    $('input[name="isSentByDespatch"]').bootstrapSwitch('state', false);
                                }
                                $("#isSentByDespatch").val(data.data.isSentByDespatch);
                                ;
                                if (data.data.isBalance == true || data.data.isBalance == 1) {
                                    $("#isBalance").attr("checked", "checked");
                                    $('input[name="isBalance"]').bootstrapSwitch('state', true);
                                } else {
                                    $("#isBalance").removeAttr("checked");
                                    $('input[name="isBalance"]').bootstrapSwitch('state', false);
                                }
                                $("#isBalance").val(data.data.isBalance);
                                ;
                                $("#paymentVoucherId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                                $("#businessPartnerId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                                $("#chartOfAccountId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                                $("#countryId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                                $("#documentNumber9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                                $("#paymentVoucherDetailTextAmount9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                                $("#paymentVoucherDetailAmount9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                                $("#executeBy9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                                $.ajax({
                                    type: 'POST',
                                    url: urlPaymentVoucherDetail,
                                    data: {
                                        method: 'read',
                                        paymentVoucherId: data.firstRecord,
                                        output: 'table',
                                        securityToken: securityToken,
                                        leafId: leafId
                                    },
                                    beforeSend: function() {
                                        var smileyRoll = './images/icons/smiley-roll.png';
                                        var $infoPanel = $('#infoPanel');
                                        $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                                        if ($infoPanel.is(':hidden')) {
                                            $infoPanel.show();
                                        }
                                    },
                                    success: function(data) {
                                        var $infoPanel = $('#infoPanel');
                                        var smileyLol = './images/icons/smiley-lol.png';
                                        var success = data.success;
                                        var tableData = data.tableData;
                                        if (success === true) {
                                            $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                            $('#tableBody').html('').empty().html(tableData);
                                            $(".chzn-select").chosen({
                                                search_contains: true
                                            });
                                            $(".chzn-select-deselect").chosen({
                                                allow_single_deselect: true
                                            });
                                        }
                                    },
                                    error: function(xhr) {
                                        var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                                        $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                        $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                                    }
                                });
                                if (nextRecord > 0) {
                                    $('#previousRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
                                    $('#nextRecordButton').removeClass().addClass('btn btn-default').attr('onClick', '').attr('onClick', "nextRecord(" + leafId + ",'" + url + "','" + urlList + "','" + urlPaymentVoucherDetail + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
                                    $('#firstRecordCounter').val(firstRecord);
                                    $('#previousRecordCounter').val(previousRecord);
                                    $('#nextRecordCounter').val(nextRecord);
                                    $('#lastRecordCounter').val(lastRecord);
                                }
                                var startIcon = './images/icons/control-stop.png';
                                $infoPanel.html('').empty().html("&nbsp;<img src='" + startIcon + "'> " + decodeURIComponent(t['firstButtonLabel']) + " ");
                                if ($infoPanel.is(':hidden')) {
                                    $infoPanel.show();
                                }
                            }
                        },
                        error: function(xhr) {
                            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                        }
                    });
                } else {
                    $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='" + smileyRollSweat + "'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                }
            },
            error: function(xhr) {
                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
            }
        });
    }
}
function endRecord(leafId, url, urlList, urlPaymentVoucherDetail, securityToken, updateAccess, deleteAccess) {
    var $infoPanel = $('#infoPanel');
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
                securityToken: securityToken,
                leafId: leafId
            },
            beforeSend: function() {
                var smileyRoll = './images/icons/smiley-roll.png';
                var $infoPanel = $('#infoPanel');
                $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            },
            success: function(data) {
                var smileyRoll = './images/icons/smiley-roll.png';
                var success = data.success;
                var message = data.message;
                var lastRecord = data.lastRecord;
                if (lastRecord === 0) {
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['recordNotFoundLabel']) + "</span>");
                    return false;
                }
                if (success === true) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            method: 'read',
                            paymentVoucherId: lastRecord,
                            output: 'json',
                            securityToken: securityToken,
                            leafId: leafId
                        },
                        beforeSend: function() {
                            var smileyRoll = './images/icons/smiley-roll.png';
                            $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        },
                        success: function(data) {
                            var x,
                                    output;
                            var success = data.success;
                            var firstRecord = data.firstRecord;
                            var lastRecord = data.lastRecord;
                            var nextRecord = data.nextRecord;
                            var previousRecord = data.previousRecord;
                            if (success === true) {
                                $('#paymentVoucherId').val(data.data.paymentVoucherId);
                                $('#bankId').val(data.data.bankId).trigger("chosen:updated");
                                $('#businessPartnerCategoryId').val(data.data.businessPartnerCategoryId).trigger("chosen:updated");
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
                                x = data.data.paymentVoucherChequeDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#paymentVoucherChequeDate').val(output);
                                $('#paymentVoucherTextAmount').val(data.data.paymentVoucherTextAmount);
                                $('#paymentVoucherAmount').val(data.data.paymentVoucherAmount);
                                $('#paymentVoucherChequeNumber').val(data.data.paymentVoucherChequeNumber);
                                $('#paymentVoucherPayee').val(data.data.paymentVoucherPayee);
                                $('#from').val(data.data.from);
                                if (data.data.isPrinted == true || data.data.isPrinted == 1) {
                                    $("#isPrinted").attr("checked", "checked");
                                    $('input[name="isPrinted"]').bootstrapSwitch('state', true);
                                } else {
                                    $("#isPrinted").removeAttr("checked");
                                    $('input[name="isPrinted"]').bootstrapSwitch('state', false);
                                }
                                $("#isPrinted").val(data.data.isPrinted);
                                ;
                                if (data.data.isConform == true || data.data.isConform == 1) {
                                    $("#isConform").attr("checked", "checked");
                                    $('input[name="isConform"]').bootstrapSwitch('state', true);
                                } else {
                                    $("#isConform").removeAttr("checked");
                                    $('input[name="isConform"]').bootstrapSwitch('state', false);
                                }
                                $("#isConform").val(data.data.isConform);
                                ;
                                if (data.data.isChequePrinted == true || data.data.isChequePrinted == 1) {
                                    $("#isChequePrinted").attr("checked", "checked");
                                    $('input[name="isChequePrinted"]').bootstrapSwitch('state', true);
                                } else {
                                    $("#isChequePrinted").removeAttr("checked");
                                    $('input[name="isChequePrinted"]').bootstrapSwitch('state', false);
                                }
                                $("#isChequePrinted").val(data.data.isChequePrinted);
                                ;
                                if (data.data.isCollectedByHand == true || data.data.isCollectedByHand == 1) {
                                    $("#isCollectedByHand").attr("checked", "checked");
                                    $('input[name="isCollectedByHand"]').bootstrapSwitch('state', true);
                                } else {
                                    $("#isCollectedByHand").removeAttr("checked");
                                    $('input[name="isCollectedByHand"]').bootstrapSwitch('state', false);
                                }
                                $("#isCollectedByHand").val(data.data.isCollectedByHand);
                                ;
                                if (data.data.isSentByDespatch == true || data.data.isSentByDespatch == 1) {
                                    $("#isSentByDespatch").attr("checked", "checked");
                                    $('input[name="isSentByDespatch"]').bootstrapSwitch('state', true);
                                } else {
                                    $("#isSentByDespatch").removeAttr("checked");
                                    $('input[name="isSentByDespatch"]').bootstrapSwitch('state', false);
                                }
                                $("#isSentByDespatch").val(data.data.isSentByDespatch);
                                ;
                                if (data.data.isBalance == true || data.data.isBalance == 1) {
                                    $("#isBalance").attr("checked", "checked");
                                    $('input[name="isBalance"]').bootstrapSwitch('state', true);
                                } else {
                                    $("#isBalance").removeAttr("checked");
                                    $('input[name="isBalance"]').bootstrapSwitch('state', false);
                                }
                                $("#isBalance").val(data.data.isBalance);
                                ;
                                $("#paymentVoucherId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                                $("#businessPartnerId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                                $("#chartOfAccountId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                                $("#countryId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                                $("#documentNumber9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                                $("#paymentVoucherDetailTextAmount9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                                $("#paymentVoucherDetailAmount9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                                $("#executeBy9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                                $.ajax({
                                    type: 'POST',
                                    url: urlPaymentVoucherDetail,
                                    data: {
                                        method: 'read',
                                        paymentVoucherId: lastRecord,
                                        output: 'table',
                                        securityToken: securityToken,
                                        leafId: leafId
                                    },
                                    beforeSend: function() {
                                        var smileyRoll = './images/icons/smiley-roll.png';
                                        var $infoPanel = $('#infoPanel');
                                        $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                                        if ($infoPanel.is(':hidden')) {
                                            $infoPanel.show();
                                        }
                                    },
                                    success: function(data) {
                                        var $infoPanel = $('#infoPanel');
                                        var success = data.success;
                                        var tableData = data.tableData;
                                        var smileyLol = './images/icons/smiley-lol.png';
                                        if (success === true) {
                                            $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                            $('#tableBody').html('').empty().html(tableData);
                                            $(".chzn-select").chosen({
                                                search_contains: true
                                            });
                                            $(".chzn-select-deselect").chosen({
                                                allow_single_deselect: true
                                            });
                                        }
                                    },
                                    error: function(xhr) {
                                        var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                                        $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                        $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                                    }
                                });
                                if (lastRecord !== 0) {
                                    $('#previousRecordButton').removeClass().addClass('btn btn-default').attr('onClick', "previousRecord(" + leafId + ",'" + url + "','" + urlList + "','" + urlPaymentVoucherDetail + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
                                    $('#nextRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
                                    $('#firstRecordCounter').val(firstRecord);
                                    $('#previousRecordCounter').val(previousRecord);
                                    $('#nextRecordCounter').val(nextRecord);
                                    $('#lastRecordCounter').val(lastRecord);

                                }
                            }
                        },
                        error: function(xhr) {
                            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                            $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                        }
                    });
                } else {
                    $infoPanel.html("<span class='label label-danger'>&nbsp;" + message + "</span>");
                }
                var endIcon = './images/icons/control-stop-180.png';
                $infoPanel.html('').empty().html("&nbsp;<img src='" + endIcon + "'> " + decodeURIComponent(t['endButtonLabel']) + " ");
            },
            error: function(xhr) {
                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
            }
        });
    }
}
function previousRecord(leafId, url, urlList, urlPaymentVoucherDetail, securityToken, updateAccess, deleteAccess) {
    var $previousRecordCounter = $('#previousRecordCounter');
    var $infoPanel = $('#infoPanel');
    var css = $('#previousRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $('#newButton').removeClass();
        if ($previousRecordCounter.val() === '' || $previousRecordCounter.val() === undefined) {
            $infoPanel.html('').empty().html("<span class='label label-danger'>" + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                success: function(data) {
                    var x,
                            output;
                    var success = data.success;
                    var firstRecord = data.firstRecord;
                    var lastRecord = data.lastRecord;
                    var nextRecord = data.nextRecord;
                    var previousRecord = data.previousRecord;
                    var $infoPanel = $('#infoPanel');
                    if (success === true) {
                        $('#paymentVoucherId').val(data.data.paymentVoucherId);
                        $('#bankId').val(data.data.bankId).trigger("chosen:updated");
                        $('#businessPartnerCategoryId').val(data.data.businessPartnerCategoryId).trigger("chosen:updated");
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
                        x = data.data.paymentVoucherChequeDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#paymentVoucherChequeDate').val(output);
                        $('#paymentVoucherTextAmount').val(data.data.paymentVoucherTextAmount);
                        $('#paymentVoucherAmount').val(data.data.paymentVoucherAmount);
                        $('#paymentVoucherChequeNumber').val(data.data.paymentVoucherChequeNumber);
                        $('#paymentVoucherPayee').val(data.data.paymentVoucherPayee);
                        $('#from').val(data.data.from);
                        if (data.data.isPrinted == true || data.data.isPrinted == 1) {
                            $("#isPrinted").attr("checked", "checked");
                            $('input[name="isPrinted"]').bootstrapSwitch('state', true);
                        } else {
                            $("#isPrinted").removeAttr("checked");
                            $('input[name="isPrinted"]').bootstrapSwitch('state', false);
                        }
                        $("#isPrinted").val(data.data.isPrinted);
                        ;
                        if (data.data.isConform == true || data.data.isConform == 1) {
                            $("#isConform").attr("checked", "checked");
                            $('input[name="isConform"]').bootstrapSwitch('state', true);
                        } else {
                            $("#isConform").removeAttr("checked");
                            $('input[name="isConform"]').bootstrapSwitch('state', false);
                        }
                        $("#isConform").val(data.data.isConform);
                        ;
                        if (data.data.isChequePrinted == true || data.data.isChequePrinted == 1) {
                            $("#isChequePrinted").attr("checked", "checked");
                            $('input[name="isChequePrinted"]').bootstrapSwitch('state', true);
                        } else {
                            $("#isChequePrinted").removeAttr("checked");
                            $('input[name="isChequePrinted"]').bootstrapSwitch('state', false);
                        }
                        $("#isChequePrinted").val(data.data.isChequePrinted);
                        ;
                        if (data.data.isCollectedByHand == true || data.data.isCollectedByHand == 1) {
                            $("#isCollectedByHand").attr("checked", "checked");
                            $('input[name="isCollectedByHand"]').bootstrapSwitch('state', true);
                        } else {
                            $("#isCollectedByHand").removeAttr("checked");
                            $('input[name="isCollectedByHand"]').bootstrapSwitch('state', false);
                        }
                        $("#isCollectedByHand").val(data.data.isCollectedByHand);
                        ;
                        if (data.data.isSentByDespatch == true || data.data.isSentByDespatch == 1) {
                            $("#isSentByDespatch").attr("checked", "checked");
                            $('input[name="isSentByDespatch"]').bootstrapSwitch('state', true);
                        } else {
                            $("#isSentByDespatch").removeAttr("checked");
                            $('input[name="isSentByDespatch"]').bootstrapSwitch('state', false);
                        }
                        $("#isSentByDespatch").val(data.data.isSentByDespatch);
                        ;
                        if (data.data.isBalance == true || data.data.isBalance == 1) {
                            $("#isBalance").attr("checked", "checked");
                            $('input[name="isBalance"]').bootstrapSwitch('state', true);
                        } else {
                            $("#isBalance").removeAttr("checked");
                            $('input[name="isBalance"]').bootstrapSwitch('state', false);
                        }
                        $("#isBalance").val(data.data.isBalance);
                        ;
                        $("#paymentVoucherId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                        $("#businessPartnerId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                        $("#chartOfAccountId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                        $("#countryId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                        $("#documentNumber9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                        $("#paymentVoucherDetailTextAmount9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                        $("#paymentVoucherDetailAmount9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                        $("#executeBy9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                        $.ajax({
                            type: 'POST',
                            url: urlPaymentVoucherDetail,
                            data: {
                                method: 'read',
                                paymentVoucherId: $('#previousRecordCounter').val(),
                                output: 'table',
                                securityToken: securityToken,
                                leafId: leafId
                            },
                            beforeSend: function() {
                                var smileyRoll = './images/icons/smiley-roll.png';
                                var $infoPanel = $('#infoPanel');
                                $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                                if ($infoPanel.is(':hidden')) {
                                    $infoPanel.show();
                                }
                            },
                            success: function(data) {
                                var $infoPanel = $('#infoPanel');
                                var success = data.success;
                                var tableData = data.tableData;
                                var smileyLol = './images/icons/smiley-lol.png';
                                if (success === true) {
                                    $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                    $('#tableBody').html('').empty().html(tableData);
                                    $(".chzn-select").chosen({
                                        search_contains: true
                                    });
                                    $(".chzn-select-deselect").chosen({
                                        allow_single_deselect: true
                                    });
                                }
                            },
                            error: function(xhr) {
                                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                                $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                            }
                        });
                    }
                    $('#firstRecordCounter').val(firstRecord);
                    $('#previousRecordCounter').val(previousRecord);
                    $('#nextRecordCounter').val(nextRecord);
                    $('#lastRecordCounter').val(lastRecord);
                    if (parseFloat(nextRecord) <= parseFloat(lastRecord)) {
                        $('#nextRecordButton').removeClass().addClass('btn btn-default').attr('onClick', '').attr('onClick', "nextRecord(" + leafId + ",'" + url + "','" + urlList + "','" + urlPaymentVoucherDetail + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
                    } else {
                        $('#nextRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
                    }
                    if (parseFloat(previousRecord) === 0) {
                        var exclamationIcon = './images/icons/exclamation.png';
                        $infoPanel.html('').empty().html("&nbsp;<img src='" + exclamationIcon + "'> " + decodeURIComponent(t['firstButtonLabel']) + " ");
                        $('#previousRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
                    } else {
                        var control = './images/icons/control-180.png';
                        $infoPanel.html('').empty().html("&nbsp;<img src='" + control + "'> " + decodeURIComponent(t['previousButtonLabel']) + " ");
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                    }
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }
            });
        }
    }
}
function nextRecord(leafId, url, urlList, urlPaymentVoucherDetail, securityToken, updateAccess, deleteAccess) {
    var $infoPanel = $('#infoPanel');
    var $nextRecordCounter = $('#nextRecordCounter');
    var css = $('#nextRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $('#newButton').removeClass();
        if ($nextRecordCounter.val() === '' || $nextRecordCounter.val() === undefined) {
            $infoPanel.html('').empty().html("<span class='label label-danger'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                success: function(data) {
                    var $infoPanel = $('#infoPanel');
                    var x,
                            output;
                    var success = data.success;
                    var firstRecord = data.firstRecord;
                    var lastRecord = data.lastRecord;
                    var nextRecord = data.nextRecord;
                    var previousRecord = data.previousRecord;
                    if (success === true) {
                        $('#paymentVoucherId').val(data.data.paymentVoucherId);
                        $('#bankId').val(data.data.bankId).trigger("chosen:updated");
                        $('#businessPartnerCategoryId').val(data.data.businessPartnerCategoryId).trigger("chosen:updated");
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
                        x = data.data.paymentVoucherChequeDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#paymentVoucherChequeDate').val(output);
                        $('#paymentVoucherTextAmount').val(data.data.paymentVoucherTextAmount);
                        $('#paymentVoucherAmount').val(data.data.paymentVoucherAmount);
                        $('#paymentVoucherChequeNumber').val(data.data.paymentVoucherChequeNumber);
                        $('#paymentVoucherPayee').val(data.data.paymentVoucherPayee);
                        $('#from').val(data.data.from);
                        if (data.data.isPrinted == true || data.data.isPrinted == 1) {
                            $("#isPrinted").attr("checked", "checked");
                            $('input[name="isPrinted"]').bootstrapSwitch('state', true);
                        } else {
                            $("#isPrinted").removeAttr("checked");
                            $('input[name="isPrinted"]').bootstrapSwitch('state', false);
                        }
                        $("#isPrinted").val(data.data.isPrinted);
                        ;
                        if (data.data.isConform == true || data.data.isConform == 1) {
                            $("#isConform").attr("checked", "checked");
                            $('input[name="isConform"]').bootstrapSwitch('state', true);
                        } else {
                            $("#isConform").removeAttr("checked");
                            $('input[name="isConform"]').bootstrapSwitch('state', false);
                        }
                        $("#isConform").val(data.data.isConform);
                        ;
                        if (data.data.isChequePrinted == true || data.data.isChequePrinted == 1) {
                            $("#isChequePrinted").attr("checked", "checked");
                            $('input[name="isChequePrinted"]').bootstrapSwitch('state', true);
                        } else {
                            $("#isChequePrinted").removeAttr("checked");
                            $('input[name="isChequePrinted"]').bootstrapSwitch('state', false);
                        }
                        $("#isChequePrinted").val(data.data.isChequePrinted);
                        ;
                        if (data.data.isCollectedByHand == true || data.data.isCollectedByHand == 1) {
                            $("#isCollectedByHand").attr("checked", "checked");
                            $('input[name="isCollectedByHand"]').bootstrapSwitch('state', true);
                        } else {
                            $("#isCollectedByHand").removeAttr("checked");
                            $('input[name="isCollectedByHand"]').bootstrapSwitch('state', false);
                        }
                        $("#isCollectedByHand").val(data.data.isCollectedByHand);
                        ;
                        if (data.data.isSentByDespatch == true || data.data.isSentByDespatch == 1) {
                            $("#isSentByDespatch").attr("checked", "checked");
                            $('input[name="isSentByDespatch"]').bootstrapSwitch('state', true);
                        } else {
                            $("#isSentByDespatch").removeAttr("checked");
                            $('input[name="isSentByDespatch"]').bootstrapSwitch('state', false);
                        }
                        $("#isSentByDespatch").val(data.data.isSentByDespatch);
                        ;
                        if (data.data.isBalance == true || data.data.isBalance == 1) {
                            $("#isBalance").attr("checked", "checked");
                            $('input[name="isBalance"]').bootstrapSwitch('state', true);
                        } else {
                            $("#isBalance").removeAttr("checked");
                            $('input[name="isBalance"]').bootstrapSwitch('state', false);
                        }
                        $("#isBalance").val(data.data.isBalance);
                        $("#paymentVoucherId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                        $("#businessPartnerId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                        $("#chartOfAccountId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                        $("#countryId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                        $("#documentNumber9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                        $("#paymentVoucherDetailTextAmount9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                        $("#paymentVoucherDetailAmount9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                        $.ajax({
                            type: 'POST',
                            url: urlPaymentVoucherDetail,
                            data: {
                                method: 'read',
                                paymentVoucherId: $('#nextRecordCounter').val(),
                                output: 'table',
                                securityToken: securityToken,
                                leafId: leafId
                            },
                            beforeSend: function() {
                                var smileyRoll = './images/icons/smiley-roll.png';
                                var $infoPanel = $('#infoPanel');
                                $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                                if ($infoPanel.is(':hidden')) {
                                    $infoPanel.show();
                                }
                            },
                            success: function(data) {
                                var $infoPanel = $('#infoPanel');
                                var success = data.success;
                                var tableData = data.tableData;
                                var smileyLol = './images/icons/smiley-lol.png';
                                if (success === true) {
                                    $('#tableBody').html('').empty().html(tableData);
                                    $(".chzn-select").chosen({
                                        search_contains: true
                                    });
                                    $(".chzn-select-deselect").chosen({
                                        allow_single_deselect: true
                                    });
                                    $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                    if ($infoPanel.is(':hidden')) {
                                        $infoPanel.show();
                                    }
                                }
                            },
                            error: function(xhr) {
                                var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                                $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                            }
                        });
                        $('#firstRecordCounter').val(firstRecord);
                        $('#previousRecordCounter').val(previousRecord);
                        $('#nextRecordCounter').val(nextRecord);
                        $('#lastRecordCounter').val(lastRecord);
                        if (parseFloat(previousRecord) > 0) {
                            $('#previousRecordButton').removeClass().addClass('btn btn-default').attr('onClick', "previousRecord(" + leafId + ",'" + url + "','" + urlList + "','" + urlPaymentVoucherDetail + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
                        } else {
                            $('#previousRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
                        }
                        if (parseFloat(nextRecord) === 0) {
                            var exclamationIcon = './images/icons/exclamation.png';
                            $('#nextRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
                            $infoPanel.html('').empty().html("&nbsp;<img src='" + exclamationIcon + "'> " + decodeURIComponent(t['endButtonLabel']) + " ");
                        } else {
                            var controlIcon = './images/icons/control.png';
                            $infoPanel.html('').empty().html("&nbsp;<img src='" + controlIcon + "'> " + decodeURIComponent(t['nextButtonLabel']) + " ");
                        }
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                    }
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError').html('').empty().html("<span class='alert alert-error col-xs-12 col-sm-12 col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
                }
            });
        }
    }
}
