<?php

require_once __DIR__ . '/vendor/securimage/securimage.php';
require_once __DIR__ . '/vendor/PhpWord/Autoloader.php';

require_once __DIR__ . '/lib/QR.php';
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

      	\PhpOffice\PhpWord\Autoloader::register();

    		$phpWord = new \PhpOffice\PhpWord\PhpWord();

    		$filenameModelo = "modelo2.docx";

    		$template = $phpWord->loadTemplate($filenameModelo);
    		$template->setValue('proveedor', $_POST['prestador']);
    		$template->setValue('chofer', $_POST['chofer']);
    		$template->setValue('matricula', $_POST['matricula']);
    		$template->setValue('dni', $_POST['dni']);
    		$template->setValue('curso', $_POST['curso']);
    		$template->setValue('sede', $_POST['sede']);
    		$template->setValue('fechacurso', $_POST['fecha_curso']);
    		$template->setValue('numero', $_POST['transaccion']);
    		$template->setValue('fechatrans', $_POST['fecha_transaccion']);

    		// QR
      	$url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" . "status_chofer.php";
      	$qrFile = generarQR($url);
      	$template->setImageValue("image1.png", $qrFile);

    		$filename = 'output.docx';
    		$filenamePdf = 'output.pdf';
    		$template->saveAs($filename);

        try {
      		 	$comando = "soffice --convert-to pdf " . $filename . " --headless";
            //En DigitalOcean
            //$comando = "sudo /usr/bin/soffice --headless --convert-to pdf --outdir /var/www/test.lattessi.ml/html/certificado/ /var/www/test.lattessi.ml/html/certificado/" . $filename . " 2>/tmp/error.txt";
      			$output = shell_exec($comando);
      			//header('Content-Description: File Transfer');
      			//header('Content-type: application/force-download');
      			header('Content-Type: application/pdf');
      			header('Content-Disposition: inline; filename="'.$filenamePdf.'"');
      			header('Cache-Control: private, max-age=0, must-revalidate');
      			header('Pragma: public');
      			//header('Content-Disposition: attachment; filename='.basename($filenamePdf));
      			//header('Content-Transfer-Encoding: binary');
      			//header('Content-Length: '.filesize($filenamePdf));
      			readfile($filenamePdf);
    		} catch (Exception $e) {
      			echo "<pre>" . $e->getMessage() . "</pre>";
    		};

    		unlink($filename);
    		unlink($filenamePdf);
        unlink($qrFile);
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
              <img id="captcha" src="vendor/securimage/securimage_show.php" alt="CAPTCHA Image" />
              <a href="#" onclick="document.getElementById('captcha').src = 'vendor/securimage/securimage_show.php?' + Math.random(); return false">[ Cambiar ]</a>
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
