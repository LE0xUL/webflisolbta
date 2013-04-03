<?php
//std: 0->ErrBD :: 1->ErrIni :: 10->Error en la consulta
//std: 200->Guardado Exitoso
// sleep(5);
session_start();
$djson;
if($_POST['k']==$_SESSION['SFT']['user_key']):
    require_once($_SESSION['SFT']['rootdir'].'/inc/fncsft.php');
    $_SESSION['SFT']['user_fch']=time();
    $djson['POST']=$_POST;
    $link = conect_db();
    $ced_pac = $_SESSION['SFT']['ced_pac'];
    $iduser = $_SESSION['SFT']['user_id'];

    foreach ($_POST as $key => $value) 
        $arrpost[$key]=mysql_real_escape_string($value,$link);

    switch ($arrpost['target']) {
        case 'cslt':
                if($arrpost['accion']=='eliminar'):
                    $sql = "DELETE FROM TBconsulta WHERE idcslt='$arrpost[idcslt]' LIMIT 1"; //CUIDADO: Hace eliminación en cascada!!
                    @mysql_query($sql,$link) or die ('{"std":10,"msg":"Error en la Consulta", "error": "'.mysql_error().'", "sql":"'.$sql.'"}');
                    $djson['std']=200;
                    $djson['msg']="La consulta y todos su datos han sido eliminados de forma satisfactoria";
                elseif ($arrpost['accion']=='nuevo' || $arrpost['accion']=='editar'): //Insertar o Editar
                    $sql_set = '';

                    if ($arrpost['accion']=='editar'):
                        $sql_ini = "UPDATE TBconsulta SET";
                        $sql_end = " WHERE idcslt='$arrpost[idcslt]' LIMIT 1";
                    else://nuevo
                        $sql_ini  = "INSERT INTO TBconsulta SET";
                        $sql_set .= " ced_pac='$ced_pac',";
                    endif;

                    if($_SESSION['SFT']['user_tipo']=='admin'): //Si es admin si crea o edita
                        $sql_set .= " iduser='$arrpost[iduser]',";
                        $sql_set .= " fch_cslt='$arrpost[fch_cslt]',";
                    elseif($arrpost['accion']=='nuevo'): //usuario normal que crea
                        $sql_set .= " iduser='$iduser',";
                        $sql_set .= " fch_cslt=CURDATE(),";
                    endif;

                    
                    $sql_set .= " motivo='$arrpost[motivo]',";
                    $sql_set .= " idrmtd='$arrpost[idrmtd]',";
                    $sql_set .= " anamnesis='$arrpost[anamnesis]',";
                    $sql_set .= " inter_med='$arrpost[inter_med]',";
                    $sql_set .= " inter_adm='$arrpost[inter_adm]'";
                    
                    $sql=$sql_ini.$sql_set.$sql_end;

                    @mysql_query($sql,$link) or die ('{"std":10,"msg":"Error en la Consulta", "error": "'.mysql_error().'", "sql":"'.$sql.'"}');
                    $djson['std']=200;
                    $djson['msg']="Los datos han sido guardados de forma satisfactoria.";
                else:   
                    $djson['std']=1;
                    $djson['msg']="El servidor no puede procesar su orden: Accion denegada.";
                endif;
                $djson['sql']=$sql;
                break;

        case 'intervcn':
                if($arrpost['accion']=='eliminar'):
                    $sql = "DELETE FROM TBintervcn WHERE idintervcn='$arrpost[idintervcn]' LIMIT 1";
                    @mysql_query($sql,$link) or die ('{"std":10,"msg":"Error en la Consulta", "error": "'.mysql_error().'", "sql":"'.$sql.'"}');
                    $djson['std']=200;
                    $djson['msg']="La intervención ha sido eliminada de forma satisfactoria";
                elseif ($arrpost['accion']=='nuevo' || $arrpost['accion']=='editar'): //Insertar o Editar

                    if ($arrpost['accion']=='editar'):
                        $sql_ini = "UPDATE TBintervcn SET";
                        $sql_end = " WHERE idintervcn='$arrpost[idintervcn]' LIMIT 1";
                    else:
                        $sql_ini = "INSERT INTO TBintervcn SET";
                        //$sql_set = " iduser='$iduser',";//Solo cuando de se crea
                        //$sql_set .= " fch_cslt='HOY',";   //Toca modificarlo en un futuro || solo el admin podrá editar una fecha
                    endif;

                    $sql_set .= " idcslt='$arrpost[idcslt]',";
                    $sql_set .= " tipoint='$arrpost[tipoint]',";
                    $sql_set .= " fch_intervcn='$arrpost[fch_intervcn]',";   //Toca modificarlo en un futuro
                    $sql_set .= " dirigido='$arrpost[dirigido]',";
                    $sql_set .= " concepto='$arrpost[concepto]',";

                    $sql_set .= " med_email=".($arrpost[med_email]?'1':'0').",";
                    $sql_set .= " med_fisico=".($arrpost[med_fisico]?'1':'0').",";
                    $sql_set .= " med_verbal=".($arrpost[med_verbal]?'1':'0').",";
                    $sql_set .= " med_historia=".($arrpost[med_historia]?'1':'0');
                    
                    $sql=$sql_ini.$sql_set.$sql_end;

                    @mysql_query($sql,$link) or die ('{"std":10,"msg":"Error en la Consulta", "error": "'.mysql_error().'", "sql":"'.$sql.'"}');
                    $djson['std']=200;
                    $djson['msg']="Los datos han sido guardados de forma satisfactoria.";
                else:   
                    $djson['std']=1;
                    $djson['msg']="El servidor no puede procesar su orden: Accion denegada.";
                endif;
                $djson['sql']=$sql;
                break;

        case 'ram':
                if($arrpost['accion']=='eliminar'):
                    $sql = "DELETE FROM TBram WHERE idram='$arrpost[idram]' LIMIT 1";
                    @mysql_query($sql,$link) or die ('{"std":10,"msg":"Error en la Consulta", "error": "'.mysql_error().'", "sql":"'.$sql.'"}');
                    $djson['std']=200;
                    $djson['msg']="La RAM ha sido eliminada de forma satisfactoria";
                elseif ($arrpost['accion']=='nuevo' || $arrpost['accion']=='editar'): //Insertar o Editar

                    if ($arrpost['accion']=='editar'):
                        $sql_ini = "UPDATE TBram SET";
                        $sql_end = " WHERE idram='$arrpost[idram]' LIMIT 1";
                    else://nuevo
                        $sql_ini = "INSERT INTO TBram SET";
                        $sql_set = " idcslt='$arrpost[idcslt]',";
                        //$sql_set = " iduser='$iduser',";//Solo cuando de se crea
                        //$sql_set .= " fch_cslt='HOY',";   //Toca modificarlo en un futuro || solo el admin podrá editar una fecha
                    endif;

                    $sql_set .= " idclsseve='$arrpost[idclsseve]',";
                    $sql_set .= " idclsseve2='$arrpost[idclsseve2]',";
                    $sql_set .= " infoaddseve='$arrpost[infoaddseve]',";
                    $sql_set .= " idclscausa='$arrpost[idclscausa]',";
                    $sql_set .= " idevolucion='$arrpost[idevolucion]',";
                    $sql_set .= " fchiniram='$arrpost[fchiniram]',";
                    $sql_set .= " descram='$arrpost[descram]',";
                    $sql_set .= " codunisalud='$arrpost[codunisalud]',";
                    $sql_set .= " fabmed='$arrpost[fabmed]',";
                    $sql_set .= " marcamed='$arrpost[marcamed]',";
                    $sql_set .= " rsmed='$arrpost[rsmed]',";
                    $sql_set .= " lotemed='$arrpost[lotemed]',";
                    $sql_set .= " fchvenmed='$arrpost[fchvenmed]',";
                    $sql_set .= " pre1ram='$arrpost[pre1ram]',";
                    $sql_set .= " pre2ram='$arrpost[pre2ram]',";
                    $sql_set .= " pre3ram='$arrpost[pre3ram]',";
                    $sql_set .= " pre4ram='$arrpost[pre4ram]',";
                    $sql_set .= " pre5ram='$arrpost[pre5ram]',";
                    $sql_set .= " pre1fat='$arrpost[pre1fat]',";
                    $sql_set .= " pre2fat='$arrpost[pre2fat]',";
                    $sql_set .= " pre3fat='$arrpost[pre3fat]',";
                    $sql_set .= " pre4fat='$arrpost[pre4fat]',";
                    $sql_set .= " pre5fat='$arrpost[pre5fat]',";
                    $sql_set .= " pre6fat='$arrpost[pre6fat]',";
                    $sql_set .= " pre7fat='$arrpost[pre7fat]',";
                    $sql_set .= " pre1eve='$arrpost[pre1eve]',";
                    $sql_set .= " pre1susp='$arrpost[pre1susp]',";
                    $sql_set .= " pre2susp='$arrpost[pre2susp]',";
                    $sql_set .= " pre1reex='$arrpost[pre1reex]',";
                    $sql_set .= " pre2reex='$arrpost[pre2reex]',";
                    $sql_set .= " analisis='$arrpost[analisis]',";
                    $sql_set .= " obs='$arrpost[obs]',";
                    $sql_set .= " gestion='$arrpost[gestion]'";
                    
                    $sql=$sql_ini.$sql_set.$sql_end;

                    @mysql_query($sql,$link) or die ('{"std":10,"msg":"Error en la Consulta", "error": "'.mysql_error().'", "sql":"'.$sql.'"}');
                    $djson['std']=200;
                    $djson['msg']="Los datos han sido guardados de forma satisfactoria.";
                else:   
                    $djson['std']=1;
                    $djson['msg']="El servidor no puede procesar su orden: Accion denegada.";
                endif;
                $djson['sql']=$sql;
                break;
        
        case 'prm':
                if($arrpost['accion']=='eliminar'):
                    $sql = "DELETE FROM TBprm WHERE idprm='$arrpost[idprm]' LIMIT 1"; //CUIDADO: Hace eliminación en cascada!!
                    @mysql_query($sql,$link) or die ('{"std":10,"msg":"Error en la Consulta", "error": "'.mysql_error().'", "sql":"'.$sql.'"}');
                    $djson['std']=200;
                    $djson['msg']="El PRM y todos su datos han sido eliminados de forma satisfactoria";
                elseif ($arrpost['accion']=='nuevo' || $arrpost['accion']=='editar'): //Insertar o Editar
                    $sql_set = '';

                    if ($arrpost['accion']=='editar'):
                        $sql_ini = "UPDATE TBprm SET";
                        $sql_end = " WHERE idprm='$arrpost[idprm]' LIMIT 1";
                    else://nuevo
                        $sql_ini  = "INSERT INTO TBprm SET";
                        $sql_set .= " idcslt='$arrpost[idcslt]',";
                    endif;
                    
                    $sql_set .= " idclsprm='$arrpost[idclsprm]',";
                    $sql_set .= " idclsprmotr='$arrpost[idclsprmotr]',";
                    $sql_set .= " desclsotr='$arrpost[desclsotr]',";
                    $sql_set .= " desprm='$arrpost[desprm]',";
                    $sql_set .= " needrnm='$arrpost[needrnm]',";
                    $sql_set .= " idsega='$arrpost[idsega]',";
                    $sql_set .= " idsegb='$arrpost[idsegb]',";
                    $sql_set .= " idem='$arrpost[idem]',";
                    $sql_set .= " idei='$arrpost[idei]',";
                    $sql_set .= " idnn='$arrpost[idnn]',";
                    $sql_set .= " idnmi='$arrpost[idnmi]',";
                    $sql_set .= " stdprm='$arrpost[stdprm]',";
                    $sql_set .= " gestion='$arrpost[gestion]'";
                    
                    $sql=$sql_ini.$sql_set.$sql_end;

                    @mysql_query($sql,$link) or die ('{"std":10,"msg":"Error en la Consulta", "error": "'.mysql_error().'", "sql":"'.$sql.'"}');
                    $djson['std']=200;
                    $djson['msg']="Los datos han sido guardados de forma satisfactoria.";
                else:   
                    $djson['std']=1;
                    $djson['msg']="El servidor no puede procesar su orden: Accion denegada.";
                endif;
                $djson['sql']=$sql;
                break;

        case 'perfil':
                if ($arrpost['accion']=='nuevo' || $arrpost['accion']=='editar'): //Insertar o Editar
                    $sql_set = '';

                    if ($arrpost['accion']=='editar'):
                        $sql_ini = "UPDATE TBdatbas SET";
                        $sql_end = " WHERE ced_pac='$ced_pac' LIMIT 1";
                    else://nuevo
                        $sql_ini  = "INSERT INTO TBdatbas SET";
                        $sql_set .= " ced_pac='$ced_pac',";
                    endif;
                    
                    $sql_set .= " dx_prin='$arrpost[dx_prin]',";
                    $sql_set .= " tel_act='$arrpost[tel_act]',";
                    $sql_set .= " dir_act='$arrpost[dir_act]',";
                    $sql_set .= " ciudad_act='$arrpost[ciudad_act]',";
                    $sql_set .= " email='$arrpost[email]',";
                    $sql_set .= " peso='$arrpost[peso]',";
                    $sql_set .= " estatura='$arrpost[estatura]',";
                    $sql_set .= " otra_enf='$arrpost[otra_enf]',";
                    $sql_set .= " std_eco='$arrpost[std_eco]',";
                    $sql_set .= " grp_fam='$arrpost[grp_fam]',";
                    $sql_set .= " grp_ext='$arrpost[grp_ext]',";
                    $sql_set .= " trb_stb='$arrpost[trb_stb]',";
                    $sql_set .= " viv_prp='$arrpost[viv_prp]',";
                    $sql_set .= " esclrzd='$arrpost[esclrzd]',";
                    $sql_set .= " med_alt='$arrpost[med_alt]',";
                    $sql_set .= " deprmd='$arrpost[deprmd]' ";
                    
                    $sql=$sql_ini.$sql_set.$sql_end;

                    @mysql_query($sql,$link) or die ('{"std":10,"msg":"Error en la Consulta", "error": "'.mysql_error().'", "sql":"'.$sql.'"}');
                    $djson['std']=200;
                    $djson['msg']="Los datos han sido guardados de forma satisfactoria.";
                else:   
                    $djson['std']=1;
                    $djson['msg']="El servidor no puede procesar su orden: Accion denegada.";
                endif;
                $djson['sql']=$sql;
                break;

        default:
                $djson['std']=1;
                $djson['msg']="El servidor no puede procesar su orden: No se encuentra la solicitud pedida.";
            break;
    }
else:
    $djson['std']=1;
    $djson['msg']="El servidor no puede procesar su orden: Acceso no autorizado.";
endif;
echo json_encode($djson);
?>