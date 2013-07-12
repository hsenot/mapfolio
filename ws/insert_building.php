<?php
/**
 * Process the created building
 *
 */

# Includes
require_once("inc/database.inc.php");
require_once("inc/json.inc.php");

# Helper function to process user entered text
function sanitizeTextParameter ($p) {
    return str_replace("'", "''", $p);
}

# Retrieve URL arguments
try {
	$p_name = isset($_REQUEST['name']) ? $_REQUEST['name'] : '';
	$p_name = sanitizeTextParameter($p_name);
	$p_tags = isset($_REQUEST['hidden-tags']) ? $_REQUEST['hidden-tags'] : '';
	$p_tags_arr=explode(",",$p_tags);
	# Merging back the array and adding the quotes
	$p_tags_list="'".implode("','",$p_tags_arr)."'";
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
	$recordSet = $pgconn->prepare($sql);
	$recordSet->execute();

    // Getting the building number (somehow curr_val does not always work)
	$sql = "SELECT currval('community.building_id_seq') as c";
	//echo $sql;
	$recordSet = $pgconn->prepare($sql);
	$recordSet->execute();

	while ($row  = $recordSet->fetch(PDO::FETCH_ASSOC))
	{
		// Have to name the column instead of using index 0 to indicate 1st column
		$building_id = $row['c'];
	}

	// Now, associate the tags to the building
	// It is assumed here that the tags already exist in the tag table
	$sql = "INSERT INTO community.tag_building(tag_id,building_id) SELECT t.id,".$building_id." FROM community.tag WHERE label in (".$p_tags_list.")";
	$recordSet = $pgconn->prepare($sql);
	$recordSet->execute();

	exit('{"success":"true","building_id":"'.$building_id.'"}');
}
catch (Exception $e) {
	trigger_error("Caught Exception: " . $e->getMessage(), E_USER_ERROR);
}

?>