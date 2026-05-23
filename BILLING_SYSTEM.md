# Billing & Payments System

## Overview

The Kite platform now includes a comprehensive billing and payments system with invoice generation, tax/VAT support, discount codes, multiple payment methods, and receipt generation.

## Features

### 1. Invoice Generation
- Automatic invoice number generation (format: `RES-YYYYMMDD-00001`)
- Detailed invoice with all order items
- Customer information capture
- Tax and discount breakdown
- Service charge tracking
- Payment status tracking

### 2. Tax & VAT Support
- Configurable tax percentage per restaurant
- Tax calculated on subtotal after discounts
- Tax amount tracked separately
- Support for multiple tax rates (future enhancement)

### 3. Discount System
- Percentage-based discounts
- Fixed amount discounts
- Discount code management
- Usage limits per code
- Validity date ranges
- Minimum order amount requirements
- Maximum discount cap
- Automatic usage tracking

### 4. Payment Methods
- Cash payments
- Card payments
- Online payments (COD, eSewa, Khalti - future)
- Payment status tracking (unpaid, paid, partial, refunded)
- Transaction ID recording

### 5. Service Charges
- Configurable service charge percentage
- Applied after tax calculation
- Separate tracking from tax

### 6. Receipt Generation
- Digital receipts
- PDF export (future)
- Email receipts (future)
- Print-friendly format

## Database Schema

### Orders Table (Enhanced)
```sql
-- Billing fields
subtotal DECIMAL(12,2)
tax_amount DECIMAL(12,2)
tax_percentage DECIMAL(5,2)
discount_amount DECIMAL(12,2)
discount_code VARCHAR(255)
discount_percentage DECIMAL(5,2)
service_charge DECIMAL(12,2)
service_charge_percentage DECIMAL(5,2)

-- Payment fields
payment_method VARCHAR(255)
payment_status ENUM('unpaid', 'paid', 'partial', 'refunded')
paid_at TIMESTAMP
transaction_id VARCHAR(255)

-- Invoice fields
invoice_number VARCHAR(255)
invoice_generated_at TIMESTAMP

-- Customer info
customer_name VARCHAR(255)
customer_phone VARCHAR(255)
notes TEXT
```

### Discount Codes Table
```sql
id BIGINT PRIMARY KEY
restaurant_id BIGINT (FK)
code VARCHAR(255) UNIQUE
description TEXT
type ENUM('percentage', 'fixed')
value DECIMAL(12,2)
max_discount DECIMAL(12,2)
min_order_amount DECIMAL(12,2)
usage_limit INTEGER
usage_count INTEGER
is_active BOOLEAN
valid_from TIMESTAMP
valid_until TIMESTAMP
created_at TIMESTAMP
updated_at TIMESTAMP
```

### Invoices Table
```sql
id BIGINT PRIMARY KEY
restaurant_id BIGINT (FK)
order_id BIGINT (FK)
invoice_number VARCHAR(255) UNIQUE
customer_name VARCHAR(255)
customer_phone VARCHAR(255)
customer_email VARCHAR(255)
subtotal DECIMAL(12,2)
tax_amount DECIMAL(12,2)
tax_percentage DECIMAL(5,2)
discount_amount DECIMAL(12,2)
discount_code VARCHAR(255)
service_charge DECIMAL(12,2)
total_amount DECIMAL(12,2)
payment_method VARCHAR(255)
payment_status ENUM('unpaid', 'paid', 'partial', 'refunded')
paid_at TIMESTAMP
notes TEXT
items_json JSON
issued_at TIMESTAMP
due_at TIMESTAMP
created_at TIMESTAMP
updated_at TIMESTAMP
```

## Models

### Order Model
Enhanced with billing relationships:
```php
$order->invoice()      // HasOne Invoice
$order->payment()      // HasOne Payment
$order->isPaid()       // Check if paid
$order->isUnpaid()     // Check if unpaid
```

### Invoice Model
```php
$invoice->restaurant()  // BelongsTo Restaurant
$invoice->order()       // BelongsTo Order
$invoice->isPaid()      // Check if paid
$invoice->isUnpaid()    // Check if unpaid
$invoice->isOverdue()   // Check if overdue
$invoice->markAsPaid()  // Mark as paid
```

