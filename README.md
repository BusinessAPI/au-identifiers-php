# Australian ABN, ACN and TFN Validation for PHP

Validate and format Australian identifiers in PHP: ABN, ACN, ARBN, TFN, Director ID and VIN.

```bash
composer require businessapi/au-identifiers
```

```php
use BusinessApi\AuIdentifiers\{Abn, Acn, Tfn, DirectorId, Vin};

Abn::isValid('51 824 753 556');              // true
Abn::format('51824753556');                  // '51 824 753 556'
Acn::format('004085616');                    // '004 085 616'
Tfn::isValid('15195606');                    // true (8-digit, organisation)
Tfn::isValid('15195606', individual: true);  // false
DirectorId::isValid('036 123 456 789 012');  // true
Vin::normalise(' 1hgcm82633a004352 ');       // '1HGCM82633A004352'
```

Each class has `isValid()`, `normalise()` and, for ABN, ACN and ARBN, `format()`. `normalise()` and `format()` return `null` for invalid input. `Arbn` wraps `Acn`. PHP 8.1+.

## Rules

| | Format | Check |
|---|---|---|
| ABN | 11 digits | ATO modulus 89 |
| ACN, ARBN | 9 digits | ASIC check digit |
| TFN | 8 or 9 digits | ATO modulus 11 |
| Director ID | 15 digits, starts with 036 | Format only |
| VIN | 17 characters, no I, O or Q | Format only (ISO 3779) |

Spaces and hyphens in numbers are ignored. VINs are trimmed and upper-cased. Anything else makes the value invalid. Pass ACNs, TFNs and Director IDs as strings so leading zeros aren't lost.

8-digit TFNs (organisations only) use their own weights, and a 9-digit TFN with a leading zero is valid if either the 9-digit check or the 8-digit check on the rest passes. Set the individual option for a person's TFN, which is always 9 digits.

The Director ID check digit method is only given to ATO-registered software providers, so it's a format check here.

## Valid isn't registered

These checks run offline and only tell you a number is well formed. To check an ABN or ACN is registered and active, or to register companies, ABNs and business names, see [Business API](https://businessapi.com.au).

Also available for [Python](https://github.com/BusinessAPI/au-identifiers-python), [JavaScript / TypeScript](https://github.com/BusinessAPI/au-identifiers-js), [.NET](https://github.com/BusinessAPI/au-identifiers-dotnet), [Java](https://github.com/BusinessAPI/au-identifiers-java).

## Licence

MIT
