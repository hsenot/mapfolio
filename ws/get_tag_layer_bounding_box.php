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

function my_pg_escape_string($t) {
    return $t;
}

# Connect to PostgreSQL database
$conn = pgConnection();
if (!$conn) {
    echo "Not connected : " . pg_error();
    exit;
}

# Existence of tag means that we search by tag
if (isset($_GET['tag']) and !empty($_GET['tag']))
{
    $tag_label = $_GET['tag'];
    $sql_tag_part = " ,community.tag_building tb, community.tag t WHERE b.id=tb.building_id AND tb.tag_id=t.id AND t.label='".$tag_label."' AND";
}
else
{
    $sql_tag_part = " WHERE";
}

# Build SQL SELECT statement and return the geometry as a GeoJSON element in EPSG: 4326
$sql = "select st_xmin(b) as xmin,st_ymin(b) as ymin,st_xmax(b) as xmax,st_ymax(b) as ymax from (SELECT ST_Extent(b.the_geom) as b FROM community.building b,community.tag_building tb, community.tag t WHERE b.id=tb.building_id AND tb.tag_id=t.id AND t.label='".$tag_label."') t";

if (isset($_REQUEST['debug']))
{
    echo $sql."\n";
}

# Try query or error
$recordSet = $conn->prepare($sql);
$recordSet->execute();
if (!$recordSet) {
    echo $sql;
    echo "An SQL error occured.\n";
    exit;
}

# Build GeoJSON
echo rs2json($recordSet,"rows");

?>