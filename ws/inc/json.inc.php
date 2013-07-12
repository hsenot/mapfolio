<?php
/**
 * Creates JSON ( http://www.json.org/ ) from an ADODB record set
 *
 * @param 		object 		$rs 		- record set object
 * @return 		string		- resulting json string
*/

function rs2geojson($rs)
{
	if (!$rs) {
		trigger_error("Caught Exception: bad recordset passed to rs2geojson function.", E_USER_ERROR);
		return false;
	}

	$output    = '';
	$rowOutput = '';

	while ($row = pg_fetch_assoc($rs)) {
	    $rowOutput = (strlen($rowOutput) > 0 ? ',' : '') . '{"type": "Feature", "geometry": ' . $row['geojson'] . ', "properties": {';
	    $props = '';
	    $id    = '';
	    foreach ($row as $key => $val) {
	        if ($key != "geojson") {
	            $props .= (strlen($props) > 0 ? ',' : '') . '"' . $key . '":"' . escapeJsonString($val) . '"';
	        }
	        if ($key == "id") {
	            $id .= ',"id":"' . escapeJsonString($val) . '"';
	        }
	    }
	    
	    $rowOutput .= $props . '}';
	    $rowOutput .= $id;
	    $rowOutput .= '}';
	    $output .= $rowOutput;
	}

	$output = '{ "type": "FeatureCollection", "features": [ ' . $output . ' ]}';
	return $output;
}


function rs2json($rs)
{
	if (!$rs) {
		trigger_error("Caught Exception: bad recordset passed to rs2json function.", E_USER_ERROR);
		return false;
	}

	$output = '';
	$rowOutput = '';
    $rowCounter = 0;

        while ($row  = $rs->fetch(PDO::FETCH_ASSOC))
		{
			if (strlen($rowOutput) > 0) $rowOutput .= ',';
            $rowOutput .= '{';

			$cols = count($row);
			$colCounter = 1;
			foreach ($row as $key => $val)
			{
				$rowOutput .= '"' . $key . '":';
				if ($key == "json" || $key == "geojson"){
					$rowOutput .= trim($val);
				}else{
					$rowOutput .= '"' . trim($val) . '"';
				}

				if ($colCounter != $cols)
				{
					$rowOutput .= ',';
				}
				$colCounter++;
			}
			$rowOutput .= '}';
			$rowCounter++;
		}

        if ($rowCounter == 0) $output = '[]';
        else $output = '[' . $rowOutput . ']';


	$output .= '}';

    //Total rows
    $output = '{"total_rows":"' . $rowCounter . '","rows":'.$output;

	//For jsonp
	if (isset($_REQUEST['callback'])) {
		$output = $_REQUEST['callback'] . '(' . $output . ');';
	}

	return $output;
}

?>