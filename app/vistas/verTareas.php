<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.4/css/all.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <title>Document</title>
    <link rel="stylesheet" href="web/css/estilosVerTarea.css">
</head>
 
<body>
    <div>
        <div class="header-flex-container">
            <img src="web/images/logo2.jpg" width="100px" height="100px" alt="">
            <h1>Bienvenido <?= Sesion::getUsuario()->getNombre() ?> a la página de tus tareas</h1>
            <a href="index.php?accion=logout"><i class="fa-solid fa-right-from-bracket logout"></i></a>
        </div>

        <main class="container">
            <?php
            imprimirMensaje();
            ?>
            <div class="list-group mt-3" id="tareas">
                <?php foreach ($tareas as $tarea) : ?>
                    <?php
                    $ticksDAO = new TicksDAO($conn);
                    $idTarea = $tarea->getId();

                    $idUsuario = Sesion::getUsuario()->getId();
                    $existeTick = $ticksDAO->existByIdUsuarioIdTarea($idUsuario, $idTarea);

                    ?>
                    <div class="list-group-item d-flex justify-content-between align-items-center <?php echo $existeTick ? 'color' : "" ?>">
                        <div><?php echo $existeTick ? '<del>' . $tarea->getTexto() . '</del>' : $tarea->getTexto() ?></div>
                        <div>
                            <?php if ($existeTick) : ?>
                                <i class="fa-circle-check btn btn-success btn-sm me-1 iconoTickOn fa-solid" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="Tarea Realizada" data-idTarea='<?= $tarea->getId() ?>' onclick="quitarTick(this)" aria-describedby="popover408664"></i>
                            <?php else :  ?>
                                <i class="fa-regular fa-circle-check btn btn-success btn-sm iconoTickOff" tabindex="0" role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="Tarea Realizada" data-idTarea='<?= $tarea->getId() ?>' onclick="ponerTick(this)"></i>
                            <?php endif ?>

                            <!-- <i class="fa-regular fa-circle-check btn btn-success btn-sm" tabindex="0" role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="Tarea Realizada"></i> -->
                            <i class="fas fa-trash btn btn-danger btn-sm" tabindex="0" role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="Borrar Tarea" data-idTarea="<?= $tarea->getId() ?>"></i>
                            <!-- <i class="fa-solid fa-image btn btn-primary btn-sm" tabindex="0" role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="Añadir una foto"></i> -->
                            <a href="index.php?accion=editarTarea&id=<?= $tarea->getId() ?>"><i class="fa-solid fa-image btn btn-primary btn-sm" tabindex="0" role="button" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-content="Añadir una foto" data-idTarea="<?= $tarea->getId() ?>"></i></a>
                            <img src="web/images/preloader.gif" class="preloaderBorrar">
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="mt-3 row mb-5" style="display: flex; align-items: center;">
                <div class="col-12 col-md-9 ">
                    <input type="text" name="texto" id="nuevaTarea" class="form-control me-3 texto " placeholder="Introduce la nueva tarea">
                </div>
                <div class="col-12 col-md-3 d-flex align-items-center ">
                    <button id="botonNuevaTarea" style=" color: #ffae00; background-color: #101820; border: 2px solid #ff7e00;  " class="btn w-100" onmouseover="this.style.color='#101820'; this.style.backgroundColor='#ffae00'; this.style.borderColor='#ff7e00';" onmouseout="this.style.color='#ffae00'; this.style.backgroundColor='#101820'; this.style.border='2px solid #ff7e00';" style="color: #ffae00; background-color: #101820; border: 2px solid #ff7e00;">Agregar Tarea</button>
                    <img src="web/images/preloader.gif" id="preloaderInsertar">
                </div>
            </div>
        </main>
    </div>
    <!-- Bootstrap 5 JS Bundle with Popper -->

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js" integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous"></script>

    <script>
        var popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
        var popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
    </script>
    <script src="web/js.js" type="text/javascript"></script>
    <script src="web/ticks.js" type="text/javascript"></script>
    <script>
        $(document).ready(function() {
            $('.tachar-btn').on('click', function() {
                var targetId = $(this).data('target'); // Obtiene el ID del <h1> a tachar
                $(targetId).toggleClass('tachado'); // Alterna la clase 'tachado' en el <h1>
                $(this).toggleClass('btn-activo'); // Alterna la clase 'btn-activo' en el botón
            });
        });
    </script>
</body>

</html>