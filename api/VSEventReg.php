<?php
	require_once("dbInfo.php");

	function addRegistration($pID, $eVID, $eRCode, $fCode, $regAdmin = null,
                             $isTeam = false, $isTeamLeader = false, $isPaid = false, $payAdmin = null,
                             $payType = null, $isAttended = false, $attendAdmin = null) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Insert query.
		$sql = "INSERT INTO `vs_event_reg`
				(
					`PID`,
					`EVID`,
					`ERCode`,
					`FCode`,
					`RegAdmin`,
					`isTeam`,
					`isTeamLeader`,
					`isPaid`,
					`PayAdmin`,
					`PayType`,
					`isAttended`,
					`AttendAdmin`
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
		mysqli_stmt_bind_param($stmt, 'iissiiiiisii', $pID, $eVID, $eRCode, $fCode, $regAdmin, $isTeam, $isTeamLeader, $isPaid, $payAdmin, $payType, $isAttended, $attendAdmin);

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

	function getAllRegistrations() {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		$sql = "SELECT	`ERID`,
						`PID`,
						`EVID`,
						`ERCode`,
						`FCode`,
						`RegAdmin`,
						`isTeam`,
						`isTeamLeader`,
						`isPaid`,
						`PayAdmin`,
						`PayType`,
						DATE_FORMAT(`PayTime`, '%m/%d/%Y %h:%i %p') AS PayTime,
						`isAttended`,
						`AttendAdmin`,
						DATE_FORMAT(`AttendTime`, '%m/%d/%Y %h:%i %p') AS AttendTime,
						DATE_FORMAT(`EventRegTime`, '%m/%d/%Y %h:%i %p') AS EventRegTime
				FROM	`vs_event_reg`;";

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
		mysqli_stmt_bind_result($stmt, $eRID, $pID, $eVID, $eRCode, $fCode, $regAdmin, $isTeam, $isTeamLeader, $isPaid, $payAdmin, $payType, $payTime, $isAttended, $attendAdmin, $attendTime, $eventRegTime);
		$list = Array();
		while(mysqli_stmt_fetch($stmt)) {
			$record = Array(
				"ERID" => $eRID,
				"PID" => $pID,
				"EVID" => $eVID,
				"ERCode" => $eRCode,
				"FCode" => $fCode,
				"RegAdmin" => $regAdmin,
				"isTeam" => $isTeam,
				"isTeamLeader" => $isTeamLeader,
				"isPaid" => $isPaid,
				"PayAdmin" => $payAdmin,
				"PayType" => $payType,
				"PayTime" => $payTime,
				"isAttended" => $isAttended,
				"AttendAdmin" => $attendAdmin,
				"AttendTime" => $attendTime,
				"EventRegTime" => $eventRegTime);

			array_push($list, $record);
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);

		return $list;
	}

	function getEventEntryByP($pID, $eVID) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Select query.
		$sql = "SELECT	`ERID`,
						`PID`,
						`EVID`,
						`ERCode`,
						`FCode`,
						`RegAdmin`,
						`isTeam`,
						`isTeamLeader`,
						`isPaid`,
						`PayAdmin`,
						`PayType`,
						DATE_FORMAT(`PayTime`, '%m/%d/%Y %h:%i %p') AS PayTime,
						`isAttended`,
						`AttendAdmin`,
						DATE_FORMAT(`AttendTime`, '%m/%d/%Y %h:%i %p') AS AttendTime,
						DATE_FORMAT(`EventRegTime`, '%m/%d/%Y %h:%i %p') AS EventRegTime
				FROM	`vs_event_reg`
				WHERE	`PID` = ?
				AND		`EVID` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'ii', $pID, $eVID);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Bind result and fetch records.
		mysqli_stmt_bind_result($stmt, $eRID, $pID, $eVID, $eRCode, $fCode, $regAdmin, $isTeam, $isTeamLeader, $isPaid, $payAdmin, $payType, $payTime, $isAttended, $attendAdmin, $attendTime, $eventRegTime);
		$list = Array();
		while(mysqli_stmt_fetch($stmt)) {
			$record = Array(
				"ERID" => $eRID,
				"PID" => $pID,
				"EVID" => $eVID,
				"ERCode" => $eRCode,
				"FCode" => $fCode,
				"RegAdmin" => $regAdmin,
				"isTeam" => $isTeam,
				"isTeamLeader" => $isTeamLeader,
				"isPaid" => $isPaid,
				"PayAdmin" => $payAdmin,
				"PayType" => $payType,
				"PayTime" => $payTime,
				"isAttended" => $isAttended,
				"AttendAdmin" => $attendAdmin,
				"AttendTime" => $attendTime,
				"EventRegTime" => $eventRegTime);

			array_push($list, $record);
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);

		return $list;
	}
	
	function getEventEntryByPID($pID) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Select query.
		$sql = "SELECT	`ERID`,
						`PID`,
						`EVID`,
						`ERCode`,
						`FCode`,
						`RegAdmin`,
						`isTeam`,
						`isTeamLeader`,
						`isPaid`,
						`PayAdmin`,
						`PayType`,
						DATE_FORMAT(`PayTime`, '%m/%d/%Y %h:%i %p') AS PayTime,
						`isAttended`,
						`AttendAdmin`,
						DATE_FORMAT(`AttendTime`, '%m/%d/%Y %h:%i %p') AS AttendTime,
						DATE_FORMAT(`EventRegTime`, '%m/%d/%Y %h:%i %p') AS EventRegTime
				FROM	`vs_event_reg`
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
		mysqli_stmt_bind_result($stmt, $eRID, $pID, $eVID, $eRCode, $fCode, $regAdmin, $isTeam, $isTeamLeader, $isPaid, $payAdmin, $payType, $payTime, $isAttended, $attendAdmin, $attendTime, $eventRegTime);
		$list = Array();
		while(mysqli_stmt_fetch($stmt)) {
			$record = Array(
				"ERID" => $eRID,
				"PID" => $pID,
				"EVID" => $eVID,
				"ERCode" => $eRCode,
				"FCode" => $fCode,
				"RegAdmin" => $regAdmin,
				"isTeam" => $isTeam,
				"isTeamLeader" => $isTeamLeader,
				"isPaid" => $isPaid,
				"PayAdmin" => $payAdmin,
				"PayType" => $payType,
				"PayTime" => $payTime,
				"isAttended" => $isAttended,
				"AttendAdmin" => $attendAdmin,
				"AttendTime" => $attendTime,
				"EventRegTime" => $eventRegTime);

			array_push($list, $record);
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);

		return $list;
	}

    function getEventEntryByE($eVID) {
        // Connect to database.
        $conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
        if(mysqli_connect_error()) {
            die("Could not connect to database. " . mysqli_connect_error());
        }

        // Select query.
        $sql = "SELECT	`ERID`,
                            `PID`,
                            `EVID`,
                            `ERCode`,
                            `FCode`,
                            `RegAdmin`,
                            `isTeam`,
                            `isTeamLeader`,
                            `isPaid`,
                            `PayAdmin`,
                            `PayType`,
                            DATE_FORMAT(`PayTime`, '%m/%d/%Y %h:%i %p') AS PayTime,
                            `isAttended`,
                            `AttendAdmin`,
                            DATE_FORMAT(`AttendTime`, '%m/%d/%Y %h:%i %p') AS AttendTime,
                            DATE_FORMAT(`EventRegTime`, '%m/%d/%Y %h:%i %p') AS EventRegTime
                    FROM	`vs_event_reg`
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
        mysqli_stmt_bind_result($stmt, $eRID, $pID, $eVID, $eRCode, $fCode, $regAdmin, $isTeam, $isTeamLeader, $isPaid, $payAdmin, $payType, $payTime, $isAttended, $attendAdmin, $attendTime, $eventRegTime);
        $list = Array();
        while(mysqli_stmt_fetch($stmt)) {
            $record = Array(
                "ERID" => $eRID,
                "PID" => $pID,
                "EVID" => $eVID,
                "ERCode" => $eRCode,
                "FCode" => $fCode,
                "RegAdmin" => $regAdmin,
                "isTeam" => $isTeam,
                "isTeamLeader" => $isTeamLeader,
                "isPaid" => $isPaid,
                "PayAdmin" => $payAdmin,
                "PayType" => $payType,
                "PayTime" => $payTime,
                "isAttended" => $isAttended,
                "AttendAdmin" => $attendAdmin,
                "AttendTime" => $attendTime,
                "EventRegTime" => $eventRegTime);

            array_push($list, $record);
        }

        // Close statement and connection.
        mysqli_stmt_close($stmt);
        mysqli_close($conn);

        return $list;
    }

	function getEventEntryByCode($eRCode) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Select query.
		$sql = "SELECT	`ERID`,
						`PID`,
						`EVID`,
						`ERCode`,
						`FCode`,
						`RegAdmin`,
						`isTeam`,
						`isTeamLeader`,
						`isPaid`,
						`PayAdmin`,
						`PayType`,
						DATE_FORMAT(`PayTime`, '%m/%d/%Y %h:%i %p') AS PayTime,
						`isAttended`,
						`AttendAdmin`,
						DATE_FORMAT(`AttendTime`, '%m/%d/%Y %h:%i %p') AS AttendTime,
						DATE_FORMAT(`EventRegTime`, '%m/%d/%Y %h:%i %p') AS EventRegTime
				FROM	`vs_event_reg`
				WHERE	`ERCode` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 's', $eRCode);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Bind result and fetch records.
		mysqli_stmt_bind_result($stmt, $eRID, $pID, $eVID, $eRCode, $fCode, $regAdmin, $isTeam, $isTeamLeader, $isPaid, $payAdmin, $payType, $payTime, $isAttended, $attendAdmin, $attendTime, $eventRegTime);
		$list = Array();
		while(mysqli_stmt_fetch($stmt)) {
			$record = Array(
				"ERID" => $eRID,
				"PID" => $pID,
				"EVID" => $eVID,
				"ERCode" => $eRCode,
				"FCode" => $fCode,
				"RegAdmin" => $regAdmin,
				"isTeam" => $isTeam,
				"isTeamLeader" => $isTeamLeader,
				"isPaid" => $isPaid,
				"PayAdmin" => $payAdmin,
				"PayType" => $payType,
				"PayTime" => $payTime,
				"isAttended" => $isAttended,
				"AttendAdmin" => $attendAdmin,
				"AttendTime" => $attendTime,
				"EventRegTime" => $eventRegTime);

			array_push($list, $record);
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);

		return $list;
	}

	function getEventEntryByFCode($fCode) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Select query.
		$sql = "SELECT	`ERID`,
						`PID`,
						`EVID`,
						`ERCode`,
						`FCode`,
						`RegAdmin`,
						`isTeam`,
						`isTeamLeader`,
						`isPaid`,
						`PayAdmin`,
						`PayType`,
						DATE_FORMAT(`PayTime`, '%m/%d/%Y %h:%i %p') AS PayTime,
						`isAttended`,
						`AttendAdmin`,
						DATE_FORMAT(`AttendTime`, '%m/%d/%Y %h:%i %p') AS AttendTime,
						DATE_FORMAT(`EventRegTime`, '%m/%d/%Y %h:%i %p') AS EventRegTime
				FROM	`vs_event_reg`
				WHERE	`FCode` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 's', $fCode);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Bind result and fetch records.
		mysqli_stmt_bind_result($stmt, $eRID, $pID, $eVID, $eRCode, $fCode, $regAdmin, $isTeam, $isTeamLeader, $isPaid, $payAdmin, $payType, $payTime, $isAttended, $attendAdmin, $attendTime, $eventRegTime);
		$list = Array();
		while(mysqli_stmt_fetch($stmt)) {
			$record = Array(
				"ERID" => $eRID,
				"PID" => $pID,
				"EVID" => $eVID,
				"ERCode" => $eRCode,
				"FCode" => $fCode,
				"RegAdmin" => $regAdmin,
				"isTeam" => $isTeam,
				"isTeamLeader" => $isTeamLeader,
				"isPaid" => $isPaid,
				"PayAdmin" => $payAdmin,
				"PayType" => $payType,
				"PayTime" => $payTime,
				"isAttended" => $isAttended,
				"AttendAdmin" => $attendAdmin,
				"AttendTime" => $attendTime,
				"EventRegTime" => $eventRegTime);

			array_push($list, $record);
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);

		return $list;
	}

	function setAttended($isAttended, $attendAdmin, $attendTime, $eRCode) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Update query.
		$sql = "UPDATE	`vs_event_reg`
				SET		`isAttended` = ?,
						`AttendAdmin` = ?,
						`AttendTime` = ?
				WHERE	`ERCode` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'iiss', $isAttended, $attendAdmin, $attendTime, $eRCode);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	}

	function setPaid($isPaid, $payAdmin, $payType, $payTime, $eRCode) {
		// Connect to database.
		$conn = mysqli_connect(getServer(), getUserName(), getPassword(), getDatabaseName());
		if(mysqli_connect_error()) {
			die("Could not connect to database. " . mysqli_connect_error());
		}

		// Update query.
		$sql = "UPDATE	`vs_event_reg`
				SET		`isPaid` = ?,
						`PayAdmin` = ?,
						`PayType` = ?,
						`PayTime` = ?
				WHERE	`ERCode` = ?;";

		// Prepare statement.
		$stmt = mysqli_prepare($conn, $sql);
		if($stmt === false) {
			die("Invalid SQL specified. " . mysqli_error($conn));
		}

		// Bind parameters.
		mysqli_stmt_bind_param($stmt, 'iisss', $isPaid, $payAdmin, $payType, $payTime, $eRCode);

		// Execute the statement.
		if(!mysqli_stmt_execute($stmt)) {
			die("Could not execute the statement. " . mysqli_stmt_error($stmt));
		}

		// Close statement and connection.
		mysqli_stmt_close($stmt);
		mysqli_close($conn);
	}
?>