<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Status Chofer</title>

  <!-- CSS -->
  <!-- load bootstrap from CDN and custom CSS -->
  <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootswatch/3.3.5/cerulean/bootstrap.min.css">

</head>
<body>

  <div class="container">
    <div class="row">
      <div class="col-md-5  toppad  pull-right col-md-offset-3 ">
        <p class=" text-info">Fecha: <?php echo date('d/m/Y H:m') ?></p>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad" >
        <div class="panel panel-info">
          <div class="panel-heading">
            <h3 class="panel-title">Status Chofer</h3>
          </div>
          <div class="panel-body">
            <div class="row">
              <div class="col-md-3 col-lg-3 " align="center"> <img alt="User Pic" src="http://st.depositphotos.com/1006018/4724/v/950/depositphotos_47242093-Delivery-Truck-Driver-Waving-Cartoon.jpg" class="img-circle img-responsive"> </div>
              <div class=" col-md-9 col-lg-9 ">
                <table class="table table-user-information">
                  <tbody>
                    <tr>
                      <td>Nombre:</td>
                      <td>Martin Gimenez</td>
                    </tr>
                    <tr>
                      <td>DNI:</td>
                      <td>11222333</td>
                    </tr>
                    <tr>
                      <td>Matricula:</td>
                      <td>ABC123</td>
                    </tr>
                    <tr>
                      <td>Estado:</td>
                      <td><b>Certificado</b></td>
                    </tr>
                    <tr>
                      <td>Fecha Curso:</td>
                      <td>10/08/2015</td>
                    </tr>
                  </tbody>
                </table>
                <a href="#" class="btn btn-primary">Imprimir certificado</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

</body>
</html>
