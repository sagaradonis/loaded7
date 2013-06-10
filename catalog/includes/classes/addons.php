<?php
/*
  $Id: cache.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
class lC_Addons {

  // class contructor 
  public function __construct() {
    
    if (empty($_GET) === false) {
      $first_array = array_slice($_GET, 0, 1);
      $module = lc_sanitize_string(basename(key($first_array)));    
    
      if (array_key_exists('login', $_GET)) return false;
    }

    $this->_initialize();
  } 
  
  // private methods
  private function _initialize() {
    global $lC_Vqmod;
    
    $lC_DirectoryListing = new lC_DirectoryListing(DIR_FS_CATALOG . 'addons');
    $lC_DirectoryListing->setRecursive(true);
    $lC_DirectoryListing->setIncludeDirectories(false);
    $lC_DirectoryListing->setCheckExtension('php');
    $lC_DirectoryListing->setStats(true);
      
    $enabled = '';
    $lC_Addons_data = array();
    foreach ( $lC_DirectoryListing->getFiles() as $addon ) { 
      $ao = utility::cleanArr($addon);  

      if ($ao['name'] != 'controller.php') continue;
      
      $nameArr = explode('/', $ao['path']);
      $class = $nameArr[count($nameArr)-2];

      if (file_exists($ao['path'])) {
        include_once($lC_Vqmod->modCheck($ao['path']));
        $GLOBALS[$class] = new $class();
        
        $_SESSION['lC_Addons_data'][$class] = array('type' => $GLOBALS[$class]->getAddonType(),
                                                    'title' => $GLOBALS[$class]->getAddonTitle(),
                                                    'description' => $GLOBALS[$class]->getAddonDescription(),
                                                    'rating' => $GLOBALS[$class]->getAddonRating(),
                                                    'author' => $GLOBALS[$class]->getAddonAuthor(),
                                                    'authorWWW' => $GLOBALS[$class]->getAddonAuthorWWW(),
                                                    'thumbnail' => $GLOBALS[$class]->getAddonThumbnail(),
                                                    'version' => $GLOBALS[$class]->getAddonVersion(),
                                                    'compatibility' => $GLOBALS[$class]->getCompatibility(),
                                                    'installed' => $GLOBALS[$class]->isInstalled(),
                                                    'enabled' => $GLOBALS[$class]->isEnabled());  
        
        if ($GLOBALS[$class]->isEnabled()) $enabled .= $addon['path'] . ';';
      }
    }   
       
    if ($enabled != '') $enabled = substr($enabled, 0, -1);
    if (!file_exists(DIR_FS_WORK . 'cache/addons.cache')) {
      file_put_contents(DIR_FS_WORK . 'cache/addons.cache', serialize($enabled));
    }    
  }
}
?>