<?php
class Product extends DataObject {

	private static $db = array(
		'Title' => 'Varchar',
		'SubTitle' => 'Varchar',
		'Price' => 'Currency',
		'Donation' => 'Boolean',
		'Details' => 'HTMLText',
		'OnSale' => 'SS_Datetime',
		'OffSale' => 'SS_Datetime',
		'Stock' => 'Int'
	);

	private static $defaults = array(
		'Price' => '0.00',
		'Donation' => False,
		'Stock' => 0
	);

	private static $has_one = array(
		'ProductCategory' => 'ProductCategoryPage',
		'ProductImage' => 'Image'
	);

	private static $summary_fields = array (
		'Thumbnail' => 'Thumbnail',
        'Title' => 'Title of Product',
        'Price' => 'Price of item (NZD)',
        'Stock' => 'Stock'
    );

    public function getCMSFields() {

        $fields = parent::getCMSFields(); 

        $fields->addFieldToTab('Root.Main',    
            $image = new UploadField('ProductImage', 'Upload an image to use for this product (max 1 in total)'),
            'Price'   
        );

        $image->setFolderName('products');
        $image->setAllowedFileCategories('image');

        return $fields;            
    }

    public function onBeforeWrite() {
		parent::onBeforeWrite();
	}

	public function getThumbnail() {
		return $this->ProductImage()->CMSThumbnail();
	}

}
