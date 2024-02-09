
let botonInsertar = document.getElementById('botonNuevaTarea');

//document.querySelector('.papelera')

botonInsertar.addEventListener('click', function () {
    //Muestro el preloader
        document.getElementById('preloaderInsertar').style.visibility='visible';

    //Envío datos mediante POST a insertar.php construyendo un FormData
    const datos = new FormData();
    datos.append('texto', document.getElementById('nuevaTarea').value);

    const options = {
        method: "POST",
        body: datos
    };

    fetch('index.php?accion=insertarTarea', options)
        .then(respuesta => {
            return respuesta.json();
        })
        .then(tarea => {
            // Crear los elementos necesarios para representar la tarea y sus acciones
            var id = tarea.id;
            var capaTarea = document.createElement('div');
            var capaTexto = document.createElement('div');
            var capaBotones = document.createElement('div');
            var iconoTick = document.createElement('i');
            var iconoBorrar = document.createElement('i');
            var iconoFoto = document.createElement('a');
            var iconoEditar = document.createElement('i');
            var imagenPreloader = document.createElement('img');
        
            
            // Configurar clases CSS y atributos para coincidir con la estructura HTML dada
            capaTarea.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
            capaTexto.innerHTML = tarea.texto;
            
            // Configurar el botón 'tickTarea'
            iconoTick.classList.add('fa-regular', 'fa-circle-check', 'btn', 'btn-success', 'btn-sm', 'me-1', 'iconoTickOff');
            iconoTick.setAttribute('data-bs-toggle', 'popover');
            iconoTick.setAttribute('data-bs-trigger', 'hover');
            iconoTick.setAttribute('data-bs-content', 'Tarea Realizada');
            iconoTick.setAttribute("data-idTarea", tarea.id);
            iconoTick.setAttribute("onclick", "ponerTick(this)");
            // Configurar el botón de borrar
            iconoBorrar.classList.add('fas', 'fa-trash', 'btn', 'btn-danger', 'btn-sm', 'me-1');
            iconoBorrar.setAttribute('data-idTarea', id);
            iconoBorrar.setAttribute('data-bs-toggle', 'popover');
            iconoBorrar.setAttribute('data-bs-trigger', 'hover');
            iconoBorrar.setAttribute('data-bs-content', 'Borrar Tarea');
        
            // Configurar el botón de editar
            iconoEditar.classList.add('fa-solid', 'fa-image', 'btn', 'btn-primary', 'btn-sm', 'me-1');
            iconoEditar.setAttribute('data-idTarea', id);
            iconoEditar.setAttribute('data-bs-toggle', 'popover');
            iconoEditar.setAttribute('data-bs-trigger', 'hover');
            iconoEditar.setAttribute('data-bs-content', 'Añadir una foto');
            
            iconoFoto.href = "index.php?accion=editarTarea&id=" + tarea.id;
            
            imagenPreloader.setAttribute('src','web/images/preloader.gif');
            imagenPreloader.classList.add('preloaderBorrar');
        
            // Añadir los elementos creados a sus contenedores correspondientes
            
            capaBotones.appendChild(iconoTick);
            tickOn = document.querySelectorAll('.iconoTickOn');
            tickOff = document.querySelectorAll('.iconoTickOff');
            capaBotones.appendChild(iconoBorrar);
            capaBotones.appendChild(iconoFoto);
            capaBotones.appendChild(iconoEditar);
            iconoFoto.appendChild(iconoEditar);
            capaTarea.appendChild(capaTexto);
            capaTarea.appendChild(capaBotones);
            capaBotones.appendChild(imagenPreloader);
        
            document.getElementById('tareas').appendChild(capaTarea);
        
            // Inicializar los popovers para los elementos recién añadidos
            new bootstrap.Popover(iconoTick);
            new bootstrap.Popover(iconoBorrar);
            new bootstrap.Popover(iconoEditar);
        
            // Añadir manejador de eventos al botón de borrar
            iconoBorrar.addEventListener('click', manejadorBorrar);
            // Limpiar el contenido del input para añadir nuevas tareas
            document.getElementById('nuevaTarea').value = '';
        })
        .finally(function(){
            //Ocultamos el preloader
            document.getElementById('preloaderInsertar').style.visibility='hidden';
        });
        

});


let papeleras = document.querySelectorAll('.fa-trash');
papeleras.forEach(papelera => {
     papelera.addEventListener('click',manejadorBorrar);
     
});


function manejadorBorrar() {
    var id = this.getAttribute('data-idTarea');
     //Mostramos preloader
     let preloader = this.parentElement.querySelector('img');
     preloader.style.visibility="visible";
     

    fetch('index.php?accion=borrarTarea&id=' + id)
        .then(datos => datos.json())
        .then(respuesta => {
            if (respuesta.respuesta == 'ok') {
                // Encuentra el icono y su elemento padre correspondiente
                var icono = document.querySelector(`i[data-idTarea="${id}"]`);
                
                // Antes de eliminar el elemento, destruye el popover asociado
                if (bootstrap && bootstrap.Popover) {
                    let popoverInstance = bootstrap.Popover.getInstance(icono);
                    if (popoverInstance) {
                        popoverInstance.dispose();
                    }
                }
                
                // Elimina el elemento padre del icono
                icono.parentElement.parentElement.remove();
            } else {
                alert("No se ha encontrado la tarea en el servidor");
                this.style.visibility = 'visible';
            }
        })
        
        .finally(function(){
            //Ocultamos preloader
            preloader.style.visibility="hidden";
        });
}
