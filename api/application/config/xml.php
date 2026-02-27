<?php  if (!defined('BASEPATH')) exit('No direct script access allowed');

/*
**KEY untuk menambahkan keamana password
*/
$config["encryption_key"] = "Paruh-waktu!**!";
$config["generate_token_key"] = "load2018!**!";

/*
 * Mengijinkan htaccess berjalan pada applikasi ini
 */
$config['allow_htaccess'] = TRUE;

/*
 * Secretkey Token
 */
$config['client_service'] = 'ParuhWaktuClient2018';
$config['auth_key']       = 'ParuhWaktuKey2018';
$config['content_type']   = 'application/x-www-form-urlencoded';
$config['secretkey']      = "kode_rahasia_kamu";


/*
 * Path untuk upload file campaigns
 */
$config['path_campaigns'] = "clients/campaigns/";

/*
 * Email sender
 */
$config['project_name'] = "Paro Waktu";
$config['project_email_sender'] = "info@parowaktu.co.id";