<?php if(!defined("LOGGED_IN_USER")) { die(); } ?>
<script type="text/javascript">
    var cmsURL = "filemanager.php?mode=1";
    var editor = null;
    
    jQuery(function() {       
        jQuery('#editor_link_twitterlink').click(function() {
            jQuery('#editor_twitterlink').dialog({
                width: 500,
                modal: true,
                resizable: false,
                title: "<?php fpLanguage::printLanguageConstant(LANG_EDITOR_SHORTLINKS); ?>",
                buttons: [
                    {
                        text: "<?php fpLanguage::printClose(); ?>",
                        click: function() {
                            jQuery( this ).dialog( "close" );
                        }
                    }
                ]
            });
            return false;
        });    
        
        jQuery("select.chosen-editor-select").chosen({ width: "100%", disable_search: true, inherit_select_classes: true, placeholder_text_single: '<?php fpLanguage::printSelect(); ?>' });
        
        <?php $autocompleteLinks = fpModuleEventsAcp::runOnLinkAutocomplete(); ?>
        
        <?php if(fpConfig::get('system_editor') == "classic") : ?>
            
        var fileOpenMode = 0;

        <?php
            $fsStore = $fpFileSystem->getFileStore();
        
            $autoComplateData = array();
            
            foreach ($fsStore as $fsFile) {
                $autoComplateData[] = array('label' => $fsFile->filename, 'value' => FPUPLOADFOLDERURL.$fsFile->filename);
            }
            
        ?>
            
        var fileNameAutocomplete = <?php print json_encode($autoComplateData); ?>;
            
        jQuery('#imgpath').autocomplete({
            source: fileNameAutocomplete,
            appendTo: "#editor_insertpicture",
            minLength: 0
        }); 

        <?php if(count($autocompleteLinks)) : ?>
        jQuery('#urladr').autocomplete({
            source: <?php print json_encode($autocompleteLinks); ?>,
            appendTo: "#editor_insertlink",
            minLength: 0
        });
        <?php endif; ?>
        
        jQuery('#editor_link_insertlink').click(function() {
            jQuery('#editor_insertlink').dialog({
                width: 500,
                height: 300,
                resizable: false,
                modal: true,
                title: "<?php fpLanguage::printLanguageConstant(LANG_EDITOR_INSERTLINK); ?>",
                buttons: [
                    {
                        text: "<?php fpLanguage::printLanguageConstant(LANG_EDITOR_INSERTLINK); ?>",
                        click: function() {
                            insertLink();
                            jQuery( this ).dialog( "close" );
                        }
                    },
                    {
                        text: "<?php fpLanguage::printLanguageConstant(LANG_UPLOAD_FILEMANAGER); ?>",
                        click: function() {
                            window.fileOpenMode = 1;
                            showFileManager();
                        }
                    },
                    {
                        text: "<?php fpLanguage::printClose(); ?>",
                        click: function() {
                            jQuery( this ).dialog( "close" );
                        }
                    }
                ],
                close: function( event, ui ) {
                    clearPathTextValuesLink();
                }
            });
            return false;
        });
        
        jQuery('#editor_link_insertimage').click(function() {
            jQuery('#editor_insertpicture').dialog({
                width: 500,
                height: 250,
                resizable: false,
                modal: true,
                title: "<?php fpLanguage::printLanguageConstant(LANG_EDITOR_INSERTPIC); ?>",
                buttons: [
                    {
                        text: "<?php fpLanguage::printLanguageConstant(LANG_EDITOR_INSERTPIC); ?>",
                        click: function() {
                            insertPicture();
                            jQuery( this ).dialog( "close" );
                        }
                    },
                    {
                        text: "<?php fpLanguage::printLanguageConstant(LANG_UPLOAD_FILEMANAGER); ?>",
                        click: function() {
                            window.fileOpenMode = 2;
                            showFileManager();
                        }
                    },
                    {
                        text: "<?php fpLanguage::printClose(); ?>",
                        click: function() {
                            jQuery( this ).dialog( "close" );
                        }
                    }
                ],
                close: function( event, ui ) {
                    clearPathTextValuesImg();
                }
            });
            return false;
        });        
        
        jQuery('#editor_link_inserttable').click(function() {
            jQuery('#editor_inserttable').dialog({
                width: 500,
                height: 250,
                resizable: false,
                modal: true,
                title: "<?php fpLanguage::printLanguageConstant(LANG_EDITOR_INSERTTABLE); ?>",
                buttons: [
                    {
                        text: "<?php fpLanguage::printLanguageConstant(LANG_EDITOR_INSERTTABLE); ?>",
                        click: function() {
                            insertTable();
                            jQuery( this ).dialog( "close" );
                        }
                    },
                    {
                        text: "<?php fpLanguage::printClose(); ?>",
                        click: function() {
                            jQuery( this ).dialog( "close" );
                        }
                    }
                ],
                close: function( event, ui ) {
                    clearTableForm();
                }
            });
            return false;
        });   
        
        jQuery('#editor_link_insertcolor').click(function() {
            jQuery('#editor_insertcolor').dialog({
                width: 500,
                height: 250,
                resizable: false,
                modal: true,
                title: "<?php fpLanguage::printLanguageConstant(LANG_EDITOR_INSERTCOLOR); ?>",
                buttons: [
                    {
                        text: "<?php fpLanguage::printLanguageConstant(LANG_EDITOR_INSERTCOLOR); ?>",
                        click: function() {
                            insertColor(jQuery('#color_hexcode').val(), jQuery('.color_mode:checked').val());
                            jQuery( this ).dialog( "close" );
                        }
                    },
                    {
                        text: "<?php fpLanguage::printClose(); ?>",
                        click: function() {
                            jQuery( this ).dialog( "close" );
                        }
                    }
                ]        
            });
            return false;
        });         
        
        jQuery('#editor_link_insertsmilies').click(function() {
            jQuery('#editor_insertsmilies').dialog({
                width: 350,
                resizable: false,
                modal: true,
                title: "<?php fpLanguage::printLanguageConstant(LANG_EDITOR_INSERTSMILEY); ?>",
                buttons: [
                    {
                        text: "<?php fpLanguage::printClose(); ?>",
                        click: function() {
                            jQuery( this ).dialog( "close" );
                        }
                    }
                ]
            });
            return false;
        });

        <?php
            $editorColors = array(
                '#000000','#993300','#333300','#003300','#003366','#00007f','#333398','#333333',
                '#800000','#ff6600','#808000','#007f00','#007171','#0000e8','#5d5d8b','#6c6c6c',
                '#f00000','#e28800','#8ebe00','#2f8e5f','#30bfbf','#3060f1','#770077','#8d8d8d',                
                '#f100f1','#f0c000','#eeee00','#00f200','#00efef','#00beee','#8d2f5e','#b5b5b5',
                '#ed8ebe','#efbf8f','#e8e88b','#bbeabb','#bcebeb','#89b6e4','#b88ae6','#ffffff'
            );
        ?>

        jQuery('.editorbtn .fp-buttonset-buttons').buttonset();

        <?php endif; ?>
    });
