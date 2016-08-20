<?php
    /**
     * FanPress CM Installer create admin user
     * @author Stefan Seehafer aka imagine <fanpress@nobody-knows.org>
     * @copyright (c) 2011-*2014, Stefan Seehafer
     * @license http://www.gnu.org/licenses/gpl.txt GPLv3
     */
?>  
  
  <form method="post" action="index.php?isstep=5&ilang=<?php print $lang; ?>">
    <p>
      <label for="autor"><?php fpLanguage::printLanguageConstant(L_ADMINUNAME); ?>:</label><br>
      <input type="text" name="usrname" size="60" maxlength="255" value="">
    </p>
    <p>
      <label for="password"><?php fpLanguage::printLanguageConstant(L_ADMINPWD); ?>:</label><br>
      <input type="password" name="password" size="60" maxlength="255">
    </p>
    <p>
      <label for="password"><?php fpLanguage::printLanguageConstant(L_ADMINPWD_CONFIRM); ?>:</label><br>
      <input type="password" name="password_confirm" size="60" maxlength="255">
    </p>    
    <p>
      <label for="name"><?php fpLanguage::printLanguageConstant(L_ADMINNAME); ?>:</label><br>
      <input type="text" name="name" size="60" maxlength="255" value="">
    </p>
    <p>
      <label for="email"><?php fpLanguage::printLanguageConstant(L_ADMINEMAIL); ?>:</label><br>
      <input type="text" name="email" size="60" maxlength="255" value="">
    </p>
    
    <p class="buttons">
        <button class="fp-ui-button" type="submit" name="btnadmin"><?php fpLanguage::printLanguageConstant(L_BTNNEXT); ?></button>    
    </p>    
  </form>