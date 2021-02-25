<?php
// define session
session_start();
ob_start();

require_once(__dir__."/config.php");
require_once(__dir__."/class/Login.php");

function db_make_connection(){
	$db_servername = DB_HOST;
	$db_username = DB_USER;
	$db_password = DB_PASS;
	$db_name = DB_NAME;

	// Create connection
	$connection = new mysqli($db_servername, $db_username, $db_password, $db_name);
	$connection->set_charset('utf8');

	// Check connection
	if ($connection->connect_error) {
		die('Connection failed: ' . $connection->connect_error);
	}

	// Return connection
	return $connection;
}

function get_header() {
	require_once("head.php");
}

function get_footer() {
	require_once("footer.php");
}

function get_content($content) {
	//include file
	require_once($_SERVER['DOCUMENT_ROOT'] . "/templates/content-templates/$content.php");
}

function get_cookies() {
	return '';
}
