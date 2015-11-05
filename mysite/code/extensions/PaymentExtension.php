<?php
class PaymentExtension extends DataExtension {
    private static $db = array(
		'OrderStatus' => "Enum('Pending, Paid, Shipped/Completed', 'Pending')"
	);

    private static $summary_fields = array (
		'ID' => 'ID',
        'Status' => 'Status',
        'OrderTotal' => 'Total Cost',
        'PaidBy.Name' => 'Ordered By',
        'OrderStatus' => 'Order Status'
    );

    public function OrderTotal() {
    	return '$'.$this->owner->Amount.' '.$this->owner->Currency;
    }
}