function saveRecord(leafId, url, urlList, securityToken) {
    if (parseInt($("#paymentVoucherId").val()) > 0) {
        updateRecord(leafId, url, urlList, securityToken, 3);
    } else {
        newRecord(leafId, url, urlList, securityToken, 5);
    }
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
            var $infoPanel = $("#infoPanel");
            $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        },
        success: function(data) {
            var $infoPanel = $("#infoPanel");
            if (data.success === false) {
                $('#centerViewport').empty().html('').html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
            } else {
                $('#centerViewport').empty().html('').append(data);
                $infoPanel.empty().html('');
                if (type === 1) {
                    $infoPanel.html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                } else if (type === 2) {
                    $infoPanel.html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['deleteRecordTextLabel']) + "</span>");
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
            var $infoPanel = $("#infoPanel");
            $infoPanel.empty().html('').html("<span class='label label-warning'><img src='./images/icons/smiley-roll.png'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
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
                $infoPanel.empty().html('').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
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
function showFormUpdate(leafId, url, securityToken, paymentVoucherId) {
    sleep(500);
    $.ajax({
        type: 'POST',
        url: url,
        data: {
            method: 'read',
            type: 'form',
            paymentVoucherId: paymentVoucherId,
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
            if (data.success === false) {
                $('#centerViewport').empty().html('').html("<span class='label label-danger'>&nbsp;<img src='./images/icons/smiley-roll.png'> " + data.message + "</span>");
            } else {
                $('#centerViewport').empty().html('').append(data);
                $infoPanel.empty().html('').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
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
function newRecord(leafId, url, urlList, securityToken, type) {
    var $bankId = $('#bankId');
    var $chartOfAccountId = $('#chartOfAccountId');
    var $businessPartnerId = $('#businessPartnerId');
    var $paymentVoucherDescription = $('#paymentVoucherDescription');
    var $paymentVoucherDate = $('#paymentVoucherDate');
    var $paymentVoucherAmount = $('#paymentVoucherAmount');
    if (type === 1) {
        if ($bankId.val().length === 0) {
            $('#bankIdHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['bankIdLabel'] + " </span>");
            $bankId.focus();
            return false;
        }
        if ($chartOfAccountId.val().length === 0) {
            $('#chartOfAccountIdHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + t['paidToTextLabel'] + " </span>");
            $chartOfAccountId.focus();
            return false;
        }
        if ($businessPartnerId.val().length === 0) {
            $('#businessPartnerIdHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
            $businessPartnerId.focus();
            return false;
        }
        if ($paymentVoucherDescription.val().length === 0) {
            $('#paymentVoucherDescriptionHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDescriptionLabel'] + " </span>");
            $('#paymentVoucherDescriptionForm').addClass('form-group has-error');
            $paymentVoucherDescription.focus();
            return false;
        }
        if ($paymentVoucherDate.val().length === 0) {
            $('#paymentVoucherDateHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDateLabel'] + " </span>");
            $('#paymentVoucherDateForm').addClass('form-group has-error');
            $paymentVoucherDate.focus();
            return false;
        }
        if ($paymentVoucherAmount.val().length === 0) {
            $('#paymentVoucherAmountHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherAmountLabel'] + " </span>");
            $('#paymentVoucherAmountForm').addClass('form-group has-error');
            $paymentVoucherAmount.focus();
            return false;
        }
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                method: 'create',
                output: 'json',
                bankId: $bankId.val(),
                chartOfAccountId: $chartOfAccountId.val(),
                businessPartnerId: $businessPartnerId.val(),
                paymentVoucherDescription: $paymentVoucherDescription.val(),
                paymentVoucherDate: $paymentVoucherDate.val(),
                paymentVoucherAmount: $paymentVoucherAmount.val(),
                from: 'paymentVoucherMobile.php',
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
                    $infoPanel.empty().html('').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>").delay(1000).fadeOut();
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                    $businessPartnerId.val('');
                    $('#businessPartnerIdHelpMe').empty().html('');
                    $paymentVoucherDescription.val('');
                    $('#paymentVoucherDescriptionHelpMe').empty().html('');
                    $paymentVoucherDate.val('');
                    $('#paymentVoucherDateHelpMe').empty().html('');
                    $paymentVoucherAmount.val('');
                    $('#paymentVoucherAmountHelpMe').empty().html('');
                } else if (data.success === false) {
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
    } else if (type === 2) {
        if ($bankId.val().length === 0) {
            $('#bankIdHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['bankIdLabel'] + " </span>");
            $bankId.focus();
            return false;
        }
        if ($chartOfAccountId.val().length === 0) {
            $('#chartOfAccountIdHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + t['paidToTextLabel'] + " </span>");
            $chartOfAccountId.focus();
            return false;
        }
        if ($businessPartnerId.val().length === 0) {
            $('#businessPartnerIdHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
            $businessPartnerId.focus();
            return false;
        }
        if ($paymentVoucherDescription.val().length === 0) {
            $('#paymentVoucherDescriptionHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDescriptionLabel'] + " </span>");
            $('#paymentVoucherDescriptionForm').addClass('form-group has-error');
            $paymentVoucherDescription.focus();
            return false;
        }
        if ($paymentVoucherDate.val().length === 0) {
            $('#paymentVoucherDateHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDateLabel'] + " </span>");
            $('#paymentVoucherDateForm').addClass('form-group has-error');
            $paymentVoucherDate.focus();
            return false;
        }
        if ($paymentVoucherAmount.val().length === 0) {
            $('#paymentVoucherAmountHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherAmountLabel'] + " </span>");
            $('#paymentVoucherAmountForm').addClass('form-group has-error');
            $paymentVoucherAmount.focus();
            return false;
        }
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                method: 'create',
                output: 'json',
                bankId: $bankId.val(),
                chartOfAccountId: $chartOfAccountId.val(),
                businessPartnerId: $businessPartnerId.val(),
                paymentVoucherDescription: $paymentVoucherDescription.val(),
                paymentVoucherDate: $paymentVoucherDate.val(),
                paymentVoucherAmount: $paymentVoucherAmount.val(),
                from: 'paymentVoucherMobile.php',
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
                    $infoPanel.empty().html('').html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['newRecordTextLabel']) + "</span>");
                    $('#paymentVoucherId').val(data.paymentVoucherId);
                }
            },
            error: function(xhr) {
                $('#infoError').empty().html('').html("<span class='alert alert-error col-md-12'><img src='./images/icons/smiley-roll-sweat.png'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
                $('#infoErrorRowFluid').removeClass().addClass('row');
            }
        });
    } else if (type === 5) {
        if ($bankId.val().length === 0) {
            $('#bankIdHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['bankIdLabel'] + " </span>");
            $bankId.focus();
            return false;
        }
        if ($chartOfAccountId.val().length === 0) {
            $('#chartOfAccountIdHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + t['paidToTextLabel'] + " </span>");
            $chartOfAccountId.focus();
            return false;
        }
        if ($businessPartnerId.val().length === 0) {
            $('#businessPartnerIdHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
            $businessPartnerId.val();
            return false;
        }
        if ($paymentVoucherDescription.val().length === 0) {
            $('#paymentVoucherDescriptionHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDescriptionLabel'] + " </span>");
            $('#paymentVoucherDescriptionForm').addClass('form-group has-error');
            $paymentVoucherDescription.focus();
            return false;
        }
        if ($paymentVoucherDate.val().length === 0) {
            $('#paymentVoucherDateHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDateLabel'] + " </span>");
            $('#paymentVoucherDateForm').addClass('form-group has-error');
            $paymentVoucherDate.focus();
            return false;
        }
        if ($paymentVoucherAmount.val().length === 0) {
            $('#paymentVoucherAmountHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherAmountLabel'] + " </span>");
            $('#paymentVoucherAmountForm').addClass('form-group has-error');
            $paymentVoucherAmount.focus();
            return false;
        }
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                method: 'create',
                output: 'json',
                bankId: $bankId.val(),
                chartOfAccountId: $chartOfAccountId.val(),
                businessPartnerId: $businessPartnerId.val(),
                paymentVoucherDescription: $paymentVoucherDescription.val(),
                paymentVoucherDate: $paymentVoucherDate.val(),
                paymentVoucherAmount: $paymentVoucherAmount.val(),
                from: 'paymentVoucherMobile.php',
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
                if (data.success === true) {
                    showGrid(leafId, urlList, securityToken, 0, 10, 1);
                } else {
                    var $infoPanel = $("#infoPanel");
                    $infoPanel.empty().html('').html("<span class='label label-danger'> <img src='./images/icons/smiley-roll-sweat.png'> " + data.message + "</span>");
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
    showMeDiv('tableDate', 0);
    showMeDiv('formEntry', 1);
}
function updateRecord(leafId, url, urlList, securityToken, type) {
    var $bankId = $('#bankId');
    var $chartOfAccountId = $('#chartOfAccountId');
    var $paymentVoucherId = $('#paymentVoucherId');
    var $businessPartnerId = $('#businessPartnerId');
    var $paymentVoucherDescription = $('#paymentVoucherDescription');
    var $paymentVoucherDate = $('#paymentVoucherDate');
    var $paymentVoucherAmount = $('#paymentVoucherAmount');
    if (type === 1) {
        if ($bankId.val().length === 0) {
            $('#bankIdHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['bankIdLabel'] + " </span>");
            $bankId.focus();
            return false;
        }
        if ($chartOfAccountId.val().length === 0) {
            $('#chartOfAccountIdHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + t['paidToTextLabel'] + " </span>");
            $chartOfAccountId.focus();
            return false;
        }
        if ($businessPartnerId.val().length === 0) {
            $('#businessPartnerIdHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
            $businessPartnerId.val('');
            return false;
        }
        if ($paymentVoucherDescription.val().length === 0) {
            $('#paymentVoucherDescriptionHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDescriptionLabel'] + " </span>");
            $('#paymentVoucherDescriptionForm').addClass('form-group has-error');
            $paymentVoucherDescription.focus();
            return false;
        }
        if ($paymentVoucherDate.val().length === 0) {
            $('#paymentVoucherDateHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDateLabel'] + " </span>");
            $('#paymentVoucherDateForm').addClass('form-group has-error');
            $paymentVoucherDate.focus();
            return false;
        }
        if ($paymentVoucherAmount.val().length === 0) {
            $('#paymentVoucherAmountHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherAmountLabel'] + " </span>");
            $('#paymentVoucherAmountForm').addClass('form-group has-error');
            $paymentVoucherAmount.focus();
            return false;
        }
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                method: 'save',
                output: 'json',
                bankId: $bankId.val(),
                chartOfAccountId: $chartOfAccountId.val(),
                paymentVoucherId: $paymentVoucherId.val(),
                businessPartnerId: $businessPartnerId.val(),
                paymentVoucherDescription: $paymentVoucherDescription.val(),
                paymentVoucherDate: $paymentVoucherDate.val(),
                paymentVoucherAmount: $paymentVoucherAmount.val(),
                from: 'paymentVoucherMobile.php',
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
                    $infoPanel.empty().html('').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
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
    } else if (type === 3) {
        if ($bankId.val().length === 0) {
            $('#bankIdHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['bankIdLabel'] + " </span>");
            $bankId.focus();
            return false;
        }
        if ($chartOfAccountId.val().length === 0) {
            $('#chartOfAccountIdHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + t['paidToTextLabel'] + " </span>");
            $chartOfAccountId.focus();
            return false;
        }
        if ($businessPartnerId.val().length === 0) {
            $('#businessPartnerIdHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['businessPartnerIdLabel'] + " </span>");
            $businessPartnerId.val('');
            return false;
        }
        if ($paymentVoucherDescription.val().length === 0) {
            $('#paymentVoucherDescriptionHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDescriptionLabel'] + " </span>");
            $('#paymentVoucherDescriptionForm').addClass('form-group has-error');
            $paymentVoucherDescription.focus();
            return false;
        }
        if ($paymentVoucherDate.val().length === 0) {
            $('#paymentVoucherDateHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherDateLabel'] + " </span>");
            $('#paymentVoucherDateForm').addClass('form-group has-error');
            $paymentVoucherDate.focus();
            return false;
        }
        if ($paymentVoucherAmount.val().length === 0) {
            $('#paymentVoucherAmountHelpMe').empty().html("<span class='label label-danger'>&nbsp;" + decodeURIComponent(t['requiredTextLabel']) + " : " + leafTranslation['paymentVoucherAmountLabel'] + " </span>");
            $('#paymentVoucherAmountForm').addClass('form-group has-error');
            $paymentVoucherAmount.focus();
            return false;
        }
        $.ajax({
            type: 'POST',
            url: url,
            data: {
                method: 'save',
                output: 'json',
                bankId: $bankId.val(),
                chartOfAccountId: $chartOfAccountId.val(),
                paymentVoucherId: $paymentVoucherId.val(),
                businessPartnerId: $businessPartnerId.val(),
                paymentVoucherDescription: $paymentVoucherDescription.val(),
                paymentVoucherDate: $paymentVoucherDate.val(),
                paymentVoucherAmount: $paymentVoucherAmount.val(),
                from: 'paymentVoucherMobile.php',
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
                    $infoPanel.html("<span class='label label-success'>&nbsp;<img src='./images/icons/smiley-lol.png'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                    if ($infoPanel.is(':hidden')) {
                        $infoPanel.show();
                    }
                    showGrid(leafId, urlList, securityToken, 0, 10, 1);
                } else if (data.success === false) {
                    $infoPanel.empty().html('').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
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
function deleteRecord(leafId, url, urlList, securityToken) {
    var css = $('#deleteRecordButton').attr('class');
    if (css.search('disabled') > 0) {
        return false;
    } else {
        if (confirm(decodeURIComponent(t['deleteRecordMessageLabel']))) {
            var $paymentVoucher = $('#paymentVoucherId');
            if (!$paymentVoucher.val()) {
                var $infoPanel = $("#infoPanel");
                $infoPanel.empty().html('').html("<span class='label label-danger'> " + decodeURIComponent(t['loadingErrorTextLabel']) + "<span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
                return false;
            } else {
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        method: 'delete',
                        output: 'json',
                        paymentVoucherId: $paymentVoucher.val(),
                        from: 'paymentVoucherMobile.php',
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
                            showGrid(leafId, urlList, securityToken, 0, 10, 2);
                        } else if (data.success === false) {
                            $infoPanel.empty().html('').html("<span class='label label-danger'>&nbsp;" + data.message + "</span>");
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
        } else {
            return false;
        }
    }
}
function resetRecord() {
    var $infoPanel = $("#infoPanel");
    $infoPanel.empty().html('').html("<span class='label label-danger'><img src='./images/icons/fruit-orange.png'> " + decodeURIComponent(t['resetRecordTextLabel']) + "</span>").delay(1000).fadeOut();
    if ($infoPanel.is(':hidden')) {
        $infoPanel.show();
    }
    $("#paymentVoucherId").val('');
    $("#paymentVoucherIdHelpMe").empty().html('');
    $("#businessPartnerId").val('');
    $("#businessPartnerIdHelpMe").empty().html('');
    $("#paymentVoucherDescription").val('');
    $("#paymentVoucherDescriptionHelpMe").empty().html('');
    $("#paymentVoucherDate").val('');
    $("#paymentVoucherDateHelpMe").empty().html('');
    $("#paymentVoucherAmount").val('');
    $("#paymentVoucherAmountHelpMe").empty().html('');
}
