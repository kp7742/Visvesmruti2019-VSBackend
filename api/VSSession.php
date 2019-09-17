<?php
	require_once("dbInfo.php");

	function addSessionLog($aID, $apiToken, $logMessage) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Insert query.
		$sql = "INSERT INTO `vs_session_log`
				(
					`AID`,
					`ApiToken`,
					`LogMessage`
				)
				VALUES
				(
					?,
					?,
					?
				);";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'iss', $aID, $apiToken, $logMessage);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Get value of the auto increment column.
		$newId = mysqli_insert_id($conn);

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);

		// Return the id.
		return $newId;
	}

	function getAllSessionLog() {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		$sql = "SELECT	`SID`,
						`AID`,
						`ApiToken`,
						`LogMessage`,
						DATE_FORMAT(`LogTime`, '%m/%d/%Y %h:%i %p') AS LogTime
				FROM	`vs_session_log`;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Bind result and fetch records.
		mysqli_stmt_bind_result($stmt, $sID, $aID, $apiToken, $logMessage, $logTime);
		$list = Array();
		while(mysqli_stmt_fetch($stmt)) {
			$record = Array(
				"SID" => $sID,
				"AID" => $aID,
				"ApiToken" => $apiToken,
				"LogMessage" => $logMessage,
				"LogTime" => $logTime);

			array_push($list, $record);
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);

		return $list;
	}
?>