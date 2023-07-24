<?php


    include '../conexion.php';
  
    switch ($_SERVER['REQUEST_METHOD']) {

      case 'GET':
        # code...
        if(isset($_GET['id'])){
 

        }else{
           try {
            $sentencia = "SELECT * FROM facturas ORDER BY descripcion";
            $resultado = mysqli_query($conexion,$sentencia);

            if ($resultado ) {
              $datos_arr = [];
              while ($data = mysqli_fetch_array($resultado)) {
                # code...
                $factura = array(
                  'numero_de_factura' =>$data['numero_de_factura'] ,
                  'numero_de_cliente' =>$data['numero_de_cliente'] ,
                  'fecha' =>$data['fecha'],
                  'descripcion' =>$data['descripcion'],
                  'tienda' =>$data['tienda'],
                  'tipo_de_movimiento' =>$data['tipo_de_movimiento'],
                  'cargo' =>$data['cargo'],
                  'abono' =>$data['abono'],
                  'saldo' =>$data['saldo'],
                );
                $datos_arr[]=$factura;
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