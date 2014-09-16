function filterBudgetList(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, chartOfAccountCategoryId: $("#chartOfAccountCategoryId").val(), chartOfAccountTypeId: $("#chartOfAccountTypeId").val(), financeYearId: $("#financeYearId").val(), financePeriodRangeId: $("#financePeriodRangeId").val(), filter: 'budgetList'}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#tbody").html('').empty().html(data.data);
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function getFinancePeriodRange(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, financeYearId: $("#financeYearId").val(), leafId: leafId, filter: 'financePeriodRange'}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#financePeriodRangeId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function getChartOfAccountType(leafId, url, securityToken) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', type: 'filter', securityToken: securityToken, leafId: leafId, chartOfAccountCategoryId: $("#chartOfAccountCategoryId").val(), filter: 'chartOfAccountType'}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                $("#chartOfAccountTypeId").html('').empty().html(data.data).trigger("chosen:updated");
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function previewBudgetTransfer(leafId, url, securityToken, chartOfAccountId) {
    $.ajax({type: 'GET', url: url, data: {offset: 0, limit: 99999, method: 'read', financeYearId: $("#financeYearId").val(), financePeriodRangeId: $("#financePeriodRangeId").val(), chartOfAccountId: chartOfAccountId, type: 'filter', securityToken: securityToken, leafId: leafId, filter: 'budgetAmountByYear'}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                if (data.data !== undefined) {
                    if (data.data) {
                        $('#chartOfAccountIdSimplePreview').val(decodeURIComponent(data.data.chartOfAccountNumber) + " - " + decodeURIComponent(data.data.chartOfAccountTitle));
                        $('#chartOfAccountIdDetailPreview').val(decodeURIComponent(data.data.chartOfAccountNumber) + " - " + decodeURIComponent(data.data.chartOfAccountTitle));
                        $('#financeYearIdSimplePreview').val(data.data.financeYearYear);
                        $('#financeYearIdDetailPreview').val(data.data.financeYearYear);
                        $('#budgetTargetMonthOneSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthOne));
                        $('#budgetTargetMonthOneDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthOne));
                        $('#budgetTargetMonthTwoSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthTwo));
                        $('#budgetTargetMonthTwoDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthTwo));
                        $('#budgetTargetMonthThreeSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthThree));
                        $('#budgetTargetMonthThreeDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthThree));
                        $('#budgetTargetMonthFourthSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthFourth));
                        $('#budgetTargetMonthFourthDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthFourth));
                        $('#budgetTargetMonthFifthSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthFifth));
                        $('#budgetTargetMonthFifthDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthFifth));
                        $('#budgetTargetMonthSixSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthSix));
                        $('#budgetTargetMonthSixDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthSix));
                        $('#budgetTargetMonthSevenSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthSeven));
                        $('#budgetTargetMonthSevenDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthSeven));
                        $('#budgetTargetMonthEightSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthEight));
                        $('#budgetTargetMonthEightDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthEight));
                        $('#budgetTargetMonthNineSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthNine));
                        $('#budgetTargetMonthNineDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthNine));
                        $('#budgetTargetMonthTenSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthTen));
                        $('#budgetTargetMonthTenDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthTen));
                        $('#budgetTargetMonthElevenSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthEleven));
                        $('#budgetTargetMonthElevenDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthEleven));
                        $('#budgetTargetMonthTwelveSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthTwelve));
                        $('#budgetTargetMonthTwelveDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthTwelve));
                        $('#budgetTargetMonthThirteenSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthThirteen));
                        $('#budgetTargetMonthThirteenDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthThirteen));
                        $('#budgetTargetMonthFourteenSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthFourteen));
                        $('#budgetTargetMonthFourteenDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthFourteen));
                        $('#budgetTargetMonthFifteenSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthFifteen));
                        $('#budgetTargetMonthFifteenDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthFifteen));
                        $('#budgetTargetMonthSixteenSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthSixteen));
                        $('#budgetTargetMonthSixteenDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthSixteen));
                        $('#budgetTargetMonthSeventeenSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthSeventeen));
                        $('#budgetTargetMonthSeventeenDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthSeventeen));
                        $('#budgetTargetMonthEighteenSimplePreview').val(decodeURIComponent(data.data.budgetTargetMonthEighteen));
                        $('#budgetTargetMonthEighteenDetailPreview').val(decodeURIComponent(data.data.budgetTargetMonthEighteen));
                    }
                }
                if (data.data !== undefined) {
                    if (data.data) {
                        $("#previewMiniTransaction").html('').empty().html(decodeURIComponent(data.data.chartOfAccountNumber) + " - " + decodeURIComponent(data.data.chartOfAccountTitle) + "<br>" + data.miniStatement);
                    } else {
                        $("#previewMiniTransaction").html('').empty().html(decodeURIComponent(data.miniStatement));
                        alert("1");
                    }
                } else {
                    $("#previewMiniTransaction").html('').empty().html(decodeURIComponent(data.miniStatement));
                    alert("2");
                }
                showMeModal('budgetPreview', 1);
                $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(5000).fadeOut();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function updateRecordInline(leafId, url, securityToken, budgetId, budgetFieldName, chartOfAccountId) {
    $.ajax({type: 'POST', url: url, data: {offset: 0, limit: 99999, method: 'checkFirst', financeYearId: $("#financeYearId").val(), financePeriodRangeId: $("#financePeriodRangeId").val(), budgetId: budgetId, chartOfAccountId: chartOfAccountId, budgetFieldName: budgetFieldName, budgetFieldValue: $("#" + budgetFieldName + "_" + budgetId).val(), securityToken: securityToken, output: 'json', leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'> " + decodeURIComponent(t['loadingTextLabel']) + "</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            var $infoPanel = $('#infoPanel');
            var success = data.success;
            var message = data.message;
            if (success === false) {
                $infoPanel.html('').empty().html("<span class='label label-danger'>&nbsp;<img src='" + smileyRoll + "'>" + message + "</spam>");
            } else {
                if (budgetId) {
                    $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['updateRecordTextLabel']) + "</span>").delay(5000).fadeOut();
                } else {
                    filterBudgetList(leafId, url, securityToken);
                    $infoPanel.html('').empty().html("<span class='label label-success'>&nbsp;<img src='" + smileyLol + "'>  " + decodeURIComponent(t['createRecordTextLabel']) + "</span>").delay(5000).fadeOut();
                }
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row-fluid');
        }});
}
function toggleBudget(val) {
    if (val === 1) {
        showMeDiv('previewMiniTransaction', 0);
        showMeDiv('previewSimple', 1);
        showMeDiv('previewDetail', 0);
    } else if (val === 2) {
        showMeDiv('previewMiniTransaction', 0);
        showMeDiv('previewSimple', 0);
        showMeDiv('previewDetail', 1);
    } else if (val === 3) {
        showMeDiv('previewMiniTransaction', 1);
        showMeDiv('previewSimple', 0);
        showMeDiv('previewDetail', 0);
    }
}