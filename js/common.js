jQuery.noConflict();
jQuery(document).ready(function() {

    jQuery('.menu').menu({ position: { at: "right+2 top+0" } });

    windowResize();

    jQuery(window).resize(function() {
        windowResize();
    });
    
    jQuery('input.fp-ui-spinner').spinner();    
    jQuery('.fp-ui-button').button();
    jQuery('.fp-ui-buttonset').buttonset(); 
    jQuery("select.chosen-select").chosen({ disable_search: true, inherit_select_classes: true });
    jQuery("select.chosen-select-newsactions").chosen({
        disable_search: true,
        inherit_select_classes: true
    }).change( function() {
        var submitYes = confirm(fpNewsListActionConfirmMsg);
        if(submitYes) {
            showLoader(true);
            jQuery('#newseditform').submit();
        } else { return false; }
    });
    
    jQuery("select.chosen-select-dialog").chosen({ width: "100%", disable_search: true, inherit_select_classes: true });
   
    jQuery('.fp-ui-jqselect').selectmenu({        
        change: function( event, ui ) {            
            if(jQuery(this).attr('id') == 'usemode') {
                jQuery('#iframecss').fadeToggle();
                jQuery('#jqueryinc').fadeToggle();
            }
        }
    });

    jQuery('.hidenav').click(function() {         
        if(!jQuery('.menu li').hasClass('fp-ui-smallnav')) {
            jQuery('.menu li').addClass('fp-ui-smallnav');
            jQuery('.menu li').addClass('admin-navi-ui-menu-item-a-hidden');            
            jQuery('.menu .submenu li a').removeClass('admin-navi-ui-menu-item-a-hidden');
            jQuery('.navlinkdescr').css('display', 'none');
            jQuery('.hidenav i').addClass('fa-rotate-180');
            jQuery('.content-wrapper').addClass('content-wrapper-smallnav');
        } else {
            jQuery('.menu li').removeClass('fp-ui-smallnav');
            jQuery('.menu li').removeClass('admin-navi-ui-menu-item-a-hidden');
            jQuery('.navlinkdescr').css('display', 'block');
            jQuery('.hidenav i').removeClass('fa-rotate-180')
            jQuery('.content-wrapper').removeClass('content-wrapper-smallnav');
        }
    });
    
    jQuery('.admin-nav-noclick').click(function() { return false; });
    
    jQuery('#profile-menu').menu();
    jQuery('#profile-menu-open').button({
        icons: {
            primary: "ui-icon-info",
            secondary: "ui-icon-triangle-1-s"
        },
        text: true
    }).click(function() {
        jQuery('#profile-dialog-layer').dialog({
            width: 500,
            modal: true,
            resizable: false,
            title: jsFPdialogProfile,
            buttons: [
                {
                    text: jsFPdialogProfileOpen,
                    icons: { primary: "ui-icon-wrench" },                  
                    click: function() {
                        showLoader(true);
                        window.location.href = jsFProotPath + 'acp/profile.php';
                    }
                },
                {
                    text: jsFPdialogLogout,
                    icons: { primary: "ui-icon-power" },
                    click: function() {
                        showLoader(true);
                        window.location.href = jsFProotPath + 'logout.php';
                    }
                }
            ]
        });
    });
    
    jQuery('#profile').button({
        icons: {
            primary: "ui-icon-wrench",
        },
        text: false
    });
    jQuery('#logout').button({
        icons: {
            primary: "ui-icon-power",
        },
        text: false
    });     

    jQuery( "#postponeinputpicker" ).datepicker({
        dateFormat: "dd.mm.yy",
        firstDay: 1,
        maxDate: "+2m",
        minDate: "-0d"
    }); 
    
    jQuery( "#news-search-input-time-from" ).datepicker({
        dateFormat: "dd.mm.yy",
        firstDay: 1
    });
    
    jQuery( "#news-search-input-time-to" ).datepicker({
        dateFormat: "dd.mm.yy",
        firstDay: 1
    });    

    jQuery('#openPostponInput').click(function(){
        jQuery('#postponeinput').fadeToggle('medium');
    });      

    jQuery('#update_details-button').click(function() {
        jQuery('#update_details').toggle('medium');
    }); 

    jQuery('#open-news').button({
        icons: {
            primary: "ui-icon-circle-triangle-e",
        },
        text: false
    });
    
    jQuery('.news-list-row-news-open-link').button({
        icons: {
            primary: "ui-icon-circle-triangle-e",
        },
        text: false
    });    

    jQuery('.fp-reload-btn').button({
        icons: {
            primary: "ui-icon-refresh",
        },
        text: false
    });

    jQuery('#clear-cache').button({
        icons: {
            primary: "ui-icon-arrowrefresh-1-e",
        },
        text: false
    }).click(function() {
        showLoader(true);
        jQuery.ajax({
            url: jsFProotPath + "acp/ajax/clearcache.php",
            type: "POST",
            success: function() {
                showLoader(false);                
            }
        });
        return false;
    });

    jQuery('.btnloader').click(function() {
        showLoader(true);
    });       
    
    jQuery('#fp-filemgr-okbtn').click(function() {
        var submitYes = confirm(fpNewsListActionConfirmMsg);        
        if(submitYes) {
            showLoader(true);
            jQuery('#fp-filemgr-form').submit();
        } else {
            showLoader(false);
            return false;
        }
    });    

    jQuery("#accordionHelp").accordion({
        header: "h2",
        heightStyle: "content"
    });          

    jQuery( "#tabsGeneral" ).tabs();

    if(typeof availableDTMasks != 'undefined') {
        jQuery("#sysdtmask").autocomplete({
            source: availableDTMasks
        });        
    };
    
    if(typeof jQuery('#fp-extended-dialog').attr('id') != 'undefined') {
        jQuery('.fp-dialog').css('position', 'fixed');
        setDialog();
    }
    
    jQuery("#start-upload-input-old").button({
        icons: {
            primary: "ui-icon-circle-arrow-e"
        },
        text: true
    })

    jQuery(".add-new-upload-input").button({
        icons: {
            primary: "ui-icon-plusthick"
        },
        text: false
    }).click(function(){
        showLoader(true);
        if(maxUploads > 0) {
            jQuery(this).
                parent().
                parent().
                children('.fileInputList').
                children('.fileInputListItemHidden').
                clone().
                appendTo('.fileInputList').
                attr('class','fileInputListItem').
                show().
                children('input').
                attr('name','datei[]').
                addClass('fileUploadBtn');
            
            jQuery('.fileUploadBtn').button({
                icons: {
                    primary: "ui-icon-plusthick"
                }, text: true
            });
        
            maxUploads--;    
        } else {
            jQuery(".add-new-upload-input").hide();
        }
        showLoader(false);
        return false;
    });    

    jQuery('.fp-file-list-img').fancybox();
    jQuery('.fp-tinymce-insert-thumb').click(function() {
        insertImageThumb(this);       
        return false;
    });
    
    jQuery('.fp-tinymce-insert-full').click(function() {
        insertImageFull(this);
        return false;
    });
    
    setFileManagerButtons();
    assignFilesCheckboxes();
    
    jQuery('#tabs-filemanager-list-id').click(function() {
        showLoader(true);
        jQuery.ajax({
            url: jsFProotPath + "acp/filemanager/file-list.php",
            type: "POST",
            data: {
                ajax: true,
                fmmode: jsFmMode
            },            
            success: function(data) {
                showLoader(false);
                jQuery("#tabs-filemanager-list").html(data);
                jQuery("#tabsGeneral").tabs();
                jQuery("select.chosen-select").chosen({ disable_search: true, inherit_select_classes: true });
                jQuery('.fp-ui-button').button();               
                setFileManagerButtons();
                jQuery('.fp-file-list-actions').on('click', '.fp-tinymce-insert-full', function(e) {
                    insertImageFull(jQuery('.fp-tinymce-insert-full'));
                    return false;
                });
                jQuery('.fp-file-list-actions').on('click', '.fp-tinymce-insert-thumb', function(e) {
                    insertImageThumb(jQuery('.fp-tinymce-insert-thumb'));
                    return false;
                });                 
            }
        });
        return false;
    });

    jQuery('.fp-modmgr-btn').click(function() {
        showLoader(true);       
        jQuery.ajax({
            url: jsFProotPath + "acp/modulemgr/" + jQuery(this).attr('href'),
            type: "GET",           
            success: function(data) {
                if(data) {
                    showLoader(false);
                    window.location.href = jsFProotPath + "acp/" + data                    
                }
            }
        });
        showLoader(false);
        return false;
    });
    
    jQuery('.fp-modmgr-spin-hover').hover( function() {
        jQuery(jQuery(this).children().children()).addClass('fa-spin');
    }, function() {
        jQuery(jQuery(this).children().children()).removeClass('fa-spin');
    }); 
    
    jQuery('#sbmlogin').click(function() {
        if(typeof window.btoa == 'function') {
            var data = jQuery('#loginpasswd').val();
            data = window.btoa((new Date()).toLocaleString() + '($$)' + window.btoa(data));
            
            jQuery('#b64').val('1');
            jQuery('#loginpasswd').val(data);
        }
        
        return jQuery(this).parent().submit();
    });
    
    jQuery('.fp-reload-btn-logs').click(function() {
        showLoader(true);       
        
        var logtype = jQuery(this).attr('reload');
        
        jQuery.ajax({
            url: jsFProotPath + "acp/ajax/" + jQuery(this).attr('href'),
            type: "POST",
            data: {
                reload: logtype,
            }, 
            success: function(data) {
                if(data) {
                    jQuery('#logcontent' + logtype).html(data);
                    showLoader(false);
                }
            }
        });
        showLoader(false);
        return false;
    });
    
    if(typeof jsEditCommentHL != 'undefined') {
        jQuery('.fbcommentedit').fancybox({
            type      : 'iframe',
            autoScale : false,
            width     : 725,
            padding   : 5,
            scrolling : 'no',
            enableEscapeButton: true,
            nextEffect : 'fade',
            prevEffect : 'fade',
            wrapCSS   : 'imageUploader',
            title     : jsEditCommentHL
        });         
    }    
    
    initUpdaterFileListButton();
    jQuery(document).tooltip();
});

