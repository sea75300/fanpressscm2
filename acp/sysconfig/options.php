<?php
 /**
   * System-Optionen
   * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
   * @copyright 2011-2014
   *
   */

    if(!defined("LOGGED_IN_USER")) { die(); }

    if(LOGGED_IN_USER && fpSecurity::checkPermissions ("system")) :
?>
        <h2><?php fpLanguage::printLanguageConstant(LANG_SYSCFG_OPTIONS); ?></h2>
        <?php
            if(isset($_POST["sbmoptions"])) {  
                
                fpConfig::update($_REQUEST);

                fpMessages::showSysNotice(LANG_SYS_NEWCONFIGMSG);
            }
        ?>

        <form method="post" action="sysconfig.php?mod=system">
            <div id="tabsGeneral">
                <ul>
                    <li><a href="#tabs-options-general"><?php fpLanguage::printLanguageConstant(LANG_SYS_GENERAL); ?></a></li>
                    <li><a href="#tabs-options-editor"><?php fpLanguage::printLanguageConstant(LANG_NEWSEDITOR); ?> / <?php fpLanguage::printLanguageConstant(LANG_UPLOAD_FILEMANAGER); ?></a></li>
                    <li><a href="#tabs-options-news"><?php fpLanguage::printLanguageConstant(LANG_SYS_NEWS); ?></a></li>
                    <li><a href="#tabs-options-comments"><?php fpLanguage::printLanguageConstant(LANG_SYS_COMMENTS); ?></a></li>
                    <?php if($fpSystem->canConnect() && function_exists('curl_init')) : ?> 
                    <li><a href="#tabs-options-twitter"><?php fpLanguage::printLanguageConstant(LANG_SYS_TWITTER_CONNECTION); ?></a></li>
                    <?php endif; ?>
                    <li><a href="#tabs-options-updates"><?php fpLanguage::printLanguageConstant(LANG_SYS_UPDATESTATUS); ?></a></li>                    
                </ul>
                
                <?php $conf = $fpSystem->loadSysConfig(array("system_lang","timedate_mask","time_zone")); ?>
                
                <div id="tabs-options-general">
                    <table class="fp-ui-options">
                        <tr>			
                                <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_SYSMAIL); ?>:</td>
                                <td class="tdtcontent"><input style="width:400px" type="text" name="system_mail" size="40" maxlength="255" value="<?php print fpConfig::get('system_mail'); ?>"></td>		
                        </tr>			
                        <tr>			
                                <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_URL); ?>:</td>
                                <td class="tdtcontent"><input style="width:400px" type="text" name="system_url" size="40" maxlength="255" value="<?php print fpConfig::get('system_url'); ?>"></td>
                        </tr>	
                        <tr>			
                            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_LANG); ?>:</td>
                            <td class="tdtcontent">
                                <select style="width:400px" class="fp-ui-jqselect" name="system_lang">
                                <?php
                                    $languages = $fpLanguage->getInstalledLanguages();                                       
                                    foreach ($languages as $language => $languageName) {
                                        if ($conf['system_lang'] == $language) {
                                            print "<option value=\"".$language."\" selected=\"selected\">".$languageName."</option>";
                                        }
                                        else {                                                
                                            print "<option value=\"".$language."\">".$languageName."</option>";
                                        }                                            
                                    }											 
                                ?>
                                </select>					
                            </td>				 
                        </tr>		
                        <tr>			
                            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_TIMEZONE); ?>:</td>
                            <td class="tdtcontent">
                                <select style="width:400px" class="fp-ui-jqselect" name="time_zone">
                                <?php foreach (fpSystem::getTimeZones() as $timezoneAreaName => $timezones) : ?>
                                    <optgroup label="<?php fpLanguage::printLanguageConstant($timezoneAreaName); ?>">                                        
                                        <?php foreach ($timezones as $timezone) : ?>
                                        <option value="<?php print $timezone; ?>" <?php if($timezone == $conf['time_zone']) : ?>selected="selected"<?php endif; ?>><?php print $timezone; ?></option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach;?>
                                </select>	
                            </td>		
                        </tr>						
                        <tr>			
                                <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_DATETIMEMASK); ?>:</td>
                                <td class="tdtcontent"><input style="width:400px" id="sysdtmask" type="text" name="timedate_mask" size="40" maxlength="255" value="<?php print $conf['timedate_mask']; ?>"></td>
                        </tr>			 			 
                        <tr>			
                                <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_SESSIONLENGHT); ?>:</td>
                                <td class="tdtcontent"><input class="fp-ui-spinner" style="width:375px" type="text" name="session_length" size="40" maxlength="255" value="<?php print fpConfig::get('session_length'); ?>"></td>
                        </tr>
                        <tr>			
                                <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_CACHETIMEOUT); ?>:</td>
                                <td class="tdtcontent"><input class="fp-ui-spinner" style="width:375px" type="text" name="cache_timeout" size="40" maxlength="255" value="<?php print FPCACHEEXPIRE; ?>"></td>		
                        </tr>                               
                        <tr>			
                            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_USEMODE); ?>:</td>
                            <td class="tdtcontent">
                                <select style="width:400px" class="fp-ui-jqselect" id="usemode" name="usemode">
                                    <option value="phpinc" <?php if(fpConfig::get('usemode') == "phpinc") { print "selected=\"selected\""; } ?>>phpinclude</option>
                                    <option value="iframe" <?php if(fpConfig::get('usemode') == "iframe") { print "selected=\"selected\""; } ?>>iframe</option>
                                </select>
                            </td>		
                        </tr>			 
                        <tr id="iframecss" <?php if(fpConfig::get('usemode') == "phpinc") : ?>class="fp-hidden"<?php endif; ?>>			
                            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_STYLESHEET); ?>:</td>
                            <td class="tdtcontent"><input style="width:400px" type="text" name="useiframecss" size="40" maxlength="255" value="<?php print fpConfig::get('useiframecss'); ?>"></td>		
                        </tr>
                        <tr id="jqueryinc" <?php if(fpConfig::get('usemode') == "iframe") : ?>class="fp-hidden"<?php endif; ?>>
                            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_INCLUDEJQUERY); ?>:<br>
                            <small><?php fpLanguage::printLanguageConstant(LANG_SYS_INCLUDEJQUERY_YES); ?></small></td>
                            <td class="tdtcontent">
                                <select style="width:400px" class="fp-ui-jqselect" name="sys_jquery" id="sys_jquery">
                                    <option value="0" <?php if(fpConfig::get('sys_jquery') == "0") { print "selected=\"selected\""; } ?>><?php fpLanguage::printNo(); ?></option>
                                    <option value="1" <?php if(fpConfig::get('sys_jquery') == "1") { print "selected=\"selected\""; } ?>><?php fpLanguage::printYes(); ?></option>
                                </select>
                            </td>
                        </tr>                            
                    </table>
                </div>
                
                <div id="tabs-options-editor">
                    <table class="fp-ui-options">
                        <tr>			
                            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_NEWS_EDITOR); ?>:</td>
                            <td class="tdtcontent">
                                <select style="width:400px" class="fp-ui-jqselect" name="system_editor">
                                    <option value="standard" <?php if(fpConfig::get('system_editor') == "standard") { print "selected=\"selected\""; } ?>><?php fpLanguage::printLanguageConstant(LANG_SYS_NEWS_EDITOR_STD); ?></option>
                                    <option value="classic" <?php if(fpConfig::get('system_editor') == "classic") { print "selected=\"selected\""; } ?>><?php fpLanguage::printLanguageConstant(LANG_SYS_NEWS_EDITOR_CLASSIC); ?></option>
                                </select>
                            </td>		
                        </tr>
                        <tr>			
                            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_NEWS_NEWUPLOADER); ?>:</td>
                            <td class="tdtcontent">
                                <select style="width:400px" class="fp-ui-jqselect" name="new_file_uploader">
                                    <option value="0" <?php if(fpConfig::get('new_file_uploader') == "0") { print "selected=\"selected\""; } ?>><?php fpLanguage::printNo(); ?></option>
                                    <option value="1" <?php if(fpConfig::get('new_file_uploader') == "1") { print "selected=\"selected\""; } ?>><?php fpLanguage::printYes(); ?></option>
                                </select>
                            </td>		
                        </tr> 
                        <tr>			
                            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_REVISIONS_ENABLED); ?>:</td>
                            <td class="tdtcontent">
                                <select style="width:400px" class="fp-ui-jqselect" name="revisions">
                                    <option value="0" <?php if(!fpConfig::get('revisions')) : ?> selected="selected" <?php endif; ?>><?php fpLanguage::printNo(); ?></option>
                                    <option value="1" <?php if(fpConfig::get('revisions')) : ?> selected="selected" <?php endif; ?>><?php fpLanguage::printYes(); ?></option>
                                </select>
                            </td>		
                        </tr>
                        <tr>			
                            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_NEWSSHOWMAXIMGSIZE); ?>:</td>
                            <td class="tdtcontent">
                                <input class="fp-ui-spinner" type="text" name="max_img_size_x" size="5" maxlength="5" value="<?php print fpConfig::get("max_img_size_x"); ?>"> <span class="fa fa-times"></span>
                                <input class="fp-ui-spinner" type="text" name="max_img_size_y" size="5" maxlength="5" value="<?php print fpConfig::get("max_img_size_y"); ?>">
                                <?php fpLanguage::printLanguageConstant(LANG_SYS_NEWSSHOWMAXIMGSIZEPIXELS); ?>
                            </td>	
                        </tr>	                        
                        
                        <tr>			
                            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_NEWSSHOWIMGTHUMBSIZE); ?>:</td>
                            <td class="tdtcontent">
                                <input class="fp-ui-spinner" type="text" name="max_img_thumb_size_x" size="5" maxlength="5" value="<?php print fpConfig::get("max_img_thumb_size_x"); ?>"> <span class="fa fa-times"></span>
                                <input class="fp-ui-spinner" type="text" name="max_img_thumb_size_y" size="5" maxlength="5" value="<?php print fpConfig::get("max_img_thumb_size_y"); ?>">
                                <?php fpLanguage::printLanguageConstant(LANG_SYS_NEWSSHOWMAXIMGSIZEPIXELS); ?>
                            </td>	
                        </tr>                        
                    </table>
                </div>
                
                <div id="tabs-options-news">
                    <table class="fp-ui-options">
                        <tr>			
                            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_NEWSSHOWLIMIT); ?>:</td>
                            <td class="tdtcontent"><input class="fp-ui-spinner" style="width:375px" type="text" name="news_show_limit" size="40" maxlength="255" value="<?php print fpConfig::get("news_show_limit"); ?>"></td>		
                        </tr>				
                        <tr>			
                            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_ACTIVENEWSTEMPLATE); ?>:</td>
                            <td class="tdtcontent">
                                <select style="width:400px" class="fp-ui-jqselect" name="active_news_template">
                                <?php
                                    $files = $fpFileSystem->getDirectoryContent(FPBASEDIR."/styles/");
                                    foreach ($files as $file) {
                                        $dateiinfo = pathinfo($file);
                                        if (fpConfig::get('active_news_template') == $dateiinfo['filename']) {
                                            print "<option selected>".$dateiinfo['filename']."</option>";
                                        } else {
                                            print "<option>".$dateiinfo['filename']."</option>";
                                        }
                                    }
                                ?>                                        
                                </select>			 
                            </td>		
                        </tr>
                        
                        <tr>			
                            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_NEWS_SORTING); ?>:</td>
                            <td class="tdtcontent">
                                <div style="margin-bottom: 0.4em;">
                                    <select style="width:400px;" class="fp-ui-jqselect" name="sort_news">
                                        <option value="id" <?php if(fpConfig::get('sort_news') == "id") { print "selected=\"selected\""; } ?>><?php fpLanguage::printLanguageConstant(LANG_SYS_NEWS_BYINTERNALID); ?></option>
                                        <option value="author" <?php if(fpConfig::get('sort_news') == "author") { print "selected=\"selected\""; } ?>><?php fpLanguage::printLanguageConstant(LANG_SYS_NEWS_BYAUTHOR); ?></option>
                                        <option value="writtentime" <?php if(fpConfig::get('sort_news') == "writtentime") { print "selected=\"selected\""; } ?>><?php fpLanguage::printLanguageConstant(LANG_SYS_NEWS_BYWRITTENTIME); ?></option>
                                        <option value="editedtime" <?php if(fpConfig::get('sort_news') == "editedtime") { print "selected=\"selected\""; } ?>><?php fpLanguage::printLanguageConstant(LANG_SYS_NEWS_BYEDITEDTIME); ?></option>
                                    </select>                                    
                                </div>
                                       
                                <div>
                                    <select style="width:400px" class="fp-ui-jqselect" name="sort_news_order">
                                        <option value="ASC" <?php if(fpConfig::get('sort_news_order') == "ASC") { print "selected=\"selected\""; } ?>><?php fpLanguage::printLanguageConstant(LANG_SYS_NEWS_ORDERASC); ?></option>
                                        <option value="DESC" <?php if(fpConfig::get('sort_news_order') == "DESC") { print "selected=\"selected\""; } ?>><?php fpLanguage::printLanguageConstant(LANG_SYS_NEWS_ORDERDESC); ?></option>
                                    </select>
                                </div>
                            </td>		
                        </tr>                        
                        
                        <tr>			
                            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_NEWSSHOWSHARELINKS); ?>:</td>
                            <td class="tdtcontent">
                                <select style="width:400px" class="fp-ui-jqselect" name="showshare">
                                    <option value="0" <?php if(fpConfig::get('showshare') == "0") { print "selected=\"selected\""; } ?>><?php fpLanguage::printNo(); ?></option>
                                    <option value="1" <?php if(fpConfig::get('showshare') == "1") { print "selected=\"selected\""; } ?>><?php fpLanguage::printYes(); ?></option>
                                </select>                                        
                            </td>		
                        </tr>	 

                        <tr>			
                            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_ARCHIVE_LINK); ?>:</td>
                            <td class="tdtcontent">
                                <select style="width:400px" class="fp-ui-jqselect" name="archive_link">
                                    <option value="0" <?php if(fpConfig::get('archive_link') == "0") { print "selected=\"selected\""; } ?>><?php fpLanguage::printNo(); ?></option>
                                    <option value="1" <?php if(fpConfig::get('archive_link') == "1") { print "selected=\"selected\""; } ?>><?php fpLanguage::printYes(); ?></option>
                                </select>                                          
                            </td>		
                        </tr>
                        
                        <tr>			
                            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_NEWS_ENABLETRASH); ?>:</td>
                            <td class="tdtcontent">
                                <select style="width:400px" class="fp-ui-jqselect" name="use_trash">
                                    <option value="0" <?php if(fpConfig::get('use_trash') == "0") { print "selected=\"selected\""; } ?>><?php fpLanguage::printNo(); ?></option>
                                    <option value="1" <?php if(fpConfig::get('use_trash') == "1") { print "selected=\"selected\""; } ?>><?php fpLanguage::printYes(); ?></option>
                                </select>                                          
                            </td>		
                        </tr>                        

                    </table>                    
                </div>
                
                <div id="tabs-options-comments">
                    <table class="fp-ui-options">
                       <tr>			
                            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_COMMENT_ENABLED_GLOBAL); ?>:</td>
                            <td class="tdtcontent">
                                <select style="width:400px" class="fp-ui-jqselect" name="comments_enabled_global">
                                    <option value="0" <?php if(fpConfig::get('comments_enabled_global') == "0") { print "selected=\"selected\""; } ?>><?php fpLanguage::printNo(); ?></option>
                                    <option value="1" <?php if(fpConfig::get('comments_enabled_global') == "1") { print "selected=\"selected\""; } ?>><?php fpLanguage::printYes(); ?></option>
                                </select>                                
                            </td>		
                       </tr>                                                
                       <tr>			
                            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_ACTIVECOMMENTTEMPLATE); ?>:</td>
                            <td class="tdtcontent">
                                 <select style="width:400px" class="fp-ui-jqselect" name="active_comment_template">
                                 <?php
                                     $files = $fpFileSystem->getDirectoryContent(FPBASEDIR."/styles/");
                                     foreach ($files as $file) {
                                         $dateiinfo = pathinfo($file);
                                         if (fpConfig::get('active_comment_template') == $dateiinfo['filename']) {
                                             print "<option selected>".$dateiinfo['filename']."</option>";
                                         } else {
                                             print "<option>".$dateiinfo['filename']."</option>";
                                         }
                                     }
                                 ?> 
                                 </select>					
                            </td>
                       </tr>	 
                       <tr>			
                               <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_ANTISPAMQUESTION); ?>:</td>
                               <td class="tdtcontent"><input style="width:400px" type="text" name="anti_spam_question" size="40" maxlength="255" value="<?php print fpConfig::get('anti_spam_question'); ?>"></td>		
                       </tr>			 
                       <tr>			
                               <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_ANTISPAMANSWER); ?>:</td>
                               <td class="tdtcontent"><input style="width:400px" type="text" name="anti_spam_answer" size="40" maxlength="255" value="<?php print fpConfig::get('anti_spam_answer'); ?>"></td>		
                       </tr>
                       <tr>			
                               <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_FLOODPROTECTION); ?>:</td>
                               <td class="tdtcontent"><input class="fp-ui-spinner" style="width:375px" type="text" name="comment_flood" size="40" maxlength="255" value="<?php print fpConfig::get('comment_flood'); ?>"></td>		
                       </tr>			
                       <tr>			
                               <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_COMMENTLINKDESCRIPTIONS); ?>:</td>
                               <td class="tdtcontent"><input style="width:400px" type="text" name="comlinkdescr" size="40" maxlength="255" value="<?php print fpConfig::get('comlinkdescr'); ?>"></td>		
                       </tr>			
                       <tr>			
                            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_COMMENTEMAIL); ?>:</td>
                            <td class="tdtcontent">
                                 <select style="width:400px" class="fp-ui-jqselect" name="sysemailoptional">
                                     <option value="0" <?php if(fpConfig::get('sysemailoptional') == "0") { print "selected=\"selected\""; } ?>><?php fpLanguage::printNo(); ?></option>
                                     <option value="1" <?php if(fpConfig::get('sysemailoptional') == "1") { print "selected=\"selected\""; } ?>><?php fpLanguage::printYes(); ?></option>
                                 </select>
                            </td>		
                       </tr>	
                       <tr>			
                            <td class="tdheadline2"><?php fpLanguage::printLanguageConstant(LANG_SYS_COMMENT_APPROVE); ?>:</td>
                            <td class="tdtcontent">
                                 <select style="width:400px" class="fp-ui-jqselect" name="confirm_comments">
                                     <option value="0" <?php if(fpConfig::get('confirm_comments') == "0") { print "selected=\"selected\""; } ?>><?php fpLanguage::printNo(); ?></option>
                                     <option value="1" <?php if(fpConfig::get('confirm_comments') == "1") { print "selected=\"selected\""; } ?>><?php fpLanguage::printYes(); ?></option>
                                 </select>
                            </td>		
                       </tr>	
                   </table>                    
                </div>
                       
                <?php if($fpSystem->canConnect() && function_exists('curl_init')) : ?>    
                <div id="tabs-options-twitter">
                    <table class="fp-ui-options">
                        <tr>		
                        <?php
                            $code = 0;
                            if($fpSystem->canConnect()) {                            
                                $test_twitter_con = new tmhOAuth(array(
                                    'consumer_key'    => fpConfig::get('twitter_consumer_key'),
                                    'consumer_secret' => fpConfig::get('twitter_consumer_secret'),
                                    'user_token'      => fpConfig::get('twitter_access_token'),
                                    'user_secret'     => fpConfig::get('twitter_access_token_secret'),
                                ));

                                $code = $test_twitter_con->request('GET', $test_twitter_con->url('1.1/account/verify_credentials'));
                            }
                        ?>
                        <?php if((fpConfig::get('twitter_access_token') == "" && fpConfig::get('twitter_access_token_secret') == "") || $code != 200) : ?>
                            <td class="tdtcontent" colspan="2">
                                <a href="twitter.php?start=1" target="_blank" class="twitterconnectlink fp-ui-button"><?php fpLanguage::printLanguageConstant(LANG_SYS_TWITTER_CONNECT); ?></a>
                            </td>
                        <?php else : ?>	 
                            <td class="tdtcontent" colspan="2">
                                <p><?php print fpMessages::showSysNotice("Twitter Staus OK"); ?></p>
                                <p><a href="twitter.php?disconnect=1" target="_blank" class="twitterconnectlink fp-ui-button"><?php fpLanguage::printLanguageConstant(LANG_SYS_TWITTER_DISCONNECT); ?></a></p>
                            </td>		
                        <?php endif; ?>
                        </tr>		
                    </table>                      
                </div> 
                <?php endif; ?>                
                
                <div id="tabs-options-updates">
                    <div style="width:400px" class="update-box"><?php $fpSystem->checkForUpdates($fpSystem->canConnect()); ?></div>                    
                </div>
            </div>
            
            <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
                <button type="submit" name="sbmoptions" class="btnloader fp-ui-button"><?php fpLanguage::printSave(); ?></button>
            </div>

        </form> 
<?php
    else :
        if(!fpSecurity::checkPermissions("system")) { fpMessages::showNoAccess(); }
    endif;
?>