/**
 * Author: DDogg, http://www.php.de/html-usability-und-barrierefreiheit/34508-onclick-input-text-area.html
 */
function insert(aTag, eTag) {    

    if(editor.doc.somethingSelected()) {
        selectedText = editor.doc.getSelection();        
        editor.doc.replaceSelection(aTag + selectedText + eTag);
    } else {
        var cursorPos       = editor.doc.getCursor();
        editor.doc.replaceRange(aTag + '' + eTag, cursorPos, cursorPos);        

        if(eTag != '') {
            cursorPos.ch = (eTag.length > cursorPos.ch)
                         ? cursorPos.ch + aTag.length
                         : cursorPos.ch - eTag.length;

            editor.doc.setCursor(cursorPos);            
        }
        
        editor.focus();

    }
    
    return false;  
}

function clearPathTextValuesLink() {      
    jQuery('#urladr').val('http://');
    jQuery('#urltxt').val('');
    jQuery('#urltarget').val('');
    fileOpenMode = 0;
}

function clearPathTextValuesImg() {
    jQuery('#imgpath').val('http://');
    jQuery('#imgalign').val('');
    jQuery('#imgalttxt').val('');
    fileOpenMode = 0;
}

function clearTableForm() {
    jQuery('#tablerows').val('1');
    jQuery('#tablecols').val('1');
}

function insertLink() {
    var lnk_url = document.getElementById('urladr').value;
    var lnk_txt =  document.getElementById('urltxt').value;
    var lnk_tgt = document.getElementById('urltarget').value;
    
    if(document.getElementById('urlcss') != null) {
        var lnk_css =  document.getElementById('urlcss').value;
    }

    if (lnk_tgt != "") {
        aTag = '<a href=\"' + lnk_url + '"\ target=\"' + lnk_tgt + '\"';
        if(lnk_css) { aTag = aTag + ' class=\"'+ lnk_css +'\"'; }
        aTag = aTag + '>' + lnk_txt ;
    }
    else {
        aTag = '<a href=\"' + lnk_url + '\"';
        if(lnk_css) { aTag = aTag + ' class=\"'+ lnk_css +'\"'; }
        aTag = aTag + '>' + lnk_txt ;
    }

    insert(aTag, '</a>');
}

function insertTable() {
    var tablerows = document.getElementById('tablerows').value;
    var tablecols =  document.getElementById('tablecols').value;
    var aTag = '<table>\n'

    for (i=0;i<tablerows;i++) {        
        aTag += '<tr>\n';        
        for (x=0;x<tablecols;x++) { aTag += '<td></td>\n'; }        
        aTag += '</tr>\n';        
    }
    insert(aTag + '</table>', '');  
}

function insertPicture() {
    var pic_path = document.getElementById('imgpath').value;  
    var pic_align = document.getElementById('imgalign').value;
    var pic_atxt =  document.getElementById('imgalttxt').value;
    
    if(document.getElementById('imgcss') != null) {
        var pic_css =  document.getElementById('imgcss').value;
    }    

    if (pic_align == "right" || pic_align == "left") {
        aTag = '<img src=\"' + pic_path + '\" alt=\"' + pic_atxt + '\" style=\"float:' + pic_align + ';margin:3px;\"';
        if(pic_css) { aTag = aTag + ' class=\"'+ pic_css +'\"'; }
        insert(aTag + '/>', ' ');
    } else if (pic_align == "center") {
        aTag = '<div style=\"text-align:' + pic_align + ';\"><img src=\"' + pic_path + '\" alt=\"' + pic_atxt + '\"';
        if(pic_css) { aTag = aTag + ' class=\"'+ pic_css +'\"'; }
        insert(aTag + '/></div>', ' ');
    } else {
        aTag = '<img src=\"' + pic_path + '\" alt=\"' + pic_atxt + '\"';
        if(pic_css) { aTag = aTag + ' class=\"'+ pic_css +'\"'; }
        insert(aTag + ' />', ' ');
    }
}

function insertListToFrom(listtype) {
    aTag = "";

    do {
        $liTxt = prompt("Element:","");
        if($liTxt != "" && $liTxt != null) { aTag = aTag + '<li>' + $liTxt + '</li>\n'; }
    } while($liTxt != "" && $liTxt != null);

    aTag = '<' + listtype + '>\n' + aTag;

    insert(aTag, '</' + listtype + '>');
}

function insertFontsize(fs) {
    aTag = '<span style=\"font-size:' + fs + 'pt;\">';
    insert(aTag, '</span>');
}

