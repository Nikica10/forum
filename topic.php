<?php 

include('includes/header.php'); 

if(isset($_GET['kat_id'])){
			$kat_id = $_GET['kat_id'];
			$_SESSION['kat_id']=$kat_id;
			}

function tema_komentari($kat_id, $tema_id){
	global $kon;
	$sql = "SELECT count(*) AS tema_komentari FROM komentari WHERE id_kat='$kat_id' AND id_teme='$tema_id'";
	$upit = mysqli_query($kon, $sql) or die(mysqli_error());
	$row = mysqli_fetch_assoc($upit);
	return $row['tema_komentari'] - 1;
}

function korisnik($id_kor) {
	global $kon;
	$sql = "SELECT korisnicko_ime FROM korisnici WHERE id='$id_kor' LIMIT 1";
	$upit = mysqli_query($kon, $sql) or die(mysqli_error());
	$row = mysqli_fetch_assoc($upit);
	return $row['korisnicko_ime'];
}


// Function that will convert the datetime string from the database into a user-friendly format
function prilagodi_vrijeme($vrijeme) {
	$vrijeme = strtotime($vrijeme);
	return date("d.m.Y G:i", $vrijeme);
}

//forma za pretragu pojma... ide na samu sebe...
function forma(){
	global $kat_id;
	
	?>
<form class="navbar-form navbar-left" action='topic.php?kat_id=<?php echo $kat_id; ?>' method='POST'>
					        <div class="form-group">
					          <input type="text" class="form-control" name='pojam' id='pojam' placeholder="Search">
					        
					        <button type="submit" name='pretrazi' id="searchBtn" class="btn btn-default">Submit</button>
					        </div>
					    </form>
<?php } 
//------------------------------------------------------------------


$sql = "SELECT * FROM kategorije WHERE id=".$_SESSION['kat_id']." LIMIT 1";

$upit = mysqli_query($kon, $sql) or die(mysqli_error($kon));

if (mysqli_num_rows($upit) == 1){
//kad smo odabrali temu,,, ukoliko smo dosli sa forme, aktivirali smo submit button POST['pretrazi']
	if(isset($_POST['pretrazi'])){ //ako smo aktivirali form pretrrazi
	$pojam = $_POST['pojam']; //uzet cemo pojam... i izvrsiti ovaj upit ispod...
	$sql2 = "SELECT * FROM teme WHERE id_kat=".$_SESSION['kat_id']." and naslov_teme like '%$pojam%' ORDER BY tema_odgovor_datum DESC";
	}
	else //inace izvrsiti obicni upit bez pretrage
	{
	$sql2 = "SELECT * FROM teme WHERE id_kat=".$_SESSION['kat_id']." ORDER BY tema_odgovor_datum DESC";	
	}


	

	
	$upit2 = mysqli_query($kon, $sql2) or die(mysqli_error($kon));

	?>

	<div class="mainBody">
		<div class="col-md-10">
	
			<table class="table table-hover">
			<tr>
				<th>Naslov teme</th>
				<th class="text-center">Posljednji Korisnik</th>
				<th class="text-center">Broj Komentara</th>
				<th class="text-center">Pogledi</th>
			</tr>
			<?php 

			while ($row2 = mysqli_fetch_assoc($upit2)) {
			// Assign local variables from the database data
			$id_teme = $row2['id'];
			$naslov = $row2['naslov_teme'];
			$pogledi = $row2['tema_pogledi'];
			$vrijeme = $row2['tema_vrijeme'];
			$kreator = $row2['kreator_teme'];

			if ($row2['zadnji_kor_teme'] == "") { $zadnji_korisnik = "N/A"; } else { $zadnji_korisnik = korisnik($row2['zadnji_kor_teme']); }
			 ?>
			<tr>
				<td><a href='replay.php?kat_id=<?php echo $kat_id; ?>&id_teme=<?php echo $id_teme; ?>'><?php echo $naslov;?></a></td>
				<td class="text-center"><?php echo $zadnji_korisnik;?></td>
				<td class="text-center"><small><?php echo tema_komentari($kat_id, $id_teme) ?></small></td>
				<td class="text-center"><?php echo $pogledi;?></td>
			</tr>
			<?php } ?>
		  	</table>
	  	</div>
	  	
	  	
	</div>
	<?php
		//4.6 pogledi kategorije---------------------------------------------------------
		// $sql7 = "SELECT * FROM kategorije";
		// $upit7 = mysqli_query($kon, $sql7) or die(mysqli_error($kon)); 
		 while ($row = mysqli_fetch_array($upit)) {
		$stari_pogledi1 = $row['kat_pogledi'];
		
		// Add 1 to the current value of the topic views
		$novi_pogledi1 = $stari_pogledi1 + 1;
		// Update query that will update the topic_views for this topic
		$sql3 = "UPDATE kategorije SET kat_pogledi='".$novi_pogledi1."' WHERE id='".$kat_id."' LIMIT 1";
		// Execute the UPDATE query
		$upit3 = mysqli_query($kon, $sql3) or die(mysqli_error($kon));
		
		
	}
		//gotovo pogledi kategorije--------------------------------------------------------
	} else {
		// If there are no topics
		// echo "<a href='index.php'>Početna stranica</a><hr />";
		echo "<p>Nema još tema u ovoj kategoriji.</p>";
		echo "<a href='index.php'>Vrati se na početnu stranicu</a><hr />";
	echo "<p>Ova kategorija trenutno ne postoji.";
	}






include('includes/footer.php'); 

?>