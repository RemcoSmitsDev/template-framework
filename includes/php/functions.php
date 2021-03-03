<?php
// define session
session_start();
ob_start();

require_once(__dir__."/config.php");

function db_make_connection(){
	return new Datebase();
}

function get_header() {
	require_once("head.php");
}

function get_footer() {
	require_once("footer.php");
}

function get_cookies() {
	return '';
}
