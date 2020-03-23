<?php
declare(strict_types=1);

/**
 * File: SimpleProductForwardInterface.php
 * @copyright Gallinago <https://gallinago.pl>
 */

namespace Gallinago\SimpleProductToConfigurableForward\Api;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Controller\Result\Redirect;

/**
 * Interface SimpleProductForwardInterface
 * @package Gallinago\SimpleProductToConfigurableForward\Api
 */
interface SimpleProductForwardInterface
{
    /**
     * @param ProductInterface $product
     * @return bool
     */
    public function shouldForward(ProductInterface $product): bool;

    /**
     * @param ProductInterface $product
     * @return Redirect
     */
    public function buildForward(ProductInterface $product): Redirect;
}
