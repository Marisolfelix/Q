<?php

  session_start();
  //Se convierte el archivo json recibido y se guarda en una variable
  $datos = json_decode(file_get_contents('php://input'));

if(isset($_SESSION['token'])){
  if($datos->token === $_SESSION['token']){
    $ses = $_SESSION['token'];
    $respuesta = array('auth' => true, 'sesion' => $ses);
    echo json_encode($respuesta);
  }else{
    $respuesta = array('auth' => false, 'sesion' => 'error');
    echo json_encode($respuesta);
  }
}else{
    $respuesta = array('auth' => false, 'sesion' => 'error');
    echo json_encode($respuesta);
}


?>