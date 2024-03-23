
<?php
    
    //Clase Escaneos para el manejo los escaneos actuales 
    class Escaneos {

          // Declaración de una variable estática
          
        public static $puertosTotales = [
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
   
        
        private $escaneoActual;
        
        
        public function __construct($escaneoActual){
            $this->escaneoActual = $escaneoActual;
        }

        
        public function setEscaneo($escaneoActual){
            $this->escaneoActual = $escaneoActual;
        }

        
        public function getEscaneo(){
            return $this->escaneoActual;
        }


        /* Empiezan validaciones al momento de escanear */

        public function elEscaneoEsUnPerfil(){
            if (strpos($this->escaneoActual, 'USR-CEVA') !== false) {
                return true; // Indica que el texto es un usuario
            }else{
                return false;
            }
            
        }



        public function elEscaneoEsUnaUbicacion(){
            $cantidadCaracteres = strlen($this->escaneoActual);
            if ($cantidadCaracteres == 9){
                $ubicacionCorta = substr($this->escaneoActual, 0, 3);
                if (array_key_exists($ubicacionCorta, Escaneos::$puertosTotales)) {
                    return true;
                }

            }
            return false;
        }

        public function obtenerPuertoDeSalida(){
            $ubicacionCorta = substr($this->escaneoActual, 0, 3);
            if (array_key_exists($ubicacionCorta, Escaneos::$puertosTotales)) {
                return Escaneos::$puertosTotales[$ubicacionCorta];
            }
        }
       
        

       /* public function elEscaneoEsUnaUbicacion(){
            $cantidadCaracteres = strlen($$this->escaneoActual);
            if ($cantidadCaracteres == 9){

               
                
            }else{
                return false;
            }
         
            if (strpos($this->escaneoActual, 'A1-') !== false || 
                strpos($this->escaneoActual, 'A2-') !== false ||
                strpos($this->escaneoActual, 'B1-') !== false ||
                strpos($this->escaneoActual, 'B2-') !== false ||
                strpos($this->escaneoActual, 'C1-') !== false ||
                strpos($this->escaneoActual, 'C2-') !== false ||
                strpos($this->escaneoActual, 'D1-') !== false ||
                strpos($this->escaneoActual, 'D2-') !== false ||
                strpos($this->escaneoActual, 'E1-') !== false ||
                strpos($this->escaneoActual, 'E2-') !== false ||
                strpos($this->escaneoActual, 'F1-') !== false ||
                strpos($this->escaneoActual, 'F2-') !== false ||
                strpos($this->escaneoActual, 'G1-') !== false ||
                strpos($this->escaneoActual, 'G2-') !== false ||
                strpos($this->escaneoActual, 'H1-') !== false ||
                strpos($this->escaneoActual, 'H2-') !== false ||
                strpos($this->escaneoActual, 'I1-') !== false ||
                strpos($this->escaneoActual, 'I2-') !== false 
                
                 ) {
                return true; // Indica que el texto es una ubicacion
            }else{
                return false;
            }
        }*/

        public function elEscaneoEsUnMu(){

            if (strpos($this->escaneoActual, 'SN') !== false || strpos($this->escaneoActual, 'SNS') !== false || strpos($this->escaneoActual, 'SNSS') !== false) {
                return true; // Indica que el texto es un MU
            }else{
                return false;
            }


        }
    
        





    }




?>