</script>

<?php if(isset($_GET["added"]) || $iseditmode == 1) : ?>
<!-- Twitter Link -->
<div class="editorlayer" id="editor_twitterlink">      
    <?php if($fpSystem->canConnect()) : ?>
        <input type="text" value="<?php print $fpNews->createShortLink($alink); ?>">
    <?php else : ?>
        <iframe style="border:0;height:40px;width:90%;padding:0px;" src="http://is.gd/create.php?format=simple&url=<?php print urlencode($alink); ?>" scrolling="no" seamless></iframe>";
    <?php endif; ?>    
</div>   
<?php endif; ?>

<?php if(fpConfig::get('system_editor') == "classic") : ?>

<!-- Dateimanager layer -->  
<div class="editorlayer" id="editor_filemanager">  
    <iframe class="fp-editor-html-filemanager" src="<?php print FP_ROOT_DIR.'/acp/filemanager.php?mode=1'; ?>"></iframe>
</div>

<!-- Link einfügen -->  
<div class="editorlayer" id="editor_insertlink">  
    <table width="100%">
        <tr>
            <td class="tdheadline2"><label><?php fpLanguage::printLanguageConstant(LANG_EDITOR_LINKURL); ?>:</label></td>
            <td class="tdtcontent"><input type="text" name="urladr" id="urladr" size="50" maxlength="255" value="http://"></td>
        </tr>
        <tr>
            <td class="tdheadline2"><label><?php fpLanguage::printLanguageConstant(LANG_EDITOR_LINKTXT); ?>:</label></td>
            <td class="tdtcontent"><input type="text" name="urltxt" id="urltxt" size="50" maxlength="255" value=""></td>
        </tr>        
        <tr>
            <td class="tdheadline2"><label><?php fpLanguage::printLanguageConstant(LANG_EDITOR_LINKTARGET); ?>:</label></td>
            <td class="tdtcontent">
                <select class="chosen-editor-select" id="urltarget">
                    <option value=""></option>
                    <option value="_blank">_blank</option>
                    <option value="_top">_top</option>
                    <option value="_self">_self</option>
                    <option value="_parent">_parent</option>
                </select>              
            </td>
        </tr>
        <?php if(fpConfig::hasTinyMceCss()) : ?>            
        <tr>
            <td class="tdheadline2"><label><?php fpLanguage::printLanguageConstant(LANG_EDITOR_CSS_CLASS); ?>:</label></td>
            <td class="tdtcontent">
                <select class="chosen-editor-select" id="urlcss">
                    <option value=""></option>
                <?php
                    $customeStyleFileContent = file(FPBASEDIR.'/inc/tiny_mce.custom.css');

                    foreach ($customeStyleFileContent as $customeStyle) :
                        if(!empty($customeStyle) && strpos($customeStyle, '.') !== false && strpos($customeStyle, '{}') !== false) :
                            $customeStyle = str_replace(array('.', '{}'), '', trim($customeStyle));

                            print "<option value=\"$customeStyle\">$customeStyle</option>";

                        endif;
                    endforeach;
                ?>
                </select>              
            </td>
        </tr>       
        <?php endif; ?>        
    </table>
