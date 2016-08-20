<?php if(fpSecurity::checkPermissions ("addnews") || fpSecurity::checkPermissions ("editnews")) : ?>  
<div class="fpcm-dashboard-conatiner">
    <div class="fpcm-dashboard-conatiner-inner fpcm-dashboard-conatiner-inner-boxes ui-widget-content ui-corner-all ui-state-normal">
        <h3 class="ui-corner-top  ui-corner-all"><span class="fa fa-tasks"></span> <?php fpLanguage::printLanguageConstant(LANG_LASTESTNEWS_HEADLINE); ?></h3>
        <div>
            <?php
            
                $newsListCache = new fpCache('newsListCache');
            
                if($newsListCache->isExpired()) {
                    $dataArray = array(
                        'showlimit' => 5,
                        'startfrom' => 0
                    );
                    $rowNews = $fpNews->getNews("list",$dataArray);
                    $newsListCache->write(json_encode($rowNews), FPCACHEEXPIRE);
                } else {
                    $rowNews = json_decode($newsListCache->read());
                }
            ?>
            <ul>
                <?php
                    if(is_array($rowNews)) {
                        foreach($rowNews AS $row) {
                            if(fpConfig::currentUser('id') == $row->author || fpConfig::currentUser('usrlevel')== 1) {
                                print "<li><a href=\"editnews.php?fn=edit&amp;nid=".$row->id."\">".htmlspecialchars_decode($row->titel)."</a><br><small>".fpLanguage::returnLanguageConstant(LANG_EDITOR_AUTHOREDIT)." ".$row->name.", ".date(fpConfig::get('timedate_mask'), $row->writtentime)."</small></li>";
                            } else {
                                print "<li><strong>".htmlspecialchars_decode($row->titel)."</strong><br><small>".fpLanguage::returnLanguageConstant(LANG_EDITOR_AUTHOREDIT)." ".$row->name.", ".date(fpConfig::get('timedate_mask'), $row->writtentime)."h</small></li>";
                            }                             
                        }                        
                    }               
                ?>                            
            </ul>            
        </div>
    </div>
</div>    
<?php endif; ?>