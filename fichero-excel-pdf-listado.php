<?php
//inicializar variables a utilizar en el documento html
// reference the Dompdf namespace
require '../../../vendor/autoload.php';
require 'funciones.php';
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$nombre_fichero = 'personas.json';
//comprobar si nos llega el formulario
if (isset($_POST['enviar'])) {
	$valorBoton = $_POST['enviar'];
	if ($valorBoton=="Guardar datos") {
	    //recuperar los datos del formulario
		$nombre=$_POST['nombre'];
		$apellido1=$_POST['apellido1'];
		$apellido2=$_POST['apellido2'];
		$ciudad=$_POST['ciudad'];
		$idioma=$_POST['idioma'];
		//Leemos el fichero de personas
		if (file_exists($nombre_fichero)) {
			$personas = json_decode(file_get_contents($nombre_fichero), true);
		}
		$persona_temp = array (
			'Nombre'=>$nombre,
			'Apellido1'=>$apellido1, 
			'Apellido2'=>$apellido2, 		
			'ciudad' =>$ciudad,
			'idioma' =>$idioma
		);
		$personas[]=$persona_temp;	
		//Guardamos el fichero de personas
		file_put_contents($nombre_fichero,json_encode($personas));
		//echo 'Después de llenar el array';
		//print_r($personas); 
		//echo '</pre>';
		}
	elseif ($valorBoton=="Generar listado pdf") {
		$lista = recuperarListado($nombre_fichero);
		// instantiate and use the dompdf class
		$dompdf = new Dompdf();
		$dompdf->getOptions()->setChroot('/path/to/common/assets-directory');
		$dompdf->loadHtml($lista);
		// (Optional) Setup the paper size and orientation
		$dompdf->setPaper('A4', 'landscape');
		// Render the HTML as PDF
		$dompdf->render();
		//Limpiamos el buffer de salida al Browser
		ob_end_clean();
		// Output the generated PDF to Browser
		$dompdf->stream();
	}
	elseif ($valorBoton=="Generar Excel") {
		$spreadsheet = new Spreadsheet();
		$sheet = $spreadsheet->getActiveSheet();
		//Leemos el fichero de personas
		$personas = json_decode(file_get_contents('personas.json'), true);
		foreach ($personas  as $persona) 
    		{
				$i++;
				$sheet->setCellValue('A'.$i, $persona['Nombre']);
				$sheet->setCellValue('B'.$i, $persona['Apellido1']);
				$sheet->setCellValue('C'.$i, $persona['Apellido2']);	
			}
		$writer = new Xlsx($spreadsheet);
		$writer->save('listado.xlsx');
		header('Location: listado.xlsx');
	}
}
?>
<html>
	<head>
		<title>Actividad 2</title>
		<meta charset="utf-8">
		<style>
			td,th {width: 150px;}
		</style>
	</head>
	<body>
		<form action="#" method="post">
			Nombre: <input type="text" name="nombre" value="<?=$nombre?>"><br>
			Apellido1: <input type="text" name="apellido1" value="<?=$apellido1?>"><br>
			Apellido2: <input type="text" name="apellido2"value="<?=$apellido2?>"><br>
			Ciudad: <input type="text" name="ciudad" value="<?=$ciudad?>"><br>
			Idioma: <input type="checkbox" name="idioma[]" value="ESP"> Castellano 
			<input type="checkbox" name="idioma[]" value="CAT"> Catalan
			<input type="checkbox" name="idioma[]" value="ENG"> Inglés<BR>
			<input type="submit" value="Guardar datos" name="enviar">
			<input type="submit" value="Generar listado pdf" name="enviar">
			<input type="submit" value="Generar Excel" name="enviar">
			<input type="text" disabled value='<?=$mensa?>'>
		</form>
	<?php
		$lista = recuperarListado($nombre_fichero);
		echo $lista;			
	?>
	</body>
</html>