</div>

<!-- Bild einfügen -->  
<div class="editorlayer" id="editor_insertpicture">  
    <table width="100%">
        <tr>
            <td class="tdheadline2"><label><?php fpLanguage::printLanguageConstant(LANG_EDITOR_IMGPATH); ?>:</label></td>
            <td class="tdtcontent"><input type="text" name="imgpath" id="imgpath" size="50" maxlength="255" value="http://"></td>
        </tr>
        <tr>
            <td class="tdheadline2"><label><?php fpLanguage::printLanguageConstant(LANG_EDITOR_IMGALTTXT); ?>:</label></td>
            <td class="tdtcontent"><input type="text" name="imgalttxt" id="imgalttxt" size="50" maxlength="255" value=""></td>
        </tr>        
        <tr>
            <td class="tdheadline2"><label><?php fpLanguage::printLanguageConstant(LANG_EDITOR_IMGALIGN); ?>:</label></td>
            <td class="tdtcontent">
                <select class="chosen-editor-select" id="imgalign">
                    <option value=""></option>
                    <option value="left">left</option>
                    <option value="center">center</option>
                    <option value="right">right</option>
                </select>              
            </td>
        </tr>   
        <?php if(fpConfig::hasTinyMceCss()) : ?>            
        <tr>
            <td class="tdheadline2"><label><?php fpLanguage::printLanguageConstant(LANG_EDITOR_CSS_CLASS); ?>:</label></td>
            <td class="tdtcontent">
                <select class="chosen-editor-select" id="imgcss">
                    <option value=""></option>
                <?php
                    $customeStyleFileContent = file(FPBASEDIR.'/inc/tiny_mce.custom.css');

                    foreach ($customeStyleFileContent as $customeStyle) :
                        if(!empty($customeStyle) && strpos($customeStyle, '.') !== false && strpos($customeStyle, '{}') !== false) :
                            $customeStyle = str_replace(array('.', '{}'), '', trim($customeStyle));

                            print "<option value=\"$customeStyle\">$customeStyle</option>";

                        endif;
                    endforeach;
                ?>
                </select>              
            </td>
        </tr>       
        <?php endif; ?>
    </table>
