<?php 
class ControladorFotos{
    public function borrar(){
        //Creamos la conexión utilizando la clase que hemos creado
        $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        //Creamos el objeto MensajesDAO para acceder a BBDD a través de este objeto
        $fotosDAO = new FotosDAO($conn);

        //Obtener el mensaje
        $nombreFoto = htmlspecialchars($_GET['nombreFoto']);

        //Comprobamos que mensaje pertenece al usuario conectado
        if($fotosDAO->deleteByNombreFoto($nombreFoto)){
            unlink('web/images/' . $nombreFoto);
            print json_encode(['respuesta'=>'ok']);
        }
    }
}