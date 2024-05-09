
<?php
    require_once 'escaneos.php';
    require_once 'perfil.php';
    require_once 'conexion.php';

    function main(){

        $nvrIp = "192.168.100.129";

        $puertosTotales = [
            "A1-" => "14002", 
            "A2-" => "14002",
            "B1-" => "14003",
            "B2-" => "14003",
            "C1-" => "14004",
            "C2-" => "14004",
            "D1-" => "14005",
            "D2-" => "14005",
            "E1-" => "14006",
            "E2-" => "14006",
            "F1-" => "14007",
            "F2-" => "14007",
            "G1-" => "14008",
            "G2-" => "14008",
            "H1-" => "14009",
            "H2-" => "14009",
            "I1-" => "14010",
            "I2-" => "14010",
                            ]; 

            


            //Se iniciza el objeto escaneos con el escaneo actual ( )
            date_default_timezone_set('America/Mexico_City');

            $hora_actual = new DateTime();
            $horaYFecha = $hora_actual->format('Y-m-d H:i:s');
            $puertoActual = 0;

            $fRecibos = false;
            $fpick = false;
            $ftrans = true;
            $contadorRecibos = 0;
            $contadorPicking = 0;

             
            // Expresión regular para validar si el escaneo sera recibos
            $preAdvice = "/^RE\d{6}$/";
            // Expresión regular para validar si el escaneo sera en picking
            $taskID = "/^OR\d{6}$/";
            $ubicacionConPuerto = "14001";

        while (true){
            
            echo "En espera de escaneo... \n";
  
            
            
            
                $SNActual = "";
                $ubcacionActual = "";
                $fSN = false;
                $fUbicacion = false;
                while(true){
                    
                
                    

                echo "Empieza el escaneo en picking / relocate";

                $esacneo = readline();
                $esacneo = strtoupper($esacneo);
                $ubicacionCorta = substr($esacneo, 0, 3);


                //validamos el cambio a recibos
               if(preg_match($preAdvice, $esacneo)) {
                $fRecibos = true;
                $fpick = false;
                limpiarConsola();
                break;

                }

                if (strpos($esacneo, 'SN') !== false || strpos($esacneo, 'SNS') !== false || strpos($esacneo, 'SNSS') !== false) {
                    $SNActual = $esacneo;
                    $fSN = true;
                } 
                
                if (array_key_exists($ubicacionCorta, $puertosTotales) ) {
                    $ubicacionConPuerto = $puertosTotales[$ubicacionCorta];
                    $ubcacionActual = $esacneo;
                    $fUbicacion = true;
                }

                if ($fSN == true && $fUbicacion == true){
                    //MANDAMOS IMPRIMIR AL NVR
                    echo "impresion al NVR en proceso de picking \n";
                    
            //Información de la conexión
            $tiempo = 1;

            //Se establece una conexión al NVR mediante TCP
            $actualConexion = fsockopen(Conexion::$nvrIp, $ubicacionConPuerto, $errno, $errstr, $tiempo);

            if (!$actualConexion) {
                //Si no hay conexión, imprime el error.
                echo "$errstr ($errno)<br />\n";
            }else{

                date_default_timezone_set('America/Mexico_City');
                $hora_actual = new DateTime();
                $horaYFecha = $hora_actual->format('Y-m-d H:i:s');
                
                //echo 'Hora actual en México: ' . $hora_actual->format('Y-m-d H:i:s') . "\n";
                //Se concatenan los datos a imprimir.        \r\n = ENTER

                //Se concatenan los datos a imprimir.        \r\n = ENTER
                $datos = "";
                $datos .= "Fecha: $horaYFecha \r\n";
                $datos .= "Usuario: ------ \r\n";
                $datos .= "Codigo: $SNActual \r\n";
                $datos .= "Ubicacion: $ubcacionActual \r\n";
                $datos .= "---------------------------------------\r\n";

                echo "Codigo enviado: ";
            }

            fwrite($actualConexion, $datos);

            //Se cierra la conexión TCP con el grabador
            fclose($actualConexion);


                    
                    

                    $ubcacionActual = "";
                    $SNActual = "";

                    $fSN = false;
                    $fUbicacion = false;
                }

               
            }


            
            
            if ($fRecibos){
                $puertoActual = "14001"; //se cambia mi puerto para envio de datos a fisheye recibos 

                while(true){
                    
                    echo "Empieza el escaneo en recibos";
                    $esacneoRecibos = readline();
                    $esacneoRecibos = strtoupper($esacneoRecibos);
                    $ubicacionCorta = substr($esacneoRecibos, 0, 3);

                    //validamos el cambio a picking / relocate
                    if(preg_match($taskID, $esacneoRecibos)) {
                        $fpick = true;
                        $fRecibos = false;
                        limpiarConsola();
                        break;
                    }

                    //validamos el cambio a transferencia / picking 
                    if (array_key_exists($ubicacionCorta, $puertosTotales) && ($contadorRecibos > 3)) {
                        echo "Cambio a relocate / picking ";
                        $fpick = true;
                        $fRecibos = false;
                        limpiarConsola();
                        break;
                    }
                    
                    if (strpos($esacneoRecibos, 'SN') !== false || strpos($esacneoRecibos, 'SNS') !== false || strpos($esacneoRecibos, 'SNSS') !== false) {
                      //MANDAMOS IMPRIMIR AL NVR
                      echo "impresion al NVR en proceso de recibos ";


                      
            //Información de la conexión
            $tiempo = 1;

            //Se establece una conexión al NVR mediante TCP
            $actualConexion = fsockopen(Conexion::$nvrIp, "14005", $errno, $errstr, $tiempo);

            if (!$actualConexion) {
                //Si no hay conexión, imprime el error.
                echo "$errstr ($errno)<br />\n";
            }else{

                date_default_timezone_set('America/Mexico_City');
                $hora_actual = new DateTime();
                $horaYFecha = $hora_actual->format('Y-m-d H:i:s');
                
                //echo 'Hora actual en México: ' . $hora_actual->format('Y-m-d H:i:s') . "\n";
                //Se concatenan los datos a imprimir.        \r\n = ENTER

                //Se concatenan los datos a imprimir.        \r\n = ENTER
                $datos = "";
                $datos .= "Fecha: $horaYFecha \r\n";
                $datos .= "Usuario: ------ \r\n";
                $datos .= "Codigo: $esacneoRecibos \r\n";
                $datos .= "Ubicacion: RECEIVE01 \r\n";
                $datos .= "---------------------------------------\r\n";

                echo "Codigo enviado: ";
            }

            fwrite($actualConexion, $datos);

            //Se cierra la conexión TCP con el grabador
            fclose($actualConexion);


                    


                      $contadorRecibos++;
                    }
                    


                }
                $contadorRecibos=0;

                

            }

            
            
            
            /*if ($escaneo->elEscaneoEsUnPerfil()){
                $perfil->setUsuario($entradaTexto);
                echo "Usuario nuevo:" . $entradaTexto. "\n";
            }elseif
            */

            /*
            if
            ($escaneo->elEscaneoEsUnMu()){
                $conexion->setMu($entradaTexto);
                echo "MU nuevo:" . $entradaTexto. "\n";
            }

            
            elseif ($escaneo->elEscaneoEsUnaUbicacion()){
                $conexion->setUbicacion($entradaTexto);
                $puertoActual = $escaneo->obtenerPuertoDeSalida();
                echo "Ubicacion nuevo:" . $entradaTexto. "\n";
                echo "Puerto nuevo:" . $puertoActual. "\n";
            }

            
            
            if ($conexion->validarEnvioDeDatos()){    
                $conexion->envioDeDatosANVR($perfil->getUsuario(), $horaYFecha, $puertoActual);
                $conexion->setMu("");
                $conexion->setUbicacion("");
                limpiarConsola();
            }

                */
        }
    
        

    }

    function limpiarConsola(){
        echo chr(27).chr(91).'H'.chr(27).chr(91).'J';   //^[H^[J
    }

   



    //crearObjetos();
    main();

    
     //Información del grabador
    /* $nvr_ip = "192.168.100.129";
     $nvr_puerto = "14002";
     $tiempo = 1;
 
     //Información d
     //Se establece una conexión al NVR mediante TCP
     $conexion = fsockopen($nvr_ip, $nvr_puerto, $errno, $errstr, $tiempo);
 
     fwrite($conexion, "Envio al servidor 1");
     //Se cierra la conexión TCP con el grabador
     fclose($conexion);*/



?>