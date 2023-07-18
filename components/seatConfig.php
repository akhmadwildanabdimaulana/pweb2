<?php
ini_set('max_execution_time', 300);
?>
<?php
require 'db-ts.php';

$sql = "DELETE FROM bus WHERE tglKonser < CURDATE()";
$result = $koneksi->query($sql);
echo $koneksi->error;

//
$sql_instance = "SELECT * FROM bus WHERE tglKonser = CURDATE() ORDER BY wktKonser ASC;";
$result = $koneksi->query($sql_instance);
$row = $result->fetch_assoc();
if ($result->num_rows == 0) {
    $sql_instance = "SELECT * FROM rute ORDER BY wktKonser ASC;";
    $result = $koneksi->query($sql_instance);
    $ind = 0;
    while ($row = $result->fetch_assoc()) {
        if ($result->num_rows > 0) {
            $result2 = $koneksi->query("INSERT INTO bus VALUES
	        				(DAYOFWEEK(CURDATE())+'" . $ind . "','" . $row["ruteID"] . "','" . $row["kapasitas"] . "',CURDATE(),'" . $row["wktKonser"] . "');");
            $ind = $ind + 1;
            echo $koneksi->error;
        } else break;
    }
}

$sql_instance = "SELECT * FROM bus WHERE tglKonser = CURDATE() + INTERVAL 1 DAY ORDER BY wktKonser ASC;";
$result = $koneksi->query($sql_instance);
$row = $result->fetch_assoc();
if ($result->num_rows == 0) {
    $sql_instance = "SELECT * FROM rute ORDER BY wktKonser ASC;";
    $result = $koneksi->query($sql_instance);
    $ind = 0;
    while ($row = $result->fetch_assoc()) {
        if ($result->num_rows > 0) {
            $result2 = $koneksi->query("INSERT INTO bus VALUES
	        				(DAYOFWEEK(CURDATE() + INTERVAL 1 DAY)*10+'" . $ind . "','" . $row["ruteID"] . "','" . $row["kapasitas"] . "',CURDATE() + INTERVAL 1 DAY,'" . $row["wktKonser"] . "');");
            $ind = $ind + 1;
            echo $koneksi->error;
        } else break;
    }
}

$sql = "DELETE FROM tiket WHERE tglKonser < CURDATE() - INTERVAL 2 DAY";
$result = $koneksi->query($sql);
echo $koneksi->error;

$sql = "SELECT * FROM tiket WHERE tglKonser = CURDATE()";
$result = $koneksi->query($sql);
echo $koneksi->error;
if ($result->num_rows == 0) {
    $sql1 = "SELECT konserID, ruteID, jumlah_kursi FROM bus WHERE tglKonser = CURDATE()";
    $result1 = $koneksi->query($sql1);
    echo $koneksi->error;
    if ($result1->num_rows > 0) {
        while ($row = $result1->fetch_assoc()) {
            for ($i = 1; $i <= $row["jumlah_kursi"]; $i++) {
                $sql2 = "INSERT INTO tiket VALUES (" . $row["konserID"] . "," . $row["ruteID"] . "," . $i . ",NULL,CURDATE());";
                $result2 = $koneksi->query($sql2);
                echo $koneksi->error;
            }
        }
    }
}

$sql = "SELECT * FROM tiket WHERE tglKonser = CURDATE() + INTERVAL 1 DAY";
$result = $koneksi->query($sql);
echo $koneksi->error;
if ($result->num_rows == 0) {
    $sql1 = "SELECT konserID, ruteID, jumlah_kursi FROM bus WHERE tglKonser = CURDATE() + INTERVAL 1 DAY";
    $result1 = $koneksi->query($sql1);
    echo $koneksi->error;
    if ($result1->num_rows > 0) {
        while ($row = $result1->fetch_assoc()) {
            for ($i = 1; $i <= $row["jumlah_kursi"]; $i++) {
                $sql2 = "INSERT INTO tiket VALUES (" . $row["konserID"] . "," . $row["ruteID"] . "," . $i . ",NULL,CURDATE() + INTERVAL 1 DAY);";
                $result2 = $koneksi->query($sql2);
                echo $koneksi->error;
            }
        }
    }
}
