<?php declare(strict_types=1);

namespace Jorklo\SwissQrBill\PaymentPart\Output\HtmlOutput\Template;

class TitleElementTemplate
{
    public const TEMPLATE = <<<EOT
<h2>{{ {{ title }} }}</h2>
EOT;
}
