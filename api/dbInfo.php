<?php
    //api url filter
    if (strpos($_SERVER['REQUEST_URI'], "dbinfo.php")) {
        require_once 'utils.php';
        PlainDie();
    }

	function getServer() {
		return "localhost";
	}

	function getDatabaseName() {
		return "vsdb";
	}

	function getUserName() {
		return "root";
	}

	function getPassword() {
		return "";
	}
?>