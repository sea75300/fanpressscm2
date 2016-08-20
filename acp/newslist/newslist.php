<?php
    /**
     * Listentemplate für News-Ausgabe
     * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
     * @copyright 2011-2014
     *
     */
    if(!defined("LOGGED_IN_USER")) { die(); }
?>
<script type="text/javascript">
    var fpNewsListActionConfirmMsg = '<?php fpLanguage::printLanguageConstant(LANG_EDIT_ACTION_CONFIRM_MSG); ?>';
</script>
<div id="tabsGeneral">
    <ul>
        <li><a href="#tabs-news-edit">
        <?php if(isset($_GET["fn"]) && $_GET["fn"] == "editactive" || isset($_POST['searchfn']) && $_POST['searchfn'] == 1) : ?>
            <?php fpLanguage::printLanguageConstant(LANG_EDIT_SUB_ACTIVE); ?>
        <?php elseif(isset($_GET["fn"]) && $_GET["fn"] == "editarchive" || isset($_POST['searchfn']) && $_POST['searchfn'] == 2) : ?>
            <?php fpLanguage::printLanguageConstant(LANG_EDIT_SUB_ARCHIVE); ?>
        <?php else : ?>
            <?php fpLanguage::printLanguageConstant(LANG_EDIT_SUB_ALL); ?>
        <?php endif; ?>
            </a></li>
        <?php if(fpConfig::get('use_trash') && !isset($_POST["search"])) : ?>
        <li><a href="#tabs-news-trash"><?php fpLanguage::printLanguageConstant(LANG_EDIT_TRASHHEADLINE); ?></a></li>
        <?php endif; ?>
    </ul>

    <div id="tabs-news-edit">
    <?php        
        $maxcmt = 0;
        if(isset($_GET["fn"]) && $_GET["fn"] == "editactive") {  
            $showActive = true;
            $rowNews = $fpNews->getNews("editactive",array());
        } elseif(isset($_GET["fn"]) && $_GET["fn"] == "editarchive") {
            $showArchived = true;
            $rowNews = $fpNews->getNews("editarchive",array());
        } elseif(!isset($_POST["search"])) {
            $rowNews = $fpNews->getNews("editmode",array());
        }

        $lastMonthRow = '';     
        
        if(!count($rowNews)) :
            fpMessages::showSysNotice(LANG_NEWS_NOTFOUND_LIST);
        else :        
    ?>        
        
        <table class="news-list-table">
            <tr>
                <td class="tdheadline news-row-open-btn"></td>
                <td class="tdheadline news-row-title"><?php fpLanguage::printLanguageConstant(LANG_EDIT_TITLE); ?></td>
                <td class="tdheadline news-row-category"><?php fpLanguage::printLanguageConstant(LANG_EDIT_CATEGORY); ?></td>
                <td class="tdheadline news-row-comments"><?php fpLanguage::printLanguageConstant(LANG_EDIT_COMMENTS); ?></td>
                <td class="tdheadline news-row-micons"></td>
                <td class="tdheadline news-row-select"><input type="checkbox" id="fileselectboxall" value=""></td>
            </tr>
            
    <?php foreach($rowNews AS $row) : ?>
        
    <?php
        $cmts = $fpComment->countCommentsOfNews($row->id,1);
        $maxcmt = $maxcmt + $cmts;

        $monthRow = fpLanguage::getMonthList(date('n', $row->writtentime)).' '.date('Y', $row->writtentime);
    ?>
    
        <?php if($monthRow != $lastMonthRow) : ?>
            <tr class="news-list-row news-list-row-head">
                <td colspan="6"><h3>
                    <?php print $monthRow; ?>
                    <input type="checkbox" class="news-list-row-head-selectall" smonth="<?php print date('my', $row->writtentime); ?>" value="">
                </h3></td>
            </tr>
        <?php endif; ?>            
            
        <?php $lastMonthRow = $monthRow; ?>
            <tr class="news-list-row">
                <td class="news-row-open-btn">
                    <a class="news-list-row-news-open-link" href="<?php print fpConfig::get('system_url'); ?>?fn=cmt&amp;nid=<?php print $row->id; ?>" target="_blank">
                        <?php fpLanguage::printLanguageConstant(LANG_EDIT_OPENNEWS); ?>
                    </a>
                </td>
                <td class="news-row-title">
                    <?php if(fpConfig::currentUser('id') == $row->author || fpConfig::currentUser('usrlevel') == 1 || fpSecurity::checkPermissions('editallnews')) :?>
                        <a title="<?php fpLanguage::printLanguageConstant(LANG_EDITOR_LASTEDIT); ?>  <?php print date(fpConfig::get('timedate_mask'), $row->editedtime); ?>" href="editnews.php?fn=edit&amp;nid=<?php print $row->id; ?>"><strong><?php print htmlspecialchars_decode($row->titel); ?></strong></a>
                    <?php else : ?>
                        <strong><?php print htmlspecialchars_decode($row->titel); ?></strong>
                    <?php endif; ?>

                    <div class="editor_meta2 editor_meta2_list">
                        <b><?php fpLanguage::printLanguageConstant(LANG_EDITOR_AUTHOREDIT); ?></b>
                        <?php print htmlspecialchars_decode($row->name); ?> &bull;
                        <?php print date(fpConfig::get('timedate_mask'), $row->writtentime); ?>
                    </div>
                </td>
                <?php 
                    $categories  = "";
                    if(isset($_POST["filter_categories"]) && $_POST["filter_categories"] != 0) {
                        $row_cat_info = $fpCategory->getCategories($_POST["filter_categories"]);

                        $categories   = $row_cat_info->catname;
                    } else {
                        $cat_array = explode(";",$row->category);
                        $catcount  = count($cat_array);

                        $row_cat_info = array();

                        for($i=0;$i<$catcount;$i++) {	  
                            $singleCategory = $fpCategory->getCategories($cat_array[$i]);                                        
                            if(!empty($singleCategory->catname)) { $row_cat_info[] = $singleCategory->catname; }
                        }
                        $categories = implode(", ", $row_cat_info);
                    }
                ?>
                <td class="tdtcontent news-row-category"><?php print htmlspecialchars_decode($categories); ?></td>
                <td class="tdtcontent news-row-comments <?php if($fpComment->hasPrivateOrNotConfirmedComments($row->id)) { print " important-notice-text"; } ?>"><?php print $cmts; ?></td>
                <td class="tdtcontent editor_meta2 news-row-micons">
                <?php include FPBASEDIR.'/acp/editor/usermeta_status.php'; ?>
                </td>
                <td class="tdtcontent news-row-select"><input type="checkbox" class="fileselectbox fileselectbox<?php print date('my', $row->writtentime); ?>" name="newsid[]" value="<?php print $row->id; ?>"></td>
            </tr>
            <?php endforeach; ?>
            <tr class="news-list-row">
                <td colspan="3"></td>
                <td class="tdtcontent"><?php print $maxcmt; ?></td>
                <td></td>
            </tr>
        </table> 
        
        <?php endif; ?>
    </div>
    
    <?php if(fpConfig::get('use_trash') && !isset($_POST["search"])) : ?>    
        <div id="tabs-news-trash">
        <?php        
            if(isset($_GET["fn"]) && $_GET["fn"] == "editactive") {  
                $showActive = true;
                $rowNews = $fpNews->getNews("editactive",array('deleted' => 1));
            } elseif(isset($_GET["fn"]) && $_GET["fn"] == "editarchive") {
                $showArchived = true;
                $rowNews = $fpNews->getNews("editarchive",array('deleted' => 1));
            } elseif(!isset($_POST["search"])) {
            $rowNews = $fpNews->getNews("editmode",array('deleted' => 1));
        }

            if(!count($rowNews)) :
                fpMessages::showSysNotice(LANG_NEWS_NOTFOUND_LIST);
            else :        
        ?>        

            <table class="news-list-table">
                <tr>
                    <td class="tdheadline news-row-title"><?php fpLanguage::printLanguageConstant(LANG_EDIT_TITLE); ?></td>
                    <td class="tdheadline news-row-category"><?php fpLanguage::printLanguageConstant(LANG_EDIT_CATEGORY); ?></td>
                    <td class="tdheadline news-row-micons"></td>
                    <td class="tdheadline news-row-select"></td>
                </tr>

                <?php foreach($rowNews AS $row) : ?>
                <tr class="news-list-row">
                    <td class="news-row-title">
                        <strong><?php print htmlspecialchars_decode($row->titel); ?></strong>

                        <div class="editor_meta2 editor_meta2_list">
                            <b><?php fpLanguage::printLanguageConstant(LANG_EDITOR_AUTHOREDIT); ?></b>
                            <?php print htmlspecialchars_decode($row->name); ?> &bull;
                            <?php print date(fpConfig::get('timedate_mask'), $row->writtentime); ?>
                        </div>
                    </td>
                    <?php 
                        $categories  = "";
                        if(isset($_POST["filter_categories"]) && $_POST["filter_categories"] != 0) {
                            $row_cat_info = $fpCategory->getCategories($_POST["filter_categories"]);

                            $categories   = $row_cat_info->catname;
                        } else {
                            $cat_array = explode(";",$row->category);
                            $catcount  = count($cat_array);

                            $row_cat_info = array();

                            for($i=0;$i<$catcount;$i++) {	  
                                $singleCategory = $fpCategory->getCategories($cat_array[$i]);                                        
                                if(!empty($singleCategory->catname)) { $row_cat_info[] = $singleCategory->catname; }
                            }
                            $categories = implode(", ", $row_cat_info);
                        }
                    ?>
                    <td class="tdtcontent news-row-category"><?php print htmlspecialchars_decode($categories); ?></td>
                    <td class="tdtcontent editor_meta2 news-row-micons">
                    <?php include FPBASEDIR.'/acp/editor/usermeta_status.php'; ?>
                    </td>
                    <td class="tdtcontent news-row-select"><input type="checkbox" name="trashids[]" value="<?php print $row->id; ?>"></td>
                </tr>
                <?php endforeach; ?>
            </table> 

            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>