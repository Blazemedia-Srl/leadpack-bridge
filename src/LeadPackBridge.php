<?php 

namespace LPACK;

use LPACK\LeadPack;

/**
 * Generate a Singleton for LeadPack
 */
class LeadPackBridge {

    private static $instance = null;

    private function __construct() { }
    
    public static function getInstance() {
      
        if(self::$instance == null) {   
        
            self::$instance = new LeadPack( LEADPACK_API, LEADPACK_API_KEY );
        }
        
        return self::$instance;
    }

}
