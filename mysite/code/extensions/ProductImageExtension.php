<?php
class ProductImageExtension extends DataExtension {
    private static $belong_many_many = array('Products' => 'Product');
}