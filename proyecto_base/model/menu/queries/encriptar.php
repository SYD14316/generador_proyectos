<?php
	//Se revisa si la sesión esta iniciada y sino se inicia
  if (session_status() === PHP_SESSION_NONE) {session_start();}
  //Se manda a llamar el archivo de configuración
  include_once $_SERVER['DOCUMENT_ROOT'].'/'.$_SESSION['ubi'].'/lib/config.php';
  //Muestro los errores
	error_reporting(E_ALL);
  ini_set("display_errors", 1);	
?>
<div class="card">
	<div class="card-body">
		<table class="table table-bodered table-striped table-sm table-hover" id="tabla_exe">
			<thead>
				<tr>
					<th>CADENA ENCRIPTADA</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<?php echo campo_limpiado($_POST['cadena'],1,0,0); ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>