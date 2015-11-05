<?php

class ProductAdmin extends ModelAdmin {

    private static $managed_models = array(
        'Product'
    );

    private static $url_segment = 'products';

    private static $menu_title = 'Products';
}