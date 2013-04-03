<?php
session_start();
if ($_GET['k'] == $_SESSION['SFT']['user_key']){
    ?>
    <div class="navbar-inner">
        <div class="container">
            <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            </a>
            <a class="brand" href="./">SFT Unisalud</a>
            <div class="nav-collapse collapse">
            <ul class="nav">
                <li <?=($_GET['menu']=='paciente')?'class="active"':''?>><a href="./">Paciente</a></li>
                <li <?=($_GET['menu']=='historia')?'class="active"':''?>><a id="lnkhistoria" href="#">Historia</a></li>
            </ul>
            <ul class="nav pull-right">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$_SESSION['SFT']['user_name']?><b class="caret"></b></a>
                    <ul class="dropdown-menu">
                        <!--<li><a href="#">Mis Datos</a></li>
                        <li><a href="#">Cambiar Contraseña</a></li>
                        <li class="divider"></li>-->
                        <li><a href="logout.php">Cerrar Sesión</a></li>
                    </ul>
                </li>
            </ul>
            </div>
        </div>
    </div>
    <?

}
else {
  echo '<div class="alert alert-error" >Petición rechazada por el servidor. Contacte al administrador del sistema e infórmele de este error.</div>';
}