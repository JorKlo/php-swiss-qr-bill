<?php declare(strict_types=1);

namespace Jorklo\SwissQrBill\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @internal
 */
final class ValidCreditorReference extends Constraint
{
    public string $message = 'The string "{{ string }}" is not a valid Creditor Reference.';
}
