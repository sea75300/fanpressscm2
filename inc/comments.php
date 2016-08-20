<?php
    /**
     * FanPress CM Kommentar-Formular News-Anzeige
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     *
     * 
     */

    if(!defined('FEMODE')) die();
    
    $fpSmilieObj         = new fpFileSystem($fpDBcon);
    
    $commentFormTemplate = fpTemplates::openTemplate(FPBASEDIR.'/styles/comment_form.html');
    
    $privateChecked      = isset($_POST["isprvt"]) ? 'checked="checked"' : '';
    
    $eventReturn         = fpModuleEventsFE::runOnReplaceSpamPlugin();
    $antiSpamPlugin      = $eventReturn ? $eventReturn : '<label for="antispam">'.fpConfig::get('anti_spam_question').'</label> <input type="text" name="antispam" size="50" maxlength="255"> ';
    
    $smileyList          = array();
    
    $smileyList[]        = '<ul class="fp-comment-smiley-row">';
    
    $smilieRows  = $fpSmilieObj->getSmilies();

    foreach($smilieRows AS $smilieRow) {
        $smileyList[]    = "<li><a href=\"\" onclick=\"insertSmilieToComment('".$smilieRow->sml_code."');return false;\"><img src=\"".FP_ROOT_DIR."img/smilies/".$smilieRow->sml_filename."\" alt=\"".fpSecurity::Filter4($smilieRow->sml_code)."\"></a></li>\n";
    }    
    
    $smileyList[]        = '</ul>';

    $replacements = array(
        '%comment_headline%'            => fpLanguage::returnLanguageConstant(LANG_COMMENT_FEHEADLINE),
        '%comment_form_url%'            => $_SERVER['PHP_SELF']."?fn=cmt&nid=".$newsid,
        '%comment_name_description%'    => fpLanguage::returnLanguageConstant(LANG_COMMENT_NAME),
        '%comment_name%'                => '<input type="text" name="cname" size="50" maxlength="255" value="'.fpSecurity::fpfilter(array('filterstring' => $mailname), array(4,5,1,7)).'">',
        '%comment_email_description%'   => fpLanguage::returnLanguageConstant(LANG_COMMENT_EMAIL),
        '%comment_email%'               => '<input type="text" name="email" size="50" maxlength="255" value="'.fpSecurity::fpfilter(array('filterstring' => $mailaddress), array(4,5,1,7)).'">',
        '%comment_url_description%'     => fpLanguage::returnLanguageConstant(LANG_COMMENT_URL),
        '%comment_url%'                 => '<input type="text" name="url" size="50" maxlength="255" value="http://'.fpSecurity::fpfilter(array('filterstring' => $hpadress), array(4,5,1,7)).'">',
        '%comment_text_area%'           => '<textarea name="cmttext" cols="50" rows="10">'.fpSecurity::fpfilter(array('filterstring' => $mailctext), array(4,5,7)).'</textarea>',
        '%comment_text_tags%'           => fpLanguage::returnLanguageConstant(LANG_COMMENT_ALLOWED_TAGS).': &lt;a href=&quot;&quot;&gt;&lt;/a&gt; &lt;b&gt; &lt;i&gt; &lt;sub&gt; &lt;em&gt; &lt;strong&gt; &lt;u&gt; &lt;blockquote&gt; &lt;p&gt;',
        '%smiley_list_description%'     => fpLanguage::returnLanguageConstant(LANG_COMMENT_SMILIES),
        '%smiley_list%'                 => implode(PHP_EOL, $smileyList),
        '%comment_antispam_plugin%'     => $antiSpamPlugin,
        '%comment_private_button%'      => fpConfig::get('confirm_comments') ? '' : '<label for="isprvt">'.fpLanguage::returnLanguageConstant(LANG_COMMENT_PRIVATE).'</label> <input type="checkbox" value="1" name="isprvt" '.$privateChecked.'>',
        '%comment_submit_button%'       => '<input type="submit" name="sbmtcomment" value="send">',
        '%comment_reset_button%'        => '<input type="reset" name="btnresetfrm" value="reset">',
    );    
    
    print fpTemplates::replaceMarker($replacements, $commentFormTemplate);
?>