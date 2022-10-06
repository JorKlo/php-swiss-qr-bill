<?php declare(strict_types=1);

namespace Jorklo\SwissQrBill\PaymentPart\Output\HtmlOutput\Template;

class TextElementTemplate
{
    public const TEMPLATE = <<<EOT
<p>{{ text }}</p>
EOT;
}
