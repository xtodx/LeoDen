<?php

namespace Tests\Unit;

use App\Dto\Request;
use App\Dto\Transaction;
use App\Services\RequestMoneyValidator;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class RequestMoneyValidatorTest extends TestCase
{
    private RequestMoneyValidator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new RequestMoneyValidator();
    }

    public function deviationDataProvider()
    {
        return [
            'case_sensitive_currency' => [100, 'usd', 90, 'USD', 10.0, true],
            'currency_case_insensitive' => [100, 'USD', 90, 'usd', 10.0, true],
            'deviation_zero' => [100, 'USD', 99, 'USD', 0.0, false],
            'deviation_over_100' => [100, 'USD', 1, 'USD', 10000.0, true],
            'small_deviation_false' => [100, 'USD', 97.54, 'USD', 1.0, false],
            'small_deviation_true' => [100, 'USD', 99, 'USD', 1.0, true],
            'large_deviation_true' => [100, 'USD', 50, 'USD', 50.0, true],
            'large_deviation_false' => [100, 'USD', 40, 'USD', 50.0, false],
        ];
    }

    /**
     * @dataProvider deviationDataProvider
     */
    public function testDeviationScenarios($reqAmount, $reqCurrency, $transAmount, $transCurrency, $deviation, $expectedResult)
    {
        Config::set('services.transaction_validation.deviation', $deviation);

        $request = new Request(amount: $reqAmount, currency: $reqCurrency);
        $transaction = new Transaction(amount: $transAmount, currency: $transCurrency);

        $result = $this->validator->validate($request, $transaction);

        $this->assertEquals($expectedResult, $result);
    }
}
