<!doctype html>
<html>
<!-- 
 Desarrollado por
                     _)                    |               
   __|   _ \   __ \   |  __ `__ \    _` |  __|   _ \   __| 
 \__ \  (   |  |   |  |  |   |   |  (   |  |     __/  (    
 ____/ \___/  _|  _| _| _|  _|  _| \__,_| \__| \___| \___| 

 ¿saber más? =>> http://sonimatec.com 
 ¿contactar? =>> hola@sonimatec.com
-->
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="<?php echo HOME ?>/src/css/base.css">
		<link rel="stylesheet" href="<?php echo HOME ?>/src/css/form.css">		
		<link rel="stylesheet" href="<?php echo HOME ?>/src/css/header-default.css">
		<link rel="stylesheet" href="<?php echo HOME ?>/src/css/skin-default.css">
		<link rel="stylesheet" href="<?php echo HOME ?>/src/css/icomoon/style.css">
	</head>

	<body>

		<header>

			<a class="brand" href="<?php echo HOME ?>">ENROL ME!</a>

			<nav class="panel">
				<div class="header">					
					<span class="title">ENROL ME!</span>
					<a data-target="header nav.panel" 
						class="action close-panel icon-close large"></a>					
				</div>
				<a class="action" href="<?php echo HOME ?>">
					<span class="icon-home"></span>
					<span>Home</span>
				</a>
				<a class="action" href="<?php echo HOME ?>/activities">
					<span class="icon-list-ul"></span>
					<span>Actividades</span>
				</a>
			</nav>

			<a data-target="header nav.panel" 
				class="action open-panel icon-navicon large"></a>

		</header>

		<main>
			<?php $this->main() ?>
		</main>

		<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js"></script>	
		<script src="<?php echo HOME ?>/src/js/header.js"></script>	
	</body

</html>