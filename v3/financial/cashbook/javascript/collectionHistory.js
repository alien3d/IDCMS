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
            from: 'collectionHistory.php',
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
            var $infoPanel = $("#infoPanel");
            var $centerViewport = $('#centerViewport');
            if (data.success === false) {
                $centerViewport.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
            } else {
                $centerViewport.html('').empty().append(data);
                $infoPanel.empty().html('');
                if (type === 1) {
                    $infoPanel.html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
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
            from: 'collectionHistory.php',
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
            var $infoPanel = $("#infoPanel");
            var $centerViewport = $('#centerViewport');
            var zoomIcon = './images/icons/magnifier-zoom-actual-equal.png';
            if (data.success === false) {
                $centerViewport.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
            } else {
                $centerViewport.html('').empty().append(data);
                $infoPanel.empty().html('').html("&nbsp;<img src='./images/icons/magnifier-zoom-actual-equal.png'> <b>" + decodeURIComponent(t['filterTextLabel']) + '</b>: ' + queryText + "");
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
            from: 'collectionHistory.php',
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
            var $infoPanel = $("#infoPanel");
            var $centerViewPort = $('#centerViewport');
            if (data.success === false) {
                $centerViewPort.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
            } else {
                $centerViewPort.html('').empty().append(data);
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
            from: 'collectionHistory.php',
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
            var $infoPanel = $("#infoPanel");
            var $centerViewPort = $('#centerViewport');
            if (data.success === false) {
                $centerViewPort.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'>" + data.message + "</span>");
            } else {
                $centerViewPort.html('').empty().append(data);
                $infoPanel.empty();
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
            from: 'collectionHistory.php',
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
            var $infoPanel = $("#infoPanel");
            var $centerViewPort = $('#centerViewport');
            if (data.success === false) {
                $centerViewPort.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
            } else {
                $centerViewPort.html('').empty().append(data);
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
function showFormUpdate(leafId, url, urlList, securityToken, collectionId) {
    sleep(500);
    $.ajax({
        type: 'POST',
        url: urlList,
        data: {
            method: 'read',
            type: 'form',
            collectionId: collectionId,
            from: 'collectionHistory.php',
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
            var $infoPanel = $("#infoPanel");
            var $centerViewPort = $('#centerViewport');
            if (data.success === false) {
                $centerViewPort.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
            } else {
                $centerViewPort.html('').empty().append(data);
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
function showModalDelete(collectionId, collectionTypeId, businessPartnerId, countryId, bankId, paymentTypeId, documentNumber, referenceNumber, chequeNumber, collectionAmount, collectionDate, collectionBankInSlipNumber, collectionBankInSlipDate, collectionDescription) {
    $('#collectionIdPreview').val('').val(decodeURIComponent(collectionId));
    $('#collectionTypeIdPreview').val('').val(decodeURIComponent(collectionTypeId));
    $('#businessPartnerIdPreview').val('').val(decodeURIComponent(businessPartnerId));
    $('#countryIdPreview').val('').val(decodeURIComponent(countryId));
    $('#bankIdPreview').val('').val(decodeURIComponent(bankId));
    $('#paymentTypeIdPreview').val('').val(decodeURIComponent(paymentTypeId));
    $('#documentNumberPreview').val('').val(decodeURIComponent(documentNumber));
    $('#referenceNumberPreview').val('').val(decodeURIComponent(referenceNumber));
    $('#chequeNumberPreview').val('').val(decodeURIComponent(chequeNumber));
    $('#collectionAmountPreview').val('').val(decodeURIComponent(collectionAmount));
    $('#collectionDatePreview').val('').val(decodeURIComponent(collectionDate));
    $('#collectionBankInSlipNumberPreview').val('').val(decodeURIComponent(collectionBankInSlipNumber));
    $('#collectionBankInSlipDatePreview').val('').val(decodeURIComponent(collectionBankInSlipDate));
    $('#collectionDescriptionPreview').val('').val(decodeURIComponent(collectionDescription));
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
            var $infoPanel = $("#infoPanel");
            $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $infoPanel = $("#infoPanel");
            if (data.success === true) {
                var path = "./v3/financial/cashbook/document/" + data.folder + "/" + data.filename;
                $infoPanel.html("<span class='label label-success'>" + decodeURIComponent(t['requestFileTextLabel']) + "</span>");
                window.open(path);
            } else {
                $infoPanel.empty().html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
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
function resetRecord(leafId, url, urlList, urlCollectionDetail, securityToken) {
    var $infoPanel = $("#infoPanel");
    $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
    if ($infoPanel.is(':hidden')) {
        $infoPanel.show();
    }
    $('#postRecordButton').addClass('btn btn-info').attr('onClick', '');
    $('#firstRecordButton').removeClass().addClass('btn btn-default').attr('onClick', "firstRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlCollectionDetail + "\",\"" + securityToken + "\")");
    $('#previousRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
    $('#nextRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
    $('#endRecordButton').removeClass().addClass('btn btn-default').attr('onClick', "endRecord\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlCollectionDetail + "\",,\"" + securityToken + "\")");
    $("#collectionId").val('');
    $("#collectionIdHelpMe").empty().html('');
    $("#collectionTypeId").val('');
    $("#collectionTypeIdHelpMe").empty().html('');
    $('#collectionTypeId').trigger("chosen:updated");
    $("#businessPartnerId").val('');
    $("#businessPartnerIdHelpMe").empty().html('');
    $('#businessPartnerId').trigger("chosen:updated");
    $("#bankId").val('').trigger("chosen:updated");
    $("#bankIdHelpMe").empty().html('');
    $("#paymentTypeId").val('').trigger("chosen:updated");
    $("#paymentTypeIdHelpMe").empty().html('');
    $("#documentNumber").val('');
    $("#documentNumberHelpMe").empty().html('');
    $("#referenceNumber").val('');
    $("#referenceNumberHelpMe").empty().html('');
    $("#chequeNumber").val('');
    $("#chequeNumberHelpMe").empty().html('');
    $("#collectionAmount").val('');
    $("#collectionAmountHelpMe").empty().html('');
    $("#collectionDate").val('');
    $("#collectionDateHelpMe").empty().html('');
    $("#collectionBankInSlipNumber").val('');
    $("#collectionBankInSlipNumberHelpMe").empty().html('');
    $("#collectionBankInSlipDate").val('');
    $("#collectionBankInSlipDateHelpMe").empty().html('');
    $("#collectionDescription").val('');
    $("#collectionDescriptionHelpMe").empty().html('');
    $('#collectionDescription').empty().val('');
    $("#collectionDetailId9999").val('');
    $("#collectionId9999").val('');
    $("#chartOfAccountId9999").prop("disabled", "true").attr("disabled", "disabled").val('').trigger("chosen:updated");
    $("#chartOfAccountId9999HelpMe").empty().html('');
    $("#collectionDetailAmount9999").prop("disabled", "true").attr("disabled", "disabled").val('');
    $("#collectionDetailAmount9999HelpMe").empty().html('');
    $("#tableBody").empty().html('');
}
function firstRecord(leafId, url, urlList, urlCollectionDetail, securityToken) {
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
                from: 'collectionHistory.php',
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
                            collectionId: data.firstRecord,
                            output: 'json',
                            from: 'collectionHistory.php',
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
                            var $infoPanel = $("#infoPanel");
                            var x,
                                    output;
                            var success = data.success;
                            if (success === true) {
                                $('#collectionId').val(data.data.collectionId);
                                $('#collectionTypeId').val(data.data.collectionTypeId).trigger("chosen:updated");
                                $('#businessPartnerId').val(data.data.businessPartnerId).trigger("chosen:updated");
                                $('#bankId').val(data.data.bankId).trigger("chosen:updated");
                                $('#paymentTypeId').val(data.data.paymentTypeId).trigger("chosen:updated");
                                $('#documentNumber').val(data.data.documentNumber);
                                $('#referenceNumber').val(data.data.referenceNumber);
                                $('#chequeNumber').val(data.data.chequeNumber);
                                $('#collectionAmount').val(data.data.collectionAmount);
                                x = data.data.collectionDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#collectionDate').val(output);
                                $('#collectionBankInSlipNumber').val(data.data.collectionBankInSlipNumber);
                                x = data.data.collectionBankInSlipDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#collectionBankInSlipDate').val(output);
                                $('#collectionDescription').val(data.data.collectionDescription);
                                $("#chartOfAccountId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                                $("#collectionDetailAmount9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                                $.ajax({
                                    type: 'POST',
                                    url: urlCollectionDetail,
                                    data: {
                                        method: 'read',
                                        collectionId: data.firstRecord,
                                        output: 'table',
                                        from: 'collectionHistory.php',
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
                                        var $infoPanel = $("#infoPanel");
                                        if (data.success === true) {
                                            $('#infoPanel').empty().html('');
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
                                    $('#nextRecordButton').removeClass().addClass('btn btn-default').attr('onClick', '').attr('onClick', "nextRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlCollectionDetail + "\",\"" + securityToken + "\")");
                                    $('#firstRecordCounter').val(data.firstRecord);
                                    $('#previousRecordCounter').val(data.previousRecord);
                                    $('#nextRecordCounter').val(data.nextRecord);
                                    $('#lastRecordCounter').val(data.lastRecord);
                                }
                                $infoPanel.empty().html("&nbsp;<img src='./images/icons/control-stop.png'> " + decodeURIComponent(t['firstButtonLabel']) + " ");
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
function endRecord(leafId, url, urlList, urlCollectionDetail, securityToken) {
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
                from: 'collectionHistory.php',
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
                var $infoPanel = $("#infoPanel");
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
                            collectionId: data.lastRecord,
                            output: 'json',
                            from: 'collectionHistory.php',
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
                            var $infoPanel = $("#infoPanel");
                            var x,
                                    output;
                            var success = data.success;
                            if (success === true) {
                                $('#collectionId').val(data.data.collectionId);
                                $('#collectionTypeId').val(data.data.collectionTypeId).trigger("chosen:updated");
                                $('#businessPartnerId').val(data.data.businessPartnerId).trigger("chosen:updated");
                                $('#bankId').val(data.data.bankId).trigger("chosen:updated");
                                $('#paymentTypeId').val(data.data.paymentTypeId).trigger("chosen:updated");
                                $('#documentNumber').val(data.data.documentNumber);
                                $('#referenceNumber').val(data.data.referenceNumber);
                                $('#chequeNumber').val(data.data.chequeNumber);
                                $('#collectionAmount').val(data.data.collectionAmount);
                                x = data.data.collectionDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#collectionDate').val(output);
                                $('#collectionBankInSlipNumber').val(data.data.collectionBankInSlipNumber);
                                x = data.data.collectionBankInSlipDate;
                                x = x.split("-");
                                output = x[2] + "-" + x[1] + "-" + x[0];
                                output = output.toString();
                                $('#collectionBankInSlipDate').val(output);
                                $('#collectionDescription').val(data.data.collectionDescription);
                                $("#chartOfAccountId9999").prop("disabled", "false").removeAttr("disabled", "").val('').trigger("chosen:updated");
                                $("#collectionDetailAmount9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                                $.ajax({
                                    type: 'POST',
                                    url: urlCollectionDetail,
                                    data: {
                                        method: 'read',
                                        collectionId: data.lastRecord,
                                        output: 'table',
                                        from: 'collectionHistory.php',
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
                                        var $infoPanel = $("#infoPanel");
                                        if (data.success === true) {
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
                                    }
                                });
                                if (data.lastRecord !== 0) {
                                    $('#previousRecordButton').removeClass().addClass('btn btn-default').attr('onClick', "previousRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlCollectionDetail + "\",\"" + securityToken + "\")");
                                    $('#nextRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
                                    $('#firstRecordCounter').val(data.firstRecord);
                                    $('#previousRecordCounter').val(data.previousRecord);
                                    $('#nextRecordCounter').val(data.nextRecord);
                                    $('#lastRecordCounter').val(data.lastRecord);
                                }
                            }
                        },
                        error: function(xhr) {
                            $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                            $('#infoErrorRowFluid').removeClass().addClass('row');
                        }
                    });
                } else {
                    $infoPanel.html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
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
function previousRecord(leafId, url, urlList, urlCollectionDetail, securityToken) {
    var css = $('#previousRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $('#newButton').removeClass();
        if ($('#previousRecordCounter').val() === '' || $('#previousRecordCounter').val() === undefined) {
            var $infoPanel = $("#infoPanel");
            $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }
        if (parseFloat($('#previousRecordCounter').val()) > 0 && parseFloat($('#previousRecordCounter').val()) < parseFloat($('#lastRecordCounter').val())) {
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'read',
                    collectionId: $('#previousRecordCounter').val(),
                    output: 'json',
                    from: 'collectionHistory.php',
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
                    var $infoPanel = $("#infoPanel");
                    var x,
                            output
                    var success = data.success;
                    if (success === true) {
                        $('#collectionId').val(data.data.collectionId);
                        $('#collectionTypeId').val(data.data.collectionTypeId).trigger("chosen:updated");
                        $('#businessPartnerId').val(data.data.businessPartnerId).trigger("chosen:updated");
                        $('#bankId').val(data.data.bankId).trigger("chosen:updated");
                        $('#paymentTypeId').val(data.data.paymentTypeId).trigger("chosen:updated");
                        $('#documentNumber').val(data.data.documentNumber);
                        $('#referenceNumber').val(data.data.referenceNumber);
                        $('#chequeNumber').val(data.data.chequeNumber);
                        $('#collectionAmount').val(data.data.collectionAmount);
                        x = data.data.collectionDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#collectionDate').val(output);
                        $('#collectionBankInSlipNumber').val(data.data.collectionBankInSlipNumber);
                        x = data.data.collectionBankInSlipDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#collectionBankInSlipDate').val(output);
                        $('#collectionDescription').val(data.data.collectionDescription);
                        $("#chartOfAccountId9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                        $("#collectionDetailAmount9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                        $.ajax({
                            type: 'POST',
                            url: urlCollectionDetail,
                            data: {
                                method: 'read',
                                collectionId: $('#previousRecordCounter').val(),
                                output: 'table',
                                from: 'collectionHistory.php',
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
                        $('#firstRecordCounter').val(data.firstRecord);
                        $('#previousRecordCounter').val(data.previousRecord);
                        $('#nextRecordCounter').val(data.nextRecord);
                        $('#lastRecordCounter').val(data.lastRecord);
                        if (parseFloat(data.nextRecord) <= parseFloat(data.lastRecord)) {
                            $('#nextRecordButton').removeClass().addClass('btn btn-default').attr('onClick', '').attr('onClick', "nextRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlCollectionDetail + "\",\"" + securityToken + "\")");
                        } else {
                            $('#nextRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
                        }
                        if (parseFloat(data.previousRecord) === 0) {
                            $infoPanel.empty().html("&nbsp;<img src='./images/icons/exclamation.png'> " + decodeURIComponent(t['firstButtonLabel']) + " ");
                            $('#previousRecordButton').removeClass().addClass('btn btn-default disabled').attr('onClick', '');
                        } else {
                            $infoPanel.empty().html('').html("&nbsp;<img src='./images/icons/control-180.png'> " + decodeURIComponent(t['previousButtonLabel']) + " ");
                            if ($infoPanel.is(':hidden')) {
                                $infoPanel.show();
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
        }

    }
}
function nextRecord(leafId, url, urlList, urlCollectionDetail, securityToken) {
    var css = $('#nextRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $('#newButton').removeClass();
        if ($('#nextRecordCounter').val() === '' || $('#nextRecordCounter').val() === undefined) {
            var $infoPanel = $("#infoPanel");
            $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }
        if (parseFloat($('#nextRecordCounter').val()) <= parseFloat($('#lastRecordCounter').val())) {
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'read',
                    collectionId: $('#nextRecordCounter').val(),
                    output: 'json',
                    from: 'collectionHistory.php',
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
                    var $infoPanel = $("#infoPanel");
                    var success = data.success;
                    var x,
                            output;
                    if (success === true) {
                        $('#collectionId').val(data.data.collectionId);
                        $('#collectionTypeId').val(data.data.collectionTypeId).trigger("chosen:updated");
                        $('#businessPartnerId').val(data.data.businessPartnerId).trigger("chosen:updated");
                        $('#bankId').val(data.data.bankId).trigger("chosen:updated");
                        $('#paymentTypeId').val(data.data.paymentTypeId).trigger("chosen:updated");
                        $('#documentNumber').val(data.data.documentNumber);
                        $('#referenceNumber').val(data.data.referenceNumber);
                        $('#chequeNumber').val(data.data.chequeNumber);
                        $('#collectionAmount').val(data.data.collectionAmount);
                        x = data.data.collectionDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#collectionDate').val(output);
                        $('#collectionBankInSlipNumber').val(data.data.collectionBankInSlipNumber);
                        x = data.data.collectionBankInSlipDate;
                        x = x.split("-");
                        output = x[2] + "-" + x[1] + "-" + x[0];
                        output = output.toString();
                        $('#collectionBankInSlipDate').val(output);
                        $('#collectionDescription').val(data.data.collectionDescription);
                        $("#chartOfAccountId9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                        $("#collectionDetailAmount9999").prop("disabled", "false").removeAttr("disabled", "").val('');
                        $.ajax({
                            type: 'POST',
                            url: urlCollectionDetail,
                            data: {
                                method: 'read',
                                collectionId: $('#nextRecordCounter').val(),
                                output: 'table',
                                from: 'collectionHistory.php',
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
                                var $infoPanel = $("#infoPanel");
                                if (data.success === true) {
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
                            $('#previousRecordButton').removeClass().addClass('btn btn-default').attr('onClick', "previousRecord(\"" + leafId + "\",\"" + url + "\",\"" + urlList + "\",\"" + urlCollectionDetail + "\",\"" + securityToken + "\")");
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
