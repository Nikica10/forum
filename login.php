<?php include('includes/header.php'); ?>



<?php

if(session_id()==""){ //
	session_start();
}
$metoda = $_SERVER['REQUEST_METHOD'];//POST ili GET

switch($metoda){
	
	case 'GET':
	
	Login();
	break;
	
	case 'POST':
	Logiraj();
	break;
	
}


function Login(){
?>	
<div class="mainBody">
	<div class="col-md-4">
			<div class="block">
				<h3 class="text-center">Prijava</h3>
					<form role="form" action="login.php" method="POST">
						<div class="form-group">
							<label>Korisničko ime</label>
							<input name="korime" type="text" class="form-control" placeholder="Enter Username">
						</div>
						<div class="form-group">
							<label>Lozinka</label>
							<input name="sifra" type="password" class="form-control" placeholder="Enter Password">
						</div>	

						<button name="posalji" type="submit" class="btn btn-lg btn-primary btn-block">Logiraj</button> <a  class="btn btn-lg btn-default btn-block" href="register.php">Registracija</a>
					</form>
			</div>
		</div>
</div>
<?php
}

function Logiraj(){

	include('includes/dbConn.php');
	$kon = mysqli_connect($host, $username, $password, $db);
	
	$korime = mysqli_real_escape_string($kon, $_POST['korime']);
	$sifra = mysqli_real_escape_string($kon, $_POST['sifra']);

	
	$sifra = sha1($sifra);
	
	//$kon = mysqli_connect("localhost", "root", "", "forum_nb2");

	//$kon = mysqli_connect("mysql.hostinger.hr", "u812941227_user", "test12345", "u812941227_db");

	
	
	$login = "select * from korisnici where korisnicko_ime='$korime' and lozinka='$sifra' and status = 1";
	//echo "<br>Login: ".$login;
	$spremi = mysqli_query($kon,$login);
	//echo "<br>redovi: ".mysqli_num_rows($spremi);
	if($spremi && mysqli_num_rows($spremi)>0){
		//echo "usao";
		while($red = mysqli_fetch_array($spremi)){
			
			$korid = $red['id'];
			$korisnik = $red['korisnicko_ime'];
			$email = $red['email'];
			//sad ćemo tek kreirati novu varijablu $_SESSION['korisnik']
			//i u nju pospremiti korisnika iz tablice, tj. onog tko se prijavio...
			$_SESSION['korisnik']=$korisnik;
			$_SESSION['idkor']=$korid;
			//$_SESSION['korisnik'] = "mmaric";
			
		}

		//napravi redirekciju na index.php
		header("Location: index.php");
	}
			else
		{
			echo "<div class='alert alert-danger' role='alert'>
				  <p>Korisničko ime ili lozinka nisu ispravni. Pokušajte ponovo.</p>
				</div>".mysqli_error($kon);
		}
	
	//echo "<br>Dobro dosao ".$korisnik.", vas mail je: ".$email;
	
}


?>

<?php include('includes/footer.php'); ?>