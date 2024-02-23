<?php
namespace Model;

class Proyecto extends ActiveRecord{
    protected static $tabla = 'proyectos';
    protected static $columnasDB = ['id','proyecto','url','propietarioid'];


     public $id;
     public $proyecto;
     public $url;
     public $propietarioid;

    public function __construct($args = []) 
    {
        $this->id = $args['id']??null;
        $this->proyecto = $args['proyecto']??'';
        $this->url = $args['url']??'';
        $this->propietarioid = $args['propietarioid']??'';
    }
    public function validarProyecto(){
        if(!$this->proyecto){
            self::$alertas['error'][]='El nombre del proyecto es obligatorio';
        }
        if(strlen($this->proyecto)>= 60){
            self::$alertas['error'][]='Maximo 50 caracteres';
        }
        return self::$alertas;
    }
}