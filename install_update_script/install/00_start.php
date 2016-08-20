<?php
    /**
     * FanPress CM Installer Start
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-*2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    if (version_compare (phpversion(),"5.3.3",">=")) :
        if(!class_exists("PDO")) {
            fpMessages::showErrorText("PHP Data Objects class (PDO) was not found!");die();
        }
        
        if(ini_get('allow_url_fopen') == 0)
            fpMessages::showSysNotice("allow_url_fopen is disabled.");
        
        if(!function_exists('curl_init'))
            fpMessages::showSysNotice("cUrl extension not found.");
        
        if(!function_exists('getimagesize'))
            fpMessages::showSysNotice("GD Lib extension not found.");                
        
        $writable = true;
        
        $checkFolders = array('upgrade', 'inc', 'styles', 'inc/modules', 'data', 'data/cache', 'data/logs', 'data/revisions', 'data/upload', 'data/fmgrthumbs');
        
        foreach ($checkFolders as $checkFolder) {
            if(!is_writable(FPBASEDIR."/$checkFolder/")) {
                if(!@chmod(FPBASEDIR."/$checkFolder/", 0777)) {
                    fpMessages::showErrorText("Please chmod the \"$checkFolder\" directory to 777.");
                    $writable = false;
                }
            }            
        }
        
        clearstatcache();
        
        if($writable) :
?>
    <div>
        <p>
        <?php
            $handle = opendir (FPBASEDIR."/lang/");
            while ($datei = readdir ($handle)) {
                $dateiinfo = pathinfo($datei);							 
                if($datei != "." && $datei != ".." && $datei != "index.html") {
        ?>
                <a class="fp-ui-button" href="index.php?isstep=1&ilang=<?php print $dateiinfo['filename']; ?>"><?php print file_get_contents(FPBASEDIR."/lang/".$dateiinfo['filename']."/lang.cfg"); ?></a>
        <?php
                }
            }
            closedir($handle);												 
        ?>
        </p>
        <p>You can install and select other languages after Installation in admin control panel.</p>
    </div> 
<?php
        endif;
    else :
        fpMessages::showErrorText("FanPress CM 2.4.0 requires PHP PHP 5.3.3 or higher. Contact your host for a newer version.<br>Your PHP Version is PHP ".phpversion());
    endif;
 ?>