<?php declare(strict_types=1);

namespace Jorklo\SwissQrBill\DataGroup;

/**
 * @internal
 */
interface QrCodeableInterface
{
    public function getQrCodeData(): array;
}
