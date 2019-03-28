<?php 


/**********************helper functions******************************************/

//Con esta función la cadena se convierte en entidad HTML
function limpiar($cadena){

   return htmlentities($cadena);  
}

//Esta función dirige a la ubicación seleccionada
function direccionar($ubicacion){

   return header("Location: {$ubicacion}");

}

//Coloca un mensaje en la variable Super global $_SESSION
function colocar_mensaje($mensaje){
   
     if(!empty($mensaje)){

     	$_SESSION['mensaje'] = $mensaje;
     }else{
     
         $mensaje = '';

     }

}

//Muestra dicho mensaje
function mostrar_mensaje(){

	 if(isset($_SESSION['mensaje'])){

	 	echo $_SESSION['mensaje'];
	 	unset($_SESSION['mensaje']);
	 }
}

//Token generado para hacer más seguras las contraseñas y validaciones
function generar_token(){
    
      $token = $_SESSION['token'] = md5(uniqid(mt_rand(), true));

      return $token;

}

//Muestra un msj de error, DELIMETER es otra forma de escribir un mensaje en php
function validar_errores($mensaje_error){
	$mensaje_error = <<<DELIMETER
      	 		 '<div class="alert alert-danger alert-dismissible" role="alert">
      	 		     <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                      <strong>Warning!</strong> $mensaje_error 
                      </button>
                      </div>
DELIMETER;
                      return $mensaje_error;
}

//Verifica si el email existe en la base de datos
function email_existe($email){
	$sql = "SELECT id FROM usuarios WHERE email = '$email'";

	$resultado = query($sql);

	if(contar_filas($resultado) == 1){

		return true;
	} else{
		return false;
	}

}

//Verifica si el usuario existe en la base de datos
function usuario_existe($usuario){
	$sql = "SELECT id FROM usuarios WHERE usuario = '$usuario'";

	$resultado = query($sql);

	if(contar_filas($resultado) == 1){

		return true;
	} else{
		return false;
	}

}

//Envia el email para validarlo
function enviar_email($email,$sujeto, $msj, $headers){
	return mail($email,$sujeto, $msj, $headers);
	
}

/**********************Funciones de validación******************************************/
//Se lleva a cabo el registro, posteriormente envia un mail de validación si el registro fue exitoso
function validar_usuario_registracion(){
    $errores = [];
	$min = 3;
	$max = 20; 

    if($_SERVER['REQUEST_METHOD'] == "POST"){

      	 $nombre = limpiar($_POST['nombre']);
      	 $apellido = limpiar($_POST['apellido']);
      	 $usuario = limpiar($_POST['usuario']);
      	 $email = limpiar($_POST['email']);	 
      	 $clave = limpiar($_POST['clave']);
      	 $confirmar_clave = limpiar($_POST['confirmar_clave']);

      	 if (strlen($nombre) < $min) {
      	 	$errores[] = "Tu nombre no puede tener menos de {$min} caracteres";
      	 }

         if (strlen($nombre) > $max) {
      	 	$errores[] = "Tu nombre no puede tener mas de {$max} caracteres";
      	 }
      	

      	 if (strlen($apellido) < $min) {
      	 	$errores[] = "Tu apellido no puede tener menos de {$min} caracteres";
      	 }

      	 if (strlen($apellido) > $max) {
      	 	$errores[] = "Tu apellido no puede tener mas de {$max} caracteres";
      	 }

      	 if (strlen($usuario) < $min) {
      	 	$errores[] = "Tu usuario no puede tener menos de {$min} caracteres";
      	 }

         if (strlen($usuario) > $max) {
      	 	$errores[] = "Tu usuario no puede tener mas de {$max} caracteres";
      	 }

      	 if(usuario_existe($usuario)){
      	 	$errores[] = "Lo siento, ese usuario ya esta registrado";
      	 }


      	 if(email_existe($email)){
      	 	$errores[] = "Lo siento, ese email ya esta registrado";
      	 }

      	 if (strlen($email) > $max) {
      	 	$errores[] = "Tu email no puede tener mas de {$max} caracteres";
      	 }

      	 if ($clave !== $confirmar_clave) {
      	 	$errores[] = "Tus contraseñas no coinciden";
      	 }

      	 if(!empty($errores)){
      	 	foreach ($errores as $error){
      	 		echo validar_errores($error);
      	 	}
            
      	 	
      	 } else {

      	 	if (registrar_usuario($nombre, $apellido, $usuario, $email, $clave)){

      	 		colocar_mensaje("<p class='bg-success text-center'>Por favor chequea tu email o carpeta spam por el link de activacion</p>");
      	 		direccionar("index.php");
      	 		
      	    }
      	    else{
      	    	colocar_mensaje("<p class='bg-danger text-center'>Disculpa, no hemos podido registrar tu usuario</p>");
      	 		direccionar("index.php"); 
      	    }

        } 


    } 
}

