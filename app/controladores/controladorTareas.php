<?php 

class ControladorTareas{
    public function verTareas(){
        //Creamos la conexión utilizando la clase que hemos creado
        $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        //Creamos el objeto MensajesDAO para acceder a BBDD a través de este objeto
        $tareasDAO = new TareasDAO($conn);

        $tareas = $tareasDAO->getByIdUsuario(Sesion::getUsuario()->getId());

        require 'app/vistas/verTareas.php';
    }

    public function inicio(){

        //Creamos la conexión utilizando la clase que hemos creado
        $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        //Creamos el objeto MensajesDAO para acceder a BBDD a través de este objeto
        $tareasDAO = new TareasDAO($conn);
        $tarea = $tareasDAO->getAll();
        //Incluyo la vista
        require 'app/vistas/inicio.php';
    }

    public function borrar(){
        //Creamos la conexión utilizando la clase que hemos creado
        $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        //Creamos el objeto MensajesDAO para acceder a BBDD a través de este objeto
        $tareasDAO = new TareasDAO($conn);

        //Obtener el mensaje
        $idTarea = htmlspecialchars($_GET['id']);

        //Comprobamos que mensaje pertenece al usuario conectado
        if($tareasDAO->delete($idTarea)){
            print json_encode(['respuesta'=>'ok']);
        }
        sleep(1);
    }

    public function editar(){
        $error ='';


        //Conectamos con la bD
        $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();

        //Obtengo el id del mensaje que viene por GET
        $idTarea = htmlspecialchars($_GET['id']);
        //Obtengo el mensaje de la BD
        $tareasDAO = new TareasDAO($conn);
        $tarea = $tareasDAO->getById($idTarea);

        //Obtengo las fotos de la BD
        $fotosDAO = new FotosDAO($conn);
        $fotos = $fotosDAO->getAllByIdTarea($idTarea);
        if(Sesion::getUsuario()->getId()!=$tarea->getIdUsuario()){
            die('<h1 style="color: red; text-align: center;">Error al editar la tarea</h1>');
        }
        //Cuando se envíe el formulario actualizo el mensaje en la BD
        if($_SERVER['REQUEST_METHOD']=='POST'){

            //Limpiamos los datos que vienen del usuario
            $texto = htmlspecialchars($_POST['texto']);
            $idUsuario = Sesion::getUsuario()->getId();

            //Validamos los datos
            if(empty($texto)){
                $error = "El campo es obligatorio";
            }
            else{
                $tarea->setTexto($texto);
                $tarea->setIdUsuario($idUsuario);

                $tareasDAO->update($tarea);
                header('location: index.php');
                die();
            }

        } //if($_SERVER['REQUEST_METHOD']=='POST'){
        
            require 'app/vistas/editarTarea.php';
    }

    public function insertarTarea(){
        
        $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();


        $texto = htmlentities($_POST['texto']);

        $tareasDAO = new TareasDAO($conn);
        $idUsuario = Sesion::getUsuario()->getId();
        $tarea = $tareasDAO->insert($texto, $idUsuario);

        print $tarea->toJSON();

        //Simulamos que el servidor tarda 1sg para probar el preloader
        sleep(1);
    }

    function addImageTarea(){
        $idTarea= htmlentities($_GET['idTarea']);

        $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
        $conn = $connexionDB->getConnexion();
        $fotosDAO = new FotosDAO($conn);
        $foto = new Foto();

        $tareaDAO = new TareasDAO($conn);
        $tarea = $tareaDAO->getById($idTarea);

        if(Sesion::getUsuario()->getId()!=$tarea->getIdUsuario()){
            die('Error al insertar la foto');
        }
        
       
        $nombreArchivo = htmlentities($_FILES['foto']['name']);
        $informacionPath = pathinfo($nombreArchivo);
        $extension = $informacionPath['extension'];
        $nombreArchivo = md5(time()+rand()) . '.' . $extension;
        move_uploaded_file($_FILES['foto']['tmp_name'],"web/images/$nombreArchivo");

        

        $foto->setIdTarea($idTarea);
        $foto->setNombreArchivo($nombreArchivo);
        $fotosDAO->insert($foto);
        print json_encode(['respuesta'=>'ok', 'nombreArchivo'=> $nombreArchivo]);

    }

   
}