<?php include('includes/header.php'); ?>




    <?php



$metoda = $_SERVER['REQUEST_METHOD'];//POST ili GET

switch($metoda){
	
	case 'GET':
	//echo "<br>url: ".$_SERVER["QUERY_STRING"];
	if($_SERVER["QUERY_STRING"]==""){
		Registracija();
	}
	else
	{
		//echo "<br>url: ".$_SERVER["QUERY_STRING"];
		if(isset($_GET['aktiviraj'])){
			
			Aktiviraj();
		}
	}
	
	break;
	
	case 'POST':
	Registriraj();
	break;
	
}

function Registracija(){
?>
<div class="mainBody">
<div class="container">
		<div class="row">
			<div class="col-md-6">
				<div class="main-col">
					<div class="block">
						<h1 class="pull-left">Kreirajte Račun</h1>
						
						<div class="clearfix"></div>
						<hr>
						<form role="form" enctype="multipart/form-data" method="post" action="register.php"> <!--pazi da ima enctype za file -->
							<div class="form-group">
								<label>Ime*</label> <input type="text" class="form-control"
									name="ime" placeholder="Upišite Ime">
							</div>
							<div class="form-group">
								<label>Prezime*</label> <input type="text" class="form-control"
									name="prezime" placeholder="Upišite Prezime">
							</div>
							<div class="form-group">
								<label>Korisničko Ime*</label> <input type="text"
									class="form-control" name="korime" placeholder="Upišite Korisničko Ime">
							</div>
							<div class="form-group">
								<label>Lozinka*</label> <input type="password" class="form-control"
									name="sifra" placeholder="Unesite Vašu Lozinku">
							</div>
							<div class="form-group">
								<label>Ponovite Lozinku*</label> <input type="password" class="form-control"
									name="sifra2" placeholder="Ponovite Vašu Lozinku">
							</div>
							<div class="form-group">
								<label>Email*</label> <input type="email" class="form-control"
									name="email" placeholder="Upišite Svoj Email">
							</div>
							<input name="posalji" type="submit" class="btn btn-primary" value="Registriraj" />
						</form>
							
						
					<!-- 
				<div class="form-group">
		<label>Confirm Password*</label> <input type="password"
			class="form-control" name="password2"
			placeholder="Enter Password Again">
			</div>
				<div class="form-group">
					<label>Upload Avatar</label>
				<input type="file" name="avatar">
				<p class="help-block"></p>
					</div>
					<div class="form-group">
					<label>About Me</label>
					<textarea id="about" rows="6" cols="80" class="form-control"
					name="about" placeholder="Tell us about yourself (Optional)"></textarea>
			</div> -->

					</div>
				</div>
			</div>
			
		</div>
    </div><!-- /.container -->
</div>
<?php	
}


function Registriraj(){

	include('includes/dbConn.php');
	$kon = mysqli_connect($host, $username, $password, $db);

	if ($_POST['sifra'] !== $_POST['sifra2']) {
		echo "<div class='alert alert-danger' role='alert'>
				  <p>Lozinka nije identična. Probajte ponovo.</p>
			</div>";
		Registracija();
		exit();
	}
	
	$ime = mysqli_real_escape_string($kon, $_POST['ime']);
	if(isset($_POST['prezime'])){
	$prezime = mysqli_real_escape_string($kon, $_POST['prezime']);
	}
	$korime = mysqli_real_escape_string($kon, $_POST['korime']);
	$sifra= mysqli_real_escape_string($kon, $_POST['sifra']);
	$sifra = sha1($sifra);
	$email = mysqli_real_escape_string($kon, $_POST['email']);
	$akt_kljuc = AktivacijskiKljuc();

	
	$provjera_imena = "SELECT * FROM korisnici WHERE korisnicko_ime='$korime'";
	$provjera_unos = mysqli_query($kon, $provjera_imena);
	if (mysqli_num_rows($provjera_unos)>0) {
		echo "Ovo ime već postoji";
		echo "<p><a href='registracija.php'>Registracija</a></p>";
		exit();
	
	}else{
		
	//------
	$unos = "insert into korisnici (ime,prezime,korisnicko_ime,email,lozinka,akt_kljuc,status) values ('$ime','$prezime','$korime','$email','$sifra','$akt_kljuc',0)";
	
	$spremi = mysqli_query($kon,$unos);
	
	PosaljiMail($korime,$email,$akt_kljuc);
	header("Location: index.php");
}
}

function AktivacijskiKljuc(){
	
	$kljuc = "";
	$slova = "abcdefghijklmnopqrstuvwxyz0123456789";
	
	for($a=0;$a<50;$a++){
		$pozicija = rand(0,strlen($slova));
		$znak = substr($slova,$pozicija,1);
		$kljuc = $kljuc.$znak;
	}
	
	return $kljuc;
}

function PosaljiMail($korime,$email,$akt_kljuc){
	
	$naslov = "Aktivacijski link za vaš raèun";
	
	$poruka = "Poštovani korisniče ".$korime;
	$poruka .= "\n\Da bi ste aktivirali vaš raèun, kliknite na donji link:";
	$link = "http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI']."?aktiviraj=1&korime=$korime&akt_kljuc=$akt_kljuc";

	$poruka .= "\n\n<a href=".$link."></a>";

	//$poruka .= "\n\nNakon aktivacije, stiæi æe vam potvrdni mail o aktivaciji";
	
	//echo $poruka;
	//global $kon;
	//$sql = "SELECT email FROM korisnici";
	//$email = mysqli_query($kon, $sql);
	mail($email,"My subject",$poruka);
}

function Aktiviraj(){
	
	$korime = $_GET['korime'];
	$akt_kljuc = $_GET['akt_kljuc'];
	$akt = $_GET['aktiviraj'];
	
	include('includes/dbConn.php');
	$kon = mysqli_connect($host, $username, $password, $db);
	//Konekcija();
	//$kon = mysqli_connect("localhost", "root", "", "forum_nb");
	// $kon = mysqli_connect("mysql.hostinger.hr", "u655849946_user", "test12345", "u655849946_baza");
	$aktiviraj = "update korisnici set status = 1 where korisnicko_ime = '$korime' and akt_kljuc = '$akt_kljuc'";
	$spremi = mysqli_query($kon,$aktiviraj);
	if($spremi){
		
		echo "<script>";
		echo "alert('Raèun uspješno aktiviran')";
		echo "</script>";
	}
	else
	{
		echo "<br>Greška: ".mysqli_connect_error();
	}
	header("Location: index.php");
}


?>

    <?php include('includes/footer.php'); ?>