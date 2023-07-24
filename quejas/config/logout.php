<?php
  session_start();

  setcookie('token','',time() - 3600, '/');
  setcookie(session_name(), '', time() - 3600, '/');
  session_destroy();
  $respuesta = array('mensaje' => '1','ruta' =>'login.html');
  echo json_encode(($respuesta));
?>