<?php
    // Definición de la clase Conexion
    class Conexion{
        // Método estático para conectar a la base de datos
        public static function Conectar(){
            // Definición de constantes para la configuración de la conexión
            define('servidor', 'localhost'); // Nombre del servidor de la base de datos
            define('nombre_bd', 'articulosdb'); // Nombre de la base de datos
            define('usuario', 'root'); // Nombre de usuario de la base de datos
            define('password', ''); // Contraseña de la base de datos
            $opciones = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'); // Opciones de configuración de la conexión
			
            try{
                // Crear una instancia de la clase PDO para establecer la conexión
                $conexion = new PDO("mysql:host=".servidor."; dbname=".nombre_bd, usuario, password, $opciones);
                return $conexion; // Devolver la conexión establecida
            }catch (Exception $e){
                // En caso de error durante la conexión, mostrar un mensaje de error y finalizar el script
                die("El error de Conexión es: ". $e->getMessage());
            }
        }
    }
?>
