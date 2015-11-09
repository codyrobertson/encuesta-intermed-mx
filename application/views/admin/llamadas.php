<section class="helpSection">
  <?php if ($total <= 0){ ?>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <button class="btn btn-success btn-lg center-block" onclick="generarMuestraMedicos()">Generar</button>
        </div>
      </div>
    </div>
    <?php } else { ?>
    <div class="row">
      <div class="col-md-2">
        <p class="text-muted"><span class="glyphicon glyphicon-chevron-right"></span> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
      </div>
      <div class="col-md-2">
        <p class="text-muted"><span class="glyphicon glyphicon-chevron-right"></span> Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </p>
      </div>
      <div class="col-md-2">
        <p class="text-muted"><span class="glyphicon glyphicon-chevron-right"></span> Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
      </div>
      <div class="col-md-2">
        <p class="text-muted"><span class="glyphicon glyphicon-chevron-right"></span> Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
      </div>
      <div class="col-md-2">
        <p class="text-muted"><span class="glyphicon glyphicon-chevron-right"></span> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
      </div>
      <div class="col-md-2">
        <p class="text-muted"><span class="glyphicon glyphicon-chevron-right"></span> Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </p>
      </div>
    </div>
    <div class="row">
      <div class="col-md-2">
        <p class="text-muted"><span class="glyphicon glyphicon-chevron-right"></span> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
      </div>
      <div class="col-md-2">
        <p class="text-muted"><span class="glyphicon glyphicon-chevron-right"></span> Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </p>
      </div>
      <div class="col-md-2">
        <p class="text-muted"><span class="glyphicon glyphicon-chevron-right"></span> Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.</p>
      </div>
      <div class="col-md-2">
        <p class="text-muted"><span class="glyphicon glyphicon-chevron-right"></span> Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
      </div>
      <div class="col-md-2">
        <p class="text-muted"><span class="glyphicon glyphicon-chevron-right"></span> Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. </p>
      </div>
      <div class="col-md-2">
        <p class="text-muted"><span class="glyphicon glyphicon-chevron-right"></span> Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. </p>
      </div>
    </div>
  <?php } ?>
</section>
<?php if ($total <= 0){ ?>
<hr>
<?php } else {
  echo '<script type="text/javascript">document.addEventListener("DOMContentLoaded", function(event) {
    generarMuestraMedicos();
  });</script>';
?>
<section class="llamadasSection container-fluid">
  <div class="contaer-fluid">
    <div class="panel">

      <div class="panel-heading">
        <h3 class="panel-title">Realizar llamadas</h3>
      </div>
      <div class="panel-body">
        <table class="table">
          <thead>
            <tr>
              <th>Nombre</th>
              <th class="text-center">Teléfono</th>
              <th class="text-center">Correo</th>
              <th class="text-center">Conf. correo</th>
              <th class="text-center">Autorizo</th>
              <th class="text-center">No autorizo</th>
              <th class="text-center">Guardar</th>
            </tr>
          </thead>
          <tbody id="muestraMed">

          </tbody>
        </table>
      </div>
    </div>
  </div>
</section>
<?php } ?>