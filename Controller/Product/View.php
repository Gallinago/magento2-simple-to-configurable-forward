<?php
declare(strict_types=1);

/**
 * File: View.php
 * @copyright Gallinago <https://gallinago.pl>
 */

namespace Gallinago\SimpleProductToConfigurableForward\Controller\Product;

use Exception;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\Catalog\Controller\Product\View as MagentoView;
use Magento\Catalog\Helper\Product\View as ViewHelper;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\Forward;
use Magento\Framework\Controller\Result\ForwardFactory;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\View\Result\PageFactory;
use Gallinago\SimpleProductToConfigurableForward\Api\SimpleProductForwardInterface;

/**
 * Class View
 * @package Gallinago\SimpleProductToConfigurableForward\Controller\Product
 */
class View extends MagentoView
{
    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var SimpleProductForwardInterface
     */
    private $simpleProductForward;

    /**
     * View constructor.
     * @param Context $context
     * @param ViewHelper $viewHelper
     * @param ForwardFactory $resultForwardFactory
     * @param PageFactory $resultPageFactory
     * @param ProductRepositoryInterface $productRepository
     * @param SimpleProductForwardInterface $simpleProductForward
     */
    public function __construct(
        Context $context,
        ViewHelper $viewHelper,
        ForwardFactory $resultForwardFactory,
        PageFactory $resultPageFactory,
        ProductRepositoryInterface $productRepository,
        SimpleProductForwardInterface $simpleProductForward
    ) {
        parent::__construct($context, $viewHelper, $resultForwardFactory, $resultPageFactory);
        $this->productRepository = $productRepository;
        $this->simpleProductForward = $simpleProductForward;
    }

    /**
     * @return Forward|Redirect
     */
    public function execute()
    {
        try {
            $product = $this->productRepository->getById((int) $this->getRequest()->getParam('id'));
            if ($this->simpleProductForward->shouldForward($product)) {
                return $this->simpleProductForward->buildForward($product);
            }
        } catch (Exception $e) {
        }
        return parent::execute();
    }
}
