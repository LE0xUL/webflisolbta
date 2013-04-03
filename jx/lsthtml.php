<?php
//std: 0->ErrBD :: 1->ErrIni :: 100->NoHayDatos
//std: 200->Resultado Exitoso
session_start();
if($_GET['k']==$_SESSION['SFT']['user_key']):
    require_once($_SESSION['SFT']['rootdir'].'/inc/fncsft.php');
    $_SESSION['SFT']['user_fch']=time();
    $link     = conect_db();
    $target = mysql_real_escape_string($_GET['target'],$link);
    //$jsond = array();
    // echo "terminos: ";
    // print_r($terminos);
    // echo "\n num_pal: ".$num_pal."\n";
    //print_r($_GET);
    
    
    switch ($target) {
        case 'dx':
                $terminos = trim($_GET['dx_prin']);
                $terminos = mysql_real_escape_string($terminos,$link);
                $terminos = explode(' ', $terminos);
                $num_pal  = sizeof($terminos);

                if ($num_pal==1):
                    $i=0;
                    do {
                        $i++;
                        switch ($i) {
                            case 1: $sqldx = "SELECT coddx, descdx FROM TBdx WHERE coddx LIKE '%$terminos[0]%' ORDER by descdx ASC";
                                    // echo "1: ".$sqldx."\n";
                                    $resdx=mysql_query($sqldx,$link);
                                    break;
                            case 2: $sqldx = "SELECT coddx, descdx FROM TBdx WHERE descdx LIKE '%$terminos[0]%' ORDER by descdx ASC";
                                    // echo "1: ".$sqldx."\n";
                                    $resdx=mysql_query($sqldx,$link);
                                    break;
                        }
                    } while ( @mysql_num_rows($resdx)==0 && $i<2);
                elseif ($num_pal>1):
                    $sqldx = "SELECT coddx, descdx FROM TBdx WHERE";
                    foreach ($terminos as $key => $value) 
                        $sqldx .= " descdx LIKE '%$value%' AND";
                    $sqldx  = rtrim($sqldx, " AND");
                    $sqldx .= " ORDER by descdx ASC";
                    // echo $sqldx."\n";
                    $resdx = @mysql_query($sqldx,$link);
                endif;
                //echo "<!-- $sqldx -->";
                if(@mysql_num_rows($resdx)!=0):
                    while($arrdx = @mysql_fetch_assoc($resdx))
                        echo "<option value='$arrdx[coddx]'>$arrdx[descdx]</option>";
                endif;
                break;
        case 'lab':
                $terminos = trim($_GET['labcln']);
                $terminos = mysql_real_escape_string($terminos,$link);
                $terminos = explode(' ', $terminos);
                $num_pal  = sizeof($terminos);
                if ($num_pal==1):
                    $sqllab = "SELECT idlabcln, labcln FROM TBlabcln WHERE labcln LIKE '%$terminos[0]%' ORDER by labcln ASC";
                elseif ($num_pal>1):
                    $sqllab = "SELECT idlabcln, labcln FROM TBlabcln WHERE";
                    foreach ($terminos as $key => $value) 
                        $sqllab .= " labcln LIKE '%$value%' AND";
                    $sqllab  = rtrim($sqllab, " AND");
                    $sqllab .= " ORDER by labcln ASC";
                endif;
                echo $sqllab."\n";
                $reslab = @mysql_query($sqllab,$link);
                //echo "<!-- $sqllab -->";
                if(@mysql_num_rows($reslab)!=0):
                    while($arrlab = @mysql_fetch_assoc($reslab))
                        echo "<option value='$arrlab[idlabcln]'>$arrlab[labcln]</option>";
                endif;
                break;

    }

// else:
//     $jsond['std']=1;
//     $jsond['msg']="Error de inicio.";
endif;
?>
