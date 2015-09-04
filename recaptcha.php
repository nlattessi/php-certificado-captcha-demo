<?php
require_once __DIR__ . '/vendor/recaptcha/src/autoload.php';
require_once __DIR__ . '/lib/Certificado.php';

// Register API keys at https://www.google.com/recaptcha/admin
$siteKey = '6LcqTwwTAAAAAFN1qbYjyrXIE-IgBRc8Vk8k0zva';
$secret = '6LcqTwwTAAAAAJgUbSgbpK66GuMng3Y9K154KUXw';

// reCAPTCHA supported 40+ languages listed here: https://developers.google.com/recaptcha/docs/language
$lang = 'es';
?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
        <title>reCAPTCHA Example</title>
        <link rel="shortcut icon" href="//www.gstatic.com/recaptcha/admin/favicon.ico" type="image/x-icon"/>
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
        <h1>Ejemplo Certificado y reCAPTCHA</h1>
    <?php
    if (isset($_POST['g-recaptcha-response'])):

      // If the form submission includes the "g-captcha-response" field
      // Create an instance of the service using your secret
      $recaptcha = new \ReCaptcha\ReCaptcha($secret);

      // If file_get_contents() is locked down on your PHP installation to disallow
      // its use with URLs, then you can use the alternative request method instead.
      // This makes use of fsockopen() instead.
      //  $recaptcha = new \ReCaptcha\ReCaptcha($secret, new \ReCaptcha\RequestMethod\SocketPost());

      // Make the call to verify the response and also pass the user's IP address
      $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);

      // If the response is a success, that's it!
      if ($resp->isSuccess()) {
          ob_end_clean();
          header("Content-Encoding: None", true);
          $certificado = crear_certificado($_POST);
          $certificado->output();
      } else {
          // If it's not successfull, then one or more error codes will be returned.
          ?>
          <h2>Hubo algun problema con reCAPTCHA...</h2>
          <p>El siguiente error fue devuelto: <?php
              foreach ($resp->getErrorCodes() as $code) {
                  echo '<tt>' , $code , '</tt> ';
              }
          ?></p>
          <p>Chequear el codigo de error en: <tt><a href="https://developers.google.com/recaptcha/docs/verify#error-code-reference">https://developers.google.com/recaptcha/docs/verify#error-code-reference</a></tt>.
          <p><strong>Nota:</strong> El codigo de error <tt>missing-input-response</tt> puede significar que no se completó el reCAPTCHA.</p>
          <p>Por favor <a href='javascript:history.go(-1)'>vuelve</a> a intentarlo.</p>
    <?php
  }
else:
// Add the g-recaptcha tag to the form you want to include the reCAPTCHA element
    ?>
    <p>Completar el form y aceptar el reCAPTCHA.</p>
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

            <div class="g-recaptcha" data-sitekey="<?php echo $siteKey; ?>"></div>
            <script type="text/javascript"
                    src="https://www.google.com/recaptcha/api.js?hl=<?php echo $lang; ?>">
            </script>
            <p><input type="submit" value="Generar" /></p>
        </fieldset>
    </form>
<?php endif; ?>
</body>
</html>
