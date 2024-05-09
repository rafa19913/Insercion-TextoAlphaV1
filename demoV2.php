
<?php

    //INICIA: VARIABLES GLOBLES
    

    // usuarios totales > actualProceso (REC = Recibos) (PICK = Picking / Transferencia)
    $usuarios = [
        "USR01" => ["RECIBOS", "", ""],
        "USR02" => ["RECIBOS", "", ""],
        "USR03" => ["RECIBOS", "", ""],
        "USR04" => ["RECIBOS", "", ""],
    ];

    // Ubicaciones de racks > puerto de camara
    $ubicacionesConPuertos = [
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


    
    define("NVR_IP", "192.168.100.129");


    //TERMINA: VARIABLES GLOBALES

    

    function elEscaneoEsValido($escaneo){
        $escaneoActual = substr($escaneo, 5); //Se remueve el USR01 y queda solametne el escaneo ESCANEO SIN USUARIO
        $usuarioActual = substr($escaneo, 0, 5); //Se obtiene el usuario que escaneo (USR01, USR02, USR03)
        // Expresion regular para validar si el escaneo es un SN
        $SNValido = "/^(SN|SNS|SNSS)\d+$/";
        // Expresion regular para validar si el escaneo es una ubicacion
        $UbicacionValida = "/^[A-Z]\d-\d-\d{2}-\d$/";
        // Expresión regular para validar si el escaneo sera un cambio de proceso a recibos
        $preAdviceValida = "/^RE\d{6}$/";
        // Expresión regular para validar si el escaneo sera un cambio de proceso a picking
        $taskIDValida = "/^OR\d{6}$/";
        
        $tipoDeEscaneo = "";
        
        //CONDICION IMPORTANTE
        //Validamos si el escaneo es un SN, UBICACION, TASKID O PRE-ADVICE O RECEIVE01 Y SE MARCA COMO ESCANEO VALIDO Y SE PROCESA
        if ($escaneoActual == "RECEIVE01"){
            $tipoDeEscaneo = "RECEIVE01";
        }else if(preg_match($SNValido, $escaneoActual)){
            $tipoDeEscaneo = "SN";
        }else if(preg_match($UbicacionValida, $escaneoActual)){
            $tipoDeEscaneo = "UB";
        }else if (preg_match($preAdviceValida, $escaneoActual)){
            $tipoDeEscaneo = "RECIBOS";
        }else if(preg_match($taskIDValida, $escaneoActual)){
            $tipoDeEscaneo = "PICKO-RELOCATE";
        }

        if ($tipoDeEscaneo == ""){
            return false;
        }

        
        
        //Se procesa el escaneo Válido
        if ($tipoDeEscaneo == "RECIBOS" || $tipoDeEscaneo == "PICKO-RELOCATE"){
            cambioDeProceso($tipoDeEscaneo, $usuarioActual);
        }else{
            procesarEscaneo($tipoDeEscaneo, $usuarioActual, $escaneoActual);
        }

        return true;


    }

    //$escaneoLimpio = (UBICACION, SN O REC01) $tipoDeEscaneo = (RECIBOS) (PICKO-RELOCATE) $usuarioActual (USR01) (USR02) (USR03) (USR04)
    //SEGUNDA FUNCION MAS IMPORTANTE VALIDAR BIEN
    function procesarEscaneo($tipoDeEscaneo, $usuarioActual, $escaneoLimpio){ 
        //VALIDAR EL PROCESO ACTUAL QUE SE ENCUENTRA EL USUARIO
        $procesoActual = "";
        $procesoActual = obtenerProcesoActualDelUsuario($usuarioActual);

        if ($procesoActual == "RECIBOS"){
            procesarEscaneoRecibos($escaneoLimpio, $usuarioActual);
        }else{
            procesarEscaneoPickRelocate($escaneoLimpio, $usuarioActual);
        }
        
    }

    

    function obtenerProcesoActualDelUsuario($usuarioActual){
        global $usuarios;
        $proceso = "";
        
        global $usuarios;

        if (validarUsuarioExistente($usuarioActual)){
            $proceso = $usuarios[$usuarioActual][0];
        }else{
            enviarMensajeAviso("El usuario no existe en obtener Proceso Actual del Usuario, contactar con soporte");
        }
        return $proceso;
        
    }


    function procesarEscaneoPickRelocate($escaneoLimpio, $usuarioActual){
        global $ubicacionesConPuertos;
        global $usuarios;

        $puertoSalida = "";
        $ubicacionCorta = "";

    
        
        $SNValido = "/^(SN|SNS|SNSS)\d+$/";
        
        if (preg_match($SNValido, $escaneoLimpio)){
            agregarSN($usuarioActual, $escaneoLimpio);
        }else{
            agregarUbicacion($usuarioActual, $escaneoLimpio);
        }

        $snActualDelUsuario = $usuarios[$usuarioActual][2];
        $ubicacionActualDelUsuario = $usuarios[$usuarioActual][1];

        if ($snActualDelUsuario != "" && $ubicacionActualDelUsuario != ""){
            if ($ubicacionActualDelUsuario == "RECEIVE01"){ 
                enviarImpresion($usuarioActual, $snActualDelUsuario, $ubicacionActualDelUsuario, "14001");    
            }else{
                $ubicacionCorta = substr($ubicacionActualDelUsuario, 0, 3); // (A1-) (B2-) (D1-)

                if (array_key_exists($ubicacionCorta, $ubicacionesConPuertos)) {
                    $puertoSalida = $ubicacionesConPuertos[$ubicacionCorta];
                }else{
                    $puertoSalida = "14001";
                }
                enviarImpresion($usuarioActual, $snActualDelUsuario, $ubicacionActualDelUsuario, $puertoSalida);
            }
            $usuarios[$usuarioActual][2] = "";
            $usuarios[$usuarioActual][1] = "";
        }

    }

    
    function procesarEscaneoRecibos($escaneoLimpio, $usuarioActual){
        //FALTA: CAMBIAR TODAS LAS POSIBLES VARIABLES A CONSTANTES COMO PICK-RELOCATE Y RECIBOS
       
        global $ubicacionesConPuertos;
        // Expresion regular para validar si el escaneo es una ubicacion
        $UbicacionValida = "/^[A-Z]\d-\d-\d{2}-\d$/";

        if(preg_match($UbicacionValida, $escaneoLimpio)){
            cambioDeProceso("PICKO-RELOCATE", $usuarioActual); 
            agregarUbicacion($usuarioActual, $escaneoLimpio);
            
            return;
        }elseif ($escaneoLimpio == "RECEIVE01"){ //Si el escaneo es RECIEVE01 regresar
            return;
        }else{
            enviarImpresion($usuarioActual, $escaneoLimpio, "RECEIVE01", "14001"); //escaneoLimpio = (SN)
        }
    
    }
    

    
    function agregarSN($usuarioActual, $SN){
        global $usuarios;

        if (validarUsuarioExistente($usuarioActual)){
            $usuarios[$usuarioActual][2] = $SN; //Se cambia el SN
            echo "El usuario: ".$usuarioActual." escaneó el SN: ".$SN."\n";
        }else{
            enviarMensajeAviso("El usuario no existe en agregarSN, contactar con soporte");
        }

    }

    function agregarUbicacion($usuarioActual, $ubicacion){
        global $usuarios;
        if (array_key_exists($usuarioActual, $usuarios)) {
            $usuarios[$usuarioActual][1] = $ubicacion; //Se cambia la ubicación
            echo "El usuario: ".$usuarioActual." escaneó la ubicación: ".$ubicacion."\n";
        }else{
            echo "El usuario: ".$usuarioActual." NO EXISTE - CONSULTAR SOPORTE: .\n";
        }
    }

    function enviarImpresion($usuario, $sn, $ubicacion, $puertoSalida){
        $tiempo = 1; 
        $actualConexion = fsockopen(NVR_IP, $puertoSalida, $errno, $errstr, $tiempo);

        if (!$actualConexion) {
            //Si no hay conexión, imprime el error.
            echo "$errstr ($errno)<br />\n";
        }else{

            date_default_timezone_set('America/Mexico_City');
            $hora_actual = new DateTime();
            $horaYFecha = $hora_actual->format('Y-m-d H:i:s');
            
            $datos = "";
            $datos .= "Fecha: $horaYFecha \r\n";
            $datos .= "Usuario: $usuario \r\n";
            $datos .= "Codigo: $sn \r\n";
            $datos .= "Ubicacion: $ubicacion \r\n";
            $datos .= "---------------------------------------\r\n";


            date_default_timezone_set('America/Mexico_City');
            $hora_actual = new DateTime();
            $horaYFecha = $hora_actual->format('Y-m-d H:i:s');

            echo "Envio de dato a NVR correctamnete \n";
            echo "SN: ". $sn. "\n";
            echo "Ubicación: ". $ubicacion. "\n";
            echo "Fecha envío: ". $horaYFecha. "\n";
        }
        fwrite($actualConexion, $datos);
        //Se cierra la conexión TCP con el grabador
        fclose($actualConexion);
    }

    function cambioDeProceso($tipoDeEscaneo, $usuarioActual){
        global $usuarios;

        if (validarUsuarioExistente($usuarioActual)){
            $usuarios[$usuarioActual][0] = $tipoDeEscaneo; //Se cambia el proceso *** MAS IMPORTANTE
            $usuarios[$usuarioActual][1] = ""; //Se cambia la ubicación
            $usuarios[$usuarioActual][2] = ""; //Se cambia el SN
            echo "El usuario: ".$usuarioActual." cambió de proceso a ".$tipoDeEscaneo."\n";
        }else{
            enviarMensajeAviso("El usuario no existe en cambio de proceso, contactar con soporte");
        }

    }

    function validarUsuarioExistente($usuario){
        global $usuarios;
        if (array_key_exists($usuario, $usuarios)) {
            return true;
        }else{
            return false;
        }
    }

    function enviarMensajeAviso($cadena){
        echo $cadena."\n";
    }

    function main(){

  
        while (true){
            echo "En espera de escaneo... \n";
  
            $escaneoRAW = readline();
            $escaneoRAW = strtoupper($escaneoRAW);

            if (elEscaneoEsValido($escaneoRAW)){
                enviarMensajeAviso("Escaneo válido - Procesado correctamente \n");
            }

        }
        

    }

    
    
    main();

    
    

?>