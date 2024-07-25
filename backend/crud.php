<?php
// Incluye el archivo de conexión para acceder a la clase Conexion
include_once 'conexion.php';
// Crea una instancia de la clase Conexion para establecer la conexión a la base de datos
$objeto = new Conexion();
$conexion = $objeto->Conectar();

// Decodifica los datos JSON recibidos del cliente
$_POST = json_decode(file_get_contents("php://input"), true);

// Función para manejar los permisos de CORS (Cross-Origin Resource Sharing)
function permisos() {  
  // Permite el acceso desde cualquier origen
  if (isset($_SERVER['HTTP_ORIGIN'])){
      header("Access-Control-Allow-Origin: *");
      // Permite los métodos HTTP GET, POST, PATCH, PUT, DELETE y OPTIONS
      header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
      // Permite ciertos encabezados en las peticiones
      header("Access-Control-Allow-Headers: Origin, Authorization, X-Requested-With, Content-Type, Accept");
      // Permite el envío de cookies en las solicitudes
      header('Access-Control-Allow-Credentials: true');      
  }  
  // Maneja las solicitudes OPTIONS (preflight)
  if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS'){
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))          
        header("Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS");
    if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
        header("Access-Control-Allow-Headers: Origin, Authorization, X-Requested-With, Content-Type, Accept");
    exit(0);
  }
}
// Ejecuta la función para manejar los permisos de CORS
permisos();

// Obtiene los datos enviados por el cliente
$opcion = (isset($_POST['opcion'])) ? $_POST['opcion'] : '';
$id = (isset($_POST['id'])) ? $_POST['id'] : '';
$descripcion = (isset($_POST['descripcion'])) ? $_POST['descripcion'] : '';
$precio = (isset($_POST['precio'])) ? $_POST['precio'] : '';
$stock = (isset($_POST['stock'])) ? $_POST['stock'] : '';

// Realiza operaciones CRUD según la opción recibida
switch($opcion){
	case 1:
        // Consulta todos los artículos de la base de datos
        $consulta = "SELECT id, descripcion, precio, stock FROM articulos";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();
        // Obtiene los datos de la consulta en formato de arreglo asociativo
        $data=$resultado->fetchAll(PDO::FETCH_ASSOC);
        break;
    case 2:
        // Inserta un nuevo artículo en la base de datos
        $consulta = "INSERT INTO articulos (descripcion, precio, stock) VALUES('$descripcion', '$precio', '$stock') ";
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();                
        break;
    case 3:
        // Actualiza un artículo existente en la base de datos
        $consulta = "UPDATE articulos SET descripcion='$descripcion', precio='$precio', stock='$stock' WHERE id='$id' ";		
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();                        
        $data=$resultado->fetchAll(PDO::FETCH_ASSOC);
        break;        
    case 4:
        // Elimina un artículo de la base de datos
        $consulta = "DELETE FROM articulos WHERE id='$id' ";		
        $resultado = $conexion->prepare($consulta);
        $resultado->execute();                           
        break;         
}
// Imprime los datos resultantes en formato JSON
print json_encode($data, JSON_UNESCAPED_UNICODE);
// Cierra la conexión a la base de datos
$conexion = NULL;
?>