function insertAlignTags(aligndes) {
    aTag = '<p style=\"text-align:' + aligndes + ';\">';
    insert(aTag, '</p>');
}

function insertMoreArea() { insert('<readmore>', '</readmore>'); }
function insertSmilies(smiliecode) { insert(' ' + smiliecode + ' ', ''); }
function insertPlayer() { insert('<player>http://', '</player>'); }
function insertColor(color, mode) {    
    mode    = (typeof mode == 'undefined') ? 'color' : mode;
    color   = (color == '') ? '#000000' : color;    
    insert(' style="' + mode + ':' + color + ';"', ' ');
    
    jQuery('#color_hexcode').val('');
    jQuery('.color_mode:checked').removeAttr('checked');    
    jQuery('#color_mode1').prop( "checked", true );
}

function showFileManager() {
    jQuery('#editor_filemanager').dialog({
        width    : 900,
        modal    : true,
        resizable: true,
        title    : jsFileManagerHeadline,
        buttons  : [
            {
                text: jsFPclose,
                click: function() {
                    jQuery( this ).dialog( "close" );
                }
            }                            
        ]
    });    
}

function initTinyMce() {
    tinymce.init({
        selector              : "textarea",
        skin                  : "fpcm",
        theme                 : "modern",
        custom_elements       : tinymceElements,
        language              : tinymceLang,
        plugins               : tinymcePlugins,
        toolbar1              : tinymceToolbar,
        content_css           : tinymceContentCss,
        link_class_list       : tinymceCssClasses,
        image_class_list      : tinymceCssClasses,
        link_list             : tinymceLinkList,
        menubar               : false,
        relative_urls         : false,
        image_advtab          : true,
        resize                : true,
        convert_urls          : true,
        browser_spellcheck    : true,
        autoresize_min_height : '500',
        file_picker_callback  : function(callback, value, meta) {
            tinymce.activeEditor.windowManager.open({
                file            : cmsURL,
                title           : tinymceUploadTitle,
                width           : 900,
                height          : 500,
                resizable       : "yes",
                inline          : "yes",
                close_previous  : "no",
                buttons  : [
                    {
                        text: jsFPclose,
                        onclick: function() {
                            top.tinymce.activeEditor.windowManager.close();
                        }
                    }                            
                ]
            }, {
                oninsert: function (url, objVals) {
                    callback(url, objVals);
                }
            });
        },
        setup : function(ed) { 
            ed.on('init', function() {
                this.getBody().style.fontSize = '14px';
                jQuery(this.iframeElement).removeAttr('title');
            });
        }                
    });    
}

function initCodeMirror() {
    jQuery('#color_hexcode').colorPicker({
        rows        : 5,
        cols        : 8,
        showCode    : 0,
        cellWidth   : 15,
        cellHeight  : 15,
        top         : 27,
        left        : 0,
        colorData   : cmColors,            
        onSelect    : function(colorCode) {
            jQuery('#color_hexcode').val(colorCode);
        }
    });    
    
    editor = CodeMirror.fromTextArea(document.getElementById("newstextarea"), {
        lineNumbers     : true,
        matchBrackets   : true,
        lineWrapping    : true,
        autoCloseTags   : true,
        id              : 'idtest',
        mode            : "text/html",
        matchTags       : {bothTags: true},
        extraKeys       : {"Ctrl-Space": "autocomplete"},
        value           : document.documentElement.innerHTML
    });

    editor.setOption('theme', 'mdn-like');
}

