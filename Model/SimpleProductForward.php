<?php
declare(strict_types=1);

/**
 * File: SimpleProductForward.php
 * @copyright Gallinago <https://gallinago.pl>
 */

namespace Gallinago\SimpleProductToConfigurableForward\Model;

use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Api\ProductRepositoryInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable as ConfigurableType;
use Magento\ConfigurableProduct\Model\ResourceModel\Product\Type\Configurable;
use Magento\Framework\Controller\Result\Redirect;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Exception\NoSuchEntityException;
use Gallinago\SimpleProductToConfigurableForward\Api\SimpleProductForwardInterface;

/**
 * Class SimpleProductForward
 * @package Gallinago\SimpleProductToConfigurableForward\Model
 */
class SimpleProductForward implements SimpleProductForwardInterface
{
    /**
     * @var Configurable
     */
    private $configurableResourceModel;

    /**
     * @var ProductRepositoryInterface
     */
    private $productRepository;

    /**
     * @var RedirectFactory
     */
    private $redirectFactory;

    /**
     * @var ProductInterface|null
     */
    private $parentProduct;

    /**
     * SimpleProductForward constructor.
     * @param Configurable $configurableResourceModel
     * @param ProductRepositoryInterface $productRepository
     * @param RedirectFactory $redirectFactory
     */
    public function __construct(
        Configurable $configurableResourceModel,
        ProductRepositoryInterface $productRepository,
        RedirectFactory $redirectFactory
    ) {
        $this->configurableResourceModel = $configurableResourceModel;
        $this->productRepository = $productRepository;
        $this->redirectFactory = $redirectFactory;
    }

    /**
     * @param ProductInterface $product
     * @return bool
     * @throws NoSuchEntityException
     */
    public function shouldForward(ProductInterface $product): bool
    {
        return $product->getTypeId() === Type::TYPE_SIMPLE
            && $this->productHasVisibleParent($product)
            && $this->getParent($product)->getTypeId() === ConfigurableType::TYPE_CODE;
    }

    /**
     * @param ProductInterface $product
     * @return Redirect
     * @throws NoSuchEntityException
     */
    public function buildForward(ProductInterface $product): Redirect
    {
        $parent = $this->getParent($product);
        $suffix = $this->buildOptionsSuffix($product, $parent);
        $redirect = $this->redirectFactory->create();
        $redirect->setUrl($parent->getProductUrl() . $suffix);
        $redirect->setStatusHeader(301);
        return $redirect;
    }

    /**
     * @param ProductInterface $product
     * @return bool
     * @throws NoSuchEntityException
     */
    private function productHasVisibleParent(ProductInterface $product): bool
    {
        return (bool) $this->getParent($product)->getIsSalable();
    }

    /**
     * @param ProductInterface $product
     * @return ProductInterface
     * @throws NoSuchEntityException
     */
    private function getParent(ProductInterface $product): ProductInterface
    {
        if (!$this->parentProduct) {
            $parentIds = $this->configurableResourceModel->getParentIdsByChild($product->getId());
            $this->parentProduct = $this->productRepository->getById(current($parentIds));
        }
        return $this->parentProduct;
    }

    /**
     * @param ProductInterface $product
     * @param ProductInterface $parent
     * @return string
     */
    private function buildOptionsSuffix(ProductInterface $product, ProductInterface $parent): string
    {
        $configurableOptions = $parent->getTypeInstance()->getConfigurableOptions($parent);
        $suffix = '#';

        foreach ($configurableOptions as $attributeId => $attributeValues) {
            foreach ($attributeValues as $option) {
                if ($product->getSku() === $option['sku']) {
                    $suffix .= "{$attributeId}={$option['value_index']}&";
                }
            }
        }
        $suffix = substr($suffix, 0, -1);
        return $suffix;
    }
}
