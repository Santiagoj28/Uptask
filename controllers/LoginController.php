<?php

namespace Controllers;

use Clases\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{
    public static function index(Router $router){ 
      $alertas = [];
        if($_SERVER['REQUEST_METHOD']==='POST'){

          $auth = new Usuario($_POST);

          $alertas = $auth->validarLogin();

          if(empty($alertas)){
            //verificar si el usuario existe
             $usuario = Usuario::where('email',$auth->email);
            
             if(!$usuario || !$usuario->confirmado){
              $alertas = Usuario::setAlerta('error','Usuario no existe o cuenta no confirmada');

             }else{
              unset($usuario->password2);
              if ($usuario->comprobarPassword($auth->password)){
                session_start();
                $_SESSION['id'] = $usuario->id;
                $_SESSION['nombre']=$usuario->nombre;
                $_SESSION['email']=$usuario->email;
                $_SESSION['login']=true;
                
                //redireccionar;
                header('location:/dashboard');
                  debuguear(debuguear($_SESSION));
              }

             } 
           
          }
        }
        $alertas = Usuario::getAlertas();
      $router->render('auth/login',[
        'titulo'=> '| Iniciar Sesion',
        'alertas'=> $alertas
      ]);
    }
    public static function logout(){
       
      session_start();
      $_SESSION = [];
      header('location:/');
    }

    public static function crear(Router $router){
      $alertas = [];
      $usuario = new Usuario();
      
        if($_SERVER['REQUEST_METHOD']==='POST'){
          $usuario->sincronizar($_POST);
          $alertas = $usuario->validarNewCuenta();
         
          if(empty($alertas)){
            $existeUsuario = Usuario::where('email',$usuario->email);
            if($existeUsuario){
              Usuario::setAlerta('error','El usuario ya existe');
              $alertas = Usuario::getAlertas();
            }
            else{
              //hashear password
              $usuario->hashPassword();
              //eliminar password 2 o elemento de un objecto
              unset($usuario->password2);
              //Generar token
              $usuario->crearToken();
              
              //enviar email
              $email = new Email($usuario->email,$usuario->nombre,$usuario->token);

              $email->enviarConfirmacion();
               //guardar
               $resultado =  $usuario->guardar();
              if($resultado){
                header('location:/mensaje');

              }
            }
          }
        }
        
        $router->render('auth/crear',[
            'titulo'=>' | Crear Cuenta',
            'alertas'=> $alertas,
            'usuario'=>$usuario
        ]);
    }

      public static function olvide(Router $router){
        $alertas = [];
        $mostrar = true;
        
        
        if($_SERVER['REQUEST_METHOD']==='POST'){

         $auth= new Usuario($_POST);
         $alertas = $auth->validarEmail();
        
         if(empty($alertas)){
          //buscar a el usuario
          $usuario = Usuario::where('email',$auth->email);
          if($usuario &&  $usuario->confirmado){
           
            //crear tokenn 
            $usuario->crearToken();
            unset($usuario->password2);
           
            $resultado =  $usuario->guardar();
             //enviar email
             $email = new Email($usuario->email,$usuario->nombre,$usuario->token);
             $email->restablecerPassword();

             //imprimir la alerta
            $alertas = Usuario::setAlerta('exito-confirmar','Hemos enviado un email con las instruciones');
            $mostrar = false;
            
          }else{
            $alertas = Usuario::setAlerta('error','Usuario no existe');
          }

         }
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide',[
          'titulo'=> '| Olvide mi password',
          'alertas'=> $alertas,
          'mostrar'=> $mostrar
        ]);
      }
      
      public static function restablecer(Router $router){
        $alertas = [];
        $token = s($_GET['token']);
        $mostrar = true ;

        if(!$token)header('location:/');

        //identificar usuario con el token  
        $usuario = Usuario::where('token',$token);

        if(empty($usuario)){
          $alertas = Usuario::setAlerta('error-max','Token no valido');
          $mostrar = false ;
        }
                    
        if($_SERVER['REQUEST_METHOD']==='POST'){
          //colocar nuevo password
          $usuario->sincronizar($_POST);

         $alertas = $usuario->validarPassword();

         if(empty($alertas)){
          //hashear el nuevo password
          $usuario->hashPassword();
          unset($usuario->password2);

          //Eliminar el token
          $usuario->token = null;

          //Guardar el usuario en la DB
          $resultado = $usuario->guardar();


          //Redireccionar
          if($resultado){
             header('location:/');
          }
         }   
        }
        $alertas = Usuario::getAlertas();
        $router->render('auth/restablecer',[
          'titulo'=> '| Restablecer Password',
          'alertas'=> $alertas,
          'mostrar'=> $mostrar
         
        ]);
      }
      public static function mensaje(Router $router){
        $router->render('auth/mensaje');
      }
      public static function confirmar(Router $router){
        $token =s( $_GET['token']);

        if(!$token)header('location:/');

        $usuario = Usuario::where('token',$token);
        if(empty($usuario)){
              ///mostrar mensaje de error
       $alertas = Usuario::setAlerta('error','El token no es valido');  
        }else{
          $usuario->confirmado ="1";
          $usuario->token = null;
          unset($usuario->password2);
          //guardar Usuario
          $usuario->guardar();
         $alertas =  Usuario::setAlerta('exito-confirmar','Cuenta comprobada exitosamente');
         }
        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar',[
          'alertas'=> $alertas
        ]);
      }
}