<?php
    /**
     * FanPress CM filesystem and smiley operations
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */

    final class fpFileSystem {
        private $fpDBcon;

        /**
         * Der Konstruktor
         * @return void
         */
        public function __construct($fpDBcon) {
            $this->fpDBcon = $fpDBcon;
        }

        /**
         * Der Destruktor
         */
        public function __destruct() {
            $this->fpDBcon = null;
        }

        /**
         * hochgeladenen Datei in Upload-Tabelle einfügen
         * @param string $filename
         * @param string $upldrid
         * @return boolean
         */  
        public function addUploadToFileStore($filename,$upldrid) {
            $fpCache = new fpCache();
            $fpCache->cleanup();
            
            $result = $this->fpDBcon->insert('uploads', 'filename,uploaderid,uploadtime', '?,?,?', array($this->fpDBcon->parseSingleQuotes($filename), $this->fpDBcon->parseSingleQuotes($upldrid), time()));	  
            if($result === false) { $this->fpDBcon->getError();return false; }
        }

	/**
         * Daten der hochgeladenen Dateien auslesen
         * @return mixed
         */         
        public function getFileStore() {
            $result = $this->fpDBcon->select('uploads', '*', 'id > 0 ORDER BY uploadtime DESC');	  
            if($result === false) { $this->fpDBcon->getError();return false; }
            return $this->fpDBcon->fetch($result, true);
        }        

        /**
         * Datei auf Upload-Tabelle entfernen
         * @param int $fsid ID der Datei in Upload-Tabelle
         * @return boolean|string
         */      
        public function deleteFromFileStore($fsid) {
            $result = $this->fpDBcon->select('uploads', 'filename', 'id = ?', array($fsid));
            if($result === false) { $this->fpDBcon->getError();return false; }
            $row    = $this->fpDBcon->fetch($result);
			
            if(!isset($row->filename)) return false;
            
            $fpCache = new fpCache();
            $fpCache->cleanup();            
            
            $delpath = FPUPLOADFOLDER.$row->filename;

            if(file_exists($delpath)) {
                if (is_writable($delpath) && unlink ($delpath)) {
                    if(file_exists(FPUPLOADFOLDER."thumb_".$row->filename)) {
                        unlink(FPUPLOADFOLDER."thumb_".$row->filename);
                    }
                    if(file_exists(FPFMGRTHUMBS."/thumb_".$row->filename)) {
                        unlink(FPFMGRTHUMBS."/thumb_".$row->filename);
                    }                        
                    $result = $this->fpDBcon->delete('uploads', 'id = ?', array($fsid));
                    if($result === false) { $this->fpDBcon->getError();return false; }
                    return array("fdel", $row->filename);
                } else {
                    return array("noprm", $row->filename);                    
                }
            } else {
                $result = $this->fpDBcon->delete('uploads', 'id = ?', array($fsid));
                if($result === false) { $this->fpDBcon->getError();return false; }
                
                return array("notext", $row->filename);			
            }
        }    

        /**
         * Prüft, ob Datei schon im Dateindex vorhanden ist
         * @param array $fileDataArray
         * Struktur: 'filename' => 'file.xyz' ODER 'fileid' => 1,
         * @since FPCM 2.3.0
         * @return boolean
         */
        public function fileExistsInStore($fileDataArray) {            
            if(isset($fileDataArray['filename'])) {                
                $res = $this->fpDBcon->count("uploads", "*", "filename LIKE '".$fileDataArray['filename']."'");
            } elseif(isset ($fileDataÁrray['fileid'])) {
                $res = $this->fpDBcon->count("uploads", "*", "id = ".$fileDataArray['fileid']);
            }

            if($res == 1) return true;
            
            return false;
        }
        
        /**
         * Baut Dateiindex neu auf
         * @since FPCM 2.3.0
         */
        public function rebuildFileStore() {
            $fpCache = new fpCache();
            $fpCache->cleanup();            
            
            $fileIndex = $this->getFileStore();
            
            $indexed_files = array();
            
            foreach ($fileIndex as $fileIndexEntry) {
                if(!file_exists(FPUPLOADFOLDER.$fileIndexEntry->filename)) {
                    $this->deleteFromFileStore($fileIndexEntry->id);              
                } else {
                    $indexed_files[] = $fileIndexEntry->filename;
                }
            }

            $files = $this->getDirectoryContent(FPUPLOADFOLDER);
            foreach ($files as $file) {
                $dateiinfo = pathinfo($file);
                if($file == 'index.html' || strpos($file, 'thumb_') !== false) continue;

                $oldFile = $file;
                if(!in_array($file, $indexed_files)) {
                    $file = $this->escapeFileName($file);
                    if(file_exists(FPUPLOADFOLDER.$file)) {
                        rename(FPUPLOADFOLDER.$file, FPUPLOADFOLDER.$oldFile);
                    }                    
                }

                if(!$this->fileExistsInStore(array('filename' => $file)) && file_exists(FPUPLOADFOLDER.$file)) {
                    $this->createImageThumb($file);
                    $this->addUploadToFileStore($file,fpConfig::currentUser('id'));                        
                }
            }                   
        }

        /**
         * Parst problematische Zeichen in Dateinamen
         * @param string $filename
         * @return string
         * @since FPCM 2.3.0
         */
        public function escapeFileName($filename) {
            if(fpConfig::get('new_file_uploader') == 1) {
                $filename = utf8_decode($filename);
            }

            $filename = strtolower($filename);  
            $filename = htmlentities ($filename, ENT_COMPAT | ENT_HTML401, FPSPECIALCHARSET);
            $textReplaces = array("&szlig;" => "ss", "&auml;" => "a", "&ouml;" => "o", "&uuml;" => "u");                        
            $filename = str_replace(array_keys($textReplaces), array_values($textReplaces), $filename);
            $filename = preg_replace('/[^A-Za-z0-9_.\-]/', '', $filename);
            return filter_var($filename, FILTER_SANITIZE_URL);
        }

       /**
        * Smiley hinzufügen
        * @param string $smiliecode Code des Smilies
        * @param string $smiliefile Dateiname der Smilie-Grafik
        * @return boolean
        */
        public function addSmilies($smiliecode,$smiliefile) {        
            if(!$this->existSmilie($smiliecode)) {
                $fpCache = new fpCache();
                $fpCache->cleanup();            

                $result = $this->fpDBcon->insert('smilies', 'sml_code,sml_filename', '?, ?', array($this->fpDBcon->parseSingleQuotes($smiliecode), $this->fpDBcon->parseSingleQuotes($smiliefile)));
                if($result === false) { $this->fpDBcon->getError();return false; }	

                return true;
            }

            return false;
        }    

        /**
         * Erzeugt Thumbnail
         * @param string $filename
         * @param bool $forceCreate
         * @return boolean
         */
        public function createImageThumb($filename, $forceCreate = false) {
            require_once FPBASEDIR.'/inc/lib/PHPImageWorkshop/ImageWorkshop.php';

            if(!file_exists(FPUPLOADFOLDER.$filename)) {
                return false;
            }            
            
            $currentFileData = $this->getImageInfo($filename);
            
            $width  = $currentFileData['width'];
            $height = $currentFileData['height'];            
            
            if($width > fpConfig::get('max_img_size_x') || $height > fpConfig::get('max_img_size_y') || $forceCreate) {

                require_once FPBASEDIR.'/inc/lib/PHPImageWorkshop/ImageWorkshop.php';
                
                $phpImgWsp = \PHPImageWorkshop\ImageWorkshop::initFromPath(FPUPLOADFOLDER.$filename);

                $maxResizeWidth    = fpConfig::get('max_img_thumb_size_x');
                $maxResizeHeight   = fpConfig::get('max_img_thumb_size_y');
                
                if($width == $height) {
                    $phpImgWsp->resizeByLargestSideInPixel($maxResizeHeight, true);
                } else {                    
                    if($maxResizeWidth < $maxResizeHeight) {                        
                        $maxResizeHeight = $height * $maxResizeWidth / $width;
                    } elseif($maxResizeWidth == $maxResizeHeight) {
                        $maxResizeWidth = $width * $maxResizeHeight / $height;
                    } else {                       
                        $maxResizeWidth = $width * $maxResizeHeight / $height;
                    }                                                            
                    $phpImgWsp->resizeInPixel($maxResizeWidth, $maxResizeHeight);                    
                }

                $phpImgWsp->save(FPUPLOADFOLDER, 'thumb_'.$filename);

                if(!file_exists(FPUPLOADFOLDER.'thumb_'.$filename)) {
                    return false;
                }                
            }       
        }
        
        /**
         * Erzeugt Thumbnail für Dateimanager-Liste
         * @param string $filename
         * @return boolean
         */
        public function createImageThumbSquare($filename) {
            
            require_once FPBASEDIR.'/inc/lib/PHPImageWorkshop/ImageWorkshop.php';

            if(!file_exists(FPUPLOADFOLDER.$filename)) {
                return false;
            }            
            
            $fileData = $this->getImageInfo($filename);
            
            $phpImgWsp = \PHPImageWorkshop\ImageWorkshop::initFromPath(FPUPLOADFOLDER.$filename);
            if($fileData['width'] <= 1500 || $fileData['height'] <= 1500) {
                $phpImgWsp->cropMaximumInPixel(0, 0, "MM");
            }
            $phpImgWsp->resizeInPixel(100, 100);
            $phpImgWsp->save(FPFMGRTHUMBS, '/thumb_'.$filename);
            
            if(!file_exists(FPFMGRTHUMBS.'/thumb_'.$filename)) {
                return false;
            }
        }

        /**
         * Gibt 
         * @param string $filename
         * @return array
         * @since FPCM2.5
         */
        public function getImageInfo($filename) {           
            
            if(!file_exists(FPUPLOADFOLDER.$filename)) {
                return array(
                    'name'      => $filename,
                    'type'      => null,
                    'width'     => 0,
                    'height'    => 0,
                    'imgtype'   => null,
                    'html'      => ''
                );                
            }
            
            $imgInfo = getimagesize(FPUPLOADFOLDER.$filename);
            
            return array(
                'name'      => $filename,
                'type'      => $imgInfo['mime'],
                'width'     => $imgInfo[0],
                'height'    => $imgInfo[1],
                'imgtype'   => $imgInfo[2],
                'html'      => $imgInfo[3]

            );
        }

        /**
         * prüfen ob Smilie schon extistert
         * @param string $smiliecode Smileycode
         * @return boolean
         */
        private function existSmilie($smiliecode) {
            $counted = $this->fpDBcon->count("smilies", "id", "sml_code like '".$this->fpDBcon->parseSingleQuotes($smiliecode)."'");            
            if($counted > 0) { return true; } else { return false; }                  
        }  

        /**
         * Ist Smiley Einfüge Formular leer
         * @param string $smiliecode Smileycode
         * @param string $smiliefile Dateipfad#
         * @return boolean
         */         
        public function isFormEmpty($smiliecode,$smiliefile) {
              if (empty($smiliecode)) { return true; }
              if (empty($smiliefile)) { return true; }

              return false;
        }       

        /**
         * Smilies aus Datenbank auslesen
         * @return mixed
         */            
        public function getSmilies() {
            $result = $this->fpDBcon->select('smilies');
            if($result === false) { $this->fpDBcon->getError();return false; }
            return $this->fpDBcon->fetch($result, true);
        }        

        /**
         * Löchen Smiley
         * 
         * @param int $smlid Smilie-ID
         * @return boolean
         */              
        public function deleteSmilies($smlids) {
            // Datei auswählen      
            $result = $this->fpDBcon->select('smilies', 'sml_filename', "id IN (".implode(',', $smlids).")");
            if($result === false) { $this->fpDBcon->getError();return false; }
            $files    = $this->fpDBcon->fetch($result);

            $fext = false;      

            foreach ($files as $file) {
                $delpath = FPBASEDIR."/img/smilies/".$file->sml_filename;

                if(file_exists($delpath)) {
                    unlink ($delpath);
                    $fext = true;
                }                
            }

            /* Eintrag in Datenbank löschen */      
            $result = $this->fpDBcon->delete('smilies', "id IN (".implode(',', $smlids).")");	  
            if($result === false) { $this->fpDBcon->getError();return false; }

            $fpCache = new fpCache();
            $fpCache->cleanup();        

            return $fext;
        }    

        /**
         * Gibt Inhalt eines Ordners wieder
         * @param string $directory
         * @return array
         * @since FPCM 2.2.0
         */
        public function getDirectoryContent($directory, $additionExclude = null, $includeSubDirs = false) {
            if(is_dir($directory) && file_exists($directory)) {
                $return = array();

                $files = scandir($directory);
                foreach ($files as $file) {
                    if((is_dir($file) && !$includeSubDirs) || (!is_null($additionExclude)) && in_array($file, $additionExclude)) { continue; }
                    if($file != "." && $file != ".." && $file != "" && strpos($file, '.db') === false) {
                        $return[] = $file;
                    }
                }                  
                return $return;
            }


            return array();
        }

        /**
         * Pfad rekursiv löschen
         * @param string $path Pfad, der gelöscht werden soll
         * @return int
         */
        public function deleteRecursive ($path) {
            if (!is_dir ($path)) { return -1; }
            $dir = @opendir ($path);
            if (!$dir) { return -2; }

            while (($entry = @readdir($dir)) !== false) {
                    if ($entry == '.' || $entry == '..') { continue; }

                    if (is_dir ($path.'/'.$entry)) {
                            $res = $this->deleteRecursive ($path.'/'.$entry);
                            if ($res == -1) {
                                    @closedir ($dir);
                                    return -2;
                            } else if ($res == -2) {
                                    @closedir ($dir);
                                    return -2;
                            } else if ($res == -3) {
                                    @closedir ($dir);
                                    return -3;
                            } else if ($res != 0) {
                                    @closedir ($dir);
                                    return -2;
                            }
                    } else if (is_file ($path.'/'.$entry) || is_link ($path.'/'.$entry)) {
                            $res = @unlink ($path.'/'.$entry);
                            if (!$res) {
                                    @closedir ($dir);
                                    return -2;
                            }
                    } else {
                            @closedir ($dir);
                            return -3;
                    }
            }

            @closedir ($dir);
            $res = @rmdir ($path);

            if (!$res) { return -2; }

            return 0;
        }
        
        /**
         * Lädt Datei von Server
         * @param string $updateFolder Ordner zum Download
         * @param string $updateFileNameRemote Dateiname, welche runterladen werden soll
         * @param string $updateFileRemote URL zu Datei auf Server
         */
        public function downloadPackage($updateFolder, $updateFileNameRemote, $updateFileRemote, $showstart = true) {
            fpMessages::writeToSysLog("package download ".$updateFileRemote); 
            
            $remoteFile = @fopen($updateFileRemote,"rb");	

            if($remoteFile) {
                $localFile = fopen($updateFolder.$updateFileNameRemote,"wb");
                while(!feof($remoteFile)) {
                    $fileContent = fgets($remoteFile);
                    fwrite($localFile, $fileContent);
                }

                fclose($remoteFile);
                fclose($localFile);  

                $md5_test_rem   = sha1_file($updateFileRemote);
                $md5_test_local = sha1_file($updateFolder.$updateFileNameRemote);                        

                if($md5_test_rem == $md5_test_local) { return true; }
            }
            
            return false;
        }

        /**
         * entpackt ZIP-Datei in Updater und kopiert diese an ensprechende Position in System
         * @param string $unzipFile Datei, die entpackt werden soll
         * @param string $pathToUnzipFile Pfad, wohin entpackt werden soll
         * @param bool $moduleUpdate Root-Pfad, wenn Modul aktualisiert wird
         */
        public function updaterUnzipAndCopy($unzipFile, $pathToUnzipFile, $moduleUpdate = false) {

            if($moduleUpdate) {
                $fpBaseDirTop = FPBASEDIR."/inc/modules/";
            } else {
                $fpBaseDirTop = dirname(FPBASEDIR)."/";
            }

            fpMessages::writeToSysLog("update package unzip and copy ".$unzipFile);

            $zip = new ZipArchive();
            $res = $zip->open($unzipFile);
            
            if($res !== TRUE) {
                fpMessages::writeToSysLog("error opening update package ".$unzipFile);
                return false;
            }
            
            
            $fileList = array();
            for($i=0;$i<$zip->numFiles;$i++) {
                $zipFileName = $fpBaseDirTop.$zip->getNameIndex($i);
                
                if(file_exists($zipFileName) && !is_writable($zipFileName)) {
                    chmod($zipFileName, 0777);
                }
                
                clearstatcache();
                
                $fileCheck = $zip->getNameIndex($i).' > ';
                if(file_exists($zipFileName)) {
                    $fileCheck .= is_writable($zipFileName) ? LANG_CHECK_WRITE_OK : LANG_CHECK_WRITE_FAILURE;
                } else {
                    $fileCheck .= 'OK';
                }
                $fileList[] = "<li>$fileCheck</li>";
            }
            
            if(count($fileList)) {
                print "<button class=\"fp-ui-button updater-file-list-show\">".fpLanguage::returnLanguageConstant(LANG_AUTO_UPDATE_FILELLIST)."</button>";
                print "<ul class=\"updater-file-list\">".implode(PHP_EOL, $fileList)."</ul>";                
            }
            
            if(!$zip->extractTo($pathToUnzipFile)) {
                fpMessages::writeToSysLog("error extracting update package ".$unzipFile);
                
                $zip->close();
                
                return false;
            }
            $zip->close();

            $this->copyRecursive($pathToUnzipFile, $fpBaseDirTop, array(basename($unzipFile), 'index.html'));
            
            return true;
        }
        
        /**
         * Kopiert Verzeichnis reskusiv
         * @param string $source
         * @param string $destination
         */
        public function copyRecursive($source, $destination, $exclude = array()) {
            $dir = opendir($source);

            if(!file_exists($destination)) @mkdir($destination, 0777);
            while(false !== ( $file = readdir($dir)) ) {
                if (( $file != '.' ) && ( $file != '..' )) {
                    if ( is_dir($source . '/' . $file) ) {
                        $this->copyRecursive($source . '/' . $file,$destination . '/' . $file);
                    } else {
                        if(!empty($destination) && !empty($file) && file_exists($destination . '/' . $file) && !is_writable($destination . '/' . $file)) {
                            @chmod($destination . '/' . $file, 0777);
                        }
                        if(count($exclude) && in_array($file, $exclude)) continue;
                        @copy($source . '/' . $file,$destination . '/' . $file);
                    }
                }
            }
            closedir($dir);
        }        
    }
?>