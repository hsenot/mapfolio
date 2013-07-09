<?php
/**
 * Process the created building
 *
 */

# Includes
require_once("database.inc.php");
require_once("json.inc.php");

# Retrieve URL arguments
try {
	$p_building_ids = $_REQUEST['building_ids'];
}
catch (Exception $e) {
    trigger_error("Caught Exception: " . $e->getMessage(), E_USER_ERROR);
}

# Performs the query and returns XML or JSON
try {
	// Opening up DB connection
	$pgconn = pgConnection();

	// Inserting the observation
	// Status: 0 => imported, 1=> created by user, 2 => deleted
	$sql = "UPDATE community.building SET status=2 WHERE id in (".$p_building_ids.")";
	//echo $sql;
	pg_query($pgconn,$sql);

	exit('{"success":"true","building_ids":"'.$p_building_ids.'"}');
}
catch (Exception $e) {
	trigger_error("Caught Exception: " . $e->getMessage(), E_USER_ERROR);
}

?>