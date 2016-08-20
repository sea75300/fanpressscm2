<?php if(!defined('LOGGED_IN_USER')) die(); ?>
<div id="news-search-box" style="display:none;">

    <input type="hidden" id="news-search-input-fn" value="<?php print $searchAll; ?>">
    <table style="width:100%;">
        <tr>      
            <td style="width:25%;">
                <?php
                    $rowUsers      = $fpUser->getAllUsers();
                    $rowCategories = $fpCategory->getCategories("all");
                ?>
                <select class="chosen-select-dialog" id="filter_authors" name="filter_authors" style="width: 100px;">
                    <option value="0"><?php fpLanguage::printLanguageConstant(LANG_EDIT_AUTHOR); ?></option>
                    <?php
                        foreach($rowUsers as $row) {
                            print "<option value=\"".$row->id."\">".htmlspecialchars_decode($row->sysusr)."</option>";
                        }
                    ?>
                </select>                        
            </td>
            <td style="width:25%;padding: 1px 0.5em;">
                <select class="chosen-select-dialog" id="filter_categories" name="filter_categories" style="width: 150px;">
                    <option value="0"><?php fpLanguage::printLanguageConstant(LANG_EDIT_CATEGORY); ?></option>
                    <?php
                        if(count($rowCategories) > 1) {
                            foreach($rowCategories as $row) {
                                print "<option value=\"".$row->id."\">".htmlspecialchars_decode($row->catname)."</option>";
                            }
                        } else {
                            print "<option value=\"".$rowCategories->id."\">".htmlspecialchars_decode($rowCategories->catname)."</option>";
                        }
                    ?>
                </select>                          
            </td>
            <td style="width:25%;padding: 1px 0px 1px 0.5em;">
                <select class="chosen-select-dialog" name="news-search-input-type" id="news-search-input-type" style="width: 100px;">
                    <option value="0"><?php fpLanguage::printLanguageConstant(LANG_EDIT_SEARCH_TITLE); ?></option>
                    <option value="1"><?php fpLanguage::printLanguageConstant(LANG_EDIT_SEARCH_TEXT); ?></option>
                </select>                         
            </td>
        </tr>
        <tr><td colspan="3" style="height:5px;"></td></tr>
        <tr>
            <td><label><?php fpLanguage::printLanguageConstant(LANG_EDIT_SEARCH_TIME); ?>:</label></td>  
            <td colspan="2" rowspan="3">
                <textarea id="news-search-input" class="news-search-box-margin" style="width:98%;height:75px;resize:none;"></textarea>
            </td>            
        </tr>
        <tr>
            <td style="width:25%;">
                <input type="text" id="news-search-input-time-from" style="width:90%;" size="10" maxlength="25" value="">
            </td>            
        </tr>        
        <tr>
            <td style="width:25%;">
                <input type="text" id="news-search-input-time-to" style="width:90%;" size="10" maxlength="25" value="">
            </td>            
        </tr>          
    </table>            
</div> 