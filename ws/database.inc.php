<?php

	function pgConnection() {
		try {
			# Connect to PostgreSQL database
			$conn = pg_connect("dbname='solar' user='opengeo' password='opengeo' host='localhost' port='54321'");
			return $conn;
		}
		catch (Exception $e) {
			trigger_error("Caught Exception: " . $e->getMessage(), E_USER_ERROR);
		}
	}

?>