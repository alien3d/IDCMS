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
function showFormUpdate(leafId, url, urlList, securityToken, generalLedgerId, updateAccess, deleteAccess) {
    sleep(500);
    $('a[rel=tooltip]').tooltip('hide');
    $.ajax({
        type: 'POST',
        url: urlList,
        data: {
            method: 'read',
            type: 'form',
            generalLedgerId: generalLedgerId,
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
                $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
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
                    $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '');
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
function showModalDelete(generalLedgerId, financeYearId, financeYearYear, financePeriodRangeId, financePeriodRangePeriod, journalNumber, documentNumber, documentDate, generalLedgerTitle, generalLedgerDescription, generalLedgerDate, countryId, countryCurrencyCode, transactionTypeId, transactionTypeCode, transactionTypeDescription, foreignAmount, localAmount, chartOfAccountCategoryId, chartOfAccountCategoryCode, chartOfAccountCategoryDescription, chartOfAccountTypeId, chartOfAccountTypeCode, chartOfAccountTypeDescription, chartOfAccountId, chartOfAccountNumber, chartOfAccountDescription, businessPartnerId, businessPartnerDescription, module, tableName, tableNameId, tableNameDetail, tableNameDetailId, leafId, leafName, from, isMerge, isAuthorized, executeName) {
    $('#generalLedgerIdPreview').val('').val(decodeURIComponent(generalLedgerId));
    $('#financeYearIdPreview').val('').val(decodeURIComponent(financeYearId));
    $('#financeYearYearPreview').val('').val(decodeURIComponent(financeYearYear));
    $('#financePeriodRangeIdPreview').val('').val(decodeURIComponent(financePeriodRangeId));
    $('#financePeriodRangePeriodPreview').val('').val(decodeURIComponent(financePeriodRangePeriod));
    $('#journalNumberPreview').val('').val(decodeURIComponent(journalNumber));
    $('#documentNumberPreview').val('').val(decodeURIComponent(documentNumber));
    $('#documentDatePreview').val('').val(decodeURIComponent(documentDate));
    $('#generalLedgerTitlePreview').val('').val(decodeURIComponent(generalLedgerTitle));
    $('#generalLedgerDescriptionPreview').val('').val(decodeURIComponent(generalLedgerDescription));
    $('#generalLedgerDatePreview').val('').val(decodeURIComponent(generalLedgerDate));
    $('#countryIdPreview').val('').val(decodeURIComponent(countryId));
    $('#countryCurrencyCodePreview').val('').val(decodeURIComponent(countryCurrencyCode));
    $('#transactionTypeIdPreview').val('').val(decodeURIComponent(transactionTypeId));
    $('#transactionTypeCodePreview').val('').val(decodeURIComponent(transactionTypeCode));
    $('#transactionTypeDescriptionPreview').val('').val(decodeURIComponent(transactionTypeDescription));
    $('#foreignAmountPreview').val('').val(decodeURIComponent(foreignAmount));
    $('#localAmountPreview').val('').val(decodeURIComponent(localAmount));
    $('#chartOfAccountCategoryIdPreview').val('').val(decodeURIComponent(chartOfAccountCategoryId));
    $('#chartOfAccountCategoryCodePreview').val('').val(decodeURIComponent(chartOfAccountCategoryCode));
    $('#chartOfAccountCategoryDescriptionPreview').val('').val(decodeURIComponent(chartOfAccountCategoryDescription));
    $('#chartOfAccountTypeIdPreview').val('').val(decodeURIComponent(chartOfAccountTypeId));
    $('#chartOfAccountTypeCodePreview').val('').val(decodeURIComponent(chartOfAccountTypeCode));
    $('#chartOfAccountTypeDescriptionPreview').val('').val(decodeURIComponent(chartOfAccountTypeDescription));
    $('#chartOfAccountIdPreview').val('').val(decodeURIComponent(chartOfAccountId));
    $('#chartOfAccountNumberPreview').val('').val(decodeURIComponent(chartOfAccountNumber));
    $('#chartOfAccountDescriptionPreview').val('').val(decodeURIComponent(chartOfAccountDescription));
    $('#businessPartnerIdPreview').val('').val(decodeURIComponent(businessPartnerId));
    $('#businessPartnerDescriptionPreview').val('').val(decodeURIComponent(businessPartnerDescription));
    $('#modulePreview').val('').val(decodeURIComponent(module));
    $('#tableNamePreview').val('').val(decodeURIComponent(tableName));
    $('#tableNameIdPreview').val('').val(decodeURIComponent(tableNameId));
    $('#tableNameDetailPreview').val('').val(decodeURIComponent(tableNameDetail));
    $('#tableNameDetailIdPreview').val('').val(decodeURIComponent(tableNameDetailId));
    $('#leafIdPreview').val('').val(decodeURIComponent(leafId));
    $('#leafNamePreview').val('').val(decodeURIComponent(leafName));
    $('#fromPreview').val('').val(decodeURIComponent(from));
    $('#isMergePreview').val('').val(decodeURIComponent(isMerge));
    $('#isAuthorizedPreview').val('').val(decodeURIComponent(isAuthorized));
    $('#executeNamePreview').val('').val(decodeURIComponent(executeName));
    showMeModal('deletePreview', 1);
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
                var path = "./v3/financial/generalLedger/document/" + folder + "/" + filename;
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
function resetRecord(leafId, url, urlList, urlChartOfAccountRoleAccess, securityToken, createAccess, updateAccess, deleteAccess) {
    var $infoPanel = $('#infoPanel');
    var resetIcon = './images/icons/fruit-orange.png';
    $infoPanel.html('').empty().html("<span class='label label-danger'><img src='" + resetIcon + "'> " + decodeURIComponent(t['resetRecordTextLabel']) + "</span>").delay(1000).fadeOut();
    if ($infoPanel.is(':hidden')) {
        $infoPanel.show();
    }
    if (createAccess === 1) {
        $('#newRecordButton1').removeClass().addClass('btn btn-success').attr("onClick", "newRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1)");
        $('#newRecordButton2').attr('onClick', '').removeClass().addClass('btn dropdown-toggle btn-success');
        $('#newRecordButton3').attr("onClick", "newRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',1)");
        $('#newRecordButton4').attr("onClick", "newRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',2)");
        $('#newRecordButton5').attr("onClick", "newRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',3)");
        $('#newRecordButton6').attr("onClick", "newRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',4)");
        $('#newRecordButton7').attr("onClick", "newRecord(" + leafId + ",'" + url + "','" + urlList + "','" + securityToken + "',5)");
    } else {
        $('#newRecordButton1').removeClass().addClass('btn btn-success disabled').attr('onClick', '');
        $('#newRecordButton2').removeClass().addClass('btn dropdown-toggle btn-success disabled');
        $('#newRecordButton3').attr('onClick', '');
        $('#newRecordButton4').attr('onClick', '');
        $('#newRecordButton5').attr('onClick', '');
        $('#newRecordButton6').attr('onClick', '');
        $('#newRecordButton7').attr('onClick', '');
    }
    $('#updateRecordButton1').removeClass().addClass('btn btn-info disabled').attr('onClick', '').attr('onClick', '');
    $('#updateRecordButton2').removeClass().addClass('btn dropdown-toggle btn-info disabled').attr('onClick', '');
    $('#updateRecordButton3').attr('onClick', '');
    $('#updateRecordButton4').attr('onClick', '');
    $('#updateRecordButton5').attr('onClick', '');
    $('#deleteRecordButton').removeClass().addClass('btn btn-danger disabled').attr('onClick', '');
    $('#postRecordButton').removeClass().addClass('btn btn-info').attr('onClick', '');
    $('#firstRecordButton').removeClass().addClass('btn btn-default').attr('onClick', "firstRecord(" + leafId + ",'" + url + "','" + urlList + "','" + urlChartOfAccountRoleAccess + "','" + securityToken + "'," + updateAccess + "," + deleteAccess + ")");
    $('#previousRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
    $('#nextRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
    $('#endRecordButton').removeClass().addClass('btn btn-default').attr('onClick', "endRecord(" + leafId + ",'" + url + "','" + urlList + "','" + urlChartOfAccountRoleAccess + "','" + securityToken + "'," + updateAccess + ")");
    $("#generalLedgerId").val('');
    $("#generalLedgerIdHelpMe").empty().html('');

    $("#financeYearId").val('');
    $("#financeYearIdHelpMe").empty().html('');
    $("#financeYearYear").val('');
    $("#financeYearYearHelpMe").empty().html('');
    $("#financePeriodRangeId").val('');
    $("#financePeriodRangeIdHelpMe").empty().html('');
    $("#financePeriodRangePeriod").val('');
    $("#financePeriodRangePeriodHelpMe").empty().html('');
    $("#journalNumber").val('');
    $("#journalNumberHelpMe").empty().html('');
    $("#documentNumber").val('');
    $("#documentNumberHelpMe").empty().html('');
    $("#documentDate").val('');
    $("#documentDateHelpMe").empty().html('');
    $("#generalLedgerTitle").val('');
    $("#generalLedgerTitleHelpMe").empty().html('');
    $('#generalLedgerTitle').empty().val('');
    $("#generalLedgerDescription").val('');
    $("#generalLedgerDescriptionHelpMe").empty().html('');
    $("#generalLedgerDate").val('');
    $("#generalLedgerDateHelpMe").empty().html('');
    $("#countryId").val('');
    $("#countryIdHelpMe").empty().html('');
    $("#countryCurrencyCode").val('');
    $("#countryCurrencyCodeHelpMe").empty().html('');
    $("#transactionTypeId").val('');
    $("#transactionTypeIdHelpMe").empty().html('');
    $("#transactionTypeCode").val('');
    $("#transactionTypeCodeHelpMe").empty().html('');
    $("#transactionTypeDescription").val('');
    $("#transactionTypeDescriptionHelpMe").empty().html('');
    $('#transactionTypeDescription').empty().val('');
    $("#foreignAmount").val('');
    $("#foreignAmountHelpMe").empty().html('');
    $("#localAmount").val('');
    $("#localAmountHelpMe").empty().html('');
    $("#chartOfAccountCategoryId").val('');
    $("#chartOfAccountCategoryIdHelpMe").empty().html('');
    $("#chartOfAccountCategoryCode").val('');
    $("#chartOfAccountCategoryCodeHelpMe").empty().html('');
    $("#chartOfAccountCategoryDescription").val('');
    $("#chartOfAccountCategoryDescriptionHelpMe").empty().html('');
    $('#chartOfAccountCategoryDescription').empty().val('');
    $("#chartOfAccountTypeId").val('');
    $("#chartOfAccountTypeIdHelpMe").empty().html('');
    $("#chartOfAccountTypeCode").val('');
    $("#chartOfAccountTypeCodeHelpMe").empty().html('');
    $("#chartOfAccountTypeDescription").val('');
    $("#chartOfAccountTypeDescriptionHelpMe").empty().html('');
    $('#chartOfAccountTypeDescription').empty().val('');
    $("#chartOfAccountId").val('');
    $("#chartOfAccountIdHelpMe").empty().html('');
    $("#chartOfAccountNumber").val('');
    $("#chartOfAccountNumberHelpMe").empty().html('');
    $("#chartOfAccountDescription").val('');
    $("#chartOfAccountDescriptionHelpMe").empty().html('');
    $('#chartOfAccountDescription').empty().val('');
    $("#businessPartnerId").val('');
    $("#businessPartnerIdHelpMe").empty().html('');
    $("#businessPartnerDescription").val('');
    $("#businessPartnerDescriptionHelpMe").empty().html('');
    $("#module").val('');
    $("#moduleHelpMe").empty().html('');
    $('#module').empty().val('');
    $("#tableName").val('');
    $("#tableNameHelpMe").empty().html('');
    $('#tableName').empty().val('');
    $("#tableNameId").val('');
    $("#tableNameIdHelpMe").empty().html('');
    $("#tableNameDetail").val('');
    $("#tableNameDetailHelpMe").empty().html('');
    $('#tableNameDetail').empty().val('');
    $("#tableNameDetailId").val('');
    $("#tableNameDetailIdHelpMe").empty().html('');
    $("#leafId").val('');
    $("#leafIdHelpMe").empty().html('');
    $("#leafName").val('');
    $("#leafNameHelpMe").empty().html('');
    $("#from").val('');
    $("#fromHelpMe").empty().html('');
    $("#isMerge").val('');
    $("#isMergeHelpMe").empty().html('');
    $("#isAuthorized").val('');
    $("#isAuthorizedHelpMe").empty().html('');
    $("#executeName").val('');
    $("#executeNameHelpMe").empty().html('');
    $("#chartOfAccountRoleAccess9999").prop("disabled", "true").attr("disabled", "disabled").val('');
    $("#chartOfAccountId9999").prop("disabled", "true").attr("disabled", "disabled").val('').trigger("chosen:updated");
    $("#roleId9999").prop("disabled", "true").attr("disabled", "disabled").val('').trigger("chosen:updated");
    $("#chartOfAccountRoleAccessValue9999").prop("disabled", "true").attr("disabled", "disabled").val('');
    $("#tableBody").html('').empty();
}