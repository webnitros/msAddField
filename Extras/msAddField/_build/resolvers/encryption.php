<?php
$transport->xpdo->loadClass('transport.xPDOObjectVehicle', XPDO_CORE_PATH, true, true);
$transport->xpdo->loadClass('EncryptedVehicle', MODX_CORE_PATH . 'components/' . strtolower($transport->name) . '/model/', true, true);