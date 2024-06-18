<?php
class Database
{
	public static function establishConnection(string $db_host='localhost', string $db_user='root', string $db_pass='', string $db_name='test')
	{
		$conn = mysqli_connect($db_host, $db_user, $db_pass, $db_name);
	
		if (mysqli_connect_errno()) 
		{
			echo "Failed to connect to MySQL: " . mysqli_connect_error();
			exit();
		}

		return $conn;
	}

	public static function getRows($conn, $tableName, $sql='')
	{
		if ($sql === '')
		{
			$sql = "select * from $tableName";
		}

		$result = mysqli_query($conn, $sql);

		if (!$result)
		{
			echo '<br>Query Error:<br>';
			exit();
		}

		$rows = [];
		while ($row = mysqli_fetch_assoc($result))
		{
			$rows[] = $row;
		}

		mysqli_close($conn);
		return $rows;
	}

	public static function displayTable($conn, $tableName, $sql='')
	{
		if ($sql === '')
		{
			$sql = "select * from $tableName";
		}

		$result = mysqli_query($conn, $sql);

		if (!$result)
		{
			echo '<br>Query Error:<br>';
			exit();
		}

		while ($row = mysqli_fetch_assoc($result))
		{
			foreach ($row as $key => $val)
			{
				echo "$val | ";
			}
			echo '<br>';
		}

		mysqli_close($conn);
	}
}
