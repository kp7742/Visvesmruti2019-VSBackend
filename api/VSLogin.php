<?php
	require_once("dbInfo.php");

	function addLogin($aID, $apiToken) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Insert query.
		$sql = "INSERT INTO `vs_login`
				(
					`AID`,
					`ApiToken`
				)
				VALUES
				(
					?,
					?
				);";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'is', $aID, $apiToken);

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

	function getAllLogin() {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		$sql = "SELECT	`LID`,
						`AID`,
						`ApiToken`,
						DATE_FORMAT(`LastLogin`, '%m/%d/%Y %h:%i %p') AS LastLogin
				FROM	`vs_login`;";

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
		mysqli_stmt_bind_result($stmt, $lID, $aID, $apiToken, $lastLogin);
		$list = Array();
		while(mysqli_stmt_fetch($stmt)) {
			$record = Array(
				"LID" => $lID,
				"AID" => $aID,
				"ApiToken" => $apiToken,
				"LastLogin" => $lastLogin);

			array_push($list, $record);
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);

		return $list;
	}

	function updateLogin($apiToken, $lastLogin, $aID) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Update query.
		$sql = "UPDATE	`vs_login`
				SET		`ApiToken` = ?,
						`LastLogin` = ?
				WHERE	`AID` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'ssi', $apiToken, $lastLogin, $aID);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	}

	function getLogin($aID) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Select query.
		$sql = "SELECT	`LID`,
						`AID`,
						`ApiToken`,
						DATE_FORMAT(`LastLogin`, '%m/%d/%Y %h:%i %p') AS LastLogin
				FROM	`vs_login`
				WHERE	`AID` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'i', $aID);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Bind result and fetch records.
		mysqli_stmt_bind_result($stmt, $lID, $aID, $apiToken, $lastLogin);
		$list = Array();
		while(mysqli_stmt_fetch($stmt)) {
			$record = Array(
				"LID" => $lID,
				"AID" => $aID,
				"ApiToken" => $apiToken,
				"LastLogin" => $lastLogin);

			array_push($list, $record);
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);

		return $list;
	}

	function getLoginByToken($apiToken) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Select query.
		$sql = "SELECT	`LID`,
						`AID`,
						`ApiToken`,
						DATE_FORMAT(`LastLogin`, '%m/%d/%Y %h:%i %p') AS LastLogin
				FROM	`vs_login`
				WHERE	`ApiToken` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 's', $apiToken);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Bind result and fetch records.
		mysqli_stmt_bind_result($stmt, $lID, $aID, $apiToken, $lastLogin);
		$list = Array();
		while(mysqli_stmt_fetch($stmt)) {
			$record = Array(
				"LID" => $lID,
				"AID" => $aID,
				"ApiToken" => $apiToken,
				"LastLogin" => $lastLogin);

			array_push($list, $record);
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);

		return $list;
	}
?>