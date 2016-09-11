<?php
/**
 * Created by PhpStorm.
 * User: zattara
 * Date: 11.9.16
 * Time: 11.10
 */

namespace CSVParceBundle\MyClass;


class Validator
{
    /**
     * Set the filter with the rules import
     *
     * @param array $product
     * @return bool
     */
    public function __invoke(array $product)
    {
        if ($product['price'] < 1000) {
            if ($product['price'] > 5 || $product['stock'] > 10) {
                return true;
            }
        }
        return false;
    }
}