function getLateInterestType(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'lateInterestType'}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
            if (data.success == false) {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<div class='alert alert-error col-md-12'>" + data.message + "</div>");
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $("#lateInterestType").trigger("chosen:updated");
                $('#infoPanel').html("<div class='alert alert-success  col-md-12'> <img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</div>");
            }
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
}
function checkDuplicate(leafId, page, securityToken) {
    if ($("#lateInterestCode").val().length == 0) {
        alert(t['oddTextLabel']);
        return false;
    }
    $.ajax({type: 'GET', url: page, data: {lateInterestCode: $("#lateInterestCode").val(), method: 'duplicate', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
            }
        }, success: function(data) {
            if (data.success == true) {
                if (data.total != 0) {
                    $("#lateInterestCode").empty();
                    $("#lateInterestCode").val('');
                    $("#lateInterestCode").focus();
                    $("#lateInterestCodeForm").removeClass();
                    $("#lateInterestCodeForm").addClass("col-md-12 form-group has-error");
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
                $("#lateInterestForm").removeClass();
                $("#lateInterestForm").addClass("col-md-12 form-group has-error");
            }
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
}
function showGrid(leafId, page, securityToken, offset, limit, type) {
    $.ajax({type: 'POST', url: page, data: {offset: offset, limit: limit, method: 'read', type: 'list', detail: 'body', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
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
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
}
function ajaxQuerySearchAll(leafId, url, securityToken) {
    $('#clearSearch').removeClass();
    $('#clearSearch').addClass('btn');
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
    $.ajax({type: 'POST', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'list', detail: 'body', query: queryText, securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
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
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
}
function ajaxQuerySearchAllCharacter(leafId, url, securityToken, character) {
    $('#clearSearch').removeClass();
    $('#clearSearch').addClass('btn');
    $.ajax({type: 'POST', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'list', detail: 'body', securityToken: securityToken, leafId: leafId, character: character}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
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
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
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
    if (dateRangeStart.length == 0) {
        dateRangeStart = $('#dateRangeStart').val();
    }
    if (dateRangeEnd.length == 0) {
        dateRangeEnd = $('#dateRangeEnd').val();
    }
    $.ajax({type: 'POST', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'list', detail: 'body', query: $('#query').val(), securityToken: securityToken, leafId: leafId, dateRangeStart: dateRangeStart, dateRangeEnd: dateRangeEnd, dateRangeType: dateRangeType}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
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
                    case'day':
                        strDate = "<b>" + t['dayTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ", " + dateStart.getFullYear();
                        break;
                    case'month':
                        strDate = "<b>" + t['monthTextLabel'] + "</b> : " + dateStart.getMonthName() + ", " + dateStart.getFullYear();
                        break;
                    case'year':
                        strDate = "<b>" + t['yearTextLabel'] + "</b> : " + dateStart.getFullYear();
                        break;
                    case'week':
                        if (dateRangeEnd.length == 0) {
                            strDate = "<b>" + t['dayTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ", " + dateStart.getFullYear();
                        } else {
                            strDate = "<b>" + t['betweenTextLabel'] + "</b> : " + dateStart.getDayName() + ", " + dateStart.getMonthName() + ", " + dateStart.getDate() + ", " + dateStart.getFullYear() + "&nbsp;<img src='./images/icons/arrow-curve-000-left.png'>&nbsp;" + dateEnd.getDayName() + ", " + dateEnd.getMonthName() + ", " + dateEnd.getDate() + ", " + dateEnd.getFullYear();
                        }
                        break;
                    case'between':
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
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
}
function ajaxQuerySearchAllDateRange(leafId, url, securityToken) {
    ajaxQuerySearchAllDate(leafId, url, securityToken, $('#dateRangeStart').val(), $('#dateRangeEnd').val(), 'between', '', t['loadingTextLabel'], t['loadingCompleteTextLabel'], t['loadingErrorTextLabel']);
}
function showForm(leafId, url, securityToken) {
    sleep(500);
    $.ajax({type: 'POST', url: url, data: {method: 'new', type: 'form', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
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
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
}
function showFormUpdate(leafId, url, urlList, securityToken, lateInterestId, updateAccess, deleteAccess) {
    sleep(500);
    $.ajax({type: 'POST', url: urlList, data: {method: 'read', type: 'form', lateInterestId: lateInterestId, securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
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
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
}
function showModalDelete(lateInterestId, lateInterestTypeId, lateInterestCode, lateInterestDescription, lateInterestWarningDay, lateInterestGraceDay) {
    $('#lateInterestIdPreview').val('');
    $('#lateInterestIdPreview').val(decodeURIComponent(lateInterestId));
    $('#lateInterestTypeIdPreview').val('');
    $('#lateInterestTypeIdPreview').val(decodeURIComponent(lateInterestTypeId));
    $('#lateInterestCodePreview').val('');
    $('#lateInterestCodePreview').val(decodeURIComponent(lateInterestCode));
    $('#lateInterestDescriptionPreview').val('');
    $('#lateInterestDescriptionPreview').val(decodeURIComponent(lateInterestDescription));
    $('#lateInterestWarningDayPreview').val('');
    $('#lateInterestWarningDayPreview').val(decodeURIComponent(lateInterestWarningDay));
    $('#lateInterestGraceDayPreview').val('');
    $('#lateInterestGraceDayPreview').val(decodeURIComponent(lateInterestGraceDay));
    showMeModal('deletePreview', 1);
}
function deleteGridRecord(leafId, url, urlList, securityToken) {
    $.ajax({type: 'POST', url: url, data: {method: 'delete', output: 'json', lateInterestId: $('#lateInterestIdPreview').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
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
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
}
function showFormCreateDetail(leafId, url, securityToken) {
    if ($('#lateInterestDetailPeriod9999').val().length == 0) {
        $('#infoPanel').empty();
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestDetailPeriodLabel'] + "</span>");
        $('#lateInterestDetailPeriod9999HelpMe').empty();
        $('#lateInterestDetailPeriod9999HelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestDetailPeriodLabel'] + "</span>");
        $('#lateInterestDetailPeriod9999').addClass('form-group has-error');
        $('#lateInterestDetailPeriod9999').focus();
        return false;
    }
    if ($('#LateInterestDetailAmount9999').val().length == 0) {
        $('#infoPanel').empty();
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['LateInterestDetailAmountLabel'] + "</span>");
        $('#LateInterestDetailAmount9999HelpMe').empty();
        $('#LateInterestDetailAmount9999HelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['LateInterestDetailAmountLabel'] + "</span>");
        $('#LateInterestDetailAmount9999').addClass('form-group has-error');
        $('#LateInterestDetailAmount9999').focus();
        return false;
    }
    $('#infoPanel').empty();
    $('#infoPanel').html('');
    $('#infoPanel').html("<span class='label label-success'>&nbsp;" + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>");
    if ($('#infoPanel').is(':hidden')) {
        $('#infoPanel').show();
    }
    $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', lateInterestId: $('#lateInterestId').val(), lateInterestDetailPeriod: $('#lateInterestDetailPeriod9999').val(), LateInterestDetailAmount: $('#LateInterestDetailAmount9999').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            $('#miniInfoPanel9999').empty();
            $('#miniInfoPanel9999').html('');
            $('#miniInfoPanel9999').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
            if (data.success == true) {
                $.ajax({type: 'POST', url: url, data: {method: 'read', output: 'table', offset: '0', limit: '9999', lateInterestId: $('#lateInterestId').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                        $('#miniInfoPanel9999').empty();
                        $('#miniInfoPanel9999').html('');
                        $('#miniInfoPanel9999').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                        if ($('#infoPanel').is(':hidden')) {
                            $('#infoPanel').show();
                        }
                    }, success: function(data) {
                        if (data.success == true) {
                            $('#tableBody').empty();
                            $('#tableBody').html('');
                            $('#tableBody').html(data.tableData);

                            $("#lateInterestId9999").prop("disabled", "false");
                            $("#lateInterestId9999").removeAttr("disabled", "");
                            $("#lateInterestId9999").val('');
                            $("#lateInterestId9999").trigger("chosen:updated");
                            $("#lateInterestDetailPeriod9999").prop("disabled", "false");
                            $("#lateInterestDetailPeriod9999").removeAttr("disabled", "");
                            $("#lateInterestDetailPeriod9999").val('');
                            $("#LateInterestDetailAmount9999").prop("disabled", "false");
                            $("#LateInterestDetailAmount9999").removeAttr("disabled", "");
                            $("#LateInterestDetailAmount9999").val('');

                            $(".chzn-select").chosen();
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                            if ($('#infoPanel').is(':hidden')) {
                                $('#infoPanel').show();
                            }
                        }
                    }, error: function(xhr) {
                        $('#infoError').empty();
                        $('#infoError').html('');
                        $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                        $('#infoErrorRowFluid').removeClass();
                        $('#infoErrorRowFluid').addClass('row');
                    }});
                $('#miniInfoPanel9999').html("<span class='label label-success'>&nbsp;<a class='close' data-dismiss='alert' href='#'>&times;</a><img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>").delay(1000).fadeOut();
            } else if (data.success == false) {
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
            }
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
}
function showFormUpdateDetail(leafId, url, securityToken, lateInterestDetailId) {
    if ($('#lateInterestDetailPeriod' + lateInterestDetailId).val().length == 0) {
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['lateInterestDetailPeriodLabel'] + "</span>");
        $('#lateInterestDetailPeriod' + lateInterestDetailId + 'HelpMe').empty();
        $('#lateInterestDetailPeriod' + lateInterestDetailId + 'HelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['lateInterestDetailPeriodLabel'] + "</span>");
        $('#lateInterestDetailPeriod' + lateInterestDetailId).addClass('form-group has-error');
        $('#lateInterestDetailPeriod' + lateInterestDetailId).focus();
        return false;
    }
    if ($('#LateInterestDetailAmount' + lateInterestDetailId).val().length == 0) {
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['LateInterestDetailAmountLabel'] + "</span>");
        $('#LateInterestDetailAmount' + lateInterestDetailId + 'HelpMe').empty();
        $('#LateInterestDetailAmount' + lateInterestDetailId + 'HelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['LateInterestDetailAmountLabel'] + "</span>");
        $('#LateInterestDetailAmount' + lateInterestDetailId).addClass('form-group has-error');
        $('#LateInterestDetailAmount' + lateInterestDetailId).focus();
        return false;
    }
    $.ajax({type: 'POST', url: url, data: {method: 'save', output: 'json', lateInterestDetailId: $('#lateInterestDetailId' + lateInterestDetailId).val(), lateInterestId: $('#lateInterestId' + lateInterestDetailId).val(), lateInterestDetailPeriod: $('#lateInterestDetailPeriod' + lateInterestDetailId).val(), LateInterestDetailAmount: $('#LateInterestDetailAmount' + lateInterestDetailId).val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            $('#miniInfoPanel' + lateInterestDetailId).empty();
            $('#miniInfoPanel' + lateInterestDetailId).html('');
            $('#miniInfoPanel' + lateInterestDetailId).html("<span class='label label-warning'> <img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
            if (data.success == true) {
                $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['updateRecordTextLabel']) + "</span>");
                $('#miniInfoPanel' + lateInterestDetailId).html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'><a class='close' data-dismiss='alert' href='#'>&times;</a></span>");
            } else if (data.success == false) {
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                $('#miniInfoPanel' + lateInterestDetailId).html("<span class='label label-danger'>&nbsp; " + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            }
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
}
function showModalDeleteDetail(lateInterestDetailId) {
    $('#lateInterestDetailIdPreview').val('');
    $('#lateInterestDetailIdPreview').val(decodeURIComponent($("#lateInterestDetailId" + lateInterestDetailId).val()));
    $('#lateInterestDetailPeriodPreview').val('');
    $('#lateInterestDetailPeriodPreview').val(decodeURIComponent($("#lateInterestDetailPeriod" + lateInterestDetailId).val()));
    $('#LateInterestDetailAmountPreview').val('');
    $('#LateInterestDetailAmountPreview').val(decodeURIComponent($("#LateInterestDetailAmount" + lateInterestDetailId).val()));
    showMeModal('deleteDetailPreview', 1);
}
function deleteGridRecordDetail(leafId, url, urlList, securityToken) {
    $.ajax({type: 'POST', url: url, data: {method: 'delete', output: 'json', lateInterestDetailId: $('#lateInterestDetailIdPreview').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
            if (data.success == true) {
                showMeModal('deleteDetailPreview', 0);
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['deleteRecordTextLabel']) + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
                removeMeTr($('#lateInterestDetailIdPreview').val())
            } else if (data.success == false) {
                $('#infoPanel').html("<span class='label label-danger'> " + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            }
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
}
function deleteGridRecordCheckbox(leafId, url, urlList, securityToken) {
    var stringText = '';
    var counter = 0;
    $('input:checkbox[name="lateInterestId[]"]').each(function() {
        stringText = stringText + "&lateInterestId[]=" + $(this).val();
    });
    $('input:checkbox[name="isDelete[]"]').each(function() {
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
    $.ajax({type: 'GET', url: url, data: {method: 'updateStatus', output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
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
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
}
function reportRequest(leafId, url, securityToken, mode) {
    $.ajax({type: 'GET', url: url, data: {method: 'report', mode: mode, securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
            if (data.success == true) {
                var path = "./v3/financial/accountReceivable/document/" + data.folder + "/" + data.filename;
                $('#infoPanel').html("<span class='label label-success'>" + decodeURIComponent(t['requestFileTextLabel']) + "</span>");
                window.open(path);
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            }
        }, error: function(xhr) {
            $('#infoError').empty();
            $('#infoError').html('');
            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass();
            $('#infoErrorRowFluid').addClass('row');
        }});
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
            if ($('#lateInterestTypeId').val().length == 0) {
                $('#lateInterestTypeIdHelpMe').empty();
                $('#lateInterestTypeIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestTypeIdLabel'] + " </span>");
                $('#lateInterestTypeId').data('chosen').activate_action();
                return false;
            }
            if ($('#lateInterestCode').val().length == 0) {
                $('#lateInterestCodeHelpMe').empty();
                $('#lateInterestCodeHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestCodeLabel'] + " </span>");
                $('#lateInterestCodeForm').addClass('form-group has-error');
                $('#lateInterestCode').focus();
                return false;
            }
            if ($('#lateInterestDescription').val().length == 0) {
                $('#lateInterestDescriptionHelpMe').empty();
                $('#lateInterestDescriptionHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestDescriptionLabel'] + " </span>");
                $('#lateInterestDescriptionForm').addClass('form-group has-error');
                $('#lateInterestDescription').focus();
                return false;
            }
            if ($('#lateInterestWarningDay').val().length == 0) {
                $('#lateInterestWarningDayHelpMe').empty();
                $('#lateInterestWarningDayHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestWarningDayLabel'] + " </span>");
                $('#lateInterestWarningDayForm').addClass('form-group has-error');
                $('#lateInterestWarningDay').focus();
                return false;
            }
            if ($('#lateInterestGraceDay').val().length == 0) {
                $('#lateInterestGraceDayHelpMe').empty();
                $('#lateInterestGraceDayHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestGraceDayLabel'] + " </span>");
                $('#lateInterestGraceDayForm').addClass('form-group has-error');
                $('#lateInterestGraceDay').focus();
                return false;
            }
            $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', lateInterestTypeId: $('#lateInterestTypeId').val(), lateInterestCode: $('#lateInterestCode').val(), lateInterestDescription: $('#lateInterestDescription').val(), lateInterestWarningDay: $('#lateInterestWarningDay').val(), lateInterestGraceDay: $('#lateInterestGraceDay').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                }, success: function(data) {
                    if (data.success == true) {
                        $('#infoPanel').empty();
                        $('#infoPanel').html('');
                        $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>").delay(1000).fadeOut();
                        if ($('#infoPanel').is(':hidden')) {
                            $('#infoPanel').show();
                        }
                        $('#lateInterestTypeId').val('');
                        $('#lateInterestTypeId').trigger("chosen:updated");
                        $('#lateInterestTypeIdHelpMe').empty();
                        $('#lateInterestTypeIdHelpMe').html('');
                        $('#lateInterestCode').val('');
                        $('#lateInterestCode').val('');
                        $('#lateInterestCodeHelpMe').empty();
                        $('#lateInterestCodeHelpMe').html('');
                        $('#lateInterestDescription').val('');
                        $('#lateInterestDescriptionForm').removeClass().addClass('col-md-12 form-group');
                        $('#lateInterestDescription').val('');
                        $('#lateInterestDescriptionHelpMe').empty();
                        $('#lateInterestDescriptionHelpMe').html('');
                        $('#lateInterestWarningDay').val('');
                        $('#lateInterestWarningDayHelpMe').empty();
                        $('#lateInterestWarningDayHelpMe').html('');
                        $('#lateInterestGraceDay').val('');
                        $('#lateInterestGraceDayHelpMe').empty();
                        $('#lateInterestGraceDayHelpMe').html('');
                    } else if (data.success == false) {
                        $('#infoPanel').empty();
                        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                        if ($('#infoPanel').is(':hidden')) {
                            $('#infoPanel').show();
                        }
                    }
                }, error: function(xhr) {
                    $('#infoError').empty();
                    $('#infoError').html('');
                    $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass();
                    $('#infoErrorRowFluid').addClass('row');
                }});
        } else if (type == 2) {
            if ($('#lateInterestTypeId').val().length == 0) {
                $('#lateInterestTypeIdHelpMe').empty();
                $('#lateInterestTypeIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestTypeIdLabel'] + " </span>");
                $('#lateInterestTypeId').data('chosen').activate_action();
                return false;
            }
            if ($('#lateInterestCode').val().length == 0) {
                $('#lateInterestCodeHelpMe').empty();
                $('#lateInterestCodeHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestCodeLabel'] + " </span>");
                $('#lateInterestCodeForm').addClass('form-group has-error');
                $('#lateInterestCode').focus();
                return false;
            }
            if ($('#lateInterestDescription').val().length == 0) {
                $('#lateInterestDescriptionHelpMe').empty();
                $('#lateInterestDescriptionHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestDescriptionLabel'] + " </span>");
                $('#lateInterestDescriptionForm').addClass('form-group has-error');
                $('#lateInterestDescription').focus();
                return false;
            }
            if ($('#lateInterestWarningDay').val().length == 0) {
                $('#lateInterestWarningDayHelpMe').empty();
                $('#lateInterestWarningDayHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestWarningDayLabel'] + " </span>");
                $('#lateInterestWarningDayForm').addClass('form-group has-error');
                $('#lateInterestWarningDay').focus();
                return false;
            }
            if ($('#lateInterestGraceDay').val().length == 0) {
                $('#lateInterestGraceDayHelpMe').empty();
                $('#lateInterestGraceDayHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestGraceDayLabel'] + " </span>");
                $('#lateInterestGraceDayForm').addClass('form-group has-error');
                $('#lateInterestGraceDay').focus();
                return false;
            }
            $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', lateInterestTypeId: $('#lateInterestTypeId').val(), lateInterestCode: $('#lateInterestCode').val(), lateInterestDescription: $('#lateInterestDescription').val(), lateInterestWarningDay: $('#lateInterestWarningDay').val(), lateInterestGraceDay: $('#lateInterestGraceDay').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                }, success: function(data) {
                    if (data.success == true) {
                        $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>");
                        $('#lateInterestId').val(data.lateInterestId);
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
                        $("#lateInterestId9999").prop("disabled", "false");
                        $("#lateInterestId9999").removeAttr("disabled", "");
                        $("#lateInterestId9999").val('');
                        $("#lateInterestId9999").trigger("chosen:updated");
                        $("#lateInterestDetailPeriod9999").prop("disabled", "false");
                        $("#lateInterestDetailPeriod9999").removeAttr("disabled", "");
                        $("#lateInterestDetailPeriod9999").val('');
                        $("#LateInterestDetailAmount9999").prop("disabled", "false");
                        $("#LateInterestDetailAmount9999").removeAttr("disabled", "");
                        $("#LateInterestDetailAmount9999").val('');

                    }
                }, error: function(xhr) {
                    $('#infoError').empty();
                    $('#infoError').html('');
                    $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass();
                    $('#infoErrorRowFluid').addClass('row');
                }});
        } else if (type == 5) {
            if ($('#lateInterestTypeId').val().length == 0) {
                $('#lateInterestTypeIdHelpMe').empty();
                $('#lateInterestTypeIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestTypeIdLabel'] + " </span>");
                $('#lateInterestTypeId').data('chosen').activate_action();
                return false;
            }
            if ($('#lateInterestCode').val().length == 0) {
                $('#lateInterestCodeHelpMe').empty();
                $('#lateInterestCodeHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestCodeLabel'] + " </span>");
                $('#lateInterestCodeForm').addClass('form-group has-error');
                $('#lateInterestCode').focus();
                return false;
            }
            if ($('#lateInterestDescription').val().length == 0) {
                $('#lateInterestDescriptionHelpMe').empty();
                $('#lateInterestDescriptionHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestDescriptionLabel'] + " </span>");
                $('#lateInterestDescriptionForm').addClass('form-group has-error');
                $('#lateInterestDescription').focus();
                return false;
            }
            if ($('#lateInterestWarningDay').val().length == 0) {
                $('#lateInterestWarningDayHelpMe').empty();
                $('#lateInterestWarningDayHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestWarningDayLabel'] + " </span>");
                $('#lateInterestWarningDayForm').addClass('form-group has-error');
                $('#lateInterestWarningDay').focus();
                return false;
            }
            if ($('#lateInterestGraceDay').val().length == 0) {
                $('#lateInterestGraceDayHelpMe').empty();
                $('#lateInterestGraceDayHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestGraceDayLabel'] + " </span>");
                $('#lateInterestGraceDayForm').addClass('form-group has-error');
                $('#lateInterestGraceDay').focus();
                return false;
            }
            $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', lateInterestTypeId: $('#lateInterestTypeId').val(), lateInterestCode: $('#lateInterestCode').val(), lateInterestDescription: $('#lateInterestDescription').val(), lateInterestWarningDay: $('#lateInterestWarningDay').val(), lateInterestGraceDay: $('#lateInterestGraceDay').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                }, success: function(data) {
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
                }, error: function(xhr) {
                    $('#infoError').empty();
                    $('#infoError').html('');
                    $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass();
                    $('#infoErrorRowFluid').addClass('row');
                }});
        }
        showMeDiv('tableDate', 0);
        showMeDiv('formEntry', 1);
    }
}
function updateRecord(leafId, url, urlList, securityToken, type, deleteAccess) {
    var css = $('#updateRecordButton2').attr('class');
    if (css.search('disabled') > 0) {
    } else {
        $('#infoPanel').empty();
        $('#infoPanel').html('');
        if (type == 1) {
            if ($('#lateInterestTypeId').val().length == 0) {
                $('#lateInterestTypeIdHelpMe').empty();
                $('#lateInterestTypeIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestTypeIdLabel'] + " </span>");
                $('#lateInterestTypeId').data('chosen').activate_action();
                return false;
            }
            if ($('#lateInterestCode').val().length == 0) {
                $('#lateInterestCodeHelpMe').empty();
                $('#lateInterestCodeHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestCodeLabel'] + " </span>");
                $('#lateInterestCodeForm').addClass('form-group has-error');
                $('#lateInterestCode').focus();
                return false;
            }
            if ($('#lateInterestDescription').val().length == 0) {
                $('#lateInterestDescriptionHelpMe').empty();
                $('#lateInterestDescriptionHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestDescriptionLabel'] + " </span>");
                $('#lateInterestDescriptionForm').addClass('form-group has-error');
                $('#lateInterestDescription').focus();
                return false;
            }
            if ($('#lateInterestWarningDay').val().length == 0) {
                $('#lateInterestWarningDayHelpMe').empty();
                $('#lateInterestWarningDayHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestWarningDayLabel'] + " </span>");
                $('#lateInterestWarningDayForm').addClass('form-group has-error');
                $('#lateInterestWarningDay').focus();
                return false;
            }
            if ($('#lateInterestGraceDay').val().length == 0) {
                $('#lateInterestGraceDayHelpMe').empty();
                $('#lateInterestGraceDayHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestGraceDayLabel'] + " </span>");
                $('#lateInterestGraceDayForm').addClass('form-group has-error');
                $('#lateInterestGraceDay').focus();
                return false;
            }
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $.ajax({type: 'POST', url: url, data: {method: 'save', output: 'json', lateInterestId: $('#lateInterestId').val(), lateInterestTypeId: $('#lateInterestTypeId').val(), lateInterestCode: $('#lateInterestCode').val(), lateInterestDescription: $('#lateInterestDescription').val(), lateInterestWarningDay: $('#lateInterestWarningDay').val(), lateInterestGraceDay: $('#lateInterestGraceDay').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                }, success: function(data) {
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
                }, error: function(xhr) {
                    $('#infoError').empty();
                    $('#infoError').html('');
                    $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass();
                    $('#infoErrorRowFluid').addClass('row');
                }});
        } else if (type == 3) {
            if ($('#lateInterestTypeId').val().length == 0) {
                $('#lateInterestTypeIdHelpMe').empty();
                $('#lateInterestTypeIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestTypeIdLabel'] + " </span>");
                $('#lateInterestTypeId').data('chosen').activate_action();
                return false;
            }
            if ($('#lateInterestCode').val().length == 0) {
                $('#lateInterestCodeHelpMe').empty();
                $('#lateInterestCodeHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestCodeLabel'] + " </span>");
                $('#lateInterestCodeForm').addClass('form-group has-error');
                $('#lateInterestCode').focus();
                return false;
            }
            if ($('#lateInterestDescription').val().length == 0) {
                $('#lateInterestDescriptionHelpMe').empty();
                $('#lateInterestDescriptionHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestDescriptionLabel'] + " </span>");
                $('#lateInterestDescriptionForm').addClass('form-group has-error');
                $('#lateInterestDescription').focus();
                return false;
            }
            if ($('#lateInterestWarningDay').val().length == 0) {
                $('#lateInterestWarningDayHelpMe').empty();
                $('#lateInterestWarningDayHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestWarningDayLabel'] + " </span>");
                $('#lateInterestWarningDayForm').addClass('form-group has-error');
                $('#lateInterestWarningDay').focus();
                return false;
            }
            if ($('#lateInterestGraceDay').val().length == 0) {
                $('#lateInterestGraceDayHelpMe').empty();
                $('#lateInterestGraceDayHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['lateInterestGraceDayLabel'] + " </span>");
                $('#lateInterestGraceDayForm').addClass('form-group has-error');
                $('#lateInterestGraceDay').focus();
                return false;
            }
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
            $.ajax({type: 'POST', url: url, data: {method: 'save', output: 'json', lateInterestId: $('#lateInterestId').val(), lateInterestTypeId: $('#lateInterestTypeId').val(), lateInterestCode: $('#lateInterestCode').val(), lateInterestDescription: $('#lateInterestDescription').val(), lateInterestWarningDay: $('#lateInterestWarningDay').val(), lateInterestGraceDay: $('#lateInterestGraceDay').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                }, success: function(data) {
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
                }, error: function(xhr) {
                    $('#infoError').empty();
                    $('#infoError').html('');
                    $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass();
                    $('#infoErrorRowFluid').addClass('row');
                }});
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
                var value = $('#lateInterestId').val();
                if (!value) {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-danger'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "<span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                    return false;
                } else {
                    $.ajax({type: 'POST', url: url, data: {method: 'delete', output: 'json', lateInterestId: $('#lateInterestId').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($('#infoPanel').is(':hidden')) {
                                $('#infoPanel').show();
                            }
                        }, success: function(data) {
                            if (data.success == true) {
                                showGrid(leafId, urlList, securityToken, 0, 10, 2);
                            } else if (data.success == false) {
                                $('#infoPanel').empty();
                                $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                                if ($('#infoPanel').is(':hidden')) {
                                    $('#infoPanel').show();
                                }
                            }
                        }, error: function(xhr) {
                            $('#infoError').empty();
                            $('#infoError').html('');
                            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass();
                            $('#infoErrorRowFluid').addClass('row');
                        }});
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
    $('#firstRecordButton').attr('onClick', "firstRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urllateInterestDetail + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
    $('#previousRecordButton').removeClass();
    $('#previousRecordButton').addClass('btn btn-default disabled');
    $('#previousRecordButton').attr('onClick', '');
    $('#nextRecordButton').removeClass();
    $('#nextRecordButton').addClass('btn btn-default disabled');
    $('#nextRecordButton').attr('onClick', '');
    $('#endRecordButton').removeClass();
    $('#endRecordButton').addClass('btn btn-default');
    $('#endRecordButton').attr('onClick', "endRecord\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urllateInterestDetail + "\",,\"" + securityToken + "\",\"" + updateAccess + "\")");
    $("#lateInterestId").val('');

    $("#lateInterestTypeId").val('');
    $('#lateInterestTypeId').trigger("chosen:updated");
    $("#lateInterestCode").val('');
    $("#lateInterestDescription").val('');
    $('#lateInterestDescription').empty();
    $('#lateInterestDescription').val('');
    $("#lateInterestWarningDay").val('');
    $("#lateInterestGraceDay").val('');
    $("#lateInterestDetailId9999").prop("disabled", "true");
    $("#lateInterestDetailId9999").attr("disabled", "disabled");
    $("#lateInterestDetailId9999").val('');
    $("#lateInterestId9999").prop("disabled", "true");
    $("#lateInterestId9999").attr("disabled", "disabled");
    $("#lateInterestId9999").val('');
    $("#lateInterestId9999").trigger("chosen:updated");
    $("#lateInterestDetailPeriod9999").prop("disabled", "true");
    $("#lateInterestDetailPeriod9999").attr("disabled", "disabled");
    $("#lateInterestDetailPeriod9999").val('');
    $("#LateInterestDetailAmount9999").prop("disabled", "true");
    $("#LateInterestDetailAmount9999").attr("disabled", "disabled");
    $("#LateInterestDetailAmount9999").val('');

    $("#tableBody").empty();
    $("#tableBody").html('');
}
function postRecord(leafId, url, urlList, urllateInterestDetail, SecurityToken) {
    var css = $('#postRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        return false;
    }
}
function firstRecord(leafId, url, urlList, urllateInterestDetail, securityToken, updateAccess, deleteAccess) {
    var css = $('#firstRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $.ajax({type: 'GET', url: url, data: {method: 'dataNavigationRequest', dataNavigation: 'firstRecord', output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                $('#infoPanel').empty();
                $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            }, success: function(data) {
                var smileyRoll = './images/icons/smiley-roll.png';
                if (firstRecord === 0) {
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['recordNotFoundLabel']) + "</span>");
                    return false;
                }
                if (data.success == true) {
                    $.ajax({type: 'POST', url: url, data: {method: 'read', lateInterestId: data.firstRecord, output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($('#infoPanel').is(':hidden')) {
                                $('#infoPanel').show();
                            }
                        }, success: function(data) {
                            if (data.success == true) {
                                $('#lateInterestId').val(data.data.lateInterestId);
                                $('#lateInterestTypeId').val(data.data.lateInterestTypeId);
                                $('#lateInterestTypeId').trigger("chosen:updated");
                                $('#lateInterestCode').val(data.data.lateInterestCode);
                                $('#lateInterestDescription').val(data.data.lateInterestDescription);
                                $('#lateInterestWarningDay').val(data.data.lateInterestWarningDay);
                                $('#lateInterestGraceDay').val(data.data.lateInterestGraceDay);
                                $("#lateInterestId9999").prop("disabled", "false");
                                $("#lateInterestId9999").removeAttr("disabled", "");
                                $("#lateInterestId9999").val('');
                                $("#lateInterestId9999").trigger("chosen:updated");
                                $("#lateInterestDetailPeriod9999").prop("disabled", "false");
                                $("#lateInterestDetailPeriod9999").removeAttr("disabled", "");
                                $("#lateInterestDetailPeriod9999").val('');
                                $("#LateInterestDetailAmount9999").prop("disabled", "false");
                                $("#LateInterestDetailAmount9999").removeAttr("disabled", "");
                                $("#LateInterestDetailAmount9999").val('');
                                $.ajax({type: 'POST', url: urllateInterestDetail, data: {method: 'read', lateInterestId: data.firstRecord, output: 'table', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                                        $('#infoPanel').empty();
                                        $('#infoPanel').html('');
                                        $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                                        if ($('#infoPanel').is(':hidden')) {
                                            $('#infoPanel').show();
                                        }
                                    }, success: function(data) {
                                        if (data.success == true) {
                                            $('#infoPanel').empty();
                                            $('#infoPanel').html('');
                                            $('#tableBody').empty();
                                            $('#tableBody').html('');
                                            $('#tableBody').html(data.tableData);
                                        }
                                    }, error: function(xhr) {
                                        $('#infoError').empty();
                                        $('#infoError').html('');
                                        $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                        $('#infoErrorRowFluid').removeClass();
                                        $('#infoErrorRowFluid').addClass('row');
                                    }});
                                if (data.nextRecord > 0) {
                                    $('#previousRecordButton').removeClass();
                                    $('#previousRecordButton').addClass('btn btn-default  disabled');
                                    $('#previousRecordButton').attr('onClick', '');
                                    $('#nextRecordButton').removeClass();
                                    $('#nextRecordButton').addClass('btn btn-default');
                                    $('#nextRecordButton').attr('onClick', '');
                                    $('#nextRecordButton').attr('onClick', "nextRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urllateInterestDetail + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
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
                        }, error: function(xhr) {
                            $('#infoError').empty();
                            $('#infoError').html('');
                            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass();
                            $('#infoErrorRowFluid').addClass('row');
                        }});
                } else {
                    $('#infoPanel').empty();
                    $('#infoPanel').html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll-sweat.png'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                }
            }, error: function(xhr) {
                $('#infoError').empty();
                $('#infoError').html('');
                $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid').removeClass();
                $('#infoErrorRowFluid').addClass('row');
            }});
    }
}
function endRecord(leafId, url, urlList, urllateInterestDetail, securityToken, updateAccess, deleteAccess) {
    var css = $('#endRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $.ajax({type: 'GET', url: url, data: {method: 'dataNavigationRequest', dataNavigation: 'lastRecord', output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                if ($('#infoPanel').is(':hidden')) {
                    $('#infoPanel').show();
                }
            }, success: function(data) {
                if (data.lastRecord === 0) {
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['recordNotFoundLabel']) + "</span>");
                    return false;
                }
                if (data.success == true) {
                    $.ajax({type: 'POST', url: url, data: {method: 'read', lateInterestId: data.lastRecord, output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($('#infoPanel').is(':hidden')) {
                                $('#infoPanel').show();
                            }
                        }, success: function(data) {
                            if (data.success == true) {
                                $('#lateInterestId').val(data.data.lateInterestId);
                                $('#lateInterestTypeId').val(data.data.lateInterestTypeId);
                                $('#lateInterestTypeId').trigger("chosen:updated");
                                $('#lateInterestCode').val(data.data.lateInterestCode);
                                $('#lateInterestDescription').val(data.data.lateInterestDescription);
                                $('#lateInterestWarningDay').val(data.data.lateInterestWarningDay);
                                $('#lateInterestGraceDay').val(data.data.lateInterestGraceDay);
                                $("#lateInterestId9999").prop("disabled", "false");
                                $("#lateInterestId9999").removeAttr("disabled", "");
                                $("#lateInterestId9999").val('');
                                $("#lateInterestId9999").trigger("chosen:updated");
                                $("#lateInterestDetailPeriod9999").prop("disabled", "false");
                                $("#lateInterestDetailPeriod9999").removeAttr("disabled", "");
                                $("#lateInterestDetailPeriod9999").val('');
                                $("#LateInterestDetailAmount9999").prop("disabled", "false");
                                $("#LateInterestDetailAmount9999").removeAttr("disabled", "");
                                $("#LateInterestDetailAmount9999").val('');
                                $.ajax({type: 'POST', url: urllateInterestDetail, data: {method: 'read', lateInterestId: data.lastRecord, output: 'table', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                                        $('#infoPanel').empty();
                                        $('#infoPanel').html('');
                                        $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                                        if ($('#infoPanel').is(':hidden')) {
                                            $('#infoPanel').show();
                                        }
                                    }, success: function(data) {
                                        if (data.success == true) {
                                            $('#infoPanel').empty();
                                            $('#infoPanel').html('');
                                            $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                            $('#tableBody').empty();
                                            $('#tableBody').html('');
                                            $('#tableBody').html(data.tableData);
                                        }
                                    }, error: function(xhr) {
                                        $('#infoError').empty();
                                        $('#infoError').html('');
                                        $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                        $('#infoErrorRowFluid').removeClass();
                                        $('#infoErrorRowFluid').addClass('row');
                                    }});
                                if (data.lastRecord != 0) {
                                    $('#previousRecordButton').removeClass();
                                    $('#previousRecordButton').addClass('btn btn-default');
                                    $('#previousRecordButton').attr('onClick', "previousRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urllateInterestDetail + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
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
                        }, error: function(xhr) {
                            $('#infoError').empty();
                            $('#infoError').html('');
                            $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass();
                            $('#infoErrorRowFluid').addClass('row');
                        }});
                } else {
                    $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                }
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $('#infoPanel').html("&nbsp;<img src='./images/icons/control-stop-180.png'> " + decodeURIComponent(t['endButtonLabel']) + " ");
            }, error: function(xhr) {
                $('#infoError').empty();
                $('#infoError').html('');
                $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid').removeClass();
                $('#infoErrorRowFluid').addClass('row');
            }});
    }
}
function previousRecord(leafId, url, urlList, urllateInterestDetail, securityToken, updateAccess, deleteAccess) {
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
            $.ajax({type: 'POST', url: url, data: {method: 'read', lateInterestId: $('#previousRecordCounter').val(), output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                }, success: function(data) {
                    if (data.success == true) {
                        $('#lateInterestId').val(data.data.lateInterestId);
                        $('#lateInterestTypeId').val(data.data.lateInterestTypeId);
                        $('#lateInterestTypeId').trigger("chosen:updated");
                        $('#lateInterestCode').val(data.data.lateInterestCode);
                        $('#lateInterestDescription').val(data.data.lateInterestDescription);
                        $('#lateInterestWarningDay').val(data.data.lateInterestWarningDay);
                        $('#lateInterestGraceDay').val(data.data.lateInterestGraceDay);
                        $("#lateInterestId9999").prop("disabled", "false");
                        $("#lateInterestId9999").removeAttr("disabled", "");
                        $("#lateInterestId9999").val('');
                        $("#lateInterestId9999").trigger("chosen:updated");
                        $("#lateInterestDetailPeriod9999").prop("disabled", "false");
                        $("#lateInterestDetailPeriod9999").removeAttr("disabled", "");
                        $("#lateInterestDetailPeriod9999").val('');
                        $("#LateInterestDetailAmount9999").prop("disabled", "false");
                        $("#LateInterestDetailAmount9999").removeAttr("disabled", "");
                        $("#LateInterestDetailAmount9999").val('');
                        $.ajax({type: 'POST', url: urllateInterestDetail, data: {method: 'read', lateInterestId: $('#previousRecordCounter').val(), output: 'table', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                                $('#infoPanel').empty();
                                $('#infoPanel').html('');
                                $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                                if ($('#infoPanel').is(':hidden')) {
                                    $('#infoPanel').show();
                                }
                            }, success: function(data) {
                                if (data.success == true) {
                                    $('#infoPanel').empty();
                                    $('#infoPanel').html('');
                                    $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                    $('#tableBody').empty();
                                    $('#tableBody').html('');
                                    $('#tableBody').html(data.tableData);
                                }
                            }, error: function(xhr) {
                                $('#infoError').empty();
                                $('#infoError').html('');
                                $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                $('#infoErrorRowFluid').removeClass();
                                $('#infoErrorRowFluid').addClass('row');
                            }});
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
                            $('#nextRecordButton').attr('onClick', "nextRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urllateInterestDetail + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
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
                }, error: function(xhr) {
                    $('#infoError').empty();
                    $('#infoError').html('');
                    $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass();
                    $('#infoErrorRowFluid').addClass('row');
                }});
        } else {
        }
    }
}
function nextRecord(leafId, url, urlList, urllateInterestDetail, securityToken, updateAccess, deleteAccess) {
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
            $.ajax({type: 'POST', url: url, data: {method: 'read', lateInterestId: $('#nextRecordCounter').val(), output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                }, success: function(data) {
                    if (data.success == true) {
                        $('#lateInterestId').val(data.data.lateInterestId);
                        $('#lateInterestTypeId').val(data.data.lateInterestTypeId);
                        $('#lateInterestTypeId').trigger("chosen:updated");
                        $('#lateInterestCode').val(data.data.lateInterestCode);
                        $('#lateInterestDescription').val(data.data.lateInterestDescription);
                        $('#lateInterestWarningDay').val(data.data.lateInterestWarningDay);
                        $('#lateInterestGraceDay').val(data.data.lateInterestGraceDay);
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
                        $("#lateInterestId9999").prop("disabled", "false");
                        $("#lateInterestId9999").removeAttr("disabled", "");
                        $("#lateInterestId9999").val('');
                        $("#lateInterestId9999").trigger("chosen:updated");
                        $("#lateInterestDetailPeriod9999").prop("disabled", "false");
                        $("#lateInterestDetailPeriod9999").removeAttr("disabled", "");
                        $("#lateInterestDetailPeriod9999").val('');
                        $("#LateInterestDetailAmount9999").prop("disabled", "false");
                        $("#LateInterestDetailAmount9999").removeAttr("disabled", "");
                        $("#LateInterestDetailAmount9999").val('');

                        $.ajax({type: 'POST', url: urllateInterestDetail, data: {method: 'read', lateInterestId: $('#nextRecordCounter').val(), output: 'table', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                                $('#infoPanel').empty();
                                $('#infoPanel').html('');
                                $('#infoPanel').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                                if ($('#infoPanel').is(':hidden')) {
                                    $('#infoPanel').show();
                                }
                            }, success: function(data) {
                                if (data.success == true) {
                                    $('#tableBody').empty();
                                    $('#tableBody').html('');
                                    $('#tableBody').html(data.tableData);
                                    $('#infoPanel').empty();
                                    $('#infoPanel').html('');
                                    $('#infoPanel').html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                                    if ($('#infoPanel').is(':hidden')) {
                                        $('#infoPanel').show();
                                    }
                                }
                            }, error: function(xhr) {
                                $('#infoError').empty();
                                $('#infoError').html('');
                                $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                                $('#infoErrorRowFluid').removeClass();
                                $('#infoErrorRowFluid').addClass('row');
                            }});
                        $('#firstRecordCounter').val(data.firstRecord);
                        $('#previousRecordCounter').val(data.previousRecord);
                        $('#nextRecordCounter').val(data.nextRecord);
                        $('#lastRecordCounter').val(data.lastRecord);
                        if (parseFloat(data.previousRecord) > 0) {
                            $('#previousRecordButton').removeClass();
                            $('#previousRecordButton').addClass('btn btn-default');
                            $('#previousRecordButton').attr('onClick', "previousRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urllateInterestDetail + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
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
                }, error: function(xhr) {
                    $('#infoError').empty();
                    $('#infoError').html('');
                    $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass();
                    $('#infoErrorRowFluid').addClass('row');
                }});
        } else {
        }
    }
}