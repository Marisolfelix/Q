<?php


    include '../conexion.php';

    switch ($_SERVER['REQUEST_METHOD']) {
      case 'POST'://se ejecuta cuando llega informacion por el metodo post

  
        // SE ASIGNAN A UNAS VARIABLES LOS VALORES CORRSPONDIENTES QUE LLEGAN POR EL METODO "POST"
        $folio_de_aclaracion = $_POST["numero_de_folio"];
        $numero_de_cliente =$_POST["numero_de_cliente"];
        $descripcion =$_POST["descripcion"];
        $factura = $_POST["factura"];
        $area = $_POST["area"];
        $tienda = $_POST["tienda"];
        $fecha_de_compra = $_POST["fecha_de_compra"];
        $importe = $_POST["importe"];
        $observacion = $_POST["observacion"];
        $update = $_POST["update"];
        

        try {

          if($update == 'true'){
            $sentencia = "UPDATE aclaraciones SET folio_de_aclaracion='$folio_de_aclaracion', numero_de_cliente='$numero_de_cliente', descripcion='$descripcion', factura='$factura', area='$area', tienda='$tienda', fecha_de_compra='$fecha_de_compra', importe=$importe, observacion='$observacion' where folio_de_aclaracion = '$folio_de_aclaracion' ";

            $resultado = mysqli_query($conexion, $sentencia) or die(mysqli_error($conexion));
              if ($resultado ) {
                $respuesta = array('error' => false , 'mensaje'=> "Se ha generado el numero de folio $folio_de_aclaracion para la aclaracion, se atendera durante un periodo de 15 dias." );
                echo json_encode($respuesta);
              } 
          }

          if($update == 'false'){
            $sentencia = "INSERT INTO aclaraciones (folio_de_aclaracion, numero_de_cliente, descripcion, factura, area, tienda, fecha_de_compra, importe,observacion) VALUES ('$folio_de_aclaracion', '$numero_de_cliente', '$descripcion', '$factura', '$area','$tienda', '$fecha_de_compra',$importe,'$observacion') ";
            $resultado = mysqli_query($conexion, $sentencia) or die(mysqli_error($conexion));

            if ($resultado ) {
              $respuesta = array('error' => false , 'mensaje'=> "Se ha generado el numero de folio $folio_de_aclaracion para la aclaracion, se atendera durante un periodo de 15 dias. en insert" );
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

      default:
        # code...
        $respuesta = array('mensaje' => 'metodo no valido' );
        echo json_encode($respuesta);
        break;
    }
   mysqli_close($conexion);


   

     
?>