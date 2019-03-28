<?php include("includes/header.php") ?>

	
<?php include("includes/nav.php")?>	


	<div class="jumbotron">
		<?php mostrar_mensaje(); ?>
		<h1 class="text-center"> Inicio</h1>
	</div>

<?php

  $sql = "SELECT * FROM usuarios";
  $resultado = query($sql);

  confirmar($resultado);

  $fila = traer_array($resultado);
  //echo $fila['usuario'];
?>
	
<?php include("includes/footer.php") ?>