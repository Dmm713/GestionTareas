<?php 
    class TicksDAO{
    private mysqli $conn;

    public function __construct($conn) {
        $this->conn = $conn;
    }

    public function insert($tick){
        if($this->existByIdUsuarioIdTarea($tick->getIdUsuario(), $tick->getIdTarea()));
        
        if(!$stmt = $this->conn->prepare("INSERT  INTO tick (idUsuario, idTarea) VALUES (?,?)")){
            die("Error al preparar la consulta insert: " . $this->conn->error );
        }
        $idUsuario = $tick->getIdUsuario();
        $idTarea = $tick->getIdTarea();
        $stmt->bind_param('ii',$idUsuario, $idTarea);
        if($stmt->execute()){
            $tick->setId($stmt->insert_id);
            return $stmt->insert_id;
        }
        else{
            return false;
        }
    }

    public function existByIdUsuarioIdTarea($idUsuario, $idTarea){
        if(!$stmt = $this->conn->prepare("SELECT * FROM tick WHERE idUsuario = ? and idTarea=?")){
            die("Error al preparar la consulta select count: " . $this->conn->error );
        }
        $stmt->bind_param('ii',$idUsuario, $idTarea);
        $stmt->execute();
        $result = $stmt->get_result();
        if($result->num_rows>=1){
            return true;
        }else{
            return false;
        }
    }
    public function delete($tick){
        if(!$stmt = $this->conn->prepare("DELETE FROM tick WHERE id = ?")){
            die("Error al preparar la consulta delete: " . $this->conn->error );
        }
        $id = $tick->getId();
        $stmt->bind_param('i',$id);
        $stmt->execute();
        if($stmt->affected_rows >=1 ){
            return true;
        }
        else{
            return false;
        }
    }
    public function getByIdUsuarioIdTarea($idUsuario, $idTarea){
        if(!$stmt = $this->conn->prepare("SELECT * FROM tick WHERE idUsuario = ? and idTarea=?")){
            die("Error al preparar la consulta select count: " . $this->conn->error );
        }
        $stmt->bind_param('ii',$idUsuario, $idTarea);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if($tick = $result->fetch_object(Tick::class)){
            return $tick;
        }
        else{
            return false;
        }
        
    } 

    }



?>