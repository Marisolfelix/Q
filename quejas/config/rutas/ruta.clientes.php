<?php


    include '../conexion.php';
  
    switch ($_SERVER['REQUEST_METHOD']) {

      case 'POST'://se ejecuta cuando llega informacion por el metodo post


          try {
            //code...
              $numero_de_cliente = $_POST["numero_cliente"];
              $sentencia = "SELECT * FROM clientes WHERE numero_de_cliente = '$numero_de_cliente'";
              $resultado= mysqli_query($conexion,$sentencia);

              if($resultado){
                $cliente = [];
                  while ($data = mysqli_fetch_array($resultado)) {
                    # code...

                      $cliente['numero_de_cliente'] = $data['numero_de_cliente'];
                      $cliente['nombre'] =$data['nombre'] ;
                      $cliente['telefono'] =$data['telefono'] ;
                      $cliente['correo'] =$data['correo'];
                      $cliente['domicilio'] = $data['domicilio'];

                  }
                  if($cliente['numero_de_cliente'] == $numero_de_cliente){
                    $respuesta = array('error'=>false,'ruta'=>"registro-de-quejas.html?id=$numero_de_cliente");
                    echo json_encode($respuesta);
                  }

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
          try {
            //code...
              $numero_de_cliente = $_GET['id'];
              $sentencia = "SELECT * FROM clientes WHERE numero_de_cliente = '$numero_de_cliente'";
              $resultado= mysqli_query($conexion,$sentencia);

              if($resultado){
                $cliente = [];
                  while ($data = mysqli_fetch_array($resultado)) {
                    # code...

                      $cliente['numero_de_cliente'] = $data['numero_de_cliente'];
                      $cliente['nombre'] =$data['nombre'] ;
                      $cliente['telefono'] =$data['telefono'] ;
                      $cliente['correo'] =$data['correo'];
                      $cliente['domicilio'] = $data['domicilio'];

                  }
                  echo json_encode($cliente);
              }
          } catch (\Throwable $th) {
            //throw $th;
            $mensaje = "Ha ocurrido un error:". mysqli_error($conexion);
            $respuesta = array('error' => true,'mensaje'=> $mensaje );
            echo json_encode($respuesta);
          }
         

        }else{
          
        }
        
        break;
      case 'DELETE':
        if(isset($_GET['id'])){
          
        }
        break;
      case 'PUT':
        
        break;
      default:
        # code...
        $respuesta = array('mensaje' => 'metodo no valido' );
        echo json_encode($respuesta);
        break;
    }
   mysqli_close($conexion);


   

     
?>