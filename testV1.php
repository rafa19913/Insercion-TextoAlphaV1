<?php
            //Información de la conexión
            $tiempo = 1;

            //Se establece una conexión al NVR mediante TCP
            $actualConexion = fsockopen("192.168.100.129", "14002", $errno, $errstr, $tiempo);

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
                $datos .= "Codigo: SN12345 \r\n";
                $datos .= "Ubicacion: RECEIVE01 \r\n";
                $datos .= "---------------------------------------\r\n";

                echo "Codigo enviado: ";
            }

            fwrite($actualConexion, $datos);

            //Se cierra la conexión TCP con el grabador
            fclose($actualConexion);


?>