jQuery(function() { 
    
    /* Editor */
    jQuery('.editor_insert_submit').click(function() {
        jQuery(this).parent().parent().dialog( "close" );  
    });

    jQuery('#newstextarea_syntaxdiv').click(function(){
        jQuery('#newstextarea').focus();
    });

    jQuery('#archivedY').click(function() {
        jQuery('.pinNews').fadeOut();
    });

    jQuery('#archivedN').click(function() {
        jQuery('.pinNews').fadeIn();
    });        

    jQuery('.fp-editor-html-click').click(function() {        
        var tag = jQuery(this).attr('htmltag');
        insert('<' + tag + '>', '</' + tag + '>');
        jQuery(this).parent().parent().trigger('click');
        return false;
    });
    
    jQuery('.fp-editor-html-fontsize').click(function() {        
        var tag = jQuery(this).attr('htmltag');
        insertFontsize(tag);
        jQuery(this).parent().parent().trigger('click');
        return false;
    });    
    
    jQuery('.fp-html-smiley').click(function() {
        insertSmilies(jQuery(this).attr('alt'));
        return false;
    });

    jQuery('#btnextmenu').button({
        icons: {
            primary: "ui-icon-wrench",
            secondary: "ui-icon-triangle-1-n"
        },
        text: false,
    }).click(function() {
        jQuery('#fp-extended-dialog').fadeToggle();
        return false;
    });
    
    jQuery('.editor-select-button').button({
        icons: {
            secondary: "ui-icon-triangle-1-s"
        },
        text: true,
    }).click(function() {
        var topPos  = jQuery(this).position().top + jQuery(this).parent().height() - 7;
        var topLeft = jQuery(this).position().left - 6;
        
        jQuery('.editor-select.active').fadeOut();
        
        jQuery(this).
                parent().
                children('.editor-select').css('top', topPos).css('left', topLeft).css('min-width', jQuery(this).parent().width()).toggleClass('active').fadeToggle().
                children('.smenu').menu();
        
        return false;
    }).parent().children('.editor-select').click(function() {
        jQuery(this).removeClass('active').fadeToggle();
    });
    
    jQuery('input.fp-ui-spinner-hour').spinner({
        min: 0,
        max: 23
    });
    jQuery('input.fp-ui-spinner-minutes').spinner({
        min: 0,
        max: 59
    });
    
    jQuery('.fp-ui-button-restore').button({
        icons: {
            primary: "ui-icon-arrowreturnthick-1-w"
        },
        text: false
    }).click(function() {
        if(!confirm(fpNewsListActionConfirmMsg)) {
            showLoader(false);
            return false;
        }        
    });
    
    jQuery(window).click(function() {
        jQuery('.editor-select').fadeOut();
    });
    
    /**
     * Keycodes
     * http://www.brain4.de/programmierecke/js/tastatur.php
     */
    jQuery(document).keypress(function(thekey) {
        if(typeof editor == 'undefined' || (editor && !editor.state.focused) || !keyShortcutsEnabled) return;
        
        if (thekey.ctrlKey && thekey.which == 115) {
            if(jQuery("#sbmbtnsave")) { jQuery("#sbmbtnsave").click();return false; }            
        }

        if (thekey.ctrlKey && thekey.which == 98) { insert('<b>', '</b>');return false; }
        if (thekey.ctrlKey && thekey.which == 105) { insert('<i>', '</i>');return false; }
        if (thekey.ctrlKey && thekey.which == 117) { insert('<u>', '</u>');return false; }
        if (thekey.ctrlKey && thekey.which == 111) { insert('<s>', '</s>');return false; }
        if (thekey.ctrlKey && thekey.shiftKey && thekey.which == 89) { insert('<sub>', '</sub>');return false; }
        if (thekey.ctrlKey && thekey.which == 121) { insert('<sup>', '</sup>');return false; }

        if (thekey.ctrlKey && thekey.which == 46) { insertListToFrom('ul');return false; }
        if (thekey.ctrlKey && thekey.which == 35) { insertListToFrom('ol');return false; }

        if (thekey.ctrlKey && thekey.which == 113) { insert('<blockquote>', '</blockquote>');return false; }
        if (thekey.ctrlKey && thekey.which == 102) { insert('<iframe src="http://" class="fp-newstext-iframe">', '</iframe>');return false; }
        if (thekey.ctrlKey && thekey.which == 109) { insert('<readmore>', '</readmore>');return false; }
        if (thekey.ctrlKey && thekey.shiftKey && thekey.which == 90) { insertPlayer();return false; }
        if (thekey.ctrlKey && thekey.which == 108) { jQuery('#editor_link_insertlink').click();  }
        if (thekey.ctrlKey && thekey.which == 112) { jQuery('#editor_link_insertimage').click();  }
        if (thekey.ctrlKey && thekey.shiftKey && thekey.which == 84) { jQuery('#editor_link_inserttable').click();  }
        if (thekey.ctrlKey && thekey.shiftKey && thekey.which == 69) { jQuery('#editor_link_insertsmilies').click();  }        

        if (thekey.ctrlKey && thekey.shiftKey && thekey.which == 76) { insertAlignTags('left');return false; }
        if (thekey.ctrlKey && thekey.shiftKey && thekey.which == 67) { insertAlignTags('center');return false; }
        if (thekey.ctrlKey && thekey.shiftKey && thekey.which == 82) { insertAlignTags('right');return false; }
        if (thekey.ctrlKey && thekey.shiftKey && thekey.which == 74) { insertAlignTags('justify');return false; }
        
        if (thekey.ctrlKey && thekey.shiftKey && thekey.which == 70) { jQuery('#editor_link_insertcolor').click();return false; }
    });        
});