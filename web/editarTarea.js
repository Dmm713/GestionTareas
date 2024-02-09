let idTarea = document.getElementById('formularioEditar').getAttribute('data-idTarea');
//Para que se abra la ventana de seleccionar archivo al hacer click en el botón
let botonAddImage = document.getElementById('addImage');
botonAddImage.addEventListener('click', function () {
    document.getElementById('inputFileImage').click();
});

//Enviamos el archivo por AJAX cuando se modifique el campo input (cuando se seleccione un archivo)
let inputFileImage = document.getElementById('inputFileImage');
inputFileImage.addEventListener('change', function () {
    let formData = new FormData();
    formData.append('foto', inputFileImage.files[0]);

    fetch('index.php?accion=addImageTarea&idTarea=' + idTarea, {
        method: 'POST',
        body: formData
    })
        .then(datos => datos.json())
        .then(respuesta => {
            console.log(respuesta);
            let nuevoDiv = document.createElement("div");
            let nuevaFoto = document.createElement("img");
            let nuevoBoton = document.createElement("button");
            nuevoBoton.classList.add('delete-btn')
            nuevoBoton.setAttribute("type", "button")
            nuevoBoton.innerHTML = "Borrar";
            nuevaFoto.classList.add('imagenTarea');
            nuevoBoton.addEventListener('click', function () {
                eventoBorrar(this);

            })
            nuevaFoto.setAttribute("src", 'web/images/' + respuesta.nombreArchivo);

            nuevoDiv.appendChild(nuevaFoto);
            nuevoDiv.appendChild(nuevoBoton);

            document.getElementById('fotos2').append(nuevoDiv);
        })
});

// Espera a que el contenido de la página se haya cargado completamente
document.addEventListener('DOMContentLoaded', function () {
    // Selecciona todos los botones con la clase 'delete-btn'
    var deleteButtons = document.querySelectorAll('.delete-btn');

    // Agrega un evento de clic a cada botón
    deleteButtons.forEach(function (btn) {
        btn.addEventListener('click', function () {
            // Encuentra el elemento 'img' dentro del mismo contenedor que el botón
            eventoBorrar(this);

        });
    });
});
function borrarFoto(nombreFoto) {
    fetch('index.php?accion=borrarFoto&nombreFoto=' + nombreFoto)
        .then(datos => datos.json())
        .then(respuesta => {
            if (respuesta.respuesta == 'ok') {
                // Encuentra el icono y su elemento padre correspondiente
                var imgEliminar = document.querySelector(`[src='web/images/${nombreFoto}']`);
                console.log(imgEliminar)
                // Elimina el elemento padre del icono
                imgEliminar.parentElement.remove();
            } else {
                alert("No se ha encontrado la tarea en el servidor");

            }
        })
}

function eventoBorrar(xthis) {
    var img = xthis.previousElementSibling; // Asume que el img es justo el elemento anterior al botón

    // Recoge el contenido de 'src' del elemento 'img'
    var imgSrc = img.getAttribute('src');

    // Aquí puedes hacer lo que necesites con imgSrc, por ejemplo, mostrarlo en consola
    console.log(imgSrc);
    var partesImagen = imgSrc.split('/');
    borrarFoto(partesImagen[2]);
}