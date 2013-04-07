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

/**
  *CONTROLADORES DEL MENU DE NAVEGACION
**/
    // $("body").on('click','.navbar a', function(){
    //     var href = $(this).attr('href');
    //     showpage(href);
    // });

    $("body").on('click','#lnkhome', function(){
        showhome();
    });

    $("body").on('click','#lnkinstalaciones', function(){
        showinstalaciones();
    });

    $("body").on('click','#lnkconferencias', function(){
        showconferencias();
    });

    $("body").on('click','#lnktalleres', function(){
        showtalleres();
    });

    $("body").on('click','#lnkstands', function(){
        showstands();
    });

    $("body").on('click','#lnkculturalibre', function(){
        showculturalibre();
    });

    $("body").on('click','#lnkhackaton', function(){
        showhackaton();
    });

    $("body").on('click','#lnkdifusion', function(){
        showdifusion();
    });

    $("body").on('click','#lnkinstalador', function(){
        showinstalador();
    });

    $("body").on('click','#lnklogistica', function(){
        showlogistica();
    });

    $("body").on('click','#lnkpatrocinio', function(){
        showpatrocinio();
    });

    $("body").on('click','#lnkcontacto', function(){
        showcontacto();
    });

/**
  *VISTAS DEL MENU DE NAVEGACION
**/
    // function showpage(href){
    //     var page = 'jx/'+href+'.html';
    //     console.log(page)
    //     load2div('#divmain',page);
    // }

    function showinstalaciones(){
        load2div('#divmain','jx/instalaciones.html');
    }

    function showhome(){
        load2div('#divmain','jx/home.html');
    }

    function showconferencias(){
        load2div('#divmain','jx/conferencias.html');
    }

    function showtalleres(){
        load2div('#divmain','jx/talleres.html');
    }

    function showstands(){
        load2div('#divmain','jx/stands.html');
    }

    function showculturalibre(){
        load2div('#divmain','jx/culturalibre.html');
    }

    function showhackaton(){
        load2div('#divmain','jx/hackaton.html');
    }

    function showdifusion(){
        load2div('#divmain','jx/difusion.html');
    }

    function showinstalador(){
        load2div('#divmain','jx/instalador.html');
    }

    function showlogistica(){
        load2div('#divmain','jx/logistica.html');
    }

    function showpatrocinio(){
        load2div('#divmain','jx/patrocinio.html');
    }

    function showcontacto(){
        load2div('#divmain','jx/contacto.html');
    }