<?php 


class TareasDAO {
    private mysqli $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }
    

    /**
     * Obtiene un mensaje de la BD en función del id pasado
     * @return Tareas Devuelve el objeto Mensaje o null si no lo encuentra
     */
    public function getById($id):Tarea|null {
        //$this->conn->prepare() devuleve un objeto de la clase mysqli_stmt
        if(!$stmt = $this->conn->prepare("SELECT * FROM tareas WHERE id = ?"))
        {
            echo "Error en la SQL: " . $this->conn->error;
        }
        //Asociar las variables a las interrogaciones(parámetros)
        $stmt->bind_param('i',$id);
        //Ejecutamos la SQL
        $stmt->execute();
        //Obtener el objeto mysql_result
        $result = $stmt->get_result();

        //Si ha encontrado algún resultado devolvemos un objeto de la clase Mensaje, sino null
        if($result->num_rows == 1){
            $tarea = $result->fetch_object(Tarea::class);
            return $tarea;
        }
        else{
            return null;
        }
    }

    public function getByIdUsuario($idUsuario) {
        //$this->conn->prepare() devuleve un objeto de la clase mysqli_stmt
        if(!$stmt = $this->conn->prepare("SELECT * FROM tareas WHERE idUsuario = ?"))
        {
            echo "Error en la SQL: " . $this->conn->error;
        }
        //Asociar las variables a las interrogaciones(parámetros)
        $stmt->bind_param('i',$idUsuario);
        //Ejecutamos la SQL
        $stmt->execute();
        //Obtener el objeto mysql_result
        $result = $stmt->get_result();
        $tareas = array();
        //Si ha encontrado algún resultado devolvemos un objeto de la clase Mensaje, sino null
        while($tarea = $result->fetch_object(Tarea::class)){
            $tareas[] = $tarea;
        }
        return $tareas;
    }

    /**
     * Obtiene todos los mensajes de la tabla mensajes
     * @return array Devuelve un array de objetos Mensaje
     */
    public function getAll():array {
        if(!$stmt = $this->conn->prepare("SELECT * FROM tareas"))
        {
            echo "Error en la SQL: " . $this->conn->error;
        }
        //Ejecutamos la SQL
        $stmt->execute();
        //Obtener el objeto mysql_result
        $result = $stmt->get_result();

        $array_tareas = array();
        
        while($tarea = $result->fetch_object(Tarea::class)){
            $array_tareas[] = $tarea;
        }
        return $array_tareas;
    }


    /**
     * borra el mensaje de la tabla mensajes del id pasado por parámetro
     * @return true si ha borrado el mensaje y false si no lo ha borrado (por que no existia)
     */
    function delete($id):bool{

        if(!$stmt = $this->conn->prepare("DELETE FROM tareas WHERE id = ?"))
        {
            echo "Error en la SQL: " . $this->conn->error;
        }
        //Asociar las variables a las interrogaciones(parámetros)
        $stmt->bind_param('i',$id);
        //Ejecutamos la SQL
        $stmt->execute();
        //Comprobamos si ha borrado algún registro o no
        if($stmt->affected_rows==1){
            return true;
        }
        else{
            return false;
        }
        
    }

    /**
     * Inserta en la base de datos el mensaje que recibe como parámetro
     * @return idMensaje Devuelve el id autonumérico que se le ha asignado al mensaje o false en caso de error
     */
    function insert($texto, $idUsuario){
        if(!$stmt = $this->conn->prepare("INSERT INTO tareas (texto, idUsuario) VALUES (?,?)")){
            die("Error al preparar la consulta insert: " . $this->conn->error );
        }
        $stmt->bind_param('si', $texto, $idUsuario);
        if($stmt->execute()){
            $idInsertado = $this->conn->insert_id;
            $nuevaTarea = $this->getById($idInsertado);
            return $nuevaTarea;
        }
        else{
            return false;
        }
    }
    /**
     * 
     */
    function update($tarea){
        if(!$stmt = $this->conn->prepare("UPDATE tareas SET texto=?, idUsuario=? WHERE id=?")){
            die("Error al preparar la consulta update: " . $this->conn->error );
        }
        $texto = $tarea->getTexto();
        $idUsuario = $tarea->getIdUsuario();
        $id = $tarea->getId();
        $stmt->bind_param('sii', $texto, $idUsuario,$id);
        return $stmt->execute();
    }
}
?>
