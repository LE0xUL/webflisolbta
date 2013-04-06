//Funcion para cargar un html en divmain
// function loadmain(file2load , datos, msg){
//   console.log(msg || 'Carga archivo a divmain');
//   datos = (datos)? datos+"&k="+sft.ukey : "k="+sft.ukey;
//   $("#divmain").empty().spin("large","#216278").load(file2load, datos);
// }

// function load2div(div4load,file2load, datos, msg, callback){
//   console.log(msg || 'Carga archivo '+file2load);
//   $(div4load).empty().spin("large","#216278").load(file2load, datos, callback);
// }

function load2div(div4load,file2load, msg){
  console.log(msg || 'Carga archivo '+file2load);
  $(div4load).empty().spin("large","#216278").load(file2load);
}

function msgsyserror(msg){
  var html ='<div class="alert alert-error hide">';
  html += '<a class="close" data-dismiss="alert">&times;</a>';
  html += '<span class="label label-important">ERROR!!</span> ';
  html += msg;
  html += '</div>';
  $('#divmsgsys').prepend(html);
  $('#divmsgsys div.hide').slideDown(500).delay(10000).slideUp(500, function(){$(this).remove();});
}
function msgsyssucces(msg){
  var html ='<div class="alert alert-success hide">';
  html += '<a class="close" data-dismiss="alert">&times;</a>';
  //html += '<span class="label label-important">ERROR!!</span> ';
  html += msg;
  html += '</div>';
  $('#divmsgsys').prepend(html);
  $('#divmsgsys div.hide').slideDown(500).delay(6000).slideUp(500, function(){$(this).remove();});
}
//Formatea el campo automáticamente a solo números
function solonum(){
  if(isNaN( parseInt( $(this).val(), 10 )))
    $(this).val(null);
  else
    $(this).val(parseInt($(this).val(), 10));
}


//Funcion que carga el menu
function showmenu(menu){
    $('#divmenu').load('./inc/menu.php','k='+sft.ukey+'&menu='+menu);
}

//Funcion que carga la interfaz principal
function showhome(){
    console.log("Se carga la interfaz principal");
    load2div('#divmain','./inc/intlstpac.php', '','Se carga Interfaz de busqueda de usuario');
}

/**
  *VISTAS DEL MENU DE NAVEGACION
**/
    function showinthistoria(e){
        e.preventDefault();
        $('#divmsgsys').empty();
        showmenu('historia');
        load2div('#divmain','./inc/inthistoria.php', 'target=tabperfil','Se carga Interfaz de historia');
    }
    function reloadhistoria(target1,target2){
        var datos;
        if(target1){
            datos = "target="+target1;
            datos +=(target2)?'$target2='+target2:'';
        }
        load2div('#divmain','./inc/inthistoria.php', datos?datos:'','Se carga Interfaz de historia');
    }