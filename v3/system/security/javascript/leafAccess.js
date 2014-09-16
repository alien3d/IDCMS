function filterForm(leafId, url, securityToken, offset, limit, field) {
    $.ajax({type: 'GET', url: url, data: {offset: offset, limit: limit, method: 'read', output: 'table', securityToken: securityToken, leafId: leafId, applicationId: $("#applicationId").val(), moduleId: $("#moduleId").val(), folderId: $("#folderId").val(), leafIdTemp: $("#leafIdTemp").val(), staffIdTemp: $("#staffIdTemp").val(), filter: field}, beforeSend: function() {
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
                    $('#folderId').html('').empty().attr('disabled', 'disabled');
                    $('#leafIdTemp').html('').empty().attr('disabled', 'disabled');
                } else if (field === 'folderId') {
                    $('#leafIdTemp').html('').empty().attr('disabled', 'disabled');
                }
                $('#' + field).html('').empty().html(data).removeAttr('disabled').trigger("chosen:updated");
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
    $.ajax({type: 'POST', url: url, data: {offset: offset, limit: limit, method: 'read', output: 'table', securityToken: securityToken, leafId: leafId, applicationId: $("#applicationId").val(), moduleId: $("#moduleId").val(), folderId: $("#folderId").val(), leafIdTemp: $("#leafIdTemp").val(), staffIdTemp: $("#staffIdTemp").val()}, beforeSend: function() {
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
                $('#tableBody').html('').empty().html(data.data);
				$infoPanel.empty().html('').html("<span class=\'label label-success \'><img src='" + smileyLol + "'>" + decodeURIComponent(t['loadingCompleteTextLabel']) + "</span>").delay(1000).fadeOut();
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
    $('input:checkbox[name="leafAccessId[]"]').each(function() {
        stringText = stringText + "&leafAccessId[]=" + $(this).val();
    });
    $('input:checkbox[name="leafAccessDraftValue[]"]').each(function() {
        if ($(this).is(':checked')) {
            stringText = stringText + "&leafAccessDraftValue[]=true";
        } else {
            stringText = stringText + "&leafAccessDraftValue[]=false";
        }
    });
    $('input:checkbox[name="leafAccessDefaultValue[]"]').each(function() {
        if ($(this).is(':checked')) {
            stringText = stringText + "&leafAccessDefaultValue[]=true";
        } else {
            stringText = stringText + "&leafAccessDefaultValue[]=false";
        }
    });
    $('input:checkbox[name="leafAccessCreateValue[]"]').each(function() {
        if ($(this).is(':checked')) {
            stringText = stringText + "&leafAccessCreateValue[]=true";
        } else {
            stringText = stringText + "&leafAccessCreateValue[]=false";
        }
    });
    $('input:checkbox[name="leafAccessReadValue[]"]').each(function() {
        if ($(this).is(':checked')) {
            stringText = stringText + "&leafAccessReadValue[]=true";
        } else {
            stringText = stringText + "&leafAccessReadValue[]=false";
        }
    });
    $('input:checkbox[name="leafAccessUpdateValue[]"]').each(function() {
        if ($(this).is(':checked')) {
            stringText = stringText + "&leafAccessUpdateValue[]=true";
        } else {
            stringText = stringText + "&leafAccessUpdateValue[]=false";
        }
    });
    $('input:checkbox[name="leafAccessDeleteValue[]"]').each(function() {
        if ($(this).is(':checked')) {
            stringText = stringText + "&leafAccessDeleteValue[]=true";
        } else {
            stringText = stringText + "&leafAccessDeleteValue[]=false";
        }
    });
    $('input:checkbox[name="leafAccessReviewValue[]"]').each(function() {
        if ($(this).is(':checked')) {
            stringText = stringText + "&leafAccessReviewValue[]=true";
        } else {
            stringText = stringText + "&leafAccessReviewValue[]=false";
        }
    });
    $('input:checkbox[name="leafAccessApprovedValue[]"]').each(function() {
        if ($(this).is(':checked')) {
            stringText = stringText + "&leafAccessApprovedValue[]=true";
        } else {
            stringText = stringText + "&leafAccessApprovedValue[]=false";
        }
    });
    $('input:checkbox[name="leafAccessPostValue[]"]').each(function() {
        if ($(this).is(':checked')) {
            stringText = stringText + "&leafAccessPostValue[]=true";
        } else {
            stringText = stringText + "&leafAccessPostValue[]=false";
        }
    });
    $('input:checkbox[name="leafAccessPrintValue[]"]').each(function() {
        if ($(this).is(':checked')) {
            stringText = stringText + "&leafAccessPrintValue[]=true";
        } else {
            stringText = stringText + "&leafAccessPrintValue[]=false";
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
				$infoPanel.html('<span class=\"label label-success\">' + message + "</span>");
                //showGrid(leafId, urlList, securityToken, 0, 99999, t['updateRecordTextLabel']);
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