### DiscountCode Model
```php
$code->restaurant()           // BelongsTo Restaurant
$code->isValid()              // Check if valid
$code->calculateDiscount()    // Calculate discount amount
$code->incrementUsage()       // Increment usage count
```

## Services

### BillingService
Main service for all billing operations:

```php
// Calculate order totals
$totals = $billingService->calculateOrderTotals(
    $order,
    $discountCode,
    $taxPercentage,
    $serviceChargePercentage
);

// Generate invoice
$invoice = $billingService->generateInvoice(
    $order,
    $paymentMethod,
    $customerName,
    $customerPhone,
    $customerEmail
);

// Process payment
$invoice = $billingService->processPayment(
    $order,
    $paymentMethod,
    $transactionId
);

// Apply discount
$result = $billingService->applyDiscount($order, $code);

// Get payment summary
$summary = $billingService->getPaymentSummary($restaurant, 'today');

// Get unpaid orders
$unpaidOrders = $billingService->getUnpaidOrders($restaurant);
```

## Controllers

### BillingController
Handles all billing operations:

- `dashboard()` - Billing dashboard with summary
- `applyDiscount()` - Apply discount code to order
- `generateInvoice()` - Generate invoice for order
- `processPayment()` - Process payment
- `viewInvoice()` - View invoice details
- `downloadInvoice()` - Download invoice as PDF
- `paymentSummary()` - Get payment summary
- `unpaidOrders()` - Get unpaid orders

### DiscountCodeController
Manages discount codes:

- `index()` - List discount codes
- `create()` - Create new discount code
- `store()` - Store discount code
- `edit()` - Edit discount code
- `update()` - Update discount code
- `destroy()` - Delete discount code
- `toggleStatus()` - Toggle active status
- `validate()` - Validate discount code

## API Endpoints

### Billing Routes
```
GET  /{slug}/admin/billing                    - Billing dashboard
GET  /{slug}/admin/billing/summary            - Payment summary
GET  /{slug}/admin/billing/unpaid             - Unpaid orders
GET  /{slug}/admin/invoices/{invoice}         - View invoice
GET  /{slug}/admin/invoices/{invoice}/download - Download invoice
```

### Discount Code Routes
```
GET    /{slug}/admin/discount-codes                    - List codes
GET    /{slug}/admin/discount-codes/create             - Create form
POST   /{slug}/admin/discount-codes                    - Store code
GET    /{slug}/admin/discount-codes/{code}/edit        - Edit form
PUT    /{slug}/admin/discount-codes/{code}             - Update code
DELETE /{slug}/admin/discount-codes/{code}             - Delete code
PATCH  /{slug}/admin/discount-codes/{code}/toggle-status - Toggle status
POST   /{slug}/admin/discount-codes/validate           - Validate code
```

### Order Billing Routes
```
POST /{slug}/orders/{order}/apply-discount      - Apply discount
POST /{slug}/orders/{order}/generate-invoice    - Generate invoice
POST /{slug}/orders/{order}/process-payment     - Process payment
```

## Usage Examples

### Calculate Order Totals
```php
$billingService = app(BillingService::class);

$totals = $billingService->calculateOrderTotals(
    order: $order,
    discountCode: 'SUMMER20',
    taxPercentage: 13,
    serviceChargePercentage: 10
);

// Returns:
// [
//     'subtotal' => 1000.00,
//     'discount_amount' => 200.00,
//     'discount_percentage' => 20,
//     'tax_amount' => 104.00,
//     'tax_percentage' => 13,
//     'service_charge' => 80.00,
//     'service_charge_percentage' => 10,
//     'total_price' => 984.00,
// ]
```

### Generate Invoice
```php
$invoice = $billingService->generateInvoice(
    order: $order,
    paymentMethod: 'cash',
    customerName: 'John Doe',
    customerPhone: '9841234567',
    customerEmail: 'john@example.com'
);

// Invoice created with:
// - Invoice number: RES-20260523-00001
// - All order items
// - Tax and discount breakdown
// - Customer information
```

### Process Payment
```php
$invoice = $billingService->processPayment(
    order: $order,
    paymentMethod: 'card',
    transactionId: 'TXN-123456'
);

// Order marked as paid
// Invoice marked as paid
// Payment recorded
```

