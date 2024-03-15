
<?php

    echo "Hola mundo";
     //Información del grabador
     $nvr_ip = "192.168.100.166";
     $nvr_puerto = "14002";
     $tiempo = 1;
 
     //Información d
     //Se establece una conexión al NVR mediante TCP
     $conexion = fsockopen($nvr_ip, $nvr_puerto, $errno, $errstr, $tiempo);
 
     fwrite($conexion, "Envio al servidor 1");
     //Se cierra la conexión TCP con el grabador
     fclose($conexion);

?>