/**********************Funciones de registro de usuario******************************************/
//Registra el usuario en la base de datos si el registro fue exitoso
function registrar_usuario($nombre, $apellido, $usuario, $email, $clave){

	$nombre = escapar($nombre);
	$apellido = escapar($apellido);
	$usuario = escapar($usuario);
	$email = escapar($email);
	$clave = escapar($clave);

    if(email_existe($email)){

      	return false;
    }else if (usuario_existe($usuario)){

        return false;
    } else{

    	$clave = md5($clave);

    	$validacion = MD5($usuario . microtime()); //en este caso para concatenar en vez de un "." tb se puede usar "+"

        $sql = "INSERT INTO usuarios(nombre, apellido, usuario, email, clave, validacion, activar) 
        VALUES('$nombre','$apellido', '$usuario', '$email', '$clave','$validacion', 0)";
        
        $resultado = query($sql);
       

        $sujeto = "Activar cuenta";

        $msj = "Por favor haz click en el link para activar tu cuenta
        http://localhost/login/activate.php?email=$email&codigo=$validacion
        ";

        $headers= "From: noreply@yourwebsite.com";

        enviar_email($email,$sujeto, $msj, $headers);

        return true;

    } 


}

/**********************Funciones de activacion de usuario******************************************/
//Activa al usuario una vez que el codigo de validación es ingresado
function activar_usuario(){

	if($_SERVER['REQUEST_METHOD'] == "GET"){

		if(isset($_GET['email'])){

			echo $email = limpiar($_GET['email']);

			echo $validacion = limpiar($_GET['codigo']);

			$sql = "SELECT id FROM usuarios WHERE email = '".escapar($_GET['email'])."' AND validacion = '".escapar($_GET['codigo'])."' ";
			$result = query($sql);
			
            
            if (contar_filas($result) == 1){
            	$sql2 = "UPDATE usuarios SET activar = 1, validacion = 0 WHERE email = '".escapar($email)."' AND validacion = '".escapar($validacion)."'";
            	$result2 = query($sql2);
			   
			    colocar_mensaje("<p class='bg-success'>Tu cuenta ha sido activada</p>");
			    direccionar("login.php");
		    }
		    else{
		    	colocar_mensaje("<p class='bg-danger'>Tu cuenta no ha podido ser activada</p>");
			    direccionar("login.php");
		    }
		} //fin de if(isset)
	} //fin de if(server)
} //fin de la funcion

/**********************Funciones de validación de login******************************************/
//Se lleva a cabo la validación del login para que el ingreso se realice sin errores
function validar_usuario_login(){
    $errores = [];
	$min = 3;
	$max = 20; 

    if($_SERVER['REQUEST_METHOD'] == "POST"){
         
       $email = limpiar($_POST['email']);	 
       $clave = limpiar($_POST['clave']);
       $recordar = isset($_POST['recordar']);

       if(empty($email)){

       	  $errores[] = "El campo email no puede estar vacio";
       }

       if(empty($clave)){

       	  $errores[] = "El campo clave no puede estar vacio";
       }

       if(!empty($errores)){
      	 	foreach ($errores as $error){
      	 		echo validar_errores($error);
      	 	}
         	
      	 } else {

      	 	if(login_usuario($email, $clave, $recordar)){

      	 		direccionar("admin.php");
      	 	} else {
      	 		echo validar_errores("Tus credenciales no son correctas");
      	 	}

      	 }
    }
}    

/**********************Funciones de login******************************************/
//Se comprueba si el ingreso coincide con los datos de la base de datos
function login_usuario($email, $clave, $recordar){
	$sql = "SELECT clave, id FROM usuarios WHERE email = '".escapar($email)."' AND activar = 1";

	$resultado = query($sql);
	if(contar_filas($resultado) == 1){
		$fila = traer_array($resultado);

		$bd_clave = $fila['clave']; 

		if(md5($clave) === $bd_clave){ 
            if($recordar == "on"){

            	setcookie('email', $email, time() + 86400); //esta sesion será "recordada" por 86400 segundos (1 día)
            }

			$_SESSION['email'] = $email;
			return true;
		} else {
			return false;
		}
        
        return true;

	} else {

		return false;
	}

} //Fin de la funcion

