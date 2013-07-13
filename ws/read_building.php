<?php
/**
 * Reads information about a single building (within a schema)
 */

require_once("inc/database.inc.php");
require_once("inc/json.inc.php");

# Schema should always be passed
$schema='';
if (isset($_REQUEST['schema']) and !empty($_REQUEST['schema']))
{
    $schema=$_REQUEST['schema'];
}
else
{
    exit("Parameter 'schema' is required to use the web service.");
}

try {
	# Parameters
	$p_building_id = $_REQUEST['building_id'];

	# Opening up DB connection
	$pgconn = pgConnection();

	# Selecting the building attributes of interest
	$sql = "SELECT b.id,b.name,(select count(*) from ".$schema.".tag_building tb where tb.building_id=b.id) as c,round(ST_Area(ST_Transform(b.the_geom,3111))::numeric,0) as area_m2,(select array_to_string(array(select t.label from ".$schema.".tag t, ".$schema.".tag_building tb where tb.building_id=".$p_building_id." and tb.tag_id=t.id),',')) as tags FROM ".$schema.".building b WHERE b.id =".$p_building_id;
	#echo $sql;
	$recordSet = $pgconn->prepare($sql);
	$recordSet->execute();

	header("Content-Type: application/json");
	echo rs2json($recordSet,"rows");
}
catch (Exception $e) {
	trigger_error("Caught Exception: " . $e->getMessage(), E_USER_ERROR);
}

?>