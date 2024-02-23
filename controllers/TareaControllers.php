<?php 

namespace Controllers;

use Model\Proyecto;
use Model\Tareas;

class TareaControllers {
    public  static function index(){
        session_start();
        $proyectoID = $_GET['url'];
        if(!$proyectoID)header('location:/dashboard');

        $proyecto = Proyecto::where('url',$proyectoID);
        if(!$proyecto|| $proyecto->propietarioid !== $_SESSION['id'])header('location:/404');

        $tareas = Tareas::belongsTo('proyectoid',$proyecto->id);

        echo json_encode(['tareas'=> $tareas]);
        
        

    }
    public static function create(){
        if($_SERVER['REQUEST_METHOD']==='POST'){
            session_start();
            $proyectoid = $_POST['proyectoid'];
            $proyecto = Proyecto::where('url',$proyectoid);

            if(!$proyecto || $proyecto->propietarioid !== $_SESSION['id']){
                $respuesta = [
                    'tipo'=> 'error',
                    'mensaje'=> 'Hubo un error al agregar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }
            //todo bien instanciar y crear la tarea
            $tarea = new Tareas($_POST);
            $tarea->proyectoid = $proyecto->id;
            $resultado = $tarea->guardar();
            $respuesta = [
                'tipo'=> 'exito',
                'id'=> $resultado['id'],
                'mensaje'=> 'Tarea agregada correctamente',
                'proyectoid'=> $proyecto->id
            ];
            echo json_encode($respuesta);
            
           
        }

    }
    public static function update(){
        if($_SERVER['REQUEST_METHOD']==='POST'){
            session_start();
            //validar que el proyecto exista 
            $proyecto = Proyecto::where('url',$_POST['url']);

            if(!$proyecto || $proyecto->propietarioid !== $_SESSION['id']){
                $respuesta = [
                    'tipo'=> 'error',
                    'mensaje'=> 'Hubo un error al actualizar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }
            $tarea = new Tareas($_POST);
            $tarea->proyectoid = $proyecto->id;
            $resultado = $tarea->guardar();
            if($resultado){
                 $respuesta = [
                    'tipo'=> 'exito',
                    'id'=> $tarea->id,
                    'proyectoid'=> $proyecto->id,
                    'mensaje'=> 'Actualizado correctamente'
                ];
                echo json_encode(['respuesta'=> $respuesta ]);
               

            }
            
                      
        }

    }

    public static function delete(){
        if($_SERVER['REQUEST_METHOD']==='POST'){

            session_start();
            //validar que el proyecto exista 
            $proyecto = Proyecto::where('url',$_POST['proyectoid']);

            if(!$proyecto || $proyecto->propietarioid !== $_SESSION['id']){
                $respuesta = [
                    'tipo'=> 'error',
                    'mensaje'=> 'Hubo un error al actualizar la tarea'
                ];
                echo json_encode($respuesta);
                return;
            }

            $tarea = new Tareas($_POST);
            $resultado = $tarea->eliminar();

           

            echo json_encode(['resultado'=>$resultado]);
            
        }

    }
}