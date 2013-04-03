<?php //Interfaz general del usuario
// sleep(10);
session_start();
if ($_GET['k'] == $_SESSION['SFT']['user_key']):
    require_once($_SESSION['SFT']['rootdir'].'/inc/fncsft.php');
    // $_SESSION['SFT']['ced_pac'] = '155453';
    $_SESSION['SFT']['user_fch']=time();
    $link = conect_db();
    

    if (isset($_GET['ced_pac']) && $_GET['ced_pac'] != NULL)
        $_SESSION['SFT']['ced_pac'] = mysql_real_escape_string($_GET['ced_pac'],$link);
    
    if (isset($_SESSION['SFT']['ced_pac']) && $_SESSION['SFT']['ced_pac'] != NULL):
        $ced_pac = $_SESSION['SFT']['ced_pac'];
        $iduser  = $_SESSION['SFT']['user_id'];
        $hoy = date("Y-n-j");
        $cslthoy = 0;
        $target  = mysql_real_escape_string($_GET['target'],$link);
        $nompac = "Error al Seleccionar el Paciente";

        $result=mysql_query("SELECT CONCAT_WS(' ', nombre1, nombre2, apellido1, apellido2) as nombre FROM TBpacientes WHERE ced_pac='$ced_pac' LIMIT 1",$link);
        if(mysql_num_rows($result)!=0){
            $fl = mysql_fetch_assoc($result);
            $nompac = $_SESSION['SFT']['nompac']=$fl['nombre'];
        }

        $result=mysql_query("SELECT idcslt FROM TBconsulta WHERE ced_pac='$ced_pac' AND fch_cslt=CURDATE() AND iduser='$iduser' LIMIT 1",$link);
        if(mysql_num_rows($result)!=0){
            $fl = mysql_fetch_assoc($result);
            $idcslthoy = $fl['idcslt'];
            $cslthoy = 1;
        }
        ?>
        
        <div class="row"> <!-- HIstorial del Paciente -->
            <div class="span12 contbox">
                <div class="row">
                    <div class="span12">
                        <div class="box-header">
                            <h2>
                                Historial del paciente <small><?=$nompac?></small>
                                <button id="btnacthis" class="btn btn-info btn-small pull-right" >
                                    <i class="icon-refresh icon-white"></i> Actualizar
                                </button>
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="span12">
                        <ul class="nav nav-tabs">
                            <li <?=(!$target || $target=='tabperfil')?'class="active"':''?>><a href="#tabperfil" data-toggle="tab">Perfil</a></li>
                            <li <?=($target=='tabconsulta')?'class="active"':''?>><a href="#tabconsulta" data-toggle="tab">Consultas</a></li>
                            <li <?=($target=='tabrampac')?'class="active"':''?>><a href="#tabrampac" data-toggle="tab">RAM</a></li>
                            <li <?=($target=='tabprmpac')?'class="active"':''?>><a href="#tabprmpac" data-toggle="tab">PRM</a></li>
                            <!-- <li <?=($target=='tabsegmtopac')?'class="active"':''?>><a href="#tabsegmtopac" data-toggle="tab">Medicamentos</a></li> -->
                        </ul>
                    </div>
                </div>
                <div class="row">
                    <div class="span12">
                        <div class="tab-content">
                            <div id="tabperfil" class="tab-pane <?=(!$target || $target=='tabperfil')?'active':''?>" >
                                <div class="row">
                                <div class="span2">
                                    <div class="tabbable tabs-left visible-desktop">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a data-toggle="tab" href="#tabdatbas">Datos Básicos</a></li>
                                            <li><a data-toggle="tab" href="#tadhislab">Historial Exámenes</a></li>
                                            <!-- <li><a data-toggle="tab" href="#lC">Cambio de Medicamentos</a></li> -->
                                            <!-- <li><a data-toggle="tab" href="#lD">Revisión por Sistemas</a></li> -->
                                        </ul>
                                    </div>
                                    <div class="tabbable hidden-desktop">
                                        <ul class="nav nav-tabs">
                                            <li class="active"><a data-toggle="tab" href="#tabdatbas">Datos Básicos</a></li>
                                            <li><a data-toggle="tab" href="#tadhislab">Historial Exámenes</a></li>
                                            <!-- <li><a data-toggle="tab" href="#lC">Cambio de Medicamentos</a></li> -->
                                            <!-- <li><a data-toggle="tab" href="#lD">Revisión por Sistemas</a></li> -->
                                        </ul>
                                    </div>
                                </div>
                                
                                <div class="span10">
                                    <div class="tab-content">
                                        <div id="tabdatbas" class="tab-pane active">
                                            <?
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
                                            <div class="row">
                                                <div class="datpacbas">
                                                    <div class="span10">
                                                        <div class="btn-group pull-right box-cont">
                                                            <?if ($arrper['ced_pac']):?>
                                                                <button class='btn btn-mini btn-warning btnedtper'  data-loading-text="<i class='icon-time icon-white'></i> Espere..."><i class="icon-user icon-white"></i> Editar Perfil</button>
                                                            <?else:?>
                                                                <button class='btn btn-mini btn-success btnnewper' data-loading-text="<i class='icon-time icon-white'></i> Espere..."><i class="icon-user icon-white"></i> Crear Perfil</button>
                                                            <?endif?>
                                                        </div>
                                                    </div>
                                                    <div class="span7"><strong>Nombre: </strong><?=$arrper[nombre]?></div>
                                                    <div class="span3"><strong>Documento: </strong><?=$ced_pac?></div>
                                                    <div class="span7"><strong>Dirección: </strong><?=$arrper[dir_act]?$arrper[dir_act]:$arrper[dir_res]?></div>
                                                    <div class="span3"><strong>Ciudad: </strong><?=$arrper[ciudad_act]?$arrper[ciudad_act]:$arrper[ciudad]?></div>
                                                    <div class="span7"><strong>email: </strong><?=$arrper[email]?$arrper[email]:''?></div>
                                                    <div class="span3"><strong>Teléfono: </strong><?=$arrper[tel_act]?$arrper[tel_act]:$arrper[tel_res]?></div>
                                                    <div class="span3"><strong>Peso: </strong><?=$arrper[peso]?$arrper[peso]:'0'?> Kg.</div>
                                                    <div class="span4"><strong>Estatura: </strong><?=$arrper[estatura]?$arrper[estatura]:'0'?> cm</div>
                                                    <div class="span3"><strong>Edad: </strong><?=$arrper[edad]?> años</div>
                                                    <div class="span10"><strong>Diagnostico Principal: </strong><?=$arrper[dx_prin]?$arrper[dx_prin]:''?></div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="span10">
                                                    <h5>Otras Enfermedades:</h5>
                                                    <div class="span9">
                                                        <pre class="text"><?=$arrper[otra_enf]?$arrper[otra_enf]:''?></pre>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="span10">
                                                    <h5>Situación SocioCultural:</h5>
                                                    <div class="span3"><strong>Estado Económico Estable: </strong><?=$arrper[std_eco]!=NULL?($arrper[std_eco]?'Si':'No'):''?></div>
                                                    <div class="span3"><strong>Grupo Familiar de apoyo: </strong><?=$arrper[grp_fam]!=NULL?($arrper[grp_fam]?'Si':'No'):''?></div>
                                                    <div class="span3"><strong>Grupo Extrafamiliar de apoyo: </strong><?=$arrper[grp_ext]!=NULL?($arrper[grp_ext]?'Si':'No'):''?></div>
                                                    <div class="span3"><strong>Trabajo estable: </strong><?=$arrper[trb_stb]!=NULL?($arrper[trb_stb]?'Si':'No'):''?></div>
                                                    <div class="span3"><strong>Vivienda Propia: </strong><?=$arrper[viv_prp]!=NULL?($arrper[viv_prp]?'Si':'No'):''?></div>
                                                    <div class="span3"><strong>Escolarizado: </strong><?=$arrper[esclrzd]!=NULL?($arrper[esclrzd]?'Si':'No'):''?></div>
                                                    <div class="span3"><strong>Utiliza medicina alternativa: </strong><?=$arrper[med_alt]!=NULL?($arrper[med_alt]?'Si':'No'):''?></div>
                                                    <div class="span3"><strong>Deprimido: </strong><?=$arrper[deprmd]!=NULL?($arrper[deprmd]?'Si':'No'):''?></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="tadhislab" class="tab-pane">
                                            <div class="row">
                                                <?if($arrper['ced_pac']):?>
                                                    <div class="span10">
                                                        <div class="box-cont pull-right">
                                                            <button class='btn btn-mini btn-success btnnewlab' data-loading-text="<i class='icon-time icon-white'></i> Espere..."><i class="icon-user icon-white"></i> Adicionar Laboratorio</button>
                                                        </div>
                                                    </div>
                                                <?endif?>
                                                <div class="span10">
                                                    <div class="box-cont">
                                                        <?
                                                            $sqllab = "SELECT idlabcln, TBlabcln.iduser as iduser, fch_lab, labcln, resltd, obs, name
                                                                       FROM TBlabcln
                                                                       INNER JOIN TBlabcln.iduser = TBuser.iduser
                                                                       WHERE TBlabcln.ced_pac='$ced_pac'";
                                                            $reslab = @mysql_query($sqllab,$link);

                                                            if(@mysql_num_rows($result)!=0):
                                                                ?>
                                                                    <table id="tblstlab" class="table table-condensed table-bordered table-striped table-hover" style="margin-bottom: 0;">
                                                                        <thead>
                                                                            <tr>
                                                                                <th></th>
                                                                                <th>Fecha</th>
                                                                                <th>Tipo de Exámen</th>
                                                                                <th>Resultado</th>
                                                                                <th>Observaciones</th>
                                                                                <th>Ingresado por</th>
                                                                            </tr>
                                                                        </thead>
                                                                        <tbody>
                                                                        <?
                                                                            while ($arrlab=@mysql_fetch_assoc($reslab)){
                                                                                ?>
                                                                                    <tr>
                                                                                        <td>
                                                                                            <?if ($_SESSION['SFT']['user_tipo'] == 'admin' || ($arrlab['iduser']==$_SESSION['SFT']['user_id'] && $arrlab['fch_cslt']==$hoy)):?>

                                                                                            <?endif?>
                                                                                        </td>
                                                                                        <td><?=htmlspecialchars($arrlab['fch_lab'])?></td>
                                                                                        <td><?=htmlspecialchars($arrlab['labcln'])?></td>
                                                                                        <td><?=htmlspecialchars($arrlab['resltd'])?></td>
                                                                                        <td><?=htmlspecialchars($arrlab['obs'])?></td>
                                                                                        <td><?=htmlspecialchars($arrlab['name'])?></td>
                                                                                    </tr>
                                                                                <?
                                                                            }
                                                                        ?>
                                                                        </tbody>
                                                                    </table>
                                                                <?
                                                            else:
                                                                ?>
                                                                    <div class="alert alert-error"> No hay Exámenes para mostrar.</div>
                                                                <?
                                                            endif;
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="lC" class="tab-pane box-cont">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Obcaecati illum voluptatum molestias aliquid possimus error fugiat dolores similique suscipit id voluptatibus debitis iste velit saepe ipsa corporis eos cupiditate. Dolores veritatis hic sunt autem voluptatem inventore neque numquam dolorem consequatur culpa distinctio temporibus eaque quis nisi deleniti accusamus ipsa ducimus suscipit voluptates rem odit accusantium maxime earum. Aliquid consectetur dolorem enim distinctio natus facere aliquam! Veritatis adipisci ab recusandae voluptas mollitia dolor rerum facilis fuga distinctio ducimus doloremque totam iste autem quae eaque modi quo porro omnis nemo ratione aspernatur eius fugit sed reprehenderit voluptate impedit quia perspiciatis nulla tempora ad excepturi numquam minus saepe libero ipsum molestiae dolores laborum. Earum consequuntur repellat illo saepe sapiente ratione ex fugiat eum magni nostrum. Voluptas recusandae consequatur magni nulla porro cumque dignissimos harum maxime soluta sequi at molestias veniam ex. Beatae expedita ullam iure obcaecati repudiandae eligendi. Eaque ipsam illo modi molestiae eius itaque praesentium aliquid expedita assumenda tempora magni soluta perspiciatis! Ut odit harum consectetur quia ullam eligendi eaque enim iusto repudiandae quas iure eos laboriosam debitis vel sequi cupiditate totam illo modi ipsum excepturi veniam accusamus recusandae earum quaerat soluta nulla deserunt quod incidunt ratione sunt id aperiam facilis reiciendis deleniti culpa eius nesciunt. Odio voluptates saepe hic iusto possimus soluta culpa temporibus sequi eos explicabo laboriosam blanditiis consequatur exercitationem nam nisi repudiandae nihil? Eum nesciunt officia in non est tempora laudantium nostrum laboriosam deleniti consectetur fugit quidem impedit et explicabo ipsum recusandae placeat libero voluptatem aspernatur cum perspiciatis iste voluptatibus ut! Nobis officia fugit expedita quod explicabo nesciunt vel numquam dolore pariatur recusandae molestiae maiores! Perferendis dolorem et ratione laudantium unde eveniet excepturi officiis earum dignissimos assumenda architecto tenetur temporibus provident dolores in aut cupiditate corrupti facere sapiente veritatis amet eligendi nihil repellat hic laborum voluptas distinctio possimus ipsa debitis repellendus voluptate maxime doloremque qui nisi dolore suscipit perspiciatis? Suscipit rem iusto veritatis neque illo modi numquam est architecto ipsum eum quia officiis excepturi voluptatibus aliquam ipsa iure nisi natus adipisci eos velit quisquam facere laboriosam et sequi id quaerat atque molestiae! Quisquam debitis molestias nemo nobis magnam esse error a similique ipsam quos exercitationem optio temporibus expedita eligendi necessitatibus quibusdam est inventore vel laudantium atque corporis iure fuga quaerat voluptatum eos dolore enim facilis tempore voluptatem porro. Reiciendis minus expedita numquam commodi ad provident cum accusamus quidem assumenda eligendi quod dolores nesciunt vero atque voluptates voluptatem minima maxime natus obcaecati quia! Iste quaerat assumenda ab temporibus explicabo voluptatum dolorem qui hic minima ea iusto reprehenderit necessitatibus facere repellat placeat magni optio incidunt tenetur aut magnam sunt id aperiam ipsa consequatur soluta inventore sint? Non quos nobis eos reprehenderit iusto rem illum animi eveniet ratione impedit dolorum architecto deserunt tenetur velit laborum consectetur iure. Vero hic a animi eveniet dolorem facilis in ducimus reprehenderit enim optio sapiente ad porro placeat sed aut ut ratione ipsam quaerat officia possimus voluptatum blanditiis dolor vitae corporis laborum id minima nostrum? Sequi totam laborum cum quam nesciunt nemo saepe dolore necessitatibus pariatur consequatur obcaecati quis mollitia deserunt officiis dicta incidunt explicabo quasi dolorem provident praesentium iusto laboriosam voluptates alias magnam eum deleniti iure. Molestiae laboriosam porro iusto ipsa quasi deleniti officia saepe ad ducimus similique cumque natus sed vero cum blanditiis nisi commodi suscipit. Quas necessitatibus maxime saepe quo reprehenderit asperiores aliquid provident quaerat cupiditate incidunt soluta fugiat ullam unde. Nihil veniam nesciunt molestiae repudiandae voluptates sed asperiores. Nostrum veniam voluptas numquam fugit dolorum accusamus consequatur aliquid facilis sapiente quibusdam optio architecto! Eveniet iusto molestiae modi dolor ut eligendi dicta sapiente consequuntur delectus odio cumque optio nobis officiis ipsum itaque dolorum nulla adipisci vero nisi quasi reiciendis unde labore consequatur omnis voluptas blanditiis perferendis quidem ducimus libero totam. Dolores rerum qui ipsum non consectetur perspiciatis itaque quam consequatur numquam in? Similique expedita officiis distinctio consectetur? Eligendi quam minima commodi cumque vel molestiae delectus? Quasi sequi consequatur modi quia ducimus repellendus sapiente vitae explicabo architecto illum repudiandae quidem soluta hic reprehenderit iusto! Blanditiis mollitia porro id accusantium quo debitis fugit nobis doloremque libero ipsam error cum delectus tempore ratione adipisci. Voluptate soluta quisquam necessitatibus autem unde neque nulla amet illum nesciunt officia suscipit commodi possimus asperiores sunt consequatur exercitationem omnis reprehenderit tempore eaque tempora officiis maxime quasi adipisci deserunt nobis. Optio nesciunt quaerat nihil inventore nulla ducimus voluptate. Quasi sint sed laborum blanditiis doloremque earum labore praesentium cum in quod ex corporis facilis perferendis obcaecati saepe beatae placeat quo nam cupiditate animi tempore voluptatum pariatur inventore provident eos rem aspernatur accusamus quae architecto reiciendis hic ratione minima veritatis molestias dignissimos assumenda nostrum eligendi similique a debitis! Odio aliquam autem aut soluta itaque magnam nisi ut dolore incidunt recusandae deserunt eligendi aspernatur pariatur vel cupiditate blanditiis excepturi rerum hic impedit necessitatibus ea iste maxime labore reiciendis tenetur quidem aliquid est facere suscipit assumenda totam eaque quo quae. Assumenda quibusdam ea optio aspernatur maxime cumque voluptates repellat fuga ipsa alias nam illo beatae quis cupiditate vero ex explicabo dolores earum deserunt mollitia similique itaque a consectetur nihil temporibus at aperiam expedita error animi laudantium. Magnam itaque veniam eos distinctio quaerat illo officiis culpa dolorem debitis maxime corporis ullam voluptatem consequatur molestias accusantium excepturi eligendi expedita qui incidunt fuga quidem explicabo adipisci ex sunt nostrum ratione dignissimos beatae rem magni similique quas pariatur laudantium molestiae saepe amet iusto animi. Sunt tempore enim incidunt illum porro at quia quidem quisquam possimus quis provident iure autem dolore sed velit rem fuga suscipit ut minus aliquid. Ipsum illum soluta rerum libero deserunt natus neque commodi consequatur magni at! Odit nobis quam sapiente officia assumenda rerum distinctio atque sint suscipit incidunt enim soluta nam explicabo aperiam sit tempora recusandae accusamus labore maxime expedita laborum esse excepturi repudiandae quae ut inventore praesentium sunt cum velit provident! Itaque sint commodi repellat hic suscipit porro omnis atque eaque non quaerat magni reiciendis nobis quasi corporis tenetur cumque dolorum similique sit nemo aperiam adipisci unde ut laudantium veritatis optio voluptatem voluptates. Tempora magnam nihil in minus illo impedit similique dolorem consequatur fugit quae debitis eos?
                                        </div>
                                        <div id="lD" class="tab-pane box-cont">
                                            Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illo omnis laudantium incidunt rerum odit blanditiis tempore sed cum culpa beatae optio voluptatibus assumenda in tenetur exercitationem alias ducimus a. Perspiciatis sapiente porro unde eos quod pariatur rerum repudiandae ipsa praesentium hic iusto nam qui similique accusantium reiciendis impedit autem voluptas neque ducimus temporibus voluptatibus sunt ullam labore amet doloribus nihil inventore. Alias assumenda harum nobis veritatis aperiam natus cupiditate mollitia repellendus veniam commodi ratione quae fugiat nesciunt sapiente pariatur aliquam officia vero vel tempore odit quas beatae dignissimos asperiores qui quis recusandae necessitatibus laboriosam corporis provident magnam. Voluptatibus molestias repellendus perspiciatis necessitatibus quae eaque. Porro autem tenetur alias explicabo quasi voluptatem laboriosam veritatis fugiat quod minus saepe dolorum voluptas vel quas aut ratione aliquid doloremque ab dolorem dicta consequatur consectetur iste totam adipisci voluptatibus corporis itaque repudiandae! Quas quaerat vel dolore tempora iusto illo deserunt suscipit fugiat nobis vitae quod maiores accusamus ullam facilis voluptatum magnam laudantium! Quod optio expedita natus dolorum dolores laborum reprehenderit impedit sed a suscipit! Odio perspiciatis fugiat excepturi alias totam modi cupiditate voluptas veniam deserunt itaque quam fuga et eum earum esse quidem necessitatibus eaque asperiores nesciunt iure quo consequatur praesentium error. Velit enim perferendis. Saepe sint corporis sunt id magni natus qui debitis expedita impedit omnis. Libero esse similique laboriosam repellendus fugiat! Consequuntur temporibus eos expedita quo culpa incidunt laboriosam iure doloribus harum dolorem officia cupiditate totam adipisci sapiente eum tenetur deleniti! Voluptatem magni pariatur fugit ex modi quam nesciunt adipisci quis eaque perferendis?
                                        </div>
                                    </div>
                                </div>
                                </div>
                            </div>
                            <div id="tabsegmtopac" class="tab-pane box-cont <?=($target=='tabsegmtopac')?'active':''?>" >
                                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Inventore rem cum iusto officia nisi vel sed nam natus dicta amet dolores consequatur sint omnis quidem accusamus quod placeat! Molestiae quis repellat placeat ab unde cupiditate voluptate corporis fugit laborum totam harum eos id consectetur dolores nulla adipisci esse quos debitis aut autem blanditiis voluptatum at vero ad deleniti accusamus quaerat! Quidem aliquid delectus odio provident tenetur expedita culpa facilis facere molestiae officiis soluta iste maiores repellendus magni obcaecati perferendis dolores error architecto libero perspiciatis quasi. 
                                Magnam nulla fuga laudantium distinctio voluptatum quod at consequatur sed dolorum reiciendis tempore vitae aperiam.
                            </div>
                            <div id="tabconsulta" class="tab-pane <?=($target=='tabconsulta')?'active':''?>" >
                                <div class="row">
                                    <div class="span6">
                                        <div class="box-cont" style="padding-right: 0;">
                                            <h4>
                                                Listado de Consultas
                                                <?if(!$cslthoy):?>
                                                    <button id="btnnewcslt" class="btn btn-success btn-small pull-right" data-loading-text="<i class='icon-time icon-white'></i> Espere..."><i class="icon-folder-open icon-white"></i> Nueva Consulta</button>
                                                <?endif?>
                                            </h4>
                                            <?
                                            $sql = "SELECT TBconsulta.idcslt as idcslt, fch_cslt as Fecha, motivo as Motivo, COALESCE(numprm,'-') as '#PRM', COALESCE(numram,'-') as '#RAM'
                                                    FROM TBconsulta
                                                    LEFT JOIN (SELECT idcslt, count(idcslt) as numram FROM TBram GROUP BY idcslt) as TB1 ON TBconsulta.idcslt=TB1.idcslt
                                                    LEFT JOIN (SELECT idcslt, count(idcslt) as numprm FROM TBprm GROUP BY idcslt) as TB2 ON TBconsulta.idcslt=TB2.idcslt
                                                    WHERE TBconsulta.ced_pac='$ced_pac' 
                                                    ORDER BY fch_cslt DESC";
                                            // echo $sql;
                                            $result=mysql_query($sql,$link);
                                            if(mysql_num_rows($result)==0):
                                                echo '<div class="alert alert-error"> No hay consultas para mostrar.</div>';
                                            else:
                                                result2tb($result,'tbllstcslt');
                                            endif;
                                            ?>                                            
                                        </div>
                                    </div>
                                    <div id="divdescslt" class="span6">
                                        <div class="box-cont" style="padding-left: 0;">
                                            <div class="alert"> Seleccione una Consulta para ver toda su información.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="tabrampac" class="tab-pane <?=($target=='tabrampac')?'active':''?>" >
                                <div class="row">
                                    <div class="span6">
                                        <div class="box-cont" style="padding-right: 0;">
                                            <h4>
                                                Listado de RAM's
                                                <?if($cslthoy):?>
                                                    <button  class="btn btn-success btn-small pull-right btnnewram" idcslt="<?=$idcslthoy?>" data-loading-text="<i class='icon-time icon-white'></i> Espere..."><i class="icon-folder-open icon-white"></i> Nueva RAM</button>
                                                <?endif?>
                                            </h4>
                                            <?
                                            // $sql = "SELECT idram, fch_cslt as Fecha, descram as Descripción, descmed as Medicamento
                                            //         FROM TBram, TBclsseve, TBmaestra, (SELECT `idcslt`, `fch_cslt` FROM `TBconsulta` WHERE `ced_pac`='$ced_pac') as TB1  
                                            //         WHERE TBram.idcslt=TB1.idcslt AND TBram.idclsseve=TBclsseve.idclsseve AND TBram.codunisalud=TBmaestra.codunisalud
                                            //         ORDER BY fch_cslt DESC";
                                            $sql = "SELECT idram, fch_cslt as Fecha, descram as Descripción
                                                    FROM TBram, (SELECT `idcslt`, `fch_cslt` FROM `TBconsulta` WHERE `ced_pac`='$ced_pac') as TB1  
                                                    WHERE TBram.idcslt=TB1.idcslt
                                                    ORDER BY fch_cslt DESC";
                                            // echo $sql;
                                            $result=mysql_query($sql,$link);
                                            if(mysql_num_rows($result)==0):
                                                echo '<div class="alert alert-error"> No hay RAM\'s para mostrar.</div>';
                                            else:
                                                result2tb($result,'tbllstram');
                                            endif;
                                            ?>
                                        </div>
                                    </div>
                                    <div class="span6">
                                        <div id="divdesram" class="box-cont" style="padding-left: 0;">
                                            <div class="alert"> Seleccione una RAM para ver toda su información.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="tabprmpac" class="tab-pane <?=($target=='tabprmpac')?'active':''?>" >
                                <div class="row">
                                    <div class="span6">
                                        <div class="box-cont" style="padding-right: 0;">
                                            <h4>
                                                Listado de PRM's
                                                <?if($cslthoy):?>
                                                    <button class="btn btn-success btn-small pull-right btnnewprm" idcslt="<?=$idcslthoy?>" data-loading-text="<i class='icon-time icon-white'></i> Espere..."><i class="icon-folder-open icon-white"></i> Nuevo PRM</button>
                                                <?endif?>
                                            </h4>
                                            <?
                                            // $sql = "SELECT idprm, fch_cslt as Fecha, desprm as Descripción, clsprm as Clasificación
                                            //         FROM TBprm, TBclsprm, (SELECT `idcslt`, `fch_cslt` FROM `TBconsulta` WHERE `ced_pac`='$ced_pac') as TB1  
                                            //         WHERE TBprm.idcslt=TB1.idcslt AND TBprm.idclsprm=TBclsprm.idclsprm
                                            //         ORDER BY fch_cslt DESC";
                                            $sql = "SELECT idprm, fch_cslt as Fecha, desprm as Descripción, IF(`needrnm`,'Si','No') as RNM
                                                    FROM TBprm, (SELECT `idcslt`, `fch_cslt` FROM `TBconsulta` WHERE `ced_pac`='$ced_pac') as TB1  
                                                    WHERE TBprm.idcslt=TB1.idcslt
                                                    ORDER BY fch_cslt DESC";
                                            // echo $sql;
                                            $result=mysql_query($sql,$link);
                                            if(mysql_num_rows($result)==0):
                                                echo '<div class="alert alert-error"> No hay PRM\'s para mostrar.</div>';
                                            else:
                                                result2tb($result,'tbllstprm');
                                            endif;
                                            ?>
                                        </div>
                                    </div>
                                    <div id="divdesprm" class="span6">
                                        <div class="box-cont" style="padding-left: 0;">
                                            <div class="alert"> Seleccione un PRM para ver toda su información.</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div id="divmodal"></div>

        <?
    else:
        echo '<div class="alert alert-error"><span class="label label-important">ERROR:</span> No se ha seleccionado ningun paciente.</div>';
    endif;
else:
    echo '<div class="alert alert-error" >Petición rechazada por el servidor. Contacte al administrador del sistema e infórmele de este error.</div>';
endif;
?>