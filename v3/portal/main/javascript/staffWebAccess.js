function getStaff(leafId, url, securityToken) {
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
            leafId: leafId,
            filter: 'staff'
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
                $('#infoPanel').html("<span class='label label-important'>&nbsp;<img src='./images/icons/smiley-roll.png'>" + data.message + "</span>");
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $("#staffId").empty();
                $("#staffId").html('');
                $("#staffId").html(data.data);
                $("#staffId").trigger("chosen:updated");
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
    if ($("#staffWebAccessCode").val().length == 0) {
        alert(t['oddTextLabel']);
        return false;
    }
    $.ajax({
        type: 'GET',
        url: page,
        data: {
            staffWebAccessCode: $("#staffWebAccessCode").val(),
            method: 'duplicate',
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
                    $("#staffWebAccessCode").empty();
                    $("#staffWebAccessCode").val('');
                    $("#staffWebAccessCode").focus();
                    $("#staffWebAccessCodeForm").removeClass();
                    $("#staffWebAccessCodeForm").addClass("col-md-12 form-group has-error");
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
                $('#infoPanel').html("<span class='label label-important'>&nbsp;" + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
                $("#staffWebAccessForm").removeClass();
                $("#staffWebAccessForm").addClass("col-md-12 form-group has-error");
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

                $('#centerViewport').html("<span class='label label-important'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
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

                $('#centerViewport').html("<span class='label label-important'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
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

                $('#centerViewport').html("<span class='label label-important'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
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

                $('#centerViewport').html("<span class='label label-important'>&nbsp;<img src='./images/icons/smiley-roll.png'>" + data.message + "</span>");
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

                $('#centerViewport').html("<span class='label label-important'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
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
function showFormUpdate(leafId, url, urlList, securityToken, staffWebAccessId, updateAccess, deleteAccess) {
    sleep(500);
    // unlimited for searching because  lazy paging.
    $.ajax({
        type: 'POST',
        url: urlList,
        data: {
            method: 'read',
            type: 'form',
            staffWebAccessId: staffWebAccessId,
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

                $('#centerViewport').html("<span class='label label-important'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
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
function showModalDelete(staffWebAccessId, staffId, staffName, staffWebAccessLogIn, staffWebAccessLogOut, phpSession, ua_type, ua_family, ua_name, ua_version, ua_url, ua_company, ua_company_url, ua_icon, ua_info_url, os_family, os_name, os_url, os_company, os_company_url, os_icon, ip_v4, ip_v6, ip_country_code, ip_country_name, ip_region_name, ip_latitude, ip_longtitude) {
    // clear first old record if exist
    $('#staffWebAccessIdPreview').val('');
    $('#staffWebAccessIdPreview').val(decodeURIComponent(staffWebAccessId));

    $('#staffIdPreview').val('');
    $('#staffIdPreview').val(decodeURIComponent(staffId));

    $('#staffNamePreview').val('');
    $('#staffNamePreview').val(decodeURIComponent(staffName));

    $('#staffWebAccessLogInPreview').val('');
    $('#staffWebAccessLogInPreview').val(decodeURIComponent(staffWebAccessLogIn));

    $('#staffWebAccessLogOutPreview').val('');
    $('#staffWebAccessLogOutPreview').val(decodeURIComponent(staffWebAccessLogOut));

    $('#phpSessionPreview').val('');
    $('#phpSessionPreview').val(decodeURIComponent(phpSession));

    $('#ua_typePreview').val('');
    $('#ua_typePreview').val(decodeURIComponent(ua_type));

    $('#ua_familyPreview').val('');
    $('#ua_familyPreview').val(decodeURIComponent(ua_family));

    $('#ua_namePreview').val('');
    $('#ua_namePreview').val(decodeURIComponent(ua_name));

    $('#ua_versionPreview').val('');
    $('#ua_versionPreview').val(decodeURIComponent(ua_version));

    $('#ua_urlPreview').val('');
    $('#ua_urlPreview').val(decodeURIComponent(ua_url));

    $('#ua_companyPreview').val('');
    $('#ua_companyPreview').val(decodeURIComponent(ua_company));

    $('#ua_company_urlPreview').val('');
    $('#ua_company_urlPreview').val(decodeURIComponent(ua_company_url));

    $('#ua_iconPreview').val('');
    $('#ua_iconPreview').val(decodeURIComponent(ua_icon));

    $('#ua_info_urlPreview').val('');
    $('#ua_info_urlPreview').val(decodeURIComponent(ua_info_url));

    $('#os_familyPreview').val('');
    $('#os_familyPreview').val(decodeURIComponent(os_family));

    $('#os_namePreview').val('');
    $('#os_namePreview').val(decodeURIComponent(os_name));

    $('#os_urlPreview').val('');
    $('#os_urlPreview').val(decodeURIComponent(os_url));

    $('#os_companyPreview').val('');
    $('#os_companyPreview').val(decodeURIComponent(os_company));

    $('#os_company_urlPreview').val('');
    $('#os_company_urlPreview').val(decodeURIComponent(os_company_url));

    $('#os_iconPreview').val('');
    $('#os_iconPreview').val(decodeURIComponent(os_icon));

    $('#ip_v4Preview').val('');
    $('#ip_v4Preview').val(decodeURIComponent(ip_v4));

    $('#ip_v6Preview').val('');
    $('#ip_v6Preview').val(decodeURIComponent(ip_v6));

    $('#ip_country_codePreview').val('');
    $('#ip_country_codePreview').val(decodeURIComponent(ip_country_code));

    $('#ip_country_namePreview').val('');
    $('#ip_country_namePreview').val(decodeURIComponent(ip_country_name));

    $('#ip_region_namePreview').val('');
    $('#ip_region_namePreview').val(decodeURIComponent(ip_region_name));

    $('#ip_latitudePreview').val('');
    $('#ip_latitudePreview').val(decodeURIComponent(ip_latitude));

    $('#ip_longtitudePreview').val('');
    $('#ip_longtitudePreview').val(decodeURIComponent(ip_longtitude));

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
            staffWebAccessId: $('#staffWebAccessIdPreview').val(),
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
                $('#infoPanel').html("<span class='label label-important'>&nbsp;" + data.message + "</span>");
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
    $('input:checkbox[name="staffWebAccessId[]"]').each(function() {
        stringText = stringText + "&staffWebAccessId[]=" + $(this).val();
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
                $('#infoPanel').html("<span class='label label-important'>&nbsp;" + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            } else {
                $('#infoPanel').empty()
                $('#infoPanel').html("<span class='label label-important'>&nbsp;" + data.message + "</span>");
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
                var path="./v3/system/security/document/" + data.folder + "/" + data.filename;
                $('#infoPanel').html("<span class='label label-success'>" + decodeURIComponent(t['requestFileTextLabel']) + "</span>");
                window.open(path);
                // a hyper link will be given to click download..
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html("<span class='label label-important'>&nbsp;" + data.message + "</span>");
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
            if ($('#staffId').val().length == 0) {
                $('#staffIdHelpMe').empty();
                $('#staffIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffIdLabel'] + " </span>");
                $('#staffId').data('chosen').activate_action();
                return false;
            }
            if ($('#staffName').val().length == 0) {
                $('#staffNameHelpMe').empty();
                $('#staffNameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffNameLabel'] + " </span>");
                $('#staffNameForm').addClass('form-group has-error');
                $('#staffName').focus();
                return false;
            }
            if ($('#staffWebAccessLogIn').val().length == 0) {
                $('#staffWebAccessLogInHelpMe').empty();
                $('#staffWebAccessLogInHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffWebAccessLogInLabel'] + " </span>");
                $('#staffWebAccessLogInForm').addClass('form-group has-error');
                $('#staffWebAccessLogIn').focus();
                return false;
            }
            if ($('#staffWebAccessLogOut').val().length == 0) {
                $('#staffWebAccessLogOutHelpMe').empty();
                $('#staffWebAccessLogOutHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffWebAccessLogOutLabel'] + " </span>");
                $('#staffWebAccessLogOutForm').addClass('form-group has-error');
                $('#staffWebAccessLogOut').focus();
                return false;
            }
            if ($('#phpSession').val().length == 0) {
                $('#phpSessionHelpMe').empty();
                $('#phpSessionHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['phpSessionLabel'] + " </span>");
                $('#phpSessionForm').addClass('form-group has-error');
                $('#phpSession').focus();
                return false;
            }
            if ($('#ua_type').val().length == 0) {
                $('#ua_typeHelpMe').empty();
                $('#ua_typeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_typeLabel'] + " </span>");
                $('#ua_typeForm').addClass('form-group has-error');
                $('#ua_type').focus();
                return false;
            }
            if ($('#ua_family').val().length == 0) {
                $('#ua_familyHelpMe').empty();
                $('#ua_familyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_familyLabel'] + " </span>");
                $('#ua_familyForm').addClass('form-group has-error');
                $('#ua_family').focus();
                return false;
            }
            if ($('#ua_name').val().length == 0) {
                $('#ua_nameHelpMe').empty();
                $('#ua_nameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_nameLabel'] + " </span>");
                $('#ua_nameForm').addClass('form-group has-error');
                $('#ua_name').focus();
                return false;
            }
            if ($('#ua_version').val().length == 0) {
                $('#ua_versionHelpMe').empty();
                $('#ua_versionHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_versionLabel'] + " </span>");
                $('#ua_versionForm').addClass('form-group has-error');
                $('#ua_version').focus();
                return false;
            }
            if ($('#ua_url').val().length == 0) {
                $('#ua_urlHelpMe').empty();
                $('#ua_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_urlLabel'] + " </span>");
                $('#ua_urlForm').addClass('form-group has-error');
                $('#ua_url').focus();
                return false;
            }
            if ($('#ua_company').val().length == 0) {
                $('#ua_companyHelpMe').empty();
                $('#ua_companyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_companyLabel'] + " </span>");
                $('#ua_companyForm').addClass('form-group has-error');
                $('#ua_company').focus();
                return false;
            }
            if ($('#ua_company_url').val().length == 0) {
                $('#ua_company_urlHelpMe').empty();
                $('#ua_company_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_company_urlLabel'] + " </span>");
                $('#ua_company_urlForm').addClass('form-group has-error');
                $('#ua_company_url').focus();
                return false;
            }
            if ($('#ua_icon').val().length == 0) {
                $('#ua_iconHelpMe').empty();
                $('#ua_iconHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_iconLabel'] + " </span>");
                $('#ua_iconForm').addClass('form-group has-error');
                $('#ua_icon').focus();
                return false;
            }
            if ($('#ua_info_url').val().length == 0) {
                $('#ua_info_urlHelpMe').empty();
                $('#ua_info_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_info_urlLabel'] + " </span>");
                $('#ua_info_urlForm').addClass('form-group has-error');
                $('#ua_info_url').focus();
                return false;
            }
            if ($('#os_family').val().length == 0) {
                $('#os_familyHelpMe').empty();
                $('#os_familyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_familyLabel'] + " </span>");
                $('#os_familyForm').addClass('form-group has-error');
                $('#os_family').focus();
                return false;
            }
            if ($('#os_name').val().length == 0) {
                $('#os_nameHelpMe').empty();
                $('#os_nameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_nameLabel'] + " </span>");
                $('#os_nameForm').addClass('form-group has-error');
                $('#os_name').focus();
                return false;
            }
            if ($('#os_url').val().length == 0) {
                $('#os_urlHelpMe').empty();
                $('#os_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_urlLabel'] + " </span>");
                $('#os_urlForm').addClass('form-group has-error');
                $('#os_url').focus();
                return false;
            }
            if ($('#os_company').val().length == 0) {
                $('#os_companyHelpMe').empty();
                $('#os_companyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_companyLabel'] + " </span>");
                $('#os_companyForm').addClass('form-group has-error');
                $('#os_company').focus();
                return false;
            }
            if ($('#os_company_url').val().length == 0) {
                $('#os_company_urlHelpMe').empty();
                $('#os_company_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_company_urlLabel'] + " </span>");
                $('#os_company_urlForm').addClass('form-group has-error');
                $('#os_company_url').focus();
                return false;
            }
            if ($('#os_icon').val().length == 0) {
                $('#os_iconHelpMe').empty();
                $('#os_iconHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_iconLabel'] + " </span>");
                $('#os_iconForm').addClass('form-group has-error');
                $('#os_icon').focus();
                return false;
            }
            if ($('#ip_v4').val().length == 0) {
                $('#ip_v4HelpMe').empty();
                $('#ip_v4HelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_v4Label'] + " </span>");
                $('#ip_v4Form').addClass('form-group has-error');
                $('#ip_v4').focus();
                return false;
            }
            if ($('#ip_v6').val().length == 0) {
                $('#ip_v6HelpMe').empty();
                $('#ip_v6HelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_v6Label'] + " </span>");
                $('#ip_v6Form').addClass('form-group has-error');
                $('#ip_v6').focus();
                return false;
            }
            if ($('#ip_country_code').val().length == 0) {
                $('#ip_country_codeHelpMe').empty();
                $('#ip_country_codeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_country_codeLabel'] + " </span>");
                $('#ip_country_codeForm').addClass('form-group has-error');
                $('#ip_country_code').focus();
                return false;
            }
            if ($('#ip_country_name').val().length == 0) {
                $('#ip_country_nameHelpMe').empty();
                $('#ip_country_nameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_country_nameLabel'] + " </span>");
                $('#ip_country_nameForm').addClass('form-group has-error');
                $('#ip_country_name').focus();
                return false;
            }
            if ($('#ip_region_name').val().length == 0) {
                $('#ip_region_nameHelpMe').empty();
                $('#ip_region_nameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_region_nameLabel'] + " </span>");
                $('#ip_region_nameForm').addClass('form-group has-error');
                $('#ip_region_name').focus();
                return false;
            }
            if ($('#ip_latitude').val().length == 0) {
                $('#ip_latitudeHelpMe').empty();
                $('#ip_latitudeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_latitudeLabel'] + " </span>");
                $('#ip_latitudeForm').addClass('form-group has-error');
                $('#ip_latitude').focus();
                return false;
            }
            if ($('#ip_longtitude').val().length == 0) {
                $('#ip_longtitudeHelpMe').empty();
                $('#ip_longtitudeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_longtitudeLabel'] + " </span>");
                $('#ip_longtitudeForm').addClass('form-group has-error');
                $('#ip_longtitude').focus();
                return false;
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'create',
                    output: 'json',
                    staffId: $('#staffId').val(),
                    staffName: $('#staffName').val(),
                    staffWebAccessLogIn: $('#staffWebAccessLogIn').val(),
                    staffWebAccessLogOut: $('#staffWebAccessLogOut').val(),
                    phpSession: $('#phpSession').val(),
                    ua_type: $('#ua_type').val(),
                    ua_family: $('#ua_family').val(),
                    ua_name: $('#ua_name').val(),
                    ua_version: $('#ua_version').val(),
                    ua_url: $('#ua_url').val(),
                    ua_company: $('#ua_company').val(),
                    ua_company_url: $('#ua_company_url').val(),
                    ua_icon: $('#ua_icon').val(),
                    ua_info_url: $('#ua_info_url').val(),
                    os_family: $('#os_family').val(),
                    os_name: $('#os_name').val(),
                    os_url: $('#os_url').val(),
                    os_company: $('#os_company').val(),
                    os_company_url: $('#os_company_url').val(),
                    os_icon: $('#os_icon').val(),
                    ip_v4: $('#ip_v4').val(),
                    ip_v6: $('#ip_v6').val(),
                    ip_country_code: $('#ip_country_code').val(),
                    ip_country_name: $('#ip_country_name').val(),
                    ip_region_name: $('#ip_region_name').val(),
                    ip_latitude: $('#ip_latitude').val(),
                    ip_longtitude: $('#ip_longtitude').val(),
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
                        $('#staffId').val('');
                        $('#staffId').trigger("chosen:updated");
                        $('#staffIdHelpMe').empty();
                        $('#staffIdHelpMe').html('');
                        $('#staffName').val('');
                        $('#staffName').val('');
                        $('#staffNameHelpMe').empty();
                        $('#staffNameHelpMe').html('');
                        $('#staffWebAccessLogIn').val('');
                        $('#staffWebAccessLogInHelpMe').empty();
                        $('#staffWebAccessLogInHelpMe').html('');
                        $('#staffWebAccessLogOut').val('');
                        $('#staffWebAccessLogOutHelpMe').empty();
                        $('#staffWebAccessLogOutHelpMe').html('');
                        $('#phpSession').val('');
                        $('#phpSession').val('');
                        $('#phpSessionHelpMe').empty();
                        $('#phpSessionHelpMe').html('');
                        $('#ua_type').val('');
                        $('#ua_type').val('');
                        $('#ua_typeHelpMe').empty();
                        $('#ua_typeHelpMe').html('');
                        $('#ua_family').val('');
                        $('#ua_familyForm').removeClass().addClass('col-md-12 form-group');
                        $('#ua_family').val('');
                        $('#ua_familyHelpMe').empty();
                        $('#ua_familyHelpMe').html('');
                        $('#ua_name').val('');
                        $('#ua_nameForm').removeClass().addClass('col-md-12 form-group');
                        $('#ua_name').val('');
                        $('#ua_nameHelpMe').empty();
                        $('#ua_nameHelpMe').html('');
                        $('#ua_version').val('');
                        $('#ua_versionForm').removeClass().addClass('col-md-12 form-group');
                        $('#ua_version').val('');
                        $('#ua_versionHelpMe').empty();
                        $('#ua_versionHelpMe').html('');
                        $('#ua_url').val('');
                        $('#ua_urlForm').removeClass().addClass('col-md-12 form-group');
                        $('#ua_url').val('');
                        $('#ua_urlHelpMe').empty();
                        $('#ua_urlHelpMe').html('');
                        $('#ua_company').val('');
                        $('#ua_companyForm').removeClass().addClass('col-md-12 form-group');
                        $('#ua_company').val('');
                        $('#ua_companyHelpMe').empty();
                        $('#ua_companyHelpMe').html('');
                        $('#ua_company_url').val('');
                        $('#ua_company_urlForm').removeClass().addClass('col-md-12 form-group');
                        $('#ua_company_url').val('');
                        $('#ua_company_urlHelpMe').empty();
                        $('#ua_company_urlHelpMe').html('');
                        $('#ua_icon').val('');
                        $('#ua_iconForm').removeClass().addClass('col-md-12 form-group');
                        $('#ua_icon').val('');
                        $('#ua_iconHelpMe').empty();
                        $('#ua_iconHelpMe').html('');
                        $('#ua_info_url').val('');
                        $('#ua_info_urlForm').removeClass().addClass('col-md-12 form-group');
                        $('#ua_info_url').val('');
                        $('#ua_info_urlHelpMe').empty();
                        $('#ua_info_urlHelpMe').html('');
                        $('#os_family').val('');
                        $('#os_familyForm').removeClass().addClass('col-md-12 form-group');
                        $('#os_family').val('');
                        $('#os_familyHelpMe').empty();
                        $('#os_familyHelpMe').html('');
                        $('#os_name').val('');
                        $('#os_nameForm').removeClass().addClass('col-md-12 form-group');
                        $('#os_name').val('');
                        $('#os_nameHelpMe').empty();
                        $('#os_nameHelpMe').html('');
                        $('#os_url').val('');
                        $('#os_urlForm').removeClass().addClass('col-md-12 form-group');
                        $('#os_url').val('');
                        $('#os_urlHelpMe').empty();
                        $('#os_urlHelpMe').html('');
                        $('#os_company').val('');
                        $('#os_companyForm').removeClass().addClass('col-md-12 form-group');
                        $('#os_company').val('');
                        $('#os_companyHelpMe').empty();
                        $('#os_companyHelpMe').html('');
                        $('#os_company_url').val('');
                        $('#os_company_urlForm').removeClass().addClass('col-md-12 form-group');
                        $('#os_company_url').val('');
                        $('#os_company_urlHelpMe').empty();
                        $('#os_company_urlHelpMe').html('');
                        $('#os_icon').val('');
                        $('#os_iconForm').removeClass().addClass('col-md-12 form-group');
                        $('#os_icon').val('');
                        $('#os_iconHelpMe').empty();
                        $('#os_iconHelpMe').html('');
                        $('#ip_v4').val('');
                        $('#ip_v4Form').removeClass().addClass('col-md-12 form-group');
                        $('#ip_v4').val('');
                        $('#ip_v4HelpMe').empty();
                        $('#ip_v4HelpMe').html('');
                        $('#ip_v6').val('');
                        $('#ip_v6Form').removeClass().addClass('col-md-12 form-group');
                        $('#ip_v6').val('');
                        $('#ip_v6HelpMe').empty();
                        $('#ip_v6HelpMe').html('');
                        $('#ip_country_code').val('');
                        $('#ip_country_codeForm').removeClass().addClass('col-md-12 form-group');
                        $('#ip_country_code').val('');
                        $('#ip_country_codeHelpMe').empty();
                        $('#ip_country_codeHelpMe').html('');
                        $('#ip_country_name').val('');
                        $('#ip_country_nameForm').removeClass().addClass('col-md-12 form-group');
                        $('#ip_country_name').val('');
                        $('#ip_country_nameHelpMe').empty();
                        $('#ip_country_nameHelpMe').html('');
                        $('#ip_region_name').val('');
                        $('#ip_region_nameForm').removeClass().addClass('col-md-12 form-group');
                        $('#ip_region_name').val('');
                        $('#ip_region_nameHelpMe').empty();
                        $('#ip_region_nameHelpMe').html('');
                        $('#ip_latitude').val('');
                        $('#ip_latitudeForm').removeClass().addClass('col-md-12 form-group');
                        $('#ip_latitude').val('');
                        $('#ip_latitudeHelpMe').empty();
                        $('#ip_latitudeHelpMe').html('');
                        $('#ip_longtitude').val('');
                        $('#ip_longtitudeForm').removeClass().addClass('col-md-12 form-group');
                        $('#ip_longtitude').val('');
                        $('#ip_longtitudeHelpMe').empty();
                        $('#ip_longtitudeHelpMe').html('');
                    } else if (data.success == false) {
                        $('#infoPanel').empty();
                        $('#infoPanel').html("<span class='label label-important'>&nbsp;" + data.message + "</span>");
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
            if ($('#staffId').val().length == 0) {
                $('#staffIdHelpMe').empty();
                $('#staffIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffIdLabel'] + " </span>");
                $('#staffId').data('chosen').activate_action();
                return false;
            }
            if ($('#staffName').val().length == 0) {
                $('#staffNameHelpMe').empty();
                $('#staffNameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffNameLabel'] + " </span>");
                $('#staffNameForm').addClass('form-group has-error');
                $('#staffName').focus();
                return false;
            }
            if ($('#staffWebAccessLogIn').val().length == 0) {
                $('#staffWebAccessLogInHelpMe').empty();
                $('#staffWebAccessLogInHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffWebAccessLogInLabel'] + " </span>");
                $('#staffWebAccessLogInForm').addClass('form-group has-error');
                $('#staffWebAccessLogIn').focus();
                return false;
            }
            if ($('#staffWebAccessLogOut').val().length == 0) {
                $('#staffWebAccessLogOutHelpMe').empty();
                $('#staffWebAccessLogOutHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffWebAccessLogOutLabel'] + " </span>");
                $('#staffWebAccessLogOutForm').addClass('form-group has-error');
                $('#staffWebAccessLogOut').focus();
                return false;
            }
            if ($('#phpSession').val().length == 0) {
                $('#phpSessionHelpMe').empty();
                $('#phpSessionHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['phpSessionLabel'] + " </span>");
                $('#phpSessionForm').addClass('form-group has-error');
                $('#phpSession').focus();
                return false;
            }
            if ($('#ua_type').val().length == 0) {
                $('#ua_typeHelpMe').empty();
                $('#ua_typeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_typeLabel'] + " </span>");
                $('#ua_typeForm').addClass('form-group has-error');
                $('#ua_type').focus();
                return false;
            }
            if ($('#ua_family').val().length == 0) {
                $('#ua_familyHelpMe').empty();
                $('#ua_familyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_familyLabel'] + " </span>");
                $('#ua_familyForm').addClass('form-group has-error');
                $('#ua_family').focus();
                return false;
            }
            if ($('#ua_name').val().length == 0) {
                $('#ua_nameHelpMe').empty();
                $('#ua_nameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_nameLabel'] + " </span>");
                $('#ua_nameForm').addClass('form-group has-error');
                $('#ua_name').focus();
                return false;
            }
            if ($('#ua_version').val().length == 0) {
                $('#ua_versionHelpMe').empty();
                $('#ua_versionHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_versionLabel'] + " </span>");
                $('#ua_versionForm').addClass('form-group has-error');
                $('#ua_version').focus();
                return false;
            }
            if ($('#ua_url').val().length == 0) {
                $('#ua_urlHelpMe').empty();
                $('#ua_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_urlLabel'] + " </span>");
                $('#ua_urlForm').addClass('form-group has-error');
                $('#ua_url').focus();
                return false;
            }
            if ($('#ua_company').val().length == 0) {
                $('#ua_companyHelpMe').empty();
                $('#ua_companyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_companyLabel'] + " </span>");
                $('#ua_companyForm').addClass('form-group has-error');
                $('#ua_company').focus();
                return false;
            }
            if ($('#ua_company_url').val().length == 0) {
                $('#ua_company_urlHelpMe').empty();
                $('#ua_company_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_company_urlLabel'] + " </span>");
                $('#ua_company_urlForm').addClass('form-group has-error');
                $('#ua_company_url').focus();
                return false;
            }
            if ($('#ua_icon').val().length == 0) {
                $('#ua_iconHelpMe').empty();
                $('#ua_iconHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_iconLabel'] + " </span>");
                $('#ua_iconForm').addClass('form-group has-error');
                $('#ua_icon').focus();
                return false;
            }
            if ($('#ua_info_url').val().length == 0) {
                $('#ua_info_urlHelpMe').empty();
                $('#ua_info_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_info_urlLabel'] + " </span>");
                $('#ua_info_urlForm').addClass('form-group has-error');
                $('#ua_info_url').focus();
                return false;
            }
            if ($('#os_family').val().length == 0) {
                $('#os_familyHelpMe').empty();
                $('#os_familyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_familyLabel'] + " </span>");
                $('#os_familyForm').addClass('form-group has-error');
                $('#os_family').focus();
                return false;
            }
            if ($('#os_name').val().length == 0) {
                $('#os_nameHelpMe').empty();
                $('#os_nameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_nameLabel'] + " </span>");
                $('#os_nameForm').addClass('form-group has-error');
                $('#os_name').focus();
                return false;
            }
            if ($('#os_url').val().length == 0) {
                $('#os_urlHelpMe').empty();
                $('#os_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_urlLabel'] + " </span>");
                $('#os_urlForm').addClass('form-group has-error');
                $('#os_url').focus();
                return false;
            }
            if ($('#os_company').val().length == 0) {
                $('#os_companyHelpMe').empty();
                $('#os_companyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_companyLabel'] + " </span>");
                $('#os_companyForm').addClass('form-group has-error');
                $('#os_company').focus();
                return false;
            }
            if ($('#os_company_url').val().length == 0) {
                $('#os_company_urlHelpMe').empty();
                $('#os_company_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_company_urlLabel'] + " </span>");
                $('#os_company_urlForm').addClass('form-group has-error');
                $('#os_company_url').focus();
                return false;
            }
            if ($('#os_icon').val().length == 0) {
                $('#os_iconHelpMe').empty();
                $('#os_iconHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_iconLabel'] + " </span>");
                $('#os_iconForm').addClass('form-group has-error');
                $('#os_icon').focus();
                return false;
            }
            if ($('#ip_v4').val().length == 0) {
                $('#ip_v4HelpMe').empty();
                $('#ip_v4HelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_v4Label'] + " </span>");
                $('#ip_v4Form').addClass('form-group has-error');
                $('#ip_v4').focus();
                return false;
            }
            if ($('#ip_v6').val().length == 0) {
                $('#ip_v6HelpMe').empty();
                $('#ip_v6HelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_v6Label'] + " </span>");
                $('#ip_v6Form').addClass('form-group has-error');
                $('#ip_v6').focus();
                return false;
            }
            if ($('#ip_country_code').val().length == 0) {
                $('#ip_country_codeHelpMe').empty();
                $('#ip_country_codeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_country_codeLabel'] + " </span>");
                $('#ip_country_codeForm').addClass('form-group has-error');
                $('#ip_country_code').focus();
                return false;
            }
            if ($('#ip_country_name').val().length == 0) {
                $('#ip_country_nameHelpMe').empty();
                $('#ip_country_nameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_country_nameLabel'] + " </span>");
                $('#ip_country_nameForm').addClass('form-group has-error');
                $('#ip_country_name').focus();
                return false;
            }
            if ($('#ip_region_name').val().length == 0) {
                $('#ip_region_nameHelpMe').empty();
                $('#ip_region_nameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_region_nameLabel'] + " </span>");
                $('#ip_region_nameForm').addClass('form-group has-error');
                $('#ip_region_name').focus();
                return false;
            }
            if ($('#ip_latitude').val().length == 0) {
                $('#ip_latitudeHelpMe').empty();
                $('#ip_latitudeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_latitudeLabel'] + " </span>");
                $('#ip_latitudeForm').addClass('form-group has-error');
                $('#ip_latitude').focus();
                return false;
            }
            if ($('#ip_longtitude').val().length == 0) {
                $('#ip_longtitudeHelpMe').empty();
                $('#ip_longtitudeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_longtitudeLabel'] + " </span>");
                $('#ip_longtitudeForm').addClass('form-group has-error');
                $('#ip_longtitude').focus();
                return false;
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'create',
                    output: 'json',
                    staffId: $('#staffId').val(),
                    staffName: $('#staffName').val(),
                    staffWebAccessLogIn: $('#staffWebAccessLogIn').val(),
                    staffWebAccessLogOut: $('#staffWebAccessLogOut').val(),
                    phpSession: $('#phpSession').val(),
                    ua_type: $('#ua_type').val(),
                    ua_family: $('#ua_family').val(),
                    ua_name: $('#ua_name').val(),
                    ua_version: $('#ua_version').val(),
                    ua_url: $('#ua_url').val(),
                    ua_company: $('#ua_company').val(),
                    ua_company_url: $('#ua_company_url').val(),
                    ua_icon: $('#ua_icon').val(),
                    ua_info_url: $('#ua_info_url').val(),
                    os_family: $('#os_family').val(),
                    os_name: $('#os_name').val(),
                    os_url: $('#os_url').val(),
                    os_company: $('#os_company').val(),
                    os_company_url: $('#os_company_url').val(),
                    os_icon: $('#os_icon').val(),
                    ip_v4: $('#ip_v4').val(),
                    ip_v6: $('#ip_v6').val(),
                    ip_country_code: $('#ip_country_code').val(),
                    ip_country_name: $('#ip_country_name').val(),
                    ip_region_name: $('#ip_region_name').val(),
                    ip_latitude: $('#ip_latitude').val(),
                    ip_longtitude: $('#ip_longtitude').val(),
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
                        $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>");
                        $('#staffWebAccessId').val(data.staffWebAccessId);
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
                    }
                },
                error: function(xhr) {
                    $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                }
            });
        } else if (type == 5) {
            //New Record and listing
            if ($('#staffId').val().length == 0) {
                $('#staffIdHelpMe').empty();
                $('#staffIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffIdLabel'] + " </span>");
                $('#staffId').data('chosen').activate_action();
                return false;
            }
            if ($('#staffName').val().length == 0) {
                $('#staffNameHelpMe').empty();
                $('#staffNameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffNameLabel'] + " </span>");
                $('#staffNameForm').addClass('form-group has-error');
                $('#staffName').focus();
                return false;
            }
            if ($('#staffWebAccessLogIn').val().length == 0) {
                $('#staffWebAccessLogInHelpMe').empty();
                $('#staffWebAccessLogInHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffWebAccessLogInLabel'] + " </span>");
                $('#staffWebAccessLogInForm').addClass('form-group has-error');
                $('#staffWebAccessLogIn').focus();
                return false;
            }
            if ($('#staffWebAccessLogOut').val().length == 0) {
                $('#staffWebAccessLogOutHelpMe').empty();
                $('#staffWebAccessLogOutHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffWebAccessLogOutLabel'] + " </span>");
                $('#staffWebAccessLogOutForm').addClass('form-group has-error');
                $('#staffWebAccessLogOut').focus();
                return false;
            }
            if ($('#phpSession').val().length == 0) {
                $('#phpSessionHelpMe').empty();
                $('#phpSessionHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['phpSessionLabel'] + " </span>");
                $('#phpSessionForm').addClass('form-group has-error');
                $('#phpSession').focus();
                return false;
            }
            if ($('#ua_type').val().length == 0) {
                $('#ua_typeHelpMe').empty();
                $('#ua_typeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_typeLabel'] + " </span>");
                $('#ua_typeForm').addClass('form-group has-error');
                $('#ua_type').focus();
                return false;
            }
            if ($('#ua_family').val().length == 0) {
                $('#ua_familyHelpMe').empty();
                $('#ua_familyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_familyLabel'] + " </span>");
                $('#ua_familyForm').addClass('form-group has-error');
                $('#ua_family').focus();
                return false;
            }
            if ($('#ua_name').val().length == 0) {
                $('#ua_nameHelpMe').empty();
                $('#ua_nameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_nameLabel'] + " </span>");
                $('#ua_nameForm').addClass('form-group has-error');
                $('#ua_name').focus();
                return false;
            }
            if ($('#ua_version').val().length == 0) {
                $('#ua_versionHelpMe').empty();
                $('#ua_versionHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_versionLabel'] + " </span>");
                $('#ua_versionForm').addClass('form-group has-error');
                $('#ua_version').focus();
                return false;
            }
            if ($('#ua_url').val().length == 0) {
                $('#ua_urlHelpMe').empty();
                $('#ua_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_urlLabel'] + " </span>");
                $('#ua_urlForm').addClass('form-group has-error');
                $('#ua_url').focus();
                return false;
            }
            if ($('#ua_company').val().length == 0) {
                $('#ua_companyHelpMe').empty();
                $('#ua_companyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_companyLabel'] + " </span>");
                $('#ua_companyForm').addClass('form-group has-error');
                $('#ua_company').focus();
                return false;
            }
            if ($('#ua_company_url').val().length == 0) {
                $('#ua_company_urlHelpMe').empty();
                $('#ua_company_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_company_urlLabel'] + " </span>");
                $('#ua_company_urlForm').addClass('form-group has-error');
                $('#ua_company_url').focus();
                return false;
            }
            if ($('#ua_icon').val().length == 0) {
                $('#ua_iconHelpMe').empty();
                $('#ua_iconHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_iconLabel'] + " </span>");
                $('#ua_iconForm').addClass('form-group has-error');
                $('#ua_icon').focus();
                return false;
            }
            if ($('#ua_info_url').val().length == 0) {
                $('#ua_info_urlHelpMe').empty();
                $('#ua_info_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_info_urlLabel'] + " </span>");
                $('#ua_info_urlForm').addClass('form-group has-error');
                $('#ua_info_url').focus();
                return false;
            }
            if ($('#os_family').val().length == 0) {
                $('#os_familyHelpMe').empty();
                $('#os_familyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_familyLabel'] + " </span>");
                $('#os_familyForm').addClass('form-group has-error');
                $('#os_family').focus();
                return false;
            }
            if ($('#os_name').val().length == 0) {
                $('#os_nameHelpMe').empty();
                $('#os_nameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_nameLabel'] + " </span>");
                $('#os_nameForm').addClass('form-group has-error');
                $('#os_name').focus();
                return false;
            }
            if ($('#os_url').val().length == 0) {
                $('#os_urlHelpMe').empty();
                $('#os_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_urlLabel'] + " </span>");
                $('#os_urlForm').addClass('form-group has-error');
                $('#os_url').focus();
                return false;
            }
            if ($('#os_company').val().length == 0) {
                $('#os_companyHelpMe').empty();
                $('#os_companyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_companyLabel'] + " </span>");
                $('#os_companyForm').addClass('form-group has-error');
                $('#os_company').focus();
                return false;
            }
            if ($('#os_company_url').val().length == 0) {
                $('#os_company_urlHelpMe').empty();
                $('#os_company_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_company_urlLabel'] + " </span>");
                $('#os_company_urlForm').addClass('form-group has-error');
                $('#os_company_url').focus();
                return false;
            }
            if ($('#os_icon').val().length == 0) {
                $('#os_iconHelpMe').empty();
                $('#os_iconHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_iconLabel'] + " </span>");
                $('#os_iconForm').addClass('form-group has-error');
                $('#os_icon').focus();
                return false;
            }
            if ($('#ip_v4').val().length == 0) {
                $('#ip_v4HelpMe').empty();
                $('#ip_v4HelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_v4Label'] + " </span>");
                $('#ip_v4Form').addClass('form-group has-error');
                $('#ip_v4').focus();
                return false;
            }
            if ($('#ip_v6').val().length == 0) {
                $('#ip_v6HelpMe').empty();
                $('#ip_v6HelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_v6Label'] + " </span>");
                $('#ip_v6Form').addClass('form-group has-error');
                $('#ip_v6').focus();
                return false;
            }
            if ($('#ip_country_code').val().length == 0) {
                $('#ip_country_codeHelpMe').empty();
                $('#ip_country_codeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_country_codeLabel'] + " </span>");
                $('#ip_country_codeForm').addClass('form-group has-error');
                $('#ip_country_code').focus();
                return false;
            }
            if ($('#ip_country_name').val().length == 0) {
                $('#ip_country_nameHelpMe').empty();
                $('#ip_country_nameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_country_nameLabel'] + " </span>");
                $('#ip_country_nameForm').addClass('form-group has-error');
                $('#ip_country_name').focus();
                return false;
            }
            if ($('#ip_region_name').val().length == 0) {
                $('#ip_region_nameHelpMe').empty();
                $('#ip_region_nameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_region_nameLabel'] + " </span>");
                $('#ip_region_nameForm').addClass('form-group has-error');
                $('#ip_region_name').focus();
                return false;
            }
            if ($('#ip_latitude').val().length == 0) {
                $('#ip_latitudeHelpMe').empty();
                $('#ip_latitudeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_latitudeLabel'] + " </span>");
                $('#ip_latitudeForm').addClass('form-group has-error');
                $('#ip_latitude').focus();
                return false;
            }
            if ($('#ip_longtitude').val().length == 0) {
                $('#ip_longtitudeHelpMe').empty();
                $('#ip_longtitudeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_longtitudeLabel'] + " </span>");
                $('#ip_longtitudeForm').addClass('form-group has-error');
                $('#ip_longtitude').focus();
                return false;
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'create',
                    output: 'json',
                    staffId: $('#staffId').val(),
                    staffName: $('#staffName').val(),
                    staffWebAccessLogIn: $('#staffWebAccessLogIn').val(),
                    staffWebAccessLogOut: $('#staffWebAccessLogOut').val(),
                    phpSession: $('#phpSession').val(),
                    ua_type: $('#ua_type').val(),
                    ua_family: $('#ua_family').val(),
                    ua_name: $('#ua_name').val(),
                    ua_version: $('#ua_version').val(),
                    ua_url: $('#ua_url').val(),
                    ua_company: $('#ua_company').val(),
                    ua_company_url: $('#ua_company_url').val(),
                    ua_icon: $('#ua_icon').val(),
                    ua_info_url: $('#ua_info_url').val(),
                    os_family: $('#os_family').val(),
                    os_name: $('#os_name').val(),
                    os_url: $('#os_url').val(),
                    os_company: $('#os_company').val(),
                    os_company_url: $('#os_company_url').val(),
                    os_icon: $('#os_icon').val(),
                    ip_v4: $('#ip_v4').val(),
                    ip_v6: $('#ip_v6').val(),
                    ip_country_code: $('#ip_country_code').val(),
                    ip_country_name: $('#ip_country_name').val(),
                    ip_region_name: $('#ip_region_name').val(),
                    ip_latitude: $('#ip_latitude').val(),
                    ip_longtitude: $('#ip_longtitude').val(),
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
                        $('#infoPanel').html("<span class='label label-important'> <img src='./images/icons/smiley-roll-sweat.png'> " + data.message + "</span>");
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
            if ($('#staffId').val().length == 0) {
                $('#staffIdHelpMe').empty();
                $('#staffIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffIdLabel'] + " </span>");
                $('#staffId').data('chosen').activate_action();
                return false;
            }
            if ($('#staffName').val().length == 0) {
                $('#staffNameHelpMe').empty();
                $('#staffNameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffNameLabel'] + " </span>");
                $('#staffNameForm').addClass('form-group has-error');
                $('#staffName').focus();
                return false;
            }
            if ($('#staffWebAccessLogIn').val().length == 0) {
                $('#staffWebAccessLogInHelpMe').empty();
                $('#staffWebAccessLogInHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffWebAccessLogInLabel'] + " </span>");
                $('#staffWebAccessLogInForm').addClass('form-group has-error');
                $('#staffWebAccessLogIn').focus();
                return false;
            }
            if ($('#staffWebAccessLogOut').val().length == 0) {
                $('#staffWebAccessLogOutHelpMe').empty();
                $('#staffWebAccessLogOutHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffWebAccessLogOutLabel'] + " </span>");
                $('#staffWebAccessLogOutForm').addClass('form-group has-error');
                $('#staffWebAccessLogOut').focus();
                return false;
            }
            if ($('#phpSession').val().length == 0) {
                $('#phpSessionHelpMe').empty();
                $('#phpSessionHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['phpSessionLabel'] + " </span>");
                $('#phpSessionForm').addClass('form-group has-error');
                $('#phpSession').focus();
                return false;
            }
            if ($('#ua_type').val().length == 0) {
                $('#ua_typeHelpMe').empty();
                $('#ua_typeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_typeLabel'] + " </span>");
                $('#ua_typeForm').addClass('form-group has-error');
                $('#ua_type').focus();
                return false;
            }
            if ($('#ua_family').val().length == 0) {
                $('#ua_familyHelpMe').empty();
                $('#ua_familyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_familyLabel'] + " </span>");
                $('#ua_familyForm').addClass('form-group has-error');
                $('#ua_family').focus();
                return false;
            }
            if ($('#ua_name').val().length == 0) {
                $('#ua_nameHelpMe').empty();
                $('#ua_nameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_nameLabel'] + " </span>");
                $('#ua_nameForm').addClass('form-group has-error');
                $('#ua_name').focus();
                return false;
            }
            if ($('#ua_version').val().length == 0) {
                $('#ua_versionHelpMe').empty();
                $('#ua_versionHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_versionLabel'] + " </span>");
                $('#ua_versionForm').addClass('form-group has-error');
                $('#ua_version').focus();
                return false;
            }
            if ($('#ua_url').val().length == 0) {
                $('#ua_urlHelpMe').empty();
                $('#ua_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_urlLabel'] + " </span>");
                $('#ua_urlForm').addClass('form-group has-error');
                $('#ua_url').focus();
                return false;
            }
            if ($('#ua_company').val().length == 0) {
                $('#ua_companyHelpMe').empty();
                $('#ua_companyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_companyLabel'] + " </span>");
                $('#ua_companyForm').addClass('form-group has-error');
                $('#ua_company').focus();
                return false;
            }
            if ($('#ua_company_url').val().length == 0) {
                $('#ua_company_urlHelpMe').empty();
                $('#ua_company_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_company_urlLabel'] + " </span>");
                $('#ua_company_urlForm').addClass('form-group has-error');
                $('#ua_company_url').focus();
                return false;
            }
            if ($('#ua_icon').val().length == 0) {
                $('#ua_iconHelpMe').empty();
                $('#ua_iconHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_iconLabel'] + " </span>");
                $('#ua_iconForm').addClass('form-group has-error');
                $('#ua_icon').focus();
                return false;
            }
            if ($('#ua_info_url').val().length == 0) {
                $('#ua_info_urlHelpMe').empty();
                $('#ua_info_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_info_urlLabel'] + " </span>");
                $('#ua_info_urlForm').addClass('form-group has-error');
                $('#ua_info_url').focus();
                return false;
            }
            if ($('#os_family').val().length == 0) {
                $('#os_familyHelpMe').empty();
                $('#os_familyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_familyLabel'] + " </span>");
                $('#os_familyForm').addClass('form-group has-error');
                $('#os_family').focus();
                return false;
            }
            if ($('#os_name').val().length == 0) {
                $('#os_nameHelpMe').empty();
                $('#os_nameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_nameLabel'] + " </span>");
                $('#os_nameForm').addClass('form-group has-error');
                $('#os_name').focus();
                return false;
            }
            if ($('#os_url').val().length == 0) {
                $('#os_urlHelpMe').empty();
                $('#os_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_urlLabel'] + " </span>");
                $('#os_urlForm').addClass('form-group has-error');
                $('#os_url').focus();
                return false;
            }
            if ($('#os_company').val().length == 0) {
                $('#os_companyHelpMe').empty();
                $('#os_companyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_companyLabel'] + " </span>");
                $('#os_companyForm').addClass('form-group has-error');
                $('#os_company').focus();
                return false;
            }
            if ($('#os_company_url').val().length == 0) {
                $('#os_company_urlHelpMe').empty();
                $('#os_company_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_company_urlLabel'] + " </span>");
                $('#os_company_urlForm').addClass('form-group has-error');
                $('#os_company_url').focus();
                return false;
            }
            if ($('#os_icon').val().length == 0) {
                $('#os_iconHelpMe').empty();
                $('#os_iconHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_iconLabel'] + " </span>");
                $('#os_iconForm').addClass('form-group has-error');
                $('#os_icon').focus();
                return false;
            }
            if ($('#ip_v4').val().length == 0) {
                $('#ip_v4HelpMe').empty();
                $('#ip_v4HelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_v4Label'] + " </span>");
                $('#ip_v4Form').addClass('form-group has-error');
                $('#ip_v4').focus();
                return false;
            }
            if ($('#ip_v6').val().length == 0) {
                $('#ip_v6HelpMe').empty();
                $('#ip_v6HelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_v6Label'] + " </span>");
                $('#ip_v6Form').addClass('form-group has-error');
                $('#ip_v6').focus();
                return false;
            }
            if ($('#ip_country_code').val().length == 0) {
                $('#ip_country_codeHelpMe').empty();
                $('#ip_country_codeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_country_codeLabel'] + " </span>");
                $('#ip_country_codeForm').addClass('form-group has-error');
                $('#ip_country_code').focus();
                return false;
            }
            if ($('#ip_country_name').val().length == 0) {
                $('#ip_country_nameHelpMe').empty();
                $('#ip_country_nameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_country_nameLabel'] + " </span>");
                $('#ip_country_nameForm').addClass('form-group has-error');
                $('#ip_country_name').focus();
                return false;
            }
            if ($('#ip_region_name').val().length == 0) {
                $('#ip_region_nameHelpMe').empty();
                $('#ip_region_nameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_region_nameLabel'] + " </span>");
                $('#ip_region_nameForm').addClass('form-group has-error');
                $('#ip_region_name').focus();
                return false;
            }
            if ($('#ip_latitude').val().length == 0) {
                $('#ip_latitudeHelpMe').empty();
                $('#ip_latitudeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_latitudeLabel'] + " </span>");
                $('#ip_latitudeForm').addClass('form-group has-error');
                $('#ip_latitude').focus();
                return false;
            }
            if ($('#ip_longtitude').val().length == 0) {
                $('#ip_longtitudeHelpMe').empty();
                $('#ip_longtitudeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_longtitudeLabel'] + " </span>");
                $('#ip_longtitudeForm').addClass('form-group has-error');
                $('#ip_longtitude').focus();
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
                    staffWebAccessId: $('#staffWebAccessId').val(),
                    staffId: $('#staffId').val(),
                    staffName: $('#staffName').val(),
                    staffWebAccessLogIn: $('#staffWebAccessLogIn').val(),
                    staffWebAccessLogOut: $('#staffWebAccessLogOut').val(),
                    phpSession: $('#phpSession').val(),
                    ua_type: $('#ua_type').val(),
                    ua_family: $('#ua_family').val(),
                    ua_name: $('#ua_name').val(),
                    ua_version: $('#ua_version').val(),
                    ua_url: $('#ua_url').val(),
                    ua_company: $('#ua_company').val(),
                    ua_company_url: $('#ua_company_url').val(),
                    ua_icon: $('#ua_icon').val(),
                    ua_info_url: $('#ua_info_url').val(),
                    os_family: $('#os_family').val(),
                    os_name: $('#os_name').val(),
                    os_url: $('#os_url').val(),
                    os_company: $('#os_company').val(),
                    os_company_url: $('#os_company_url').val(),
                    os_icon: $('#os_icon').val(),
                    ip_v4: $('#ip_v4').val(),
                    ip_v6: $('#ip_v6').val(),
                    ip_country_code: $('#ip_country_code').val(),
                    ip_country_name: $('#ip_country_name').val(),
                    ip_region_name: $('#ip_region_name').val(),
                    ip_latitude: $('#ip_latitude').val(),
                    ip_longtitude: $('#ip_longtitude').val(),
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
                        $('#infoPanel').html("<span class='label label-important'>&nbsp;" + data.message + "</span>");
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
            if ($('#staffId').val().length == 0) {
                $('#staffIdHelpMe').empty();
                $('#staffIdHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffIdLabel'] + " </span>");
                $('#staffId').data('chosen').activate_action();
                return false;
            }
            if ($('#staffName').val().length == 0) {
                $('#staffNameHelpMe').empty();
                $('#staffNameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffNameLabel'] + " </span>");
                $('#staffNameForm').addClass('form-group has-error');
                $('#staffName').focus();
                return false;
            }
            if ($('#staffWebAccessLogIn').val().length == 0) {
                $('#staffWebAccessLogInHelpMe').empty();
                $('#staffWebAccessLogInHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffWebAccessLogInLabel'] + " </span>");
                $('#staffWebAccessLogInForm').addClass('form-group has-error');
                $('#staffWebAccessLogIn').focus();
                return false;
            }
            if ($('#staffWebAccessLogOut').val().length == 0) {
                $('#staffWebAccessLogOutHelpMe').empty();
                $('#staffWebAccessLogOutHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['staffWebAccessLogOutLabel'] + " </span>");
                $('#staffWebAccessLogOutForm').addClass('form-group has-error');
                $('#staffWebAccessLogOut').focus();
                return false;
            }
            if ($('#phpSession').val().length == 0) {
                $('#phpSessionHelpMe').empty();
                $('#phpSessionHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['phpSessionLabel'] + " </span>");
                $('#phpSessionForm').addClass('form-group has-error');
                $('#phpSession').focus();
                return false;
            }
            if ($('#ua_type').val().length == 0) {
                $('#ua_typeHelpMe').empty();
                $('#ua_typeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_typeLabel'] + " </span>");
                $('#ua_typeForm').addClass('form-group has-error');
                $('#ua_type').focus();
                return false;
            }
            if ($('#ua_family').val().length == 0) {
                $('#ua_familyHelpMe').empty();
                $('#ua_familyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_familyLabel'] + " </span>");
                $('#ua_familyForm').addClass('form-group has-error');
                $('#ua_family').focus();
                return false;
            }
            if ($('#ua_name').val().length == 0) {
                $('#ua_nameHelpMe').empty();
                $('#ua_nameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_nameLabel'] + " </span>");
                $('#ua_nameForm').addClass('form-group has-error');
                $('#ua_name').focus();
                return false;
            }
            if ($('#ua_version').val().length == 0) {
                $('#ua_versionHelpMe').empty();
                $('#ua_versionHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_versionLabel'] + " </span>");
                $('#ua_versionForm').addClass('form-group has-error');
                $('#ua_version').focus();
                return false;
            }
            if ($('#ua_url').val().length == 0) {
                $('#ua_urlHelpMe').empty();
                $('#ua_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_urlLabel'] + " </span>");
                $('#ua_urlForm').addClass('form-group has-error');
                $('#ua_url').focus();
                return false;
            }
            if ($('#ua_company').val().length == 0) {
                $('#ua_companyHelpMe').empty();
                $('#ua_companyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_companyLabel'] + " </span>");
                $('#ua_companyForm').addClass('form-group has-error');
                $('#ua_company').focus();
                return false;
            }
            if ($('#ua_company_url').val().length == 0) {
                $('#ua_company_urlHelpMe').empty();
                $('#ua_company_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_company_urlLabel'] + " </span>");
                $('#ua_company_urlForm').addClass('form-group has-error');
                $('#ua_company_url').focus();
                return false;
            }
            if ($('#ua_icon').val().length == 0) {
                $('#ua_iconHelpMe').empty();
                $('#ua_iconHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_iconLabel'] + " </span>");
                $('#ua_iconForm').addClass('form-group has-error');
                $('#ua_icon').focus();
                return false;
            }
            if ($('#ua_info_url').val().length == 0) {
                $('#ua_info_urlHelpMe').empty();
                $('#ua_info_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ua_info_urlLabel'] + " </span>");
                $('#ua_info_urlForm').addClass('form-group has-error');
                $('#ua_info_url').focus();
                return false;
            }
            if ($('#os_family').val().length == 0) {
                $('#os_familyHelpMe').empty();
                $('#os_familyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_familyLabel'] + " </span>");
                $('#os_familyForm').addClass('form-group has-error');
                $('#os_family').focus();
                return false;
            }
            if ($('#os_name').val().length == 0) {
                $('#os_nameHelpMe').empty();
                $('#os_nameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_nameLabel'] + " </span>");
                $('#os_nameForm').addClass('form-group has-error');
                $('#os_name').focus();
                return false;
            }
            if ($('#os_url').val().length == 0) {
                $('#os_urlHelpMe').empty();
                $('#os_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_urlLabel'] + " </span>");
                $('#os_urlForm').addClass('form-group has-error');
                $('#os_url').focus();
                return false;
            }
            if ($('#os_company').val().length == 0) {
                $('#os_companyHelpMe').empty();
                $('#os_companyHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_companyLabel'] + " </span>");
                $('#os_companyForm').addClass('form-group has-error');
                $('#os_company').focus();
                return false;
            }
            if ($('#os_company_url').val().length == 0) {
                $('#os_company_urlHelpMe').empty();
                $('#os_company_urlHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_company_urlLabel'] + " </span>");
                $('#os_company_urlForm').addClass('form-group has-error');
                $('#os_company_url').focus();
                return false;
            }
            if ($('#os_icon').val().length == 0) {
                $('#os_iconHelpMe').empty();
                $('#os_iconHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['os_iconLabel'] + " </span>");
                $('#os_iconForm').addClass('form-group has-error');
                $('#os_icon').focus();
                return false;
            }
            if ($('#ip_v4').val().length == 0) {
                $('#ip_v4HelpMe').empty();
                $('#ip_v4HelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_v4Label'] + " </span>");
                $('#ip_v4Form').addClass('form-group has-error');
                $('#ip_v4').focus();
                return false;
            }
            if ($('#ip_v6').val().length == 0) {
                $('#ip_v6HelpMe').empty();
                $('#ip_v6HelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_v6Label'] + " </span>");
                $('#ip_v6Form').addClass('form-group has-error');
                $('#ip_v6').focus();
                return false;
            }
            if ($('#ip_country_code').val().length == 0) {
                $('#ip_country_codeHelpMe').empty();
                $('#ip_country_codeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_country_codeLabel'] + " </span>");
                $('#ip_country_codeForm').addClass('form-group has-error');
                $('#ip_country_code').focus();
                return false;
            }
            if ($('#ip_country_name').val().length == 0) {
                $('#ip_country_nameHelpMe').empty();
                $('#ip_country_nameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_country_nameLabel'] + " </span>");
                $('#ip_country_nameForm').addClass('form-group has-error');
                $('#ip_country_name').focus();
                return false;
            }
            if ($('#ip_region_name').val().length == 0) {
                $('#ip_region_nameHelpMe').empty();
                $('#ip_region_nameHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_region_nameLabel'] + " </span>");
                $('#ip_region_nameForm').addClass('form-group has-error');
                $('#ip_region_name').focus();
                return false;
            }
            if ($('#ip_latitude').val().length == 0) {
                $('#ip_latitudeHelpMe').empty();
                $('#ip_latitudeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_latitudeLabel'] + " </span>");
                $('#ip_latitudeForm').addClass('form-group has-error');
                $('#ip_latitude').focus();
                return false;
            }
            if ($('#ip_longtitude').val().length == 0) {
                $('#ip_longtitudeHelpMe').empty();
                $('#ip_longtitudeHelpMe').html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['ip_longtitudeLabel'] + " </span>");
                $('#ip_longtitudeForm').addClass('form-group has-error');
                $('#ip_longtitude').focus();
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
                    staffWebAccessId: $('#staffWebAccessId').val(),
                    staffId: $('#staffId').val(),
                    staffName: $('#staffName').val(),
                    staffWebAccessLogIn: $('#staffWebAccessLogIn').val(),
                    staffWebAccessLogOut: $('#staffWebAccessLogOut').val(),
                    phpSession: $('#phpSession').val(),
                    ua_type: $('#ua_type').val(),
                    ua_family: $('#ua_family').val(),
                    ua_name: $('#ua_name').val(),
                    ua_version: $('#ua_version').val(),
                    ua_url: $('#ua_url').val(),
                    ua_company: $('#ua_company').val(),
                    ua_company_url: $('#ua_company_url').val(),
                    ua_icon: $('#ua_icon').val(),
                    ua_info_url: $('#ua_info_url').val(),
                    os_family: $('#os_family').val(),
                    os_name: $('#os_name').val(),
                    os_url: $('#os_url').val(),
                    os_company: $('#os_company').val(),
                    os_company_url: $('#os_company_url').val(),
                    os_icon: $('#os_icon').val(),
                    ip_v4: $('#ip_v4').val(),
                    ip_v6: $('#ip_v6').val(),
                    ip_country_code: $('#ip_country_code').val(),
                    ip_country_name: $('#ip_country_name').val(),
                    ip_region_name: $('#ip_region_name').val(),
                    ip_latitude: $('#ip_latitude').val(),
                    ip_longtitude: $('#ip_longtitude').val(),
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
                        $('#infoPanel').html("<span class='label label-important'>&nbsp;" + data.message + "</span>");
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
                var value = $('#staffWebAccessId').val();
                if (!value) {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-important'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "<span>");
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
                            staffWebAccessId: $('#staffWebAccessId').val(),
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
                                $('#infoPanel').html("<span class='label label-important'>&nbsp;" + data.message + "</span>");
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
function resetRecord(leafId, url, urlList, securityToken, createAccess, updateAccess, deleteAccess) {
    $('#infoPanel').empty();
    $('#infoPanel').html('');
    $('#infoPanel').html("<span class='label label-important'><img src='./images/icons/fruit-orange.png'> " + decodeURIComponent(t['resetRecordTextLabel']) + "</span>").delay(1000).fadeOut();
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
    $('#firstRecordButton').attr('onClick', "firstRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
    $('#previousRecordButton').removeClass();
    $('#previousRecordButton').addClass('btn btn-default disabled');
    $('#previousRecordButton').attr('onClick', '');
    $('#nextRecordButton').removeClass();
    $('#nextRecordButton').addClass('btn btn-default disabled');
    $('#nextRecordButton').attr('onClick', '');
    $('#endRecordButton').removeClass();
    $('#endRecordButton').addClass('btn btn-default');
    $('#endRecordButton').attr('onClick', "endRecord\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + updateAccess + "\")");
    $("#staffWebAccessId").val('');
    $("#staffWebAccessIdHelpMe").empty();
    $("#staffWebAccessIdHelpMe").html('');
    
    $("#staffId").val('');
    $("#staffIdHelpMe").empty();
    $("#staffIdHelpMe").html('');
    $('#staffId').trigger("chosen:updated");
    $("#staffName").val('');
    $("#staffNameHelpMe").empty();
    $("#staffNameHelpMe").html('');
    $("#staffWebAccessLogIn").val('');
    $("#staffWebAccessLogInHelpMe").empty();
    $("#staffWebAccessLogInHelpMe").html('');
    $("#staffWebAccessLogOut").val('');
    $("#staffWebAccessLogOutHelpMe").empty();
    $("#staffWebAccessLogOutHelpMe").html('');
    $("#phpSession").val('');
    $("#phpSessionHelpMe").empty();
    $("#phpSessionHelpMe").html('');
    $("#ua_type").val('');
    $("#ua_typeHelpMe").empty();
    $("#ua_typeHelpMe").html('');
    $("#ua_family").val('');
    $("#ua_familyHelpMe").empty();
    $("#ua_familyHelpMe").html('');
    $('#ua_family').empty();
    $('#ua_family').val('');
    $("#ua_name").val('');
    $("#ua_nameHelpMe").empty();
    $("#ua_nameHelpMe").html('');
    $('#ua_name').empty();
    $('#ua_name').val('');
    $("#ua_version").val('');
    $("#ua_versionHelpMe").empty();
    $("#ua_versionHelpMe").html('');
    $('#ua_version').empty();
    $('#ua_version').val('');
    $("#ua_url").val('');
    $("#ua_urlHelpMe").empty();
    $("#ua_urlHelpMe").html('');
    $('#ua_url').empty();
    $('#ua_url').val('');
    $("#ua_company").val('');
    $("#ua_companyHelpMe").empty();
    $("#ua_companyHelpMe").html('');
    $('#ua_company').empty();
    $('#ua_company').val('');
    $("#ua_company_url").val('');
    $("#ua_company_urlHelpMe").empty();
    $("#ua_company_urlHelpMe").html('');
    $('#ua_company_url').empty();
    $('#ua_company_url').val('');
    $("#ua_icon").val('');
    $("#ua_iconHelpMe").empty();
    $("#ua_iconHelpMe").html('');
    $('#ua_icon').empty();
    $('#ua_icon').val('');
    $("#ua_info_url").val('');
    $("#ua_info_urlHelpMe").empty();
    $("#ua_info_urlHelpMe").html('');
    $('#ua_info_url').empty();
    $('#ua_info_url').val('');
    $("#os_family").val('');
    $("#os_familyHelpMe").empty();
    $("#os_familyHelpMe").html('');
    $('#os_family').empty();
    $('#os_family').val('');
    $("#os_name").val('');
    $("#os_nameHelpMe").empty();
    $("#os_nameHelpMe").html('');
    $('#os_name').empty();
    $('#os_name').val('');
    $("#os_url").val('');
    $("#os_urlHelpMe").empty();
    $("#os_urlHelpMe").html('');
    $('#os_url').empty();
    $('#os_url').val('');
    $("#os_company").val('');
    $("#os_companyHelpMe").empty();
    $("#os_companyHelpMe").html('');
    $('#os_company').empty();
    $('#os_company').val('');
    $("#os_company_url").val('');
    $("#os_company_urlHelpMe").empty();
    $("#os_company_urlHelpMe").html('');
    $('#os_company_url').empty();
    $('#os_company_url').val('');
    $("#os_icon").val('');
    $("#os_iconHelpMe").empty();
    $("#os_iconHelpMe").html('');
    $('#os_icon').empty();
    $('#os_icon').val('');
    $("#ip_v4").val('');
    $("#ip_v4HelpMe").empty();
    $("#ip_v4HelpMe").html('');
    $('#ip_v4').empty();
    $('#ip_v4').val('');
    $("#ip_v6").val('');
    $("#ip_v6HelpMe").empty();
    $("#ip_v6HelpMe").html('');
    $('#ip_v6').empty();
    $('#ip_v6').val('');
    $("#ip_country_code").val('');
    $("#ip_country_codeHelpMe").empty();
    $("#ip_country_codeHelpMe").html('');
    $('#ip_country_code').empty();
    $('#ip_country_code').val('');
    $("#ip_country_name").val('');
    $("#ip_country_nameHelpMe").empty();
    $("#ip_country_nameHelpMe").html('');
    $('#ip_country_name').empty();
    $('#ip_country_name').val('');
    $("#ip_region_name").val('');
    $("#ip_region_nameHelpMe").empty();
    $("#ip_region_nameHelpMe").html('');
    $('#ip_region_name').empty();
    $('#ip_region_name').val('');
    $("#ip_latitude").val('');
    $("#ip_latitudeHelpMe").empty();
    $("#ip_latitudeHelpMe").html('');
    $('#ip_latitude').empty();
    $('#ip_latitude').val('');
    $("#ip_longtitude").val('');
    $("#ip_longtitudeHelpMe").empty();
    $("#ip_longtitudeHelpMe").html('');
    $('#ip_longtitude').empty();
    $('#ip_longtitude').val('');
}
function postRecord(leafId, url, urlList, SecurityToken) {
    var css = $('#postRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        return false;
    }
}
function firstRecord(leafId, url, urlList, securityToken, updateAccess, deleteAccess) {
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
                // this is where we append a loading image
                $('#infoPanel').empty();
                $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                            staffWebAccessId: data.firstRecord,
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
                            if (data.success == true) {
                                // resetting field value
                                $('#staffWebAccessId').val(data.data.staffWebAccessId);
                                $('#staffId').val(data.data.staffId);
                                $('#staffId').trigger("chosen:updated");
                                $('#staffName').val(data.data.staffName);
                                $('#staffWebAccessLogIn').val(data.data.staffWebAccessLogIn);
                                $('#staffWebAccessLogOut').val(data.data.staffWebAccessLogOut);
                                $('#phpSession').val(data.data.phpSession);
                                $('#ua_type').val(data.data.ua_type);
                                $('#ua_family').val(data.data.ua_family);
                                $('#ua_name').val(data.data.ua_name);
                                $('#ua_version').val(data.data.ua_version);
                                $('#ua_url').val(data.data.ua_url);
                                $('#ua_company').val(data.data.ua_company);
                                $('#ua_company_url').val(data.data.ua_company_url);
                                $('#ua_icon').val(data.data.ua_icon);
                                $('#ua_info_url').val(data.data.ua_info_url);
                                $('#os_family').val(data.data.os_family);
                                $('#os_name').val(data.data.os_name);
                                $('#os_url').val(data.data.os_url);
                                $('#os_company').val(data.data.os_company);
                                $('#os_company_url').val(data.data.os_company_url);
                                $('#os_icon').val(data.data.os_icon);
                                $('#ip_v4').val(data.data.ip_v4);
                                $('#ip_v6').val(data.data.ip_v6);
                                $('#ip_country_code').val(data.data.ip_country_code);
                                $('#ip_country_name').val(data.data.ip_country_name);
                                $('#ip_region_name').val(data.data.ip_region_name);
                                $('#ip_latitude').val(data.data.ip_latitude);
                                $('#ip_longtitude').val(data.data.ip_longtitude);
                                if (data.nextRecord > 0) {
                                    $('#previousRecordButton').removeClass();
                                    $('#previousRecordButton').addClass('btn btn-default  disabled');
                                    $('#previousRecordButton').attr('onClick', '');
                                    $('#nextRecordButton').removeClass();
                                    $('#nextRecordButton').addClass('btn btn-default');
                                    $('#nextRecordButton').attr('onClick', '');
                                    $('#nextRecordButton').attr('onClick', "nextRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
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
                    $('#infoPanel').html("<span class='label label-important'>&nbsp;<img src='./images/icons/smiley-roll-sweat.png'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
function endRecord(leafId, url, urlList, securityToken, updateAccess, deleteAccess) {
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
                if (data.success == true) {
                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: {
                            method: 'read',
                            staffWebAccessId: data.lastRecord,
                            output: 'json',
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
                                $('#staffWebAccessId').val(data.data.staffWebAccessId);
                                $('#staffId').val(data.data.staffId);
                                $('#staffId').trigger("chosen:updated");
                                $('#staffName').val(data.data.staffName);
                                $('#staffWebAccessLogIn').val(data.data.staffWebAccessLogIn);
                                $('#staffWebAccessLogOut').val(data.data.staffWebAccessLogOut);
                                $('#phpSession').val(data.data.phpSession);
                                $('#ua_type').val(data.data.ua_type);
                                $('#ua_family').val(data.data.ua_family);
                                $('#ua_name').val(data.data.ua_name);
                                $('#ua_version').val(data.data.ua_version);
                                $('#ua_url').val(data.data.ua_url);
                                $('#ua_company').val(data.data.ua_company);
                                $('#ua_company_url').val(data.data.ua_company_url);
                                $('#ua_icon').val(data.data.ua_icon);
                                $('#ua_info_url').val(data.data.ua_info_url);
                                $('#os_family').val(data.data.os_family);
                                $('#os_name').val(data.data.os_name);
                                $('#os_url').val(data.data.os_url);
                                $('#os_company').val(data.data.os_company);
                                $('#os_company_url').val(data.data.os_company_url);
                                $('#os_icon').val(data.data.os_icon);
                                $('#ip_v4').val(data.data.ip_v4);
                                $('#ip_v6').val(data.data.ip_v6);
                                $('#ip_country_code').val(data.data.ip_country_code);
                                $('#ip_country_name').val(data.data.ip_country_name);
                                $('#ip_region_name').val(data.data.ip_region_name);
                                $('#ip_latitude').val(data.data.ip_latitude);
                                $('#ip_longtitude').val(data.data.ip_longtitude);
                                if (data.lastRecord != 0) {
                                    $('#previousRecordButton').removeClass();
                                    $('#previousRecordButton').addClass('btn btn-default');
                                    $('#previousRecordButton').attr('onClick', "previousRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
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
                    $('#infoPanel').html("<span class='label label-important'>&nbsp;" + data.message + "</span>");
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
function previousRecord(leafId, url, urlList, securityToken, updateAccess, deleteAccess) {
    var css = $('#previousRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $('#newButton').removeClass();
        if ($('#previousRecordCounter').val() == '' || $('#previousRecordCounter').val() == undefined) {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-important'>" + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
                    staffWebAccessId: $('#previousRecordCounter').val(),
                    output: 'json',
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
                        $('#staffWebAccessId').val(data.data.staffWebAccessId);
                        $('#staffId').val(data.data.staffId);
                        $('#staffId').trigger("chosen:updated");
                        $('#staffName').val(data.data.staffName);
                        $('#staffWebAccessLogIn').val(data.data.staffWebAccessLogIn);
                        $('#staffWebAccessLogOut').val(data.data.staffWebAccessLogOut);
                        $('#phpSession').val(data.data.phpSession);
                        $('#ua_type').val(data.data.ua_type);
                        $('#ua_family').val(data.data.ua_family);
                        $('#ua_name').val(data.data.ua_name);
                        $('#ua_version').val(data.data.ua_version);
                        $('#ua_url').val(data.data.ua_url);
                        $('#ua_company').val(data.data.ua_company);
                        $('#ua_company_url').val(data.data.ua_company_url);
                        $('#ua_icon').val(data.data.ua_icon);
                        $('#ua_info_url').val(data.data.ua_info_url);
                        $('#os_family').val(data.data.os_family);
                        $('#os_name').val(data.data.os_name);
                        $('#os_url').val(data.data.os_url);
                        $('#os_company').val(data.data.os_company);
                        $('#os_company_url').val(data.data.os_company_url);
                        $('#os_icon').val(data.data.os_icon);
                        $('#ip_v4').val(data.data.ip_v4);
                        $('#ip_v6').val(data.data.ip_v6);
                        $('#ip_country_code').val(data.data.ip_country_code);
                        $('#ip_country_name').val(data.data.ip_country_name);
                        $('#ip_region_name').val(data.data.ip_region_name);
                        $('#ip_latitude').val(data.data.ip_latitude);
                        $('#ip_longtitude').val(data.data.ip_longtitude);
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
                            $('#nextRecordButton').attr('onClick', "nextRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
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
function nextRecord(leafId, url, urlList, securityToken, updateAccess, deleteAccess) {
    var css = $('#nextRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $('#newButton').removeClass();
        if ($('#nextRecordCounter').val() == '' || $('#nextRecordCounter').val() == undefined) {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-important'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
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
                    staffWebAccessId: $('#nextRecordCounter').val(),
                    output: 'json',
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
                        $('#staffWebAccessId').val(data.data.staffWebAccessId);
                        $('#staffId').val(data.data.staffId);
                        $('#staffId').trigger("chosen:updated");
                        $('#staffName').val(data.data.staffName);
                        $('#staffWebAccessLogIn').val(data.data.staffWebAccessLogIn);
                        $('#staffWebAccessLogOut').val(data.data.staffWebAccessLogOut);
                        $('#phpSession').val(data.data.phpSession);
                        $('#ua_type').val(data.data.ua_type);
                        $('#ua_family').val(data.data.ua_family);
                        $('#ua_name').val(data.data.ua_name);
                        $('#ua_version').val(data.data.ua_version);
                        $('#ua_url').val(data.data.ua_url);
                        $('#ua_company').val(data.data.ua_company);
                        $('#ua_company_url').val(data.data.ua_company_url);
                        $('#ua_icon').val(data.data.ua_icon);
                        $('#ua_info_url').val(data.data.ua_info_url);
                        $('#os_family').val(data.data.os_family);
                        $('#os_name').val(data.data.os_name);
                        $('#os_url').val(data.data.os_url);
                        $('#os_company').val(data.data.os_company);
                        $('#os_company_url').val(data.data.os_company_url);
                        $('#os_icon').val(data.data.os_icon);
                        $('#ip_v4').val(data.data.ip_v4);
                        $('#ip_v6').val(data.data.ip_v6);
                        $('#ip_country_code').val(data.data.ip_country_code);
                        $('#ip_country_name').val(data.data.ip_country_name);
                        $('#ip_region_name').val(data.data.ip_region_name);
                        $('#ip_latitude').val(data.data.ip_latitude);
                        $('#ip_longtitude').val(data.data.ip_longtitude);
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
                        if (parseFloat(data.previousRecord) > 0) {
                            $('#previousRecordButton').removeClass();
                            $('#previousRecordButton').addClass('btn btn-default');
                            $('#previousRecordButton').attr('onClick', "previousRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
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
