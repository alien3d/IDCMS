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
            securityToken: securityToken,
            from: 'paymentVoucher.php',
            leafId: leafId,
            filter: 'bank'
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
            if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'>" + data.message + "</span>");
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $("#bankId").empty();
                $("#bankId").html('');
                $("#bankId").html(data.data);
                $("#bankId").trigger("chosen:updated");
                $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</spanm>").delay(5000).fadeOut();
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
}
function getBusinessPartnerCategory(leafId, url, securityToken) {
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
            from: 'paymentVoucher.php',
            securityToken: securityToken,
            leafId: leafId,
            filter: 'businessPartnerCategory'
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
            if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'>" + data.message + "</span>");
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $("#businessPartnerCategoryId").empty();
                $("#businessPartnerCategoryId").html('');
                $("#businessPartnerCategoryId").html(data.data);
                $("#businessPartnerCategoryId").trigger("chosen:updated");
                $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</spanm>").delay(5000).fadeOut();
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
            from: 'paymentVoucher.php',
            securityToken: securityToken,
            leafId: leafId,
            filter: 'businessPartner'
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
            if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'>" + data.message + "</span>");
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $("#businessPartnerId").empty();
                $("#businessPartnerId").html('');
                $("#businessPartnerId").html(data.data);
                $("#businessPartnerId").trigger("chosen:updated");
                $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</spanm>").delay(5000).fadeOut();
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
}
function checkDuplicate(leafId, page, securityToken) {
    if ($("#paymentVoucherCode").val().length == 0) {
        alert(t['oddTextLabel']);
        return false;
    }
    $.ajax({
        type: 'GET',
        url: page,
        data: {
            paymentVoucherCode: $("#paymentVoucherCode").val(),
            method: 'duplicate',
            from: 'paymentVoucher.php',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == true) {
                if (data.total != 0) {
                    $("#paymentVoucherCode").empty();
                    $("#paymentVoucherCode").val('');
                    $("#paymentVoucherCode").focus();
                    $("#paymentVoucherCodeForm").removeClass();
                    $("#paymentVoucherCodeForm").addClass("col-md-12 form-group has-error");
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $("#infoPanel").html("<img src='./images/icons/status-busy.png'> " + t['codeDuplicateTextLabel']).delay(5000).fadeOut();
                } else {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $("#infoPanel").html("<img src='./images/icons/status.png'> " + t['codeAvailableTextLabel']).delay(5000).fadeOut();
                }
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
                $("#paymentVoucherForm").removeClass();
                $("#paymentVoucherForm").addClass("col-md-12 form-group has-error");
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
            from: 'paymentVoucher.php',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == false) {
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
                if (type == 1) {
                    $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                } else if (type == 2) {
                    $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['deleteRecordTextLabel']) + "</span>");
                }
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
                $(document).scrollTop();
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
}
function ajaxQuerySearchAll(leafId, url, securityToken) {
    // un hide button search
    $('#clearSearch').removeClass();
    $('#clearSearch').addClass('btn');
    // unlimited for searching because  lazy paging.
    var queryGrid = $('#query').val();
    var queryWidget = $('#queryWidget').val();
    var queryText;
    if (queryGrid != undefined) {
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
            from: 'paymentVoucher.php',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == false) {
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
                $('#infoPanel').html("&nbsp;<img src='./images/icons/magnifier-zoom-actual-equal.png'> <b>" + decodeURIComponent(t['filterTextLabel']) + '</b>: ' + queryText + "");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
                $(document).scrollTop();
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
}
function ajaxQuerySearchAllCharacter(leafId, url, securityToken, character) {
    // unhide button search
    $('#clearSearch').removeClass();
    $('#clearSearch').addClass('btn');
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
            from: 'paymentVoucher.php',
            securityToken: securityToken,
            leafId: leafId,
            character: character
        },
        beforeSend: function() {
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == false) {
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
                $('#infoPanel').html("&nbsp;<img src='./images/icons/magnifier-zoom-actual-equal.png'> <b>" + decodeURIComponent(t['filterTextLabel']) + "</b>: " + character + " ");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
                $(document).scrollTop();
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
    if (dateRangeStart.length == 0) {
        dateRangeStart = $('#dateRangeStart').val();
    }
    if (dateRangeEnd.length == 0) {
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
            from: 'paymentVoucher.php',
            securityToken: securityToken,
            leafId: leafId,
            dateRangeStart: dateRangeStart,
            dateRangeEnd: dateRangeEnd,
            dateRangeType: dateRangeType
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
            if (data.success == false) {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();

                $('#centerViewport').html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'>" + data.message + "</span>");
            } else {
                $('#centerViewport').html('');
                $('#centerViewport').empty();
                $('#centerViewport').removeClass();

                $('#centerViewport').append(data);
                $('#infoPanel').empty();
                if (dateRangeType == 'day') {
                    calendarPng = 'calendar-select-days.png';
                } else if (dateRangeType == 'week' || dateRangeType == 'between') {
                    calendarPng = 'calendar-select-week.png';
                } else if (dateRangeType == 'month') {
                    calendarPng = 'calendar-select-month.png';
                } else if (dateRangeType == 'year') {
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
                        if (dateRangeEnd.length == 0) {
                            strDate = "<b>" + t['dayTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ", " + dateStart.getFullYear();
                        } else {
                            strDate = "<b>" + t['betweenTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ", " + dateStart.getFullYear() + "&nbsp;<img src='./images/icons/arrow-curve-000-left.png'>&nbsp;" + dateEnd.getDayName() + ", " + dateEnd.getMonthName() + ", " + dateEnd.getDate() + ", " + dateEnd.getFullYear();
                        }
                        break;
                    case 'between':
                        if (dateRangeEnd.length == 0) {
                            strDate = "<b>" + t['dayTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ', ' + dateStart.getFullYear();
                        } else {
                            strDate = "<b>" + t['betweenTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ", " + dateStart.getFullYear() + "&nbsp;<img src='./images/icons/arrow-curve-000-left.png'>&nbsp;" + dateEnd.getDayName() + ", " + dateEnd.getMonthName() + ", " + dateEnd.getDate() + ", " + dateEnd.getFullYear();
                        }
                        break;
                }
                $('#infoPanel').empty();
                $('#infoPanel').html("<img src='./images/icons/" + calendarPng + "'> " + strDate + " ");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
                $(document).scrollTop();
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
            from: 'paymentVoucher.php',
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
            if (data.success == false) {
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
                $(document).scrollTop();
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
            from: 'paymentVoucher.php',
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
            if (data.success == false) {
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
                if (updateAccess == 1) {
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
                if (deleteAccess == 1) {
                    $('#deleteRecordButton').removeClass();
                    $('#deleteRecordButton').addClass('btn btn-danger');
                    $('#deleteRecordButton').attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + deleteAccess + "\")");
                } else {
                    $('#deleteRecordButton').removeClass();
                    $('#deleteRecordButton').addClass('btn btn-danger');
                    $('#deleteRecordButton').attr('onClick', '');
                }
                $(document).scrollTop();
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
}
function showModalDelete(paymentVoucherId, bankId, businessPartnerCategoryId, businessPartnerId, paymentTypeId, documentNumber, referenceNumber, paymentVoucherDescription, paymentVoucherDate, paymentVoucherAmount, paymentVoucherChequeNumber, paymentVoucherPayee, isPrinted, isConform, isChequePrinted) {
    // clear first old record if exist
    $('#paymentVoucherIdPreview').val('');
    $('#paymentVoucherIdPreview').val(decodeURIComponent(paymentVoucherId));

    $('#bankIdPreview').val('');
    $('#bankIdPreview').val(decodeURIComponent(bankId));

    $('#businessPartnerCategoryIdPreview').val('');
    $('#businessPartnerCategoryIdPreview').val(decodeURIComponent(businessPartnerCategoryId));

    $('#businessPartnerIdPreview').val('');
    $('#businessPartnerIdPreview').val(decodeURIComponent(businessPartnerId));

    $('#paymentTypeIdPreview').val('');
    $('#paymentTypeIdPreview').val(decodeURIComponent(paymentTypeId));

    $('#documentNumberPreview').val('');
    $('#documentNumberPreview').val(decodeURIComponent(documentNumber));

    $('#referenceNumberPreview').val('');
    $('#referenceNumberPreview').val(decodeURIComponent(referenceNumber));

    $('#paymentVoucherDescriptionPreview').val('');
    $('#paymentVoucherDescriptionPreview').val(decodeURIComponent(paymentVoucherDescription));

    $('#paymentVoucherDatePreview').val('');
    $('#paymentVoucherDatePreview').val(decodeURIComponent(paymentVoucherDate));

    $('#paymentVoucherAmountPreview').val('');
    $('#paymentVoucherAmountPreview').val(decodeURIComponent(paymentVoucherAmount));

    $('#paymentVoucherChequeNumberPreview').val('');
    $('#paymentVoucherChequeNumberPreview').val(decodeURIComponent(paymentVoucherChequeNumber));

    $('#paymentVoucherPayeePreview').val('');
    $('#paymentVoucherPayeePreview').val(decodeURIComponent(paymentVoucherPayee));

    $('#isPrintedPreview').val('');
    $('#isPrintedPreview').val(decodeURIComponent(isPrinted));

    $('#isConformPreview').val('');
    $('#isConformPreview').val(decodeURIComponent(isConform));

    $('#isChequePrintedPreview').val('');
    $('#isChequePrintedPreview').val(decodeURIComponent(isChequePrinted));

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
            from: 'paymentVoucher.php',
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
            if (data.success == true) {
                showMeModal('deletePreview', 0);
                showGrid(leafId, urlList, securityToken, 0, 10, 2);
            } else if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
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
}
function showFormCreateDetail(leafId, url, securityToken) {
    // checking based on row
    if ($('#countryId9999').val().length == 0) {
        $('#infoPanel').empty();
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['countryIdLabel'] + "</span>");
        $('#countryId9999HelpMe').empty();
        $('#countryId9999HelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['countryIdLabel'] + "</span>");
        $('#countryId9999').data('chosen').activate_action();
        return false;
    }
    if ($('#purchaseOrderId9999').val().length == 0) {
        $('#infoPanel').empty();
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['purchaseOrderIdLabel'] + "</span>");
        $('#purchaseOrderId9999HelpMe').empty();
        $('#purchaseOrderId9999HelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['purchaseOrderIdLabel'] + "</span>");
        $('#purchaseOrderId9999').data('chosen').activate_action();
        return false;
    }
    if ($('#paymentVoucherAllocationAmount9999').val().length == 0) {
        $('#infoPanel').empty();
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherAllocationAmountLabel'] + "</span>");
        $('#paymentVoucherAllocationAmount9999HelpMe').empty();
        $('#paymentVoucherAllocationAmount9999HelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherAllocationAmountLabel'] + "</span>");
        $('#paymentVoucherAllocationAmount9999').data('chosen').activate_action();
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
            countryId: $('#countryId9999').val(),
            purchaseOrderId: $('#purchaseOrderId9999').val(),
            from: 'paymentVoucher.php',
            paymentVoucherAllocationAmount: $('#paymentVoucherAllocationAmount9999').val(),
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
            if (data.success == true) {
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        method: 'read',
                        output: 'table',
                        offset: '0',
                        limit: '9999',
                        paymentVoucherId: $('#paymentVoucherId').val(),
                        from: 'paymentVoucher.php',
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
                        if (data.success == true) {
                            // make sure empty
                            $('#tableBody').empty();
                            $('#tableBody').html('');
                            $('#tableBody').html(data.tableData);

                            $("#countryId9999").prop("disabled", "false");
                            $("#countryId9999").removeAttr("disabled", "");
                            $("#countryId9999").val('');
                            $("#countryId9999").trigger("chosen:updated");
                            $("#purchaseOrderId9999").prop("disabled", "false");
                            $("#purchaseOrderId9999").removeAttr("disabled", "");
                            $("#purchaseOrderId9999").val('');
                            $("#purchaseOrderId9999").trigger("chosen:updated");
                            $("#paymentVoucherAllocationAmount9999").prop("disabled", "false");
                            $("#paymentVoucherAllocationAmount9999").removeAttr("disabled", "");
                            $("#paymentVoucherAllocationAmount9999").val('');

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
                        $('#infoError').empty();
                        $('#infoError').html('');
                        $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                        $('#infoErrorRowFluid').removeClass();
                        $('#infoErrorRowFluid').addClass('row');
                    }
                });
                $('#miniInfoPanel9999').html("<span class='label label-success'>&nbsp;<a class='close' data-dismiss='alert' href='#'>&times;</a><img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>").delay(1000).fadeOut();
            } else if (data.success == false) {
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
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
}
function showFormUpdateDetail(leafId, url, securityToken, paymentVoucherId) {
    // checking based on row
    if ($('#countryId' + paymentVoucherId).val().length == 0) {
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['countryIdLabel'] + "</span>");
        $('#countryId' + paymentVoucherId + 'HelpMe').empty();
        $('#countryId' + paymentVoucherId + 'HelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['countryIdLabel'] + "</span>");
        $('#countryId' + paymentVoucherId).data('chosen').activate_action();
        return false;
    }
    if ($('#purchaseOrderId' + paymentVoucherId).val().length == 0) {
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['purchaseOrderIdLabel'] + "</span>");
        $('#purchaseOrderId' + paymentVoucherId + 'HelpMe').empty();
        $('#purchaseOrderId' + paymentVoucherId + 'HelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['purchaseOrderIdLabel'] + "</span>");
        $('#purchaseOrderId' + paymentVoucherId).data('chosen').activate_action();
        return false;
    }
    if ($('#paymentVoucherAllocationAmount' + paymentVoucherId).val().length == 0) {
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['paymentVoucherAllocationAmountLabel'] + "</span>");
        $('#paymentVoucherAllocationAmount' + paymentVoucherId + 'HelpMe').empty();
        $('#paymentVoucherAllocationAmount' + paymentVoucherId + 'HelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['paymentVoucherAllocationAmountLabel'] + "</span>");
        $('#paymentVoucherAllocationAmount' + paymentVoucherId).data('chosen').activate_action();
        return false;
    }
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            method: 'save',
            output: 'json',
            paymentVoucherId: $('#paymentVoucherId').val(),
            countryId: $('#countryId' + paymentVoucherId).val(),
            purchaseOrderId: $('#purchaseOrderId' + paymentVoucherId).val(),
            paymentVoucherAllocationAmount: $('#paymentVoucherAllocationAmount' + paymentVoucherId).val(),
            from: 'paymentVoucher.php',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            $('#miniInfoPanel' + paymentVoucherId).empty();
            $('#miniInfoPanel' + paymentVoucherId).html('');
            $('#miniInfoPanel' + paymentVoucherId).html("<span class='label label-warning'> <img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == true) {
                $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['updateRecordTextLabel']) + "</span>");
                $('#miniInfoPanel' + paymentVoucherId).html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'><a class='close' data-dismiss='alert' href='#'>&times;</a></span>");
            } else if (data.success == false) {
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                $('#miniInfoPanel' + paymentVoucherId).html("<span class='label label-danger'>&nbsp; " + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
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
}
function showModalDeleteDetail(paymentVoucherId) {
    // clear first old record if exist
    $('#countryIdPreview').val('');
    $('#countryIdPreview').val(decodeURIComponent($("#countryId" + paymentVoucherId + " option:selected").text()));

    $('#purchaseOrderIdPreview').val('');
    $('#purchaseOrderIdPreview').val(decodeURIComponent($("#purchaseOrderId" + paymentVoucherId + " option:selected").text()));

    $('#paymentVoucherAllocationAmountPreview').val('');
    $('#paymentVoucherAllocationAmountPreview').val(decodeURIComponent($("#paymentVoucherAllocationAmount" + paymentVoucherId).val()));

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
            paymentVoucherId: $('#paymentVoucherIdPreview').val(),
            from: 'paymentVoucher.php',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == true) {
                showMeModal('deleteDetailPreview', 0);
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['deleteRecordTextLabel']) + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
                removeMeTr($('#paymentVoucherIdPreview').val())
            } else if (data.success == false) {
                $('#infoPanel').html("<span class='label label-danger'> " + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
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
    if (counter == 0) {
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
            from: 'paymentVoucher.php',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == true) {
                showGrid(leafId, urlList, securityToken, 0, 10, 2);
            } else if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            } else {
                $('#infoPanel').empty()
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
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
}
function reportRequest(leafId, url, securityToken, mode) {
    $.ajax({
        type: 'GET',
        url: url,
        data: {
            method: 'report',
            mode: mode,
            from: 'paymentVoucher.php',
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            // this is where we append a loading image
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        },
        success: function(data) {
            // successful request; do something with the data
            if (data.success == true) {
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
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }
    });
}
function auditRecord(leafId, url, securityToken) {
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
        if (type == 1) {
            // new record and continue.Reset Current Record
            if ($('#bankId').val().length == 0) {
                $('#bankIdHelpMe').empty();
                $('#bankIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['bankIdLabel'] + " </span>");
                $('#bankId').data('chosen').activate_action();
                return false;
            }
            if ($('#businessPartnerId').val().length == 0) {
                $('#businessPartnerIdHelpMe').empty();
                $('#businessPartnerIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $('#businessPartnerId').data('chosen').activate_action();
                return false;
            }
            if ($('#paymentVoucherDescription').val().length == 0) {
                $('#paymentVoucherDescriptionHelpMe').empty();
                $('#paymentVoucherDescriptionHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDescriptionLabel'] + " </span>");
                $('#paymentVoucherDescriptionForm').addClass('form-group has-error');
                $('#paymentVoucherDescription').focus();
                return false;
            }
            if ($('#paymentVoucherDate').val().length == 0) {
                $('#paymentVoucherDateHelpMe').empty();
                $('#paymentVoucherDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDateLabel'] + " </span>");
                $('#paymentVoucherDateForm').addClass('form-group has-error');
                $('#paymentVoucherDate').focus();
                return false;
            }
            if ($('#paymentVoucherAmount').val().length == 0) {
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
                    from: 'paymentVoucher.php',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    // this is where we append a loading image
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                },
                success: function(data) {
                    // successful request; do something with the data
                    if (data.success == true) {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>").delay(1000).fadeOut();
                        if ($('#infoPanel').is(':hidden')) {
                            $('#infoPanel').show();
                        }
                        // resetting field value
                        $('#bankId').val('');
                        $('#bankId').trigger("chosen:updated");
                        $('#bankIdHelpMe').empty();
                        $('#bankIdHelpMe').html('');
                        $('#businessPartnerCategoryId').val('');
                        $('#businessPartnerCategoryId').trigger("chosen:updated");
                        $('#businessPartnerCategoryIdHelpMe').empty();
                        $('#businessPartnerCategoryIdHelpMe').html('');
                        $('#businessPartnerId').val('');
                        $('#businessPartnerId').trigger("chosen:updated");
                        $('#businessPartnerIdHelpMe').empty();
                        $('#businessPartnerIdHelpMe').html('');
                        $('#paymentTypeId').val('');
                        $('#paymentTypeId').trigger("chosen:updated");
                        $('#paymentTypeIdHelpMe').empty();
                        $('#paymentTypeIdHelpMe').html('');
                        $('#documentNumber').val('');
                        $('#documentNumber').val('');
                        $('#documentNumberHelpMe').empty();
                        $('#documentNumberHelpMe').html('');
                        $('#referenceNumber').val('');
                        $('#referenceNumber').val('');
                        $('#referenceNumberHelpMe').empty();
                        $('#referenceNumberHelpMe').html('');
                        $('#paymentVoucherDescription').val('');
                        $('#paymentVoucherDescriptionForm').removeClass().addClass('col-md-12 form-group');
                        $('#paymentVoucherDescription').val('');
                        $('#paymentVoucherDescriptionHelpMe').empty();
                        $('#paymentVoucherDescriptionHelpMe').html('');
                        $('#paymentVoucherDate').val('');
                        $('#paymentVoucherDateHelpMe').empty();
                        $('#paymentVoucherDateHelpMe').html('');
                        $('#paymentVoucherChequeDate').val('');
                        $('#paymentVoucherChequeDateHelpMe').empty();
                        $('#paymentVoucherChequeDateHelpMe').html('');
                        $('#paymentVoucherAmount').val('');
                        $('#paymentVoucherAmountHelpMe').empty();
                        $('#paymentVoucherAmountHelpMe').html('');
                        $('#paymentVoucherChequeNumber').val('');
                        $('#paymentVoucherChequeNumberForm').removeClass().addClass('col-md-12 form-group');
                        $('#paymentVoucherChequeNumber').val('');
                        $('#paymentVoucherChequeNumberHelpMe').empty();
                        $('#paymentVoucherChequeNumberHelpMe').html('');
                        $('#paymentVoucherPayee').val('');
                        $('#paymentVoucherPayee').val('');
                        $('#paymentVoucherPayeeHelpMe').empty();
                        $('#paymentVoucherPayeeHelpMe').html('');
                        $('#isPrinted').val('');
                        $('#isPrintedHelpMe').empty();
                        $('#isPrintedHelpMe').html('');
                        $('#isConform').val('');
                        $('#isConformHelpMe').empty();
                        $('#isConformHelpMe').html('');
                        $('#isChequePrinted').val('');
                        $('#isChequePrintedHelpMe').empty();
                        $('#isChequePrintedHelpMe').html('');
                    } else if (data.success == false) {
                        $('#infoPanel').empty();
                        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                        if ($('#infoPanel').is(':hidden')) {
                            $('#infoPanel').show();
                        }
                    }
                },
                error: function(xhr) {
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                }
            });
        } else if (type == 2) {
            // new record and update  or delete record
            if ($('#bankId').val().length == 0) {
                $('#bankIdHelpMe').empty();
                $('#bankIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['bankIdLabel'] + " </span>");
                $('#bankId').data('chosen').activate_action();
                return false;
            }
            if ($('#businessPartnerId').val().length == 0) {
                $('#businessPartnerIdHelpMe').empty();
                $('#businessPartnerIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $('#businessPartnerId').data('chosen').activate_action();
                return false;
            }
            if ($('#paymentVoucherDescription').val().length == 0) {
                $('#paymentVoucherDescriptionHelpMe').empty();
                $('#paymentVoucherDescriptionHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDescriptionLabel'] + " </span>");
                $('#paymentVoucherDescriptionForm').addClass('form-group has-error');
                $('#paymentVoucherDescription').focus();
                return false;
            }
            if ($('#paymentVoucherDate').val().length == 0) {
                $('#paymentVoucherDateHelpMe').empty();
                $('#paymentVoucherDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDateLabel'] + " </span>");
                $('#paymentVoucherDateForm').addClass('form-group has-error');
                $('#paymentVoucherDate').focus();
                return false;
            }
            if ($('#paymentVoucherAmount').val().length == 0) {
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
                    from: 'paymentVoucher.php',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    // this is where we append a loading image
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                },
                success: function(data) {
                    // successful request; do something with the data
                    if (data.success == true) {
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
                        if (updateAccess == 1) {
                            $('#updateRecordButton1').addClass('btn btn-info');
                            $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info');
                            $('#updateRecordButton1').attr('onClick', '');
                            $('#updateRecordButton2').attr('onClick', '');
                            $('#updateRecordButton3').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 1 + "\")");
                            $('#updateRecordButton4').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 2 + "\")");
                            $('#updateRecordButton5').attr('onClick', "updateRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + 3 + "\")");
                        } else {
                            $('#updateRecordButton1').addClass('btn btn-info disabled');
                            $('#updateRecordButton2').addClass('btn dropdown-toggle btn-info disabled');
                            $('#updateRecordButton1').attr('onClick', '');
                            $('#updateRecordButton2').attr('onClick', '');
                            $('#updateRecordButton3').attr('onClick', '');
                            $('#updateRecordButton4').attr('onClick', '');
                            $('#updateRecordButton5').attr('onClick', '');
                        }
                        if (deleteAccess == 1) {
                            $('#deleteRecordButton').removeClass();
                            $('#deleteRecordButton').addClass('btn btn-danger');
                            $('#deleteRecordButton').attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\")");
                        } else {
                            $('#deleteRecordButton').removeClass();
                            $('#deleteRecordButton').addClass('btn btn-danger');
                            $('#deleteRecordButton').attr('onClick', '');
                        }
                        $("#countryId9999").prop("disabled", "false");
                        $("#countryId9999").removeAttr("disabled", "");
                        $("#countryId9999").val('');
                        $("#countryId9999").trigger("chosen:updated");
                        $("#purchaseOrderId9999").prop("disabled", "false");
                        $("#purchaseOrderId9999").removeAttr("disabled", "");
                        $("#purchaseOrderId9999").val('');
                        $("#purchaseOrderId9999").trigger("chosen:updated");
                        $("#paymentVoucherAllocationAmount9999").prop("disabled", "false");
                        $("#paymentVoucherAllocationAmount9999").removeAttr("disabled", "");
                        $("#paymentVoucherAllocationAmount9999").val('');

                    }
                },
                error: function(xhr) {
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                }
            });
        } else if (type == 5) {
            //New Record and listing
            if ($('#bankId').val().length == 0) {
                $('#bankIdHelpMe').empty();
                $('#bankIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['bankIdLabel'] + " </span>");
                $('#bankId').data('chosen').activate_action();
                return false;
            }
            if ($('#businessPartnerId').val().length == 0) {
                $('#businessPartnerIdHelpMe').empty();
                $('#businessPartnerIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $('#businessPartnerId').data('chosen').activate_action();
                return false;
            }
            if ($('#paymentVoucherDescription').val().length == 0) {
                $('#paymentVoucherDescriptionHelpMe').empty();
                $('#paymentVoucherDescriptionHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDescriptionLabel'] + " </span>");
                $('#paymentVoucherDescriptionForm').addClass('form-group has-error');
                $('#paymentVoucherDescription').focus();
                return false;
            }
            if ($('#paymentVoucherDate').val().length == 0) {
                $('#paymentVoucherDateHelpMe').empty();
                $('#paymentVoucherDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDateLabel'] + " </span>");
                $('#paymentVoucherDateForm').addClass('form-group has-error');
                $('#paymentVoucherDate').focus();
                return false;
            }
            if ($('#paymentVoucherAmount').val().length == 0) {
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
                    from: 'paymentVoucher.php',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    // this is where we append a loading image
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                },
                success: function(data) {
                    // successful request; do something with the data
                    if (data.success == true) {
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
        if (type == 1) {
            // update record and continue
            if ($('#bankId').val().length == 0) {
                $('#bankIdHelpMe').empty();
                $('#bankIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['bankIdLabel'] + " </span>");
                $('#bankId').data('chosen').activate_action();
                return false;
            }
            if ($('#businessPartnerId').val().length == 0) {
                $('#businessPartnerIdHelpMe').empty();
                $('#businessPartnerIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $('#businessPartnerId').data('chosen').activate_action();
                return false;
            }
            if ($('#paymentVoucherDescription').val().length == 0) {
                $('#paymentVoucherDescriptionHelpMe').empty();
                $('#paymentVoucherDescriptionHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDescriptionLabel'] + " </span>");
                $('#paymentVoucherDescriptionForm').addClass('form-group has-error');
                $('#paymentVoucherDescription').focus();
                return false;
            }
            if ($('#paymentVoucherDate').val().length == 0) {
                $('#paymentVoucherDateHelpMe').empty();
                $('#paymentVoucherDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDateLabel'] + " </span>");
                $('#paymentVoucherDateForm').addClass('form-group has-error');
                $('#paymentVoucherDate').focus();
                return false;
            }
            if ($('#paymentVoucherAmount').val().length == 0) {
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
                    isPrinted: $('#isPrinted').val(),
                    isConform: $('#isConform').val(),
                    isChequePrinted: $('#isChequePrinted').val(),
                    from: 'paymentVoucher.php',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    // this is where we append a loading image
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                },
                success: function(data) {
                    // successful request; do something with the data
                    if (data.success == true) {
                        $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['updateRecordTextLabel']) + "</span>");
                        if (deleteAccess == 1) {
                            $('#deleteRecordButton').removeClass();
                            $('#deleteRecordButton').addClass('btn btn-danger');
                            $('#deleteRecordButton').attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + deleteAccess + "\")");
                        } else {
                            $('#deleteRecordButton').removeClass();
                            $('#deleteRecordButton').addClass('btn btn-danger');
                            $('#deleteRecordButton').attr('onClick', '');
                        }
                    } else if (data.success == false) {
                        $('#infoPanel').empty()
                        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                        if ($('#infoPanel').is(':hidden')) {
                            $('#infoPanel').show();
                        }
                    }
                },
                error: function(xhr) {
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                }
            });
        } else if (type == 3) {
            // update record and listing
            if ($('#bankId').val().length == 0) {
                $('#bankIdHelpMe').empty();
                $('#bankIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['bankIdLabel'] + " </span>");
                $('#bankId').data('chosen').activate_action();
                return false;
            }
            if ($('#businessPartnerId').val().length == 0) {
                $('#businessPartnerIdHelpMe').empty();
                $('#businessPartnerIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
                $('#businessPartnerId').data('chosen').activate_action();
                return false;
            }
            if ($('#paymentVoucherDescription').val().length == 0) {
                $('#paymentVoucherDescriptionHelpMe').empty();
                $('#paymentVoucherDescriptionHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDescriptionLabel'] + " </span>");
                $('#paymentVoucherDescriptionForm').addClass('form-group has-error');
                $('#paymentVoucherDescription').focus();
                return false;
            }
            if ($('#paymentVoucherDate').val().length == 0) {
                $('#paymentVoucherDateHelpMe').empty();
                $('#paymentVoucherDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDateLabel'] + " </span>");
                $('#paymentVoucherDateForm').addClass('form-group has-error');
                $('#paymentVoucherDate').focus();
                return false;
            }
            if ($('#paymentVoucherAmount').val().length == 0) {
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
                    paymentVoucherChequeDate: $('#paymentVoucherChequeDate').val(),
                    paymentVoucherDate: $('#paymentVoucherDate').val(),
                    paymentVoucherAmount: $('#paymentVoucherAmount').val(),
                    paymentVoucherChequeNumber: $('#paymentVoucherChequeNumber').val(),
                    paymentVoucherPayee: $('#paymentVoucherPayee').val(),
                    isPrinted: $('#isPrinted').val(),
                    isConform: $('#isConform').val(),
                    isChequePrinted: $('#isChequePrinted').val(),
                    from: 'paymentVoucher.php',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    // this is where we append a loading image
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                },
                success: function(data) {
                    // successful request; do something with the data
                    if (data.success == true) {
                        $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                        if ($('#infoPanel').is(':hidden')) {
                            $('#infoPanel').show();
                        }
                        showGrid(leafId, urlList, securityToken, 0, 10, 1);
                    } else if (data.success == false) {
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
        if (deleteAccess == 1) {
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
                            from: 'paymentVoucher.php',
                            securityToken: securityToken,
                            leafId: leafId
                        },
                        beforeSend: function() {
                            // this is where we append a loading image
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($('#infoPanel').is(':hidden')) {
                                $('#infoPanel').show();
                            }
                        },
                        success: function(data) {
                            // successful request; do something with the data
                            if (data.success == true) {
                                showGrid(leafId, urlList, securityToken, 0, 10, 2);
                            } else if (data.success == false) {
                                $('#infoPanel').empty();
                                $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                                if ($('#infoPanel').is(':hidden')) {
                                    $('#infoPanel').show();
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
                }
            } else {
                return false;
            }
        }
    }
}
function resetRecord(leafId, url, urlList, urlPaymentVoucher, securityToken, createAccess, updateAccess, deleteAccess) {
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
    if (createAccess == 1) {
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
    $('#firstRecordButton').attr('onClick', "firstRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlPaymentVoucher + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
    $('#previousRecordButton').removeClass();
    $('#previousRecordButton').addClass('btn btn-default disabled');
    $('#previousRecordButton').attr('onClick', '');
    $('#nextRecordButton').removeClass();
    $('#nextRecordButton').addClass('btn btn-default disabled');
    $('#nextRecordButton').attr('onClick', '');
    $('#endRecordButton').removeClass();
    $('#endRecordButton').addClass('btn btn-default');
    $('#endRecordButton').attr('onClick', "endRecord\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlPaymentVoucher + "\",,\"" + securityToken + "\",\"" + updateAccess + "\")");
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
    $("#paymentVoucherChequeDate").val('');
    $("#paymentVoucherChequeDateHelpMe").empty();
    $("#paymentVoucherChequeDateHelpMe").html('');
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
    $("#isPrinted").val('');
    $("#isPrintedHelpMe").empty();
    $("#isPrintedHelpMe").html('');
    $("#isConform").val('');
    $("#isConformHelpMe").empty();
    $("#isConformHelpMe").html('');
    $("#isChequePrinted").val('');
    $("#isChequePrintedHelpMe").empty();
    $("#isChequePrintedHelpMe").html('');
    $("#paymentVoucherId9999").prop("disabled", "true");
    $("#paymentVoucherId9999").attr("disabled", "disabled");
    $("#paymentVoucherId9999").val('');

    $("#countryId9999").prop("disabled", "true");
    $("#countryId9999").attr("disabled", "disabled");
    $("#countryId9999").val('');
    $("#countryId9999").trigger("chosen:updated");
    $("#purchaseOrderId9999").prop("disabled", "true");
    $("#purchaseOrderId9999").attr("disabled", "disabled");
    $("#purchaseOrderId9999").val('');
    $("#purchaseOrderId9999").trigger("chosen:updated");
    $("#paymentVoucherAllocationAmount9999").prop("disabled", "true");
    $("#paymentVoucherAllocationAmount9999").attr("disabled", "disabled");
    $("#paymentVoucherAllocationAmount9999").val('');

    $("#tableBody").empty();
    $("#tableBody").html('');
}
function postRecord(leafId, url, urlList, urlPaymentVoucher, SecurityToken) {
    var css = $('#postRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        return false;
    }
}
function firstRecord(leafId, url, urlList, urlPaymentVoucher, securityToken, updateAccess, deleteAccess) {
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
                from: 'paymentVoucher.php',
                securityToken: securityToken,
                leafId: leafId
            },
            beforeSend: function() {
                // this is where we append a loading image
                $('#infoPanel').empty();
                $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            },
            success: function(data) {
                // successful request; do something with the data
                var smileyRoll = './images/icons/smiley-roll.png';
                if (firstRecord === 0) {
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['recordNotFoundLabel']) + "</span>");
                    return false;
                }
                if (data.success == true) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            method: 'read',
                            paymentVoucherId: data.firstRecord,
                            output: 'json',
                            from: 'paymentVoucher.php',
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
                            if (data.success == true) {
                                // resetting field value
                                $('#paymentVoucherId').val(data.data.paymentVoucherId);
                                $('#bankId').val(data.data.bankId);
                                $('#bankId').trigger("chosen:updated");
                                $('#businessPartnerCategoryId').val(data.data.businessPartnerCategoryId);
                                $('#businessPartnerCategoryId').trigger("chosen:updated");
                                $('#businessPartnerId').val(data.data.businessPartnerId);
                                $('#businessPartnerId').trigger("chosen:updated");
                                $('#paymentTypeId').val(data.data.paymentTypeId);
                                $('#paymentTypeId').trigger("chosen:updated");
                                $('#documentNumber').val(data.data.documentNumber);
                                $('#referenceNumber').val(data.data.referenceNumber);
                                $('#paymentVoucherDescription').data("wysihtml5").val(data.data.paymentVoucherDescription);
                                var x = data.data.paymentVoucherDate;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#paymentVoucherChequeDate').val(output);
                                var x = data.data.paymentVoucherDate;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#paymentVoucherChequeDate').val(output);
                                $('#paymentVoucherAmount').val(data.data.paymentVoucherAmount);
                                $('#paymentVoucherChequeNumber').val(data.data.paymentVoucherChequeNumber);
                                $('#paymentVoucherPayee').val(data.data.paymentVoucherPayee);
                                $('#isPrinted').val(data.data.isPrinted);
                                $('#isConform').val(data.data.isConform);
                                $('#isChequePrinted').val(data.data.isChequePrinted);
                                $("#countryId9999").prop("disabled", "false");
                                $("#countryId9999").removeAttr("disabled", "");
                                $("#countryId9999").val('');
                                $("#countryId9999").trigger("chosen:updated");
                                $("#purchaseOrderId9999").prop("disabled", "false");
                                $("#purchaseOrderId9999").removeAttr("disabled", "");
                                $("#purchaseOrderId9999").val('');
                                $("#purchaseOrderId9999").trigger("chosen:updated");
                                $("#paymentVoucherAllocationAmount9999").prop("disabled", "false");
                                $("#paymentVoucherAllocationAmount9999").removeAttr("disabled", "");
                                $("#paymentVoucherAllocationAmount9999").val('');

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
                                        if (data.success == true) {
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
                                        $('#infoError').empty();
                                        $('#infoError').html('');
                                        $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                        $('#infoErrorRowFluid').removeClass();
                                        $('#infoErrorRowFluid').addClass('row');
                                    }
                                });
                                if (data.nextRecord > 0) {
                                    $('#previousRecordButton').removeClass();
                                    $('#previousRecordButton').addClass('btn btn-default  disabled');
                                    $('#previousRecordButton').attr('onClick', '');
                                    $('#nextRecordButton').removeClass();
                                    $('#nextRecordButton').addClass('btn btn-default');
                                    $('#nextRecordButton').attr('onClick', '');
                                    $('#nextRecordButton').attr('onClick', "nextRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlPaymentVoucher + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
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
                                    if (updateAccess == 1) {
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
                                    if (deleteAccess == 1) {
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
                            $('#infoError').empty();
                            $('#infoError').html('');
                            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass();
                            $('#infoErrorRowFluid').addClass('row');
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
                $('#infoError').empty();
                $('#infoError').html('');
                $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid').removeClass();
                $('#infoErrorRowFluid').addClass('row');
            }
        });
    }
}
function endRecord(leafId, url, urlList, urlPaymentVoucher, securityToken, updateAccess, deleteAccess) {
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
                from: 'paymentVoucher.php',
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
                if (data.success == true) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            method: 'read',
                            paymentVoucherId: data.lastRecord,
                            output: 'json',
                            from: 'paymentVoucher.php',
                            securityToken: securityToken,
                            leafId: leafId
                        },
                        beforeSend: function() {
                            // this is where we append a loading image
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($('#infoPanel').is(':hidden')) {
                                $('#infoPanel').show();
                            }
                        },
                        success: function(data) {
                            // successful request; do something with the data
                            if (data.success == true) {
                                // reset field value
                                $('#paymentVoucherId').val(data.data.paymentVoucherId);
                                $('#bankId').val(data.data.bankId);
                                $('#bankId').trigger("chosen:updated");
                                $('#businessPartnerCategoryId').val(data.data.businessPartnerCategoryId);
                                $('#businessPartnerCategoryId').trigger("chosen:updated");
                                $('#businessPartnerId').val(data.data.businessPartnerId);
                                $('#businessPartnerId').trigger("chosen:updated");
                                $('#paymentTypeId').val(data.data.paymentTypeId);
                                $('#paymentTypeId').trigger("chosen:updated");
                                $('#documentNumber').val(data.data.documentNumber);
                                $('#referenceNumber').val(data.data.referenceNumber);
                                $('#paymentVoucherDescription').val(data.data.paymentVoucherDescription);
                                var x = data.data.paymentVoucherDate;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#paymentVoucherDate').val(output);
                                $('#paymentVoucherChequeDate').val(output);
                                var x = data.data.paymentVoucherDate;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#paymentVoucherChequeDate').val(output);
                                $('#paymentVoucherAmount').val(data.data.paymentVoucherAmount);
                                $('#paymentVoucherChequeNumber').val(data.data.paymentVoucherChequeNumber);
                                $('#paymentVoucherPayee').val(data.data.paymentVoucherPayee);
                                $('#isPrinted').val(data.data.isPrinted);
                                $('#isConform').val(data.data.isConform);
                                $('#isChequePrinted').val(data.data.isChequePrinted);
                                $("#countryId9999").prop("disabled", "false");
                                $("#countryId9999").removeAttr("disabled", "");
                                $("#countryId9999").val('');
                                $("#countryId9999").trigger("chosen:updated");
                                $("#purchaseOrderId9999").prop("disabled", "false");
                                $("#purchaseOrderId9999").removeAttr("disabled", "");
                                $("#purchaseOrderId9999").val('');
                                $("#purchaseOrderId9999").trigger("chosen:updated");
                                $("#paymentVoucherAllocationAmount9999").prop("disabled", "false");
                                $("#paymentVoucherAllocationAmount9999").removeAttr("disabled", "");
                                $("#paymentVoucherAllocationAmount9999").val('');

                                $.ajax({
                                    type: 'POST',
                                    url: urlPaymentVoucher,
                                    data: {
                                        method: 'read',
                                        paymentVoucherId: data.lastRecord,
                                        output: 'table',
                                        from: 'paymentVoucher.php',
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
                                        if (data.success == true) {
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
                                        $('#infoError').empty();
                                        $('#infoError').html('');
                                        $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                        $('#infoErrorRowFluid').removeClass();
                                        $('#infoErrorRowFluid').addClass('row');
                                    }
                                });
                                if (data.lastRecord != 0) {
                                    $('#previousRecordButton').removeClass();
                                    $('#previousRecordButton').addClass('btn btn-default');
                                    $('#previousRecordButton').attr('onClick', "previousRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlPaymentVoucher + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
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
                                    if (updateAccess == 1) {
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
                                    if (deleteAccess == 1) {
                                        $('#deleteRecordButton').removeClass();
                                        $('#deleteRecordButton').addClass('btn btn-danger');
                                        $('#deleteRecordButton').attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + deleteAccess + "\")");
                                    } else {
                                        $('#deleteRecordButton').removeClass();
                                        $('#deleteRecordButton').addClass('btn btn-danger');
                                        $('#deleteRecordButton').attr('onClick', '');
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
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("&nbsp;<img src='./images/icons/control-stop-180.png'> " + decodeURIComponent(t['endButtonLabel']) + " ");
            },
            error: function(xhr) {
                $('#infoError').empty();
                $('#infoError').html('');
                $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid').removeClass();
                $('#infoErrorRowFluid').addClass('row');
            }
        });
    }
}
function previousRecord(leafId, url, urlList, urlPaymentVoucher, securityToken, updateAccess, deleteAccess) {
    var css = $('#previousRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $('#newButton').removeClass();
        if ($('#previousRecordCounter').val() == '' || $('#previousRecordCounter').val() == undefined) {
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
                    from: 'paymentVoucher.php',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    // this is where we append a loading image
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                },
                success: function(data) {
                    // successful request; do something with the data
                    if (data.success == true) {
                        $('#paymentVoucherId').val(data.data.paymentVoucherId);
                        $('#bankId').val(data.data.bankId);
                        $('#bankId').trigger("chosen:updated");
                        $('#businessPartnerCategoryId').val(data.data.businessPartnerCategoryId);
                        $('#businessPartnerCategoryId').trigger("chosen:updated");
                        $('#businessPartnerId').val(data.data.businessPartnerId);
                        $('#businessPartnerId').trigger("chosen:updated");
                        $('#paymentTypeId').val(data.data.paymentTypeId);
                        $('#paymentTypeId').trigger("chosen:updated");
                        $('#documentNumber').val(data.data.documentNumber);
                        $('#referenceNumber').val(data.data.referenceNumber);
                        $('#paymentVoucherDescription').val(data.data.paymentVoucherDescription);
                        var x = data.data.paymentVoucherDate;
                        x = x.split("-");
                        var output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#paymentVoucherDate').val(output);
                        $('#paymentVoucherChequeDate').val(output);
                        var x = data.data.paymentVoucherDate;
                        x = x.split("-");
                        var output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#paymentVoucherChequeDate').val(output);
                        $('#paymentVoucherAmount').val(data.data.paymentVoucherAmount);
                        $('#paymentVoucherChequeNumber').val(data.data.paymentVoucherChequeNumber);
                        $('#paymentVoucherPayee').val(data.data.paymentVoucherPayee);
                        $('#isPrinted').val(data.data.isPrinted);
                        $('#isConform').val(data.data.isConform);
                        $('#isChequePrinted').val(data.data.isChequePrinted);
                        $("#countryId9999").prop("disabled", "false");
                        $("#countryId9999").removeAttr("disabled", "");
                        $("#countryId9999").val('');
                        $("#countryId9999").trigger("chosen:updated");
                        $("#purchaseOrderId9999").prop("disabled", "false");
                        $("#purchaseOrderId9999").removeAttr("disabled", "");
                        $("#purchaseOrderId9999").val('');
                        $("#purchaseOrderId9999").trigger("chosen:updated");
                        $("#paymentVoucherAllocationAmount9999").prop("disabled", "false");
                        $("#paymentVoucherAllocationAmount9999").removeAttr("disabled", "");
                        $("#paymentVoucherAllocationAmount9999").val('');

                        $.ajax({
                            type: 'POST',
                            url: urlPaymentVoucher,
                            data: {
                                method: 'read',
                                paymentVoucherId: $('#previousRecordCounter').val(),
                                output: 'table',
                                from: 'paymentVoucher.php',
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
                                if (data.success == true) {
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
                                $('#infoError').empty();
                                $('#infoError').html('');
                                $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                $('#infoErrorRowFluid').removeClass();
                                $('#infoErrorRowFluid').addClass('row');
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
                        if (updateAccess == 1) {
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
                        if (deleteAccess == 1) {
                            $('#deleteRecordButton').removeClass();
                            $('#deleteRecordButton').addClass('btn btn-danger');
                            $('#deleteRecordButton').attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + deleteAccess + "\")");
                        } else {
                            $('#deleteRecordButton').removeClass();
                            $('#deleteRecordButton').addClass('btn btn-danger');
                            $('#deleteRecordButton').attr('onClick', '');
                        }
                        $('#firstRecordCounter').val(data.firstRecord);
                        $('#previousRecordCounter').val(data.previousRecord);
                        $('#nextRecordCounter').val(data.nextRecord);
                        $('#lastRecordCounter').val(data.lastRecord);
                        if (parseFloat(data.nextRecord) <= parseFloat(data.lastRecord)) {
                            $('#nextRecordButton').removeClass();
                            $('#nextRecordButton').addClass('btn btn-default');
                            $('#nextRecordButton').attr('onClick', '');
                            $('#nextRecordButton').attr('onClick', "nextRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlPaymentVoucher + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
                        } else {
                            $('#nextRecordButton').removeClass();
                            $('#nextRecordButton').addClass('btn btn-default disabled');
                            $('#nextRecordButton').attr('onClick', '');
                        }
                        if (parseFloat(data.previousRecord) == 0) {
                            $('#infoPanel').empty();
                            $('#infoPanel').html("&nbsp;<img src='./images/icons/exclamation.png'> " + decodeURIComponent(t['firstButtonLabel']) + " ");
                            $('#previousRecordButton').removeClass();
                            $('#previousRecordButton').addClass('btn btn-default disabled');
                            $('#previousRecordButton').attr('onClick', '');
                        } else {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html("&nbsp;<img src='./images/icons/control-180.png'> " + decodeURIComponent(t['previousButtonLabel']) + " ");
                            if ($('#infoPanel').is(':hidden')) {
                                $('#infoPanel').show();
                            }
                        }
                        $(document).scrollTop();
                    }
                },
                error: function(xhr) {
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                }
            });
        } else {
            // debugging purpose only
        }
    }
}
function nextRecord(leafId, url, urlList, urlPaymentVoucher, securityToken, updateAccess, deleteAccess) {
    var css = $('#nextRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $('#newButton').removeClass();
        if ($('#nextRecordCounter').val() == '' || $('#nextRecordCounter').val() == undefined) {
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
                    from: 'paymentVoucher.php',
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                },
                success: function(data) {
                    // successful request; do something with the data
                    if (data.success == true) {
                        $('#paymentVoucherId').val(data.data.paymentVoucherId);
                        $('#bankId').val(data.data.bankId);
                        $('#bankId').trigger("chosen:updated");
                        $('#businessPartnerCategoryId').val(data.data.businessPartnerCategoryId);
                        $('#businessPartnerCategoryId').trigger("chosen:updated");
                        $('#businessPartnerId').val(data.data.businessPartnerId);
                        $('#businessPartnerId').trigger("chosen:updated");
                        $('#paymentTypeId').val(data.data.paymentTypeId);
                        $('#paymentTypeId').trigger("chosen:updated");
                        $('#documentNumber').val(data.data.documentNumber);
                        $('#referenceNumber').val(data.data.referenceNumber);
                        $('#paymentVoucherDescription').val(data.data.paymentVoucherDescription);
                        var x = data.data.paymentVoucherDate;
                        x = x.split("-");
                        var output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#paymentVoucherDate').val(output);
                        $('#paymentVoucherChequeDate').val(output);
                        var x = data.data.paymentVoucherDate;
                        x = x.split("-");
                        var output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#paymentVoucherChequeDate').val(output);
                        $('#paymentVoucherAmount').val(data.data.paymentVoucherAmount);
                        $('#paymentVoucherChequeNumber').val(data.data.paymentVoucherChequeNumber);
                        $('#paymentVoucherPayee').val(data.data.paymentVoucherPayee);
                        $('#isPrinted').val(data.data.isPrinted);
                        $('#isConform').val(data.data.isConform);
                        $('#isChequePrinted').val(data.data.isChequePrinted);
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
                        if (updateAccess == 1) {
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
                        if (deleteAccess == 1) {
                            $('#deleteRecordButton').removeClass();
                            $('#deleteRecordButton').addClass('btn btn-danger');
                            $('#deleteRecordButton').attr('onClick', "deleteRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + deleteAccess + "\")");
                        } else {
                            $('#deleteRecordButton').removeClass();
                            $('#deleteRecordButton').addClass('btn btn-danger');
                            $('#deleteRecordButton').attr('onClick', '');
                        }
                        $("#countryId9999").prop("disabled", "false");
                        $("#countryId9999").removeAttr("disabled", "");
                        $("#countryId9999").val('');
                        $("#countryId9999").trigger("chosen:updated");
                        $("#purchaseOrderId9999").prop("disabled", "false");
                        $("#purchaseOrderId9999").removeAttr("disabled", "");
                        $("#purchaseOrderId9999").val('');
                        $("#purchaseOrderId9999").trigger("chosen:updated");
                        $("#paymentVoucherAllocationAmount9999").prop("disabled", "false");
                        $("#paymentVoucherAllocationAmount9999").removeAttr("disabled", "");
                        $("#paymentVoucherAllocationAmount9999").val('');

                        $.ajax({
                            type: 'POST',
                            url: urlPaymentVoucher,
                            data: {
                                method: 'read',
                                paymentVoucherId: $('#nextRecordCounter').val(),
                                output: 'table',
                                from: 'paymentVoucher.php',
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
                                if (data.success == true) {
                                    // make sure empty
                                    $('#tableBody').empty();
                                    $('#tableBody').html('');
                                    $('#tableBody').html(data.tableData);
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
                                $('#infoError').empty();
                                $('#infoError').html('');
                                $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                $('#infoErrorRowFluid').removeClass();
                                $('#infoErrorRowFluid').addClass('row');
                            }
                        });
                        $('#firstRecordCounter').val(data.firstRecord);
                        $('#previousRecordCounter').val(data.previousRecord);
                        $('#nextRecordCounter').val(data.nextRecord);
                        $('#lastRecordCounter').val(data.lastRecord);
                        if (parseFloat(data.previousRecord) > 0) {
                            $('#previousRecordButton').removeClass();
                            $('#previousRecordButton').addClass('btn btn-default');
                            $('#previousRecordButton').attr('onClick', "previousRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlPaymentVoucher + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
                        } else {
                            $('#previousRecordButton').removeClass();
                            $('#previousRecordButton').addClass('btn btn-default disabled');
                            $('#previousRecordButton').attr('onClick', '');
                        }
                        if (parseFloat(data.nextRecord) == 0) {
                            $('#nextRecordButton').removeClass();
                            $('#nextRecordButton').addClass('btn btn-default disabled');
                            $('#nextRecordButton').attr('onClick', '');
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
                }
            });
        } else {
        }
    }
}
