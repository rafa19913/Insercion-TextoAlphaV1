
<?php
    
    //Clase Conexion para el envio y manejo de datos a NVR 
    class Conexion {
        private $mu;
        private $ubicacion;

        
        public function __construct($mu, $ubicacion){
            $this->mu = $mu;
            $this->ubicacion = $ubicacion;
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

        public function envioDeDatosANVR($usuario){
            //FALTA: hacer el proceso de enviar datos a NVR
            echo "Se envio data al NVR \n";
            echo "User:". $usuario. " MU:" . $this->mu . " Ubicacion:" . $this->ubicacion . "\n";
        }
        



    }




?>
