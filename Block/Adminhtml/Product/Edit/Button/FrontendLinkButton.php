<?php
declare(strict_types=1);

/**
 * File: FrontendLinkButton.php
 * @copyright Gallinago <https://gallinago.pl>
 */

namespace Gallinago\SimpleProductToConfigurableForward\Block\Adminhtml\Product\Edit\Button;

use Magento\Catalog\Block\Adminhtml\Product\Edit\Button\Generic;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Registry;
use Magento\Framework\View\Element\UiComponent\Context;
use Magento\Framework\Url;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class FrontendLinkButton
 * @package Gallinago\SimpleProductToConfigurableForward\Block\Adminhtml\Product\Edit\Button
 */
class FrontendLinkButton extends Generic
{
    /**
     * @var Url
     */
    private $urlBuilder;

    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var string
     */
    private $storeCode;

    /**
     * FrontendLinkButton constructor.
     * @param Context $context
     * @param Registry $registry
     * @param Url $urlBuilder
     * @param StoreManagerInterface $storeManager
     * @param string $storeCode
     */
    public function __construct(
        Context $context,
        Registry $registry,
        Url $urlBuilder,
        StoreManagerInterface $storeManager,
        string $storeCode = 'default'
    ) {
        parent::__construct($context, $registry);
        $this->urlBuilder = $urlBuilder;
        $this->storeManager = $storeManager;
        $this->storeCode = $storeCode;
    }

    /**
     * @return array
     */
    public function getButtonData(): array
    {
        try {
            $product = $this->getProduct();
            $store = $this->storeManager->getStore($this->storeCode);
            $baseUrl = trim($store->getBaseUrl(), '/');
            $url = "{$baseUrl}/catalog/product/view/id/{$product->getId()}";
            return [
                'label' => __('View product'),
                'class' => 'action-secondary',
                'on_click' => "window.open('{$url}','_blank');",
                'sort_order' => 999
            ];
        } catch (NoSuchEntityException $e) {
            return [];
        }
    }
}
