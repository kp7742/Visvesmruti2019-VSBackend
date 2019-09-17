<?php
	require_once("dbInfo.php");

	function addAdmin($eMail, $password, $name, $department, $mobile, $isFaculty = false, $isCoordinator = false,
                      $isCampaigner = false, $eventID = null, $totalFeeCollected = 0) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Insert query.
		$sql = "INSERT INTO `vs_admins`
				(
					`EMail`,
					`Password`,
					`Name`,
					`Department`,
					`Mobile`,
					`isFaculty`,
					`isCoordinator`,
					`isCampaigner`,
					`EventID`,
					`TotalFeeCollected`
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
		mysqli_stmt_bind_param($stmt, 'sssssiiiii', $eMail, $password, $name, $department, $mobile, $isFaculty, $isCoordinator, $isCampaigner, $eventID, $totalFeeCollected);

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

	function getAllAdmins() {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		$sql = "SELECT	`AID`,
						`EMail`,
						`Password`,
						`Name`,
						`Department`,
						`Mobile`,
						`isFaculty`,
						`isCoordinator`,
						`isCampaigner`,
						`EventID`,
						`TotalFeeCollected`,
						DATE_FORMAT(`RegisterTime`, '%m/%d/%Y %h:%i %p') AS RegisterTime
				FROM	`vs_admins`;";

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
		mysqli_stmt_bind_result($stmt, $aID, $eMail, $password, $name, $department, $mobile, $isFaculty, $isCoordinator, $isCampaigner, $eventID, $totalFeeCollected, $registerTime);
		$list = Array();
		while(mysqli_stmt_fetch($stmt)) {
			$record = Array(
				"AID" => $aID,
				"EMail" => $eMail,
				"Password" => $password,
				"Name" => $name,
				"Department" => $department,
				"Mobile" => $mobile,
				"isFaculty" => $isFaculty,
				"isCoordinator" => $isCoordinator,
				"isCampaigner" => $isCampaigner,
				"EventID" => $eventID,
				"TotalFeeCollected" => $totalFeeCollected,
				"RegisterTime" => $registerTime);

			array_push($list, $record);
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);

		return $list;
	}

    function updateAdmin($isFaculty, $isCoordinator, $isCampaigner, $eventID, $eMail) {
        // Connect to database.
        $conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
        if(mysqli_connect_error()) {
            die("Could not connect to database. " . mysqli_connect_error());
        }

        // Update query.
        $sql = "UPDATE	`vs_admins`
                    SET		`isFaculty` = ?,
                            `isCoordinator` = ?,
                            `isCampaigner` = ?,
                            `EventID` = ?
                    WHERE	`EMail` = ?;";

        // Prepare statement.
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt === false) {
            die("Invalid SQL specified. " . mysqli_error($conn));
        }

        // Bind parameters.
        mysqli_stmt_bind_param($stmt, 'iiiis', $isFaculty, $isCoordinator, $isCampaigner, $eventID, $eMail);

        // Execute the statement.
        if(!mysqli_stmt_execute($stmt)) {
            die("Could not execute the statement. " . mysqli_stmt_error($stmt));
        }

        // Close statement and connection.
        mysqli_stmt_close($stmt);
        mysqli_close($conn);
    }

	function getAdmin($eMail) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Select query.
		$sql = "SELECT	`AID`,
						`EMail`,
						`Password`,
						`Name`,
						`Department`,
						`Mobile`,
						`isFaculty`,
						`isCoordinator`,
						`isCampaigner`,
						`EventID`,
						`TotalFeeCollected`,
						DATE_FORMAT(`RegisterTime`, '%m/%d/%Y %h:%i %p') AS RegisterTime
				FROM	`vs_admins`
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
		mysqli_stmt_bind_result($stmt, $aID, $eMail, $password, $name, $department, $mobile, $isFaculty, $isCoordinator, $isCampaigner, $eventID, $totalFeeCollected, $registerTime);
		$list = Array();
		while(mysqli_stmt_fetch($stmt)) {
			$record = Array(
				"AID" => $aID,
				"EMail" => $eMail,
				"Password" => $password,
				"Name" => $name,
				"Department" => $department,
				"Mobile" => $mobile,
				"isFaculty" => $isFaculty,
				"isCoordinator" => $isCoordinator,
				"isCampaigner" => $isCampaigner,
				"EventID" => $eventID,
				"TotalFeeCollected" => $totalFeeCollected,
				"RegisterTime" => $registerTime);

			array_push($list, $record);
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);

		return $list;
	}

	function getAdminByID($aID) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Select query.
		$sql = "SELECT	`AID`,
						`EMail`,
						`Password`,
						`Name`,
						`Department`,
						`Mobile`,
						`isFaculty`,
						`isCoordinator`,
						`isCampaigner`,
						`EventID`,
						`TotalFeeCollected`,
						DATE_FORMAT(`RegisterTime`, '%m/%d/%Y %h:%i %p') AS RegisterTime
				FROM	`vs_admins`
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
		mysqli_stmt_bind_result($stmt, $aID, $eMail, $password, $name, $department, $mobile, $isFaculty, $isCoordinator, $isCampaigner, $eventID, $totalFeeCollected, $registerTime);
		$list = Array();
		while(mysqli_stmt_fetch($stmt)) {
			$record = Array(
				"AID" => $aID,
				"EMail" => $eMail,
				"Password" => $password,
				"Name" => $name,
				"Department" => $department,
				"Mobile" => $mobile,
				"isFaculty" => $isFaculty,
				"isCoordinator" => $isCoordinator,
				"isCampaigner" => $isCampaigner,
				"EventID" => $eventID,
				"TotalFeeCollected" => $totalFeeCollected,
				"RegisterTime" => $registerTime);

			array_push($list, $record);
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);

		return $list;
	}

	function IncAdminPaid($totalFeeCollected, $eMail) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Update query.
		$sql = "UPDATE	`vs_admins`
				SET		`TotalFeeCollected` = ?
				WHERE	`EMail` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'is', $totalFeeCollected, $eMail);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	}

	function IncAdminPaidByID($totalFeeCollected, $aID) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Update query.
		$sql = "UPDATE	`vs_admins`
				SET		`TotalFeeCollected` = ?
				WHERE	`AID` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'ii', $totalFeeCollected, $aID);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	}
?>