function assignFilesCheckboxes() {
    jQuery('#fileselectboxall').click(function(){
        if(jQuery('#fileselectboxall').prop('checked'))        
            jQuery('.fileselectbox').prop('checked', true);
        else
            jQuery('.fileselectbox').prop('checked', false);
    });
        
    jQuery('.news-list-row-head-selectall').click(function(){
        var smonth = jQuery(this).attr('smonth');
        
        if(jQuery(this).prop('checked'))        
            jQuery('.fileselectbox' + smonth).prop('checked', true);
        else
            jQuery('.fileselectbox' + smonth).prop('checked', false);
    });
}

function windowResize() {
    jQuery('.admin-navi').css('top', jQuery('#header').height());

    var newheight = jQuery('#header').height() + 2;
    jQuery('#header-top-spacer').height(newheight);

    var loginTopPos = (jQuery(window).height() / 2 - jQuery('.fp-login-form').height() * 0.52);
    jQuery('.fp-login-form').css('margin-top', loginTopPos);    
}

function setDialog() {
    
    jQuery('.fp-dialog .fp-dialog-close').button({ icons: { primary: "ui-icon-closethick" }, text: false });

    jQuery('.fp-dialog .fp-dialog-close').click(function() {            
        jQuery(this).parent().parent().fadeToggle();
        return false;
    });    
}

