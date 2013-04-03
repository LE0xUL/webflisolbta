<?php //Muestra los detalles para una consulta, PRM o RAM
// sleep(10);
session_start();
if ($_GET['k'] == $_SESSION['SFT']['user_key']):

    require_once($_SESSION['SFT']['rootdir'].'/inc/fncsft.php');
    $_SESSION['SFT']['user_fch']=time();
    $link = conect_db();
    $ced_pac = $_SESSION['SFT']['ced_pac'];
    $nompac  = $_SESSION['SFT']['nompac'];
    $target = mysql_real_escape_string($_GET['target'],$link);
    $acedt = mysql_real_escape_string($_GET['ac'],$link); //edt||new
    $acedt = $acedt=='edt'?1:0; //1:edt 0:new

    switch ($target) {
        case 'cslt': //Modal para crear o editar una Consulta dado su idcslt
                if ($acedt && isset($_GET['idcslt']) && $_GET['idcslt'] != NULL){
                    $idcslt  = mysql_real_escape_string($_GET['idcslt'],$link);
                    $rescslt = mysql_query("SELECT * FROM TBconsulta WHERE idcslt='$idcslt' LIMIT 1",$link);
                    $arrcslt = mysql_fetch_assoc($rescslt);
                    foreach ($arrcslt as $key => $value) 
                        $arrcslt[$key]=htmlspecialchars($value);
                }
                ?>
                <form id="frmnewedtcslt" class="form-horizontal">
                    <div id="modnewedtcslt" class="modal fade in hide" aria-hidden="false" aria-labelledby="edtnewcslt" role="dialog" tabindex="-1" style="display: block;">

                        <div class="modal-header">
                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                            <h3 id="edtnewcslt"><?=$acedt?'Editar Consulta':'Crear Consulta'?></h3>
                        </div>

                        <div class="modal-body">
                            <?if($_SESSION['SFT']['user_tipo']=='admin'):?>
                                <div class="control-group">
                                    <label class="control-label" for="iptfch_cslt">Fecha de la Consulta</label>
                                    <div class="controls">
                                        <div class="input-append">
                                            <input id="iptfch_cslt" name="fch_cslt" type="text" class="span2" placeholder="yyyy-mm-dd" data-date-format="yyyy-mm-dd" value="<?=$acedt?$arrcslt[fch_cslt]:''?>">
                                            <span class="add-on"><i class="icon-calendar"></i></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="sltiduser">Atendido por:</label>
                                    <div class="controls">
                                        <select id="sltiduser" name="iduser" class="input-xlarge">
                                            <?
                                            $result = mysql_query("SELECT * FROM TBuser",$link);
                                            while ($fl=mysql_fetch_assoc($result)){
                                                echo '<option value="'.$fl[iduser].'"';
                                                if ($acedt && $arrcslt[iduser]==$fl[iduser])
                                                    echo ' selected';
                                                echo '>'.$fl[name]."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            <?endif?>

                            <div class="control-group">
                                <label class="control-label" for="txtmotivo">Motivo Consulta</label>
                                <div class="controls">
                                    <textarea class="input-xlarge" id="txtmotivo" name="motivo" rows="5"><?=$acedt?$arrcslt[motivo]:''?></textarea>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="sltidrmtd">Remitido por:</label>
                                <div class="controls">
                                    <select id="sltidrmtd" name="idrmtd" class="input-xlarge">
                                        <?
                                        $result = mysql_query("SELECT * FROM TBremitido",$link);
                                        while ($fl=mysql_fetch_assoc($result)){
                                            echo '<option value="'.$fl[idrmtd].'"';
                                            if ($acedt && $arrcslt[idrmtd]==$fl[idrmtd])
                                                echo ' selected';
                                            echo '>'.$fl[remitido]."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="txtanamnesis">ANAMNESIS</label>
                                <div class="controls">
                                    <textarea class="input-xlarge" id="txtanamnesis" name="anamnesis" rows="5"><?=$acedt?$arrcslt[anamnesis]:''?></textarea>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="sltinter_med">Necesita intervención médica?</label>
                                <div class="controls">
                                    <select id="sltinter_med" name="inter_med" style="width: auto">
                                        <option value='1' <?=($acedt && 1==$arrcslt[inter_med])?'selected="selected"':''?>>Sí</option>
                                        <option value='0' <?=($acedt && 0==$arrcslt[inter_med])?'selected="selected"':''?>>No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="sltinter_adm">Necesita intervención administrativa?</label>
                                <div class="controls">
                                    <select id="sltinter_adm" name="inter_adm" style="width: auto">
                                        <option value='1' <?=($acedt && 1==$arrcslt[inter_adm])?'selected="selected"':''?>>Sí</option>
                                        <option value='0' <?=($acedt && 0==$arrcslt[inter_adm])?'selected="selected"':''?>>No</option>
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" name="idcslt" value="<?=$acedt?$idcslt:'0'?>" />
                            <input type="hidden" name="ced_pac" value="<?=$ced_pac?>" />
                            <input type="hidden" name="accion" value="<?=$acedt?'editar':'nuevo'?>" />

                        </div>

                        <div class="modal-footer form-actions">
                            <button class="btn" data-dismiss="modal" aria-hidden="true"><i class='icon-remove'></i> Cancelar</button>
                            <!-- <button class="btn btn-danger" data-loading-text="Eliminando">Eliminar</button> -->
                            <button type="submit" class="btn btn-primary" data-loading-text="Guardando"><i class='icon-ok icon-white'></i> Guardar</button>
                        </div>
                    </div>
                </form>
                <?
                break;

        case 'intervcn': //Modal para crear o editar una intervención de paciente
                if ($acedt && isset($_GET['idintervcn']) && $_GET['idintervcn'] != NULL)://editar
                    $idintervcn  = mysql_real_escape_string($_GET['idintervcn'],$link);
                    $resint = @mysql_query("SELECT * FROM TBintervcn WHERE idintervcn='$idintervcn' LIMIT 1",$link);
                    $arrint = @mysql_fetch_assoc($resint);
                    foreach ($arrint as $key => $value) 
                        $arrint[$key]=htmlspecialchars($value);
                endif;

                $idcslt  = $acedt?$arrint[idcslt]:mysql_real_escape_string($_GET['idcslt'],$link);
                $tipoint = $acedt?$arrint[tipoint]:mysql_real_escape_string($_GET['tipoint'],$link);
                
                ?>
                <form id="frmnewedtint" class="form-horizontal">
                    <div id="modnewedtint" class="modal fade in hide" aria-hidden="false" aria-labelledby="edtnewintpac" role="dialog" tabindex="-1" style="display: block;">

                        <div class="modal-header">
                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                            <h3 id="edtnewintpac"><?=$acedt?'Editar Intervención':'Crear Intervención'?></h3>
                        </div>

                        <div class="modal-body">
                            <div class="control-group">
                                <label class="control-label" for="iptfch_intervcn">Fecha de la Intervención</label>
                                <div class="controls">
                                    <div class="input-append">
                                        <input id="iptfch_intervcn" name="fch_intervcn" type="text" class="span2" placeholder="yyyy-mm-dd" data-date-format="yyyy-mm-dd" value="<?=$acedt?$arrint[fch_intervcn]:''?>">
                                        <span class="add-on"><i class="icon-calendar"></i></span>
                                    </div>
                                    <span class="help-block"><small>En un futuro esta entrada se invalidará y el sistema agregará por defecto la fecha del día en que se crea</small></span>
                                </div>
                            </div>   

                            <div class="control-group">
                                <label class="control-label" for="iptdirigido">Dirigido a:</label>
                                <div class="controls">
                                    <?if($tipoint != 'paciente'):?>
                                        <input class="input-xlarge" id="iptdirigido" name="dirigido" value="<?=$acedt?$arrint[dirigido]:''?>">
                                    <?else:?>
                                        <input class="input-xlarge uneditable-input" id="iptdirigido" name="dirigido" value="<?=$nompac?>">
                                    <?endif?>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" >Medio:</label>
                                <div class="controls">
                                    <label class="checkbox">
                                        <input type="checkbox" name="med_verbal" value="1" <?=($acedt && $arrint[med_verbal]==1)?'checked':''?>> Verbal
                                    </label>
                                    <label class="checkbox">
                                        <input type="checkbox" name="med_fisico" value="1" <?=($acedt && $arrint[med_fisico]==1)?'checked':''?>> Físico
                                    </label>
                                    <label class="checkbox">
                                        <input type="checkbox" name="med_email" value="1" <?=($acedt && $arrint[med_email]==1)?'checked':''?>> e-mail
                                    </label>
                                    <label class="checkbox">
                                        <input type="checkbox" name="med_historia" value="1" <?=($acedt && $arrint[med_historia]==1)?'checked':''?>> Historia Clínica
                                    </label>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="txtconcepto">Concepto:</label>
                                <div class="controls">
                                    <textarea class="input-xlarge" id="txtconcepto" name="concepto" rows="5"><?=$acedt?$arrint[concepto]:''?></textarea>
                                </div>
                            </div>
                            
                            <input type="hidden" name="idintervcn" value="<?=$acedt?$idintervcn:'0'?>" />
                            <input type="hidden" name="idcslt"  value="<?=$idcslt?>" />
                            <input type="hidden" name="tipoint" value="<?=$tipoint?>" />
                            <input type="hidden" name="accion"  value="<?=$acedt?'editar':'nuevo'?>" />

                        </div>

                        <div class="modal-footer form-actions">
                            <button class="btn" data-dismiss="modal" aria-hidden="true"><i class='icon-remove'></i> Cancelar</button>
                            <!-- <button class="btn btn-danger" data-loading-text="Eliminando">Eliminar</button> -->
                            <button type="submit" class="btn btn-primary" data-loading-text="Guardando"><i class='icon-ok icon-white'></i> Guardar</button>
                        </div>
                    </div>
                </form>
                <?
                break;

        case 'ram': //Modal para crear o editar una RAM dado su idram
                if ($acedt && isset($_GET['idram']) && $_GET['idram'] != NULL){
                    $idram  = mysql_real_escape_string($_GET['idram'],$link);
                    $resram = mysql_query("SELECT * FROM TBram WHERE idram='$idram' LIMIT 1",$link);
                    $arrram = mysql_fetch_assoc($resram);
                    foreach ($arrram as $key => $value) 
                        $arrram[$key]=htmlspecialchars($value);
                }

                $idcslt  = $acedt?$arrram[idcslt]:mysql_real_escape_string($_GET['idcslt'],$link);
                ?>
                <form id="frmnewedtram" class="form-horizontal">
                    <div id="modnewedtram" class="modal fade in hide" aria-hidden="false" aria-labelledby="edtnewram" role="dialog" tabindex="-1" style="display: block;">

                        <div class="modal-header">
                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                            <h3 id="edtnewram"><?=$acedt?'Editar RAM':'Crear RAM'?></h3>
                        </div>

                        <div class="modal-body">

                            <h4>Información Básica</h4>
                            <hr>
                            
                            <div class="control-group">
                                <label class="control-label" for="sltidclsseve">Severidad:</label>
                                <div class="controls">
                                    <select id="sltidclsseve" name="idclsseve" class="input-xlarge">
                                        <?
                                        $result = mysql_query("SELECT * FROM TBclsseve",$link);
                                        while ($fl=mysql_fetch_assoc($result)){
                                            echo '<option value="'.$fl[idclsseve].'"';
                                            if ($acedt && $arrram[idclsseve]==$fl[idclsseve])
                                                echo ' selected';
                                            echo '>'.$fl[clsseve]."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="sltidclsseve2">Descripción Severidad:</label>
                                <div class="controls">
                                    <select id="sltidclsseve2" name="idclsseve2" class="input-xlarge">
                                        <?
                                        $result = mysql_query("SELECT * FROM TBclsseve2",$link);
                                        while ($fl=mysql_fetch_assoc($result)){
                                            echo '<option value="'.$fl[idclsseve2].'"';
                                            if ($acedt && $arrram[idclsseve2]==$fl[idclsseve2])
                                                echo ' selected';
                                            echo '>'.$fl[descseve2]."</option>";
                                        }
                                        if($acedt && $arrram[idclsseve2]==6):
                                        ?>
                                            <input type='text' class='input-xlarge' name='infoaddseve' value='<?=$arrram[infoaddseve]?>'>
                                        <?else:?>
                                            <input type='text' class='input-xlarge hide' name='infoaddseve'>
                                        <?endif?>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="sltidclscausa">Causalidad:</label>
                                <div class="controls">
                                    <select id="sltidclscausa" name="idclscausa" class="input-xlarge">
                                        <?
                                        $result = mysql_query("SELECT * FROM TBclscausa",$link);
                                        while ($fl=mysql_fetch_assoc($result)){
                                            echo '<option value="'.$fl[idclscausa].'"';
                                            if ($acedt && $arrram[idclscausa]==$fl[idclscausa])
                                                echo ' selected';
                                            echo '>'.$fl[clscausa]."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="sltidevolucion">Evolución:</label>
                                <div class="controls">
                                    <select id="sltidevolucion" name="idevolucion" class="input-xlarge">
                                        <?
                                        $result = mysql_query("SELECT * FROM TBclsevol",$link);
                                        while ($fl=mysql_fetch_assoc($result)){
                                            echo '<option value="'.$fl[idevolucion].'"';
                                            if ($acedt && $arrram[idevolucion]==$fl[idevolucion])
                                                echo ' selected';
                                            echo '>'.$fl[descevolucion]."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="iptfchiniram">Fecha de inicio de la RAM</label>
                                <div class="controls">
                                    <div class="input-append">
                                        <input id="iptfchiniram" name="fchiniram" type="text" class="span2" placeholder="yyyy-mm-dd" data-date-format="yyyy-mm-dd" value="<?=$acedt?$arrram[fchiniram]:''?>">
                                        <span class="add-on"><i class="icon-calendar"></i></span>
                                    </div>
                                </div>
                            </div>   

                            <div class="control-group">
                                <label class="control-label" for="txtdescram">Descripción de la RAM</label>
                                <textarea class="input-block-level" id="txtdescram" name="descram" rows="5"><?=$acedt?$arrram[descram]:''?></textarea>
                            </div>

                            <br>
                            <h4>Información del Medicamento</h4>
                            <hr>

                            <div class="control-group">
                                <label class="control-label" for="sltcodunisalud">Medicamento Sospechoso:</label>
                                <div class="controls">
                                    <select id="sltcodunisalud" name="codunisalud" class="input-xlarge">
                                        <?
                                        $result = mysql_query("SELECT * FROM TBmaestra ORDER BY descmed",$link);
                                        while ($fl=mysql_fetch_assoc($result)){
                                            echo '<option value="'.$fl[codunisalud].'"';
                                            if ($acedt && $arrram[codunisalud]==$fl[codunisalud])
                                                echo ' selected';
                                            echo '>'.$fl[descmed]."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="iptfabmed">Fabricante:</label>
                                <div class="controls">
                                    <input id="iptfabmed" name="fabmed" type="text" class="input-xlarge" value="<?=$acedt?$arrram[fabmed]:''?>">
                                </div>
                            </div>  

                            <div class="control-group">
                                <label class="control-label" for="iptmarcamed">Nombre Comercial:</label>
                                <div class="controls">
                                    <input id="iptmarcamed" name="marcamed" type="text" class="input-xlarge" value="<?=$acedt?$arrram[marcamed]:''?>">
                                </div>
                            </div>  

                            <div class="control-group">
                                <label class="control-label" for="iptrsmed">Registro Sanitario:</label>
                                <div class="controls">
                                    <input id="iptrsmed" name="rsmed" type="text" class="input-xlarge" value="<?=$acedt?$arrram[rsmed]:''?>">
                                </div>
                            </div>  

                            <div class="control-group">
                                <label class="control-label" for="iptlotemed">Lote:</label>
                                <div class="controls">
                                    <input id="iptlotemed" name="lotemed" type="text" class="input-xlarge" value="<?=$acedt?$arrram[lotemed]:''?>">
                                </div>
                            </div>  

                            <div class="control-group">
                                <label class="control-label" for="iptfchvenmed">Fecha de Vencimiento:</label>
                                <div class="controls">
                                    <div class="input-append">
                                        <input id="iptfchvenmed" name="fchvenmed" type="text" class="span2" placeholder="yyyy-mm-dd" data-date-format="yyyy-mm-dd" value="<?=$acedt?$arrram[fchvenmed]:''?>">
                                        <span class="add-on"><i class="icon-calendar"></i></span>
                                    </div>
                                </div>
                            </div> 

                            <br>
                            <h4>Si se sospecha de una reacción adversa a medicamento</h4>
                            <hr>

                            <div class="control-group">
                                ¿El evento adverso apareció cuando se administró el medicamento sospechoso?<br>
                                <select name="pre1ram" style="width: auto">
                                    <option value='0' <?=($acedt && 0==$arrram[pre1ram])?'selected':''?>>No</option>
                                    <option value='1' <?=($acedt && 1==$arrram[pre1ram])?'selected':''?>>Sí</option>
                                    <option value='2' <?=($acedt && 2==$arrram[pre1ram])?'selected':''?>>No Sabe</option>
                                </select>
                            </div>

                            <div class="control-group">
                                ¿La RAM mejoró al suspender o al administrar un antagonista específico?<br>
                                <select name="pre2ram" style="width: auto">
                                    <option value='0' <?=($acedt && 0==$arrram[pre2ram])?'selected':''?>>No</option>
                                    <option value='1' <?=($acedt && 1==$arrram[pre2ram])?'selected':''?>>Sí</option>
                                    <option value='2' <?=($acedt && 2==$arrram[pre2ram])?'selected':''?>>No Sabe</option>
                                </select>
                            </div>

                            <div class="control-group">
                                ¿La RAM reapareció al readministrar el medicamento?<br>
                                <select name="pre3ram" style="width: auto">
                                    <option value='0' <?=($acedt && 0==$arrram[pre3ram])?'selected':''?>>No</option>
                                    <option value='1' <?=($acedt && 1==$arrram[pre3ram])?'selected':''?>>Sí</option>
                                    <option value='2' <?=($acedt && 2==$arrram[pre3ram])?'selected':''?>>No Sabe</option>
                                </select>
                            </div>

                            <div class="control-group">
                                ¿Existen causas alternativas que pueden causar esta reacción (otros medicamentos o enfermedades)?<br>
                                <select name="pre4ram" style="width: auto">
                                    <option value='0' <?=($acedt && 0==$arrram[pre4ram])?'selected':''?>>No</option>
                                    <option value='1' <?=($acedt && 1==$arrram[pre4ram])?'selected':''?>>Sí</option>
                                    <option value='2' <?=($acedt && 2==$arrram[pre4ram])?'selected':''?>>No Sabe</option>
                                </select>
                            </div>

                            <div class="control-group">
                                ¿Hay informes previos concluyentes sobre la RAM?<br>
                                <select name="pre5ram" style="width: auto">
                                    <option value='0' <?=($acedt && 0==$arrram[pre5ram])?'selected':''?>>No</option>
                                    <option value='1' <?=($acedt && 1==$arrram[pre5ram])?'selected':''?>>Sí</option>
                                    <option value='2' <?=($acedt && 2==$arrram[pre5ram])?'selected':''?>>No Sabe</option>
                                </select>
                            </div>


                            <br>
                            <h4>Si se sospecha de un fallo terapéutico</h4>
                            <hr>

                            <div class="control-group">
                                ¿Existen reportes en la práctica médica de fallo terapéutico?<br>
                                <select name="pre1fat" style="width: auto">
                                    <option value='0' <?=($acedt && 0==$arrram[pre1fat])?'selected':''?>>No</option>
                                    <option value='1' <?=($acedt && 1==$arrram[pre1fat])?'selected':''?>>Sí</option>
                                    <option value='2' <?=($acedt && 2==$arrram[pre1fat])?'selected':''?>>No Sabe</option>
                                </select>
                            </div>

                            <div class="control-group">
                                ¿El problema podría ser la marca utilizada, no el principio activo?<br>
                                <select name="pre2fat" style="width: auto">
                                    <option value='0' <?=($acedt && 0==$arrram[pre2fat])?'selected':''?>>No</option>
                                    <option value='1' <?=($acedt && 1==$arrram[pre2fat])?'selected':''?>>Sí</option>
                                    <option value='2' <?=($acedt && 2==$arrram[pre2fat])?'selected':''?>>No Sabe</option>
                                </select>
                            </div>

                            <div class="control-group">
                                ¿La dosis y frecuencia son las correctas?<br>
                                <select name="pre3fat" style="width: auto">
                                    <option value='0' <?=($acedt && 0==$arrram[pre3fat])?'selected':''?>>No</option>
                                    <option value='1' <?=($acedt && 1==$arrram[pre3fat])?'selected':''?>>Sí</option>
                                    <option value='2' <?=($acedt && 2==$arrram[pre3fat])?'selected':''?>>No Sabe</option>
                                </select>
                            </div>

                            <div class="control-group">
                                ¿La vía de administración es la adecuada?<br>
                                <select name="pre4fat" style="width: auto">
                                    <option value='0' <?=($acedt && 0==$arrram[pre4fat])?'selected':''?>>No</option>
                                    <option value='1' <?=($acedt && 1==$arrram[pre4fat])?'selected':''?>>Sí</option>
                                    <option value='2' <?=($acedt && 2==$arrram[pre4fat])?'selected':''?>>No Sabe</option>
                                </select>
                            </div>

                            <div class="control-group">
                                ¿El paciente tiene adherencia con el tratamiento?<br>
                                <select name="pre5fat" style="width: auto">
                                    <option value='0' <?=($acedt && 0==$arrram[pre5fat])?'selected':''?>>No</option>
                                    <option value='1' <?=($acedt && 1==$arrram[pre5fat])?'selected':''?>>Sí</option>
                                    <option value='2' <?=($acedt && 2==$arrram[pre5fat])?'selected':''?>>No Sabe</option>
                                </select>
                            </div>

                            <div class="control-group">
                                ¿Hubo problemas de disponibilidad con el medicamento?<br>
                                <select name="pre6fat" style="width: auto">
                                    <option value='0' <?=($acedt && 0==$arrram[pre6fat])?'selected':''?>>No</option>
                                    <option value='1' <?=($acedt && 1==$arrram[pre6fat])?'selected':''?>>Sí</option>
                                    <option value='2' <?=($acedt && 2==$arrram[pre6fat])?'selected':''?>>No Sabe</option>
                                </select>
                            </div>

                            <div class="control-group">
                                ¿El fallo T. puede deberse a interacción medicamentosa o alimentaria?<br>
                                <select name="pre7fat" style="width: auto">
                                    <option value='0' <?=($acedt && 0==$arrram[pre7fat])?'selected':''?>>No</option>
                                    <option value='1' <?=($acedt && 1==$arrram[pre7fat])?'selected':''?>>Sí</option>
                                    <option value='2' <?=($acedt && 2==$arrram[pre7fat])?'selected':''?>>No Sabe</option>
                                </select>
                            </div>
    
                            <br>
                            <h4>Manejo del evento y desecenlace</h4>
                            <hr>

                            <div class="control-group">
                                ¿El evento desapareció con tratamiento farmacológico?<br>
                                <select name="pre1eve" style="width: auto">
                                    <option value='0' <?=($acedt && 0==$arrram[pre1eve])?'selected':''?>>No</option>
                                    <option value='1' <?=($acedt && 1==$arrram[pre1eve])?'selected':''?>>Sí</option>
                                </select>
                            </div>

                            <h5>Suspensión</h5>

                            <div class="control-group">
                                ¿El evento desapareció al suspender el medicamento?<br>
                                <select name="pre1susp" style="width: auto">
                                    <option value='0' <?=($acedt && 0==$arrram[pre1susp])?'selected':''?>>No</option>
                                    <option value='1' <?=($acedt && 1==$arrram[pre1susp])?'selected':''?>>Sí</option>
                                    <option value='2' <?=($acedt && 2==$arrram[pre1susp])?'selected':''?>>N/A</option>
                                </select>
                            </div>

                            <div class="control-group">
                                ¿El evento desapareció o redujo su intensidad al reducir la dosis?<br>
                                <select name="pre2susp" style="width: auto">
                                    <option value='0' <?=($acedt && 0==$arrram[pre2susp])?'selected':''?>>No</option>
                                    <option value='1' <?=($acedt && 1==$arrram[pre2susp])?'selected':''?>>Sí</option>
                                    <option value='2' <?=($acedt && 2==$arrram[pre2susp])?'selected':''?>>N/A</option>
                                </select>
                            </div>

                            <h5>Re-exposición</h5>

                            <div class="control-group">
                                ¿El evento reapareció al re-administrar al medicamento?<br>
                                <select name="pre1reex" style="width: auto">
                                    <option value='0' <?=($acedt && 0==$arrram[pre1reex])?'selected':''?>>No</option>
                                    <option value='1' <?=($acedt && 1==$arrram[pre1reex])?'selected':''?>>Sí</option>
                                    <option value='2' <?=($acedt && 2==$arrram[pre1reex])?'selected':''?>>N/A</option>
                                </select>
                            </div>

                            <div class="control-group">
                                ¿El paciente ha presentado anteriormente reacción al medicamento?<br>
                                <select name="pre2reex" style="width: auto">
                                    <option value='0' <?=($acedt && 0==$arrram[pre2reex])?'selected':''?>>No</option>
                                    <option value='1' <?=($acedt && 1==$arrram[pre2reex])?'selected':''?>>Sí</option>
                                    <option value='2' <?=($acedt && 2==$arrram[pre2reex])?'selected':''?>>N/A</option>
                                </select>
                            </div>

                            
                            <h5>Análisis de caso y evolución del paciente</h5>

                            <div class="control-group">
                                <textarea class="input-block-level" name="analisis" rows="5"><?=$acedt?$arrram[analisis]:''?></textarea>
                            </div>

                            <h5>Otros diagnósticos y observaciones <small>(escriba información adicional que considere pertinente)</small></h5>

                            <div class="control-group">
                                <textarea class="input-block-level" name="obs" rows="5"><?=$acedt?$arrram[obs]:''?></textarea>
                            </div>

                            <h5>Gestión</h5>

                            <div class="control-group">
                                <textarea class="input-block-level" name="gestion" rows="5"><?=$acedt?$arrram[gestion]:''?></textarea>
                            </div>


                            <input type="hidden" name="idram" value="<?=$acedt?$arrram[idram]:'0'?>" />
                            <input type="hidden" name="idcslt" value="<?=$idcslt?>" />
                            <input type="hidden" name="accion" value="<?=$acedt?'editar':'nuevo'?>" />

                        </div>

                        <div class="modal-footer form-actions">
                            <button class="btn" data-dismiss="modal" aria-hidden="true"><i class='icon-remove'></i> Cancelar</button>
                            <!-- <button class="btn btn-danger" data-loading-text="Eliminando">Eliminar</button> -->
                            <button type="submit" class="btn btn-primary" data-loading-text="Guardando"><i class='icon-ok icon-white'></i> Guardar</button>
                        </div>
                    </div>
                </form>
                <?
                break;

        case 'prm': //Modal para crear o editar una intervención de paciente
                if ($acedt && isset($_GET['idprm']) && $_GET['idprm'] != NULL)://editar
                    $idprm  = mysql_real_escape_string($_GET['idprm'],$link);
                    $resprm = @mysql_query("SELECT * FROM TBprm WHERE idprm='$idprm' LIMIT 1",$link);
                    $arrprm = @mysql_fetch_assoc($resprm);
                    foreach ($arrprm as $key => $value) 
                        $arrprm[$key]=htmlspecialchars($value);
                endif;

                $idcslt  = $acedt?$arrprm[idcslt]:mysql_real_escape_string($_GET['idcslt'],$link);
                
                ?>
                <form id="frmnewedtprm" class="form-horizontal">
                    <div id="modnewedtprm" class="modal fade in hide" aria-hidden="false" aria-labelledby="edtnewprm" role="dialog" tabindex="-1" style="display: block;">

                        <div class="modal-header">
                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                            <h3 id="edtnewprm"><?=$acedt?'Editar PRM':'Crear PRM'?></h3>
                        </div>

                        <div class="modal-body">

                            <div class="control-group">
                                <label class="control-label" for="sltidclsprm">Clasificación de PRM:</label>
                                <div class="controls">
                                    <select id="sltidclsprm" name="idclsprm" class="input-xlarge">
                                        <?
                                        $result = mysql_query("SELECT * FROM TBclsprm ORDER BY clsprm",$link);
                                        while ($fl=mysql_fetch_assoc($result)){
                                            echo '<option value="'.$fl[idclsprm].'"';
                                            if ($acedt && $arrprm[idclsprm]==$fl[idclsprm])
                                                echo ' selected';
                                            echo '>'.$fl[clsprm]."</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div id="divsltidclsprmotr" class="control-group <?=($acedt && $arrprm[idclsprm]==14)?'':'hide'?>">
                                <label class="control-label" for="sltidclsprmotr">Subclasificación de PRM:</label>
                                <div class="controls">
                                    <select id="sltidclsprmotr" name="idclsprmotr" class="input-xlarge">
                                        <?
                                        $result = mysql_query("SELECT * FROM TBclsprmotr ORDER BY clsprmotr",$link);
                                        while ($fl=mysql_fetch_assoc($result)){
                                            echo '<option value="'.$fl[idclsprmotr].'"';
                                            if ($acedt && $arrprm[idclsprmotr]==$fl[idclsprmotr])
                                                echo ' selected';
                                            echo '>'.$fl[clsprmotr]."</option>";
                                        }
                                        if($acedt && $arrprm[idclsprmotr]==7):
                                        ?>
                                            <input type='text' class='input-xlarge' name='desclsotr' value='<?=$arrprm[desclsotr]?>'>
                                        <?else:?>
                                            <input type='text' class='input-xlarge hide' name='desclsotr' placeholder="Cual?">
                                        <?endif?>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="txtdesprm">Descripción del PRM:</label>
                                <div class="controls">
                                    <textarea class="input-xlarge" id="txtdesprm" name="desprm" rows="5"><?=$acedt?$arrprm[desprm]:''?></textarea>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="sltneedrnm">Crear RNM?</label>
                                <div class="controls">
                                    <select id="sltneedrnm" name="needrnm" style="width: auto">
                                        <option value='1' <?=($acedt && 1==$arrprm[needrnm])?'selected="selected"':''?>>Sí</option>
                                        <option value='0' <?=($acedt && 0==$arrprm[needrnm])?'selected="selected"':''?>>No</option>
                                    </select>
                                </div>
                            </div>

                            <div id="divsltrnm" <?=($acedt && !$arrprm[needrnm])?'class="hide"':''?>>
                                <hr>
                                <h4>Seguridad</h4>
                                <br />
                                <div class="control-group">
                                    <label class="control-label" for="sltidsega">Inseguridad No Cuantitativa</label>
                                    <div class="controls">
                                        <select id="sltidsega" name="idsega" class="input-xlarge">
                                            <?
                                            $result = mysql_query("SELECT * FROM TB_SEGA",$link);
                                            while ($fl=mysql_fetch_assoc($result)){
                                                echo '<option value="'.$fl[idsega].'"';
                                                if ($acedt && $arrprm[idsega]==$fl[idsega])
                                                    echo ' selected';
                                                echo '>'.$fl[des_sega]."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="sltidsegb">Inseguridad Cuantitativa</label>
                                    <div class="controls">
                                        <select id="sltidsegb" name="idsegb" class="input-xlarge">
                                            <?
                                            $result = mysql_query("SELECT * FROM TB_SEGB",$link);
                                            while ($fl=mysql_fetch_assoc($result)){
                                                echo '<option value="'.$fl[idsegb].'"';
                                                if ($acedt && $arrprm[idsegb]==$fl[idsegb])
                                                    echo ' selected';
                                                echo '>'.$fl[des_segb]."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <h4>Efectividad</h4>
                                <br />

                                <div class="control-group">
                                    <label class="control-label" for="sltidem">Medicamento Inadecuado</label>
                                    <div class="controls">
                                        <select id="sltidem" name="idem" class="input-xlarge">
                                            <?
                                            $result = mysql_query("SELECT * FROM TB_EM",$link);
                                            while ($fl=mysql_fetch_assoc($result)){
                                                echo '<option value="'.$fl[idem].'"';
                                                if ($acedt && $arrprm[idem]==$fl[idem])
                                                    echo ' selected';
                                                echo '>'.$fl[medinadecuado]."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="sltidei">Infradosificación</label>
                                    <div class="controls">
                                        <select id="sltidei" name="idei" class="input-xlarge">
                                            <?
                                            $result = mysql_query("SELECT * FROM TB_EI",$link);
                                            while ($fl=mysql_fetch_assoc($result)){
                                                echo '<option value="'.$fl[idei].'"';
                                                if ($acedt && $arrprm[idei]==$fl[idei])
                                                    echo ' selected';
                                                echo '>'.$fl[infraDosificacion]."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <h4>Necesidad</h4>
                                <br />

                                <div class="control-group">
                                    <label class="control-label" for="sltidnn">Necesidad de tratamiento adicional</label>
                                    <div class="controls">
                                        <select id="sltidnn" name="idnn" class="input-xlarge">
                                            <?
                                            $result = mysql_query("SELECT * FROM TB_NN",$link);
                                            while ($fl=mysql_fetch_assoc($result)){
                                                echo '<option value="'.$fl[idnn].'"';
                                                if ($acedt && $arrprm[idnn]==$fl[idnn])
                                                    echo ' selected';
                                                echo '>'.$fl[neceTrataAdicional]."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="sltidnmi">Medicamento Innecesario</label>
                                    <div class="controls">
                                        <select id="sltidnmi" name="idnmi" class="input-xlarge">
                                            <?
                                            $result = mysql_query("SELECT * FROM TB_NMI",$link);
                                            while ($fl=mysql_fetch_assoc($result)){
                                                echo '<option value="'.$fl[idnmi].'"';
                                                if ($acedt && $arrprm[idnmi]==$fl[idnmi])
                                                    echo ' selected';
                                                echo '>'.$fl[medinnecesario]."</option>";
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                
                                <hr>

                                <div class="control-group">
                                    <label class="control-label" for="sltstdprm">Estado?</label>
                                    <div class="controls">
                                        <select id="sltstdprm" name="stdprm" style="width: auto">
                                            <option value='1' <?=($acedt && 1==$arrprm[stdprm])?'selected="selected"':''?>>Abierto</option>
                                            <option value='0' <?=($acedt && 0==$arrprm[stdprm])?'selected="selected"':''?>>Cerrado</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="txtgestion">Gestión:</label>
                                <div class="controls">
                                    <textarea class="input-xlarge" id="txtgestion" name="gestion" rows="5"><?=$acedt?$arrprm[gestion]:''?></textarea>
                                </div>
                            </div>
                            
                            <input type="hidden" name="idprm" value="<?=$acedt?$idprm:'0'?>" />
                            <input type="hidden" name="idcslt"  value="<?=$idcslt?>" />
                            <input type="hidden" name="accion"  value="<?=$acedt?'editar':'nuevo'?>" />

                        </div>

                        <div class="modal-footer form-actions">
                            <button class="btn" data-dismiss="modal" aria-hidden="true"><i class='icon-remove'></i> Cancelar</button>
                            <!-- <button class="btn btn-danger" data-loading-text="Eliminando">Eliminar</button> -->
                            <button type="submit" class="btn btn-primary" data-loading-text="Guardando"><i class='icon-ok icon-white'></i> Guardar</button>
                        </div>
                    </div>
                </form>
                <?
                break;

        case 'perfil': //Modal para crear o editar los datos basicos del paciente
                $sqlper = "SELECT TBdatbas.ced_pac as ced_pac, CONCAT_WS(' ', nombre1, nombre2, apellido1, apellido2) as nombre, dir_res, tel_res, ciudad, 
                            ((year(curdate())-year(fch_nac))-(right(curdate(),5)<right(fch_nac, 5))) as edad, sexo, dx_prin, tel_act, dir_act, dir_act,
                            ciudad_act, email, peso, estatura, otra_enf, std_eco, grp_fam, grp_ext, trb_stb, viv_prp, esclrzd, med_alt, deprmd
                          FROM TBpacientes
                          LEFT JOIN TBdatbas ON TBpacientes.ced_pac = TBdatbas.ced_pac
                          WHERE TBpacientes.ced_pac='$ced_pac'
                          LIMIT 1";
                $resper = @mysql_query($sqlper,$link);
                $arrper = @mysql_fetch_assoc($resper);
                foreach ($arrper as $key => $value) 
                    $arrper[$key]=htmlspecialchars($value);                
                ?>
                <form id="frmnewedtper" class="form-horizontal">
                    <div id="modnewedtper" class="modal fade in hide" aria-hidden="false" aria-labelledby="edtnewper" role="dialog" tabindex="-1" style="display: block;">

                        <div class="modal-header">
                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                            <h3 id="edtnewper"><?=$acedt?'Editar Perfil':'Crear Perfil'?></h3>
                        </div>

                        <div class="modal-body">
                            <div class="control-group">
                                <label class="control-label" for="iptnombre">Nombre</label>
                                <div class="controls">
                                    <input id="iptnombre" name="nombre" type="text" class="span3" value="<?=$arrper[nombre]?>" disabled>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="iptced_pac">Documento</label>
                                <div class="controls">
                                    <input id="iptced_pac" name="ced_pac" type="text" class="span3" value="<?=$ced_pac?>" disabled>
                                </div>
                            </div>   

                            <div class="control-group">
                                <label class="control-label" for="iptdir_act">Dirección</label>
                                <div class="controls">
                                    <input id="iptdir_act" name="dir_act" type="text" class="span3" value="<?=$acedt?$arrper[dir_act]:$arrper[dir_res]?>">
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="iptciudad_act">Ciudad</label>
                                <div class="controls">
                                    <input id="iptciudad_act" name="ciudad_act" type="text" class="span3" value="<?=$acedt?$arrper[ciudad_act]:$arrper[ciudad]?>">
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="ipttel_act">Teléfono</label>
                                <div class="controls">
                                    <input id="ipttel_act" name="tel_act" type="text" class="span3" value="<?=$acedt?$arrper[tel_act]:$arrper[tel_res]?>">
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="iptemail">e-mail</label>
                                <div class="controls">
                                    <input id="iptemail" name="email" type="text" class="span3" value="<?=$acedt?$arrper[email]:''?>">
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="iptpeso">Peso</label>
                                <div class="controls">
                                    <input id="iptpeso" name="peso" type="text" class="span2" value="<?=$acedt?$arrper[peso]:''?>"> Kg.
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="iptestatura">Estatura</label>
                                <div class="controls">
                                    <input id="iptestatura" name="estatura" type="text" class="span2" value="<?=$acedt?$arrper[estatura]:''?>"> cm.
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="iptdx_prin">Diagnóstico Principal</label>
                                <div class="controls">
                                    <input id="iptdx_prin" name="dx_prin" type="text" class="span3" value="<?=$acedt?$arrper[dx_prin]:''?>">
                                    <select id="sltcoddx" name="coddx" class="input-xlarge hide"></select>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="txtotra_enf">Otras Enfermedades</label>
                                <div class="controls">
                                    <textarea class="input-xlarge" id="txtotra_enf" name="otra_enf" rows="5"><?=$acedt?$arrper[otra_enf]:''?></textarea>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="sltstd_eco">Estado Económico Estable</label>
                                <div class="controls">
                                    <select id="sltstd_eco" name="std_eco" style="width: auto">
                                        <option value='1' <?=($acedt && 1==$arrper[std_eco])?'selected="selected"':''?>>Sí</option>
                                        <option value='0' <?=($acedt && 0==$arrper[std_eco])?'selected="selected"':''?>>No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="sltgrp_fam">Grupo Familiar de apoyo</label>
                                <div class="controls">
                                    <select id="sltgrp_fam" name="grp_fam" style="width: auto">
                                        <option value='1' <?=($acedt && 1==$arrper[grp_fam])?'selected="selected"':''?>>Sí</option>
                                        <option value='0' <?=($acedt && 0==$arrper[grp_fam])?'selected="selected"':''?>>No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="sltgrp_ext">Grupo Extrafamiliar de apoyo</label>
                                <div class="controls">
                                    <select id="sltgrp_ext" name="grp_ext" style="width: auto">
                                        <option value='1' <?=($acedt && 1==$arrper[grp_ext])?'selected="selected"':''?>>Sí</option>
                                        <option value='0' <?=($acedt && 0==$arrper[grp_ext])?'selected="selected"':''?>>No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="slttrb_stb">Trabajo estable</label>
                                <div class="controls">
                                    <select id="slttrb_stb" name="trb_stb" style="width: auto">
                                        <option value='1' <?=($acedt && 1==$arrper[trb_stb])?'selected="selected"':''?>>Sí</option>
                                        <option value='0' <?=($acedt && 0==$arrper[trb_stb])?'selected="selected"':''?>>No</option>
                                    </select>
                                </div>
                            </div>


                            <div class="control-group">
                                <label class="control-label" for="sltviv_prp">Vivienda Propia</label>
                                <div class="controls">
                                    <select id="sltviv_prp" name="viv_prp" style="width: auto">
                                        <option value='1' <?=($acedt && 1==$arrper[viv_prp])?'selected="selected"':''?>>Sí</option>
                                        <option value='0' <?=($acedt && 0==$arrper[viv_prp])?'selected="selected"':''?>>No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="sltesclrzd">Escolarizado</label>
                                <div class="controls">
                                    <select id="sltesclrzd" name="esclrzd" style="width: auto">
                                        <option value='1' <?=($acedt && 1==$arrper[esclrzd])?'selected="selected"':''?>>Sí</option>
                                        <option value='0' <?=($acedt && 0==$arrper[esclrzd])?'selected="selected"':''?>>No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="sltmed_alt">Utiliza medicina alternativa</label>
                                <div class="controls">
                                    <select id="sltmed_alt" name="med_alt" style="width: auto">
                                        <option value='1' <?=($acedt && 1==$arrper[med_alt])?'selected="selected"':''?>>Sí</option>
                                        <option value='0' <?=($acedt && 0==$arrper[med_alt])?'selected="selected"':''?>>No</option>
                                    </select>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="sltdeprmd">Deprimido</label>
                                <div class="controls">
                                    <select id="sltdeprmd" name="deprmd" style="width: auto">
                                        <option value='1' <?=($acedt && 1==$arrper[deprmd])?'selected="selected"':''?>>Sí</option>
                                        <option value='0' <?=($acedt && 0==$arrper[deprmd])?'selected="selected"':''?>>No</option>
                                    </select>
                                </div>
                            </div>

                            <input type="hidden" name="ced_pac"  value="<?=$ced_pac?>" />
                            <input type="hidden" name="accion"  value="<?=$acedt?'editar':'nuevo'?>" />
                        </div>

                        <div class="modal-footer form-actions">
                            <button class="btn" data-dismiss="modal" aria-hidden="true"><i class='icon-remove'></i> Cancelar</button>
                            <button type="submit" class="btn btn-primary" data-loading-text="Guardando"><i class='icon-ok icon-white'></i> Guardar</button>
                        </div>
                    </div>
                </form>
                <?
                break;

        case 'lab': //Modal para crear o editar un laboratorio
                if ($acedt && isset($_GET['idlabcln']) && $_GET['idlabcln'] != NULL)://editar
                    $idlabcln  = mysql_real_escape_string($_GET['idlabcln'],$link);
                    $sqllab = "SELECT idlabcln, fch_lab, labcln, resltd, obs
                               FROM TBlabcln
                               WHERE idlabcln='$ced_pac'
                               LIMIT 1";
                    $reslab = @mysql_query($sqllab,$link);
                    $arrlab = @mysql_fetch_assoc($reslab);
                    foreach ($arrlab as $key => $value) 
                        $arrlab[$key]=htmlspecialchars($value);
                endif;
                ?>
                <form id="frmnewedtper" class="form-horizontal">
                    <div id="modnewedtper" class="modal fade in hide" aria-hidden="false" aria-labelledby="edtnewlab" role="dialog" tabindex="-1" style="display: block;">

                        <div class="modal-header">
                            <button aria-hidden="true" data-dismiss="modal" class="close" type="button">×</button>
                            <h3 id="edtnewlab"><?=$acedt?'Editar Examen':'Crear Examen'?></h3>
                        </div>

                        <div class="modal-body">
                            <div class="control-group">
                                <label class="control-label" for="iptfch_lab">Fecha del Laboratorio</label>
                                <div class="controls">
                                    <div class="input-append">
                                        <input id="iptfch_lab" name="fch_lab" type="text" class="span2" placeholder="yyyy-mm-dd" data-date-format="yyyy-mm-dd" value="<?=$acedt?$arrlab[fch_lab]:''?>">
                                        <span class="add-on"><i class="icon-calendar"></i></span>
                                    </div>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="iptlabcln">Tipo de Examen</label>
                                <div class="controls">
                                    <input id="iptlabcln" name="labcln" type="text" class="span3" value="<?=$acedt?$arrlab[labcln]:''?>">
                                    <select id="sltidlabcln" name="idlabcln" class="input-xlarge hide"></select>
                                </div>
                            </div>


                            <div class="control-group">
                                <label class="control-label" for="txtresltd">Resultado</label>
                                <div class="controls">
                                    <textarea class="input-xlarge" id="txtresltd" name="resltd" rows="3"><?=$acedt?$arrlab[resltd]:''?></textarea>
                                </div>
                            </div>

                            <div class="control-group">
                                <label class="control-label" for="txtobs">Observaciones</label>
                                <div class="controls">
                                    <textarea class="input-xlarge" id="txtobs" name="obs" rows="3"><?=$acedt?$arrlab[obs]:''?></textarea>
                                </div>
                            </div>

                            <input type="hidden" name="ced_pac"  value="<?=$ced_pac?>" />
                            <input type="hidden" name="accion"  value="<?=$acedt?'editar':'nuevo'?>" />
                        </div>

                        <div class="modal-footer form-actions">
                            <button class="btn" data-dismiss="modal" aria-hidden="true"><i class='icon-remove'></i> Cancelar</button>
                            <button type="submit" class="btn btn-primary" data-loading-text="Guardando"><i class='icon-ok icon-white'></i> Guardar</button>
                        </div>
                    </div>
                </form>
                <?
                break;

        default:
                echo '<div class="alert alert-error"><span class="label label-important">ERROR:</span> No se han podido generar los datos.</div>';
            break;
    }
else:
    echo '<div class="alert alert-error"><span class="label label-important">ERROR:</span> Petición rechazada por el servidor. Contacte al administrador del sistema e infórmele de este error.</div>';
endif;
?>