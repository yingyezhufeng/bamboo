<?php

/**
 * This file is part of the Elcodi package.
 *
 * Copyright (c) 2014 Elcodi.com
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 * @author Aldo Chiecchia <zimage@tiscali.it>
 */

namespace Store\StoreProductBundle\Services;

use Doctrine\Common\Collections\ArrayCollection;

use Elcodi\ProductBundle\Entity\Interfaces\CategoryInterface;
use Elcodi\ProductBundle\Entity\Interfaces\ProductInterface;
use Elcodi\ProductBundle\Services\ProductCollectionProvider as BaseProductCollectionProvider;

/**
 * Product Collection provider
 *
 * Locale is injected because we can just query products, loading at the same
 */
class ProductCollectionProvider extends BaseProductCollectionProvider
{
    /**
     * Given a specific Product, return a simple collection of related products
     *
     * @param ProductInterface $product Product
     * @param int              $limit   Limit
     *
     * @return ArrayCollection
     */
    public function getRelatedProducts(ProductInterface $product, $limit)
    {
        $relatedProducts = new ArrayCollection();
        $principalCategory = $product->getPrincipalCategory();

        if ($principalCategory instanceof CategoryInterface) {

            $relatedProducts = $this
                ->productRepository
                ->findBy(array(
                    'principalCategory' => $product->getPrincipalCategory(),
                    'enabled'           => true
                ));

            $relatedProducts = new ArrayCollection($relatedProducts);
            $relatedProducts->removeElement($product);
            $relatedProducts = $relatedProducts->slice(0, $limit);
        }

        return $relatedProducts;
    }
}
