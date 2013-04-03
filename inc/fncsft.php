<?php
session_start();

if(isset($_SESSION['SFT']['rootdir']) && $_SESSION['SFT']['rootdir']!= NULL){
    
    require_once($_SESSION['SFT']['rootdir'].'/inc/varsft.php');

    //Funcion para conectarse a la base de datos
    function conect_db(){
        //$link = @mysql_connect('trecetp.mydomaincommysql.com', 'sftuserdb', '*password*') or die ('{"std":0,"msg":"Error en la conexión: '.mysql_error().'"}');
        //mysql_select_db(db02281045_sftdb) or die('{"std":0,"msg":"Error en la selección: '.mysql_error().'"}');
        $link=@mysql_connect(db_server,db_user,db_user_pass) or die ('{"std":0,"msg":"Error en la conexión: '.mysql_error().'"}');
        mysql_select_db(db_database,$link) or die('{"std":0,"msg":"Error en la selección: '.mysql_error().'"}');
        mysql_query("SET NAMES 'utf8' COLLATE 'utf8_general_ci'");
        return $link;
    }
    
    //Función que controla los intentos de acceso no autorizado
    function errorlogin(){
        if(isset($_SESSION['SFT']['errlogin']) && $_SESSION['SFT']['errlogin'] != NULL){
            $_SESSION['SFT']['errlogin']++;
            
            if ($_SESSION['SFT']['errlogin']>10) {
                sleep($_SESSION['SFT']['errlogin']-9);
                return 1;   
            }
            else return 0;
        }
        else {
            $_SESSION['SFT']['errlogin']=1;
            return 0;
        }
    }

    function showfooter() { /*?>
        <div id="footer">
            <p>&copy; GPIOpen 2012</p>
        </div> <?*/
    }

    function result2tb($result, $id="tb4sql"){
        $arr=mysql_fetch_assoc($result);
        $keys = array_keys($arr);
        $long = sizeof($arr);
        ?>
        <table id="<?=$id?>" class="table table-condensed table-bordered table-striped table-hover" style="margin-bottom: 0;">
            <thead>
                <tr>
                    <?for($i=1;$i<$long;$i++) echo "<th>".htmlspecialchars($keys[$i])."</th>";?>
                </tr>
            </thead>
            <tbody>
            <?do{?>
                <tr <?=$keys[0].'="'.$arr[$keys[0]].'"'?>>
                    <?for($i=1;$i<$long;$i++) echo "<td>".htmlspecialchars($arr[$keys[$i]])."</td>";?>
                </tr>
            <?}while ($arr=mysql_fetch_assoc($result));?>
            </tbody>
        </table>
        <?
    }
}
?>