<?php

class Conexion {
    private $servidor;
    private $usuario;
    private $contraseña;
    private $basedatos;
    public $conexion;

    public function __construct() {
        $this->servidor = "localhost";
        $this->usuario = "root";
        $this->contraseña = "";
        $this->basedatos = "jhayli";
    }

    function conectar() {
        $this->conexion = new mysqli($this->servidor, $this->usuario, $this->contraseña, $this->basedatos);
        $this->conexion->set_charset("utf8");
        
        if ($this->conexion->connect_error) {
            die("Conexión fallida: " . $this->conexion->connect_error);
        }
    }

    function cerrar() {
        $this->conexion->close();
    }
}

class Modelo_Categoria {
    private $conexion;

    public function __construct(Conexion $conexion) {
        $this->conexion = $conexion;
        $this->conexion->conectar();
    }

    public function listarCategoria() {
        $sql = "CALL SP_LISTAR_CATEGORIA()";
        $arreglo = array();

        if ($consulta = $this->conexion->conexion->query($sql)) {
            while ($consulta_vu = mysqli_fetch_assoc($consulta)) {
                $arreglo["data"][] = $consulta_vu;
            }
            return $arreglo;
        }
        return null; // O manejar el error de otra forma
    }

    public function registrarCategoria($categoria) {
        $sql = "CALL SP_REGISTRAR_CATEGORIA('$categoria')";
        if ($consulta = $this->conexion->conexion->query($sql)) {
            if ($row = mysqli_fetch_array($consulta)) {
                return trim($row[0]);
            }
        }
        return null; // O manejar el error de otra forma
    }

    public function modificarCategoria($id, $categoriaactual, $categorianuevo, $estatus) {
        $sql = "CALL SP_EDITAR_CATEGORIA('$id','$categoriaactual','$categorianuevo','$estatus')";
        if ($consulta = $this->conexion->conexion->query($sql)) {
            if ($row = mysqli_fetch_array($consulta)) {
                return trim($row[0]);
            }
        }
        return null; // O manejar el error de otra forma
    }
}

class Controlador_Categoria {
    private $modelo;

    public function __construct(Modelo_Categoria $modelo) {
        $this->modelo = $modelo;
    }

    public function listarCategoria() {
        $consulta = $this->modelo->listarCategoria();
        if ($consulta) {
            echo json_encode($consulta);
        } else {
            echo json_encode([
                "sEcho" => 1,
                "iTotalRecords" => "0",
                "iTotalDisplayRecords" => "0",
                "aaData" => []
            ]);
        }
    }

    public function registrarCategoria() {
        $categoria = htmlspecialchars($_POST['categoria'], ENT_QUOTES, 'UTF-8');
        $resultado = $this->modelo->registrarCategoria($categoria);
        echo $resultado;
    }

    public function modificarCategoria() {
        $id = htmlspecialchars($_POST['id'], ENT_QUOTES, 'UTF-8');
        $categoriaactual = htmlspecialchars($_POST['categoriaactual'], ENT_QUOTES, 'UTF-8');
        $categorianuevo = htmlspecialchars($_POST['categorianuevo'], ENT_QUOTES, 'UTF-8');
        $estatus = htmlspecialchars($_POST['estatus'], ENT_QUOTES, 'UTF-8');
        $resultado = $this->modelo->modificarCategoria($id, $categoriaactual, $categorianuevo, $estatus);
        echo $resultado;
    }
}

?>
