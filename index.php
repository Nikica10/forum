<?php 

include('includes/header.php'); 

// sql upit
$sql = "SELECT * FROM kategorije ORDER BY ime_kat ASC";
// dozvoljava čćžđš
mysqli_set_charset($kon,"utf8");
// izvršava upit
$res = mysqli_query($kon, $sql) or die(mysqli_error($kon));


function broj_tema($kat_id){
	global $kon;
	$sql = "SELECT count(*) AS broj FROM teme WHERE id_kat='$kat_id'";
	$upit = mysqli_query($kon, $sql) or die(mysqli_error($kon));
	$row = mysqli_fetch_assoc($upit);
	return $row['broj'];
}


function zadnje_teme($kat_id){ //	OVU FUNKCIJU JOS PROUCIT
	
	global $kon;
	// $naslov = "";
	$sql ="SELECT * FROM teme WHERE id_kat='$kat_id' ORDER BY tema_vrijeme DESC LIMIT 2";
$upit = mysqli_query($kon, $sql) or die(mysqli_error($kon));
$teme = "";
while ($row =mysqli_fetch_array($upit)) {
	// $naslov = array($row['naslov_teme']); 
	// print_r($naslov[0]);
	$naslov = $row['naslov_teme'].", "."<br>";
	$teme = $teme.$naslov;
	//return $naslov;
}
return substr($teme,0,strlen($teme)-6);
//return $teme;

}


?>



<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<div class="mainBody">
		<div class="col-md-10">
	
			<table class="table table-hover">
			<tr>
				<th>Naslov teme</th><th class="text-center">Broj Tema</th><th class="text-center">Zadnje teme</th><th class="text-center">Pogledi</th>
			</tr>
			<?php while ($row = mysqli_fetch_assoc($res)) {
					$kat_id = $row['id'];
					$title = $row['ime_kat'];
					$description = $row['opis_kat'];
					$pogledi = $row['kat_pogledi'];
			 ?>
			<tr>
		
				<td><a href='topic.php?kat_id=<?php echo $kat_id ?>'><?php echo $title;?></a><br><p><?php echo $description; ?></p> </td>
				<td class="text-center"><?php echo broj_tema($kat_id);?></td>
				<td class="text-center"><small><?php echo zadnje_teme($kat_id); ?></small></td>
				<td class="text-center"><?php echo $pogledi;?></td>
			</tr>
			<?php } ?>
		  	</table>
	  	</div>
	  	
	  	
	</div>

	
</body>
</html>


<?php include('includes/footer.php'); ?>