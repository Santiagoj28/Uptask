<?php
namespace Model;

class Usuario extends ActiveRecord{
    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id','nombre','email','password','token','confirmado'];
    
    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $token;
    public $confirmado;



    public function __construct($args = [])
     {
        $this->id =$args['id']??null;
        $this->nombre=$args['nombre']??'';
        $this->email=$args['email']??'';
        $this->password=$args['password']??'';
        $this->password_actual=$args['password_actual']??'';
        $this->password_nuevo=$args['password_nuevo']??'';

        $this->token=$args['token']??'';
        $this->confirmado=$args['confirmado']??0;
    }
    //validar login
    public function validarLogin(){
        if(!$this->email){
            self::$alertas['error'][]= 'El email es obligatorio';
        }
        else if(!filter_var($this->email,FILTER_VALIDATE_EMAIL)){
            self::$alertas['error'][]="Email no valido";
        }
        if(!$this->password){
            self::$alertas['error'][]= 'El password es obligatorio';
        }
        else if(strlen($this->password)<6){
            self::$alertas['error'][]='Minimo 6 caracteres';
        }

        return self::$alertas;

    }
    public  function nuevo_password(){
        if(!$this->password_actual){
            self::$alertas['error'][]='El password actual no puede ir vacio ';
        }
        if(!$this->password_nuevo){
            self::$alertas['error'][]='El nuevo password no puede ir vacio ';
        }
       elseif(strlen($this->password_nuevo)< 6){
            self::$alertas['error'][]='El password debe tener minimo 6 caracteres ';
        }
        return self::$alertas;
    }
    //validar cuentas nuevas
    public function validarNewCuenta()
    {
        if(!$this->nombre){
            self::$alertas['error'][]='Debes colocar tu nombre';
        }
        if(!$this->email){
            self::$alertas['error'][]='Debes colocar un Email';
        }
        if(!$this->password){
            self::$alertas['error'][]='El password es obligatorio';
        }
         else if(strlen($this->password)<6){
            self::$alertas['error'][]='Minimo 6 caracteres';
        }
        if($this->password !== $this->password2){
            self::$alertas['error'][]="Los passwords deben ser iguales";
        }
        return self::$alertas;
    }
    //comprobar password
    public function comprobar_password():bool{
        return password_verify($this->password_actual, $this->password);
    }

    //hashea password
    public  function hashPassword() :void{
      
        $this->password = password_hash($this->password,PASSWORD_BCRYPT);
    }

    //crear token unico
    public function crearToken() :void{
        $this->token = md5(uniqid());
    }
    
    //validar email
    public function validarEmail(){
            if(!$this->email){
                self::$alertas['error'][]="Debes colocar el email de tu cuenta";
            }
            else if(!filter_var($this->email,FILTER_VALIDATE_EMAIL)){
                self::$alertas['error'][]="Email no valido";
            }
            return self::$alertas;
    }

    //validar password
    public function validarPassword(){
        if(!$this->password){
            self::$alertas['error'][]='El password es obligatorio';
        }
         else if(strlen($this->password)<6){
            self::$alertas['error'][]='Minimo 6 caracteres';
        }
       else if($this->password !== $this->password2){
            self::$alertas['error'][]="Los passwords deben ser iguales";
        }
        return self::$alertas;
    }

    //validar perfil
    public function validar_perfil(){
        if(!$this->nombre){
            self::$alertas['error'][]='El nombre es obligatorio';
        }
        if(!$this->email){
            self::$alertas['error'][]='El email es obligatorio';
        }
        return self::$alertas;
    }

    public function comprobarPassword($password){
        $resultado = password_verify($password,$this->password);
        if(!$resultado){
            self::$alertas['error'][]='PASSWORD INCORRECTO';
        }else{
            return true;
        }
    }
}