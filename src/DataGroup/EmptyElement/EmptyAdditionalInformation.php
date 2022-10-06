<?php declare(strict_types=1);

namespace Jorklo\SwissQrBill\DataGroup\EmptyElement;

use Jorklo\SwissQrBill\DataGroup\QrCodeableInterface;

/**
 * @internal
 */
final class EmptyAdditionalInformation implements QrCodeableInterface
{
    public const TRAILER_EPD = 'EPD';

    public function getQrCodeData(): array
    {
        return [
            null,
            self::TRAILER_EPD
        ];
    }
}
