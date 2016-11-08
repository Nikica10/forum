<?php 

include('includes/header.php'); 

if(isset($_GET['kat_id'])) {
	$kat_id = $_GET['kat_id'];
}

function forma(){
	global $kat_id;
	
	?>
<form class="navbar-form navbar-left" action='topic.php?kat_id=<?php echo $kat_id; ?>' method='POST'>
					        <div class="form-group">
					          <input type="text" class="form-control" name='pojam' id='pojam' placeholder="Search">
					        
					        <button type="submit" name='pretrazi' class="btn btn-default">Submit</button>
					        </div>
					    </form>
<?php } 

?>

<div class="mainBody">
	<div class="col-md-8">
			<div class="block">
				<h3 class="text-center">Kreiraj Temu</h3>
					<form role="form" action="create_topic.php" method="POST">
						<div class="form-group">
							<label>Naslov Teme</label>
							<input type="text"  name="naslov_teme" class="form-control" placeholder="Unesite Naslov">
						</div>
						<div class="form-group">
							<label>Sadržaj Teme</label>
							<textarea name="sadrzaj_teme" class="form-control" placeholder="Unesite Sadržaj" rows="5"></textarea>
							
						</div>	
						<input type="hidden" name="id_kat" value="<?php echo $kat_id; ?>" />
						<button name="tema_submit" type="submit" class="btn btn-lg btn-primary btn-block">Kreiraj temu</button>
					</form>
			</div>
		</div>
</div>




<?php

// if ($_SESSION['korisnik'] == "") {
// 	header("Location: index.php");
// 	exit();

if (isset($_POST['tema_submit'])) {
	// Make sure that the title and content fields have been filled in
	if (($_POST['naslov_teme'] == "") && ($_POST['sadrzaj_teme'] == "")) {
		echo "Treba popuniti oba polja.";
		exit();
	} else {
		// Connect to the database
		include_once("includes/dbConn.php");
		// Assign the POST variables to local variables
		$kat_id = mysqli_real_escape_string($kon, $_POST['id_kat']);
		$naslov = mysqli_real_escape_string($kon, $_POST['naslov_teme']);
		$sadrzaj = mysqli_real_escape_string($kon, $_POST['sadrzaj_teme']);
		$korisnik = $_SESSION['idkor'];
		// Insert query to enter the topic information into the database
		$sql = "INSERT INTO teme (id_kat, naslov_teme, kreator_teme, tema_vrijeme, tema_odgovor_datum) VALUES (".$kat_id.", '".$naslov."', '".$korisnik."', now(), now())";
		// Execute the INSERT query
		$upit = mysqli_query($kon, $sql) or die(mysqli_error($kon));
		// Gather the generated mysql_insert_id from the INSERT query
		$novi_id_teme = mysqli_insert_id($kon);
		// Insert query to enter the post information into the database
		$sql2 = "INSERT INTO komentari (id_kat, id_teme, kreator_kom, sadrzaj_kom, vrijeme_kom) VALUES ('".$kat_id."', '".$novi_id_teme."', '".$korisnik."', '".$sadrzaj."', now())";
		// Execute the INSERT query
		$upit2 = mysqli_query($kon, $sql2) or die(mysqli_error($kon));
		// Update the forum category associated with this new topic
		$sql3 = "UPDATE kategorije SET zadnji_unos_vrijeme=now(), zadnji_korisnik=".$korisnik." WHERE id=".$kat_id." LIMIT 1";
		// Execute the category UPDATE query
		$upit3 = mysqli_query($kon, $sql3) or die(mysqli_error($kon));
		// Check to make sure all the required queries have been executed
		//nije završeno 17.8
		if (($upit) && ($upit2) && ($upit3)) {
			// $sql4 = "SELECT * FROM komentari WHERE id_kat='".$kat_id."' AND id_teme='".$novi_id_teme."'";
			// $upit4 = mysqli_query($kon, $sql4) or die(mysqli_error($kon));
			// while ($row = mysqli_fetch_assoc($upit4)) {
			// 	echo $row['sadrzaj_kom'];
			// }
			header('Location: replay.php?kat_id='.urlencode($kat_id).'&id_teme='.urlencode($novi_id_teme));
		} else {
			echo "Problem sa kreiranjem teme. Pokušajte ponovo.";
		} 

		
	}  

	
}

include('includes/footer.php'); 

?>