<?php

    final class fpModule_fpCutenewsCoverter_Converter {
          
            private $fpDBcon = null;

            /**
             * Der Konstruktor
             * @access public
             * @return void
             */
            public function __construct($fpDBcon) {
                $this->fpDBcon = $fpDBcon;
            }
         /**
          * Benutzer kopieren
          *
          * @access public
          * @param int $name Name des benutzers
          * @param int $email E-Mail-Adresse
          * @param int $sysusr Benutzername im System, bspw. zum einloggen
          */          
        public function copyUsers($name,$email,$sysusr) {
            
            $newUser = new fpAuthor();
            $newUser->setDisplayName($name);
            $newUser->setEmail($email);
            $newUser->setUserName($sysusr);
            $newUser->setPassword($sysusr);
            $newUser->setRegistertime(time());
            $newUser->setUserRoll(1);
            return $newUser->save();
        }

        /**
          * Kategorien kopieren
          * @access public
          * @param int $name Kategoriename
          * @param int $iconpath Pfad zum Icon der Kategorie
          * @param int $minulvl minimaler User-Level, um Kategorie zu verwenden
          */    
        public function copyCategories($name,$iconpath,$minulvl) {   
            if($minulvl > 3) { $minulvl = 3; }

            $sql = "
                INSERT INTO ".FP_PREFIX."_categories
                (id, catname, icon_path, minlevel)
                VALUES (NULL, '".$name."', '".$iconpath."', '".$minulvl."');
            "; 
            $result = $this->fpDBcon->exec($sql);	  
            if($result === false) { $this->fpDBcon->getError();return false; }
        }

        /**
          * News kopieren
          * @access public
          * @param int $newstime Verfassungszeit
          * @param int News-Kategorie
          * @param string News-Titel
          * @param string News-Autor
          * @param text News-Kategorie
          * @param bool kennzeichnung, ob News veröffentlicht werden soll
          */    
        public function copyCNpostToFPpost($newstime,$newscategory,$newstitle,$newsauthor,$newstext,$newsispreview = 0,$newsisarchived = 0) {
            $newscategory = str_replace(",",";",$newscategory);

            $newstext = str_replace("{nl}","",  trim($newstext));

            $sql = "SELECT id FROM ".FP_PREFIX."_authors WHERE sysusr like '".$newsauthor."';";
            $result = $this->fpDBcon->query($sql);	
            if($result === false) { $this->fpDBcon->getError();return false; }
            $userId = $this->fpDBcon->fetch($result);
            
            $countedAC = $this->fpDBcon->count("authors", "id", "id like '".$newscategory."'");
            if($countedAC == 0) { $newscategory = 1; }

            $fpNewsPost = new fpArticle();
            $fpNewsPost->setTitel($newstitle);
            $fpNewsPost->setContent($newstext);
            $fpNewsPost->setCategory($newscategory);
            $fpNewsPost->setArchived($newsisarchived);
            $fpNewsPost->setCommentsActive(($newsisarchived == 1) ? 0 : 1);
            $fpNewsPost->setPreview($newsispreview);
            $fpNewsPost->setPinned(0);
            $fpNewsPost->setAuthor($userId);
            $fpNewsPost->setWrittentime($newstime);
            return $fpNewsPost->save();
        }      
    }

?>