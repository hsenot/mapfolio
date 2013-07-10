<?php
/**
 * Process the created building
 *
 */

# Includes
require_once("database.inc.php");
require_once("json.inc.php");

# Helper function to process user entered text
function sanitizeTextParameter ($p) {
    return str_replace("'", "''", $p);
}

# Retrieve URL arguments
try {
	$p_name = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
	$p_name = sanitizeTextParameter($p_name);
	$p_tags = isset($_REQUEST['hidden-tags']) ? $_REQUEST['hidden-tags'] : '';
	$p_geom = isset($_REQUEST['geom']) ? $_REQUEST['geom'] : '';
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
	$sql = "INSERT INTO community.building(name,source_id,status,the_geom) VALUES ('".$p_name."',0,1,ST_GeomFromText('".$p_geom."',4326));";
	//echo $sql;
	pg_query($pgconn,$sql);

    // Getting the building number (somehow curr_val does not always work)
	$sql = "SELECT currval('community.building_id_seq') as c";
	//echo $sql;
	$recordSet = pg_query($pgconn,$sql);

	while ($row  = pg_fetch_assoc($recordSet))
	{
		// Have to name the column instead of using 0 to indicate 1st column
		$building_id = $row['c'];
	}

	exit('{"success":"true","building_id":"'.$building_id.'"}');
}
catch (Exception $e) {
	trigger_error("Caught Exception: " . $e->getMessage(), E_USER_ERROR);
}

?>