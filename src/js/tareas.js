
(function(){
    obtenerTareas();
    let tareas = [];
    let filtradas = [];
    //console.log(tareas)

    //boton para mostrar el model y agregar la tarea
    const nuevaTareaBn = document.querySelector('#agregar-tarea');
    nuevaTareaBn.addEventListener('click',function (){
        mostrarFormulario();
    });

    //FILTROS DE BUSQUEDA
    const filtros = document.querySelectorAll('#filtros input[type="radio"]')
    filtros.forEach(radio => {
        radio.addEventListener('input',filtrarTareas);
    })
    
    function filtrarTareas(e){
       const filtro = e.target.value;
       if( filtro !== ''){
        filtradas = tareas.filter(tarea => tarea.estado === filtro);
       }else{
        filtradas = [];
       }
       mostrarTareas();

    }
 

     //Obtener tareas
    async function obtenerTareas(){
        try {
            const id = obtenerProyectos()
            const url = `/api/tareas?url=${id}`;
            const resultado = await fetch(url);
            const respuesta = await resultado.json();
            
            tareas = respuesta.tareas
            mostrarTareas();
        } catch (error) {
            console.log(error);
            
        }
    }
    
    //Mostrar las tareas
    function mostrarTareas(){
        limpiarTareas();
        totalPendientes();
        totalCompletas();
 
        const arrayTareas = filtradas.length ? filtradas : tareas;

       if(arrayTareas.length === 0){
        const contenedorTareas = document.querySelector('#listado-tareas');

        const textoNotareas = document.createElement('LI');
        textoNotareas.textContent = 'No hay tareas';
        textoNotareas.classList.add('no-tareas');
        contenedorTareas.appendChild(textoNotareas);
        return;

       }
       const estados = {
         0 : 'Pendiente',
         1 : 'Completa'
       } 

       arrayTareas.forEach(tarea=>{
        const contenedorTarea = document.createElement('LI');
        contenedorTarea.dataset.tareaId = tarea.id;
        contenedorTarea.classList.add('tarea');

        const nombreTarea = document.createElement('P');
        nombreTarea.textContent = tarea.nombre;
        nombreTarea.ondblclick = function (){
            mostrarFormulario(true,{...tarea});
        }

        const opcionesDiv = document.createElement('DIV');
        opcionesDiv.classList.add('opciones');
        

        //botones
        const btnEstadoTarea = document.createElement('BUTTON');
        btnEstadoTarea.classList.add('estado-tarea');
        btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`)
        btnEstadoTarea.textContent = estados[tarea.estado];
        btnEstadoTarea.dataset.estadoTarea = tarea.estado;
        btnEstadoTarea.ondblclick = function() {
            cambiarEstado({...tarea});
        }

         const btnEliminarTarea = document.createElement('BUTTON');
         btnEliminarTarea.classList.add('eliminar-tarea');
         btnEliminarTarea.dataset.idTarea = tarea.id
         btnEliminarTarea.textContent = 'Eliminar';
         btnEliminarTarea.ondblclick = function(){
           confirmarEliminarTarea({...tarea});
         }

       
        
        opcionesDiv.appendChild(btnEstadoTarea);
        opcionesDiv.appendChild(btnEliminarTarea);

        contenedorTarea.appendChild(nombreTarea);
        contenedorTarea.appendChild(opcionesDiv);
       
        const listadoTareas = document.querySelector('#listado-tareas');
        listadoTareas.appendChild(contenedorTarea);
       
       })
    }

    //Total filtros
    function totalPendientes(){
        const totalPendientes = tareas.filter(tarea => tarea.estado === '0');
        const pendientesRadio = document.querySelector('#pendientes');
        if(totalPendientes.length === 0){
        pendientesRadio.disabled = true
        }else{
            pendientesRadio.disabled = false;
        }
    }
    function totalCompletas(){
        const totalcompletas = tareas.filter(tarea => tarea.estado === '1');
        const completasRadio = document.querySelector('#completadas');
        if(totalcompletas.length === 0){
        completasRadio.disabled = true
        }else{
            completasRadio.disabled = false;
        }
    }

    function mostrarFormulario(editar = false, tarea = {}){
       
       const modal = document.createElement('DIV');
       modal.classList.add('modal');
       modal.innerHTML = `
       <form class="formulario nueva-tarea">
            <legend>${editar ? 'Editar Tarea' : 'Añade una nueva tarea'}</legend>
            <div class="campo">
                <label for="">Tarea</label>
                <input
                type='text'
                placeholder='${editar ? 'Cambiar nombre de tarea' : 'Añadir tarea al proyecto actual'}'
                name="tarea"
                id="tarea"
                value="${tarea.nombre ? tarea.nombre : ''}"
                >
            </div>
            <div class="opciones">
                <input type="submit" class="submit-nueva-tarea" value="${tarea.nombre ? 'Actualizar tarea' : 'Añadir tarea'}">
                <button type="button" class="cerrar-modal">Cancelar</button>
                
                
            </div>
       </form> 
       `;
       setTimeout(() => {
        const formulario = document.querySelector('.formulario');
        formulario.classList.add('animar');
        
       }, 0);
       
       
       //delegation
       modal.addEventListener('click',function(e){
        e.preventDefault();
        if(e.target.classList.contains('cerrar-modal')){
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('cerrar');
            setTimeout(() => {
               
                modal.remove();
            }, 500);
        }
        if(e.target.classList.contains('submit-nueva-tarea')){
            const nombreTarea = document.querySelector('#tarea').value.trim();
            if(nombreTarea ===''){
                //mostrar alerta de error
                mostrarAlerta('El nombre de la tarea es obligatorio',
                'error',
                document.querySelector('.formulario legend'));
               return;
             
            }
            
            if(editar){
                tarea.nombre = nombreTarea;
                actualizarTarea(tarea);
            }else{
               agregarTarea(nombreTarea);
            }

        }
        
        }   )

      document.querySelector('.dashboard').appendChild(modal);
    }
    


    //mostrar la alerta
     function mostrarAlerta(mensaje,tipo,referencia){
        const alertaPrevia = document.querySelector('.alertas');
        if(alertaPrevia){
            alertaPrevia.remove();
        }

        //
        const alerta = document.createElement('DIV');
        alerta.classList.add('alertas',tipo);
        alerta.textContent = mensaje;
       referencia.parentElement.insertBefore(alerta,referencia);

      //eliminar la alerta despues de 5 segundos
      setTimeout(() => {
        alerta.remove();
      }, 5000);
     }

    //consulta el servidor para agregar una nueva tarea a el proyecto actual
      async function agregarTarea(tarea){
        //construir la peticion
        const datos = new FormData();
        datos.append('nombre',tarea);
        datos.append('proyectoid',obtenerProyectos())


        try {
            const url = `${location.origin}/api/tarea`;
            const respuesta = await fetch(url,{
                method: 'POST',
                body : datos
            });
            const resultado = await respuesta.json();
           //console.log(resultado);

            mostrarAlerta(resultado.mensaje,resultado.tipo,document.querySelector('.formulario legend'));
          if(resultado.tipo==='exito'){
            const modal = document.querySelector('.modal');
            setTimeout(() => {
                modal.remove();
            }, 3000);

            //agregar el objeto de tarea a el global de tareas
            const tareaObj = {
                id :String( resultado.id),
                nombre : tarea,
                estado : "0",
                proyectoid : resultado.proyectoid

            }
            //console.log(tareaObj);
            tareas = [...tareas,tareaObj];
            mostrarTareas();
           
            
          }
            
        } catch (error) {
            console.log(error);
            
        }
       

    }//fin del async 
    function cambiarEstado(tarea) {

    const nuevoEstado = tarea.estado === "1" ? "0" : "1";
    tarea.estado = nuevoEstado;
      //console.log(tarea);
      //console.log(tareas);
      actualizarTarea(tarea);
      
    }

    //ACTUALIZAR
     async function actualizarTarea(tarea){  
          
        const {estado, id,nombre,proyectoid} = tarea
        const datos = new FormData();
        datos.append('id',id);
        datos.append('nombre',nombre);
        datos.append('estado',estado);
        datos.append('url',obtenerProyectos());
        try {
            const url = `/api/tarea/actualizar`
            const respuesta = await fetch(url,{
                method : 'POST',
                body : datos
            });
            const resultado = await respuesta.json();

            //console.log(resultado);
            if(resultado.respuesta.tipo === 'exito'){
               Swal.fire(
                resultado.respuesta.mensaje,
                resultado.respuesta.mensaje,
                'success'
               );

               const modal = document.querySelector('.modal');
               if(modal){
                modal.remove();
               }
             
                    tareas = tareas.map(tareaMemoria=>{
                        if(tareaMemoria.id=== id){
                            tareaMemoria.estado = estado;
                            tareaMemoria.nombre = nombre;
                        }
                        return tareaMemoria;
                    });
                   mostrarTareas();
            }
            
        } catch (error) {
            console.log(error);
            
        }
    }

    //confirmacion ELIMINAR
     function confirmarEliminarTarea(tarea){
         
          Swal.fire({
          title: "Estas seguro de eliminar esta tarea?",
          text: "You won't be able to revert this!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#3085d6",
          cancelButtonColor: "#d33",
          confirmButtonText: "Si,eliminar",
        
    }).then((result) => {
        
          if (result.isConfirmed) {
            EliminarTarea(tarea);
           Swal.fire({
           title: "Eliminado!",
           text: "tu tarea ha sido eliminada correctamente.",
           icon: "success",
          }); 
          }
     });
   }
   

   //Eliminar tareas
   async function EliminarTarea(tarea){

    const {estado, id,nombre} = tarea;
    const datos = new FormData();
    datos.append('id',id);
    datos.append('nombre',nombre);
    datos.append('estado',estado);
    datos.append('proyectoid',obtenerProyectos());
   

    try {
        const url = '/api/tarea/delete'
        const respuesta = await fetch(url,{
            method: 'POST',
            body : datos
        });
        
        const resultado = await respuesta.json();
        if(resultado.resultado){
           
           
                tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== tarea.id);
                mostrarTareas();
        }
        
    } catch (error) {
        console.log(error);
        
    }
    
   }

    
   //OBTENER PROYECTOS
    function obtenerProyectos(){
        //leer lo que tengo en la url para identificar el proyecto
        const proyectoParams = new URLSearchParams(window.location.search);
        const proyecto = Object.fromEntries(proyectoParams.entries());
        return  proyecto.url;
        }

        function limpiarTareas(){
            const listadoTareas = document.querySelector('#listado-tareas');
            while (listadoTareas.firstChild){
                listadoTareas.removeChild(listadoTareas.firstChild);
            }
        }
    

})();