<?php
/**
 * PostGIS to GeoJSON
 * Query a PostGIS table or view and return the results in GeoJSON format, suitable for use in OpenLayers, Leaflet, etc.
 * 
 * @param 		string		$geotable		The PostGIS layer name *REQUIRED*
 * @param 		string		$geomfield		The PostGIS geometry field *REQUIRED*
 * @param 		string		$srid			The SRID of the returned GeoJSON *OPTIONAL (If omitted, EPSG: 4326 will be used)*
 * @param 		string 		$fields 		Fields to be returned *OPTIONAL (If omitted, all fields will be returned)* NOTE- Uppercase field names should be wrapped in double quotes
 * @param 		string		$parameters		SQL WHERE clause parameters *OPTIONAL*
 * @param 		string		$orderby		SQL ORDER BY constraint *OPTIONAL*
 * @param 		string		$sort			SQL ORDER BY sort order (ASC or DESC) *OPTIONAL*
 * @param 		string		$limit			Limit number of results returned *OPTIONAL*
 * @param 		string		$offset			Offset used in conjunction with limit *OPTIONAL*
 * @return 		string					resulting geojson string
 */

require_once("inc/database.inc.php");
require_once("inc/json.inc.php");

function escapeJsonString($value) { # list from www.json.org: (\b backspace, \f formfeed)
  $escapers = array("\\", "/", "\"", "\n", "\r", "\t", "\x08", "\x0c");
  $replacements = array("\\\\", "\\/", "\\\"", "\\n", "\\r", "\\t", "\\f", "\\b");
  $result = str_replace($escapers, $replacements, $value);
  return $result;
}
 
# Retrive URL variables
if (empty($_GET['geotable'])) {
    echo "missing required parameter: <i>geotable</i>";
    exit;
} else
    $geotable = $_GET['geotable'];

if (empty($_GET['geomfield'])) {
    echo "missing required parameter: <i>geomfield</i>";
    exit;
} else
    $geomfield = $_GET['geomfield'];

if (empty($_GET['srid'])) {
    $srid = '4326';
} else
    $srid = $_GET['srid'];

if (empty($_GET['fields'])) {
    $fields = '*';
} else
    $fields = $_GET['fields'];

$parameters = $_GET['parameters'];

$bbox = $_GET['bbox'];

$orderby    = $_GET['orderby'];

if (empty($_GET['sort'])) {
    $sort = 'ASC';
} else
    $sort = $_GET['sort'];
	
$limit      = $_GET['limit'];

$offset     = $_GET['offset'];

# Connect to PostgreSQL database
$conn = pgConnection();
if (!$conn) {
    echo "Not connected : " . pg_error();
    exit;
}

# Build SQL SELECT statement and return the geometry as a GeoJSON element in EPSG: 4326
$sql = "SELECT " . pg_escape_string($fields) . ", st_asgeojson(st_transform(" . pg_escape_string($geomfield) . ",$srid),5) AS geojson FROM " . pg_escape_string($geotable);
if (strlen(trim($bbox)) > 0) {
    $bbox=trim($bbox);
    $ca = split(",",$bbox);
    $sql_bbox = "ST_Intersects(ST_Envelope(ST_Union(ST_SetSRID(ST_Point(".$ca[0].",".$ca[1]."),4326),ST_SetSRID(ST_Point(".$ca[2].",".$ca[3]."),4326)))," . pg_escape_string($geomfield) .")";
}
if (strlen(trim($parameters)) > 0) {
    if ($sql_bbox)
    $sql .= " WHERE " . pg_escape_string($parameters) ." AND ".$sql_bbox;
}
else
{
    $sql .= " WHERE ".$sql_bbox;
}
if (strlen(trim($orderby)) > 0) {
    $sql .= " ORDER BY " . pg_escape_string($orderby) . " " . $sort;
}
if (strlen(trim($limit)) > 0) {
    $sql .= " LIMIT " . pg_escape_string($limit);
}
if (strlen(trim($offset)) > 0) {
    $sql .= " OFFSET " . pg_escape_string($offset);
}

if (isset($_REQUEST['debug']))
{
    echo $sql."\n";
}

# Try query or error
$rs = pg_query($conn, $sql);
if (!$rs) {
    echo $sql;
    echo "An SQL error occured.\n";
    exit;
}

# Build GeoJSON
echo rs2geojson($rs);

?>