<?php 

require_once __DIR__ . '/../includes/app.php';

use Controllers\DashboardController;
use Controllers\LoginController;
use Controllers\TareaControllers;
use MVC\Router;
$router = new Router();

//Login
$router->get('/',[LoginController::class,'index']);
$router->post('/',[LoginController::class,'index']);
$router->get('/logout',[LoginController::class,'logout']);

//Crear
$router->get('/crear_cuenta',[LoginController::class,'crear']);
$router->post('/crear_cuenta',[LoginController::class,'crear']);

//Olvide password
$router->get('/olvide',[LoginController::class,'olvide']);
$router->post('/olvide',[LoginController::class,'olvide']);

//Colocar new password
$router->get('/restablecer_password',[LoginController::class,'restablecer']);
$router->post('/restablecer_password',[LoginController::class,'restablecer']);

//Confirmacion de cuenta
$router->get('/mensaje',[LoginController::class,'mensaje']);
$router->get('/confirmar',[LoginController::class,'confirmar']);

//ZONA PRIVAD
$router->get('/dashboard',[DashboardController::class,'index']);
$router->get('/crear-proyectos',[DashboardController::class,'crear']);
$router->post('/crear-proyectos',[DashboardController::class,'crear']);
$router->get('/perfil',[DashboardController::class,'perfil']);
$router->post('/perfil',[DashboardController::class,'perfil']);
$router->get('/proyecto',[DashboardController::class,'proyecto']);
$router->post('/cambiar_password',[DashboardController::class,'cambiar_password']);
$router->get('/cambiar_password',[DashboardController::class,'cambiar_password']);

//Api para las tareas
$router->get('/api/tareas',[TareaControllers::class,'index']);
$router->post('/api/tarea',[TareaControllers::class,'create']);
$router->post('/api/tarea/actualizar',[TareaControllers::class,'update']);
$router->post('/api/tarea/delete',[TareaControllers::class,'delete']);






// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();