</div>

<!-- Tabelle einfügen -->  
<div class="editorlayer" id="editor_inserttable">  
    <table width="100%">
        <tr>
            <td class="tdheadline2" style="width: 50%;"><label><?php fpLanguage::printLanguageConstant(LANG_EDITOR_INSERTTABLE_ROWS); ?>:</label></td>
            <td class="tdtcontent" style="width: 50%;"><input type="text" name="tablerows" id="tablerows" size="25" maxlength="5" value="1"></td>
        </tr>
        <tr>
            <td class="tdheadline2"><label><?php fpLanguage::printLanguageConstant(LANG_EDITOR_INSERTTABLE_COLS); ?>:</label></td>
            <td class="tdtcontent"><input type="text" name="tablecols" id="tablecols" size="25" maxlength="5" value="1"></td>
        </tr>        
    </table>
</div>

<!-- Tabelle einfügen -->  
<div class="editorlayer" id="editor_insertcolor">  
    <table width="100%">
        <tr>
            <td class="tdheadline2" style="width: 50%;"><label><?php fpLanguage::printLanguageConstant(LANG_EDITOR_INSERTCOLOR_HEXCODE); ?>:</label></td>
            <td class="tdtcontent" style="width: 50%;"><input type="text" name="color_hexcode" id="color_hexcode" size="7" maxlength="5" value=""></td>
        </tr>   
        <tr>
            <td class="tdheadline2" style="width: 50%;padding-top:8px;"><label><?php fpLanguage::printLanguageConstant(LANG_EDITOR_INSERTCOLOR_TEXT); ?>:</label></td>
            <td class="tdtcontent" style="width: 50%;padding-top:8px;"><input type="radio" name="color_mode" id="color_mode1" class="color_mode" value="color" checked="checked"></td>
        </tr>
        <tr>
            <td class="tdheadline2" style="width: 50%;padding-top:8px;"><label><?php fpLanguage::printLanguageConstant(LANG_EDITOR_INSERTCOLOR_BACKGROUND); ?>:</label></td>
            <td class="tdtcontent" style="width: 50%;padding-top:8px;"><input type="radio" name="color_mode" id="color_mode2" class="color_mode" value="background"></td>
        </tr>        
    </table>
</div>

<div class="editorlayer" id="editor_insertsmilies">
    <table>
        <tr>
            
        <?php
            $smilieRows  = $fpFileSystem->getSmilies();   
            $count = 0;        
        ?>

        <?php foreach($smilieRows AS $smilieRow) : ?>
        <?php
            $count++;
        
            $fileInfo   = getimagesize(FPBASEDIR."/img/smilies/".$smilieRow->sml_filename);
            $code       = fpSecurity::Filter4($smilieRow->sml_code);
        ?>

            <td style="width:30px;height:24px;">
                <img class="fp-html-smiley" <?php print $fileInfo[3]; ?> src="<?php print FP_ROOT_DIR."img/smilies/".$smilieRow->sml_filename; ?>" border="0" title="<?php print $code; ?>" alt="<?php print $code; ?>">
            </td>
            
            <?php if($count == 10) : ?>
                </tr><tr>
                <?php $count = 0; ?>
            <?php endif; ?>

        <?php endforeach; ?>          
        </tr>
    </table>
</div>

<?php endif; ?>