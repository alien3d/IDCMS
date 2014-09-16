function filterForm(leafId, url, securityToken, offset, limit, field) {
    $.ajax({type: 'GET', url: url, data: {offset: offset, limit: limit, method: 'read', output: 'table', securityToken: securityToken, leafId: leafId, applicationId: $("#applicationId").val(), moduleId: $("#moduleId").val(), folderId: $("#folderId").val(), leafIdTemp: $("#leafIdTemp").val(), roleId: $("#roleId").val(), filter: field}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var success = data.success;
            var message = data.message;
            var $infoPanel = $('#infoPanel');
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            if (success === false) {
                $('#centerViewport').html('').empty().html("<span class=\"label label-success\"><img src='" + smileyRoll + "'>" + message + "</span>");
            } else {
                if (field === 'moduleId') {
                    $('#folderId').html('').empty();
                    $('#leafIdTemp').html('').empty();
                } else if (field === 'folderId') {
                    $('#leafIdTemp').html('').empty();
                }
                $('#' + field).html('').empty().html(data).trigger("chosen:updated").removeAttr('disabled');
                $infoPanel.html('').empty().html("<span class=\"label label-success\"><img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
        }});
}
function filterGrid(leafId, url, securityToken, offset, limit) {
    $.ajax({type: 'POST', url: url, data: {offset: offset, limit: limit, method: 'read', output: 'table', securityToken: securityToken, leafId: leafId, applicationId: $("#applicationId").val(), moduleId: $("#moduleId").val(), folderId: $("#folderId").val(), leafIdTemp: $("#leafIdTemp").val(), roleId: $("#roleId").val()}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var success = data.success;
            var message = data.message;
            var $infoPanel = $('#infoPanel');
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png'
            if (success === false) {
                $('#centerViewport').html('').empty().html("<span class=\"label label-success\"><img src='" + smileyRoll + "'>" + message + "</span>");
            } else {
                $('#tableBody').html('').empty().html(data.data).empty().html('').html("<span class=\'label label-success \'><img src='" + smileyLol + "'>" + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
        }});
}
function showGrid(leafId, page, securityToken, offset, limit, message) {
    if (typeof message === "undefined") {
        var message = decodeURIComponent(t['loadingCompleteTextLabel']);
    }
    $.ajax({type: 'POST', url: page, data: {offset: offset, limit: limit, method: 'read', type: 'list', detail: 'body', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var success = data.success;
            var message = data.message;
            var $infoPanel = $('#infoPanel');
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            if (success === false) {
                $('#centerViewport').html('').empty().html("<span class=\"label label-success\"><img src='" + smileyRoll + "'>" + message + "</span>");
            } else {
                $('#centerViewport').html('').empty().append(data);
                $infoPanel.html('').empty().html("<span class=\"label label-success\"><img src='" + smileyLol + "'> " + message + "</span>").delay(1000).fadeOut();
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
        }});
}
function showForm(leafId, url, securityToken) {
    sleep(500);
    $.ajax({type: 'POST', url: url, data: {method: 'new', type: 'form', securityToken: securityToken, leafId: leafId}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var success = data.success;
            var message = data.message;
            var $infoPanel = $('#infoPanel');
            var $centerViewPort = $('#centerViewport');
            var smileyRoll = './images/icons/smiley-roll.png';
            var smileyLol = './images/icons/smiley-lol.png';
            if (success === false) {
                $centerViewPort.html('').empty().html("<span class=\"label label-success\"><img src='" + smileyRoll + "'>" + message + "</span>");
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            } else {
                $centerViewPort.html('').empty().append(data);
                $infoPanel.html('').empty().html("<span class=\"label label-success\"><img src='" + smileyLol + "'> " + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
                if ($infoPanel.is(':hidden')) {
                    $infoPanel.show();
                }
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
        }});
}
function updateGridRecordCheckbox(leafId, url, urlList, securityToken) {
    var stringText = '';
    $('input:checkbox[name="leafRoleAccessId[]"]').each(function() {
        stringText = stringText + "&leafRoleAccessId[]=" + $(this).val();
    });
    $('input:checkbox[name="leafRoleAccessDraftValue[]"]').each(function() {
        if ($(this).is(':checked')) {
            stringText = stringText + "&leafRoleAccessDraftValue[]=true";
        } else {
            stringText = stringText + "&leafRoleAccessDraftValue[]=false";
        }
    });
    $('input:checkbox[name="leafRoleAccessDefaultValue[]"]').each(function() {
        if ($(this).is(':checked')) {
            stringText = stringText + "&leafRoleAccessDefaultValue[]=true";
        } else {
            stringText = stringText + "&leafRoleAccessDefaultValue[]=false";
        }
    });
    $('input:checkbox[name="leafRoleAccessCreateValue[]"]').each(function() {
        if ($(this).is(':checked')) {
            stringText = stringText + "&leafRoleAccessCreateValue[]=true";
        } else {
            stringText = stringText + "&leafRoleAccessCreateValue[]=false";
        }
    });
    $('input:checkbox[name="leafRoleAccessReadValue[]"]').each(function() {
        if ($(this).is(':checked')) {
            stringText = stringText + "&leafRoleAccessReadValue[]=true";
        } else {
            stringText = stringText + "&leafRoleAccessReadValue[]=false";
        }
    });
    $('input:checkbox[name="leafRoleAccessUpdateValue[]"]').each(function() {
        if ($(this).is(':checked')) {
            stringText = stringText + "&leafRoleAccessUpdateValue[]=true";
        } else {
            stringText = stringText + "&leafRoleAccessUpdateValue[]=false";
        }
    });
    $('input:checkbox[name="leafRoleAccessDeleteValue[]"]').each(function() {
        if ($(this).is(':checked')) {
            stringText = stringText + "&leafRoleAccessDeleteValue[]=true";
        } else {
            stringText = stringText + "&leafRoleAccessDeleteValue[]=false";
        }
    });
    $('input:checkbox[name="leafRoleAccessReviewValue[]"]').each(function() {
        if ($(this).is(':checked')) {
            stringText = stringText + "&leafRoleAccessReviewValue[]=true";
        } else {
            stringText = stringText + "&leafRoleAccessReviewValue[]=false";
        }
    });
    $('input:checkbox[name="leafRoleAccessApprovedValue[]"]').each(function() {
        if ($(this).is(':checked')) {
            stringText = stringText + "&leafRoleAccessApprovedValue[]=true";
        } else {
            stringText = stringText + "&leafRoleAccessApprovedValue[]=false";
        }
    });
    $('input:checkbox[name="leafRoleAccessPostValue[]"]').each(function() {
        if ($(this).is(':checked')) {
            stringText = stringText + "&leafRoleAccessPostValue[]=true";
        } else {
            stringText = stringText + "&leafRoleAccessPostValue[]=false";
        }
    });
    $('input:checkbox[name="leafRoleAccessPrintValue[]"]').each(function() {
        if ($(this).is(':checked')) {
            stringText = stringText + "&leafRoleAccessPrintValue[]=true";
        } else {
            stringText = stringText + "&leafRoleAccessPrintValue[]=false";
        }
    });
    url = url + "?securityMayhem=tingting" + stringText;
    $.ajax({type: 'GET', url: url, data: {method: 'update', output: 'json', securityToken: securityToken, leafId: leafId, leafIdTemp: $("#leafIdTemp").val()}, beforeSend: function() {
            var smileyRoll = './images/icons/smiley-roll.png';
            var $infoPanel = $('#infoPanel');
            $infoPanel.html('').empty().html("<span class='label label-warning'><img src='" + smileyRoll + "'>&nbsp;" + decodeURIComponent(t['loadingTextLabel']) + "....</span>");
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, success: function(data) {
            var success = data.success;
            var message = data.message;
            var $infoPanel = $('#infoPanel');
            if (success === true) {
                showGrid(leafId, urlList, securityToken, 0, 99999, t['updateRecordTextLabel']);
            } else if (data.success === false) {
                $infoPanel.html('<span class=\"label label-success\">' + message + "</span>");
            } else {
                $infoPanel.html('<span class=\"label label-success\">' + message + "</span>");
            }
            if ($infoPanel.is(':hidden')) {
                $infoPanel.show();
            }
        }, error: function(xhr) {
            var smileyRollSweat = './images/icons/smiley-roll-sweat.png';
            $('#infoError').html('').empty().html("<span class='alert alert-error col-md-12'><img src='" + smileyRollSweat + "'><strong>" + xhr.status + "</strong> : " + decodeURIComponent(t['loadingErrorTextLabel']) + "</span>");
            $('#infoErrorRowFluid').removeClass().addClass('row');
        }});
}