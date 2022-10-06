<?php declare(strict_types=1);

namespace Jorklo\SwissQrBill\PaymentPart\Output;

use Jorklo\SwissQrBill\QrBill;

interface OutputInterface
{
    public function getQrBill(): ?QrBill;

    public function getLanguage(): ?string;

    public function getPaymentPart();

    public function setPrintable(bool $printable);

    public function isPrintable(): bool;

    public function setQrCodeImageFormat(string $imageFormat);

    public function getQrCodeImageFormat(): string;
}
