<?php include('includes/header.php'); ?> 

<div class="mainBody">
<div class="container">
		<div class="row">
			<div class="col-md-6">
				<div class="main-col">
					<div class="block">
						<h1 class="pull-left">Napravite Račun</h1>
						
						<div class="clearfix"></div>
						<hr>
						<form role="form" enctype="multipart/form-data" method="post" action="create_profile.php"> <!--pazi da ima enctype za file -->
							<input type="hidden" name="korisnicko_ime" value="<?php echo $_SESSION['korisnik']; ?>">
							<input type="hidden" name="prazna_slika" value="slike/empty_profile.png">
					<div class="form-group">
						<label>Spol</label>
					    <div class="radio">
					      <label>
					        <input type="radio" name="spol" id="optionsRadios1" value="1" checked>
					        Muško
					      </label>

					    </div>
					    <div class="radio">
					      <label>
					        <input type="radio" name="spol" id="optionsRadios2" value="2">
					        Žensko
					      </label>
					    </div>
					</div>
							<div class="form-group">
								<label>Zanimanje</label> <input type="text" class="form-control"
									name="zanimanje" placeholder="Unesite Vaše Zanimanje">
							</div>
							<div class="form-group">
								<label>O meni</label> <textarea class="form-control"
									name="o_meni" placeholder="Recite nešto o sebi"></textarea> 
							</div>
							<div class="form-group">
								<label>Grad</label> <input type="text" class="form-control"
									name="grad" placeholder="Unesite Vaš Grad">
							</div>
							<div class="form-group">
								<label>Slika</label> <input type="file" class="form-control"
									name="datoteka" placeholder="Unesite Vašu Sliku">
							</div>
							<input name="posalji" type="submit" class="btn btn-default" value="Kreiraj" />
						</form>
					</div>
				</div>
			</div>
			
		</div>
    </div><!-- /.container -->
</div>

<?php
	
if (isset($_POST['posalji'])) {

	$korisnicko_ime = mysqli_real_escape_string($kon, $_POST['korisnicko_ime']);
	$spol = mysqli_real_escape_string($kon, $_POST['spol']);
	$zanimanje = mysqli_real_escape_string($kon, $_POST['zanimanje']);
	$grad = mysqli_real_escape_string($kon, $_POST['grad']);
	$o_meni = mysqli_real_escape_string($kon, $_POST['o_meni']);
	
	$mjesto = "slike/";	
	
	//za upload bilo koje datoteke
	$ime_dat = basename($_FILES['datoteka']['name']);

	
 
	if($ime_dat != "" || $ime_dat != null){ //samo ako je file uzet kroz formu
	$slika = $mjesto.$ime_dat;	//blogslike/slika.jpg
	$stavi = move_uploaded_file($_FILES['datoteka']['tmp_name'],$slika);
	if(!$stavi){
		echo "<br>Greška kod stavljanja datoteke!";
	} 
//pridruzi folderu blogslike ime datoteke koju smo poslali kroz formu	
	}
	
	include('includes/dbConn.php');
	$kon = mysqli_connect($host, $username, $password, $db);
	
	$upit = "UPDATE korisnici SET spol='$spol', grad='$grad', zanimanje='$zanimanje', slika='$slika', o_meni='$o_meni' WHERE korisnicko_ime='$korisnicko_ime' LIMIT 1";
	$spremi = mysqli_query($kon,$upit);
	
	//----
	$prazna_slika = "slike/empty_profile.png";
	
	$prikazi = "SELECT * FROM korisnici WHERE korisnicko_ime='$korisnicko_ime' LIMIT 1";
	$spremiprikaz = mysqli_query($kon,$prikazi);
	
	while($red = mysqli_fetch_array($spremiprikaz)){
		
		$ime=$red['ime'];
		$prezime=$red['prezime'];
		$korisnicko_ime=$red['korisnicko_ime'];
		$spol=$red['spol'];
		$zanimanje=$red['zanimanje'];
		$grad=$red['grad'];
		$o_meni= $red['o_meni'];
		$slika=$red['slika'];

		$_SESSION['slika']=$slika;
		
		header("Location: profile.php?pogledaj=1");
		
	}
}
?>

<?php include('includes/footer.php'); ?> 