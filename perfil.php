
<?php
    
    //Clase Perfil para el usuario actual escaneando 
    class Perfil {
        private $usuario;

        
        public function __construct($usuario){
            $this->usuario = $usuario;
        }

        public function setUsuario($usuario){
            $this->usuario = $usuario;
        }

        
        public function getUsuario(){
            return $this->usuario;
        }

   
        
        
        

    }



?>
