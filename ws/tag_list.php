<?php
/**
 * Returns a list of assets from the database as JSON file
 */

# Includes
require_once("inc/database.inc.php");
require_once("inc/json.inc.php");

# Set arguments for error email
$err_user_name = "Herve";
$err_email = "herve.senot@groundtruth.com.au";

# Performs the query and returns XML or JSON
try {
	$format = 'json';
	$sql = "SELECT label as tag FROM community.tag ORDER BY label";
	$pgconn = pgConnection();
	if (!$pgconn) {
    	echo "Not connected : " . pg_error();
    	exit;
	}

    /*** fetch into an PDOStatement object ***/
	$recordSet = $pgconn->prepare($sql);
	$recordSet->execute();
	if (!$recordSet) {
	    echo $sql;
	    echo "An SQL error occured.\n";
	    exit;
	}

	header("Content-Type: application/json");
	echo rs2json($recordSet,"tags");
}
catch (Exception $e) {
	//trigger_error("Caught Exception: " . $e->getMessage(), E_USER_ERROR);
	echo $sql;
	echo "An SQL error occured.\n";
}

?>