<?php //Interfaz general del usuario
session_start();
if ($_GET['k'] == $_SESSION['SFT']['user_key']):
    require_once($_SESSION['SFT']['rootdir'].'/inc/fncsft.php');
    $_SESSION['SFT']['user_fch']=time();
    unset($_SESSION['SFT']['ced_pac']);
    unset($_SESSION['SFT']['nompac']);
    ?>
    <div class="row">
        <div class="span12 contbox" >
            <div class="box-header">
                <h2>Seleccionar Paciente</h2>
            </div>
            <div id="divsrhusr" class="box-cont" >
                <p>Puede buscar por el No. del documento, ó por el nombre ó apellido de forma parcial ó completa.</p>
                <form id="frmsrhusr" class="form-search">
                    <div class="input-append">
                        <input  id="iptsrhusr" type="text" class="span4 search-query" name='t'>
                        <button id="btnsrhusr" type="submit" class="btn btn-info" data-loading-text="Buscando"><i class="icon-search icon-white"></i> Buscar</button>
                    </div>
                </form>
            </div>

            <div id="divlstusr" class="box-cont hide"></div>

        </div>
    </div>
    <?
else:
    echo '<div class="alert alert-error" >Petición rechazada por el servidor. Contacte al administrador del sistema e infórmele de este error.</div>';
endif;
?>