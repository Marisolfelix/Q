<?php
session_start();
include '../conexion.php';

$numero_empleado = $_POST['numero_empleado'];
$password = $_POST['password'];
try {
  //code...
  $sentencia = " select * from usuarios where numero_empleado = '$numero_empleado' ";
  $resultado = mysqli_query($conexion, $sentencia);
  if ($resultado ) {
    $filas = mysqli_num_rows($resultado);
    if($filas){
      $usuario = [];
                    while ($data = mysqli_fetch_array($resultado)) {
                      $usuario['password'] = $data['password'];
                      $usuario['rol'] = $data['rol'];
                      $usuario['nombre'] = $data['nombre'];
                      $usuario['numero_empleado'] = $data['numero_empleado'];
                    }

          if($numero_empleado === $usuario['numero_empleado'] && $password === $usuario['password']){
            $token = md5(uniqid(rand(),true));
            $respuesta = array('mensaje' => $usuario['rol'],'autenticacion' => '1' ,'token' => $token );
            $_SESSION['token'] = $respuesta['token'];
            setcookie('token', $respuesta['token'],time()+(60*60*24*31),"/");
            echo json_encode($respuesta);
          }

          if($password != $usuario['password']){
            $respuesta = array('mensaje' => 'contraseña incorrecta', 'autenticacion' => '0' );
            echo json_encode($respuesta);
          }
    }else {
    # code...
      $respuesta = array('error' => true,'mensaje'=> 'numero de empleado incorrecto' );
      echo json_encode($respuesta);
    }
              
  }
} catch (\Throwable $th) {
  //throw $th;
  $mensaje = "Ha ocurrido un error:". mysqli_error($conexion);
  $respuesta = array('error' => true,'mensaje'=> $mensaje );
  echo json_encode($respuesta);
}

?>