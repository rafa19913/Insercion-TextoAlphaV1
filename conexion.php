
<?php
    
    //Clase Conexion para el envio y manejo de datos a NVR 
    class Conexion {

        public static $nvrIp = "192.168.100.129";

        private $mu;
        private $ubicacion;

        private $puerto;

 
        
        
        public function __construct($mu, $ubicacion, $puerto){
            $this->mu = $mu;
            $this->ubicacion = $ubicacion;
            $this->puerto = $puerto;
        }

            
        public function setMu($mu){
            $this->mu = $mu;
        }

        
        public function getMu(){
            return $this->mu;
        }

        public function setUbicacion($ubicacion){
            $this->ubicacion = $ubicacion;
        }

        
        public function getUbicacion(){
            return $this->ubicacion;
        }

        public function validarEnvioDeDatos(){
            if ($this->mu != "" && $this->ubicacion != ""){
                return true;
            }else{
                return false;
            }
        }

        public function envioDeDatosANVR($usuario, $horaYFecha, $puerto){


            //$puerto = $this->obtenerPuertoDeSalida($this->ubicacion);
            
            //echo "Se envio data al NVR \n";
            //echo "User:". $usuario. " MU:" . $this->mu . " Ubicacion:" . $this->ubicacion . "\n";
            
            
            //Información de la conexión
            $tiempo = 1;

            //Se establece una conexión al NVR mediante TCP
            $actualConexion = fsockopen(Conexion::$nvrIp, $puerto, $errno, $errstr, $tiempo);

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
                $datos .= "Usuario: $usuario \r\n";
                $datos .= "Codigo: $this->mu \r\n";
                $datos .= "Ubicacion: $this->ubicacion \r\n";
                $datos .= "---------------------------------------\r\n";

                echo "Codigo enviado: ";
            }

            fwrite($actualConexion, $datos);

            //Se cierra la conexión TCP con el grabador
            fclose($actualConexion);



        }
/*
        public function obtenerPuertoDeSalida($ubicacion){

            if (strpos($ubicacion, 'A1-') !== false || 
            strpos($ubicacion, 'A2-') !== false){
                return "14002"; //CAMARA CON ESTE PUERTO
            }

            if (strpos($ubicacion, 'B1-') !== false || 
            strpos($ubicacion, 'B2-') !== false){
                return "14003"; //CAMARA CON ESTE PUERTO
            }

            if (strpos($ubicacion, 'C1-') !== false || 
            strpos($ubicacion, 'C2-') !== false){
                return "14004"; //CAMARA CON ESTE PUERTO
            }

            


        }
        

*/

    }




?>
