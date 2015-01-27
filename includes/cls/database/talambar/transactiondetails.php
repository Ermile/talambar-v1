<?php
namespace database\talambar;
class transactiondetails 
{
	public $transactiondetail_row      = array('null' =>'YES', 'show' =>'YES', 'label'=>'Row',           'type' => 'smallint@5',   );
	public $transaction_id             = array('null' =>'NO',  'show' =>'YES', 'label'=>'Transaction',   'type' => 'int@10',       'foreign'=>'transactions@id!id');
	public $product_id                 = array('null' =>'NO',  'show' =>'YES', 'label'=>'Product',       'type' => 'smallint@5',   'foreign'=>'products@id!product_title');
	public $transactiondetail_quantity = array('null' =>'NO',  'show' =>'YES', 'label'=>'Quantity',      'type' => 'int@10',       );
	public $transactiondetail_price    = array('null' =>'NO',  'show' =>'YES', 'label'=>'Price',         'type' => 'decimal@13,4', );
	public $transactiondetail_discount = array('null' =>'YES', 'show' =>'YES', 'label'=>'Discount',      'type' => 'decimal@13,4', );

	public function transactiondetail_row() 
	{
		$this->form("text")->name("row")->min(0)->max(9999)->type('number');
	}

	//------------------------------------------------------------------ id - foreign key
	public function transaction_id() 
	{
		$this->form("select")->name("transaction_")->min(0)->max(999999999)->required()->type("select")->validate()->id();
		$this->setChild();
	}

	//------------------------------------------------------------------ id - foreign key
	public function product_id() 
	{
		$this->form("select")->name("product_")->min(0)->max(9999)->required()->type("select")->validate()->id();
		$this->setChild();
	}
	public function transactiondetail_quantity() 
	{
		$this->form("text")->name("quantity")->max(999999999)->required()->type('number');
	}
	public function transactiondetail_price() 
	{
		$this->form("text")->name("price")->max(999999999999)->required()->type('number');
	}
	public function transactiondetail_discount() 
	{
		$this->form("text")->name("discount")->max(999999999999)->type('number');
	}
}
?>