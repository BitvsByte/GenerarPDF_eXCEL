<?php
function recuperarListado($nombreFichero) {
	if (file_exists($nombreFichero)) {
			$personas = json_decode(file_get_contents($nombreFichero), true);		
			$listado = '<h1>Listado de personas</h1>';
			$listado .= '<table>';
			$listado .= '<tr><th>Nombre</th><th>Primer apellido</th><th>Segundo apellido</th><th>Ciudad</th><th>Idioma</th></tr>';
			foreach ($personas as $per) {
				$listado .= '<tr>';
    			$listado .= '<td style="color:red">'.$per['Nombre'].'</td>';
				$listado .= '<td>'.$per['Apellido1'].'</td>';
				$listado .= '<td>'.$per['Apellido2'].'</td>';
				$listado .= '<td>'.$per['ciudad'].'</td>';	
				$listado .= '<td>';
				 if (isset($per['idioma'])) { 
					foreach ($per['idioma'] as $idi) {
						$listado .= $idi.' ';
					}
				 }	 
				$listado .= '</td>';	
				$listado .= '</tr>';
			}
			$listado .= '</table>';
	} else {
		$listado = "No hay datos";
	}
	return $listado;

}
?>