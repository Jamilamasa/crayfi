# Cray Finance Laravel SDK

A first-class Laravel Composer package for integrating Cray Finance APIs. This package abstracts authentication, HTTP calls, validation, retries, and error handling, providing a clean and expressive API for developers.

## Requirements

- PHP 8.1+
- Laravel 9.0+

## Installation

Install the package via Composer:

```bash
composer require crayfi/cray-laravel
```

## Configuration

Publish the configuration file:

```bash
php artisan vendor:publish --tag=cray-config
```

This will create a `config/cray.php` file. Configure your API credentials in your `.env` file:

```env
CRAY_API_KEY=your_api_key_here
CRAY_ENV=sandbox
CRAY_TIMEOUT=30
CRAY_RETRIES=2
```

### Environment Switching

Set `CRAY_ENV` to `live` for production or `sandbox` for development/staging.

- `sandbox`: Uses `https://dev-gateman.v3.connectramp.com`
- `live`: Uses `https://pay.connectramp.com`

You can also explicitly set `CRAY_BASE_URL` if needed.

## Usage

The package provides a `Cray` facade for easy access to all modules.

### 1. Cards

Handle card transactions including initiation, charging, and querying.

```php
use Cray\Laravel\Facades\Cray;

// Initiate a card transaction
$response = Cray::cards()->initiate([
    'reference' => '4aeb118e-5009-450a-94fc-d74f6cd88646',
    'amount' => '100',
    'currency' => 'USD',
    'card_data' => [
        'pan' => '5399832641760090',
        'cvv' => '146',
        'expiryMonth' => '05',
        'expiryYear' => '50',
    ],
    'customer_information' => [
        'email' => 'test@testmail.com',
        'firstName' => 'John',
        'lastName' => 'Doe',
    ],
    // ... other required fields
]);

// Process payment (Charge)
$charge = Cray::cards()->charge([
    'transaction_id' => 'SRK4NC92PFHLGZW78A3E'
]);

// Query transaction status
$status = Cray::cards()->query('customer_reference_id');
```

### 2. Mobile Money (MoMo)

Process mobile money payments.

```php
// Initiate MoMo payment
$momo = Cray::momo()->initiate([
    'customer_reference' => 'e4d7c3b8-5f29-4b46-81a6-8d98c1e75812',
    'amount' => '3950',
    'currency' => 'XOF',
    'phone_no' => '2290161248277',
    'payment_provider' => 'MTN',
    'country' => 'benin',
    'firstname' => 'Cray',
    'lastname' => 'Momo',
]);

// Requery MoMo transaction
$status = Cray::momo()->requery('customer_reference_id');
```

### 3. Wallets

Fetch wallet balances.

```php
// Get all wallet balances
$balances = Cray::wallets()->balances();

// Get subaccounts
$subaccounts = Cray::wallets()->subaccounts();
```

### 4. FX & Conversions

Handle exchange rates and currency conversions.

```php
// Get specific exchange rate
$rate = Cray::fx()->rates([
    'source_currency' => 'USD',
    'destination_currency' => 'NGN'
]);

// Get rates by destination
$rates = Cray::fx()->ratesByDestination([
    'destination_currency' => 'NGN'
]);

// Generate a quote
$quote = Cray::fx()->quote([
    'source_currency' => 'NGN',
    'destination_currency' => 'USD',
    'source_amount' => 1500
]);

// Execute conversion
$conversion = Cray::fx()->convert([
    'quote_id' => 'quote:98a5d6d3-7cbc-4c7d-b4f6-d3bbbbe340b6'
]);

// Query conversions history
$history = Cray::fx()->conversions();
```

### 5. Payouts

Manage disbursements and transfers.

```php
// Get payment methods for a country
$methods = Cray::payouts()->paymentMethods('NG');

// Get banks (optionally filter by country code)
$banks = Cray::payouts()->banks('GH');

// Validate account name
$account = Cray::payouts()->validateAccount([
    'account_number' => '0112345678',
    'bank_code' => '058',
    'country_code' => 'GH' // if applicable
]);

// Disburse funds
$transfer = Cray::payouts()->disburse([
    'customer_reference' => 'ref-123',
    'account_number' => '898789',
    'bank_code' => '78978',
    'amount' => '10',
    'currency' => 'NGN',
    'narration' => 'Payment for services',
    'sender_info' => ['name' => 'My Business'],
    'recipient_name' => 'John Doe'
]);

// Requery payout
$status = Cray::payouts()->requery('transaction_id');
```

### 6. Refunds

Initiate and track refunds.

```php
// Initiate a refund (full or partial)
$refund = Cray::refunds()->initiate([
    'pan' => '4696660001638370',
    'subaccount_id' => '9999999999',
    'amount' => '1.2' // Optional, for partial refund
]);

// Check refund status
$status = Cray::refunds()->query('refund_reference_id');
```

## Error Handling

The package throws specific exceptions for different error scenarios. You should catch these exceptions to handle errors gracefully.

```php
use Cray\Laravel\Exceptions\CrayApiException;
use Cray\Laravel\Exceptions\CrayAuthenticationException;
use Cray\Laravel\Exceptions\CrayValidationException;
use Cray\Laravel\Exceptions\CrayTimeoutException;

try {
    $response = Cray::cards()->initiate($payload);
} catch (CrayAuthenticationException $e) {
    // Handle invalid API key or unauthorized access
    return response()->json(['error' => 'Unauthorized'], 401);
} catch (CrayValidationException $e) {
    // Handle validation errors (400/422)
    // $e->getErrors() contains the validation details
    return response()->json(['errors' => $e->getErrors()], 422);
} catch (CrayTimeoutException $e) {
    // Handle timeouts
    return response()->json(['error' => 'Request timed out'], 504);
} catch (CrayApiException $e) {
    // Handle other API errors (5xx, etc.)
    return response()->json(['error' => $e->getMessage()], 500);
}
```

## License

The MIT License (MIT).
