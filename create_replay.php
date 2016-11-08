<?php include('includes/header.php');  

function forma(){
	global $kat_id;
	
	?>
<form class="navbar-form navbar-left" action='topic.php?kat_id=<?php echo $kat_id; ?>' method='POST'>
					        <div class="form-group">
					          <input type="text" class="form-control" name='pojam' id='pojam' placeholder="Search">
					        
					        <button type="submit" name='pretrazi' class="btn btn-default">Submit</button>
					        </div>
					    </form>
<?php } ?>

<div class="mainBody">
	<div class="col-md-8">
			<div class="block">
				<h3 class="text-center">Komentiraj</h3>
					<form role="form" action="create_replay.php" method="POST">
						<div class="form-group">
							<label>Upište Komentar</label>
							<textarea class="ckeditor" name="sadrzaj_odgovora"></textarea>
						</div>
						<input type="hidden" name="kat_id" value="<?php echo $kat_id; ?>" />
						<input type="hidden" name="id_teme" value="<?php echo $id_teme; ?>" />

						<button name="posalji_odgovor" type="submit" class="btn btn-lg btn-primary btn-block">Pošalji</button> 
					</form>
			</div>
		</div>
</div>

<?php 

if ($_SESSION['korisnik']) {
	if (isset($_POST['posalji_odgovor'])) {
		
		$kreator = $_SESSION['idkor'];
		$kat_id = $_POST['kat_id'];
		$id_teme = $_POST['id_teme'];
		$sadrzaj_odgovora = mysqli_real_escape_string($kon, $_POST['sadrzaj_odgovora']);
		$sadrzaj_odgovora = str_replace("'", "", $sadrzaj_odgovora);
		// Insert query to enter the information into the posts table
		$sql = "INSERT INTO komentari (id_kat, id_teme, kreator_kom, sadrzaj_kom, vrijeme_kom) VALUES ('".$kat_id."', '".$id_teme."', '".$kreator."', '".$sadrzaj_odgovora."', now())";
		// Execute the INSERT query
		$upit = mysqli_query($kon, $sql) or die(mysqli_error($kon));
		// Update query that will update the category that is associated with this topic reply
		$sql2 = "UPDATE kategorije SET zadnji_unos_vrijeme=now(), zadnji_korisnik='".$kreator."' WHERE id='".$kat_id."' LIMIT 1";
		// Execute the category UPDATE query
		$upit2 = mysqli_query($kon, $sql2) or die(mysqli_error());
		// Update query that will update the topic that is associated with this topic reply
		$sql3 = "UPDATE teme SET tema_odgovor_datum=now(), zadnji_kor_teme='$kreator' WHERE id='$id_teme' LIMIT 1";
		// Execute the topic UPDATE query
		$upit3 = mysqli_query($kon, $sql3) or die(mysqli_error());
		
		//START THE EMAIL PROCESSING SCRIPT
		// Select query that will select the post_creators associated with the topic you are replying to
		$sql4 = "SELECT kreator_kom FROM komentari WHERE id_kat='".$kat_id."' AND id_teme='".$id_teme."' GROUP BY kreator_kom";
		// Execute the SELECT query
		$upit4 = mysqli_query($kon, $sql4) or die(mysqli_error());

	

		// Check to make sure all the required queries have been executed
		if (($upit) && ($upit2) && ($upit3)) {
			//echo "<p>Komentar uspješno poslan. <a href='pogledaj_temu.php?kat_id=".$kat_id."&id_teme=".$id_teme."'>Vrati se na temu.</a></p>";
			header("Location: topic.php?kat_id=$kat_id&id_teme=$id_teme");
		} else {
			//echo "<p>Komentar nije poslan. Probajte ponovo.</p>";
			echo "<div class='alert alert-danger' role='alert'>
					 Komentar nije poslan. Probajte ponovo.
				  </div>";
							}
		
	} else {
		//exit();
		
	}
} else {
	//exit();
	//echo "Morate biti prijavljeni da sudjelujete u forumu.";
	echo "<div class='alert alert-danger' role='alert'>
					 Morate biti prijavljeni da sudjelujete u forumu.
				  </div>";
}
?>
<?php

include('includes/footer.php'); 

?> 