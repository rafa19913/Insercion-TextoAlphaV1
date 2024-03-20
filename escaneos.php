
<?php
    
    //Clase Escaneos para el manejo los escaneos actuales 
    class Escaneos {

          // Declaración de una variable estática
        public static $perfilValido = 'USR-CEVA-00';
   
        
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
            
            if (strpos($this->escaneoActual, self::$perfilValido) !== false) {
                return true; 
            }
            return false;
            
        }

       
        

        public function elEscaneoEsUnaUbicacion(){
            
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
        }

        public function elEscaneoEsUnMu(){

            if (strpos($this->escaneoActual, 'SN') !== false || strpos($this->escaneoActual, 'SNS') !== false || strpos($this->escaneoActual, 'SNSS') !== false) {
                return true; // Indica que el texto es un MU
            }else{
                return false;
            }


        }
    
        





    }




?>