/**********************Función de ingreso******************************************/
//Mantiene la sesión de acuerdo a si se colocó "recordarme" o no
function logeado(){

	if (isset($_SESSION['email']) || isset($_COOKIE['email'])){

		return true;
	} else{

		return false;
	}
}

/**********************Función recuperar password******************************************/
//Se actualiza la validacion de la base de datos ya que un nuevo password será ingresado
function recuperar_password(){

	if($_SERVER['REQUEST_METHOD'] == "POST"){
        if (isset($_SESSION['token']) && $_POST['token'] === $_SESSION['token']){
             $email = limpiar($_POST['email']);
             if (email_existe($email)) {

                 $validacion = md5($email . microtime()); //en este caso para concatenar en vez de un "." tb se puede usar "+"

                 setcookie('temp_access_code', $validacion, time()+600);

                 $sql = "UPDATE usuarios SET validacion = '".escapar($validacion)."' WHERE email = '".escapar($email)."'";
                 $resultado = query($sql);
                 

                 $sujeto = "Por favor resetea tu contraseña";
                 $mensaje = "Aqui está tu codigo para resetear la contraseña: {$validacion}

                   Click aqui para resetear tu contraseña http://localhost/codigo.php?email=$email&codigo=$validacion

                 ";

                 $header = "From: noreply@yourwebsite.com";

               
                 if (!enviar_email($email, $sujeto, $mensaje, $header)){
                           echo validar_errores("El email no pudo ser enviado");
                     
                 }

                 colocar_mensaje("<p class='bg-success text-center'>Por favor chequea tu email o carpeta de spam para el codigo de contraseña</p>");
                 direccionar("index.php");
             } else{

                echo validar_errores("Este email no existe");
             }
 
        } else {

             direccionar("index.php");
        }
       //Boton cancelar
		  if (isset($_POST['cancel_submit'])){
       
          direccionar("login.php");
      }
	}
}

/**********************Codigo de Validacion******************************************/
//Se verifica el código de validación
function validar_codigo(){

   if(isset($_COOKIE['temp_access_code'])){

 
         if(!isset($_GET['email']) && !isset($_GET['codigo'])){

             direccionar("index.php");


         } else if (empty($_GET['email']) || empty($_GET['codigo'])){
 
             direccionar("index.php");
         } else{
                 if(isset($_POST['codigo'])){

                    $email = limpiar($_GET['email']); 
                    $validacion = limpiar($_POST['codigo']);

                    $sql = "SELECT id FROM usuarios WHERE validacion = '".escapar($validacion)."' AND email = '".escapar($email)."'";
                    $resultado = query($sql);
                    
                    if(contar_filas($resultado) == 1){
                       setcookie('temp_access_code', $validacion, time() + 900);
                      direccionar("reset.php?email=$email&codigo=$validacion");
                    } else{
                      echo "Lo siento, la validacion no es correcta";
                    }
                 }
               }


   } else{
        colocar_mensaje("<p class='bg-danger text-center'>Lo siento, tu validacion ha expirado</p>");

        direccionar("recuperar.php");
   }
}


/**********************Función Password Reset******************************************/
//Se modifica el password en la base de datos
function password_reset(){
     if(isset($_COOKIE['temp_access_code'])){

       if(isset($_GET['email']) && isset($_GET['codigo'])){

          if (isset($_SESSION['token']) && isset($_POST['token'])) {

            if ($_POST['token'] === $_SESSION['token']){
                
                if ($_POST['password'] == $_POST['confirmar_password']){

                  $actualizar_password = md5($_POST['password']);

                  $sql = "UPDATE usuarios SET clave = '".escapar($actualizar_password)."', validacion = 0 WHERE email = '".escapar($_GET['email'])."'";

                  query($sql);

                  colocar_mensaje("<p class='bg-success text-center'>Tu contraseña ha sido actualizada</p>");

                  direccionar("login.php");
                  } else{

                    echo validar_errores("Las claves no coinciden");
                  } 

            }
        
            
          }
       } 
    } else{

         colocar_mensaje("<p class='bg-danger text-center'>Lo siento, tu validacion ha expirado</p>");
         direccionar("recuperar.php");
     }
}
?>