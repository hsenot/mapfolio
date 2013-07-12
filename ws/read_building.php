<?php
/**
 * Process the created building
 *
 */

# Includes
require_once("inc/database.inc.php");
require_once("inc/json.inc.php");

# Retrieve URL arguments
try {
	$p_building_id = $_REQUEST['building_id'];
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
	$sql = "SELECT name,round(ST_Area(ST_Transform(the_geom,3111))::numeric,0) as area_m2,(select array_to_string(array(select t.label from community.tag t, community.tag_building tb where tb.building_id=".$p_building_id." and tb.tag_id=t.id),',')) as tags FROM community.building WHERE id in (".$p_building_id.")";
	//echo $sql;
	$recordSet = $pgconn->prepare($sql);
	$recordSet->execute();

	header("Content-Type: application/json");
	echo rs2json($recordSet,"rows");
}
catch (Exception $e) {
	trigger_error("Caught Exception: " . $e->getMessage(), E_USER_ERROR);
}

?>