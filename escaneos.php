
<?php
    
    //Clase Escaneos para el manejo los escaneos actuales 
    class Escaneos {

        //Declaración de una variable estática
          
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
            // La ubicacion es en recibos 
            if ($this->escaneoActual == "SHIP05"){
                return true;
            }

            // La ubicacion es en estante
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

            if ($this->escaneoActual == "SHIP05" || $this->escaneoActual == "RECEIVE01"){ // REGRESA EL PUERTO DE RECIBOS 
                return "14001"; // PUERTO DE RECIBOS 
            }
            

            if (array_key_exists($ubicacionCorta, Escaneos::$puertosTotales)) {
                return Escaneos::$puertosTotales[$ubicacionCorta];
            }
            // EN CASO QUE ENCUENTRE SE ESCANEE UNA UBICACION NO DADA DE ALTA SE MANDA A LA CAMARA DE RECIBOS
            return "14001";
        }
       
        
        public function elEscaneoEsUnMu(){

            if (strpos($this->escaneoActual, 'SN') !== false || strpos($this->escaneoActual, 'SNS') !== false || strpos($this->escaneoActual, 'SNSS') !== false) {
                return true; // Indica que el texto es un MU
            }else{
                return false;
            }

        }



        public function actualEscaneo($escaneo){
            // SE VALIDA EN QUE PROCESO ESTAMOS (RECIBOS, PICKING, RELOCATE)
            // Dato introducido por el usuario
      
            
            // Expresión regular para validar si el escaneo sera recibos
            $preAdvice = "/^RE\d{6}$/";
            // Expresión regular para validar si el escaneo sera en picking
            $taskID = "/^OR\d{6}$/";


            if (preg_match($preAdvice, $escaneo)) {
                //Empieza el proceso de recibos
                $this->empezarProcesoRecibos();
                return "RECIBOS";
            }

            else if(preg_match($taskID, $escaneo)) {
                //Empieza el proceso de picking
                return "PICK";
            }

            
            return "NADA";

        }

        public function empezarProcesoRecibos(){
            echo "Alo";
        }
    
        





    }




?>
