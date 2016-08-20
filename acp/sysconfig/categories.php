<?php
    /**
     * News-Kategorien
     * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
     * @copyright 2011-2014
     *
     */

     if(!defined("LOGGED_IN_USER")) { die(); }

    $catadded           = false;
    $catdel             = false;
    $catedited          = false;    
    $editActionString   = '';
    $iseditmode         = 0;
    
    if(LOGGED_IN_USER && fpSecurity::checkPermissions ("category")) {  
        print "<h2>".fpLanguage::returnLanguageConstant(LANG_SYSCFG_CATEGORIES)."</h2> ";

        if(isset($_GET["fn"]) && $_GET["fn"] == "edit" && isset($_GET["cid"])) {
            
            $category = new fpArticleCategory((int) $_GET["cid"]);

            $iseditmode = 1;   
            $editActionString = "&fn=edit&cid=".$category->getId();            
            
            if(isset($_POST["sbmecat"])) {
                $category->setCatname(fpSecurity::Filter1($_POST["catname"]));
                $category->setIconPath(fpSecurity::Filter1($_POST["iconpath"]));
                $category->setMinlevel((int) $_POST["ulvl"]);
                
                if($category->update()) {
                    fpMessages::showSysNotice(LANG_CAT_EDITATMSG);
                }
            }            

            include "parts/cateditor.php"; 
        } else {               
            
            $category       = new fpArticleCategory();            
            $fpCategoryObj  = new fpCategory($fpDBcon);
            
            if (isset($_POST["sbmcat"])) {
                if(!$fpCategoryObj->isFormEmpty($_POST["catname"],isset($_POST["ulvl"]))) {
                    $category->setCatname(fpSecurity::Filter1($_POST["catname"]));
                    $category->setIconPath(fpSecurity::Filter1($_POST["iconpath"]));
                    $category->setMinlevel((int) $_POST["ulvl"]);

                    if($category->save()) {
                        fpMessages::showSysNotice(LANG_CAT_ADDCATMSG);
                    } else {
                        fpMessages::showErrorText(LANG_CAT_CATEXISTMSG); 
                    }
                } else {
                    fpMessages::showErrorText(LANG_CAT_EMPTY);
                }
            }   

            if (isset($_POST["sbmdelcat"]) && isset($_POST["delcat"])) {
                    $delcats_arr = array_map('intval', $_POST["delcat"]);

                    $fpCategoryObj->deleteCategory($delcats_arr);
                    
                    fpMessages::showSysNotice(LANG_CAT_DELETED);
            }    

            $rows = $fpCategoryObj->getCategories("all", true);
?> 
    <div id="tabsGeneral">
        <ul>
            <li><a href="#tabs-category-existing"><?php fpLanguage::printLanguageConstant(LANG_CAT_EXISTING); ?></a></li>
            <li><a href="#tabs-category-create"><?php fpLanguage::printLanguageConstant(LANG_CAT_ADDCAT); ?></a></li>
        </ul>
        <div id="tabs-category-existing">
            <form method="post" action="sysconfig.php?mod=category">
                    <table width="100%" border="0">
                            <tr>
                                    <td class="tdheadline" style="text-align:left;"><?php fpLanguage::printLanguageConstant(LANG_CAT_NAME); ?></td>
                                    <td class="tdheadline" style="width:225px;"><?php fpLanguage::printLanguageConstant(LANG_CAT_ICON); ?></td>
                                    <td class="tdheadline" style="width:150px;"><?php fpLanguage::printLanguageConstant(LANG_CAT_MINLVL); ?></td>           
                                    <td class="tdheadline" style="width:25px;"><?php fpLanguage::printLanguageConstant(LANG_GLOBAL_DELETE); ?></td>         
                            </tr>
                            <?php foreach($rows AS $row) : ?>
                            <tr>      
                                    <td><a href="sysconfig.php?mod=category&fn=edit&cid=<?php print $row->id; ?>"><?php print htmlspecialchars_decode($row->catname); ?></a></td>
                                    <td class="tdtcontent"><img src="<?php print htmlspecialchars($row->icon_path, ENT_COMPAT | ENT_HTML401, FPSPECIALCHARSET); ?>" alt=""></td>
                                    <td class="tdtcontent"><?php print fpLanguage::replaceDBDataByLanguageData(htmlspecialchars_decode($row->leveltitle), $langReplaceDataArray); ?></td>
                                    <td class="tdtcontent"><input type="checkbox" name="delcat[]" value="<?php print $row->id; ?>"></td>      
                            </tr>      
                            <?php endforeach; ?>
                    </table>
                <div class="buttons fp-editor-buttons-fixed ui-widget-content ui-corner-all ui-state-normal">
                    <button type="submit" name="sbmdelcat" class="btnloader fp-ui-button"><?php fpLanguage::printOk(); ?></button>
                </div>
            </form>             
        </div>
        <div id="tabs-category-create">
        <?php
                include "parts/cateditor.php";
            }
        ?>            
        </div>
    </div>

<?php
        
    } else {
          if(!fpSecurity::checkPermissions("system")) { fpMessages::showNoAccess(); }
          
    }   
?>    