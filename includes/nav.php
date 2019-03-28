 <nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="contenedor">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Alternar navegador</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="index.php">Proyecto</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <ul class="nav navbar-nav">
            <li><a href="index.php">Inicio</a></li>
          

            <?php if(logeado()){ ?>

             <li><a href="admin.php">Administrador</a></li>
             <li><a href="logout.php">Logout</a></li>




            <?php }else{ ?>
              <li><a href="login.php">Login</a></li>
            <?php }?>
            
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>