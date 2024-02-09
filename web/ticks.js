//let tickOn = document.querySelectorAll('.iconoTickOn');
// tickOn.forEach(favoritoOn=>{
//     favoritoOn.addEventListener('click', quitarTick);


// });
// let tickOff = document.querySelectorAll('.iconoTickOff');
// tickOff.forEach(tickOff =>{
//     tickOff.addEventListener('click',ponerTick);
// });

function ponerTick(xthis) {
    let idTarea = xthis.getAttribute('data-idTarea');
    fetch('index.php?accion=insertar_tick&id=' + idTarea)
        .then(datos => datos.json())
        .then(respuesta => {
            if (respuesta.respuesta == "ok") {
                xthis.classList.remove("iconoTickOff");
                xthis.classList.remove('fa-regular');
                xthis.classList.add('iconoTickOn');
                xthis.classList.add('fa-solid');
                xthis.getAttribute('onclick','ponerTick(this)').remove;
                xthis.setAttribute('onclick', 'quitarTick(this)');
                xthis.parentNode.parentNode.classList.add('color');  
                xthis.parentNode.parentNode.firstElementChild.innerHTML = `<del>${xthis.parentNode.parentNode.firstElementChild.innerHTML}</del>`;
            }


        })
}
function quitarTick(xthis) {
    let idTarea = xthis.getAttribute('data-idTarea');
    fetch('index.php?accion=borrar_tick&id=' + idTarea)
        .then(datos => datos.json())
        .then(respuesta => {
            if (respuesta.respuesta == "ok") {
                xthis.classList.remove('iconoTickOn');
                xthis.classList.remove('fa-solid');
                xthis.classList.add("iconoTickOff");
                xthis.classList.add('fa-regular');
                xthis.setAttribute('onclick', 'ponerTick(this)');
                xthis.parentNode.parentNode.classList.remove('color');  
                xthis.parentNode.parentNode.firstElementChild.innerHTML = `${xthis.parentNode.parentNode.firstElementChild.firstElementChild.innerHTML}`;
            }
           

        })
}