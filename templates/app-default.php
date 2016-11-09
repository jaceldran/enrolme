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
		<title>ENROL ME!</title>
		<link rel="icon" href="<?php echo HOME ?>/img/sonimatec.ico" type="image/x-icon">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="<?php echo HOME ?>/css/style.css">
		<link rel="stylesheet" href="<?php echo HOME ?>/css/icomoon/style.css">
	</head>

	<body>

		<header>
					
			<a class="brand" href="<?php echo HOME ?>">
				ENROL ME! <span class="status">alfa</span>
			</a>

			<nav class="panel">
				<div class="header">					
					<span class="title">ENROL ME!</span>
					<a data-target="header nav.panel" 
						class="action close-panel icon-close"></a>					
				</div>
				<?php $this->navMain() ?>
			</nav>
			<a data-target="header nav.panel" 
				class="action open-panel icon-navicon large"></a>
		</header>

		<main>
			<?php $this->main() ?>
		</main>

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>		
		<script src="<?php echo HOME ?>/js/header.js"></script>
		<?php $this->javascript() ?>
	</body

</html>