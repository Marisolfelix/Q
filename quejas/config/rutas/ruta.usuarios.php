<?php


      include '../conexion.php';
  
    switch ($_SERVER['REQUEST_METHOD']) {

      case 'POST'://se ejecuta cuando llega informacion por el metodo post

        // se valida que todos los campos tengan conteido
        if(empty($_POST["nombre"]) || empty($_POST["numEmpleado"]) || empty($_POST["password"]) || empty($_POST["rol"])){
          $respuesta = array('error' =>true , 'mensaje' => 'Uno o mas campos vienen vacios' );
            echo json_encode($respuesta);
            exit();
        }

        // SE ASIGNAN A UNAS VARIABLES LOS VALORES CORRSPONDIENTES QUE LLEGAN POR EL METODO "POST"
        $nombre = $_POST["nombre"];
        $numEmpleado =$_POST["numEmpleado"];
        $password =$_POST["password"];
        $rol = $_POST["rol"];

        // VALIDACION DE DATOS
        // se valida que el nombre solo contenga letras y espacios
        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $nombre)) {
          $respuesta = array ( 'error' => true , 'mensaje' => 'El nombre solo puede contener letras y espacios' );
            echo json_encode($respuesta);
            exit();
        }
        // se valida que el numero de empleado solo contenga numeros
        if (!preg_match('/^[0-9]+$/ ', $numEmpleado )) {
          $respuesta = array ( 'error' => true , 'mensaje' => 'El numero de empleado solo puede contener numeros' );
            echo json_encode($respuesta );
            exit();
        }
        

        try {
  
          $sentencia = "INSERT INTO usuarios (numero_empleado, nombre, password, rol) VALUES ('$numEmpleado', '$nombre', '$password', '$rol') ";
          $resultado = mysqli_query($conexion, $sentencia) or die(mysqli_error($conexion));

          if ($resultado ) {
            $respuesta = array('error' => false , 'mensaje'=> 'Los datos se han guardado correctamente' );
            echo json_encode($respuesta);
          } 
        } catch (\Throwable $th) {
          //throw $th;
        $mensaje = "Ha ocurrido un error:". mysqli_error($conexion);
        $respuesta = array('error' => true,'mensaje'=> $mensaje );
        echo json_encode($respuesta);
        }  
        break;
      
      case 'GET':
        # code...
        if(isset($_GET['id'])){
      

        }else{
          try {
            $sentencia = "SELECT * FROM usuarios ORDER BY rol";
            $resultado = mysqli_query($conexion,$sentencia);

            if ($resultado ) {
              $datos_arr = [];
              while ($data = mysqli_fetch_array($resultado)) {
                # code...
                $usuario = array(
                  'nombre' =>$data['nombre'] ,
                  'numero_empleado' =>$data['numero_empleado'] ,
                  'rol' =>$data['rol'] 
                );
                $datos_arr[]=$usuario;
              }
              // $respuesta = array('datos'=> $datos_arr  );
              echo json_encode($datos_arr);
            } 
          } catch (\Throwable $th) {
            $mensaje = "Ha ocurrido un error:". mysqli_error($conexion);
            $respuesta = array('error' => true,'mensaje'=> $mensaje );
            echo json_encode($respuesta);
          }
        }
        
        break;
      case 'DELETE':
        if(isset($_GET['id'])){
          $numero_empleado = $_GET['id'];
          $sentencia = "DELETE FROM usuarios WHERE numero_empleado = '$numero_empleado'";
          $resultado = mysqli_query($conexion, $sentencia);

          if($resultado){
            $respuesta  = array('mensaje' =>'Se ha eliminado correctamente');
            echo json_encode($respuesta);
          }else{
            $respuesta  = array('mensaje' =>'No ha podido eliminar');
            echo json_encode($respuesta);
          }
        }
        break;
      case 'PUT':
        try {
          //Se convierte el archivo json recibido y se guarda en una variable
          $datos = json_decode(file_get_contents('php://input'));

        // se valida que el nombre solo contenga letras y espacios
        if (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', $datos->nombre)) {
          $respuesta = array ( 'error' => true , 'mensaje' => 'El nombre solo puede contener letras y espacios' );
            echo json_encode($respuesta);
            exit();
        }
        // se valida que el numero de empleado solo contenga numeros
        if (!preg_match('/^[0-9]+$/ ', $datos->numEmpleado )) {
          $respuesta = array ( 'error' => true , 'mensaje' => 'El numero de empleado solo puede contener numeros' );
            echo json_encode($respuesta );
            exit();
        }

          //se crea la sentencia sql que se enviara a la base de datos
          $sentencia = "UPDATE usuarios SET numero_empleado = '$datos->numEmpleado', nombre ='$datos->nombre', rol= '$datos->rol' WHERE numero_empleado = '$datos->id' ";
          //se envia la sentencia sql a la base de datos
          $resultado = mysqli_query($conexion, $sentencia);
          //si el resultado es correcto se enviara el mensaje afirmativo como respuesta
          if ($resultado ) {
            $respuesta = array('error' => false , 'mensaje'=> 'Los datos se han guardado correctamente' );
            echo json_encode($respuesta);
          } 
        } catch (\Throwable $th) {//se manejan los errores que se puedan tener
          //throw $th;
          if(mysqli_error($conexion)){
            $mensaje = "Ha ocurrido un error:". mysqli_error($conexion);
            $respuesta = array('error' => true,'mensaje'=> $mensaje );
            echo json_encode($respuesta);
          }
        }
        break;
      default:
        # code...
        $respuesta = array('mensaje' => 'metodo no valido' );
        echo json_encode($respuesta);
        break;
    }
   mysqli_close($conexion);


   

     
?>