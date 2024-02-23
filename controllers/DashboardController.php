<?php

namespace Controllers;

use Model\Proyecto;
use Model\Usuario;
use MVC\Router;

class DashboardController{
    public static function index(Router $router){
        session_start();
         isAuth();
         $id = $_SESSION['id'];
         

         //proyecto que pertenece a el propietario id
         $proyectos = proyecto::belongsTo('propietarioid',$id);
        
        $router->render('dashboard/index',[
            'titulo' => 'Proyectos',
            'proyectos'=> $proyectos
        ]);
    }
    public static function crear(Router $router){
        
        session_start();
         isAuth();
         $alertas = [];
         if($_SERVER['REQUEST_METHOD']==='POST'){
           //instanciarlo
           $proyecto = new Proyecto($_POST);

           //validacion
           $alertas = $proyecto->validarProyecto();

           if(empty($alertas)){
            //generar url unica para cada usuario
            $hash = md5((uniqid()));
            $proyecto->url = $hash;

            //almacenar el creador del proyecto
            $proyecto->propietarioid = $_SESSION['id'];
            
            //guardar
            $proyecto->guardar();

            //redireccionar
            header('location:/proyecto?url='. $proyecto->url);
           }
         }
        $router->render('dashboard/crear',[
            'titulo' => 'Crear Proyectos',
            'alertas'=> $alertas
        ]);
    }
    public static function perfil(Router $router){
        session_start();
         isAuth();
         $alertas = [];
         $usuario = Usuario::find($_SESSION['id']);

         if($_SERVER['REQUEST_METHOD']==='POST'){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validar_perfil();

            if(empty($alertas)){
                //verificar que el email no exista
                $existeUsuario = Usuario::where('email',$usuario->email);
                if($existeUsuario && $existeUsuario->id !== $usuario->id){
                    Usuario::setAlerta('error','Email no valido,Ya pertenece a otra cuenta');
                    $alertas = $usuario->getAlertas();

                }else{
                      //guardar el usuario
                $usuario->guardar();

                Usuario::setAlerta('exito','Guardado Correctamente');
                $alertas = $usuario->getAlertas();
                
                //asignar nombre nuevo a la barra
                $_SESSION['nombre']=$usuario->nombre;

                }

              
            }

         }
         
        $router->render('dashboard/perfil',[
            'titulo' => 'Perfil',
            'usuario'=> $usuario,
            'alertas' => $alertas
        ]);
    }
    public static function proyecto(Router $router){
        session_start();
         isAuth();
         $alertas = [];
        
         $token = s($_GET['url']);
         if(!$token)header('location:/dashboard');
          
         //revisar que la persona que visita el proyecto sea la que lo creo
         $proyecto = Proyecto::where('url',$token);
         if($proyecto->propietarioid !== $_SESSION['id']){
            header('location:/dashboard');
         }

         
         
        $router->render('dashboard/proyecto',[
            'titulo' => $proyecto->proyecto,
            'alertas'=> $alertas
        ]);

    }
    public static function cambiar_password(Router $router){
        session_start();
        isAuth();
        $alertas = [];
        
        if($_SERVER['REQUEST_METHOD']==='POST'){
            $usuario = Usuario::find($_SESSION['id']);
            
            //sincronizar los datos del usuario
            $usuario->sincronizar($_POST);

            $alertas = $usuario->nuevo_password();

            if(empty($alertas)){
                $resultado = $usuario->comprobar_password();

                if($resultado){
                    
                    $usuario->password = $usuario->password_nuevo;

                    //eliminar propiedades no necesarias
                    unset($usuario->password_actual);
                    unset($usuario->password_nuevo);

                    //hashear password
                    $usuario->hashPassword();

                    $resultado = $usuario->guardar();

                    if($resultado){
                        Usuario::setAlerta('exito','Tu password ha sido cambiado exitosamente');
                        $alertas = $usuario->getAlertas();

                    }
                }else{
                    Usuario::setAlerta('error','Password incorrecto');
                    $alertas = $usuario->getAlertas();
                }

              
            }
           
           

        }
        $router->render('/dashboard/cambiar-password',[
            'titulo' => 'Cambiar password',
            'alertas'=>$alertas 
        ]);

    }
}