<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

    function getHead(){
    require_once(APPPATH."views/header.php");
    }   

    function getSubHead(){
    require_once(APPPATH."views/sub_header.php");
    }

    function getFooter(){
    require_once(APPPATH."views/footer.php");
    }