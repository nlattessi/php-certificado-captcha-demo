<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/lib/Certificado.php';
require_once __DIR__ . '/vendor/securimage/securimage.php';
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>Ejemplo certificado</title>
        <link rel='shortcut icon' type='image/x-icon' href='/favicon.ico' />
        <style type="text/css">
            body {
                margin: 1em 5em 0 5em;
                font-family: sans-serif;
            }
            fieldset {
                display: inline;
                padding: 1em;
            }
        </style>
    </head>
    <body>
        <h1>Ejemplo Certificado y CAPTCHA</h1>
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST'){
        $securimage = new Securimage();
        if ($securimage->check($_POST['captcha_code']) == false) {
            echo "El CAPTCHA ingresado es incorrecto.<br /><br />";
            echo "Por favor <a href='javascript:history.go(-1)'>vuelve</a> a intentarlo.";
            exit;
        }
        ob_end_clean();
        header("Content-Encoding: None", true);
        $certificado = crear_certificado($_POST);
        $certificado->output();
      } else {
    ?>
    <p>Completar el formulario e ingrese el CAPTCHA</p>
    <form action="" method="post">
        <fieldset>
            <legend>Form para el ejemplo:</legend>
            <p>Prestador: <input type="text" name="prestador" value="Cursos S.A."></p>
            <p>Nombre Chofer: <input type="text" name="chofer" value="Martin Gimenez"></p>
            <p>Matricula Chofer: <input type="text" name="matricula" value="ABC123"></p>
            <p>DNI Chofer: <input type="text" name="dni" value="11222333"></p>
            <p>Curso: <input type="text" name="curso" value="Anual"></p>
            <p>Sede del Curso: <input type="text" name="sede" value="San Martin 555"></p>
            <p>Fecha del Curso: <input type="text" name="fecha_curso" value="20/08/2015"></p>
            <p>Numero de Transacción: <input type="text" name="transaccion" value="ABC123-ZXC"></p>
            <p>Fecha de Transacción: <input type="text" name="fecha_transaccion" value="30/08/2015"></p>

            <hr>

            <p>
              CAPTCHA:
              <img id="captcha" src="/vendor/securimage/securimage_show.php" alt="CAPTCHA Image" />
              <a href="#" onclick="document.getElementById('captcha').src = '/vendor/securimage/securimage_show.php?' + Math.random(); return false">[ Cambiar ]</a>
            </p>
            <p>
              Ingrese el CAPTCHA: <input type="text" name="captcha_code" size="10" maxlength="6" />
            </p>

            <p><input type="submit" value="Generar" /></p>
        </fieldset>
    </form>
<?php } ?>
</body>
</html>
