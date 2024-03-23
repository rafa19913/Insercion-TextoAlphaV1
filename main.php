
<?php
    require_once 'escaneos.php';
    require_once 'perfil.php';
    require_once 'conexion.php';

    function main(){
        //Se iniciza el objeto escaneos con el escaneo actual ( )
        date_default_timezone_set('America/Mexico_City');

        $escaneo = new Escaneos("TEST1234554");
        $perfil = new Perfil("USR-CEVA-000");
        $conexion = new Conexion ("","","");


        $hora_actual = new DateTime();
        $horaYFecha = $hora_actual->format('Y-m-d H:i:s');
        $puertoActual = 0;
        while (true){
            echo "En espera de escaneo... \n";
            
            //ACLARAR DUDA DE USUARIOS PENDIENTE (COMO INGRESAR)
            //FALTA EL PROCESO PARA INGRESO DE USUARIO (PERFIL)
            
            
            $entradaTexto = readline();
            $entradaTexto = strtoupper($entradaTexto);
            $escaneo->setEscaneo($entradaTexto);
        
            

            if ($escaneo->elEscaneoEsUnPerfil()){
                $perfil->setUsuario($entradaTexto);
                echo "Usuario nuevo:" . $entradaTexto. "\n";
            }elseif

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