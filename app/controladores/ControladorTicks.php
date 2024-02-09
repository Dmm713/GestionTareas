<?php 
class ControladorTicks{
    function insertarTick(){
        //Creamos la conexión utilizando la clase que hemos creado
        $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $idTarea = filter_var($_GET['id'],FILTER_SANITIZE_NUMBER_INT);
        $ticksDAO = new TicksDAO($conn);
        $tick = new Tick();
        $tick->setIdTarea($idTarea);
        $tick->setIdUsuario(Sesion::getUsuario()->getId());
        if($ticksDAO->insert($tick)){
            print json_encode(['respuesta'=>'ok']);
        }else{
            print json_encode(['respuesta'=>'error']);
        }
    }
    function borrarTick(){
   
        //Creamos la conexión utilizando la clase que hemos creado
        $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        $idTarea = filter_var($_GET['id'],FILTER_SANITIZE_NUMBER_INT);
        $ticksDAO = new TicksDAO($conn);
        if(!$tick = $ticksDAO->getByIdUsuarioIdTarea(Sesion::getUsuario()->getId(),$idTarea)){
            print json_encode(['respuesta'=>'error', 'mensaje'=>'el favorito no existe']);
            die();
        }
        
        if($ticksDAO->delete($tick)){
            print json_encode(['respuesta'=>'ok']);
        }else{
            print json_encode(['respuesta'=>'error']);
        }
    }


}



?>