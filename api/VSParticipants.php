<?php
	require_once("dbInfo.php");

	function addParticipant($eMail, $firstName, $lastName, $college, $department, $semester, $mobile, $gender) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Insert query.
		$sql = "INSERT INTO `vs_participants`
				(
					`EMail`,
					`FirstName`,
					`LastName`,
					`College`,
					`Department`,
					`Semester`,
					`Mobile`,
					`Gender`
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
					?
				);";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'sssssiss', $eMail, $firstName, $lastName, $college, $department, $semester, $mobile, $gender);

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

	function getAllParticipants() {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		$sql = "SELECT	`PID`,
						`EMail`,
						`FirstName`,
						`LastName`,
						`College`,
						`Department`,
						`Semester`,
						`Mobile`,
						`Gender`,
						DATE_FORMAT(`RegisterTime`, '%m/%d/%Y %h:%i %p') AS RegisterTime
				FROM	`vs_participants`;";

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
		mysqli_stmt_bind_result($stmt, $pID, $eMail, $firstName, $lastName, $college, $department, $semester, $mobile, $gender, $registerTime);
		$list = Array();
		while(mysqli_stmt_fetch($stmt)) {
			$record = Array(
				"PID" => $pID,
				"EMail" => $eMail,
				"FirstName" => $firstName,
				"LastName" => $lastName,
				"College" => $college,
				"Department" => $department,
				"Semester" => $semester,
				"Mobile" => $mobile,
				"Gender" => $gender,
				"RegisterTime" => $registerTime);

			array_push($list, $record);
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);

		return $list;
	}

	function getParticipant($eMail) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Select query.
		$sql = "SELECT	`PID`,
						`EMail`,
						`FirstName`,
						`LastName`,
						`College`,
						`Department`,
						`Semester`,
						`Mobile`,
						`Gender`,
						DATE_FORMAT(`RegisterTime`, '%m/%d/%Y %h:%i %p') AS RegisterTime
				FROM	`vs_participants`
				WHERE	`EMail` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 's', $eMail);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Bind result and fetch records.
		mysqli_stmt_bind_result($stmt, $pID, $eMail, $firstName, $lastName, $college, $department, $semester, $mobile, $gender, $registerTime);
		$list = Array();
		while(mysqli_stmt_fetch($stmt)) {
			$record = Array(
				"PID" => $pID,
				"EMail" => $eMail,
				"FirstName" => $firstName,
				"LastName" => $lastName,
				"College" => $college,
				"Department" => $department,
				"Semester" => $semester,
				"Mobile" => $mobile,
				"Gender" => $gender,
				"RegisterTime" => $registerTime);

			array_push($list, $record);
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);

		return $list;
	}

	function getParticipantByID($pID) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Select query.
		$sql = "SELECT	`PID`,
						`EMail`,
						`FirstName`,
						`LastName`,
						`College`,
						`Department`,
						`Semester`,
						`Mobile`,
						`Gender`,
						DATE_FORMAT(`RegisterTime`, '%m/%d/%Y %h:%i %p') AS RegisterTime
				FROM	`vs_participants`
				WHERE	`PID` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'i', $pID);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Bind result and fetch records.
		mysqli_stmt_bind_result($stmt, $pID, $eMail, $firstName, $lastName, $college, $department, $semester, $mobile, $gender, $registerTime);
		$list = Array();
		while(mysqli_stmt_fetch($stmt)) {
			$record = Array(
				"PID" => $pID,
				"EMail" => $eMail,
				"FirstName" => $firstName,
				"LastName" => $lastName,
				"College" => $college,
				"Department" => $department,
				"Semester" => $semester,
				"Mobile" => $mobile,
				"Gender" => $gender,
				"RegisterTime" => $registerTime);

			array_push($list, $record);
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);

		return $list;
	}
?>