<?php

include('includes/header.php'); 

$kat_id = $_GET['kat_id'];
		$id_teme = $_GET['id_teme'];
		// Assign local variables
		if(isset($_GET['kat_id'])){
			$kat_id = $_GET['kat_id'];
			$_SESSION['kat_id']=$kat_id;
			}

function korisnik($id_kor) {
	global $kon;
	$sql = "SELECT korisnicko_ime FROM korisnici WHERE id='$id_kor' LIMIT 1";
	//$sql = "SELECT korisnici.korisnicko_ime,profil.slika FROM korisnici,profil WHERE korisnici.id='$id_kor'";
	$res = mysqli_query($kon, $sql) or die(mysqli_error($kon));
	$row = mysqli_fetch_assoc($res);
	$ime = $row['korisnicko_ime'];
	return $ime;
}

// Function that will convert the datetime string from the database into a user-friendly format
function prilagodi_vrijeme($vrijeme) {
	$vrijeme = strtotime($vrijeme);
	return date("d.m.Y",$vrijeme)." u ".date("H:i:s",$vrijeme);
}

//------------------moj kod

function slika($ime){
	global $kon;
	$sql2 = "SELECT * FROM korisnici WHERE korisnicko_ime='$ime'";
	$res2 = mysqli_query($kon, $sql2) or die(mysqli_error($kon));
	while ($row2 = mysqli_fetch_array($res2)) {
		$slika = $row2['slika'];
		$prazna_slika = "slike/empty_profile.png";
	if ($slika == "") {
			return "<img src='$prazna_slika' width='100px' height='100px'>.<br>";
		}else{
			return "<img src='$slika' width='100px' height='100px'>.<br>";
		};
	}
	}

function odgovor(){
	global $kat_id;
	global $id_teme;
	// danas 24.5
	global $kon;
	$upit = "SELECT * FROM komentari WHERE id_kat='$kat_id' AND id_teme='$id_teme' LIMIT 1";
	$rezultat = mysqli_query($kon, $upit) or die(mysqli_error($kon));
	$bla = "";

	while ($row = mysqli_fetch_assoc($rezultat)) {
		// davanje vrijednosti varijabli iz kategorija 
		$id_komentara = $row['id'];
		$bla = $bla . $id_komentara;
		// echo $bla;
	
	}
	if (isset($_SESSION['korisnik'])){
		return "<a href='create_replay.php?kat_id=".$kat_id."&id_teme=".$id_teme."&id_kom=".$bla."'>Odgovori</a>";
	}

	if (!isset($_SESSION['korisnik'])){
		return "<a href='login.php'>Odgovori</a>";
	}
}

//forma za komentare
function forma(){
	global $kat_id;
	global $id_teme;
	?>
<form class="navbar-form navbar-left" action='replay.php?kat_id=<?php echo $kat_id; ?>&id_teme=<?php echo $id_teme; ?>' method='POST'>
					        <div class="form-group">
					          <input type="text" class="form-control" name='pojam' id='pojam' placeholder="Search">
					        </div>
					        <button type="submit" name='pretrazi' class="btn btn-default">Submit</button>
					    </form>
<?php } 

//------------------

$sql8 = "SELECT id_kat, id_teme FROM komentari WHERE id_kat=".$kat_id." AND id_teme=".$id_teme." LIMIT 1";

$upit8 = mysqli_query($kon, $sql8) or die(mysqli_error($kon));


if (mysqli_num_rows($upit8) == 1){
//kad smo odabrali temu,,, ukoliko smo dosli sa forme, aktivirali smo submit button POST['pretrazi']
	if(isset($_POST['pretrazi'])){ //ako smo aktivirali form pretrrazi
	$pojam = $_POST['pojam']; //uzet cemo pojam... i izvrsiti ovaj upit ispod...

	$sql = "SELECT * FROM komentari WHERE id_kat=".$kat_id." and id_teme=".$id_teme." and sadrzaj_kom like '%$pojam%' ORDER BY vrijeme_kom DESC";
	}
	else //inace izvrsiti obicni upit bez pretrage
	{
	$sql = "SELECT * FROM komentari WHERE id_kat='".$kat_id."' AND id='".$id_teme."' LIMIT 1";	
	}

	//echo "<br>Upit: ".$sql;
	}


//------------------



$sql = "SELECT * FROM teme WHERE id_kat='".$kat_id."' AND id='".$id_teme."' LIMIT 1";
// Execute the SELECT query
$upit = mysqli_query($kon, $sql) or die(mysqli_error());

