<?php declare(strict_types=1);

namespace Jorklo\SwissQrBill;

use Jorklo\SwissQrBill\Constraint\ValidCreditorInformationPaymentReferenceCombination;
use Jorklo\SwissQrBill\DataGroup\AddressInterface;
use Jorklo\SwissQrBill\DataGroup\Element\AdditionalInformation;
use Jorklo\SwissQrBill\DataGroup\Element\AlternativeScheme;
use Jorklo\SwissQrBill\DataGroup\Element\CreditorInformation;
use Jorklo\SwissQrBill\DataGroup\Element\Header;
use Jorklo\SwissQrBill\DataGroup\Element\PaymentAmountInformation;
use Jorklo\SwissQrBill\DataGroup\Element\PaymentReference;
use Jorklo\SwissQrBill\DataGroup\EmptyElement\EmptyAdditionalInformation;
use Jorklo\SwissQrBill\DataGroup\EmptyElement\EmptyAddress;
use Jorklo\SwissQrBill\DataGroup\QrCodeableInterface;
use Jorklo\SwissQrBill\Exception\InvalidQrBillDataException;
use Jorklo\SwissQrBill\QrCode\QrCode;
use Jorklo\SwissQrBill\String\StringModifier;
use Jorklo\SwissQrBill\Validator\SelfValidatableInterface;
use Jorklo\SwissQrBill\Validator\SelfValidatableTrait;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

final class QrBill implements SelfValidatableInterface
{
    use SelfValidatableTrait;

    private Header $header;
    private ?CreditorInformation $creditorInformation = null;
    private ?AddressInterface $creditor = null;
    private ?PaymentAmountInformation $paymentAmountInformation = null;
    private ?AddressInterface $ultimateDebtor = null;
    private ?PaymentReference $paymentReference = null;
    private ?AdditionalInformation $additionalInformation = null;
    /** @var AlternativeScheme[] */
    private array $alternativeSchemes = [];

    private function __construct(Header $header)
    {
        $this->header = $header;
    }

    public static function create(): self
    {
        $header = Header::create(
            Header::QRTYPE_SPC,
            Header::VERSION_0200,
            Header::CODING_LATIN
        );

        return new self($header);
    }

    public function getHeader(): Header
    {
        return $this->header;
    }

    public function setHeader(Header $header): self
    {
        $this->header = $header;

        return $this;
    }

    public function getCreditorInformation(): ?CreditorInformation
    {
        return $this->creditorInformation;
    }

    public function setCreditorInformation(CreditorInformation $creditorInformation): self
    {
        $this->creditorInformation = $creditorInformation;

        return $this;
    }

    public function getCreditor(): ?AddressInterface
    {
        return $this->creditor;
    }

    public function setCreditor(AddressInterface $creditor): self
    {
        $this->creditor = $creditor;
        
        return $this;
    }

    public function getPaymentAmountInformation(): ?PaymentAmountInformation
    {
        return $this->paymentAmountInformation;
    }

    public function setPaymentAmountInformation(PaymentAmountInformation $paymentAmountInformation): self
    {
        $this->paymentAmountInformation = $paymentAmountInformation;
        
        return $this;
    }

    public function getUltimateDebtor(): ?AddressInterface
    {
        return $this->ultimateDebtor;
    }

    public function setUltimateDebtor(AddressInterface $ultimateDebtor): self
    {
        $this->ultimateDebtor = $ultimateDebtor;
        
        return $this;
    }

    public function getPaymentReference(): ?PaymentReference
    {
        return $this->paymentReference;
    }

    public function setPaymentReference(PaymentReference $paymentReference): self
    {
        $this->paymentReference = $paymentReference;
        
        return $this;
    }

    public function getAdditionalInformation(): ?AdditionalInformation
    {
        return $this->additionalInformation;
    }

    public function setAdditionalInformation(AdditionalInformation $additionalInformation): self
    {
        $this->additionalInformation = $additionalInformation;

        return $this;
    }

    public function getAlternativeSchemes(): array
    {
        return $this->alternativeSchemes;
    }

    public function setAlternativeSchemes(array $alternativeSchemes): self
    {
        $this->alternativeSchemes = $alternativeSchemes;

        return $this;
    }

    public function addAlternativeScheme(AlternativeScheme $alternativeScheme): self
    {
        $this->alternativeSchemes[] = $alternativeScheme;

        return $this;
    }

    /**
     * @throws InvalidQrBillDataException
     */
    public function getQrCode(?string $fileFormat = null): QrCode
    {
        if (!$this->isValid()) {
            throw new InvalidQrBillDataException(
                'The provided data is not valid to generate a qr code. Use getViolations() to find details.'
            );
        }

        return QrCode::create(
            $this->getQrCodeContent(),
            $fileFormat
        );
    }

    private function getQrCodeContent(): string
    {
        $elements = [
            $this->getHeader(),
            $this->getCreditorInformation(),
            $this->getCreditor(),
            new EmptyAddress(), # Placeholder for ultimateCreditor, which is currently not yet allowed to be used by the implementation guidelines
            $this->getPaymentAmountInformation(),
            $this->getUltimateDebtor() ?: new EmptyAddress(),
            $this->getPaymentReference(),
            $this->getAdditionalInformation() ?: new EmptyAdditionalInformation(),
            $this->getAlternativeSchemes()
        ];

        $qrCodeStringElements = $this->extractQrCodeDataFromElements($elements);

        return implode("\n", $qrCodeStringElements);
    }

    private function extractQrCodeDataFromElements(array $elements): array
    {
        $qrCodeElements = [];

        foreach ($elements as $element) {
            if ($element instanceof QrCodeableInterface) {
                $qrCodeElements[] = $element->getQrCodeData();
            } elseif (is_array($element)) {
                $qrCodeElements[] = $this->extractQrCodeDataFromElements($element);
            }
        }

        $qrCodeElements = array_merge(... $qrCodeElements);

        array_walk($qrCodeElements, static function (&$string) {
            if (is_string($string)) {
                $string = StringModifier::replaceLineBreaksAndTabsWithSpaces($string);
                $string = StringModifier::replaceMultipleSpacesWithOne($string);
                $string = trim($string);
            }
        });

        return $qrCodeElements;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addConstraint(
            new ValidCreditorInformationPaymentReferenceCombination()
        );

        $metadata->addPropertyConstraints('header', [
            new Assert\NotNull(),
            new Assert\Valid()
        ]);

        $metadata->addPropertyConstraints('creditorInformation', [
            new Assert\NotNull(),
            new Assert\Valid()
        ]);

        $metadata->addPropertyConstraints('creditor', [
            new Assert\NotNull(),
            new Assert\Valid()
        ]);

        $metadata->addPropertyConstraints('paymentAmountInformation', [
            new Assert\NotNull(),
            new Assert\Valid()
        ]);

        $metadata->addPropertyConstraints('ultimateDebtor', [
            new Assert\Valid()
        ]);

        $metadata->addPropertyConstraints('paymentReference', [
            new Assert\NotNull(),
            new Assert\Valid()
        ]);

        $metadata->addPropertyConstraints('alternativeSchemes', [
            new Assert\Count([
                'max' => 2
            ]),
            new Assert\Valid([
                'traverse' => true
            ])
        ]);
    }
}
