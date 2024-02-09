<!DOCTYPE html>
<html>
<head>
	<title>Inicio</title>
	<link rel="stylesheet" type="text/css" href="slide navbar ../../web/css/estilosLogin.css">
  
<link href="https://fonts.googleapis.com/css2?family=Jost:wght@500&display=swap" rel="stylesheet">
</head>
<body>

	<div class="main">  	
		<input type="checkbox" id="chk" aria-hidden="true">
      <p style="align-self: center; width: 100%; text-align: center; color: red; font-size: 20px; position: absolute;"><?php imprimirMensaje();?></p>
			<div class="signup">
				<form action="index.php?accion=registrar" method="post">
					<label for="chk" aria-hidden="true">Registrarse</label>
					<input type="text" name="nombre" placeholder="Nombre de usuario" required="">
					<input type="email" name="email" placeholder="Email" required="">
					<input type="password" name="password" placeholder="Contraseña" required="">
					<button>Registrarse</button>
				</form>
			</div>

			<div class="login">
				<form action="index.php?accion=login" method="post">
					<label for="chk" aria-hidden="true">Iniciar Sesión</label>
					<input type="email" name="email" placeholder="Email" required="">
					<input type="password" name="password" placeholder="Contraseña" required="">
					<button>Iniciar Sesión</button>
        </form> 
			</div>       
	</div>
  
  
   
</body>
</html>