<?php

class PaymentAdmin extends ModelAdmin {

    private static $managed_models = array(
        'Payment'
    );

    private static $url_segment = 'payments';

    private static $menu_title = 'Payments';

    public function getSearchContext() {
        $context = parent::getSearchContext();

        if($this->modelClass == 'Payment') {
        	$fields = $context->getFields();
        	$fields->removeByName('ID');
            $fields->push(
            	DropdownField::create(
	            	'PaidByID', 
	            	'Paid By:',
	            	Member::map_in_groups()
	            )->setEmptyString('(Any)')
	        );
        }

        return $context;
    }

    public function getList() {
        $list = parent::getList();

        $params = $this->request->requestVar('q'); // use this to access search parameters

        if($this->modelClass == 'Payment' && empty($params)) {
            $list = $list->exclude('AmountAmount', '0');
        }

        return $list;
    }
}