function setFileManagerButtons() {
    jQuery('.fp-ui-button-filelist').button({
        icons: {
            primary: "ui-icon-newwin"
        },
        text: false        
    }).next().button({
        icons: {
            primary: "ui-icon-arrow-4-diag"
        },
        text: false        
    }).next().button({
        icons: {
            primary: "ui-icon-circle-zoomin"
        },
        text: false        
    }).next().button({
        icons: {
            primary: "ui-icon-image"
        },
        text: false        
    });    
}

function searchNews() {
    if(((new Date()).getTime() - lastnewssearch) < 10000) {
        alert(waitmsg);
        return false;
    }
    
    showLoader(true);
    jQuery.ajax({
        url: jsFProotPath + "acp/ajax/search.php",
        type: "POST",
        data: {
            search: jQuery("#news-search-input").val(),
            authors: jQuery("#filter_authors").val(),
            categories: jQuery("#filter_categories").val(),
            type: jQuery("#news-search-input-type").val(),
            datetimefrom: jQuery("#news-search-input-time-from").val(),
            datetimeto: jQuery("#news-search-input-time-to").val(),
            searchfn: jQuery("#news-search-input-fn").val()
        },
        success: function(data) {
            showLoader(false);
            jQuery("#news-list-box").html(data);
            jQuery("#tabsGeneral").tabs();
            jQuery('.news-list-row-news-open-link').button({
                icons: {
                    primary: "ui-icon-circle-triangle-e",
                },
                text: false
            });
            assignFilesCheckboxes();
        }
    });
    lastnewssearch = (new Date()).getTime();
}

var spinJsOpts = {
    lines: 8,
    length: 0,
    width: 10,
    radius: 15,
    corners: 1,
    rotate: 0,
    direction: 1,
    color: '#000',
    speed: 0.75,
    trail: 10,
    shadow: false,
    hwaccel: true,
    className: 'spinner',
    zIndex: 2e9,
    top: '50%',
    left: '50%'
}

var spinner = new Spinner(spinJsOpts);    

function showLoader(show){
    if(show) { return spinner.spin(document.getElementById('body')); }
    
    return spinner.stop();
}

function relocate(url) {
    window.location.href = url;
}

