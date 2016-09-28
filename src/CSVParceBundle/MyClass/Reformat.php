<?php
/**
 * Created by PhpStorm.
 * User: zattara
 * Date: 11.9.16
 * Time: 13.55
 */

namespace CSVParceBundle\MyClass;


class Reformat
{
    /**
     * Set the date in accordance with the rules import
     *
     * @param array $product
     * @return array $product
     */
    public function __invoke(array $product)
    {
        if ($product['discontinued']) {
            $product['discontinuedDate'] = new \DateTime('now');
        } else $product['discontinuedDate'] = null;

        return $product;

    }
}