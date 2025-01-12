<?php declare(strict_types=1);

namespace Jorklo\SwissQrBill\PaymentPart\Output\HtmlOutput\Template;

class PaymentPartTemplateOnlyCode
{
    public const TEMPLATE = <<<EOT
<style>
#qr-bill {
	box-sizing: border-box;
	border-collapse: collapse;
	color: #000;
}

#qr-bill * {
	font-family: Arial, Frutiger, Helvetica, "Liberation Sans";
}

#qr-bill img.qr-bill-placeholder {
    margin-top: 1pt;
}

#qr-bill-separate-info {
    text-align: center;
    font-size: 8pt;
    line-height: 9pt;
	border-bottom: 0.75pt solid black;
	height: 5mm;
	vertical-align: middle;
}

/* h1 / h2 */
#qr-bill h1 {
	font-size: 11pt;
	font-weight: bold;
	margin: 0;
	padding: 0;
	height: 7mm;
}

#qr-bill h2 {
	font-weight: bold;
	margin: 0;
	padding: 0;
}

#qr-bill-payment-part h2 {
	font-size: 8pt;
	line-height: 11pt;
    margin-top: 11pt;
}

#qr-bill-receipt h2 {
	font-size: 6pt;
	line-height: 8pt;
    margin-top: 8pt;
}

#qr-bill-payment-part h2:first-child,
#qr-bill-receipt h2:first-child {
	margin-top: 0;
}

/* p */
#qr-bill p {
	font-weight: normal;
	margin: 0;
	padding: 0;
}

#qr-bill-receipt p {
	font-size: 8pt;
	line-height: 9pt;
}

#qr-bill-payment-part p {
	font-size: 10pt;
	line-height: 11pt;
}

#qr-bill-amount-area-receipt p{
    line-height: 11pt;
}

#qr-bill-amount-area p{
    line-height: 13pt;
}

#qr-bill-payment-further-information p {
    font-size: 7pt;
    line-height: 9pt;
}

/* Receipt */
#qr-bill-receipt {
    box-sizing: border-box;
    width: 62mm;
	border-right: 0.2mm solid black;
	padding-left: 5mm;
	padding-top: 5mm;
	vertical-align: top;
}

#qr-bill-information-receipt {
    height: 56mm;
}

#qr-bill-amount-area-receipt {
    height: 14mm;
}

#qr-bill-currency-receipt {
	float: left;
	margin-right: 2mm;
}

#qr-bill-acceptance-point {
    height: 18mm;
    text-align: right;
    margin-right: 5mm;
}

#qr-bill img#placeholder_amount_receipt {
    float: right;
    margin-top: -9pt;
    margin-right: 5mm;
}

/* Main part */
#qr-bill-payment-part {
    box-sizing: border-box;
    width: 148mm;
	padding-left: 5mm;
	padding-top: 5mm;
	padding-right: 5mm;
	vertical-align: top;
}

#qr-bill-payment-part-left {
    float: left;
    box-sizing: border-box;
    width: 51mm;
}

#qr-bill-swiss-qr-image {
	width: 46mm;
	height: 46mm;
	margin: 5mm;
	margin-left: 0;
}

#qr-bill-amount-area {
    height: 22mm;
}

#qr-bill-currency {
	float: left;
	margin-right: 2mm;
}

#qr-bill-payment-further-information {
    clear: both;
}

#qr-bill img#placeholder_amount {
    margin-left: 11mm;
    margin-top: -11pt;
}

{{ printable-content }}
</style>

<img style="" src="{{ swiss-qr-image }}" id="qr-bill-swiss-qr-image">

EOT;
}
