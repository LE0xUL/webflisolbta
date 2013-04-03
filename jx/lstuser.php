<?php
//std: 0->ErrBD :: 1->ErrIni :: 100->NoHayDatos
//std: 200->Resultado Exitoso
session_start();
if($_GET['k']==$_SESSION['SFT']['user_key'] && isset($_GET['t']) && $_GET['t']!=NULL):
    require_once($_SESSION['SFT']['rootdir'].'/inc/fncsft.php');
    $_SESSION['SFT']['user_fch']=time();
    //echo "entra al archivo";
    $link     = conect_db();
    $terminos = trim($_GET['t']);
    $terminos = mysql_real_escape_string($terminos,$link);
    $terminos = explode(' ', $terminos);
    $num_pal  = sizeof($terminos);
    // echo "terminos: ";
    // print_r($terminos);
    // echo "\n num_pal: ".$num_pal."\n";

    if ($num_pal==1):
        $i=0;
        do {
            $i++;
            switch ($i) {
                case 1: $sql = "SELECT TBpacientes.ced_pac, TBpacientes.ced_pac as 'Documento', CONCAT_WS(' ', nombre1, nombre2) as Nombres, CONCAT_WS(' ', apellido1, apellido2) as Apellidos, 
                                ((year(curdate())-year(fch_nac))-(right(curdate(),5)<right(fch_nac, 5))) as Edad, sexo as Sexo, COALESCE(numcon,0) as Consultas
                                FROM TBpacientes 
                                LEFT JOIN (SELECT ced_pac, count(ced_pac) as numcon FROM TBconsulta group by ced_pac) TB1 ON TBpacientes.ced_pac=TB1.ced_pac
                                WHERE TBpacientes.ced_pac LIKE '%$terminos[0]%'
                                ORDER by Nombres ASC";
                        // echo $sql."\n";
                        $result=mysql_query($sql,$link);
                        break;
                case 2: $sql = "SELECT TBpacientes.ced_pac, TBpacientes.ced_pac as 'Documento', CONCAT_WS(' ', nombre1, nombre2) as Nombres, CONCAT_WS(' ', apellido1, apellido2) as Apellidos, 
                                ((year(curdate())-year(fch_nac))-(right(curdate(),5)<right(fch_nac, 5))) as Edad, sexo as Sexo, COALESCE(numcon,0) as Consultas
                                FROM TBpacientes
                                LEFT JOIN (SELECT ced_pac, count(ced_pac) as numcon FROM TBconsulta group by ced_pac) TB1 ON TBpacientes.ced_pac=TB1.ced_pac
                                WHERE apellido1 LIKE '%$terminos[0]%' OR apellido2 LIKE '%$terminos[0]%' OR nombre1 LIKE '%$terminos[0]%' OR nombre2 LIKE '%$terminos[0]%'
                                ORDER by Nombres ASC";
                        // echo $sql."\n";
                        $result=mysql_query($sql,$link);
                        break;
            }
        } while ( mysql_num_rows($result)==0 && $i<2);
        // echo $sql."\n";
    elseif ($num_pal>1):
        $i=0;
        do {
            $i++;
            switch ($i) {
                case 1: $sql = "SELECT TBpacientes.ced_pac, TBpacientes.ced_pac as 'Documento', CONCAT_WS(' ', nombre1, nombre2) as Nombres, CONCAT_WS(' ', apellido1, apellido2) as Apellidos, 
                                (year(curdate())-year(fch_nac))-(right(curdate(),5)<right(fch_nac, 5)) as Edad, sexo as Sexo, COALESCE(numcon,0) as Consultas
                                FROM TBpacientes
                                LEFT JOIN (SELECT ced_pac, count(ced_pac) as numcon FROM TBconsulta group by ced_pac) TB1 ON TBpacientes.ced_pac=TB1.ced_pac
                                WHERE (apellido1 LIKE '%$terminos[0]%' OR apellido2 LIKE '%$terminos[0]%') AND (nombre1 LIKE '%$terminos[1]%' OR nombre2 LIKE '%$terminos[1]%')
                                ORDER by Nombres ASC";
                        // echo $sql."\n";
                        $result=mysql_query($sql,$link);
                        break;
                case 2: $sql = "SELECT TBpacientes.ced_pac, TBpacientes.ced_pac as 'Documento', CONCAT_WS(' ', nombre1, nombre2) as Nombres, CONCAT_WS(' ', apellido1, apellido2) as Apellidos, 
                                (year(curdate())-year(fch_nac))-(right(curdate(),5)<right(fch_nac, 5)) as Edad, sexo as Sexo, COALESCE(numcon,0) as Consultas
                                FROM TBpacientes
                                LEFT JOIN (SELECT ced_pac, count(ced_pac) as numcon FROM TBconsulta group by ced_pac) TB1 ON TBpacientes.ced_pac=TB1.ced_pac
                                WHERE (apellido1 LIKE '%$terminos[1]%' OR apellido2 LIKE '%$terminos[1]%') AND (nombre1 LIKE '%$terminos[0]%' OR nombre2 LIKE '%$terminos[0]%')
                                ORDER by Nombres ASC";
                        // echo $sql."\n";
                        $result=mysql_query($sql,$link);
                        break;
                case 3: $sql = "SELECT TBpacientes.ced_pac, TBpacientes.ced_pac as 'Documento', CONCAT_WS(' ', nombre1, nombre2) as Nombres, CONCAT_WS(' ', apellido1, apellido2) as Apellidos, 
                                (year(curdate())-year(fch_nac))-(right(curdate(),5)<right(fch_nac, 5)) as Edad, sexo as Sexo, COALESCE(numcon,0) as Consultas
                                FROM TBpacientes
                                LEFT JOIN (SELECT ced_pac, count(ced_pac) as numcon FROM TBconsulta group by ced_pac) TB1 ON TBpacientes.ced_pac=TB1.ced_pac
                                WHERE (apellido1 LIKE '%$terminos[0]%' AND apellido2 LIKE '%$terminos[1]%') OR (apellido1 LIKE '%$terminos[1]%' AND apellido2 LIKE '%$terminos[0]%')
                                ORDER by Nombres ASC";
                        // echo $sql."\n";
                        $result=mysql_query($sql,$link);
                        break;
                case 4: $sql = "SELECT TBpacientes.ced_pac, TBpacientes.ced_pac as 'Documento', CONCAT_WS(' ', nombre1, nombre2) as Nombres, CONCAT_WS(' ', apellido1, apellido2) as Apellidos, 
                                (year(curdate())-year(fch_nac))-(right(curdate(),5)<right(fch_nac, 5)) as Edad, sexo as Sexo, COALESCE(numcon,0) as Consultas
                                FROM TBpacientes
                                LEFT JOIN (SELECT ced_pac, count(ced_pac) as numcon FROM TBconsulta group by ced_pac) TB1 ON TBpacientes.ced_pac=TB1.ced_pac
                                WHERE (nombre1 LIKE '%$terminos[0]%' AND nombre2 LIKE '%$terminos[1]%') OR (nombre1 LIKE '%$terminos[1]%' AND nombre2 LIKE '%$terminos[0]%')
                                ORDER by Nombres ASC";
                        // echo $sql."\n";
                        $result=mysql_query($sql,$link);
                        break;/*
                default:$sql="SELECT * FROM TBpacientes WHERE ced_pac LIKE '%$terminos[0]%'";
                        $result=mysql_query($sql,$link);*/
          }
        } while ( mysql_num_rows($result)==0 && $i<4);
        // echo $sql."\n";
    endif;

    if(mysql_num_rows($result)==0):
        echo '<div class="alert alert-error"> No se encontraron resultados.</div>';
    else:
        echo '<h4>Se encontraron '.mysql_num_rows($result).' Resultados</h4>';
        result2tb($result,'tbllstusr');
    endif;

else:
    echo '<div class="alert alert-error" >Petición rechazada por el servidor. Contacte al administrador del sistema e infórmele de este error.</div>';
endif;
?>
