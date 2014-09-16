function getInvoiceRecurringType(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'invoiceRecurringType'}, beforeSend: function() {
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
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'>" + data.message + "</span>");
            } else {
                $('#infoPanel').empty();
                $('#infoPanel').html('');
                $("#invoiceRecurringTypeId").empty();
                $("#invoiceRecurringTypeId").html('');
                $("#invoiceRecurringTypeId").html(data.data);
                $("#invoiceRecurringTypeId").trigger("chosen:updated");
                $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</spanm>").delay(5000).fadeOut();
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
    if ($("#invoiceRecurringCode").val().length == 0) {
        alert(t['oddTextLabel']);
        return false;
    }
    $.ajax({type: 'GET', url: page, data: {invoiceRecurringCode: $("#invoiceRecurringCode").val(), method: 'duplicate', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                    $("#invoiceRecurringCode").empty();
                    $("#invoiceRecurringCode").val('');
                    $("#invoiceRecurringCode").focus();
                    $("#invoiceRecurringCodeForm").removeClass();
                    $("#invoiceRecurringCodeForm").addClass("col-md-12 form-group has-error");
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
                $("#invoiceRecurringForm").removeClass();
                $("#invoiceRecurringForm").addClass("col-md-12 form-group has-error");
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
function showFormUpdate(leafId, url, urlList, securityToken, invoiceRecurringId, updateAccess, deleteAccess) {
    sleep(500);
    $.ajax({type: 'POST', url: urlList, data: {method: 'read', type: 'form', invoiceRecurringId: invoiceRecurringId, securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
function showModalDelete(invoiceRecurringId, invoiceRecurringTypeId, documentNumber, referenceNumber, journalRecurringTitle, invoiceRecurringDescription, invoiceRecurringDate, invoiceRecurringStartDate, invoiceRecurringEndDate, invoiceRecurringAmount) {
    $('#invoiceRecurringIdPreview').val('');
    $('#invoiceRecurringIdPreview').val(decodeURIComponent(invoiceRecurringId));
    $('#invoiceRecurringTypeIdPreview').val('');
    $('#invoiceRecurringTypeIdPreview').val(decodeURIComponent(invoiceRecurringTypeId));
    $('#documentNumberPreview').val('');
    $('#documentNumberPreview').val(decodeURIComponent(documentNumber));
    $('#referenceNumberPreview').val('');
    $('#referenceNumberPreview').val(decodeURIComponent(referenceNumber));
    $('#journalRecurringTitlePreview').val('');
    $('#journalRecurringTitlePreview').val(decodeURIComponent(journalRecurringTitle));
    $('#invoiceRecurringDescriptionPreview').val('');
    $('#invoiceRecurringDescriptionPreview').val(decodeURIComponent(invoiceRecurringDescription));
    $('#invoiceRecurringDatePreview').val('');
    $('#invoiceRecurringDatePreview').val(decodeURIComponent(invoiceRecurringDate));
    $('#invoiceRecurringStartDatePreview').val('');
    $('#invoiceRecurringStartDatePreview').val(decodeURIComponent(invoiceRecurringStartDate));
    $('#invoiceRecurringEndDatePreview').val('');
    $('#invoiceRecurringEndDatePreview').val(decodeURIComponent(invoiceRecurringEndDate));
    $('#invoiceRecurringAmountPreview').val('');
    $('#invoiceRecurringAmountPreview').val(decodeURIComponent(invoiceRecurringAmount));
    showMeModal('deletePreview', 1);
}
function deleteGridRecord(leafId, url, urlList, securityToken) {
    $.ajax({type: 'POST', url: url, data: {method: 'delete', output: 'json', invoiceRecurringId: $('#invoiceRecurringIdPreview').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
    if ($('#chartOfAccountId9999').val().length == 0) {
        $('#infoPanel').empty();
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountIdLabel'] + "</span>");
        $('#chartOfAccountId9999HelpMe').empty();
        $('#chartOfAccountId9999HelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['chartOfAccountIdLabel'] + "</span>");
        $('#chartOfAccountId9999').addClass('form-group has-error');
        $('#chartOfAccountId9999').focus();
        return false;
    }
    if ($('#countryId9999').val().length == 0) {
        $('#infoPanel').empty();
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['countryIdLabel'] + "</span>");
        $('#countryId9999HelpMe').empty();
        $('#countryId9999HelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['countryIdLabel'] + "</span>");
        $('#countryId9999').addClass('form-group has-error');
        $('#countryId9999').focus();
        return false;
    }
    if ($('#transactionTypeId9999').val().length == 0) {
        $('#infoPanel').empty();
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['transactionTypeIdLabel'] + "</span>");
        $('#transactionTypeId9999HelpMe').empty();
        $('#transactionTypeId9999HelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['transactionTypeIdLabel'] + "</span>");
        $('#transactionTypeId9999').addClass('form-group has-error');
        $('#transactionTypeId9999').focus();
        return false;
    }
    if ($('#documentNumber9999').val().length == 0) {
        $('#infoPanel').empty();
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['documentNumberLabel'] + "</span>");
        $('#documentNumber9999HelpMe').empty();
        $('#documentNumber9999HelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['documentNumberLabel'] + "</span>");
        $('#documentNumber9999').addClass('form-group has-error');
        $('#documentNumber9999').focus();
        return false;
    }
    if ($('#invoiceRecurringDetailAmount9999').val().length == 0) {
        $('#infoPanel').empty();
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringDetailAmountLabel'] + "</span>");
        $('#invoiceRecurringDetailAmount9999HelpMe').empty();
        $('#invoiceRecurringDetailAmount9999HelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringDetailAmountLabel'] + "</span>");
        $('#invoiceRecurringDetailAmount9999').addClass('form-group has-error');
        $('#invoiceRecurringDetailAmount9999').focus();
        return false;
    }
    $('#infoPanel').empty();
    $('#infoPanel').html('');
    $('#infoPanel').html("<span class='label label-success'>&nbsp;" + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>");
    if ($('#infoPanel').is(':hidden')) {
        $('#infoPanel').show();
    }
    $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', invoiceRecurringId: $('#invoiceRecurringId').val(), chartOfAccountId: $('#chartOfAccountId9999').val(), countryId: $('#countryId9999').val(), transactionTypeId: $('#transactionTypeId9999').val(), documentNumber: $('#documentNumber9999').val(), invoiceRecurringDetailAmount: $('#invoiceRecurringDetailAmount9999').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                $.ajax({type: 'POST', url: url, data: {method: 'read', output: 'table', offset: '0', limit: '9999', invoiceRecurringId: $('#invoiceRecurringId').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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

                            $("#invoiceRecurringId9999").prop("disabled", "false");
                            $("#invoiceRecurringId9999").removeAttr("disabled", "");
                            $("#invoiceRecurringId9999").val('');
                            $("#invoiceRecurringId9999").trigger("chosen:updated");
                            $("#chartOfAccountId9999").prop("disabled", "false");
                            $("#chartOfAccountId9999").removeAttr("disabled", "");
                            $("#chartOfAccountId9999").val('');
                            $("#chartOfAccountId9999").trigger("chosen:updated");
                            $("#countryId9999").prop("disabled", "false");
                            $("#countryId9999").removeAttr("disabled", "");
                            $("#countryId9999").val('');
                            $("#countryId9999").trigger("chosen:updated");
                            $("#transactionTypeId9999").prop("disabled", "false");
                            $("#transactionTypeId9999").removeAttr("disabled", "");
                            $("#transactionTypeId9999").val('');
                            $("#transactionTypeId9999").trigger("chosen:updated");
                            $("#documentNumber9999").prop("disabled", "false");
                            $("#documentNumber9999").removeAttr("disabled", "");
                            $("#documentNumber9999").val('');
                            $("#journalNumber9999").prop("disabled", "false");
                            $("#journalNumber9999").removeAttr("disabled", "");
                            $("#journalNumber9999").val('');
                            $("#invoiceRecurringDetailAmount9999").prop("disabled", "false");
                            $("#invoiceRecurringDetailAmount9999").removeAttr("disabled", "");
                            $("#invoiceRecurringDetailAmount9999").val('');

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
function showFormUpdateDetail(leafId, url, securityToken, invoiceRecurringDetailId) {
    if ($('#chartOfAccountId' + invoiceRecurringDetailId).val().length == 0) {
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['chartOfAccountIdLabel'] + "</span>");
        $('#chartOfAccountId' + invoiceRecurringDetailId + 'HelpMe').empty();
        $('#chartOfAccountId' + invoiceRecurringDetailId + 'HelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['chartOfAccountIdLabel'] + "</span>");
        $('#chartOfAccountId' + invoiceRecurringDetailId).addClass('form-group has-error');
        $('#chartOfAccountId' + invoiceRecurringDetailId).focus();
        return false;
    }
    if ($('#countryId' + invoiceRecurringDetailId).val().length == 0) {
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['countryIdLabel'] + "</span>");
        $('#countryId' + invoiceRecurringDetailId + 'HelpMe').empty();
        $('#countryId' + invoiceRecurringDetailId + 'HelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['countryIdLabel'] + "</span>");
        $('#countryId' + invoiceRecurringDetailId).addClass('form-group has-error');
        $('#countryId' + invoiceRecurringDetailId).focus();
        return false;
    }
    if ($('#transactionTypeId' + invoiceRecurringDetailId).val().length == 0) {
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['transactionTypeIdLabel'] + "</span>");
        $('#transactionTypeId' + invoiceRecurringDetailId + 'HelpMe').empty();
        $('#transactionTypeId' + invoiceRecurringDetailId + 'HelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['transactionTypeIdLabel'] + "</span>");
        $('#transactionTypeId' + invoiceRecurringDetailId).addClass('form-group has-error');
        $('#transactionTypeId' + invoiceRecurringDetailId).focus();
        return false;
    }
    if ($('#documentNumber' + invoiceRecurringDetailId).val().length == 0) {
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['documentNumberLabel'] + "</span>");
        $('#documentNumber' + invoiceRecurringDetailId + 'HelpMe').empty();
        $('#documentNumber' + invoiceRecurringDetailId + 'HelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['documentNumberLabel'] + "</span>");
        $('#documentNumber' + invoiceRecurringDetailId).addClass('form-group has-error');
        $('#documentNumber' + invoiceRecurringDetailId).focus();
        return false;
    }
    if ($('#journalNumber' + invoiceRecurringDetailId).val().length == 0) {
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['journalNumberLabel'] + "</span>");
        $('#journalNumber' + invoiceRecurringDetailId + 'HelpMe').empty();
        $('#journalNumber' + invoiceRecurringDetailId + 'HelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['journalNumberLabel'] + "</span>");
        $('#journalNumber' + invoiceRecurringDetailId).addClass('form-group has-error');
        $('#journalNumber' + invoiceRecurringDetailId).focus();
        return false;
    }
    if ($('#invoiceRecurringDetailAmount' + invoiceRecurringDetailId).val().length == 0) {
        $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['invoiceRecurringDetailAmountLabel'] + "</span>");
        $('#invoiceRecurringDetailAmount' + invoiceRecurringDetailId + 'HelpMe').empty();
        $('#invoiceRecurringDetailAmount' + invoiceRecurringDetailId + 'HelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + ' :' + leafTranslation['invoiceRecurringDetailAmountLabel'] + "</span>");
        $('#invoiceRecurringDetailAmount' + invoiceRecurringDetailId).addClass('form-group has-error');
        $('#invoiceRecurringDetailAmount' + invoiceRecurringDetailId).focus();
        return false;
    }
    $.ajax({type: 'POST', url: url, data: {method: 'save', output: 'json', invoiceRecurringDetailId: $('#invoiceRecurringDetailId' + invoiceRecurringDetailId).val(), invoiceRecurringId: $('#invoiceRecurringId').val(), chartOfAccountId: $('#chartOfAccountId' + invoiceRecurringDetailId).val(), countryId: $('#countryId' + invoiceRecurringDetailId).val(), transactionTypeId: $('#transactionTypeId' + invoiceRecurringDetailId).val(), documentNumber: $('#documentNumber' + invoiceRecurringDetailId).val(), journalNumber: $('#journalNumber' + invoiceRecurringDetailId).val(), invoiceRecurringDetailAmount: $('#invoiceRecurringDetailAmount' + invoiceRecurringDetailId).val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            $('#miniInfoPanel' + invoiceRecurringDetailId).empty();
            $('#miniInfoPanel' + invoiceRecurringDetailId).html('');
            $('#miniInfoPanel' + invoiceRecurringDetailId).html("<span class='label label-warning'> <img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
        }, success: function(data) {
            if (data.success == true) {
                $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['updateRecordTextLabel']) + "</span>");
                $('#miniInfoPanel' + invoiceRecurringDetailId).html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'><a class='close' data-dismiss='alert' href='#'>&times;</a></span>");
            } else if (data.success == false) {
                $('#infoPanel').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
                $('#miniInfoPanel' + invoiceRecurringDetailId).html("<span class='label label-danger'>&nbsp; " + data.message + "</span>");
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
function showModalDeleteDetail(invoiceRecurringDetailId) {
    $('#invoiceRecurringDetailIdPreview').val('');
    $('#invoiceRecurringDetailIdPreview').val(decodeURIComponent($("#invoiceRecurringDetailId" + invoiceRecurringDetailId).val()));
    $('#chartOfAccountIdPreview').val('');
    $('#chartOfAccountIdPreview').val(decodeURIComponent($("#chartOfAccountId" + invoiceRecurringDetailId + " option:selected").text()));
    $('#countryIdPreview').val('');
    $('#countryIdPreview').val(decodeURIComponent($("#countryId" + invoiceRecurringDetailId + " option:selected").text()));
    $('#transactionTypeIdPreview').val('');
    $('#transactionTypeIdPreview').val(decodeURIComponent($("#transactionTypeId" + invoiceRecurringDetailId + " option:selected").text()));
    $('#documentNumberPreview').val('');
    $('#documentNumberPreview').val(decodeURIComponent($("#documentNumber" + invoiceRecurringDetailId).val()));
    $('#journalNumberPreview').val('');
    $('#journalNumberPreview').val(decodeURIComponent($("#journalNumber" + invoiceRecurringDetailId).val()));
    $('#invoiceRecurringDetailAmountPreview').val('');
    $('#invoiceRecurringDetailAmountPreview').val(decodeURIComponent($("#invoiceRecurringDetailAmount" + invoiceRecurringDetailId).val()));
    showMeModal('deleteDetailPreview', 1);
}
function deleteGridRecordDetail(leafId, url, urlList, securityToken) {
    $.ajax({type: 'POST', url: url, data: {method: 'delete', output: 'json', invoiceRecurringDetailId: $('#invoiceRecurringDetailIdPreview').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                removeMeTr($('#invoiceRecurringDetailIdPreview').val())
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
    $('input:checkbox[name="invoiceRecurringId[]"]').each(function() {
        stringText = stringText + "&invoiceRecurringId[]=" + $(this).val();
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
            if ($('#invoiceRecurringTypeId').val().length == 0) {
                $('#invoiceRecurringTypeIdHelpMe').empty();
                $('#invoiceRecurringTypeIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringTypeIdLabel'] + " </span>");
                $('#invoiceRecurringTypeId').data('chosen').activate_action();
                return false;
            }
            if ($('#referenceNumber').val().length == 0) {
                $('#referenceNumberHelpMe').empty();
                $('#referenceNumberHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['referenceNumberLabel'] + " </span>");
                $('#referenceNumberForm').addClass('form-group has-error');
                $('#referenceNumber').focus();
                return false;
            }
            if ($('#journalRecurringTitle').val().length == 0) {
                $('#journalRecurringTitleHelpMe').empty();
                $('#journalRecurringTitleHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['journalRecurringTitleLabel'] + " </span>");
                $('#journalRecurringTitleForm').addClass('form-group has-error');
                $('#journalRecurringTitle').focus();
                return false;
            }
            if ($('#invoiceRecurringDescription').val().length == 0) {
                $('#invoiceRecurringDescriptionHelpMe').empty();
                $('#invoiceRecurringDescriptionHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringDescriptionLabel'] + " </span>");
                $('#invoiceRecurringDescriptionForm').addClass('form-group has-error');
                $('#invoiceRecurringDescription').focus();
                return false;
            }
            if ($('#invoiceRecurringDate').val().length == 0) {
                $('#invoiceRecurringDateHelpMe').empty();
                $('#invoiceRecurringDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringDateLabel'] + " </span>");
                $('#invoiceRecurringDateForm').addClass('form-group has-error');
                $('#invoiceRecurringDate').focus();
                return false;
            }
            if ($('#invoiceRecurringStartDate').val().length == 0) {
                $('#invoiceRecurringStartDateHelpMe').empty();
                $('#invoiceRecurringStartDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringStartDateLabel'] + " </span>");
                $('#invoiceRecurringStartDateForm').addClass('form-group has-error');
                $('#invoiceRecurringStartDate').focus();
                return false;
            }
            if ($('#invoiceRecurringEndDate').val().length == 0) {
                $('#invoiceRecurringEndDateHelpMe').empty();
                $('#invoiceRecurringEndDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringEndDateLabel'] + " </span>");
                $('#invoiceRecurringEndDateForm').addClass('form-group has-error');
                $('#invoiceRecurringEndDate').focus();
                return false;
            }
            if ($('#invoiceRecurringAmount').val().length == 0) {
                $('#invoiceRecurringAmountHelpMe').empty();
                $('#invoiceRecurringAmountHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringAmountLabel'] + " </span>");
                $('#invoiceRecurringAmountForm').addClass('form-group has-error');
                $('#invoiceRecurringAmount').focus();
                return false;
            }
            $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', invoiceRecurringTypeId: $('#invoiceRecurringTypeId').val(), referenceNumber: $('#referenceNumber').val(), journalRecurringTitle: $('#journalRecurringTitle').val(), invoiceRecurringDescription: $('#invoiceRecurringDescription').val(), invoiceRecurringDate: $('#invoiceRecurringDate').val(), invoiceRecurringStartDate: $('#invoiceRecurringStartDate').val(), invoiceRecurringEndDate: $('#invoiceRecurringEndDate').val(), invoiceRecurringAmount: $('#invoiceRecurringAmount').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                        $('#invoiceRecurringTypeId').val('');
                        $('#invoiceRecurringTypeId').trigger("chosen:updated");
                        $('#invoiceRecurringTypeIdHelpMe').empty();
                        $('#invoiceRecurringTypeIdHelpMe').html('');
                        $('#documentNumber').val('');
                        $('#documentNumber').val('');
                        $('#documentNumberHelpMe').empty();
                        $('#documentNumberHelpMe').html('');
                        $('#referenceNumber').val('');
                        $('#referenceNumber').val('');
                        $('#referenceNumberHelpMe').empty();
                        $('#referenceNumberHelpMe').html('');
                        $('#journalRecurringTitle').val('');
                        $('#journalRecurringTitle').val('');
                        $('#journalRecurringTitleHelpMe').empty();
                        $('#journalRecurringTitleHelpMe').html('');
                        $('#invoiceRecurringDescription').val('');
                        $('#invoiceRecurringDescriptionForm').removeClass().addClass('col-md-12 form-group');
                        $('#invoiceRecurringDescription').val('');
                        $('#invoiceRecurringDescriptionHelpMe').empty();
                        $('#invoiceRecurringDescriptionHelpMe').html('');
                        $('#invoiceRecurringDate').val('');
                        $('#invoiceRecurringDateHelpMe').empty();
                        $('#invoiceRecurringDateHelpMe').html('');
                        $('#invoiceRecurringStartDate').val('');
                        $('#invoiceRecurringStartDateHelpMe').empty();
                        $('#invoiceRecurringStartDateHelpMe').html('');
                        $('#invoiceRecurringEndDate').val('');
                        $('#invoiceRecurringEndDateHelpMe').empty();
                        $('#invoiceRecurringEndDateHelpMe').html('');
                        $('#invoiceRecurringAmount').val('');
                        $('#invoiceRecurringAmountHelpMe').empty();
                        $('#invoiceRecurringAmountHelpMe').html('');
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
            if ($('#invoiceRecurringTypeId').val().length == 0) {
                $('#invoiceRecurringTypeIdHelpMe').empty();
                $('#invoiceRecurringTypeIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringTypeIdLabel'] + " </span>");
                $('#invoiceRecurringTypeId').data('chosen').activate_action();
                return false;
            }
            if ($('#referenceNumber').val().length == 0) {
                $('#referenceNumberHelpMe').empty();
                $('#referenceNumberHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['referenceNumberLabel'] + " </span>");
                $('#referenceNumberForm').addClass('form-group has-error');
                $('#referenceNumber').focus();
                return false;
            }
            if ($('#journalRecurringTitle').val().length == 0) {
                $('#journalRecurringTitleHelpMe').empty();
                $('#journalRecurringTitleHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['journalRecurringTitleLabel'] + " </span>");
                $('#journalRecurringTitleForm').addClass('form-group has-error');
                $('#journalRecurringTitle').focus();
                return false;
            }
            if ($('#invoiceRecurringDescription').val().length == 0) {
                $('#invoiceRecurringDescriptionHelpMe').empty();
                $('#invoiceRecurringDescriptionHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringDescriptionLabel'] + " </span>");
                $('#invoiceRecurringDescriptionForm').addClass('form-group has-error');
                $('#invoiceRecurringDescription').focus();
                return false;
            }
            if ($('#invoiceRecurringDate').val().length == 0) {
                $('#invoiceRecurringDateHelpMe').empty();
                $('#invoiceRecurringDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringDateLabel'] + " </span>");
                $('#invoiceRecurringDateForm').addClass('form-group has-error');
                $('#invoiceRecurringDate').focus();
                return false;
            }
            if ($('#invoiceRecurringStartDate').val().length == 0) {
                $('#invoiceRecurringStartDateHelpMe').empty();
                $('#invoiceRecurringStartDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringStartDateLabel'] + " </span>");
                $('#invoiceRecurringStartDateForm').addClass('form-group has-error');
                $('#invoiceRecurringStartDate').focus();
                return false;
            }
            if ($('#invoiceRecurringEndDate').val().length == 0) {
                $('#invoiceRecurringEndDateHelpMe').empty();
                $('#invoiceRecurringEndDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringEndDateLabel'] + " </span>");
                $('#invoiceRecurringEndDateForm').addClass('form-group has-error');
                $('#invoiceRecurringEndDate').focus();
                return false;
            }
            if ($('#invoiceRecurringAmount').val().length == 0) {
                $('#invoiceRecurringAmountHelpMe').empty();
                $('#invoiceRecurringAmountHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringAmountLabel'] + " </span>");
                $('#invoiceRecurringAmountForm').addClass('form-group has-error');
                $('#invoiceRecurringAmount').focus();
                return false;
            }
            $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', invoiceRecurringTypeId: $('#invoiceRecurringTypeId').val(), referenceNumber: $('#referenceNumber').val(), journalRecurringTitle: $('#journalRecurringTitle').val(), invoiceRecurringDescription: $('#invoiceRecurringDescription').val(), invoiceRecurringDate: $('#invoiceRecurringDate').val(), invoiceRecurringStartDate: $('#invoiceRecurringStartDate').val(), invoiceRecurringEndDate: $('#invoiceRecurringEndDate').val(), invoiceRecurringAmount: $('#invoiceRecurringAmount').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                }, success: function(data) {
                    if (data.success == true) {
                        $('#infoPanel').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>");
                        $('#invoiceRecurringId').val(data.invoiceRecurringId);
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
                        $("#invoiceRecurringId9999").prop("disabled", "false");
                        $("#invoiceRecurringId9999").removeAttr("disabled", "");
                        $("#invoiceRecurringId9999").val('');
                        $("#invoiceRecurringId9999").trigger("chosen:updated");
                        $("#chartOfAccountId9999").prop("disabled", "false");
                        $("#chartOfAccountId9999").removeAttr("disabled", "");
                        $("#chartOfAccountId9999").val('');
                        $("#chartOfAccountId9999").trigger("chosen:updated");
                        $("#countryId9999").prop("disabled", "false");
                        $("#countryId9999").removeAttr("disabled", "");
                        $("#countryId9999").val('');
                        $("#countryId9999").trigger("chosen:updated");
                        $("#transactionTypeId9999").prop("disabled", "false");
                        $("#transactionTypeId9999").removeAttr("disabled", "");
                        $("#transactionTypeId9999").val('');
                        $("#transactionTypeId9999").trigger("chosen:updated");
                        $("#documentNumber9999").prop("disabled", "false");
                        $("#documentNumber9999").removeAttr("disabled", "");
                        $("#documentNumber9999").val('');
                        $("#journalNumber9999").prop("disabled", "false");
                        $("#journalNumber9999").removeAttr("disabled", "");
                        $("#journalNumber9999").val('');
                        $("#invoiceRecurringDetailAmount9999").prop("disabled", "false");
                        $("#invoiceRecurringDetailAmount9999").removeAttr("disabled", "");
                        $("#invoiceRecurringDetailAmount9999").val('');

                    }
                }, error: function(xhr) {
                    $('#infoError').empty();
                    $('#infoError').html('');
                    $('#infoError').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid').removeClass();
                    $('#infoErrorRowFluid').addClass('row');
                }});
        } else if (type == 5) {
            if ($('#invoiceRecurringTypeId').val().length == 0) {
                $('#invoiceRecurringTypeIdHelpMe').empty();
                $('#invoiceRecurringTypeIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringTypeIdLabel'] + " </span>");
                $('#invoiceRecurringTypeId').data('chosen').activate_action();
                return false;
            }
            if ($('#referenceNumber').val().length == 0) {
                $('#referenceNumberHelpMe').empty();
                $('#referenceNumberHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['referenceNumberLabel'] + " </span>");
                $('#referenceNumberForm').addClass('form-group has-error');
                $('#referenceNumber').focus();
                return false;
            }
            if ($('#journalRecurringTitle').val().length == 0) {
                $('#journalRecurringTitleHelpMe').empty();
                $('#journalRecurringTitleHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['journalRecurringTitleLabel'] + " </span>");
                $('#journalRecurringTitleForm').addClass('form-group has-error');
                $('#journalRecurringTitle').focus();
                return false;
            }
            if ($('#invoiceRecurringDescription').val().length == 0) {
                $('#invoiceRecurringDescriptionHelpMe').empty();
                $('#invoiceRecurringDescriptionHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringDescriptionLabel'] + " </span>");
                $('#invoiceRecurringDescriptionForm').addClass('form-group has-error');
                $('#invoiceRecurringDescription').focus();
                return false;
            }
            if ($('#invoiceRecurringDate').val().length == 0) {
                $('#invoiceRecurringDateHelpMe').empty();
                $('#invoiceRecurringDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringDateLabel'] + " </span>");
                $('#invoiceRecurringDateForm').addClass('form-group has-error');
                $('#invoiceRecurringDate').focus();
                return false;
            }
            if ($('#invoiceRecurringStartDate').val().length == 0) {
                $('#invoiceRecurringStartDateHelpMe').empty();
                $('#invoiceRecurringStartDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringStartDateLabel'] + " </span>");
                $('#invoiceRecurringStartDateForm').addClass('form-group has-error');
                $('#invoiceRecurringStartDate').focus();
                return false;
            }
            if ($('#invoiceRecurringEndDate').val().length == 0) {
                $('#invoiceRecurringEndDateHelpMe').empty();
                $('#invoiceRecurringEndDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringEndDateLabel'] + " </span>");
                $('#invoiceRecurringEndDateForm').addClass('form-group has-error');
                $('#invoiceRecurringEndDate').focus();
                return false;
            }
            if ($('#invoiceRecurringAmount').val().length == 0) {
                $('#invoiceRecurringAmountHelpMe').empty();
                $('#invoiceRecurringAmountHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringAmountLabel'] + " </span>");
                $('#invoiceRecurringAmountForm').addClass('form-group has-error');
                $('#invoiceRecurringAmount').focus();
                return false;
            }
            $.ajax({type: 'POST', url: url, data: {method: 'create', output: 'json', invoiceRecurringTypeId: $('#invoiceRecurringTypeId').val(), referenceNumber: $('#referenceNumber').val(), journalRecurringTitle: $('#journalRecurringTitle').val(), invoiceRecurringDescription: $('#invoiceRecurringDescription').val(), invoiceRecurringDate: $('#invoiceRecurringDate').val(), invoiceRecurringStartDate: $('#invoiceRecurringStartDate').val(), invoiceRecurringEndDate: $('#invoiceRecurringEndDate').val(), invoiceRecurringAmount: $('#invoiceRecurringAmount').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
            if ($('#invoiceRecurringTypeId').val().length == 0) {
                $('#invoiceRecurringTypeIdHelpMe').empty();
                $('#invoiceRecurringTypeIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringTypeIdLabel'] + " </span>");
                $('#invoiceRecurringTypeId').data('chosen').activate_action();
                return false;
            }
            if ($('#invoiceRecurringDate').val().length == 0) {
                $('#invoiceRecurringDateHelpMe').empty();
                $('#invoiceRecurringDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringDateLabel'] + " </span>");
                $('#invoiceRecurringDateForm').addClass('form-group has-error');
                $('#invoiceRecurringDate').focus();
                return false;
            }
            if ($('#invoiceRecurringStartDate').val().length == 0) {
                $('#invoiceRecurringStartDateHelpMe').empty();
                $('#invoiceRecurringStartDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringStartDateLabel'] + " </span>");
                $('#invoiceRecurringStartDateForm').addClass('form-group has-error');
                $('#invoiceRecurringStartDate').focus();
                return false;
            }
            if ($('#invoiceRecurringEndDate').val().length == 0) {
                $('#invoiceRecurringEndDateHelpMe').empty();
                $('#invoiceRecurringEndDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringEndDateLabel'] + " </span>");
                $('#invoiceRecurringEndDateForm').addClass('form-group has-error');
                $('#invoiceRecurringEndDate').focus();
                return false;
            }
            if ($('#invoiceRecurringAmount').val().length == 0) {
                $('#invoiceRecurringAmountHelpMe').empty();
                $('#invoiceRecurringAmountHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringAmountLabel'] + " </span>");
                $('#invoiceRecurringAmountForm').addClass('form-group has-error');
                $('#invoiceRecurringAmount').focus();
                return false;
            }
            $('#infoPanel').empty();
            $('#infoPanel').html('');
            $.ajax({type: 'POST', url: url, data: {method: 'save', output: 'json', invoiceRecurringId: $('#invoiceRecurringId').val(), invoiceRecurringTypeId: $('#invoiceRecurringTypeId').val(), referenceNumber: $('#referenceNumber').val(), journalRecurringTitle: $('#journalRecurringTitle').val(), invoiceRecurringDescription: $('#invoiceRecurringDescription').val(), invoiceRecurringDate: $('#invoiceRecurringDate').val(), invoiceRecurringStartDate: $('#invoiceRecurringStartDate').val(), invoiceRecurringEndDate: $('#invoiceRecurringEndDate').val(), invoiceRecurringAmount: $('#invoiceRecurringAmount').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
            if ($('#invoiceRecurringTypeId').val().length == 0) {
                $('#invoiceRecurringTypeIdHelpMe').empty();
                $('#invoiceRecurringTypeIdHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringTypeIdLabel'] + " </span>");
                $('#invoiceRecurringTypeId').data('chosen').activate_action();
                return false;
            }
            if ($('#referenceNumber').val().length == 0) {
                $('#referenceNumberHelpMe').empty();
                $('#referenceNumberHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['referenceNumberLabel'] + " </span>");
                $('#referenceNumberForm').addClass('form-group has-error');
                $('#referenceNumber').focus();
                return false;
            }
            if ($('#journalRecurringTitle').val().length == 0) {
                $('#journalRecurringTitleHelpMe').empty();
                $('#journalRecurringTitleHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['journalRecurringTitleLabel'] + " </span>");
                $('#journalRecurringTitleForm').addClass('form-group has-error');
                $('#journalRecurringTitle').focus();
                return false;
            }
            if ($('#invoiceRecurringDescription').val().length == 0) {
                $('#invoiceRecurringDescriptionHelpMe').empty();
                $('#invoiceRecurringDescriptionHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringDescriptionLabel'] + " </span>");
                $('#invoiceRecurringDescriptionForm').addClass('form-group has-error');
                $('#invoiceRecurringDescription').focus();
                return false;
            }
            if ($('#invoiceRecurringDate').val().length == 0) {
                $('#invoiceRecurringDateHelpMe').empty();
                $('#invoiceRecurringDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringDateLabel'] + " </span>");
                $('#invoiceRecurringDateForm').addClass('form-group has-error');
                $('#invoiceRecurringDate').focus();
                return false;
            }
            if ($('#invoiceRecurringStartDate').val().length == 0) {
                $('#invoiceRecurringStartDateHelpMe').empty();
                $('#invoiceRecurringStartDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringStartDateLabel'] + " </span>");
                $('#invoiceRecurringStartDateForm').addClass('form-group has-error');
                $('#invoiceRecurringStartDate').focus();
                return false;
            }
            if ($('#invoiceRecurringEndDate').val().length == 0) {
                $('#invoiceRecurringEndDateHelpMe').empty();
                $('#invoiceRecurringEndDateHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringEndDateLabel'] + " </span>");
                $('#invoiceRecurringEndDateForm').addClass('form-group has-error');
                $('#invoiceRecurringEndDate').focus();
                return false;
            }
            if ($('#invoiceRecurringAmount').val().length == 0) {
                $('#invoiceRecurringAmountHelpMe').empty();
                $('#invoiceRecurringAmountHelpMe').html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['invoiceRecurringAmountLabel'] + " </span>");
                $('#invoiceRecurringAmountForm').addClass('form-group has-error');
                $('#invoiceRecurringAmount').focus();
                return false;
            }
            if ($('#infoPanel').is(':hidden')) {
                $('#infoPanel').show();
            }
            $.ajax({type: 'POST', url: url, data: {method: 'save', output: 'json', invoiceRecurringId: $('#invoiceRecurringId').val(), invoiceRecurringTypeId: $('#invoiceRecurringTypeId').val(), referenceNumber: $('#referenceNumber').val(), journalRecurringTitle: $('#journalRecurringTitle').val(), invoiceRecurringDescription: $('#invoiceRecurringDescription').val(), invoiceRecurringDate: $('#invoiceRecurringDate').val(), invoiceRecurringStartDate: $('#invoiceRecurringStartDate').val(), invoiceRecurringEndDate: $('#invoiceRecurringEndDate').val(), invoiceRecurringAmount: $('#invoiceRecurringAmount').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                var value = $('#invoiceRecurringId').val();
                if (!value) {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-danger'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "<span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                    return false;
                } else {
                    $.ajax({type: 'POST', url: url, data: {method: 'delete', output: 'json', invoiceRecurringId: $('#invoiceRecurringId').val(), securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
function resetRecord(leafId, url, urlList, urlInvoiceRecurringDetail, securityToken, createAccess, updateAccess, deleteAccess) {
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
    $('#firstRecordButton').attr('onClick', "firstRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlInvoiceRecurringDetail + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
    $('#previousRecordButton').removeClass();
    $('#previousRecordButton').addClass('btn btn-default disabled');
    $('#previousRecordButton').attr('onClick', '');
    $('#nextRecordButton').removeClass();
    $('#nextRecordButton').addClass('btn btn-default disabled');
    $('#nextRecordButton').attr('onClick', '');
    $('#endRecordButton').removeClass();
    $('#endRecordButton').addClass('btn btn-default');
    $('#endRecordButton').attr('onClick', "endRecord\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlInvoiceRecurringDetail + "\",,\"" + securityToken + "\",\"" + updateAccess + "\")");
    $("#invoiceRecurringId").val('');
    $("#invoiceRecurringIdHelpMe").empty();
    $("#invoiceRecurringIdHelpMe").html('');

    $("#invoiceRecurringTypeId").val('');
    $("#invoiceRecurringTypeIdHelpMe").empty();
    $("#invoiceRecurringTypeIdHelpMe").html('');
    $('#invoiceRecurringTypeId').trigger("chosen:updated");
    $("#documentNumber").val('');
    $("#documentNumberHelpMe").empty();
    $("#documentNumberHelpMe").html('');
    $("#referenceNumber").val('');
    $("#referenceNumberHelpMe").empty();
    $("#referenceNumberHelpMe").html('');
    $("#journalRecurringTitle").val('');
    $("#journalRecurringTitleHelpMe").empty();
    $("#journalRecurringTitleHelpMe").html('');
    $("#invoiceRecurringDescription").val('');
    $("#invoiceRecurringDescriptionHelpMe").empty();
    $("#invoiceRecurringDescriptionHelpMe").html('');
    $('#invoiceRecurringDescription').empty();
    $('#invoiceRecurringDescription').val('');
    $("#invoiceRecurringDate").val('');
    $("#invoiceRecurringDateHelpMe").empty();
    $("#invoiceRecurringDateHelpMe").html('');
    $("#invoiceRecurringStartDate").val('');
    $("#invoiceRecurringStartDateHelpMe").empty();
    $("#invoiceRecurringStartDateHelpMe").html('');
    $("#invoiceRecurringEndDate").val('');
    $("#invoiceRecurringEndDateHelpMe").empty();
    $("#invoiceRecurringEndDateHelpMe").html('');
    $("#invoiceRecurringAmount").val('');
    $("#invoiceRecurringAmountHelpMe").empty();
    $("#invoiceRecurringAmountHelpMe").html('');
    $("#invoiceRecurringDetailId9999").prop("disabled", "true");
    $("#invoiceRecurringDetailId9999").attr("disabled", "disabled");
    $("#invoiceRecurringDetailId9999").val('');

    $("#chartOfAccountId9999").prop("disabled", "true");
    $("#chartOfAccountId9999").attr("disabled", "disabled");
    $("#chartOfAccountId9999").val('');
    $("#chartOfAccountId9999").trigger("chosen:updated");
    $("#countryId9999").prop("disabled", "true");
    $("#countryId9999").attr("disabled", "disabled");
    $("#countryId9999").val('');
    $("#countryId9999").trigger("chosen:updated");
    $("#transactionTypeId9999").prop("disabled", "true");
    $("#transactionTypeId9999").attr("disabled", "disabled");
    $("#transactionTypeId9999").val('');
    $("#transactionTypeId9999").trigger("chosen:updated");
    $("#documentNumber9999").prop("disabled", "true");
    $("#documentNumber9999").attr("disabled", "disabled");
    $("#documentNumber9999").val('');
    $("#journalNumber9999").prop("disabled", "true");
    $("#journalNumber9999").attr("disabled", "disabled");
    $("#journalNumber9999").val('');
    $("#invoiceRecurringDetailAmount9999").prop("disabled", "true");
    $("#invoiceRecurringDetailAmount9999").attr("disabled", "disabled");
    $("#invoiceRecurringDetailAmount9999").val('');

    $("#tableBody").empty();
    $("#tableBody").html('');
}
function postRecord(leafId, url, urlList, urlInvoiceRecurringDetail, SecurityToken) {
    var css = $('#postRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        return false;
    }
}
function firstRecord(leafId, url, urlList, urlInvoiceRecurringDetail, securityToken, updateAccess, deleteAccess) {
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
                    $.ajax({type: 'POST', url: url, data: {method: 'read', invoiceRecurringId: data.firstRecord, output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($('#infoPanel').is(':hidden')) {
                                $('#infoPanel').show();
                            }
                        }, success: function(data) {
                            if (data.success == true) {
                                $('#invoiceRecurringId').val(data.data.invoiceRecurringId);
                                $('#invoiceRecurringTypeId').val(data.data.invoiceRecurringTypeId);
                                $('#invoiceRecurringTypeId').trigger("chosen:updated");
                                $('#documentNumber').val(data.data.documentNumber);
                                $('#referenceNumber').val(data.data.referenceNumber);
                                $('#journalRecurringTitle').val(data.data.journalRecurringTitle);
                                $('#invoiceRecurringDescription').val(data.data.invoiceRecurringDescription);
                                var x = data.data.invoiceRecurringDate;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#invoiceRecurringDate').val(output);
                                var x = data.data.invoiceRecurringStartDate;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#invoiceRecurringStartDate').val(output);
                                var x = data.data.invoiceRecurringEndDate;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#invoiceRecurringEndDate').val(output);
                                $('#invoiceRecurringAmount').val(data.data.invoiceRecurringAmount);
                                $("#invoiceRecurringId9999").prop("disabled", "false");
                                $("#invoiceRecurringId9999").removeAttr("disabled", "");
                                $("#invoiceRecurringId9999").val('');
                                $("#invoiceRecurringId9999").trigger("chosen:updated");
                                $("#chartOfAccountId9999").prop("disabled", "false");
                                $("#chartOfAccountId9999").removeAttr("disabled", "");
                                $("#chartOfAccountId9999").val('');
                                $("#chartOfAccountId9999").trigger("chosen:updated");
                                $("#countryId9999").prop("disabled", "false");
                                $("#countryId9999").removeAttr("disabled", "");
                                $("#countryId9999").val('');
                                $("#countryId9999").trigger("chosen:updated");
                                $("#transactionTypeId9999").prop("disabled", "false");
                                $("#transactionTypeId9999").removeAttr("disabled", "");
                                $("#transactionTypeId9999").val('');
                                $("#transactionTypeId9999").trigger("chosen:updated");
                                $("#documentNumber9999").prop("disabled", "false");
                                $("#documentNumber9999").removeAttr("disabled", "");
                                $("#documentNumber9999").val('');
                                $("#invoiceRecurringDetailAmount9999").prop("disabled", "false");
                                $("#invoiceRecurringDetailAmount9999").removeAttr("disabled", "");
                                $("#invoiceRecurringDetailAmount9999").val('');

                                $.ajax({type: 'POST', url: urlInvoiceRecurringDetail, data: {method: 'read', invoiceRecurringId: data.firstRecord, output: 'table', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                                            $(".chzn-select").chosen({search_contains: true});
                                            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
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
                                    $('#nextRecordButton').attr('onClick', "nextRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlInvoiceRecurringDetail + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
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
function endRecord(leafId, url, urlList, urlInvoiceRecurringDetail, securityToken, updateAccess, deleteAccess) {
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
                var smileyRoll = './images/icons/smiley-roll.png';
                if (data.lastRecord === 0) {
                    $infoPanel.html('').empty().html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['recordNotFoundLabel']) + "</span>");
                    return false;
                }
                if (data.success == true) {
                    $.ajax({type: 'POST', url: url, data: {method: 'read', invoiceRecurringId: data.lastRecord, output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                            $('#infoPanel').empty();
                            $('#infoPanel').html('');
                            $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                            if ($('#infoPanel').is(':hidden')) {
                                $('#infoPanel').show();
                            }
                        }, success: function(data) {
                            if (data.success == true) {
                                $('#invoiceRecurringId').val(data.data.invoiceRecurringId);
                                $('#invoiceRecurringTypeId').val(data.data.invoiceRecurringTypeId);
                                $('#invoiceRecurringTypeId').trigger("chosen:updated");
                                $('#documentNumber').val(data.data.documentNumber);
                                $('#referenceNumber').val(data.data.referenceNumber);
                                $('#journalRecurringTitle').val(data.data.journalRecurringTitle);
                                $('#invoiceRecurringDescription').val(data.data.invoiceRecurringDescription);
                                var x = data.data.invoiceRecurringDate;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#invoiceRecurringDate').val(output);
                                var x = data.data.invoiceRecurringStartDate;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#invoiceRecurringStartDate').val(output);
                                var x = data.data.invoiceRecurringEndDate;
                                x = x.split("-");
                                var output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#invoiceRecurringEndDate').val(output);
                                $('#invoiceRecurringAmount').val(data.data.invoiceRecurringAmount);
                                $("#invoiceRecurringId9999").prop("disabled", "false");
                                $("#invoiceRecurringId9999").removeAttr("disabled", "");
                                $("#invoiceRecurringId9999").val('');
                                $("#invoiceRecurringId9999").trigger("chosen:updated");
                                $("#chartOfAccountId9999").prop("disabled", "false");
                                $("#chartOfAccountId9999").removeAttr("disabled", "");
                                $("#chartOfAccountId9999").val('');
                                $("#chartOfAccountId9999").trigger("chosen:updated");
                                $("#countryId9999").prop("disabled", "false");
                                $("#countryId9999").removeAttr("disabled", "");
                                $("#countryId9999").val('');
                                $("#countryId9999").trigger("chosen:updated");
                                $("#transactionTypeId9999").prop("disabled", "false");
                                $("#transactionTypeId9999").removeAttr("disabled", "");
                                $("#transactionTypeId9999").val('');
                                $("#transactionTypeId9999").trigger("chosen:updated");
                                $("#documentNumber9999").prop("disabled", "false");
                                $("#documentNumber9999").removeAttr("disabled", "");
                                $("#documentNumber9999").val('');
                                $("#invoiceRecurringDetailAmount9999").prop("disabled", "false");
                                $("#invoiceRecurringDetailAmount9999").removeAttr("disabled", "");
                                $("#invoiceRecurringDetailAmount9999").val('');

                                $.ajax({type: 'POST', url: urlInvoiceRecurringDetail, data: {method: 'read', invoiceRecurringId: data.lastRecord, output: 'table', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                                            $(".chzn-select").chosen({search_contains: true});
                                            $(".chzn-select-deselect").chosen({allow_single_deselect: true});
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
                                    $('#previousRecordButton').attr('onClick', "previousRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlInvoiceRecurringDetail + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
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
function previousRecord(leafId, url, urlList, urlInvoiceRecurringDetail, securityToken, updateAccess, deleteAccess) {
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
            $.ajax({type: 'POST', url: url, data: {method: 'read', invoiceRecurringId: $('#previousRecordCounter').val(), output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                }, success: function(data) {
                    if (data.success == true) {
                        $('#invoiceRecurringId').val(data.data.invoiceRecurringId);
                        $('#invoiceRecurringTypeId').val(data.data.invoiceRecurringTypeId);
                        $('#invoiceRecurringTypeId').trigger("chosen:updated");
                        $('#documentNumber').val(data.data.documentNumber);
                        $('#referenceNumber').val(data.data.referenceNumber);
                        $('#journalRecurringTitle').val(data.data.journalRecurringTitle);
                        $('#invoiceRecurringDescription').val(data.data.invoiceRecurringDescription);
                        var x = data.data.invoiceRecurringDate;
                        x = x.split("-");
                        var output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#invoiceRecurringDate').val(output);
                        var x = data.data.invoiceRecurringStartDate;
                        x = x.split("-");
                        var output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#invoiceRecurringStartDate').val(output);
                        var x = data.data.invoiceRecurringEndDate;
                        x = x.split("-");
                        var output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#invoiceRecurringEndDate').val(output);
                        $('#invoiceRecurringAmount').val(data.data.invoiceRecurringAmount);
                        $("#invoiceRecurringId9999").prop("disabled", "false");
                        $("#invoiceRecurringId9999").removeAttr("disabled", "");
                        $("#invoiceRecurringId9999").val('');
                        $("#invoiceRecurringId9999").trigger("chosen:updated");
                        $("#chartOfAccountId9999").prop("disabled", "false");
                        $("#chartOfAccountId9999").removeAttr("disabled", "");
                        $("#chartOfAccountId9999").val('');
                        $("#chartOfAccountId9999").trigger("chosen:updated");
                        $("#countryId9999").prop("disabled", "false");
                        $("#countryId9999").removeAttr("disabled", "");
                        $("#countryId9999").val('');
                        $("#countryId9999").trigger("chosen:updated");
                        $("#transactionTypeId9999").prop("disabled", "false");
                        $("#transactionTypeId9999").removeAttr("disabled", "");
                        $("#transactionTypeId9999").val('');
                        $("#transactionTypeId9999").trigger("chosen:updated");
                        $("#documentNumber9999").prop("disabled", "false");
                        $("#documentNumber9999").removeAttr("disabled", "");
                        $("#documentNumber9999").val('');
                        $("#invoiceRecurringDetailAmount9999").prop("disabled", "false");
                        $("#invoiceRecurringDetailAmount9999").removeAttr("disabled", "");
                        $("#invoiceRecurringDetailAmount9999").val('');

                        $.ajax({type: 'POST', url: urlInvoiceRecurringDetail, data: {method: 'read', invoiceRecurringId: $('#previousRecordCounter').val(), output: 'table', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                                    $(".chzn-select").chosen({search_contains: true});
                                    $(".chzn-select-deselect").chosen({allow_single_deselect: true});
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
                            $('#nextRecordButton').attr('onClick', "nextRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlInvoiceRecurringDetail + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
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
function nextRecord(leafId, url, urlList, urlInvoiceRecurringDetail, securityToken, updateAccess, deleteAccess) {
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
            $.ajax({type: 'POST', url: url, data: {method: 'read', invoiceRecurringId: $('#nextRecordCounter').val(), output: 'json', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
                    $('#infoPanel').empty();
                    $('#infoPanel').html('');
                    $('#infoPanel').html("<span class='label label-warning'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($('#infoPanel').is(':hidden')) {
                        $('#infoPanel').show();
                    }
                }, success: function(data) {
                    if (data.success == true) {
                        $('#invoiceRecurringId').val(data.data.invoiceRecurringId);
                        $('#invoiceRecurringTypeId').val(data.data.invoiceRecurringTypeId);
                        $('#invoiceRecurringTypeId').trigger("chosen:updated");
                        $('#documentNumber').val(data.data.documentNumber);
                        $('#referenceNumber').val(data.data.referenceNumber);
                        $('#journalRecurringTitle').val(data.data.journalRecurringTitle);
                        $('#invoiceRecurringDescription').val(data.data.invoiceRecurringDescription);
                        var x = data.data.invoiceRecurringDate;
                        x = x.split("-");
                        var output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#invoiceRecurringDate').val(output);
                        var x = data.data.invoiceRecurringStartDate;
                        x = x.split("-");
                        var output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#invoiceRecurringStartDate').val(output);
                        var x = data.data.invoiceRecurringEndDate;
                        x = x.split("-");
                        var output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#invoiceRecurringEndDate').val(output);
                        $('#invoiceRecurringAmount').val(data.data.invoiceRecurringAmount);
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
                        $("#invoiceRecurringId9999").prop("disabled", "false");
                        $("#invoiceRecurringId9999").removeAttr("disabled", "");
                        $("#invoiceRecurringId9999").val('');
                        $("#invoiceRecurringId9999").trigger("chosen:updated");
                        $("#chartOfAccountId9999").prop("disabled", "false");
                        $("#chartOfAccountId9999").removeAttr("disabled", "");
                        $("#chartOfAccountId9999").val('');
                        $("#chartOfAccountId9999").trigger("chosen:updated");
                        $("#countryId9999").prop("disabled", "false");
                        $("#countryId9999").removeAttr("disabled", "");
                        $("#countryId9999").val('');
                        $("#countryId9999").trigger("chosen:updated");
                        $("#transactionTypeId9999").prop("disabled", "false");
                        $("#transactionTypeId9999").removeAttr("disabled", "");
                        $("#transactionTypeId9999").val('');
                        $("#transactionTypeId9999").trigger("chosen:updated");
                        $("#documentNumber9999").prop("disabled", "false");
                        $("#documentNumber9999").removeAttr("disabled", "");
                        $("#documentNumber9999").val('');
                        $("#invoiceRecurringDetailAmount9999").prop("disabled", "false");
                        $("#invoiceRecurringDetailAmount9999").removeAttr("disabled", "");
                        $("#invoiceRecurringDetailAmount9999").val('');

                        $.ajax({type: 'POST', url: urlInvoiceRecurringDetail, data: {method: 'read', invoiceRecurringId: $('#nextRecordCounter').val(), output: 'table', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
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
                                    $(".chzn-select").chosen({search_contains: true});
                                    $(".chzn-select-deselect").chosen({allow_single_deselect: true});
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
                            $('#previousRecordButton').attr('onClick', "previousRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlInvoiceRecurringDetail + "\",\"" + securityToken + "\",\"" + updateAccess + "\",\"" + deleteAccess + "\")");
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