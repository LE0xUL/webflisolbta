<?php
//std: 0->ErrBD :: 1->ErrIni :: 110->ErrLogin :: 120->ErrLogin > 10 :: 130->ErrLogin > 20 | Sistema inactivo
//std: 200->Login Exitoso
session_start();
if(isset($_SESSION['SFT']['rootdir']) && $_SESSION['SFT']['rootdir']!= NULL){
    require_once($_SESSION['SFT']['rootdir'].'/inc/fncsft.php');
    //$inactivo = 1;
    if($inactivo){
        $jsond['std']=130;
        $jsond['msg']="Disculpe las molestias, pero en el momento no es posible validar su ingreso ya que la plataforma se ha deshabilitado temporalmente debido a que esta en mantenimiento.<br/>Gracias.";
        echo json_encode($jsond);
    }
    else if(isset($_SESSION['SFT']['errlogin']) && $_SESSION['SFT']['errlogin'] != NULL && $_SESSION['SFT']['errlogin']>20){
        $jsond['std']=130;
        $jsond['msg']="Se han registrado demasiados intentos fallidos y el sistema se ha inhabilitado por un tiempo. <br/><br/>Se recomienda que cierre la ventana y vuelva al cabo un tiempo.";
        echo json_encode($jsond);
    } else {
        $link = conect_db();
        $user = mysql_real_escape_string($_GET['usersft'],$link);
        $pswd = md5(mysql_real_escape_string($_GET['pswdsft'],$link));
        $sql="SELECT * FROM TBuser WHERE usersft='$user' LIMIT 1";
        $result=mysql_query($sql,$link);
        
        if(mysql_num_rows($result)==0){
            $jsond['std']=(errorlogin())?120:110;
            $jsond['msg']="Usuario Incorrecto.";
            $jsond['sql']=$sql;
            echo json_encode($jsond);
        } else{
            $array=mysql_fetch_array($result);
        
            if($array['pswdsft']==$pswd ){//Login exitoso!!
                if ($array['estado']==1) {
                    $_SESSION['SFT']['user_id']=$array["iduser"];
                    $_SESSION['SFT']['user_name']=$array["name"];
                    $_SESSION['SFT']['user_cargo']=$array["cargo"];
                    $_SESSION['SFT']['user_tipo']=$array["tipo"];
                    $_SESSION['SFT']['user_key']=md5(time()).$pswd.session_id();
                    $_SESSION['SFT']['user_fch']=time();
                    $_SESSION['SFT']['user_cls']=(isset($_GET['ccls']) && $_GET['ccls']==1)?1:0;
                    unset($_SESSION['SFT']['errlogin']);
                    
                    $jsond['std']=200;
                    $jsond['msg']="Login Exitoso";
                    $jsond['ukey']=$_SESSION['SFT']['user_key'];
                    echo json_encode($jsond);
                } else {
                    $jsond['std']=(errorlogin())?120:110;
                    $jsond['msg']="Usuario inactivo.";
                    echo json_encode($jsond);
                }
            } else {
                $jsond['std']=(errorlogin())?120:110;
                $jsond['msg']="Contraseña Incorrecta.";
                echo json_encode($jsond);
            }
        }
        mysql_free_result($result);
        mysql_close();   
    }
} else {
    $jsond['std']=1;
    $jsond['msg']="Error de inicio.";
	echo json_encode($jsond);
}
?>