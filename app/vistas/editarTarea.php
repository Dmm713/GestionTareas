<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="web/css/estilosEditar.css ">
</head>

<body>
    <div class="header-flex-container">
        <img src="web/images/logo2.jpg" width="100px" height="100px" alt="">
        <h1>Añadir Foto A La Tarea</h1>
        <a href="index.php?accion=verTareas"><i class="fa-solid fa-arrow-left atras"></i></a>
    </div>

    <?= $error ?>
    <form action="index.php?accion=editarTarea&id=<?= $idTarea ?>" method="post" data-idTarea="<?= $idTarea ?>" id="formularioEditar">
        <input type="text" name="texto" placeholder="Texto" readonly value="<?= $tarea->getTexto() ?>"><br>
        <div id="fotos">
            <div id="fotos2">
                <?php foreach ($fotos as $foto) : ?>
                    <div class="foto-container">
                        <img src="web/images/<?= $foto->getNombreArchivo() ?>" class="imagenTarea">
                        <button type="button" class="delete-btn">Borrar</button>
                    </div>
                <?php endforeach; ?>

            </div>
            <div id="addImage">+</div>
            <input type="file" style="display: none;" id="inputFileImage">
        </div>

        <input type="submit">
    </form>
    <script src="web/editarTarea.js"></script>
    <script>
       
    </script>
</body>

</html>