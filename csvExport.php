<?php
header('Content-Encoding: windows-1250');
header('Content-type: text/csv; windows-1250');

include('functions.php');
$data =  "Tip posiljke;;Ime in Priimek;;Naslov;Poštna številka;Mesto;Država;Telefon;Email;;Opomba;;Plačilo;Znesek;;;;;Sklic ;;;;" . PHP_EOL;


if (isset($_POST['narocilo'])){
	foreach ($_POST['narocilo'] as $id){
		$sql = "SELECT * FROM Orders WHERE OrderID = $id";
		$result = mysqli_query($conn,$sql);
	
		while($row = mysqli_fetch_array($result)){
			if($row['PaymentMethod'] == "Plačilo po povzetju"){
				$placilo = "ODKBN";
				$znesek = str_replace('.', ',', $row['Total']);
			}else{
				$placilo = "";
				$znesek = "";
			}

			if($row['Country'] == "SI"){
			$tipPosiljke = "109";
			}else{
			$tipPosiljke = "302";
			}
			
			$drzava = drzave($row['Country']);
			
			$data .= $tipPosiljke . ";;" . $row['Name'] . " " . $row['Surname'] . ";" . $row['Company'] . ";" . $row['Address'] . ";" . $row['PostCode'] . ";" . $row['City'] . ";" . $drzava  . ";" . $row['Phone'] . ";" . $row['Email'] . ";;"  . $row['Address2'] .   ";;" . $placilo . ";" . $znesek . ";;;;;"  . $row['InvoiceID'] . ";;;;"  . PHP_EOL ;
		}
	}
}
$data = iconv("UTF-8", "Windows-1250", $data);
$myfile = fopen("posta.csv", "w") or die("Unable to open file!");
fwrite($myfile, $data);
fclose($myfile);


echo $data; exit();

?>









