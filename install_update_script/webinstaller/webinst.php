<?php
    /**
     * FanPress CM 2 Web-Installer
     */

     $currentVersion = "2.5.5";
?>
<!DOCTYPE HTML>
<HTML>
    <head>
        <title>FanPress CM  $currentVersion - WebInstaller</title>    
        <meta http-equiv="Content-Language" content="de">
        <meta http-equiv="content-type" content= "text/html; charset=iso-8859-1">
        <meta name="robots" content="noindex, nofollow">  
        <style type="text/css">
        body {
            font-family: sans-serif;
            font-size: 10pt;
            color: #0048a9;
            background: #efeef3;
            margin:0;
            padding:0;
        }     
        h1 {
            font-size:1.5em;
            color: #000000;
            padding: 5px 0px;
            margin:0px;
        }       
        a, a:visited {
            font-weight: bold;
            text-decoration: none;  
            color: #0048a9;
        }

        a:hover, a:focus, a:active {
            font-weight: bold;
            text-decoration: none;  
            color: #3399ff;
        }

        b { color:#0048a9;padding:2px; }        
        </style>
    </head> 

    <body> 
        <div style="text-align: center;width: 500px;margin: 25% auto;">
<?php
     print "<h1>FanPress CM ".$currentVersion." Web Installer</h1>";
     if(ini_get('allow_url_fopen') == 1) {
        if(isset($_GET["startinst"])) {

            $update_folder = dirname(__FILE__)."/";
            
            define("UPDATE_FILE_REMOTE_FILE", "fanpress".$currentVersion."_full.zip");
            define("UPDATE_FILE_REMOTE_NAME", "http://nobody-knows.org/updatepools/fanpress/system/packages/".UPDATE_FILE_REMOTE_FILE);
            define("UPDATE_FILE_LOCAL_NAME", $update_folder.UPDATE_FILE_REMOTE_FILE);            
            
            $fopen_file_rem   = @fopen(UPDATE_FILE_REMOTE_NAME,"rb");	
            
            if($fopen_file_rem) {
                $fopen_file_local = fopen(UPDATE_FILE_LOCAL_NAME,"wb");

                while(!feof($fopen_file_rem)) {
                    $update_file_content = fgets($fopen_file_rem);
                    fwrite($fopen_file_local, $update_file_content);
                }

                fclose($fopen_file_rem);
                fclose($fopen_file_local);
                
                $update_file_zip = zip_open(UPDATE_FILE_LOCAL_NAME);

                $filecount = 0;
                $dircount = 0;                
                
                print "<ul style=\"margin:0;padding:0;font-size:0.75em;\" id=\"update_details\">";

                while($zip_entry = zip_read($update_file_zip)) {
                    $entry_name = zip_entry_name($zip_entry);
                    $entry_size = zip_entry_filesize($zip_entry);

                    if(strpos($entry_name,".")) {
                        print "<li style=\"background:#eeeeee;margin-left:35px;padding:3px;\">$entry_name has $entry_size bytes";

                        $tmp = fopen($update_folder.$entry_name,"wb");

                        $tmp_content = zip_entry_read($zip_entry,$entry_size);
                        fwrite($tmp,$tmp_content,$entry_size);

                        fclose($tmp);

                        $filecount++;

                        if(!@rename($update_folder.$entry_name,$fpbase.$entry_name)) {
                            if(!@is_writable($fpbase.$entry_name)) {
                                if(@chmod($fpbase.$entry_name,0775)) {
                                    if(!@rename($update_folder.$entry_name,$fpbase.$entry_name)) {
                                            print " &rarr; <b>FAILURE</b>!";
                                    } else {
                                        print " &rarr; <b>OK</b>";
                                    }
                                }
                            }
                        } else { print " &rarr; <b>OK</b>"; }
                        print "</li>\n";
                    } else {

                        print "<li style=\"list-style:none;background:#cccccc;padding:3px;\">".$entry_name;

                        if(!@mkdir($update_folder.$entry_name)) {
                                print " &rarr; <b>FAILURE</b>";
                        } else { print " &rarr; <b>OK</b>"; }

                        if(!@file_exists($fpbase.$entry_name)) {
                            if(!@mkdir($fpbase.$entry_name)) {
                                print " &rarr; <b>FAILURE</b>";
                            } else { print " &rarr; <b>OK</b>"; }
                        }
                        $dircount++;

                        print "</li>\n";
                    }
                    zip_entry_close($zip_entry);
                }

                zip_close($update_file_zip);
                unlink(UPDATE_FILE_LOCAL_NAME);

                print "</ul>";
                
                print "<p><a href=\"fanpress/install/index.php\">Proceed install</a></p>";  
                unlink('webinst.php');
            }             
        } else {
            if(is_writable(dirname(__FILE__))) {
                print "<p><a href=\"webinst.php?startinst=yes\">Start install</a></p>";
            } else {
                print "<p>Could not write into directory.</p>";
            }
        }
         
    } else {
        print "<p>The WebInstaller can't be executed. PHP \"allow_url_fopen\" is disabled.</p>";
    }	
    
?>
        </div>
    </body>
</html>
