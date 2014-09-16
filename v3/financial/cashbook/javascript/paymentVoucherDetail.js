function getBank(leafId, url, securityToken) {
    // un hide button search
    // unlimited for searching because  lazy paging.
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'filter',
            from: 'paymentVoucherDetail.php',
            securityToken: securityToken,
            leafId: leafId,
            filter: 'bank'
        },
        beforeSend: function() {
            var $infoPanel = $("#infoPanel");
            $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();

            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success === false) {
                $('#infoPanel').empty().html('').html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'>" + data.message + "</span>");
            } else {
                $("#bankId").empty().html('').html(data.data).trigger("chosen:updated");
                $('#infoPanel').empty().html('').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</spanm>").delay(5000).fadeOut();
            }
        },
        error: function(xhr) {
            $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
        }
    });
}

function getBusinessPartner(leafId, url, securityToken) {
    // un hide button search
    // unlimited for searching because  lazy paging.
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'filter',
            from: 'paymentVoucherDetail.php',
            securityToken: securityToken,
            leafId: leafId,
            filter: 'businessPartner'
        },
        beforeSend: function() {
            var $infoPanel = $("#infoPanel");
            $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success === false) {
                $('#infoPanel').empty().html('').html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'>" + data.message + "</span>");
            } else {
                $("#businessPartnerId").empty().html('').html(data.data).trigger("chosen:updated");
                $('#infoPanel').empty().html('').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</spanm>").delay(5000).fadeOut();
            }
        },
        error: function(xhr) {
            $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
        }
    });
}
function checkDuplicate(leafId, page, securityToken) {
    if ($("#paymentVoucherCode").val().length === 0) {
        alert(t['oddTextLabel']);
        return false;
    }
    $.ajax({
        type: 'GET',
        url: page,
        data: {
            paymentVoucherCode: $("#paymentVoucherCode").val(),
            method: 'duplicate',
            from: 'paymentVoucherDetail.php',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var $infoPanel = $("#infoPanel");
            $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();

            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success === true) {
                if (data.total !== 0) {
                    $("#paymentVoucherCode").empty().val('').focus();
                    $("#paymentVoucherCodeForm").removeClass().addClass("col-md-12 form-group has-error");
                    $('#infoPanel').empty().html('').html("<img src='./images/icons/status-busy.png'> " + t['codeDuplicateTextLabel']).delay(5000).fadeOut();
                } else {
                    $('#infoPanel').empty().html('').html("<img src='./images/icons/status.png'> " + t['codeAvailableTextLabel']).delay(5000).fadeOut();
                }
            } else {
                $('#infoPanel').empty().html('').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
                $("#paymentVoucherForm").removeClass().addClass("col-md-12 form-group has-error");
            }
        },
        error: function(xhr) {
            $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
        }
    });
}
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
            from: 'paymentVoucherDetail.php',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var $infoPanel = $("#infoPanel");
            $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();

            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success === false) {
                $('#centerViewport').html('').empty().html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
            } else {
                $('#centerViewport').html('').empty().append(data);
                $('#infoPanel').empty().html('');
                if (type === 1) {
                    $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                } else if (type === 2) {
                    $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['deleteRecordTextLabel']) + "</span>");
                }
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
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
function ajaxQuerySearchAll(leafId, url, securityToken) {
    // un hide button search
    $('#clearSearch').removeClass().addClass('btn');
    // unlimited for searching because  lazy paging.
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
            from: 'paymentVoucherDetail.php',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var $infoPanel = $("#infoPanel");
            $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();

            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success === false) {
                $('#centerViewport').html('').empty().html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
            } else {
                $('#centerViewport').html('').empty().append(data);
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("&nbsp;<img src='./images/icons/magnifier-zoom-actual-equal.png'> <b>" + decodeURIComponent(t['filterTextLabel']) + '</b>: ' + queryText + "");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
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
function ajaxQuerySearchAllCharacter(leafId, url, securityToken, character) {
    // unhide button search
    $('#clearSearch').removeClass().addClass('btn');
    // unlimited for searching because  lazy paging.
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            offset: 0,
            limit: 99999,
            method: 'read',
            type: 'list',
            detail: 'body',
            from: 'paymentVoucherDetail.php',
            securityToken: securityToken,
            leafId: leafId,
            character: character
        },
        beforeSend: function() {
            var $infoPanel = $("#infoPanel");
            $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();

            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success === false) {
                $('#centerViewport').html('').empty().html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
            } else {
                $('#centerViewport').html('').empty().append(data);
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("&nbsp;<img src='./images/icons/magnifier-zoom-actual-equal.png'> <b>" + decodeURIComponent(t['filterTextLabel']) + "</b>: " + character + " ");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
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
    // date array
    Date.prototype.getMonthName = function() {
        var m = [t['januaryTextLabel'], t['februaryTextLabel'], t['marchTextLabel'], t['aprilTextLabel'], t['mayTextLabel'], t['juneTextLabel'], t['julyTextLabel'],
            t['augustTextLabel'], t['septemberTextLabel'], t['octoberTextLabel'], t['novemberTextLabel'], t['decemberTextLabel']];
        return m[this.getMonth()];
    };
    Date.prototype.getDayName = function() {
        var d = [t['sundayTextLabel'], t['mondayTextLabel'], t['tuesdayTextLabel'], t['wednesdayTextLabel'],
            t['thursdayTextLabel'], t['fridayTextLabel'], t['saturdayTextLabel']];
        return d[this.getDay()];
    };
    // end date array
    // declare/set date object
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
    // unlimited for searching because  lazy paging.
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
            from: 'paymentVoucherDetail.php',
            securityToken: securityToken,
            leafId: leafId,
            dateRangeStart: dateRangeStart,
            dateRangeEnd: dateRangeEnd,
            dateRangeType: dateRangeType
        },
        beforeSend: function() {
            var $infoPanel = $("#infoPanel");
            $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();

            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success === false) {
                $('#centerViewport').html('').empty().html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'>" + data.message + "</span>");
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
                $('#infoPanel').empty().html("<img src='./images/icons/" + calendarPng + "'> " + strDate + " ");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
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
    // unlimited for searching because  lazy paging.
    sleep(500);
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            method: 'new',
            type: 'form',
            securityToken: securityToken,
            from: 'paymentVoucherDetail.php',
            leafId: leafId
        },
        beforeSend: function() {
            var $infoPanel = $("#infoPanel");
            $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success === false) {
                $('#centerViewport').html('').empty().html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
            } else {
                $('#centerViewport').html('').empty().append(data);
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
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
function showFormUpdate(leafId, url, urlList, securityToken, paymentVoucherId, updateAccess, deleteAccess) {
    sleep(500);
    // unlimited for searching because  lazy paging.
    $.ajax({
        type: 'POST',
        url: urlList,
        data: {
            method: 'read',
            type: 'form',
            paymentVoucherId: paymentVoucherId,
            from: 'paymentVoucherDetail.php',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success === false) {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();

                $('#centerViewport').html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
            } else {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();

                $('#centerViewport').append(data);
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
                $('#newRecordButton1').removeClass();
                $('#newRecordButton2').removeClass();
                $('#newRecordButton3').removeClass();
                $('#newRecordButton4').removeClass();
                $('#newRecordButton5').removeClass();
                $('#newRecordButton6').removeClass();
                $('#newRecordButton7').removeClass();
                $('#newRecordButton1').addClass('btn btn-success disabled');
                $('#newRecordButton2').addClass('btn dropdown-toggle btn-success disabled');
                $('#newRecordButton3').addClass('disabled');
                $('#newRecordButton4').addClass('disabled');
                $('#newRecordButton5').addClass('disabled');
                $('#newRecordButton6').addClass('disabled');
                $('#newRecordButton7').addClass('disabled');
                $('#newRecordButton1').attr('onClick', '');
                $('#newRecordButton2').attr('onClick', '');
                $('#newRecordButton3').attr('onClick', '');
                $('#newRecordButton4').attr('onClick', '');
                $('#newRecordButton5').attr('onClick', '');
                $('#newRecordButton6').attr('onClick', '');
                $('#newRecordButton7').attr('onClick', '');
                $('#updateRecordButton1').removeClass();
                $('#updateRecordButton2').removeClass();
                $('#updateRecordButton3').removeClass();
                $('#updateRecordButton4').removeClass();
                $('#updateRecordButton5').removeClass();
                $('#updateRecordButton1').removeClass();
                $('#updateRecordButton2').removeClass();
                $('#updateRecordButton3').removeClass();
                $('#updateRecordButton4').removeClass();
                $('#updateRecordButton5').removeClass();
                if (updateAccess === 1) {
                    $('#updateRecordButton1').addClass('btn btn-info');
                    $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info');
                    $('#updateRecordButton1').attr('onClick', '');
                    $('#updateRecordButton2').attr('onClick', '');
                    $('#updateRecordButton3').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 1 + "\",\"" + deleteAccess + "\")");
                    $('#updateRecordButton4').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 2 + "\",\"" + deleteAccess + "\")");
                    $('#updateRecordButton5').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 3 + "\",\"" + deleteAccess + "\")");
                } else {
                    $('#updateRecordButton1').addClass('btn btn-info disabled');
                    $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info disabled');
                    $('#updateRecordButton1').attr('onClick', '');
                    $('#updateRecordButton2').attr('onClick', '');
                    $('#updateRecordButton3').attr('onClick', '');
                    $('#updateRecordButton4').attr('onClick', '');
                    $('#updateRecordButton5').attr('onClick', '');
                }
                if (deleteAccess === 1) {
                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + deleteAccess + "\")");
                } else {
                    $('#deleteRecordButton').removeClass().addClass('btn btn-danger').attr('onClick', '');
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
function showModalDelete(paymentVoucherId, bankId, businessPartnerCategoryId, businessPartnerId, paymentTypeId, documentNumber, referenceNumber, paymentVoucherDescription, paymentVoucherDate, paymentVoucherAmount, paymentVoucherChequeNumber, paymentVoucherPayee, from, isPrinted, isConform, isChequePrinted) {
    // clear first old record if exist
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

    $('#fromPreview').val('').val(decodeURIComponent(from));

    $('#isPrintedPreview').val('').val(decodeURIComponent(isPrinted));

    $('#isConformPreview').val('').val(decodeURIComponent(isConform));

    $('#isChequePrintedPreview').val('').val(decodeURIComponent(isChequePrinted));

    // open modal box
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
            from: 'paymentVoucherDetail.php',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var $infoPanel = $("#infoPanel");
            $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();

            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success === true) {
                showMeModal('deletePreview', 0);
                showGrid(leafId, urlList, securityToken, 0, 10, 2);
            } else if (data.success === false) {
                $('#infoPanel').empty();
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            }
        },
        error: function(xhr) {
            $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
        }
    });
}
function showFormCreateDetail(leafId, url, securityToken) {

    if ($('#chartOfAccountId9999').val().length === 0) {
        $('#infoPanel').empty();
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountIdLabel'] + "</span>");
        $('#chartOfAccountId9999HelpMe').empty();
        $('#chartOfAccountId9999HelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountIdLabel'] + "</span>");
        $('#chartOfAccountId9999').data('chosen').activate_action();
        return false;
    }
    if ($('#paymentVoucherDetailAmount9999').val().length === 0) {
        $('#infoPanel').empty();
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDetailAmountLabel'] + "</span>");
        $('#paymentVoucherDetailAmount9999HelpMe').empty();
        $('#paymentVoucherDetailAmount9999HelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDetailAmountLabel'] + "</span>");
        $('#paymentVoucherDetailAmount9999').addClass('form-group has-error');
        $('#paymentVoucherDetailAmount9999').focus();
        return false;
    }
    $('#infoPanel').empty();
    $('#infoPanel').html('');
    $('#infoPanel').html("<span class='label label-success'>&nbsp;" + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>");
    if ($('#infoPanel').is(':hidden')) {
        $('#infoPanel').show();
    }
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            method: 'create',
            output: 'json',
            paymentVoucherId: $('#paymentVoucherId').val(),
            businessPartnerId: $('#businessPartnerId').val(),
            chartOfAccountId: $('#chartOfAccountId9999').val(),
            paymentVoucherDetailAmount: $('#paymentVoucherDetailAmount9999').val(),
            from: 'paymentVoucherDetail.php',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            $('#miniInfoPanel9999').empty();
            $('#miniInfoPanel9999').html('');
            $('#miniInfoPanel9999').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success === true) {
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        method: 'read',
                        output: 'table',
                        offset: '0',
                        limit: '9999',
                        paymentVoucherId: $('#paymentVoucherId').val(),
                        from: 'paymentVoucherDetail.php',
                        securityToken: securityToken,
                        leafId: leafId
                    },
                    beforeSend: function() {
                        // this is where we append a loading image
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                        $('#miniInfoPanel9999').empty();
                        $('#miniInfoPanel9999').html('');
                        $('#miniInfoPanel9999').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                        if ($('#infoPanel').is(':hidden')) {
                            $('#infoPanel').show();
                        }
                    },
                    success: function(data) {
                        var success = data.success;
                        var tableData = data.tableData;
                        var message = data.message;
                        // successful request; do something with the data
                        if (success === true) {
                            // make sure empty
                            $('#tableBody')
                                    .empty()
                                    .html('')
                                    .html(tableData);
                            $("#chartOfAccountId9999")
                                    .prop("disabled", "false")
                                    .removeAttr("disabled", "")
                                    .val('')
                                    .trigger("chosen:updated");
                            $("#paymentVoucherDetailAmount9999")
                                    .prop("disabled", "false")
                                    .removeAttr("disabled", "")
                                    .val('');
                            $(".chzn-select").chosen();
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                            if ($('#infoPanel').is(':hidden')) {
                                $('#infoPanel').show();
                            }
                        }
                    },
                    error: function(xhr) {
                        $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                        $('#infoErrorRowFluid').removeClass().addClass('row');
                    }
                });
                $('#miniInfoPanel9999').html("<span class='label label-success'>&nbsp;<a class='close' data-dismiss='alert' href='#'>&times;</a><img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>").delay(1000).fadeOut();
            } else if (data.success === false) {
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
            }
        },
        error: function(xhr) {
            $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
        }
    });
}
function showFormUpdateDetail(leafId, url, securityToken, paymentVoucherDetailId) {
    // checking based on row
    if ($('#chartOfAccountId' + paymentVoucherDetailId).val().length === 0) {
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['chartOfAccountIdLabel'] + "</span>");
        $('#chartOfAccountId' + paymentVoucherDetailId + 'HelpMe').empty();
        $('#chartOfAccountId' + paymentVoucherDetailId + 'HelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['chartOfAccountIdLabel'] + "</span>");
        $('#chartOfAccountId' + paymentVoucherDetailId).data('chosen').activate_action();
        return false;
    }
    if ($('#paymentVoucherDetailAmount' + paymentVoucherDetailId).val().length === 0) {
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['paymentVoucherDetailAmountLabel'] + "</span>");
        $('#paymentVoucherDetailAmount' + paymentVoucherDetailId + 'HelpMe')
                .empty()
                .html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['paymentVoucherDetailAmountLabel'] + "</span>")
                .addClass('form-group has-error')
                .focus();
        return false;
    }
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            method: 'save',
            output: 'json',
            paymentVoucherDetailId: $('#paymentVoucherDetailId' + paymentVoucherDetailId).val(),
            paymentVoucherId: $('#paymentVoucherId').val(),
            businessPartnerId: $('#businessPartnerId').val(),
            chartOfAccountId: $('#chartOfAccountId' + paymentVoucherDetailId).val(),
            paymentVoucherDetailAmount: $('#paymentVoucherDetailAmount' + paymentVoucherDetailId).val(),
            from: 'paymentVoucherDetail.php',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            $('#miniInfoPanel' + paymentVoucherDetailId).empty();
            $('#miniInfoPanel' + paymentVoucherDetailId).html('');
            $('#miniInfoPanel' + paymentVoucherDetailId).html("<span class='label label-warning'> <img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success === true) {
                $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['updateRecordTextLabel']) + "</span>");
                $('#miniInfoPanel' + paymentVoucherDetailId).html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'><a class='close' data-dismiss='alert' href='#'>&times;</a></span>");
            } else if (data.success === false) {
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                $('#miniInfoPanel' + paymentVoucherDetailId).html("<span class='label label-danger'>&nbsp; " + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            }
        },
        error: function(xhr) {
            $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
        }
    });
}
function showModalDeleteDetail(paymentVoucherDetailId) {
    // clear first old record if exist
    $('#paymentVoucherDetailIdPreview').val('');
    $('#paymentVoucherDetailIdPreview').val(decodeURIComponent($("#paymentVoucherDetailId" + paymentVoucherDetailId).val()));

    $('#chartOfAccountIdPreview').val('').val(decodeURIComponent($("#chartOfAccountId" + paymentVoucherDetailId + " option:selected").text()));

    $('#documentNumberPreview').val('').val(decodeURIComponent($("#documentNumber" + paymentVoucherDetailId).val()));
    if ($("#journalNumber" + paymentVoucherDetailId).val() === undefined) {
        $('#journalNumberPreview').val('').val(decodeURIComponent(t['notYetPostedTextLabel']));
    } else {
        $('#journalNumberPreview').val('').val(decodeURIComponent($("#journalNumber" + paymentVoucherDetailId).val()));
    }
    $('#paymentVoucherDetailAmountPreview').val('').val(decodeURIComponent($("#paymentVoucherDetailAmount" + paymentVoucherDetailId).val()));

    // open modal box
    showMeModal('deleteDetailPreview', 1);
}
function deleteGridRecordDetail(leafId, url, urlList, securityToken) {
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            method: 'delete',
            output: 'json',
            paymentVoucherDetailId: $('#paymentVoucherDetailIdPreview').val(),
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var $infoPanel = $("#infoPanel");
            $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();

            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success === true) {
                showMeModal('deleteDetailPreview', 0);
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['deleteRecordTextLabel']) + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
                removeMeTr($('#paymentVoucherDetailIdPreview').val());
            } else if (data.success === false) {
                $('#infoPanel').html("<span class='label label-danger'> " + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            }
        },
        error: function(xhr) {
            $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
        }
    });
}
function deleteGridRecordCheckbox(leafId, url, urlList, securityToken) {
    var stringText = '';
    var counter = 0;
    $('input:checkbox[name="paymentVoucherId[]"]').each(function() {
        stringText = stringText + "&paymentVoucherId[]=" + $(this).val();
    });
    $('input:checkbox[name="isDelete[]"]').each(function() {
        // to cater old code extjs
        if ($(this).is(':checked')) {
            stringText = stringText + "&isDelete[]=true";
        } else {
            stringText = stringText + "&isDelete[]=false";
        }
        if ($(this).is(':checked')) {
            counter++;
        }
    });
    if (counter === 0) {
        alert(decodeURIComponent(t['deleteCheckboxTextLabel']));
        return false;
    } else {
        url = url + "?" + stringText;
    }
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            method: 'updateStatus',
            output: 'json',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var $infoPanel = $("#infoPanel");
            $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();

            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success === true) {
                showGrid(leafId, urlList, securityToken, 0, 10, 2);
            } else if (data.success === false) {
                $('#infoPanel').empty();
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            }
        },
        error: function(xhr) {
            $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
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
            var $infoPanel = $("#infoPanel");
            $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();

            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success === true) {
                var path = "./v3/financial/cashbook/document/" + data.folder + "/" + data.filename;
                $('#infoPanel').html("<span class='label label-success'>" + decodeURIComponent(t['requestFileTextLabel']) + "</span>");
                window.open(path);
                // a hyper link will be given to click download..
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            }
        },
        error: function(xhr) {
            $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
        }
    });
}
function auditRecord() {
    var css = $('#auditRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        return false;
    }
}
function newRecord(leafId, url, urlList, securityToken, type, createAccess, updateAccess, deleteAccess) {
    var css = $('#newRecordButton2').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $('#infoPanel').empty();
        $('#infoPanel').html('');
        if (type === 1) {
            // new record and continue.Reset Current Record
            if ($('#bankId').val().length === 0) {
                $('#bankIdHelpMe').empty();
                $('#bankIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['bankIdLabel'] + " </span>");
                $('#bankId').data('chosen').activate_action();
                return false;
            }
            if ($('#businessPartnerId').val().length === 0) {
                $('#businessPartnerIdHelpMe').empty();
                $('#businessPartnerIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $('#businessPartnerId').data('chosen').activate_action();
                return false;
            }
            if ($('#paymentVoucherDescription').val().length === 0) {
                $('#paymentVoucherDescriptionHelpMe').empty();
                $('#paymentVoucherDescriptionHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDescriptionLabel'] + " </span>");
                $('#paymentVoucherDescriptionForm').addClass('form-group has-error');
                $('#paymentVoucherDescription').focus();
                return false;
            }
            if ($('#paymentVoucherDate').val().length === 0) {
                $('#paymentVoucherDateHelpMe').empty();
                $('#paymentVoucherDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDateLabel'] + " </span>");
                $('#paymentVoucherDateForm').addClass('form-group has-error');
                $('#paymentVoucherDate').focus();
                return false;
            }
            if ($('#paymentVoucherAmount').val().length === 0) {
                $('#paymentVoucherAmountHelpMe').empty();
                $('#paymentVoucherAmountHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherAmountLabel'] + " </span>");
                $('#paymentVoucherAmountForm').addClass('form-group has-error');
                $('#paymentVoucherAmount').focus();
                return false;
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'create',
                    output: 'json',
                    bankId: $('#bankId').val(),
                    businessPartnerCategoryId: $('#businessPartnerCategoryId').val(),
                    businessPartnerId: $('#businessPartnerId').val(),
                    paymentTypeId: $('#paymentTypeId').val(),
                    referenceNumber: $('#referenceNumber').val(),
                    paymentVoucherDescription: $('#paymentVoucherDescription').val(),
                    paymentVoucherDate: $('#paymentVoucherDate').val(),
                    paymentVoucherChequeDate: $('#paymentVoucherChequeDate').val(),
                    paymentVoucherAmount: $('#paymentVoucherAmount').val(),
                    paymentVoucherChequeNumber: $('#paymentVoucherChequeNumber').val(),
                    paymentVoucherPayee: $('#paymentVoucherPayee').val(),
                    from: 'paymentVoucherDetail.php',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var $infoPanel = $("#infoPanel");
                    $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();

                    }
                },
                success: function(data) {
                    // successful request; do something with the data
                    if (data.success === true) {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>").delay(1000).fadeOut();
                        if ($('#infoPanel').is(':hidden')) {
                            $('#infoPanel').show();
                        }
                        // resetting field value
                        $('#bankId').val('').trigger("chosen:updated");
                        $('#bankIdHelpMe').empty().html('');
                        $('#businessPartnerId').val('').trigger("chosen:updated");
                        $('#businessPartnerIdHelpMe').empty().html('');
                        $('#paymentTypeId').val('').trigger("chosen:updated");
                        $('#paymentTypeIdHelpMe').empty().html('');
                        $('#documentNumber').val('');
                        $('#documentNumberHelpMe').empty().html('');
                        $('#referenceNumber').val('');
                        $('#referenceNumberHelpMe').empty().html('');
                        $('#paymentVoucherDescription').val('');
                        $('#paymentVoucherDescriptionForm').removeClass().addClass('col-md-12 form-group');
                        $('#paymentVoucherDescription').val('');
                        $('#paymentVoucherDescriptionHelpMe').empty().html('');
                        $('#paymentVoucherDate').val('');
                        $('#paymentVoucherDateHelpMe').empty().html('');
                        $('#paymentVoucherChequeDate').val('');
                        $('#paymentVoucherChequeDateHelpMe').empty().html('');
                        $('#paymentVoucherAmount').val('');
                        $('#paymentVoucherAmountHelpMe').empty().html('');
                        $('#paymentVoucherChequeNumber').val('');
                        $('#paymentVoucherChequeNumberForm').removeClass().addClass('col-md-12 form-group');
                        $('#paymentVoucherChequeNumber').val('');
                        $('#paymentVoucherChequeNumberHelpMe').empty();
                        $('#paymentVoucherChequeNumberHelpMe').html('');
                        $('#paymentVoucherPayee').val('');
                        $('#paymentVoucherPayeeHelpMe').empty().html('');
                    } else if (data.success === false) {
                        $('#infoPanel').empty();
                        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                        if ($('#infoPanel').is(':hidden')) {
                            $('#infoPanel').show();
                        }
                    }
                },
                error: function(xhr) {
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row');
                }
            });
        } else if (type === 2) {
            // new record and update  or delete record
            if ($('#bankId').val().length === 0) {
                $('#bankIdHelpMe').empty();
                $('#bankIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['bankIdLabel'] + " </span>");
                $('#bankId').data('chosen').activate_action();
                return false;
            }
            if ($('#businessPartnerId').val().length === 0) {
                $('#businessPartnerIdHelpMe').empty();
                $('#businessPartnerIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $('#businessPartnerId').data('chosen').activate_action();
                return false;
            }
            if ($('#paymentVoucherDescription').val().length === 0) {
                $('#paymentVoucherDescriptionHelpMe').empty();
                $('#paymentVoucherDescriptionHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDescriptionLabel'] + " </span>");
                $('#paymentVoucherDescriptionForm').addClass('form-group has-error');
                $('#paymentVoucherDescription').focus();
                return false;
            }
            if ($('#paymentVoucherDate').val().length === 0) {
                $('#paymentVoucherDateHelpMe').empty();
                $('#paymentVoucherDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDateLabel'] + " </span>");
                $('#paymentVoucherDateForm').addClass('form-group has-error');
                $('#paymentVoucherDate').focus();
                return false;
            }
            if ($('#paymentVoucherAmount').val().length === 0) {
                $('#paymentVoucherAmountHelpMe').empty();
                $('#paymentVoucherAmountHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherAmountLabel'] + " </span>");
                $('#paymentVoucherAmountForm').addClass('form-group has-error');
                $('#paymentVoucherAmount').focus();
                return false;
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'create',
                    output: 'json',
                    bankId: $('#bankId').val(),
                    businessPartnerCategoryId: $('#businessPartnerCategoryId').val(),
                    businessPartnerId: $('#businessPartnerId').val(),
                    paymentTypeId: $('#paymentTypeId').val(),
                    referenceNumber: $('#referenceNumber').val(),
                    paymentVoucherDescription: $('#paymentVoucherDescription').val(),
                    paymentVoucherDate: $('#paymentVoucherDate').val(),
                    paymentVoucherAmount: $('#paymentVoucherAmount').val(),
                    paymentVoucherChequeNumber: $('#paymentVoucherChequeNumber').val(),
                    paymentVoucherPayee: $('#paymentVoucherPayee').val(),
                    from: 'paymentVoucherDetail.php',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var $infoPanel = $("#infoPanel");
                    $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();

                    }
                },
                success: function(data) {
                    // successful request; do something with the data
                    if (data.success === true) {
                        $('#bankIdHelpMe').empty().html('');
                        $('#businessPartnerIdHelpMe').empty().html('');
                        $('#paymentTypeIdHelpMe').empty().html('');
                        $('#documentNumberHelpMe').empty().html('');
                        $('#referenceNumberHelpMe').empty().html('');
                        $('#paymentVoucherDescriptionHelpMe').empty().html('');
                        $('#paymentVoucherDateHelpMe').empty().html('');
                        $('#paymentVoucherChequeDateHelpMe').empty().html('');
                        $('#paymentVoucherAmountHelpMe').empty().html('');
                        $('#paymentVoucherChequeNumberHelpMe').empty().html('');

                        $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>");
                        $('#paymentVoucherId').val(data.paymentVoucherId);
                        $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
                        $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled').attr('onClick', '');
                        $('#newRecordButton3').removeClass().addClass('disabled').attr('onClick', '');
                        $('#newRecordButton4').removeClass().addClass('disabled').attr('onClick', '');
                        $('#newRecordButton5').removeClass().addClass('disabled').attr('onClick', '');
                        $('#newRecordButton6').removeClass().addClass('disabled').attr('onClick', '');
                        $('#newRecordButton7').removeClass().addClass('disabled').attr('onClick', '');
                        if (updateAccess === 1) {
                            $('#updateRecordButton1').removeClass().addClass('btn btn-info').addClass('btn dropdown-toggle btn-info').attr('onClick', '');
                            $('#updateRecordButton2').removeClass().attr('onClick', '');
                            $('#updateRecordButton3').removeClass().attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 1 + "\")");
                            $('#updateRecordButton4').removeClass().attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 2 + "\")");
                            $('#updateRecordButton5').removeClass().attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 3 + "\")");
                        } else {
                            $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '').attr('onClick', '');
                            $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled').attr('onClick', '');
                            $('#updateRecordButton3').removeClass().attr('onClick', '');
                            $('#updateRecordButton4').removeClass().attr('onClick', '');
                            $('#updateRecordButton5').removeClass().attr('onClick', '');
                        }
                        if (deleteAccess === 1) {
                            $('#deleteRecordButton')
                                    .removeClass()
                                    .addClass('btn btn-danger')
                                    .attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\")");
                        } else {
                            $('#deleteRecordButton')
                                    .removeClass()
                                    .addClass('btn btn-danger')
                                    .attr('onClick', '');
                        }
                        $("#chartOfAccountId9999").prop("disabled", "false");
                        $("#chartOfAccountId9999").removeAttr("disabled", "");
                        $("#chartOfAccountId9999").val('');
                        $("#chartOfAccountId9999").trigger("chosen:updated");
                        $("#paymentVoucherDetailAmount9999").prop("disabled", "false");
                        $("#paymentVoucherDetailAmount9999").removeAttr("disabled", "");
                        $("#paymentVoucherDetailAmount9999").val('');
                    }
                },
                error: function(xhr) {
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row');
                }
            });
        } else if (type === 5) {
            //New Record and listing
            if ($('#bankId').val().length === 0) {
                $('#bankIdHelpMe').empty();
                $('#bankIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['bankIdLabel'] + " </span>");
                $('#bankId').data('chosen').activate_action();
                return false;
            }
            if ($('#businessPartnerId').val().length === 0) {
                $('#businessPartnerIdHelpMe').empty();
                $('#businessPartnerIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $('#businessPartnerId').data('chosen').activate_action();
                return false;
            }
            if ($('#paymentVoucherDescription').val().length === 0) {
                $('#paymentVoucherDescriptionHelpMe').empty();
                $('#paymentVoucherDescriptionHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDescriptionLabel'] + " </span>");
                $('#paymentVoucherDescriptionForm').addClass('form-group has-error');
                $('#paymentVoucherDescription').focus();
                return false;
            }
            if ($('#paymentVoucherDate').val().length === 0) {
                $('#paymentVoucherDateHelpMe').empty();
                $('#paymentVoucherDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDateLabel'] + " </span>");
                $('#paymentVoucherDateForm').addClass('form-group has-error');
                $('#paymentVoucherDate').focus();
                return false;
            }
            if ($('#paymentVoucherAmount').val().length === 0) {
                $('#paymentVoucherAmountHelpMe').empty();
                $('#paymentVoucherAmountHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherAmountLabel'] + " </span>");
                $('#paymentVoucherAmountForm').addClass('form-group has-error');
                $('#paymentVoucherAmount').focus();
                return false;
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'create',
                    output: 'json',
                    bankId: $('#bankId').val(),
                    businessPartnerCategoryId: $('#businessPartnerCategoryId').val(),
                    businessPartnerId: $('#businessPartnerId').val(),
                    paymentTypeId: $('#paymentTypeId').val(),
                    referenceNumber: $('#referenceNumber').val(),
                    paymentVoucherDescription: $('#paymentVoucherDescription').val(),
                    paymentVoucherDate: $('#paymentVoucherDate').val(),
                    paymentVoucherChequeDate: $('#paymentVoucherChequeDate').val(),
                    paymentVoucherAmount: $('#paymentVoucherAmount').val(),
                    paymentVoucherChequeNumber: $('#paymentVoucherChequeNumber').val(),
                    paymentVoucherPayee: $('#paymentVoucherPayee').val(),
                    from: 'paymentVoucherDetail.php',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var $infoPanel = $("#infoPanel");
                    $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();

                    }
                },
                success: function(data) {
                    // successful request; do something with the data
                    if (data.success === true) {
                        showGrid(leafId, urlList, securityToken, 0, 10, 1);
                    } else {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html("<span class='label label-danger'> <img src='./images/icons/smiley-roll-sweat.png'> " + data.message + "</span>");
                        if ($('#infoPanel').is(':hidden')) {
                            $('#infoPanel').show();
                        }
                    }
                },
                error: function(xhr) {
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row');
                }
            });
        }
        showMeDiv('tableDate', 0);
        showMeDiv('formEntry', 1);
    }
}
function updateRecord(leafId, url, urlList, securityToken, type, deleteAccess) {
    var css = $('#updateRecordButton2').attr('class');
    if (css.search('disabled') > 0) {
        // access denied
    } else {
        $('#infoPanel').empty();
        $('#infoPanel').html('');
        if (type === 1) {
            // update record and continue
            if ($('#bankId').val().length === 0) {
                $('#bankIdHelpMe').empty();
                $('#bankIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['bankIdLabel'] + " </span>");
                $('#bankId').data('chosen').activate_action();
                return false;
            }
            if ($('#businessPartnerId').val().length === 0) {
                $('#businessPartnerIdHelpMe').empty();
                $('#businessPartnerIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $('#businessPartnerId').data('chosen').activate_action();
                return false;
            }
            if ($('#paymentVoucherDescription').val().length === 0) {
                $('#paymentVoucherDescriptionHelpMe').empty();
                $('#paymentVoucherDescriptionHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDescriptionLabel'] + " </span>");
                $('#paymentVoucherDescriptionForm').addClass('form-group has-error');
                $('#paymentVoucherDescription').focus();
                return false;
            }
            if ($('#paymentVoucherDate').val().length === 0) {
                $('#paymentVoucherDateHelpMe').empty();
                $('#paymentVoucherDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDateLabel'] + " </span>");
                $('#paymentVoucherDateForm').addClass('form-group has-error');
                $('#paymentVoucherDate').focus();
                return false;
            }
            if ($('#paymentVoucherAmount').val().length === 0) {
                $('#paymentVoucherAmountHelpMe').empty();
                $('#paymentVoucherAmountHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherAmountLabel'] + " </span>");
                $('#paymentVoucherAmountForm').addClass('form-group has-error');
                $('#paymentVoucherAmount').focus();
                return false;
            }
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'save',
                    output: 'json',
                    paymentVoucherId: $('#paymentVoucherId').val(),
                    bankId: $('#bankId').val(),
                    businessPartnerCategoryId: $('#businessPartnerCategoryId').val(),
                    businessPartnerId: $('#businessPartnerId').val(),
                    paymentTypeId: $('#paymentTypeId').val(),
                    referenceNumber: $('#referenceNumber').val(),
                    paymentVoucherDescription: $('#paymentVoucherDescription').val(),
                    paymentVoucherDate: $('#paymentVoucherDate').val(),
                    paymentVoucherChequeDate: $('#paymentVoucherChequeDate').val(),
                    paymentVoucherAmount: $('#paymentVoucherAmount').val(),
                    paymentVoucherChequeNumber: $('#paymentVoucherChequeNumber').val(),
                    paymentVoucherPayee: $('#paymentVoucherPayee').val(),
                    from: 'paymentVoucherDetail.php',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var $infoPanel = $("#infoPanel");
                    $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();

                    }
                },
                success: function(data) {
                    // successful request; do something with the data
                    if (data.success === true) {
                        $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['updateRecordTextLabel']) + "</span>");
                        if (deleteAccess === 1) {
                            $('#deleteRecordButton')
                                    .removeClass()
                                    .addClass('btn btn-danger')
                                    .attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + deleteAccess + "\")");
                        } else {
                            $('#deleteRecordButton')
                                    .removeClass()
                                    .addClass('btn btn-danger')
                                    .attr('onClick', '');
                        }
                    } else if (data.success === false) {
                        $('#infoPanel').empty();
                        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                        if ($('#infoPanel').is(':hidden')) {
                            $('#infoPanel').show();
                        }
                    }
                },
                error: function(xhr) {
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass().addClass('row');
                }
            });
        } else if (type === 3) {
            // update record and listing
            if ($('#bankId').val().length === 0) {
                $('#bankIdHelpMe').empty();
                $('#bankIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['bankIdLabel'] + " </span>");
                $('#bankId').data('chosen').activate_action();
                return false;
            }
            if ($('#businessPartnerId').val().length === 0) {
                $('#businessPartnerIdHelpMe').empty();
                $('#businessPartnerIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $('#businessPartnerId').data('chosen').activate_action();
                return false;
            }
            if ($('#paymentVoucherDescription').val().length === 0) {
                $('#paymentVoucherDescriptionHelpMe').empty();
                $('#paymentVoucherDescriptionHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDescriptionLabel'] + " </span>");
                $('#paymentVoucherDescriptionForm').addClass('form-group has-error');
                $('#paymentVoucherDescription').focus();
                return false;
            }
            if ($('#paymentVoucherDate').val().length === 0) {
                $('#paymentVoucherDateHelpMe').empty();
                $('#paymentVoucherDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDateLabel'] + " </span>");
                $('#paymentVoucherDateForm').addClass('form-group has-error');
                $('#paymentVoucherDate').focus();
                return false;
            }
            if ($('#paymentVoucherAmount').val().length === 0) {
                $('#paymentVoucherAmountHelpMe').empty();
                $('#paymentVoucherAmountHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherAmountLabel'] + " </span>");
                $('#paymentVoucherAmountForm').addClass('form-group has-error');
                $('#paymentVoucherAmount').focus();
                return false;
            }
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'save',
                    output: 'json',
                    paymentVoucherId: $('#paymentVoucherId').val(),
                    bankId: $('#bankId').val(),
                    businessPartnerCategoryId: $('#businessPartnerCategoryId').val(),
                    businessPartnerId: $('#businessPartnerId').val(),
                    paymentTypeId: $('#paymentTypeId').val(),
                    referenceNumber: $('#referenceNumber').val(),
                    paymentVoucherDescription: $('#paymentVoucherDescription').val(),
                    paymentVoucherDate: $('#paymentVoucherDate').val(),
                    paymentVoucherChequeDate: $('#paymentVoucherChequeDate').val(),
                    paymentVoucherAmount: $('#paymentVoucherAmount').val(),
                    paymentVoucherChequeNumber: $('#paymentVoucherChequeNumber').val(),
                    paymentVoucherPayee: $('#paymentVoucherPayee').val(),
                    from: 'paymentVoucherDetail.php',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var $infoPanel = $("#infoPanel");
                    $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();

                    }
                },
                success: function(data) {
                    // successful request; do something with the data
                    if (data.success === true) {
                        $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                        if ($('#infoPanel').is(':hidden')) {
                            $('#infoPanel').show();
                        }
                        showGrid(leafId, urlList, securityToken, 0, 10, 1);
                    } else if (data.success === false) {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                        if ($('#infoPanel').is(':hidden')) {
                            $('#infoPanel').show();
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
}
function deleteRecord(leafId, url, urlList, securityToken, deleteAccess) {
    var css = $('#deleteRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        if (deleteAccess === 1) {
            if (confirm(decodeURIComponent(t['deleteRecordMessageLabel']))) {
                var value = $('#paymentVoucherId').val();
                if (!value) {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-danger'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "<span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                    return false;
                } else {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            method: 'delete',
                            output: 'json',
                            paymentVoucherId: $('#paymentVoucherId').val(),
                            from: 'paymentVoucherDetail.php',
                            securityToken: securityToken,
                            leafId: leafId
                        },
                        beforeSend: function() {
                            var $infoPanel = $("#infoPanel");
                            $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();

                            }
                        },
                        success: function(data) {
                            // successful request; do something with the data
                            if (data.success === true) {
                                showGrid(leafId, urlList, securityToken, 0, 10, 2);
                            } else if (data.success === false) {
                                $('#infoPanel').empty();
                                $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                                if ($('#infoPanel').is(':hidden')) {
                                    $('#infoPanel').show();
                                }
                            }
                        },
                        error: function(xhr) {
                            $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass().addClass('row');
                        }
                    });
                }
            } else {
                return false;
            }
        }
    }
}
function resetRecord(leafId, url, urlList, urlPaymentVoucherDetail, securityToken, createAccess, updateAccess, deleteAccess) {
    $('#infoPanel').empty();
    $('#infoPanel').html('');
    $('#infoPanel').html("<span class='label label-danger'><img src='./images/icons/fruit-orange.png'> " + decodeURIComponent(t['resetRecordTextLabel']) + "</span>").delay(1000).fadeOut();
    if ($('#infoPanel').is(':hidden')) {
        $('#infoPanel').show();
    }
    $('#newRecordButton1').removeClass();
    $('#newRecordButton2').removeClass();
    $('#newRecordButton1').addClass('btn btn-success');
    $('#newRecordButton2').addClass('btn dropdown-toggle btn-success');
    if (createAccess === 1) {
        $('#newRecordButton1').removeClass();
        $('#newRecordButton2').removeClass();
        $('#newRecordButton3').removeClass();
        $('#newRecordButton4').removeClass();
        $('#newRecordButton5').removeClass();
        $('#newRecordButton6').removeClass();
        $('#newRecordButton7').removeClass();
        $('#newRecordButton1').addClass('btn btn-success');
        $('#newRecordButton2').addClass('btn dropdown-toggle btn-success');
        $('#newRecordButton1').attr('onClick', '');
        $('#newRecordButton2').attr('onClick', '');
        $('#newRecordButton3').attr('onClick', "newRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 1 + "\")");
        $('#newRecordButton4').attr('onClick', "newRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 2 + "\")");
        $('#newRecordButton5').attr('onClick', "newRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 3 + "\")");
        $('#newRecordButton6').attr('onClick', "newRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 4 + "\")");
        $('#newRecordButton7').attr('onClick', "newRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 5 + "\")");
    } else {
        $('#newRecordButton1').addClass('btn btn-success disabled');
        $('#newRecordButton2').addClass('btn dropdown-toggle btn-success disabled');
        $('#newRecordButton1').removeClass();
        $('#newRecordButton2').removeClass();
        $('#newRecordButton3').removeClass();
        $('#newRecordButton4').removeClass();
        $('#newRecordButton5').removeClass();
        $('#newRecordButton6').removeClass();
        $('#newRecordButton7').removeClass();
    }
    $('#updateRecordButton1').removeClass();
    $('#updateRecordButton2').removeClass();
    $('#updateRecordButton3').removeClass();
    $('#updateRecordButton4').removeClass();
    $('#updateRecordButton5').removeClass();
    $('#updateRecordButton1').addClass('btn btn-info disabled');
    $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info disabled');
    $('#updateRecordButton1').attr('onClick', '');
    $('#updateRecordButton2').attr('onClick', '');
    $('#updateRecordButton3').attr('onClick', '');
    $('#updateRecordButton4').attr('onClick', '');
    $('#updateRecordButton5').attr('onClick', '');
    $('#deleteRecordButton').removeClass();
    $('#deleteRecordButton').addClass('btn btn-danger disabled');
    $('#deleteRecordButton').attr('onClick', '');
    $('#postRecordButton').removeClass();
    $('#postRecordButton').addClass('btn btn-info');
    $('#postRecordButton').attr('onClick', '');
    $('#firstRecordButton').removeClass();
    $('#firstRecordButton').addClass('btn btn-default');
    $('#firstRecordButton').attr('onClick', "firstRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlPaymentVoucherDetail + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
    $('#previousRecordButton').removeClass();
    $('#previousRecordButton').addClass('btn btn-default disabled');
    $('#previousRecordButton').attr('onClick', '');
    $('#nextRecordButton').removeClass();
    $('#nextRecordButton').addClass('btn btn-default disabled');
    $('#nextRecordButton').attr('onClick', '');
    $('#endRecordButton').removeClass();
    $('#endRecordButton').addClass('btn btn-default');
    $('#endRecordButton').attr('onClick', "endRecord\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlPaymentVoucherDetail + "\",,\"" + securityToken + "\",\"" + updateAccess + "\")");
    $("#paymentVoucherId").val('');
    $("#paymentVoucherIdHelpMe").empty();
    $("#paymentVoucherIdHelpMe").html('');

    $("#bankId").val('');
    $("#bankIdHelpMe").empty();
    $("#bankIdHelpMe").html('');
    $('#bankId').trigger("chosen:updated");
    $("#businessPartnerCategoryId").val('');
    $("#businessPartnerCategoryIdHelpMe").empty();
    $("#businessPartnerCategoryIdHelpMe").html('');
    $('#businessPartnerCategoryId').trigger("chosen:updated");
    $("#businessPartnerId").val('');
    $("#businessPartnerIdHelpMe").empty();
    $("#businessPartnerIdHelpMe").html('');
    $('#businessPartnerId').trigger("chosen:updated");
    $("#paymentTypeId").val('');
    $("#paymentTypeIdHelpMe").empty();
    $("#paymentTypeIdHelpMe").html('');
    $('#paymentTypeId').trigger("chosen:updated");
    $("#documentNumber").val('');
    $("#documentNumberHelpMe").empty();
    $("#documentNumberHelpMe").html('');
    $("#referenceNumber").val('');
    $("#referenceNumberHelpMe").empty();
    $("#referenceNumberHelpMe").html('');
    $("#paymentVoucherDescription").val('');
    $("#paymentVoucherDescriptionHelpMe").empty();
    $("#paymentVoucherDescriptionHelpMe").html('');
    $('#paymentVoucherDescription').empty();
    $('#paymentVoucherDescription').val('');
    $("#paymentVoucherDate").val('');
    $("#paymentVoucherDateHelpMe").empty();
    $("#paymentVoucherDateHelpMe").html('');
    $("#paymentVoucherAmount").val('');
    $("#paymentVoucherAmountHelpMe").empty();
    $("#paymentVoucherAmountHelpMe").html('');
    $("#paymentVoucherChequeNumber").val('');
    $("#paymentVoucherChequeNumberHelpMe").empty();
    $("#paymentVoucherChequeNumberHelpMe").html('');
    $('#paymentVoucherChequeNumber').empty();
    $('#paymentVoucherChequeNumber').val('');
    $("#paymentVoucherPayee").val('');
    $("#paymentVoucherPayeeHelpMe").empty();
    $("#paymentVoucherPayeeHelpMe").html('');
    $("#chartOfAccountId9999").prop("disabled", "true");
    $("#chartOfAccountId9999").attr("disabled", "disabled");
    $("#chartOfAccountId9999").val('');
    $("#chartOfAccountId9999").trigger("chosen:updated");
    $("#paymentVoucherDetailAmount9999").prop("disabled", "true");
    $("#paymentVoucherDetailAmount9999").attr("disabled", "disabled");
    $("#paymentVoucherDetailAmount9999").val('');
    $("#tableBody").empty().html('');
}
function postRecord() {
    var css = $('#postRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        return false;
    }
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
                from: 'paymentVoucherDetail.php',
                securityToken: securityToken,
                leafId: leafId
            },
            beforeSend: function() {
                var $infoPanel = $("#infoPanel");
                $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();

                }
            },
            success: function(data) {
                // successful request; do something with the data
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
                            from: 'paymentVoucherDetail.php',
                            securityToken: securityToken,
                            leafId: leafId
                        },
                        beforeSend: function() {
                            var $infoPanel = $("#infoPanel");
                            $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
                            }
                        },
                        success: function(data) {
                            // successful request; do something with the data
                            if (data.success === true) {
                                // resetting field value
                                $('#paymentVoucherId').val(data.data.paymentVoucherId);
                                $('#bankId').val(data.data.bankId).trigger("chosen:updated");
                                $('#businessPartnerId')
                                        .val(data.data.businessPartnerId)
                                        .trigger("chosen:updated");
                                $('#paymentTypeId')
                                        .val(data.data.paymentTypeId)
                                        .trigger("chosen:updated");
                                $('#documentNumber').val(data.data.documentNumber);
                                $('#referenceNumber').val(data.data.referenceNumber);
                                $('#paymentVoucherDescription').val(data.data.paymentVoucherDescription);
                                var x = data.data.paymentVoucherDate;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#paymentVoucherDate').val(output);
                                $('#paymentVoucherAmount').val(data.data.paymentVoucherAmount);
                                $('#paymentVoucherChequeNumber').val(data.data.paymentVoucherChequeNumber);
                                $('#paymentVoucherPayee').val(data.data.paymentVoucherPayee);
                                $("#paymentVoucherId9999").val('');
                                $("#chartOfAccountId9999").prop("disabled", "false");
                                $("#chartOfAccountId9999").removeAttr("disabled", "").val('').trigger("chosen:updated");
                                $("#paymentVoucherDetailAmount9999").prop("disabled", "false");
                                $("#paymentVoucherDetailAmount9999").removeAttr("disabled", "");
                                $("#paymentVoucherDetailAmount9999").val('');
                                $.ajax({
                                    type: 'POST',
                                    url: urlPaymentVoucherDetail,
                                    data: {
                                        method: 'read',
                                        paymentVoucherId: data.firstRecord,
                                        output: 'table',
                                        from: 'paymentVoucherDetail.php',
                                        securityToken: securityToken,
                                        leafId: leafId
                                    },
                                    beforeSend: function() {
                                        var $infoPanel = $("#infoPanel");
                                        $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
                                        if ($infoPanel.is(':hidden')) {
                                            $infoPanel.show();

                                        }
                                    },
                                    success: function(data) {
                                        // successful request; do something with the data
                                        if (data.success === true) {
                                            // make sure empty
                                            $('#infoPanel').empty();
                                            $('#infoPanel').html('');
                                            //$('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> "+decodeURIComponent(t['loadingCompleteTextLabel'])+"</span>").delay(1000).fadeOut();
                                            $('#tableBody').empty();
                                            $('#tableBody').html('');
                                            $('#tableBody').html(data.tableData);
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
                                    $('#previousRecordButton').removeClass();
                                    $('#previousRecordButton').addClass('btn btn-default  disabled');
                                    $('#previousRecordButton').attr('onClick', '');
                                    $('#nextRecordButton').removeClass();
                                    $('#nextRecordButton').addClass('btn btn-default');
                                    $('#nextRecordButton').attr('onClick', '');
                                    $('#nextRecordButton').attr('onClick', "nextRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlPaymentVoucherDetail + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
                                    $('#firstRecordCounter').val(data.firstRecord);
                                    $('#previousRecordCounter').val(data.previousRecord);
                                    $('#nextRecordCounter').val(data.nextRecord);
                                    $('#lastRecordCounter').val(data.lastRecord);
                                    $('#newRecordButton1').removeClass();
                                    $('#newRecordButton2').removeClass();
                                    $('#newRecordButton3').removeClass();
                                    $('#newRecordButton4').removeClass();
                                    $('#newRecordButton5').removeClass();
                                    $('#newRecordButton6').removeClass();
                                    $('#newRecordButton7').removeClass();
                                    $('#newRecordButton1').addClass('btn btn-success disabled');
                                    $('#newRecordButton2').addClass('btn dropdown-toggle btn-success disabled');
                                    $('#newRecordButton3').addClass('disabled');
                                    $('#newRecordButton4').addClass('disabled');
                                    $('#newRecordButton5').addClass('disabled');
                                    $('#newRecordButton6').addClass('disabled');
                                    $('#newRecordButton7').addClass('disabled');
                                    $('#newRecordButton1').attr('onClick', '');
                                    $('#newRecordButton2').attr('onClick', '');
                                    $('#newRecordButton3').attr('onClick', '');
                                    $('#newRecordButton4').attr('onClick', '');
                                    $('#newRecordButton5').attr('onClick', '');
                                    $('#newRecordButton6').attr('onClick', '');
                                    $('#newRecordButton7').attr('onClick', '');
                                    $('#updateRecordButton1').removeClass();
                                    $('#updateRecordButton2').removeClass();
                                    $('#updateRecordButton3').removeClass();
                                    $('#updateRecordButton4').removeClass();
                                    $('#updateRecordButton5').removeClass();
                                    if (updateAccess === 1) {
                                        $('#updateRecordButton1').addClass('btn btn-info');
                                        $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info');
                                        $('#updateRecordButton1').attr('onClick', '');
                                        $('#updateRecordButton2').attr('onClick', '');
                                        $('#updateRecordButton3').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 1 + "\",\"" + deleteAccess + "\")");
                                        $('#updateRecordButton4').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 2 + "\",\"" + deleteAccess + "\")");
                                        $('#updateRecordButton5').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 3 + "\",\"" + deleteAccess + "\")");
                                    } else {
                                        $('#updateRecordButton1').addClass('btn btn-info disabled');
                                        $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info disabled');
                                        $('#updateRecordButton1').attr('onClick', '');
                                        $('#updateRecordButton2').attr('onClick', '');
                                        $('#updateRecordButton3').attr('onClick', '');
                                        $('#updateRecordButton4').attr('onClick', '');
                                        $('#updateRecordButton5').attr('onClick', '');
                                    }
                                    if (deleteAccess === 1) {
                                        $('#deleteRecordButton').removeClass();
                                        $('#deleteRecordButton').addClass('btn btn-danger');
                                        $('#deleteRecordButton').attr('onClick', '');
                                        $('#deleteRecordButton').attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + deleteAccess + "\")");
                                    } else {
                                        $('#deleteRecordButton').removeClass();
                                        $('#deleteRecordButton').addClass('btn btn-danger');
                                        $('#deleteRecordButton').attr('onClick', '');
                                    }
                                }
                                $('#infoPanel').empty();
                                $('#infoPanel').html("&nbsp;<img src='./images/icons/control-stop.png'> " + decodeURIComponent(t['firstButtonLabel']) + " ");
                                if ($('#infoPanel').is(':hidden')) {
                                    $('#infoPanel').show();
                                }
                            }
                        },
                        error: function(xhr) {
                            $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass().addClass('row');
                        }
                    });
                } else {
                    $('#infoPanel').empty();
                    $('#infoPanel').html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll-sweat.png'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
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
function endRecord(leafId, url, urlList, urlPaymentVoucherDetail, securityToken, updateAccess, deleteAccess) {
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
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                // this is where we append a loading image
                $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            },
            success: function(data) {
                // successful request; do something with the data
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
                            from: 'paymentVoucherDetail.php',
                            securityToken: securityToken,
                            leafId: leafId
                        },
                        beforeSend: function() {
                            var $infoPanel = $("#infoPanel");
                            $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();

                            }
                        },
                        success: function(data) {
                            // successful request; do something with the data
                            if (data.success === true) {
                                // reset field value
                                $('#paymentVoucherId').val(data.data.paymentVoucherId);
                                $('#bankId').val(data.data.bankId).trigger("chosen:updated");
                                $('#businessPartnerCategoryId').val(data.data.businessPartnerCategoryId).trigger("chosen:updated");
                                $('#businessPartnerId').val(data.data.businessPartnerId).trigger("chosen:updated");
                                $('#paymentTypeId').val(data.data.paymentTypeId).trigger("chosen:updated");
                                $('#documentNumber').val(data.data.documentNumber);
                                $('#referenceNumber').val(data.data.referenceNumber);
                                $('#paymentVoucherDescription').val(data.data.paymentVoucherDescription);
                                var x = data.data.paymentVoucherDate;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#paymentVoucherDate').val(output);
                                $('#paymentVoucherAmount').val(data.data.paymentVoucherAmount);
                                $('#paymentVoucherChequeNumber').val(data.data.paymentVoucherChequeNumber);
                                $('#paymentVoucherPayee').val(data.data.paymentVoucherPayee);
                                $('#from').val(data.data.from);
                                $('#isPrinted').val(data.data.isPrinted);
                                $('#isConform').val(data.data.isConform);
                                $('#isChequePrinted').val(data.data.isChequePrinted);
                                $("#paymentVoucherId9999").val('');
                                $("#chartOfAccountId9999").prop("disabled", "false");
                                $("#chartOfAccountId9999").removeAttr("disabled", "");
                                $("#chartOfAccountId9999").val('');
                                $("#chartOfAccountId9999").trigger("chosen:updated");
                                $("#paymentVoucherDetailAmount9999").prop("disabled", "false");
                                $("#paymentVoucherDetailAmount9999").removeAttr("disabled", "");
                                $("#paymentVoucherDetailAmount9999").val('');
                                $.ajax({
                                    type: 'POST',
                                    url: urlPaymentVoucherDetail,
                                    data: {
                                        method: 'read',
                                        paymentVoucherId: data.lastRecord,
                                        output: 'table',
                                        from: 'paymentVoucherDetail.php',
                                        securityToken: securityToken,
                                        leafId: leafId
                                    },
                                    beforeSend: function() {
                                        // this is where we append a loading image
                                        $('#infoPanel').empty();
                                        $('#infoPanel').html('');
                                        $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                                        if ($('#infoPanel').is(':hidden')) {
                                            $('#infoPanel').show();
                                        }
                                    },
                                    success: function(data) {
                                        // successful request; do something with the data
                                        if (data.success === true) {
                                            // make sure empty
                                            $('#infoPanel').empty().html('').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
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
                                        ;
                                    }
                                });
                                if (data.lastRecord !== 0) {
                                    $('#previousRecordButton').removeClass();
                                    $('#previousRecordButton').addClass('btn btn-default');
                                    $('#previousRecordButton').attr('onClick', "previousRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlPaymentVoucherDetail + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
                                    $('#nextRecordButton').removeClass();
                                    $('#nextRecordButton').addClass('btn btn-default disabled');
                                    $('#nextRecordButton').attr('onClick', '');
                                    $('#firstRecordCounter').val(data.firstRecord);
                                    $('#previousRecordCounter').val(data.previousRecord);
                                    $('#nextRecordCounter').val(data.nextRecord);
                                    $('#lastRecordCounter').val(data.lastRecord);
                                    $('#newRecordButton1').removeClass();
                                    $('#newRecordButton2').removeClass();
                                    $('#newRecordButton3').removeClass();
                                    $('#newRecordButton4').removeClass();
                                    $('#newRecordButton5').removeClass();
                                    $('#newRecordButton6').removeClass();
                                    $('#newRecordButton7').removeClass();
                                    $('#newRecordButton1').addClass('btn btn-success disabled');
                                    $('#newRecordButton2').addClass('btn dropdown-toggle btn-success disabled');
                                    $('#newRecordButton3').addClass('disabled');
                                    $('#newRecordButton4').addClass('disabled');
                                    $('#newRecordButton5').addClass('disabled');
                                    $('#newRecordButton6').addClass('disabled');
                                    $('#newRecordButton7').addClass('disabled');
                                    $('#newRecordButton1').attr('onClick', '');
                                    $('#newRecordButton2').attr('onClick', '');
                                    $('#newRecordButton3').attr('onClick', '');
                                    $('#newRecordButton4').attr('onClick', '');
                                    $('#newRecordButton5').attr('onClick', '');
                                    $('#newRecordButton6').attr('onClick', '');
                                    $('#newRecordButton7').attr('onClick', '');
                                    $('#updateRecordButton1').removeClass();
                                    $('#updateRecordButton2').removeClass();
                                    $('#updateRecordButton3').removeClass();
                                    $('#updateRecordButton4').removeClass();
                                    $('#updateRecordButton5').removeClass();
                                    if (updateAccess === 1) {
                                        $('#updateRecordButton1').addClass('btn btn-info');
                                        $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info');
                                        $('#updateRecordButton1').attr('onClick', '');
                                        $('#updateRecordButton2').attr('onClick', '');
                                        $('#updateRecordButton3').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 1 + "\",\"" + deleteAccess + "\")");
                                        $('#updateRecordButton4').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 2 + "\",\"" + deleteAccess + "\")");
                                        $('#updateRecordButton5').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 3 + "\",\"" + deleteAccess + "\")");
                                    } else {
                                        $('#updateRecordButton1').addClass('btn btn-info disabled');
                                        $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info disabled');
                                        $('#updateRecordButton1').attr('onClick', '');
                                        $('#updateRecordButton2').attr('onClick', '');
                                        $('#updateRecordButton3').attr('onClick', '');
                                        $('#updateRecordButton4').attr('onClick', '');
                                        $('#updateRecordButton5').attr('onClick', '');
                                    }
                                    if (deleteAccess === 1) {
                                        $('#deleteRecordButton')
                                                .removeClass()
                                                .addClass('btn btn-danger')
                                                .attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + deleteAccess + "\")");
                                    } else {
                                        $('#deleteRecordButton')
                                                .removeClass()
                                                .addClass('btn btn-danger')
                                                .attr('onClick', '');
                                    }
                                }
                            }
                        },
                        error: function(xhr) {
                            $('#infoError').empty();
                            $('#infoError').html('');
                            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass();
                            $('#infoErrorRowFluid').addClass('row');
                        }
                    });
                } else {
                    $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                }
                $('#infoPanel')
                        .empty()
                        .html('')
                        .html("&nbsp;<img src='./images/icons/control-stop-180.png'> " + decodeURIComponent(t['endButtonLabel']) + " ");
            },
            error: function(xhr) {
                $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid').removeClass().addClass('row');
            }
        });
    }
}
function previousRecord(leafId, url, urlList, urlPaymentVoucherDetail, securityToken, updateAccess, deleteAccess) {
    var css = $('#previousRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $('#newButton').removeClass();
        if ($('#previousRecordCounter').val() === '' || $('#previousRecordCounter').val() === undefined) {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-danger'>" + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }
        if (parseFloat($('#previousRecordCounter').val()) > 0 && parseFloat($('#previousRecordCounter').val()) < parseFloat($('#lastRecordCounter').val())) {
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'read',
                    paymentVoucherId: $('#previousRecordCounter').val(),
                    output: 'json',
                    from: 'paymentVoucherDetail.php',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var $infoPanel = $("#infoPanel");
                    $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();

                    }
                },
                success: function(data) {
                    // successful request; do something with the data
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

                        x = data.data.paymentVoucherChequeDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#paymentVoucherChequeDate').val(output);

                        $('#paymentVoucherAmount').val(data.data.paymentVoucherAmount);
                        $('#paymentVoucherChequeNumber').val(data.data.paymentVoucherChequeNumber);
                        $('#paymentVoucherPayee').val(data.data.paymentVoucherPayee);
                        $("#paymentVoucherId9999").val('');
                        $("#chartOfAccountId9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('')
                                .trigger("chosen:updated");
                        $("#paymentVoucherDetailAmount9999")
                                .prop("disabled", "false")
                                .removeAttr("disabled", "")
                                .val('');
                        $.ajax({
                            type: 'POST',
                            url: urlPaymentVoucherDetail,
                            data: {
                                method: 'read',
                                paymentVoucherId: $('#previousRecordCounter').val(),
                                output: 'table',
                                from: 'paymentVoucherDetail.php',
                                securityToken: securityToken,
                                leafId: leafId
                            },
                            beforeSend: function() {
                                // this is where we append a loading image
                                $('#infoPanel').empty();
                                $('#infoPanel').html('');
                                $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                                if ($('#infoPanel').is(':hidden')) {
                                    $('#infoPanel').show();
                                }
                            },
                            success: function(data) {
                                // successful request; do something with the data
                                if (data.success === true) {
                                    // make sure empty
                                    $('#infoPanel').empty();
                                    $('#infoPanel').html('');
                                    $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                    $('#tableBody').empty();
                                    $('#tableBody').html('');
                                    $('#tableBody').html(data.tableData);
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
                        $('#newRecordButton1').removeClass();
                        $('#newRecordButton2').removeClass();
                        $('#newRecordButton3').removeClass();
                        $('#newRecordButton4').removeClass();
                        $('#newRecordButton5').removeClass();
                        $('#newRecordButton6').removeClass();
                        $('#newRecordButton7').removeClass();
                        $('#newRecordButton1').addClass('btn btn-success disabled');
                        $('#newRecordButton2').addClass('btn dropdown-toggle btn-success disabled');
                        $('#newRecordButton3').addClass('disabled');
                        $('#newRecordButton4').addClass('disabled');
                        $('#newRecordButton5').addClass('disabled');
                        $('#newRecordButton6').addClass('disabled');
                        $('#newRecordButton7').addClass('disabled');
                        $('#newRecordButton1').attr('onClick', '');
                        $('#newRecordButton2').attr('onClick', '');
                        $('#newRecordButton3').attr('onClick', '');
                        $('#newRecordButton4').attr('onClick', '');
                        $('#newRecordButton5').attr('onClick', '');
                        $('#newRecordButton6').attr('onClick', '');
                        $('#newRecordButton7').attr('onClick', '');
                        $('#updateRecordButton1').removeClass();
                        $('#updateRecordButton2').removeClass();
                        $('#updateRecordButton3').removeClass();
                        $('#updateRecordButton4').removeClass();
                        $('#updateRecordButton5').removeClass();
                        if (updateAccess === 1) {
                            $('#updateRecordButton1').addClass('btn btn-info');
                            $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info');
                            $('#updateRecordButton1').attr('onClick', '');
                            $('#updateRecordButton2').attr('onClick', '');
                            $('#updateRecordButton3').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 1 + "\",\"" + deleteAccess + "\")");
                            $('#updateRecordButton4').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 2 + "\",\"" + deleteAccess + "\")");
                            $('#updateRecordButton5').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 3 + "\",\"" + deleteAccess + "\")");
                        } else {
                            $('#updateRecordButton1').addClass('btn btn-info disabled');
                            $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info disabled');
                            $('#updateRecordButton1').attr('onClick', '');
                            $('#updateRecordButton2').attr('onClick', '');
                            $('#updateRecordButton3').attr('onClick', '');
                            $('#updateRecordButton4').attr('onClick', '');
                            $('#updateRecordButton5').attr('onClick', '');
                        }
                        if (deleteAccess === 1) {
                            $('#deleteRecordButton')
                                    .removeClass()
                                    .addClass('btn btn-danger')
                                    .attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + deleteAccess + "\")");
                        } else {
                            $('#deleteRecordButton')
                                    .removeClass()
                                    .addClass('btn btn-danger')
                                    .attr('onClick', '');
                        }
                        $('#firstRecordCounter').val(data.firstRecord);
                        $('#previousRecordCounter').val(data.previousRecord);
                        $('#nextRecordCounter').val(data.nextRecord);
                        $('#lastRecordCounter').val(data.lastRecord);
                        if (parseFloat(data.nextRecord) <= parseFloat(data.lastRecord)) {
                            $('#nextRecordButton')
                                    .removeClass()
                                    .addClass('btn btn-info')
                                    .attr('onClick', '')
                                    .attr('onClick', "nextRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlPaymentVoucherDetail + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
                        } else {
                            $('#nextRecordButton')
                                    .removeClass()
                                    .addClass('btn btn-info disabled')
                                    .attr('onClick', '');
                        }
                        if (parseFloat(data.previousRecord) === 0) {
                            $('#infoPanel')
                                    .empty()
                                    .html("&nbsp;<img src='./images/icons/exclamation.png'> " + decodeURIComponent(t['firstButtonLabel']) + " ");
                            $('#previousRecordButton')
                                    .removeClass()
                                    .addClass('btn btn-info disabled')
                                    .attr('onClick', '');
                        } else {
                            $('#infoPanel')
                                    .empty()
                                    .html('')
                                    .html("&nbsp;<img src='./images/icons/control-180.png'> " + decodeURIComponent(t['previousButtonLabel']) + " ");
                            if ($('#infoPanel').is(':hidden')) {
                                $('#infoPanel').show();
                            }
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
            // debugging purpose only
        }
    }
}
function nextRecord(leafId, url, urlList, urlPaymentVoucherDetail, securityToken, updateAccess, deleteAccess) {
    var css = $('#nextRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $('#newButton').removeClass();
        if ($('#nextRecordCounter').val() === '' || $('#nextRecordCounter').val() === undefined) {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-danger'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }
        if (parseFloat($('#nextRecordCounter').val()) <= parseFloat($('#lastRecordCounter').val())) {
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'read',
                    paymentVoucherId: $('#nextRecordCounter').val(),
                    output: 'json',
                    from: 'paymentVoucherDetail.php',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var $infoPanel = $("#infoPanel");
                    $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();

                    }
                },
                success: function(data) {
                    // successful request; do something with the data
                    if (data.success === true) {
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

                        x = data.data.paymentChequeDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#paymentVoucherChequeDate').val(output);

                        $('#paymentVoucherAmount').val(data.data.paymentVoucherAmount);
                        $('#paymentVoucherChequeNumber').val(data.data.paymentVoucherChequeNumber);
                        $('#paymentVoucherPayee').val(data.data.paymentVoucherPayee);
                        $('#newRecordButton1').removeClass();
                        $('#newRecordButton2').removeClass();
                        $('#newRecordButton3').removeClass();
                        $('#newRecordButton4').removeClass();
                        $('#newRecordButton5').removeClass();
                        $('#newRecordButton6').removeClass();
                        $('#newRecordButton7').removeClass();
                        $('#newRecordButton1').addClass('btn btn-success disabled');
                        $('#newRecordButton2').addClass('btn dropdown-toggle btn-success disabled');
                        $('#newRecordButton3').addClass('disabled');
                        $('#newRecordButton4').addClass('disabled');
                        $('#newRecordButton5').addClass('disabled');
                        $('#newRecordButton6').addClass('disabled');
                        $('#newRecordButton7').addClass('disabled');
                        $('#newRecordButton1').attr('onClick', '');
                        $('#newRecordButton2').attr('onClick', '');
                        $('#newRecordButton3').attr('onClick', '');
                        $('#newRecordButton4').attr('onClick', '');
                        $('#newRecordButton5').attr('onClick', '');
                        $('#newRecordButton6').attr('onClick', '');
                        $('#newRecordButton7').attr('onClick', '');
                        $('#updateRecordButton1').removeClass();
                        $('#updateRecordButton2').removeClass();
                        $('#updateRecordButton3').removeClass();
                        $('#updateRecordButton4').removeClass();
                        $('#updateRecordButton5').removeClass();
                        if (updateAccess === 1) {
                            $('#updateRecordButton1').addClass('btn btn-info').attr('onClick', '');
                            $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info').attr('onClick', '');
                            $('#updateRecordButton3').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 1 + "\",\"" + deleteAccess + "\")");
                            $('#updateRecordButton4').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 2 + "\",\"" + deleteAccess + "\")");
                            $('#updateRecordButton5').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 3 + "\",\"" + deleteAccess + "\")");
                        } else {
                            $('#updateRecordButton1')
                                    .addClass('btn btn-info disabled')
                                    .attr('onClick', '');
                            $('#updateRecordButton2')
                                    .addClass('btn dropdown-toggle btn-info disabled')
                                    .attr('onClick', '');
                            $('#updateRecordButton3').attr('onClick', '');
                            $('#updateRecordButton4').attr('onClick', '');
                            $('#updateRecordButton5').attr('onClick', '');
                        }
                        if (deleteAccess === 1) {
                            $('#deleteRecordButton')
                                    .removeClass()
                                    .addClass('btn btn-danger')
                                    .attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + deleteAccess + "\")");
                        } else {
                            $('#deleteRecordButton')
                                    .removeClass()
                                    .addClass('btn btn-danger')
                                    .attr('onClick', '');
                        }
                        $("#paymentVoucherId9999").prop("disabled", "false");
                        $("#paymentVoucherId9999").removeAttr("disabled", "");
                        $("#paymentVoucherId9999").val('');
                        $("#chartOfAccountId9999").prop("disabled", "false");
                        $("#chartOfAccountId9999").removeAttr("disabled", "");
                        $("#chartOfAccountId9999").val('');
                        $("#chartOfAccountId9999").trigger("chosen:updated");
                        $("#paymentVoucherDetailAmount9999").prop("disabled", "false");
                        $("#paymentVoucherDetailAmount9999").removeAttr("disabled", "");
                        $("#paymentVoucherDetailAmount9999").val('');
                        $.ajax({
                            type: 'POST',
                            url: urlPaymentVoucherDetail,
                            data: {
                                method: 'read',
                                paymentVoucherId: $('#nextRecordCounter').val(),
                                output: 'table',
                                securityToken: securityToken,
                                from: 'paymentVoucherDetail.php',
                                leafId: leafId
                            },
                            beforeSend: function() {
                                // this is where we append a loading image
                                var $infoPanel = $("#infoPanel");
                                $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
                                if ($infoPanel.is(':hidden')) {
                                    $infoPanel.show();

                                }
                            },
                            success: function(data) {
                                // successful request; do something with the data
                                if (data.success === true) {
                                    // make sure empty
                                    $('#tableBody')
                                            .empty()
                                            .html('')
                                            .html(data.tableData);
                                    $(".chzn-select").chosen({
                                        search_contains: true
                                    });
                                    $(".chzn-select-deselect").chosen({
                                        allow_single_deselect: true
                                    });
                                    $('#infoPanel').empty();
                                    $('#infoPanel').html('');
                                    $('#infoPanel').html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                    if ($('#infoPanel').is(':hidden')) {
                                        $('#infoPanel').show();
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
                            $('#previousRecordButton')
                                    .removeClass()
                                    .addClass('btn btn-info')
                                    .attr('onClick', "previousRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlPaymentVoucherDetail + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
                        } else {
                            $('#previousRecordButton')
                                    .removeClass()
                                    .addClass('btn btn-info disabled')
                                    .attr('onClick', '');
                        }
                        if (parseFloat(data.nextRecord) === 0) {
                            $('#nextRecordButton')
                                    .removeClass()
                                    .addClass('btn btn-info disabled')
                                    .attr('onClick', '');
                            $('#infoPanel').html("&nbsp;<img src='./images/icons/exclamation.png'> " + decodeURIComponent(t['endButtonLabel']) + " ");
                            if ($('#infoPanel').is(':hidden')) {
                                $('#infoPanel').show();
                            }
                        } else {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html("&nbsp;<img src='./images/icons/control.png'> " + decodeURIComponent(t['nextButtonLabel']) + " ");
                            if ($('#infoPanel').is(':hidden')) {
                                $('#infoPanel').show();
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
