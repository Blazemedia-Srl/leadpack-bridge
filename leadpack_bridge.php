<?php
/*
Plugin Name: LeadPackBridge
Description: Bridge per ottenere i dati dal servizio LeadPack
Version: 1.0.0
Author: Blazemedia
License: LGPL
*/

require_once __DIR__ .'/vendor/autoload.php';

use LPACK\LeadPackBridge;

define( 'LEADPACK_API',     'https://leadpack.atlanticmoon.com/partner/api/' );
define( 'LEADPACK_API_KEY', 'c32baa8a6a5f906a8e8b380fd8ceb2b119bcf47c8fa55c9b1b7ae89bf739ae77' );

/// crea una istanza del Bridge
$bridge = LeadPackBridge::getInstance();
