<?php

$con = mysqli_connect('localhost','root','','login_base');


function contar_filas($resultado){
   
   return mysqli_num_rows($resultado); //devuelve el nro de filas 

}

function escapar($cadena){
     global $con;
     return mysqli_real_escape_string($con, $cadena); //Escapa los caracteres especiales de una cadena para usarla en una sentencia SQL

}



function query($query){

    global $con;
    
    $resultado = mysqli_query($con, $query);
    confirmar($resultado);
    return $resultado;
    

}

function confirmar($resultado){
    global $con;
    
    if (!$resultado) {
    	die("QUERY FAILED" . mysqli_error($con));
    }

}

function traer_array($resultado){
    global $con;

    return mysqli_fetch_array($resultado); //Recorre array

}


?>