function showForceUpdateDialog() {
    jQuery('#fpcm-force-update-notice').dialog({
        width: 500,
        height: 100,
        modal: true,
        resizable: false,
        position: { my: "top", at: "top+30%" }
    });
}

function runUpdater() {
    if(!updateConfirmed) {
        jQuery("#updater-info0").html(updaterStart);
        jQuery('.fp-ui-button-updater').button({
            icons: {
                primary: "ui-icon-refresh",
            },
            text: true
        });
        return false;
    }
    
    var updateStart = (new Date().getTime());
    
    jQuery( "#fp-progressbar" ).progressbar({
        max: 5,
        value: 0
    });     
    
    var i = 1;
    var exit = false;
    for(i=1;i<=5;i++) {
        showLoader(true);

        jQuery("#fp-progressbar").progressbar( "option", "value", i );
        if(i==1) {
            jQuery("#updater-info0").html(updaterStartDownload);
        }

        jQuery.ajax({
            url: updaterUrl + i,
            type: "GET",
            async: false,
            success: function(data) {
                data = jQuery.trim(data);
                errorcode = data.substring(data.length-4, data.length);

                if (data == 'e000a') {
                    jQuery('#updater-info'+i+'_msg').html(updaterInitError);
                    exit = true;
                }
                else if (data == 'e000b') {
                    jQuery('#updater-info'+i+'_msg').html(updaterUpgradeFolderError);
                    exit = true;
                }
                else if (errorcode == 'e001') {
                    jQuery('#updater-info'+i+'_msg').html(updaterDownloadError);
                    exit = true;
                }                
                else if (errorcode == 'e002') {
                    jQuery('#updater-info'+i+'_msg').html(updaterExtractError);
                    exit = true;
                }                 
                else if (errorcode == 'e003') {
                    jQuery('#updater-info'+i+'_msg').html(updaterCopyError);
                    exit = true;
                }               
                else if (errorcode == 's002') {
                    jQuery('#updater-info'+i+'_msg').html(updaterExtractOk);
                }               
                else if (errorcode == 'm004') {
                    jQuery('#updater-info'+i+'_msg').html(updaterCleanupMsg);
                }
                
                if(errorcode.substring(0, 2) == 'e0' || errorcode.substring(0, 2) == 's0' || errorcode.substring(0, 2) == 'm0') {
                    data = data.replace(errorcode, '');
                }
                
                jQuery('#updater-info'+i).html(data);
                
                showLoader(false);
            }
        });
        
        if(i == 2) {
            initUpdaterFileListButton();
        }
        
        if(i >= 5) showLoader(false);
        
        if(exit) return false;
        
    }
    
    var updateTimer = ((new Date().getTime()) - updateStart) / 1000;

    jQuery('#updater-info_timer').html(updaterTimerMsg + ': ' + updateTimer + ' sec');
}

function initUpdaterFileListButton() {
    jQuery('.updater-file-list-show').button({
        icons: {
            primary: "ui-icon-circle-triangle-s",
        },
        text: true
    }).click(function() {
        jQuery('.updater-file-list').fadeToggle();
        return false;
    });    
}

function insertImageFull(self) {
    var url = jQuery(self).attr('href');
    var title = jQuery(self).attr('imgtxt');

    if(jsSyseditor == 'classic') {
        if(parent.fileOpenMode == 1) {
            parent.document.getElementById('urladr').value   = url;
            parent.document.getElementById('urltxt').value = title;
        }            
        if(parent.fileOpenMode == 2) {
            parent.document.getElementById('imgpath').value   = url;
            parent.document.getElementById('imgalttxt').value = title;                
        }        
        window.parent.jQuery('#editor_filemanager').dialog('close');
    } else {
        top.tinymce.activeEditor.windowManager.getParams().oninsert(url, { alt: title, text: title });
        top.tinymce.activeEditor.windowManager.close();
    }
}

function insertImageThumb(self) {
    var url = jQuery(self).attr('href');
    var title = jQuery(self).attr('imgtxt');

    if(jsSyseditor == 'classic') {
        if(parent.fileOpenMode == 1) {
            parent.document.getElementById('urladr').value   = url;
            parent.document.getElementById('urltxt').value = title;
        }            
        if(parent.fileOpenMode == 2) {
            parent.document.getElementById('imgpath').value   = url;
            parent.document.getElementById('imgalttxt').value = title;                
        }
        window.parent.jQuery('#editor_filemanager').dialog('close');
    } else {
        top.tinymce.activeEditor.windowManager.getParams().oninsert(url, { alt: title, text: title });
        top.tinymce.activeEditor.windowManager.close();
    }    
}