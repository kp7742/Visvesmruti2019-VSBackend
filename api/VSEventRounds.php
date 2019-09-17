<?php
	require_once("dbInfo.php");

	function addRoundsDetails($eRID, $wonRounds = 0, $isWinRound1 = false, $isLockedRound1 = false, $isWinRound2 = false,
                              $isLockedRound2 = false, $isWinRound3 = false, $isLockedRound3 = false,
                              $isWinRound4 = false, $isLockedRound4 = false) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Insert query.
		$sql = "INSERT INTO `vs_event_rounds`
				(
					`ERID`,
					`WonRounds`,
					`isWinRound1`,
					`isLockedRound1`,
					`isWinRound2`,
					`isLockedRound2`,
					`isWinRound3`,
					`isLockedRound3`,
					`isWinRound4`,
					`isLockedRound4`
				)
				VALUES
				(
					?,
					?,
					?,
					?,
					?,
					?,
					?,
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
		mysqli_stmt_bind_param($stmt, 'iiiiiiiiii', $eRID, $wonRounds, $isWinRound1, $isLockedRound1, $isWinRound2, $isLockedRound2, $isWinRound3, $isLockedRound3, $isWinRound4, $isLockedRound4);

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

	function getAllRounds() {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		$sql = "SELECT	`RID`,
						`ERID`,
						`WonRounds`,
						`isWinRound1`,
						`isLockedRound1`,
						`isWinRound2`,
						`isLockedRound2`,
						`isWinRound3`,
						`isLockedRound3`,
						`isWinRound4`,
						`isLockedRound4`
				FROM	`vs_event_rounds`;";

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
		mysqli_stmt_bind_result($stmt, $rID, $eRID, $wonRounds, $isWinRound1, $isLockedRound1, $isWinRound2, $isLockedRound2, $isWinRound3, $isLockedRound3, $isWinRound4, $isLockedRound4);
		$list = Array();
		while(mysqli_stmt_fetch($stmt)) {
			$record = Array(
				"RID" => $rID,
				"ERID" => $eRID,
				"WonRounds" => $wonRounds,
				"isWinRound1" => $isWinRound1,
				"isLockedRound1" => $isLockedRound1,
				"isWinRound2" => $isWinRound2,
				"isLockedRound2" => $isLockedRound2,
				"isWinRound3" => $isWinRound3,
				"isLockedRound3" => $isLockedRound3,
				"isWinRound4" => $isWinRound4,
				"isLockedRound4" => $isLockedRound4);

			array_push($list, $record);
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);

		return $list;
	}

	function getRoundDetails($eRID) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Select query.
		$sql = "SELECT	`RID`,
						`ERID`,
						`WonRounds`,
						`isWinRound1`,
						`isLockedRound1`,
						`isWinRound2`,
						`isLockedRound2`,
						`isWinRound3`,
						`isLockedRound3`,
						`isWinRound4`,
						`isLockedRound4`
				FROM	`vs_event_rounds`
				WHERE	`ERID` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'i', $eRID);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Bind result and fetch records.
		mysqli_stmt_bind_result($stmt, $rID, $eRID, $wonRounds, $isWinRound1, $isLockedRound1, $isWinRound2, $isLockedRound2, $isWinRound3, $isLockedRound3, $isWinRound4, $isLockedRound4);
		$list = Array();
		while(mysqli_stmt_fetch($stmt)) {
			$record = Array(
				"RID" => $rID,
				"ERID" => $eRID,
				"WonRounds" => $wonRounds,
				"isWinRound1" => $isWinRound1,
				"isLockedRound1" => $isLockedRound1,
				"isWinRound2" => $isWinRound2,
				"isLockedRound2" => $isLockedRound2,
				"isWinRound3" => $isWinRound3,
				"isLockedRound3" => $isLockedRound3,
				"isWinRound4" => $isWinRound4,
				"isLockedRound4" => $isLockedRound4);

			array_push($list, $record);
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);

		return $list;
	}

	function updateRound($wonRounds, $isWinRound1, $isLockedRound1, $isWinRound2, $isLockedRound2, $isWinRound3, $isLockedRound3, $isWinRound4, $isLockedRound4, $eRID) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Update query.
		$sql = "UPDATE	`vs_event_rounds`
				SET		`WonRounds` = ?,
						`isWinRound1` = ?,
						`isLockedRound1` = ?,
						`isWinRound2` = ?,
						`isLockedRound2` = ?,
						`isWinRound3` = ?,
						`isLockedRound3` = ?,
						`isWinRound4` = ?,
						`isLockedRound4` = ?
				WHERE	`ERID` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'iiiiiiiiii', $wonRounds, $isWinRound1, $isLockedRound1, $isWinRound2, $isLockedRound2, $isWinRound3, $isLockedRound3, $isWinRound4, $isLockedRound4, $eRID);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	}

	function setWonRounds($wonRounds, $eRID) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Update query.
		$sql = "UPDATE	`vs_event_rounds`
				SET		`WonRounds` = ?
				WHERE	`ERID` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'ii', $wonRounds, $eRID);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	}

	function setRound1Winner($isWinRound1, $eRID) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Update query.
		$sql = "UPDATE	`vs_event_rounds`
				SET		`isWinRound1` = ?
				WHERE	`ERID` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'ii', $isWinRound1, $eRID);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	}

	function setRound2Winner($isWinRound2, $eRID) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Update query.
		$sql = "UPDATE	`vs_event_rounds`
				SET		`isWinRound2` = ?
				WHERE	`ERID` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'ii', $isWinRound2, $eRID);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	}

	function setRound3Winner($isWinRound3, $eRID) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Update query.
		$sql = "UPDATE	`vs_event_rounds`
				SET		`isWinRound3` = ?
				WHERE	`ERID` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'ii', $isWinRound3, $eRID);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	}

	function setRound4Winner($isWinRound4, $eRID) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Update query.
		$sql = "UPDATE	`vs_event_rounds`
				SET		`isWinRound4` = ?
				WHERE	`ERID` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'ii', $isWinRound4, $eRID);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	}

	function setRound1Lock($isLockedRound1, $eRID) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Update query.
		$sql = "UPDATE	`vs_event_rounds`
				SET		`isLockedRound1` = ?
				WHERE	`ERID` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'ii', $isLockedRound1, $eRID);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	}

	function setRound2Lock($isLockedRound2, $eRID) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Update query.
		$sql = "UPDATE	`vs_event_rounds`
				SET		`isLockedRound2` = ?
				WHERE	`ERID` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'ii', $isLockedRound2, $eRID);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	}

	function setRound3Lock($isLockedRound3, $eRID) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Update query.
		$sql = "UPDATE	`vs_event_rounds`
				SET		`isLockedRound3` = ?
				WHERE	`ERID` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'ii', $isLockedRound3, $eRID);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	}

	function setRound4Lock($isLockedRound4, $eRID) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Update query.
		$sql = "UPDATE	`vs_event_rounds`
				SET		`isLockedRound4` = ?
				WHERE	`ERID` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'ii', $isLockedRound4, $eRID);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	}
?>