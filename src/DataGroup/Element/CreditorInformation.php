<?php declare(strict_types=1);

namespace Jorklo\SwissQrBill\DataGroup\Element;

use Jorklo\SwissQrBill\DataGroup\QrCodeableInterface;
use Jorklo\SwissQrBill\String\StringModifier;
use Jorklo\SwissQrBill\Validator\SelfValidatableInterface;
use Jorklo\SwissQrBill\Validator\SelfValidatableTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

final class CreditorInformation implements QrCodeableInterface, SelfValidatableInterface
{
    use SelfValidatableTrait;

    /**
     * IBAN or QR-IBAN of the creditor
     */
    private string $iban;

    private function __construct(string $iban)
    {
        $this->iban = StringModifier::stripWhitespace($iban);
    }

    public static function create(string $iban): self
    {
        return new self($iban);
    }

    public function getIban(): string
    {
        return $this->iban;
    }

    public function getFormattedIban(): string
    {
        return trim(chunk_split($this->iban, 4, ' '));
    }

    public function containsQrIban(): bool
    {
        $qrIid = substr($this->iban, 4, 5);

        if ($this->isValid() && (int) $qrIid >= 30000 && (int) $qrIid <= 31999) {
            return true;
        }

        return false;
    }

    public function getQrCodeData(): array
    {
        return [
            $this->getIban()
        ];
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        // Only IBANs with CH or LI country code
        $metadata->addPropertyConstraints('iban', [
            new Assert\NotBlank(),
            new Assert\Iban(),
            new Assert\Regex([
                'pattern' => '/^(CH|LI)/',
                'match' => true
            ])
        ]);
    }
}
