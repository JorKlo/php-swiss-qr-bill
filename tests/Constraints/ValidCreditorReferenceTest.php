<?php declare(strict_types=1);

namespace Jorklo\Tests\SwissQrBill\Constraints;

use Jorklo\SwissQrBill\Constraint\ValidCreditorReference;
use Jorklo\SwissQrBill\Constraint\ValidCreditorReferenceValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

final class ValidCreditorReferenceTest extends ConstraintValidatorTestCase
{
    protected function createValidator()
    {
        return new ValidCreditorReferenceValidator();
    }

    public function testNullIsValid()
    {
        $this->validator->validate(null, new ValidCreditorReference());

        $this->assertNoViolation();
    }

    public function testEmptyStringIsValid()
    {
        $this->validator->validate('', new ValidCreditorReference());

        $this->assertNoViolation();
    }

    /**
     * @dataProvider getValidCreditorReferences
     */
    public function testValidCreditorReferences($value)
    {
        $this->validator->validate($value, new ValidCreditorReference());

        $this->assertNoViolation();
    }

    public function getValidCreditorReferences()
    {
        return [
            ['RF45 1234 5123 45'],
            ['RF451234512345']
        ];
    }

    /**
     * @dataProvider getInvalidCreditorReferences
     */
    public function testInvalidCreditorReferences($creditorReference)
    {
        $this->validator->validate($creditorReference, new ValidCreditorReference([
            'message' => 'myMessage',
        ]));

        $this->buildViolation('myMessage')
            ->setParameter('{{ string }}', $creditorReference)
            ->assertRaised();
    }

    public function getInvalidCreditorReferences()
    {
        return [
            ['RF43 1234 5123 45'],
            ['RF431234512345'],
            ['RF431234512345Ä'],
            ['foo']
        ];
    }
}
