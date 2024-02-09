<?php 

require_once 'app/config/config.php';
require_once 'app/modelos/ConnexionDB.php';
require_once 'app/modelos/Usuario.php';
require_once 'app/modelos/UsuariosDAO.php';
require_once 'app/modelos/Foto.php';
require_once 'app/modelos/FotosDAO.php';
require_once 'app/modelos/Sesion.php';
require_once 'app/modelos/Tarea.php';
require_once 'app/modelos/TareasDAO.php';
require_once 'app/modelos/Tick.php';
require_once 'app/modelos/TicksDAO.php';
require_once 'app/controladores/ControladorUsuarios.php';
require_once 'app/controladores/controladorTareas.php';
require_once 'app/controladores/ControladorTicks.php';
require_once 'app/controladores/controladorFotos.php';
require_once 'app/utils/funciones.php';


//Uso de variables de sesión
session_start();

//Mapa de enrutamiento
$mapa = array(
    'inicio'=>array("controlador"=>'ControladorUsuarios',
                    'metodo'=>'inicio',
                    'privada'=>false),
    'verTareas'=>array("controlador"=>'ControladorTareas',
                         'metodo'=>'verTareas', 
                         'privada'=>false),
    'insertarTarea'=>array('controlador'=>'ControladorTareas',
                              'metodo'=>'insertarTarea', 
                              'privada'=>true),
    'borrarTarea'=>array('controlador'=>'ControladorTareas',
                            'metodo'=>'borrar', 
                            'privada'=>true),
    'editarTarea'=>array('controlador'=>'ControladorTareas',
                            'metodo'=>'editar', 
                            'privada'=>true), 
    'login'=>array('controlador'=>'ControladorUsuarios', 
                   'metodo'=>'login', 
                   'privada'=>false),
    'logout'=>array('controlador'=>'ControladorUsuarios', 
                    'metodo'=>'logout', 
                    'privada'=>true),
    'registrar'=>array('controlador'=>'ControladorUsuarios', 
                       'metodo'=>'registrar', 
                       'privada'=>false),
    'insertar_tick'=>array(
                        'controlador'=>'ControladorTicks',
                        'metodo'=>'insertarTick',
                        'privada'=>true),
    'borrar_tick'=>array(
                        'controlador'=>'ControladorTicks',
                        'metodo'=>'borrarTick',
                        'privada'=>true),
    'addImageTarea'=>array(
                        'controlador'=>'ControladorTareas',
                        'metodo'=>'addImageTarea',
                        'privada' => false),
    'deleteImageTarea' => array(
                        'controlador'=> 'ControladorTareas',
                        'metodo'=>'deleteImageTarea',
                        'privada'=> false),
    'borrarFoto' => array(
                        'controlador'=> 'ControladorFotos',
                        'metodo'=>'borrar',
                        'privada'=> false),
                        

    /* 'insertar_favorito'=>array('controlador'=>'ControladorFavoritos', 
                       'metodo'=>'insertar', 
                       'privada'=>false),                       
    'borrar_favorito'=>array('controlador'=>'ControladorFavoritos', 
                       'metodo'=>'borrar', 
                       'privada'=>false),
    'addImageMensaje'=>array('controlador'=>'ControladorMensajes', 
                       'metodo'=>'addImageMensaje', 
                       'privada'=>false),
    'deleteImageMensaje'=>array('controlador'=>'ControladorMensajes', 
                       'metodo'=>'deleteImageMensaje', 
                       'privada'=>false),              */          
);



//Parseo de la ruta
if(isset($_GET['accion'])){ //Compruebo si me han pasado una acción concreta, sino pongo la accción por defecto inicio
    if(isset($mapa[$_GET['accion']])){  //Compruebo si la accción existe en el mapa, sino muestro error 404
        $accion = $_GET['accion']; 
    }
    else{
        //La acción no existe
        header('Status: 404 Not found');
        echo 'Página no encontrada';
        die();
    }
}else{
    $accion='inicio';   //Acción por defecto
}

//Si existe la cookie y no ha iniciado sesión, le iniciamos sesión de forma automática
//if( !isset($_SESSION['email']) && isset($_COOKIE['sid'])){
if( !Sesion::existeSesion() && isset($_COOKIE['sid'])){
    //Conectamos con la bD
    $connexionDB = new ConnexionDB(MYSQL_USER,MYSQL_PASS,MYSQL_HOST,MYSQL_DB);
    $conn = $connexionDB->getConnexion();
    
    //Nos conectamos para obtener el id y la foto del usuario
    $usuariosDAO = new UsuariosDAO($conn);
    if($usuario = $usuariosDAO->getBySid($_COOKIE['sid'])){
        //$_SESSION['email']=$usuario->getEmail();
        //$_SESSION['id']=$usuario->getId();
        //$_SESSION['foto']=$usuario->getFoto();
        Sesion::iniciarSesion($usuario);
    }
    
}

//Si la acción es privada compruebo que ha iniciado sesión, sino, lo echamos a index
// if(!isset($_SESSION['email']) && $mapa[$accion]['privada']){
if(!Sesion::existeSesion() && $mapa[$accion]['privada']){
    header('location: index.php');
    guardarMensaje("Debes iniciar sesión para acceder a $accion");
    die();
}


//$acción ya tiene la acción a ejecutar, cogemos el controlador y metodo a ejecutar del mapa
$controlador = $mapa[$accion]['controlador'];
$metodo = $mapa[$accion]['metodo'];

//Ejecutamos el método de la clase controlador
$objeto = new $controlador();
$objeto->$metodo();