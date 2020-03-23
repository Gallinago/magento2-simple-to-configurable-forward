<?php
declare(strict_types=1);

/**
 * File: registration.php
 * @copyright Gallinago <https://gallinago.pl>
 */

\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'Gallinago_SimpleProductToConfigurableForward',
    __DIR__
);