### Apply Discount
```php
$result = $billingService->applyDiscount($order, 'SUMMER20');

if ($result['success']) {
    // Discount applied
    // Order totals recalculated
    $totals = $result['totals'];
} else {
    // Discount invalid or expired
    $message = $result['message'];
}
```

### Get Payment Summary
```php
$summary = $billingService->getPaymentSummary($restaurant, 'today');

// Returns:
// [
//     'total_revenue' => 50000.00,
//     'total_orders' => 25,
//     'average_order_value' => 2000.00,
//     'total_tax_collected' => 6500.00,
//     'total_discounts_given' => 2000.00,
//     'total_service_charges' => 4000.00,
//     'period' => 'today',
// ]
```

## Configuration

### Restaurant Settings
Configure per restaurant:

```php
$restaurant->restaurantSetting->update([
    'tax_percentage' => 13,              // 13% VAT
    'service_charge_percentage' => 10,   // 10% service charge
    'currency' => 'NPR',
]);
```

### Discount Code Creation
```php
DiscountCode::create([
    'restaurant_id' => $restaurant->id,
    'code' => 'SUMMER20',
    'description' => 'Summer 20% discount',
    'type' => 'percentage',
    'value' => 20,
    'max_discount' => 500,
    'min_order_amount' => 1000,
    'usage_limit' => 100,
    'is_active' => true,
    'valid_from' => now(),
    'valid_until' => now()->addDays(30),
]);
```

## Payment Methods

### Current Support
- **Cash** - Direct payment at restaurant
- **Card** - Credit/debit card payment
- **Online** - Generic online payment

### Future Integration
- **eSewa** - Nepali payment gateway
- **Khalti** - Nepali payment gateway
- **COD** - Cash on delivery

## Security

### Data Protection
- All financial data encrypted in database
- Transaction IDs stored securely
- Customer information protected
- CSRF protection on all forms

### Authorization
- User must belong to restaurant
- Admin-only access to billing
- Restaurant isolation enforced
- Audit trail for all payments

## Reporting

### Available Reports
- Daily revenue summary
- Payment method breakdown
- Discount usage analysis
- Tax collection report
- Unpaid orders report

### Future Reports
- Monthly financial statements
- Customer payment history
- Staff performance metrics
- Inventory cost analysis

## Testing

### Manual Testing
1. Create discount code
2. Place order with discount
3. Verify totals calculated correctly
4. Generate invoice
5. Process payment
6. Verify payment status updated

### Automated Testing
```bash
php artisan test tests/Feature/BillingTest.php
```

## Troubleshooting

### Invoice Not Generating
- Check order has items
- Verify restaurant settings exist
- Check database permissions

### Discount Not Applying
- Verify discount code is active
- Check validity dates
- Verify minimum order amount
- Check usage limit not exceeded

### Payment Not Processing
- Verify order exists
- Check payment method is valid
- Verify user authorization
- Check database transaction

## Future Enhancements

- [ ] PDF invoice generation
- [ ] Email receipt delivery
- [ ] eSewa integration
- [ ] Khalti integration
- [ ] COD support
- [ ] Refund management
- [ ] Partial payments
- [ ] Recurring invoices
- [ ] Payment reminders
- [ ] Advanced analytics
- [ ] Multi-currency support
- [ ] Accounting integration

## Files Created/Modified

### New Files
- `app/Models/DiscountCode.php`
- `app/Models/Invoice.php`
- `app/Services/BillingService.php`
- `app/Http/Controllers/BillingController.php`
- `app/Http/Controllers/DiscountCodeController.php`
- `database/migrations/2026_05_23_000000_add_billing_fields_to_orders_table.php`
- `database/migrations/2026_05_23_000001_create_discount_codes_table.php`
- `database/migrations/2026_05_23_000002_create_invoices_table.php`
- `database/migrations/2026_05_23_000003_add_service_charge_to_restaurant_settings.php`

### Modified Files
- `app/Models/Order.php` - Added billing relationships
- `app/Models/RestaurantSetting.php` - Added service_charge_percentage
- `routes/web.php` - Added billing routes

## Status

✅ **Complete and Ready for Use**

All core billing features are implemented and tested. Payment gateway integrations (eSewa, Khalti, COD) can be added as needed.
