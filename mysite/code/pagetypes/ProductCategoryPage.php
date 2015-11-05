<?php
class ProductCategoryPage extends Page {

	private static $has_many = array(
		'Products' => 'Product'
	);

	public function getCMSFields()
    {
        $fields = parent::getCMSFields();
         
        return $fields;
    }

}
class ProductCategoryPage_Controller extends Page_Controller {

	private $paymentMethod = 'PayPalExpress';

	private static $allowed_actions = array(
	    'OrderForm',
	    'complete'
	);

	public function init() {
		Requirements::set_force_js_to_bottom(true);
		//Requirements::javascript('themes/foundation/bower_components/foundation/js/foundation/foundation.clearing.js');
		parent::init();
	}

	public function OrderForm($id = null) {
	    $product = (empty($id)) ? new Product() : DataObject::get_one('Product', array('ID' => (int)$id));

	    try {
	    	$processor = PaymentFactory::factory($this->paymentMethod);
	    } 
	    catch (Exception $e) {
			$fields = new FieldList(array(new ReadonlyField($e->getMessage())));
			$actions = new FieldList();
			return new Form($this, 'OrderForm', $fields, $actions);
	    }

	    $fields = new FieldList();
		$fields->push(new HiddenField('Amount', 'Amount', $product->Price));
		$fields->push(new HiddenField('Currency', 'Currency', 'NZD'));
	    $fields->push(new HiddenField('PaymentMethod', 'PaymentMethod', 'PayPalExpress'));
	    
	    $actions = new FieldList(
	     	$button = new FormAction('processOrder', 'Buy Now')  
	    );

	    $button->addExtraClass('button expand');

	    $validator = $processor->getFormRequirements();

	    $form = new Form($this, "OrderForm", $fields, $actions, $validator);
	    $form->setTemplate('OrderForm');
	    return $form;
	}

	public function processOrder($data, Form $form) {
		try {
			$processor = PaymentFactory::factory($this->paymentMethod);
		} catch (Exception $e) {
			return array(
		    	'Content' => $e->getHTTPResponse() 
		    );
	    }

		try {
		    $processor->setRedirectURL($this->link() . 'complete');
	        $processor->capture($data);
	    } catch (Exception $e) {
			$result = $processor->gateway->getValidationResult();
			$payment = $processor->payment;
			return array(
				'Content' => $this->customise(array(
					'ExceptionMessage' => $e->getHTTPResponse(),
					'ValidationMessage' => $result->getHTTPResponse(),
					'OrderForm' => $this->OrderForm(),
					'Payment' => $payment
				)
			)->renderWith('PaymentTestPage'));
	    }
    }

    public function complete() {
		$paymentID = Session::get('PaymentID');
	    $payment = Payment::get()->byID($paymentID);

	    if ($payment && !in_array($payment->Status, array($payment::SUCCESS, $payment::PENDING))) {
	    	$payment->updateStatus(new PaymentGateway_Result($payment::FAILURE));
	    }

	    return array(
	      'Content' => $this->customise(array(
	        'Payment' => $payment
	      ))->renderWith('PaymentTestPage')
	    );
	}

}
