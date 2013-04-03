<style>
    body { padding: 0px; }
        
    #divlogin {
        background-color: rgba(98,180,207,0.7);
        -moz-border-radius: 0 0 15px 15px;
        -webkit-border-radius: 0 0 15px 15px;
        border-radius:0 0 15px 15px;
        /*border: solid 5px #216278;
        border-top-width: 0px;*/
        box-shadow: 0px 0px 50px #024E68;
        color: #3AAACF;
        float: none;
        margin: auto;
        padding: 60px 15px 20px 15px;
        text-align: center;
    }
      
      #divlogin h3 {
          color: #3AAACF;
          line-height: 30px;
          padding-top: 30px;
          text-shadow: #024E68 2px 2px 5px;
      }
      
      #btningresar {
          color: #216278;
          text-shadow: #fff 1px 1px 1px;
      }
      
      #divfrmlogin {
          margin-top: 30px;
      }

      #diverrlogin {
          display: none;
          float: none;
          margin: auto;
          text-align: justify;
      }

      .navbar-fixed-top {
          margin-bottom: 0;
      }
      
      @media (max-width: 320px) {
          #divlogin {
              width: 300px;
          }
      }

      @media (max-width: 480px) {
          #divlogin {
              width: auto;
          }
          
      }
      
      @media (max-width: 767px){
          #divlogin {
              width: 300px;
          }
          #divlogin h3 {
              font-size: 20px;
              line-height: 25px;
          }
      }
      
      @media (max-width: 979px) and (min-width: 768px) {
          #divlogin {
              width: 300px;
          }
      }
</style>
<div id="divlogin" class="span4 hide">
    <img src="./img/titUnisalud.png" />
    <h3>PLATAFORMA PARA EL SEGUIMIENTO FARMACOTERAPEUTICO</h3>
    <div id="divfrmlogin" >
        <div class="btn btn-info">INGRESAR</div>
        <form id="frmlogin" class="hide form-inline" >
            <input id="usersft" type="text" class="input-small" name="usersft" placeholder="Usuario">
            <input id="pswdsft" type="password" class="input-small" name="pswdsft" placeholder="Contraseña">
            <button id="btnlogin" type="submit" class="btn btn-primary" data-loading-text="Verificando...">Entrar</button>
            <label class="checkbox"><input type="checkbox" name="ccls" value="1">No cerrar la sesión en este navegador</label>
        </form>
    </div>
    <div id="diverrlogin" class="span3 alert alert-error hide" ></div>
</div>
<script type="text/javascript">
    console.log("Se carga frmlogin.php");

    $(document).ready(function(){
        //animación para mostrar el login del sistema
        $("#divlogin").slideDown(1000);
    });
</script>
