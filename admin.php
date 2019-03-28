<!DOCTYPE html>
<?php include("includes/header.php") ?>

  
<?php include("includes/nav.php")?> 


	<div class="jumbotron">
		<h1 class="text-center"><?php 
		if(logeado()){
                 echo "Logeado";
		} else{
			redirect("index.php");
		}


		?></h1>
	</div>





<?php include("includes/footer.php") ?>