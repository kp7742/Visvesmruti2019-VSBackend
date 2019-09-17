<?php
	require_once("dbInfo.php");

	function addEvent($eVCode, $eVName, $eVDepartment, $eVRounds, $eVPrice, $isSinglePrice, $isTeamEvent, $minMembers, $maxMembers) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Insert query.
		$sql = "INSERT INTO `vs_events`
				(
					`EVCode`,
					`EVName`,
					`EVDepartment`,
					`EVRounds`,
					`EVPrice`,
					`isSinglePrice`,
					`isTeamEvent`,
					`MinMembers`,
					`MaxMembers`
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
					?
				);";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'sssiiiiii', $eVCode, $eVName, $eVDepartment, $eVRounds, $eVPrice, $isSinglePrice, $isTeamEvent, $minMembers, $maxMembers);

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

	function getAllEvents() {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		$sql = "SELECT	`EVID`,
						`EVCode`,
						`EVName`,
						`EVDepartment`,
						`EVRounds`,
						`EVPrice`,
						`isSinglePrice`,
						`isTeamEvent`,
						`MinMembers`,
						`MaxMembers`
				FROM	`vs_events`;";

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
		mysqli_stmt_bind_result($stmt, $eVID, $eVCode, $eVName, $eVDepartment, $eVRounds, $eVPrice, $isSinglePrice, $isTeamEvent, $minMembers, $maxMembers);
		$list = Array();
		while(mysqli_stmt_fetch($stmt)) {
			$record = Array(
				"EVID" => $eVID,
				"EVCode" => $eVCode,
				"EVName" => $eVName,
				"EVDepartment" => $eVDepartment,
				"EVRounds" => $eVRounds,
				"EVPrice" => $eVPrice,
				"isSinglePrice" => $isSinglePrice,
				"isTeamEvent" => $isTeamEvent,
				"MinMembers" => $minMembers,
				"MaxMembers" => $maxMembers);

			array_push($list, $record);
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);

		return $list;
	}

	function getEventByCode($eVCode) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Select query.
		$sql = "SELECT	`EVID`,
						`EVCode`,
						`EVName`,
						`EVDepartment`,
						`EVRounds`,
						`EVPrice`,
						`isSinglePrice`,
						`isTeamEvent`,
						`MinMembers`,
						`MaxMembers`
				FROM	`vs_events`
				WHERE	`EVCode` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 's', $eVCode);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Bind result and fetch records.
		mysqli_stmt_bind_result($stmt, $eVID, $eVCode, $eVName, $eVDepartment, $eVRounds, $eVPrice, $isSinglePrice, $isTeamEvent, $minMembers, $maxMembers);
		$list = Array();
		while(mysqli_stmt_fetch($stmt)) {
			$record = Array(
				"EVID" => $eVID,
				"EVCode" => $eVCode,
				"EVName" => $eVName,
				"EVDepartment" => $eVDepartment,
				"EVRounds" => $eVRounds,
				"EVPrice" => $eVPrice,
				"isSinglePrice" => $isSinglePrice,
				"isTeamEvent" => $isTeamEvent,
				"MinMembers" => $minMembers,
				"MaxMembers" => $maxMembers);

			array_push($list, $record);
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);

		return $list;
	}

	function getEventByID($eVID) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Select query.
		$sql = "SELECT	`EVID`,
						`EVCode`,
						`EVName`,
						`EVDepartment`,
						`EVRounds`,
						`EVPrice`,
						`isSinglePrice`,
						`isTeamEvent`,
						`MinMembers`,
						`MaxMembers`
				FROM	`vs_events`
				WHERE	`EVID` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'i', $eVID);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Bind result and fetch records.
		mysqli_stmt_bind_result($stmt, $eVID, $eVCode, $eVName, $eVDepartment, $eVRounds, $eVPrice, $isSinglePrice, $isTeamEvent, $minMembers, $maxMembers);
		$list = Array();
		while(mysqli_stmt_fetch($stmt)) {
			$record = Array(
				"EVID" => $eVID,
				"EVCode" => $eVCode,
				"EVName" => $eVName,
				"EVDepartment" => $eVDepartment,
				"EVRounds" => $eVRounds,
				"EVPrice" => $eVPrice,
				"isSinglePrice" => $isSinglePrice,
				"isTeamEvent" => $isTeamEvent,
				"MinMembers" => $minMembers,
				"MaxMembers" => $maxMembers);

			array_push($list, $record);
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);

		return $list;
	}
?>