<?php
/**
 * RentShare/Resources.php
 *
 * @package default
 */


namespace RentShare;

class AccessToken extends APIResource {
	public static $resource = '/access_tokens';
	public static $object_type = 'access_token';
}


class AutopayEnrollment extends APIResource {
	public static $resource = '/autopay_enrollments';
	public static $object_type = 'autopay_enrollment';
}


class Event extends APIResource {
	public static $resource = '/events';
	public static $object_type = 'event';
}


class Account extends APIResource {
	public static $resource = '/accounts';
	public static $object_type = 'account';
}


class DepositAccount extends APIResource {
	public static $resource = '/deposit_accounts';
	public static $object_type = 'deposit_account';
}


class Transaction extends APIResource {
	public static $resource = '/transactions';
	public static $object_type = 'transaction';
}


class PaymentMethod extends APIResource {
	public static $resource = '/payment_methods';
	public static $object_type = 'payment_method';
}


class Address extends APIResource {
	public static $resource = '/addresses';
	public static $object_type = 'address';
}


class RecurringInvoice extends APIResource {
	public static $resource = '/recurring_invoices';
	public static $object_type = 'recurring_invoice';
}


class Invoice extends APIResource {
	public static $resource = '/invoices';
	public static $object_type = 'invoice';
}


class InvoiceItem extends APIResource {
	public static $resource = '/invoice_items';
	public static $object_type = 'invoice_item';
}


class InvoicePayer extends APIResource {
	public static $resource = '/invoice_payers';
	public static $object_type = 'invoice_payer';
}


class InvoiceItemAllocation extends APIResource {
	public static $resource = '/invoice_item_allocations';
	public static $object_type = 'invoice_item_allocation';
}


?>
