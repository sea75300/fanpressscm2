<?php
 /**
   * FanPress CM Style Footer
   * @author Stefan Seehafer aka imagine (fanpress@nobody-knows.org)
   * @copyright 2011-2014
   *
   */

    if(!defined("LOGGED_IN_USER")) { die(); }
?>
            </div>

            <div class="clear"></div>           
        </div>
        
        <div class="footer">
            <div class="footer-text">
                <b><?php fpLanguage::printLanguageConstant(LANG_HEADER_VERSIONTXT); ?></b> <?php print FPSYSVERSION; ?> &bull;
                &copy; 2011-2014 <a href="http://nobody-knows.org/download/fanpress-cm/" target="_blank">nobody-knows.org</a>                    
            </div>
        </div>  
        
        <?php fpDebugOutput(); ?>
    </body>
</html>
