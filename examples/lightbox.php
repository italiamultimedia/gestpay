<?php
$projectPath = realpath(__DIR__ . '/..') . DIRECTORY_SEPARATOR;
require $projectPath . 'vendor/autoload.php';

use ItaliaMultimedia\GestPay\Environment;

/* Edit */

$environment = Environment::SANDBOX;
$shopLogin = '';

$axerveScriptUrl = sprintf(
    'https://%s/pagam/javascript/axerve.js',
    Environment::SANDBOX == $environment ? 'sandbox.gestpay.net' : 'ecomm.sella.it'
);
$paymentId = isset($_GET['paymentId']) ? $_GET['paymentId'] : null;
$paymentToken = isset($_GET['paymentToken']) ? $_GET['paymentToken'] : null;
?>
<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Gestpay</title>
    </head>
    <body>
        <?php if (!empty($paymentId) && !empty($paymentToken)) { ?>
            <script src="<?=$axerveScriptUrl?>"></script>
            <script>
                <?php if (Environment::SANDBOX == $environment) { ?>
                    axerve.debug = true;
                <?php } ?>
                axerve.lightBox.shop = '<?=$shopLogin?>';
                axerve.lightBox.open(
                    '<?=$paymentId?>',
                    '<?=$paymentToken?>',
                    function (response) {
                        console.log('axerve callback response:'); //XXX
                        console.log(response); //XXX
                    }
                );
            </script>
        <?php } ?>
    </body>
</html>