if (mysqli_num_rows($upit) == 1) {
	
	// Fetch all the topic data from the database
	while ($row = mysqli_fetch_assoc($upit)) {
// ovdje završava tema

	$per_page=5;

	if (isset($_GET['stranica'])) {
			$page = $_GET['stranica'];
		}else {
			$page=1;
		}

// Page will start from 0 and Multiple by Per Page
$start_from = ($page-1) * $per_page;
		// Query the posts table for all posts in the specified topic
		if(isset($_POST['pojam'])){
			$_SESSION['pojam']=$_POST['pojam'];
		$sql2 = "SELECT * FROM komentari WHERE id_kat=".$kat_id." and id_teme=".$id_teme." and sadrzaj_kom like '%$pojam%' ORDER BY vrijeme_kom DESC LIMIT $start_from, $per_page";
		}
		else
		{

			if(isset($_SESSION['pojam'])){
				$pojam = $_SESSION['pojam'];//cuva search pojam od prije...
				$sql2 = "SELECT * FROM komentari WHERE id_kat=".$kat_id." and id_teme=".$id_teme." and sadrzaj_kom like '%$pojam%' ORDER BY vrijeme_kom DESC LIMIT $start_from, $per_page";
			}
			else
			{
				$sql2 = "SELECT * FROM komentari WHERE id_kat='$kat_id' AND id_teme='$id_teme' LIMIT $start_from, $per_page";
			}
			
		}
		
		// Execute the SELECT query
		$upit2 = mysqli_query($kon, $sql2) or die(mysqli_error());
		// Fetch all the post data from the database
		while ($row2 = mysqli_fetch_assoc($upit2)) {


			// Echo out the topic post data from the database
			$netko = korisnik($row2['kreator_kom']);

			?>
	
	<div class="mainBody">
		<div class="col-md-10">
			<div class="main-col">
				<div class="block">
					<div id="topics">
						<div class="row">
									<div class="col-md-2">
									
										<div class="user-info">
											
										
												<div class="avatar"> <?php echo slika(korisnik($row2['kreator_kom'])); ?> </div>
												<p><strong><?php echo korisnik($row2['kreator_kom']); ?></strong></p>
												<p><?php echo prilagodi_vrijeme($row2['vrijeme_kom']); ?></p>
												<p><a href="profile.php?korisnik=<?php echo $netko; ?>">Profile</a></p>
											
										</div>
									</div>
									<div class="col-md-8">
										<div class="topicName"><p>Tema: <?php echo $row['naslov_teme']; ?></p><hr></div>
										<div class="topic-content pull-right">
											<p class="text-justify"><?php echo $row2['sadrzaj_kom'] ?></p>
										</div>
									</div>
						</div>
					</div>
				</div>
			</div>		
		</div>
	</div>		
	

			
			<?php

			}
	//ovo je brojač pogleda
		// Assign local variable for the current number of views that this topic has
		$stari_pogledi = $row['tema_pogledi'];
		// Add 1 to the current value of the topic views
		$novi_pogledi = $stari_pogledi + 1;
		// Update query that will update the topic_views for this topic
		$sql3 = "UPDATE teme SET tema_pogledi='".$novi_pogledi."' WHERE id_kat='".$kat_id."' AND id='".$id_teme."' LIMIT 1";
		// Execute the UPDATE query
		$upit3 = mysqli_query($kon, $sql3) or die(mysqli_error());

//ovo mi je za paginaciju ako je iz searcha ili ne

if(isset($_POST['pojam'])){
	$sql4 = "SELECT * FROM komentari WHERE id_kat=".$kat_id." and id_teme=".$id_teme." and sadrzaj_kom like '%$pojam%' ORDER BY vrijeme_kom DESC";
}
else
{
	$sql4 = "SELECT * FROM komentari WHERE id_kat='$kat_id' AND id_teme='$id_teme'";
}

$upit4 = mysqli_query($kon, $sql4);

// zbroj zapisa
$total_records = mysqli_num_rows($upit4);

//korištenje ceil funkcije da podjeli $total_records i $per_page
$total_pages = ceil($total_records / $per_page);


//prva stranica
?>

<button type="button" class="btn btn-primary btn-lg center-block"><?php echo odgovor(); ?></button>


 <!--bootstrap paginaciija-->

<div class="text-center">
<nav aria-label="Page navigation">
  <ul class="pagination">

    <li>
      <a href="replay.php?kat_id=<?php echo $kat_id; ?>&id_teme=<?php echo $id_teme; ?>&stranica=1" aria-label="Previous">
        <span aria-hidden="true">&laquo;</span>
      </a>
    </li>
    <?php for ($i=1; $i<=$total_pages; $i++) { ?>
    <li><a href="replay.php?kat_id=<?php echo $kat_id; ?>&id_teme=<?php echo $id_teme; ?>&stranica=<?php echo $i; ?>"><?php echo $i; ?></a></li>
   <?php } ?>
    <li>
      <a href="replay.php?kat_id=<?php echo $kat_id; ?>&id_teme=<?php echo $id_teme; ?>&stranica=<?php echo $total_pages; ?>" aria-label="Next">
        <span aria-hidden="true">&raquo;</span>
      </a>
    </li>
  </ul>
</nav>
</div>
 <!--bootstrap paginacija završava-->



<?php
	}

}else {
	// ako tema ne postoji
	echo "<p>Ova tema ne postoji.</p>";
}

include('includes/footer.php'); 



//-------------test-------------



	

?>