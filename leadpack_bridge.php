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

$leadpackApi = getenv("LEADPACK_API") ? getenv("LEADPACK_API") : "https://leadpack.atlanticmoon.com/partner/api/";

$leadpackApiKey = getenv("LEADPACK_API_KEY") ? getenv("LEADPACK_API") : 'c32baa8a6a5f906a8e8b380fd8ceb2b119bcf47c8fa55c9b1b7ae89bf739ae77';

define( 'LEADPACK_API', $leadpackApi );
define( 'LEADPACK_API_KEY', $leadpackApiKey );


/// crea una istanza del Bridge
//$leadPackBridge = LeadPackBridge::getInstance();
