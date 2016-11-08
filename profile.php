<?php

include('includes/header.php');




	if(isset($_GET['pogledaj']) && $_GET['pogledaj']==1){

		$korisnik = $_SESSION['korisnik'];

	}else{

		$ime = $_GET['korisnik'];
	}



function komentator($ime){

	// $host = "mysql.hostinger.hr";   
	// $username = "u655849946_user";  
	// $password = "test12345";       
	// $db = "u655849946_baza";

	// $host = "localhost";
	// $username = "root";
	// $password = "";
	// $db = "forum_nb2";
	//  $kon = mysqli_connect($host, $username, $password, $db);
	include('includes/dbConn.php');
				
	$kon = mysqli_connect($host, $username, $password, $db);
	//OtvoriBazu();
	$sql = "SELECT * FROM korisnici WHERE korisnicko_ime='$ime' LIMIT 1";
$unos = mysqli_query($kon,$sql) or die($kon);
$prazna_slika = "slike/empty_profile.png";

while($red = mysqli_fetch_array($unos)){
		
		$ime=$red['ime'];
		$prezime=$red['prezime'];
		$korisnicko_ime=$red['korisnicko_ime'];
		$spol=$red['spol'];
		$zanimanje=$red['zanimanje'];
		$grad=$red['grad'];
		$o_meni= $red['o_meni'];
		$slika=$red['slika'];

		//$_SESSION['slika']=$slika;
		?>
		
		<div class="mainBody">
<div class="col-md-4">
<table class="table table-bordered">

  <tbody>
    <tr>
      
      <th>Ime:</th>
      <td><?php echo $ime; ?></td>
      
    </tr>
    <tr>
      
      <th>Prezime:</th>
      <td><?php echo $prezime; ?></td>
      
    </tr>
    <tr>
      <th>Korisničko Ime:</th>
      <td><?php echo $korisnicko_ime; ?></td>
     </tr>
     <tr>
      <th>Spol:</th>
      <td><?php if ($spol == 1) {
			echo "Muško";
		}else if($spol == 2){
			echo "Žensko";
		} else {
			echo "";
		} ?>
		</td>
     </tr>
     <tr>
      <th>Grad:</th>
      <td><?php echo $grad; ?></td>
     </tr>
     <tr>
      <th>Zanimanje:</th>
      <td><?php echo $zanimanje; ?></td>
     </tr>
     <tr>
      <th>O Meni:</th>
      <td><?php echo $o_meni; ?></td>
     </tr>
     <tr>
      <th>Slika:</th>
      <td><?php 
      		if ($slika == "") {
				echo "<img src='$prazna_slika' width='100px' height='100px'>";
			}else{
				echo "<img src='$slika' width='100px' height='100px'>";
			}
		?>
		</td>
     </tr>
  </tbody>
</table>
</div>
</div>
<?php
		
	
		
	}
}


function korisnik($korisnik){
	// $host = "localhost";
	// $username = "root";
	// $password = "";
	// $db = "forum_nb2";

	// $host = "mysql.hostinger.hr";  		
	// $username = "u655849946_user";   
	// $password = "test12345";       
	// $db = "u655849946_baza";
	include('includes/dbConn.php');
				
	$kon = mysqli_connect($host, $username, $password, $db);
	$sql = "SELECT * FROM korisnici WHERE korisnicko_ime='$korisnik' LIMIT 1";
	mysqli_set_charset($kon,"utf8");
$unos = mysqli_query($kon,$sql) or die($kon);
$prazna_slika = "slike/empty_profile.png";

while($red = mysqli_fetch_array($unos)){
		
		$ime=$red['ime'];
		$prezime=$red['prezime'];
		$korisnicko_ime=$red['korisnicko_ime'];
		$spol=$red['spol'];
		$zanimanje=$red['zanimanje'];
		$grad=$red['grad'];
		$o_meni= $red['o_meni'];
		$slika=$red['slika'];

		//$_SESSION['slika']=$slika;
		?>

		<div class="mainBody">
<div class="col-md-4">
<table class="table table-bordered">

  <tbody>
    <tr>
      
      <th>Ime:</th>
      <td><?php echo $ime; ?></td>
      
    </tr>
    <tr>
      
      <th>Prezime:</th>
      <td><?php echo $prezime; ?></td>
      
    </tr>
    <tr>
      <th>Korisničko Ime:</th>
      <td><?php echo $korisnicko_ime; ?></td>
     </tr>
     <tr>
      <th>Spol:</th>
      <td><?php if ($spol == 1) {
			echo "Muško";
		}else if($spol == 2){
			echo "Žensko";
		} else {
			echo "";
		} ?>
		</td>
     </tr>
     <tr>
      <th>Grad:</th>
      <td><?php echo $grad; ?></td>
     </tr>
     <tr>
      <th>Zanimanje:</th>
      <td><?php echo $zanimanje; ?></td>
     </tr>
     <tr>
      <th>O Meni:</th>
      <td><?php echo $o_meni; ?></td>
     </tr>
     <tr>
      <th>Slika:</th>
      <td><?php 
      		if ($slika == "") {
				echo "<img src='$prazna_slika' width='100px' height='100px'>";
			}else{
				echo "<img src='$slika' width='100px' height='100px'>";
			}
		?>
		</td>
     </tr>

  </tbody>
</table>
<div><a href="create_profile.php"><button type="button" class="btn btn-primary btn-block">Uredi Profil</button></a></div>
</div>
</div>
<?php
	}
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {
	if(isset($_GET['pogledaj']) && $_GET['pogledaj']==1){
		
		echo korisnik($korisnik);
		
	}else{
		
		echo komentator($ime);
		
}
}

include('includes/footer.php');

?>