<?php //Muestra los detalles para una consulta, PRM o RAM
// sleep(100);
session_start();
if ($_GET['k'] == $_SESSION['SFT']['user_key']):

    require_once($_SESSION['SFT']['rootdir'].'/inc/fncsft.php');
    $_SESSION['SFT']['user_fch']=time();
    $link = conect_db();
    $hoy= date("Y-m-j");
    // $hoy ='2013-02-28';
    $target = mysql_real_escape_string($_GET['target'],$link);

    switch ($target) {
        case 'cslt': //Detalles de una Consulta dado su idcslt
                if (isset($_GET['idcslt']) && $_GET['idcslt'] != NULL):

                    $idcslt = mysql_real_escape_string($_GET['idcslt'],$link);
                    $ced_pac = $_SESSION['SFT']['ced_pac'];
                    $sql = "SELECT TBconsulta.idcslt as idcslt, TBconsulta.iduser, fch_cslt, motivo, remitido, anamnesis, COALESCE(intervencion,'') as 'inter_old', name, inter_med, inter_adm FROM TBconsulta 
                            LEFT JOIN TBremitido ON TBconsulta.idrmtd=TBremitido.idrmtd
                            LEFT JOIN TBintervcn_old ON TBconsulta.idcslt=TBintervcn_old.idcslt
                            LEFT JOIN TBuser ON TBconsulta.iduser=TBuser.iduser
                            WHERE TBconsulta.idcslt='$idcslt' AND ced_pac='$ced_pac' 
                            LIMIT 1";
                    $result = @mysql_query($sql,$link);

                    if(@mysql_num_rows($result)!=0):
                        $arrcslt = mysql_fetch_assoc($result);
                        foreach ($arrcslt as $key => $value) 
                            $arrcslt[$key]=htmlspecialchars($value);
                        ?>
                        <div class="row">
                            <div class="span6">
                                <div class="box-cont" style="padding-left: 0;">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#tabdatcslt" data-toggle="tab">Consulta</a></li>
                                        <li><a href="#tabintervn" data-toggle="tab">Intervenciones</a></li>
                                        <li><a href="#tabramcslt" data-toggle="tab">RAM</a></li>
                                        <li><a href="#tabprmcslt" data-toggle="tab">PRM</a></li>
                                        <!-- <li><a href="#tabsegmto" data-toggle="tab">Seguimiento</a></li> -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="span6">
                                <div class="box-cont" style="padding-left: 0;">
                                    <div class="tab-content">
                                        <div id="tabdatcslt" class="tab-pane active">
                                            <?if ($_SESSION['SFT']['user_tipo'] == 'admin' || ($arrcslt['iduser']==$_SESSION['SFT']['user_id'] && $arrcslt['fch_cslt']==$hoy)):?>
                                                <div class="btn-group pull-right">
                                                    <button id="btndelcslt" idcslt="<?=$idcslt?>" class='btn btn-mini btn-danger'  data-loading-text="Eliminando"><i class="icon-trash icon-white"></i> Eliminar</button>
                                                    <button id="btnedtcslt" idcslt="<?=$idcslt?>" class='btn btn-mini btn-warning' data-loading-text="<i class='icon-time icon-white'></i> Espere..."><i class="icon-pencil icon-white"></i> Editar</button>
                                                </div>
                                            <?endif?>
                                            <!-- <?='user_id:'.$_SESSION['SFT']['user_id'].' iduser:'.$arrcslt['iduser'].' Hoy:'.$hoy.' fch_cslt:'.$arrcslt['fch_cslt']?> -->
                                            <dl class="dl-horizontal"><dt>Fecha:</dt><dd><?=$arrcslt['fch_cslt']?></dd></dl>
                                            <dl class="dl-horizontal"><dt>Atendido por:</dt><dd><?=$arrcslt['name']?></dd></dl>
                                            <dl class="dl-horizontal"><dt>Remitido por:</dt><dd><?=$arrcslt['remitido']?></dd></dl>
                                            <dl class="dl-horizontal"><dt>Motivo:</dt><dd><pre class="text"><?=$arrcslt['motivo']?></pre></dd></dl>
                                            <dl class="dl-horizontal"><dt>Anamnesis:</dt><dd><pre class="text"><?=$arrcslt['anamnesis']?></pre></dd></dl>
                                            <dl class="dl-horizontal"><dt>Int. Médica:</dt><dd><?=$arrcslt['inter_med']?'Sí':'No'?></dd></dl>
                                            <dl class="dl-horizontal"><dt>Int. Administrativa:</dt><dd><?=$arrcslt['inter_adm']?'Sí':'No'?></dd></dl>
                                            <?if($arrcslt['inter_old']):?>
                                                <dl class="dl-horizontal"><dt>Int. General:</dt><dd><pre class="text"><?=$arrcslt['inter_old']?></pre></dd></dl>
                                            <?endif?>
                                        </div>
                                        <div id="tabintervn" class="tab-pane">
                                            <div id="accintcslt" class="accordion">
                                                <div class="accordion-group">
                                                    <?
                                                    $sqlinter = "SELECT * FROM TBintervcn WHERE idcslt='$idcslt' and tipoint='paciente' LIMIT 1";
                                                    $resinter = mysql_query($sqlinter,$link);
                                                    $newinter = 1;
                                                    if(@mysql_num_rows($resinter)!=0):
                                                        $newinter = 0;
                                                        $arrint = mysql_fetch_assoc($resinter);
                                                        foreach ($arrint as $key => $value) 
                                                            $arrint[$key]=htmlspecialchars($value);
                                                    endif;
                                                    ?>
                                                    <div class="accordion-heading">
                                                        <a href="#intpaciente" data-parent="#accintcslt" data-toggle="collapse" class="accordion-toggle collapsed">
                                                            Paciente
                                                            <div class="btn-group pull-right">
                                                                <?if ($_SESSION['SFT']['user_tipo'] == 'admin' || ($arrcslt['iduser']==$_SESSION['SFT']['user_id'] && $arrcslt['fch_cslt']==$hoy)):?>
                                                                    <?if($newinter):?>
                                                                        <button idcslt="<?=$idcslt?>" tipoint="paciente" class='btn btn-mini btn-success newintervcn' data-loading-text="<i class='icon-time icon-white'></i> Espere...">Crear</button>
                                                                    <?else:?>
                                                                        <button idintervcn="<?=$arrint[idintervcn]?>" class='btn btn-mini btn-danger delintervcn'  data-loading-text="Eliminando"><i class="icon-trash icon-white"></i> Eliminar</button>
                                                                        <button idintervcn="<?=$arrint[idintervcn]?>" class='btn btn-mini btn-warning edtintervcn' data-loading-text="<i class='icon-time icon-white'></i> Espere..."><i class="icon-pencil icon-white"></i> Editar</button>
                                                                    <?endif;?>
                                                                <?endif?>
                                                            </div>
                                                        </a>
                                                    </div>
                                                    <div class="accordion-body collapse" id="intpaciente" style="height: 0px;">
                                                        <div class="accordion-inner">
                                                            <?if($newinter):?>
                                                                <div class="alert alert-error"> No existe esta intervención.</div>
                                                            <?else:?>
                                                                <dl class="dl-horizontal"><dt>Fecha:</dt><dd><?=$arrint['fch_intervcn']?></dd></dl>
                                                                <dl class="dl-horizontal"><dt>Dirigido a:</dt><dd><?=$arrint['dirigido']?></dd></dl>
                                                                <dl class="dl-horizontal"><dt>Medio</dt>
                                                                    <dd>Verbal: <?=$arrint['med_verbal']?'Si':'No'?></dd>
                                                                    <dd>Físico: <?=$arrint['med_fisico']?'Si':'No'?></dd>
                                                                    <dd>e-mail: <?=$arrint['med_email']?'Si':'No'?></dd>
                                                                    <dd>Historia: <?=$arrint['med_historia']?'Si':'No'?></dd>
                                                                </dl>
                                                                <dl class="dl-horizontal"><dt>Concepto:</dt><dd><pre class="text"><?=$arrint['concepto']?></pre></dd></dl>
                                                                <!-- <dl class="dl-horizontal"><dt>Horario:</dt><dd><?=$arrint['horario']?></dd></dl> -->
                                                            <?endif?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <?if($arrcslt['inter_med']):?>
                                                    <div class="accordion-group">
                                                        <?
                                                        $sqlinter = "SELECT * FROM TBintervcn WHERE idcslt='$idcslt' and tipoint='medico' LIMIT 1";
                                                        $resinter = mysql_query($sqlinter,$link);
                                                        $newinter = 1;
                                                        if(@mysql_num_rows($resinter)!=0):
                                                            $newinter = 0;
                                                            $arrint = mysql_fetch_assoc($resinter);
                                                            foreach ($arrint as $key => $value) 
                                                                $arrint[$key]=htmlspecialchars($value);
                                                        endif;
                                                        ?>
                                                        <div class="accordion-heading">
                                                            <a href="#intmedico" data-parent="#accintcslt" data-toggle="collapse" class="accordion-toggle">
                                                                Médico
                                                                <div class="btn-group pull-right">
                                                                    <?if ($_SESSION['SFT']['user_tipo'] == 'admin' || ($arrcslt['iduser']==$_SESSION['SFT']['user_id'] && $arrcslt['fch_cslt']==$hoy)):?>
                                                                        <?if($newinter):?>
                                                                            <button idcslt="<?=$idcslt?>" tipoint="medico" class='btn btn-mini btn-success newintervcn' data-loading-text="<i class='icon-time icon-white'></i> Espere...">Crear</button>
                                                                        <?else:?>
                                                                            <button idintervcn="<?=$arrint[idintervcn]?>" class='btn btn-mini btn-danger delintervcn'  data-loading-text="Eliminando"><i class="icon-trash icon-white"></i> Eliminar</button>
                                                                            <button idintervcn="<?=$arrint[idintervcn]?>" class='btn btn-mini btn-warning edtintervcn' data-loading-text="<i class='icon-time icon-white'></i> Espere..."><i class="icon-pencil icon-white"></i> Editar</button>
                                                                        <?endif;?>
                                                                    <?endif?>
                                                                </div>
                                                            </a>
                                                        </div>
                                                        <div class="accordion-body collapse" id="intmedico">
                                                            <div class="accordion-inner">
                                                                <?if($newinter):?>
                                                                    <div class="alert alert-error"> No existe esta intervención.</div>
                                                                <?else:?>
                                                                    <dl class="dl-horizontal"><dt>Fecha:</dt><dd><?=$arrint['fch_intervcn']?></dd></dl>
                                                                    <dl class="dl-horizontal"><dt>Dirigido a:</dt><dd><?=$arrint['dirigido']?></dd></dl>
                                                                    <dl class="dl-horizontal"><dt>Medio</dt>
                                                                        <dd>Verbal: <?=$arrint['med_verbal']?'Si':'No'?></dd>
                                                                        <dd>Físico: <?=$arrint['med_fisico']?'Si':'No'?></dd>
                                                                        <dd>e-mail: <?=$arrint['med_email']?'Si':'No'?></dd>
                                                                        <dd>Historia: <?=$arrint['med_historia']?'Si':'No'?></dd>
                                                                    </dl>
                                                                    <dl class="dl-horizontal"><dt>Concepto:</dt><dd><pre class="text"><?=$arrint['concepto']?></pre></dd></dl>
                                                                    <!-- <dl class="dl-horizontal"><dt>Horario:</dt><dd><?=$arrint['horario']?></dd></dl> -->
                                                                <?endif?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?endif?>
                                                <?if($arrcslt['inter_adm']):?>
                                                    <div class="accordion-group">
                                                        <?
                                                        $sqlinter = "SELECT * FROM TBintervcn WHERE idcslt='$idcslt' and tipoint='admin' LIMIT 1";
                                                        $resinter = mysql_query($sqlinter,$link);
                                                        $newinter = 1;
                                                        if(@mysql_num_rows($resinter)!=0):
                                                            $newinter = 0;
                                                            $arrint = mysql_fetch_assoc($resinter);
                                                            foreach ($arrint as $key => $value) 
                                                                $arrint[$key]=htmlspecialchars($value);
                                                        endif;
                                                        ?>
                                                        <div class="accordion-heading">
                                                            <a href="#intadmin" data-parent="#accintcslt" data-toggle="collapse" class="accordion-toggle">
                                                                Administrativa
                                                                <div class="btn-group pull-right">
                                                                    <?if ($_SESSION['SFT']['user_tipo'] == 'admin' || ($arrcslt['iduser']==$_SESSION['SFT']['user_id'] && $arrcslt['fch_cslt']==$hoy)):?>
                                                                        <?if($newinter):?>
                                                                            <button idcslt="<?=$idcslt?>" tipoint="admin" class='btn btn-mini btn-success newintervcn' data-loading-text="<i class='icon-time icon-white'></i> Espere...">Crear</button>
                                                                        <?else:?>
                                                                            <button idintervcn="<?=$arrint[idintervcn]?>" class='btn btn-mini btn-danger delintervcn'  data-loading-text="Eliminando"><i class="icon-trash icon-white"></i> Eliminar</button>
                                                                            <button idintervcn="<?=$arrint[idintervcn]?>" class='btn btn-mini btn-warning edtintervcn' data-loading-text="<i class='icon-time icon-white'></i> Espere..."><i class="icon-pencil icon-white"></i> Editar</button>
                                                                        <?endif;?>
                                                                    <?endif?>
                                                                </div>
                                                            </a>
                                                        </div>
                                                        <div class="accordion-body collapse" id="intadmin">
                                                            <div class="accordion-inner">
                                                                <?if($newinter):?>
                                                                    <div class="alert alert-error"> No existe esta intervención.</div>
                                                                <?else:?>
                                                                    <dl class="dl-horizontal"><dt>Fecha:</dt><dd><?=$arrint['fch_intervcn']?></dd></dl>
                                                                    <dl class="dl-horizontal"><dt>Dirigido a:</dt><dd><?=$arrint['dirigido']?></dd></dl>
                                                                    <dl class="dl-horizontal"><dt>Medio</dt>
                                                                        <dd>Verbal: <?=$arrint['med_verbal']?'Si':'No'?></dd>
                                                                        <dd>Físico: <?=$arrint['med_fisico']?'Si':'No'?></dd>
                                                                        <dd>e-mail: <?=$arrint['med_email']?'Si':'No'?></dd>
                                                                        <dd>Historia: <?=$arrint['med_historia']?'Si':'No'?></dd>
                                                                    </dl>
                                                                    <dl class="dl-horizontal"><dt>Concepto:</dt><dd><pre class="text"><?=$arrint['concepto']?></pre></dd></dl>
                                                                    <!-- <dl class="dl-horizontal"><dt>Horario:</dt><dd><?=$arrint['horario']?></dd></dl> -->
                                                                <?endif?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?endif?>
                                            </div>
                                        </div>
                                        <div id="tabramcslt" class="tab-pane">
                                            <?if ($_SESSION['SFT']['user_tipo'] == 'admin' /*|| ($arrcslt['iduser']==$_SESSION['SFT']['user_id'] && $arrcslt['fch_cslt']==$hoy)*/):?>
                                                <div class="clearfix">
                                                    <button class="btn btn-success btn-small pull-right btnnewram" idcslt="<?=$idcslt?>" data-loading-text="<i class='icon-time icon-white'></i> Espere..."><i class="icon-folder-open icon-white"></i> Crear una RAM en la consulta seleccionada</button>
                                                </div>
                                            <?endif;
                                            // $sql = "SELECT idram, fch_cslt as Fecha, descram as Descripción, descmed as Medicamento
                                            //         FROM TBram, TBclsseve, TBmaestra, (SELECT `idcslt`, `fch_cslt` FROM `TBconsulta` WHERE `ced_pac`='$ced_pac') as TB1  
                                            //         WHERE TBram.idcslt=TB1.idcslt AND TBram.idclsseve=TBclsseve.idclsseve AND TBram.codunisalud=TBmaestra.codunisalud
                                            //         ORDER BY fch_cslt DESC";
                                            $sql = "SELECT idram, descram as Descripción, clsseve as Clase
                                                    FROM TBram, TBclsseve
                                                    WHERE idcslt='$idcslt' AND TBram.idclsseve=TBclsseve.idclsseve";
                                            //echo $sql;
                                            $result=@mysql_query($sql,$link);
                                            if(@mysql_num_rows($result)==0):
                                                ?>
                                                <div class="alert alert-error"> No hay RAM's para mostrar.</div>
                                                <?
                                            else:
                                                result2tb($result,'tbllstramcslt');
                                            endif;
                                            ?>
                                        </div>
                                        <div id="tabprmcslt" class="tab-pane">
                                            <?if ($_SESSION['SFT']['user_tipo'] == 'admin' /*|| ($arrcslt['iduser']==$_SESSION['SFT']['user_id'] && $arrcslt['fch_cslt']==$hoy)*/):?>
                                                <div class="clearfix">
                                                    <button class="btn btn-success btn-small pull-right btnnewprm" idcslt="<?=$idcslt?>" data-loading-text="<i class='icon-time icon-white'></i> Espere..."><i class="icon-folder-open icon-white"></i> Crear un PRM en la consulta seleccionada</button>
                                                </div>
                                            <?endif;
                                            // $sql = "SELECT idprm, fch_cslt as Fecha, desprm as Descripción, clsprm as Clasificación
                                            //         FROM TBprm, TBclsprm, (SELECT `idcslt`, `fch_cslt` FROM `TBconsulta` WHERE `ced_pac`='$ced_pac') as TB1  
                                            //         WHERE TBprm.idcslt=TB1.idcslt AND TBprm.idclsprm=TBclsprm.idclsprm
                                            //         ORDER BY fch_cslt DESC";
                                            $sql = "SELECT idprm, desprm as Descripción, IF(`stdprm`,'Abierto','Cerrado') as Estado
                                                    FROM TBprm 
                                                    WHERE idcslt='$idcslt'";
                                            //echo $sql;
                                            $result=@mysql_query($sql,$link);
                                            if(@mysql_num_rows($result)==0):
                                                echo '<div class="alert alert-error"> No hay PRM\'s para mostrar.</div>';
                                            else:
                                                result2tb($result,'tbllstprmcslt');
                                            endif;
                                            ?>
                                        </div>
                                        <div id="tabsegmto" class="tab-pane">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sequi atque quibusdam veritatis hic voluptates officiis! Et quisquam consequatur quia qui amet quas saepe animi mollitia! A sit fuga distinctio eum incidunt vel aliquid delectus doloribus. Fugit corporis expedita veniam eius illum officia facere dignissimos repudiandae blanditiis voluptatibus consectetur minima a autem vitae architecto nam error doloremque molestiae culpa dolorum quas at recusandae impedit quis dolor repellendus sunt nisi deserunt sint aliquam debitis placeat quaerat perspiciatis porro ipsam distinctio itaque quae animi vero dicta non quam. 
                                            Quo adipisci repellendus neque corporis ipsam fuga beatae! Aspernatur sint libero corporis ad unde totam possimus. Consectetur eos inventore dolorem. Repellendus nostrum nemo expedita odit temporibus recusandae maiores asperiores blanditiis reiciendis sunt magnam corporis porro deserunt explicabo iusto officiis autem nihil repellat voluptatum suscipit perspiciatis itaque nisi quidem molestiae dolore doloremque quaerat veritatis sequi neque deleniti. Molestiae voluptatum aut quibusdam doloremque deleniti sint maiores illo eos vitae reiciendis libero consectetur ipsam officia nobis autem pariatur quisquam fugit. Molestias nam cumque ipsa expedita maiores sapiente corrupti optio labore excepturi modi nisi commodi accusamus doloribus odio accusantium delectus quisquam quam nemo fuga quis dolores a suscipit! Pariatur sint quidem voluptate repudiandae cumque natus eos repellendus ea laboriosam.
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?
                    else:
                        echo '<div class="alert alert-error"><span class="label label-important">ERROR:</span> No se ha encontrado la consulta.</div>';
                        echo "\n<!--SQL: $sql-->";
                        echo "\n<!--Error: ".mysql_error()."-->";
                    endif;
                else:
                    echo '<div class="alert alert-error"><span class="label label-important">ERROR:</span> No se ha seleccionado ninguna consulta.</div>';
                endif;
                break;

        case 'ram': //Detalles de una ram dado su idram
                if (isset($_GET['idram']) && $_GET['idram'] != NULL):

                    $idram = mysql_real_escape_string($_GET['idram'],$link);

                    $sql = "SELECT TBconsulta.iduser as iduser, TBconsulta.fch_cslt as fch_cslt, name, clsseve, descseve2, descevolucion, clscausa, descmed, TBram.*
                            FROM TBram 
                            LEFT JOIN TBconsulta ON TBram.idcslt=TBconsulta.idcslt
                            LEFT JOIN TBuser ON TBconsulta.iduser=TBuser.iduser
                            LEFT JOIN TBclsseve ON TBram.idclsseve=TBclsseve.idclsseve
                            LEFT JOIN TBclsseve2 ON TBram.idclsseve2=TBclsseve2.idclsseve2
                            LEFT JOIN TBclsevol ON TBram.idevolucion=TBclsevol.idevolucion
                            LEFT JOIN TBclscausa ON TBram.idclscausa=TBclscausa.idclscausa
                            LEFT JOIN TBmaestra ON TBram.codunisalud=TBmaestra.codunisalud
                            WHERE TBram.idram='$idram'
                            LIMIT 1";
                    $result = @mysql_query($sql,$link);

                    if(@mysql_num_rows($result)!=0):
                        $arrram = mysql_fetch_assoc($result);
                        foreach ($arrram as $key => $value) 
                            $arrram[$key]=htmlspecialchars($value);
                        
                        if ($_SESSION['SFT']['user_tipo'] == 'admin' || ($arrram['iduser']==$_SESSION['SFT']['user_id'] && $arrram['fch_cslt']==$hoy)):?>
                            <div class="btn-group pull-right">
                                <button idram="<?=$idram?>" class='btn btn-mini btn-danger btndelram'  data-loading-text="Eliminando"><i class="icon-trash icon-white"></i> Eliminar</button>
                                <button idram="<?=$idram?>" class='btn btn-mini btn-warning btnedtram' data-loading-text="<i class='icon-time icon-white'></i> Espere..."><i class="icon-pencil icon-white"></i> Editar</button>
                            </div>
                        <?endif?>
                        <dl class="dl-horizontal"><dt>Fecha de la consulta:</dt><dd><?=$arrram['fch_cslt']?></dd></dl>
                        <dl class="dl-horizontal"><dt>Atendido por:</dt><dd><?=$arrram['name']?></dd></dl>
                        <dl class="dl-horizontal"><dt>Severidad:</dt><dd><?=$arrram['clsseve']?></dd></dl>
                        <dl class="dl-horizontal"><dt>Descripción Severidad:</dt><dd><?=$arrram['idclsseve2']!=6?$arrram['descseve2']:$arrram['infoaddseve']?></dd></dl>
                        <dl class="dl-horizontal"><dt>Causalidad:</dt><dd><?=$arrram['clscausa']?></dd></dl>
                        <dl class="dl-horizontal"><dt>Evolución:</dt><dd><?=$arrram['descevolucion']?></dd></dl>
                        <dl class="dl-horizontal"><dt>Inicio de la RAM:</dt><dd><?=$arrram['fchiniram']?></dd></dl>
                        
                        <h5>Descripción de la RAM:</h5>
                        <pre class="text"><?=$arrram['descram']?></pre>

                        <dl class="dl-horizontal"><dt>Med. Sospechoso:</dt><dd><?=$arrram['descmed']?></dd></dl>
                        <dl class="dl-horizontal"><dt>Fabricante:</dt><dd><?=$arrram['fabmed']?></dd></dl>
                        <dl class="dl-horizontal"><dt>Nombre Comercial:</dt><dd><?=$arrram['marcamed']?></dd></dl>
                        <dl class="dl-horizontal"><dt>Registro Sanitario:</dt><dd><?=$arrram['rsmed']?></dd></dl>
                        <dl class="dl-horizontal"><dt>Lote:</dt><dd><?=$arrram['lotemed']?></dd></dl>
                        <dl class="dl-horizontal"><dt>Fecha de Vencimiento:</dt><dd><?=$arrram['fchvenmed']?></dd></dl>

                        <br>
                        <h4>Si se sospecha de una reacción adversa a medicamento</h4>
                        <hr>

                        <br>¿El evento adverso apareció cuando se administró el medicamento sospechoso?<br>
                        <?=(0==$arrram['pre1ram'])?'No':(1==$arrram['pre1ram']?'Si':'No Sabe')?><br>

                        <br>¿La RAM mejoró al suspender o al administrar un antagonista específico?<br>
                        <?=(0==$arrram['pre2ram'])?'No':(1==$arrram['pre2ram']?'Si':'No Sabe')?><br>

                        <br>¿La RAM reapareció al readministrar el medicamento?<br>
                        <?=(0==$arrram['pre3ram'])?'No':(1==$arrram['pre3ram']?'Si':'No Sabe')?><br>

                        <br>¿Existen causas alternativas que pueden causar esta reacción (otros medicamentos o enfermedades)?<br>
                        <?=(0==$arrram['pre4ram'])?'No':(1==$arrram['pre4ram']?'Si':'No Sabe')?><br>

                        <br>¿Hay informes previos concluyentes sobre la RAM?<br>
                        <?=(0==$arrram['pre5ram'])?'No':(1==$arrram['pre5ram']?'Si':'No Sabe')?><br>

                        <br>
                        <h4>Si se sospecha de un fallo terapéutico</h4>
                        <hr>

                        <br>¿Existen reportes en la práctica médica de fallo terapéutico?<br>
                        <?=(0==$arrram['pre1fat'])?'No':(1==$arrram['pre1fat']?'Si':'No Sabe')?><br>

                        <br>¿El problema podría ser la marca utilizada, no el principio activo?<br>
                        <?=(0==$arrram['pre2fat'])?'No':(1==$arrram['pre2fat']?'Si':'No Sabe')?><br>

                        <br>¿La dosis y frecuencia son las correctas?<br>
                        <?=(0==$arrram['pre3fat'])?'No':(1==$arrram['pre3fat']?'Si':'No Sabe')?><br>

                        <br>¿La vía de administración es la adecuada?<br>
                        <?=(0==$arrram['pre4fat'])?'No':(1==$arrram['pre4fat']?'Si':'No Sabe')?><br>

                        <br>¿El paciente tiene adherencia con el tratamiento?<br>
                        <?=(0==$arrram['pre5fat'])?'No':(1==$arrram['pre5fat']?'Si':'No Sabe')?><br>

                        <br>¿Hubo problemas de disponibilidad con el medicamento?<br>
                        <?=(0==$arrram['pre6fat'])?'No':(1==$arrram['pre6fat']?'Si':'No Sabe')?><br>

                        <br>¿El fallo T. puede deberse a interacción medicamentosa o alimentaria?<br>
                        <?=(0==$arrram['pre7fat'])?'No':(1==$arrram['pre7fat']?'Si':'No Sabe')?><br>

                        <br>
                        <h4>Manejo del evento y desecenlace</h4>
                        <hr>

                        <br>¿El evento desapareció con tratamiento farmacológico?<br>
                        <?=($arrram['pre1eve'])?'Si':'No'?><br>

                        <h5>Suspensión</h5>

                        <br>¿El evento desapareció al suspender el medicamento?<br>
                        <?=(0==$arrram['pre1susp'])?'No':(1==$arrram['pre1susp']?'Si':'N/A')?><br>

                        <br>¿El evento desapareció o redujo su intensidad al reducir la dosis?<br>
                        <?=(0==$arrram['pre2susp'])?'No':(1==$arrram['pre2susp']?'Si':'N/A')?><br>

                        <h5>Re-exposición</h5>

                        <br>¿El evento reapareció al re-administrar al medicamento?<br>
                        <?=(0==$arrram['pre1reex'])?'No':(1==$arrram['pre1reex']?'Si':'N/A')?><br>

                        <br>¿El paciente ha presentado anteriormente reacción al medicamento?<br>
                        <?=(0==$arrram['pre2reex'])?'No':(1==$arrram['pre2reex']?'Si':'N/A')?><br>

                        <h5>Análisis de caso y evolución del paciente</h5>
                        <pre class="text"><?=$arrram['analisis']?></pre>

                        <h5>Otros diagnósticos y observaciones</h5>
                        <pre class="text"><?=$arrram['obs']?></pre>

                        <h5>Gestión</h5>
                        <pre class="text"><?=$arrram['gestion']?></pre>

                        <?
                    else:
                        echo '<div class="alert alert-error"><span class="label label-important">ERROR:</span> No se ha encontrado la RAM selecionada.</div>';
                        echo "\n<!--SQL: $sql-->";
                        echo "\n<!--Error: ".mysql_error()."-->";
                    endif;
                else:
                    echo '<div class="alert alert-error"><span class="label label-important">ERROR:</span> No se ha seleccionado ninguna RAM.</div>';
                endif;
                break;

        case 'prm': //Detalles de un PRM dado su idprm
                if (isset($_GET['idprm']) && $_GET['idprm'] != NULL):

                    $idprm = mysql_real_escape_string($_GET['idprm'],$link);
                    $sql = "SELECT TBprm.*, TBconsulta.iduser as iduser, fch_cslt, name, clsprm, clsprmotr, des_sega, des_segb, medinadecuado, infraDosificacion, neceTrataAdicional, medinnecesario
                            FROM TBprm 
                            LEFT JOIN TBconsulta ON TBprm.idcslt=TBconsulta.idcslt
                            LEFT JOIN TBuser ON TBconsulta.iduser=TBuser.iduser
                            LEFT JOIN TBclsprm ON TBprm.idclsprm = TBclsprm.idclsprm
                            LEFT JOIN TBclsprmotr ON TBprm.idclsprmotr = TBclsprmotr.idclsprmotr
                            LEFT JOIN TB_SEGA ON TBprm.idsega=TB_SEGA.idsega
                            LEFT JOIN TB_SEGB ON TBprm.idsegb=TB_SEGB.idsegb
                            LEFT JOIN TB_EM ON TBprm.idem=TB_EM.idem
                            LEFT JOIN TB_EI ON TBprm.idei=TB_EI.idei
                            LEFT JOIN TB_NN ON TBprm.idnn=TB_NN.idnn
                            LEFT JOIN TB_NMI ON TBprm.idnmi=TB_NMI.idnmi
                            WHERE TBprm.idprm='$idprm'
                            LIMIT 1";
                    $result = @mysql_query($sql,$link);

                    if(@mysql_num_rows($result)!=0):
                        $arrprm = mysql_fetch_assoc($result);
                        foreach ($arrprm as $key => $value) 
                            $arrprm[$key]=htmlspecialchars($value);
                        ?>
                        <div class="row">
                            <div class="span6">
                                <div class="box-cont" style="padding-left: 0;">
                                    <ul class="nav nav-tabs">
                                        <li class="active"><a href="#tabdatprm" data-toggle="tab">Detalles del PRM</a></li>
                                        <!-- <li><a href="#tabsegprm" data-toggle="tab">Seguimiento</a></li> -->
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="span6">
                                <div class="box-cont" style="padding-left: 0;">
                                    <div class="tab-content">
                                        <div id="tabdatprm" class="tab-pane active">
                                            <?if ($_SESSION['SFT']['user_tipo'] == 'admin' || ($arrprm['iduser']==$_SESSION['SFT']['user_id'] && $arrprm['fch_cslt']==$hoy)):?>
                                                <div class="btn-group pull-right">
                                                    <button idprm="<?=$idprm?>" class='btn btn-mini btn-danger btndelprm'  data-loading-text="Eliminando"><i class="icon-trash icon-white"></i> Eliminar</button>
                                                    <button idprm="<?=$idprm?>" class='btn btn-mini btn-warning btnedtprm' data-loading-text="<i class='icon-time icon-white'></i> Espere..."><i class="icon-pencil icon-white"></i> Editar</button>
                                                </div>
                                            <?endif?>
                                            <!-- <?='user_id:'.$_SESSION['SFT']['user_id'].' iduser:'.$arrprm['iduser'].' Hoy:'.$hoy.' fch_cslt:'.$arrprm['fch_cslt']?> -->
                                            <dl class="dl-horizontal"><dt>Fecha:</dt><dd><?=$arrprm['fch_cslt']?></dd></dl>
                                            <dl class="dl-horizontal"><dt>Atendido por:</dt><dd><?=$arrprm['name']?></dd></dl>
                                            <dl class="dl-horizontal"><dt>Estado:</dt><dd><?=$arrprm['stdprm']?'Abierto':'Cerrado'?></dd></dl>
                                            <dl class="dl-horizontal"><dt>Clasificación de PRM:</dt><dd><?=$arrprm['clsprm']?></dd></dl>
                                            
                                            <?if ($arrprm['idclsprm']==14):?>
                                                <dl class="dl-horizontal">
                                                    <dt>Subclasificación PRM:</dt><dd><?=$arrprm['clsprmotr']?></dd>
                                                    <?if ($arrprm['idclsprmotr']==7):?>
                                                        <dt>Cual:</dt><dd><?=$arrprm['desclsotr']?></dd>
                                                    <?endif?>
                                                </dl>
                                            <?endif?>

                                            <h5>Descripción del PRM:</h5>
                                            <pre class="text"><?=$arrprm['desprm']?></pre>

                                            <?if ($arrprm['needrnm']):?>
                                                <br>
                                                <h4>Descripción del RNM:</h4>
                                                <hr>

                                                <?if($arrprm['idsega']>1 || $arrprm['idsegb']>1):?>
                                                    <h4>Seguridad</h4>
                                                    <?if($arrprm['idsega']>1):?>
                                                        <dl class="dl-horizontal"><dt>Inseg. No Cuantitativa:</dt><dd><?=$arrprm['des_sega']?></dd></dl>
                                                    <?else:?>
                                                        <dl class="dl-horizontal"><dt>Inseg. Cuantitativa:</dt><dd><?=$arrprm['des_segb']?></dd></dl>
                                                    <?endif?>
                                                <?endif?>

                                                <?if($arrprm['idem']>1 || $arrprm['idei']>1):?>
                                                    <h4>Efectividad</h4>
                                                    <?if($arrprm['idem']>1):?>
                                                        <dl class="dl-horizontal"><dt>Med. Inadecuado:</dt><dd><?=$arrprm['medinadecuado']?></dd></dl>
                                                    <?else:?>
                                                        <dl class="dl-horizontal"><dt>Infradosificación:</dt><dd><?=$arrprm['infraDosificacion']?></dd></dl>
                                                    <?endif?>
                                                <?endif?>

                                                <?if($arrprm['idnn']>1 || $arrprm['idnmi']>1):?>
                                                    <h4>Necesidad</h4>
                                                    <?if($arrprm['idnn']>1):?>
                                                        <dl class="dl-horizontal"><dt>Nece. Trata. Adi.:</dt><dd><?=$arrprm['neceTrataAdicional']?></dd></dl>
                                                    <?else:?>
                                                        <dl class="dl-horizontal"><dt>Med. Innecesario:</dt><dd><?=$arrprm['medinnecesario']?></dd></dl>
                                                    <?endif?>
                                                <?endif?>
                                                <hr>
                                            <?endif?>

                                            <h5>Gestión:</h5>
                                            <pre class="text"><?=$arrprm['gestion']?></pre>                                            
                                        </div>
                                        
                                        <div id="tabsegprm" class="tab-pane">
                                            <?
                                            $ced_pac = $_SESSION['SFT']['ced_pac'];
                                            $iduser = $_SESSION['SFT']['user_id'];
                                            $result=mysql_query("SELECT idcslt FROM TBconsulta WHERE ced_pac='$ced_pac' AND fch_cslt=CURDATE() AND iduser='$iduser' LIMIT 1",$link);
                                            if(mysql_num_rows($result)!=0):
                                                $fl = mysql_fetch_assoc($result);
                                                $idcslthoy = $fl['idcslt'];
                                                ?>
                                                <div class="clearfix">
                                                    <button class="btn btn-success btn-small pull-right btnnewram" idprm="<?=$idprm?>" idcslt="<?=$idcslthoy?>" data-loading-text="<i class='icon-time icon-white'></i> Espere..."><i class="icon-folder-open icon-white"></i> Ingresar Nuevo Seguimiento</button>
                                                </div>
                                            <?endif;
                                            $sql = "SELECT idsegmto, fch_cslt as Fecha,  descsegmto as Detalles
                                                    FROM tabsegmto, TBconsulta
                                                    WHERE idprm='$idprm' AND tabsegmto.idcslt=TBconsulta.idcslt
                                                    ORDER BY fch_cslt DESC";
                                            //echo $sql;
                                            $result=@mysql_query($sql,$link);
                                            if(@mysql_num_rows($result)==0):
                                                ?>
                                                <div class="alert alert-error"> No hay Seguimiento para mostrar.</div>
                                                <?
                                            else:
                                                result2tb($result,'tbllstsegprm');
                                            endif;
                                            ?>
                                        </div>                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?
                    else:
                        echo '<div class="alert alert-error"><span class="label label-important">ERROR:</span> No se ha encontrado el PRM.</div>';
                        echo "\n<!--SQL: $sql-->";
                        echo "\n<!--Error: ".mysql_error()."-->";
                    endif;
                else:
                    echo '<div class="alert alert-error"><span class="label label-important">ERROR:</span> No se ha seleccionado ningun PRM.</div>';
                endif;
                break;
        
        default:
                echo '<div class="alert alert-error"><span class="label label-important">ERROR:</span> No se han podido generar los datos.</div>';
            break;
    }
    
    
else:
    echo '<div class="alert alert-error"><span class="label label-important">ERROR:</span> Petición rechazada por el servidor. Contacte al administrador del sistema e infórmele de este error.</div>';
endif;
?>