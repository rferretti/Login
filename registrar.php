<?php include("includes/header.php") ?>
<?php include("includes/nav.php") ?>


	<div class="row">
		<div class="col-lg-6 col-lg-offset-3">
           <?php validar_usuario_registracion(); ?>
							 	
		</div>



	</div>
    	<div class="row">
			<div class="col-md-6 col-md-offset-3">
				<div class="panel panel-login">
					<div class="panel-heading">
						<div class="row">
							<div class="col-xs-6">
								<a href="login.php">Login</a>
							</div>
							<div class="col-xs-6">
								<a href="registrar.php" class="active" id="">Registrarse</a>
							</div>
						</div>
						<hr>
					</div>
					<div class="panel-body">
						<div class="row">
							<div class="col-lg-12">
								<form id="register-form" method="post" role="form" >

									<div class="form-group">
										<div class="form-group">
										<input type="text" name="nombre" id="nombre" tabindex="1" class="form-control" placeholder="Nombre" value="" required >
									</div>
									<div class="form-group">
										<input type="text" name="apellido" id="apellido" tabindex="1" class="form-control" placeholder="Apellido" value="" required >
									</div>
										<input type="text" name="usuario" id="usuario" tabindex="1" class="form-control" placeholder="Usuario" value="" required >
									</div>
									<div class="form-group">
										<input type="email" name="email" id="email" tabindex="1" class="form-control" placeholder="Email" value="" required >
									</div>
									<div class="form-group">
										<input type="password" name="clave" id="clave" tabindex="2" class="form-control" placeholder="Clave" required>
									</div>
									<div class="form-group">
										<input type="password" name="confirmar_clave" id="confirmar-clave" tabindex="2" class="form-control" placeholder="Confirmar Clave" required>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-6 col-sm-offset-3">
												<input type="submit" name="register-submit" id="register-submit" tabindex="4" class="form-control btn btn-register" value="Registrarse Ahora">
											</div>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	
<?php include("includes/footer.php") ?>
