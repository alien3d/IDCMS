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
            $infoPanel
                    .html('').empty()
                    .html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
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
                $centerViewPort

                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
            } else {
                $centerViewPort
                        .html('').empty()

                        .append(data);
            }
            var $infoPanel = $('#infoPanel');
            $infoPanel
                    .html('').empty();
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
            $('#infoError')
                    .html('').empty()
                    .html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row-fluid');
        }
    });
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
            $infoPanel
                    .html('').empty()
                    .html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                $centerViewPort

                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
            } else {
                $centerViewPort
                        .html('').empty()

                        .append(data);
                var $infoPanel = $('#infoPanel');
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
                $(document).scrollTop();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError')
                    .html('').empty()
                    .html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row-fluid');
        }
    });
}
function showFormUpdate(leafId, url, urlList, securityToken, systemSettingId, updateAccess, deleteAccess) {
    sleep(500);
    $('a[rel=tooltip]').tooltip('hide');
    $.ajax({
        type: 'POST',
        url: urlList,
        data: {
            method: 'read',
            type: 'form',
            systemSettingId: systemSettingId,
            securityToken: securityToken,
            leafId: leafId
        },
        beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel
                    .html('').empty()
                    .html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                $centerViewPort

                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;<img src='" + smileyRoll + "'> " + message + "</span>");
            } else {
                $centerViewPort

                        .html('').empty()
                        .append(data);
                $infoPanel
                        .html('').empty()
                        .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
                if (updateAccess === 1) {
                    $('#updateRecordButton1')
                            .removeClass().addClass('btn btn-info');
                    $('#updateRecordButton2')
                            .removeClass().addClass('btn dropdown-toggle btn-info');
                    $('#updateRecordButton3').attr('onClick', "updateRecord(" + leafId + ",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",1," + deleteAccess + ")");
                    $('#updateRecordButton4').attr('onClick', "updateRecord(" + leafId + ",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",2," + deleteAccess + ")");
                    $('#updateRecordButton5').attr('onClick', "updateRecord(" + leafId + ",\"" + url + "\",\"" + urlList + "\",\"" + securityToken + "\",3," + deleteAccess + ")");
                } else {
                    $('#updateRecordButton1')
                            .removeClass().addClass('btn btn-info disabled');
                    $('#updateRecordButton2')
                            .removeClass().addClass('btn dropdown-toggle btn-info disabled');
                    $('#updateRecordButton3').attr('onClick', '');
                    $('#updateRecordButton4').attr('onClick', '');
                    $('#updateRecordButton5').attr('onClick', '');
                }
                $(document).scrollTop();
            }
        },
        error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError')
                    .html('').empty()
                    .html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid')
                    .removeClass().addClass('row-fluid');
        }
    });
}
function showModalDelete(systemSettingId, countryId, languageId, languageCode, systemSettingDateFormat, systemSettingTimeFormat, systemSettingWeekStart, systemWebsite) {
    $('#systemSettingIdPreview').val('').val(decodeURIComponent(systemSettingId));
    $('#countryIdPreview').val('').val(decodeURIComponent(countryId));
    $('#languageIdPreview').val('').val(decodeURIComponent(languageId));
    $('#languageCodePreview').val('').val(decodeURIComponent(languageCode));
    $('#systemSettingDateFormatPreview').val('').val(decodeURIComponent(systemSettingDateFormat));
    $('#systemSettingTimeFormatPreview').val('').val(decodeURIComponent(systemSettingTimeFormat));
    $('#systemSettingWeekStartPreview').val('').val(decodeURIComponent(systemSettingWeekStart));
    $('#systemWebsitePreview').val('').val(decodeURIComponent(systemWebsite));
    showMeModal('deletePreview', 1);
}
function updateRecord(leafId, url, urlList, securityToken, type) {
    var $infoPanel = $('#infoPanel');
    var css = $('#updateRecordButton2').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        $infoPanel
                .empty().html('');
        if (type === 1) {
            if ($('#countryId').val().length === 0) {
                $('#countryIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['countryIdLabel'] + " </span>");
                $('#countryId').data('chosen').activate_action();
                return false;
            }
            if ($('#languageId').val().length === 0) {
                $('#languageIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['languageIdLabel'] + " </span>");
                $('#languageId').data('chosen').activate_action();
                return false;
            }
            if ($('#languageCode').val().length === 0) {
                $('#languageCodeHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['languageCodeLabel'] + " </span>");
                $('#languageCodeForm')
                        .removeClass().addClass('form-group has-error');
                $('#languageCode').focus();
                return false;
            }
            if ($('#systemSettingDateFormat').val().length === 0) {
                $('#systemSettingDateFormatHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['systemSettingDateFormatLabel'] + " </span>");
                $('#systemSettingDateFormatForm')
                        .removeClass().addClass('form-group has-error');
                $('#systemSettingDateFormat').focus();
                return false;
            }
            if ($('#systemSettingTimeFormat').val().length === 0) {
                $('#systemSettingTimeFormatHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['systemSettingTimeFormatLabel'] + " </span>");
                $('#systemSettingTimeFormatForm')
                        .removeClass().addClass('form-group has-error');
                $('#systemSettingTimeFormat').focus();
                return false;
            }
            if ($('#systemSettingWeekStart').val().length === 0) {
                $('#systemSettingWeekStartHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['systemSettingWeekStartLabel'] + " </span>");
                $('#systemSettingWeekStartForm')
                        .removeClass().addClass('form-group has-error');
                $('#systemSettingWeekStart').focus();
                return false;
            }
            if ($('#systemWebsite').val().length === 0) {
                $('#systemWebsiteHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['systemWebsiteLabel'] + " </span>");
                $('#systemWebsiteForm')
                        .removeClass().addClass('form-group has-error');
                $('#systemWebsite').focus();
                return false;
            }
            $infoPanel
                    .html('').empty();
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'save',
                    output: 'json',
                    systemSettingId: $('#systemSettingId').val(),
                    countryId: $('#countryId').val(),
                    languageId: $('#languageId').val(),
                    languageCode: $('#languageCode').val(),
                    systemSettingDateFormat: $('#systemSettingDateFormat').val(),
                    systemSettingTimeFormat: $('#systemSettingTimeFormat').val(),
                    systemSettingWeekStart: $('#systemSettingWeekStart').val(),
                    systemWebsite: $('#systemWebsite').val(),
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel
                            .html('').empty()
                            .html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                success: function(data) {
                    var $infoPanel = $('#infoPanel');
                    var success = data.success;
                    var message = data.message;
                    var smileyLol = './images/icons/smiley-lol.png';
                    if (success === true) {
                        $infoPanel
                                .html('').empty()
                                .html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['updateRecordTextLabel']) + "</span>");

                    } else if (success === false) {
                        $infoPanel.empty().html("<span class='label label-important'>&nbsp;" + message + "</span>");
                        if ($infoPanel.is(':hidden')) {
                            $infoPanel.show();
                        }
                    }
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError')
                            .html('').empty()
                            .html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid')
                            .removeClass().addClass('row-fluid');
                }
            });
        } else if (type === 3) {
            // update record and listing
            if ($('#countryId').val().length === 0) {
                $('#countryIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['countryIdLabel'] + " </span>");
                $('#countryId').data('chosen').activate_action();
                return false;
            }
            if ($('#languageId').val().length === 0) {
                $('#languageIdHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['languageIdLabel'] + " </span>");
                $('#languageId').data('chosen').activate_action();
                return false;
            }
            if ($('#languageCode').val().length === 0) {
                $('#languageCodeHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['languageCodeLabel'] + " </span>");
                $('#languageCodeForm').removeClass().addClass('form-group has-error');
                $('#languageCode').focus();
                return false;
            }
            if ($('#systemSettingDateFormat').val().length === 0) {
                $('#systemSettingDateFormatHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['systemSettingDateFormatLabel'] + " </span>");
                $('#systemSettingDateFormatForm').removeClass().addClass('form-group has-error');
                $('#systemSettingDateFormat').focus();
                return false;
            }
            if ($('#systemSettingTimeFormat').val().length === 0) {
                $('#systemSettingTimeFormatHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['systemSettingTimeFormatLabel'] + " </span>");
                $('#systemSettingTimeFormatForm').removeClass().addClass('form-group has-error');
                $('#systemSettingTimeFormat').focus();
                return false;
            }
            if ($('#systemSettingWeekStart').val().length === 0) {
                $('#systemSettingWeekStartHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['systemSettingWeekStartLabel'] + " </span>");
                $('#systemSettingWeekStartForm').removeClass().addClass('form-group has-error');
                $('#systemSettingWeekStart').focus();
                return false;
            }
            if ($('#systemWebsite').val().length === 0) {
                $('#systemWebsiteHelpMe')
                        .html('').empty()
                        .html("<span class='label label-important'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['systemWebsiteLabel'] + " </span>");
                $('#systemWebsiteForm').removeClass().addClass('form-group has-error');
                $('#systemWebsite').focus();
                return false;
            }
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    method: 'save',
                    output: 'json',
                    systemSettingId: $('#systemSettingId').val(),
                    countryId: $('#countryId').val(),
                    languageId: $('#languageId').val(),
                    languageCode: $('#languageCode').val(),
                    systemSettingDateFormat: $('#systemSettingDateFormat').val(),
                    systemSettingTimeFormat: $('#systemSettingTimeFormat').val(),
                    systemSettingWeekStart: $('#systemSettingWeekStart').val(),
                    systemWebsite: $('#systemWebsite').val(),
                    securityToken: securityToken,
                    leafId: leafId
                },
                beforeSend: function() {
                    var smileyRoll = './images/icons/smiley-roll.png';
                    var $infoPanel = $('#infoPanel');
                    $infoPanel
                            .html('').empty()
                            .html("<span class='label label-warning'>&nbsp;<img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                success: function(data) {
                    var $infoPanel = $('#infoPanel');
                    var success = data.success;
                    var message = data.message;
                    var smileyLol = './images/icons/smiley-lol.png';
                    if (success === true) {
                        $infoPanel.html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                        showGrid(leafId, urlList, securityToken, 0, 10, 1);
                    } else if (success === false) {
                        $infoPanel
                                .html('').empty()
                                .html("<span class='label label-important'>&nbsp;" + message + "</span>");
                    }
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                },
                error: function(xhr) {
                    var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
                    $('#infoError')
                            .html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                    $('#infoErrorRowFluid')
                            .removeClass().addClass('row-fluid');
                }
            });
        }
    }
}
function resetRecord() {
    var $infoPanel = $('#infoPanel');
    var resetIcon = './images/icons/fruit-orange.png';
    $infoPanel
            .html('').empty()
            .html("<span class='label label-important'><img src='" + resetIcon + "'> " + decodeURIComponent(t['resetRecordTextLabel']) + "</span>").delay(1000).fadeOut();
    if ($infoPanel.is(':hidden')) {
        $infoPanel.show();
    }
    $('#updateRecordButton1')
            .removeClass().addClass('btn btn-info disabled')
            .attr('onClick', '');
    $('#updateRecordButton2')
            .removeClass().addClass('btn dropdown-toggle btn-info disabled')
            .attr('onClick', '');
    $('#updateRecordButton3').attr('onClick', '');
    $('#updateRecordButton4').attr('onClick', '');
    $('#updateRecordButton5').attr('onClick', '');
    $('#deleteRecordButton')
            .removeClass().addClass('btn btn-danger disabled')
            .attr('onClick', '');
    $('#postRecordButton')
            .removeClass().addClass('btn btn-info')
            .attr('onClick', '');
    $("#systemSettingId").val('');
    $("#systemSettingIdHelpMe")
            .empty().html('');
    
    $("#countryId").val('');
    $("#countryIdHelpMe")
            .empty().html('');
    $('#countryId').trigger("chosen:updated");
    $("#languageId").val('');
    $("#languageIdHelpMe")
            .empty().html('');
    $('#languageId').trigger("chosen:updated");
    $("#languageCode").val('');
    $("#languageCodeHelpMe")
            .empty().html('');
    $("#systemSettingDateFormat").val('');
    $("#systemSettingDateFormatHelpMe")
            .empty().html('');
    $("#systemSettingTimeFormat").val('');
    $("#systemSettingTimeFormatHelpMe")
            .empty().html('');
    $("#systemSettingWeekStart").val('');
    $("#systemSettingWeekStartHelpMe")
            .empty().html('');
    $("#systemWebsite").val('');
    $("#systemWebsiteHelpMe")
            .empty().html('');
    $('#systemWebsite')
            .empty()
            .val('');
}