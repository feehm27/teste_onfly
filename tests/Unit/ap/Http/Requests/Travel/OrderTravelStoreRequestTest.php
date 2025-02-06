<?php

namespace Tests\Unit\Http\Requests\Travel;

use App\Http\Requests\Travel\OrderTravelStoreRequest;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Validator;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class OrderTravelStoreRequestTest extends TestCase
{
    use WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        $this->request = new OrderTravelStoreRequest();
    }

    public function testShouldContainAllExpectedRules()
    {
        $expect = [
            'name_applicant' => ['required', 'string'],
            'destination' => ['required', 'string'],
            'departure_date' => ['required', 'date_format:Y-m-d H:i:s', 'after:today'],
            'return_date' => ['required', 'date_format:Y-m-d H:i:s', 'after:departure_date'],
        ];

        $this->assertEquals($expect, $this->request->rules());
    }

    public function testShouldAcceptValidData()
    {
        $validator = Validator::make([
            'name_applicant' => 'Teste nome do solicitante',
            'destination' => 'Belo Horizonte',
            'departure_date' => Carbon::now()->addDay()->format('Y-m-d H:i:s'),
            'return_date' => Carbon::now()->addDay()->addSeconds(30)->format('Y-m-d H:i:s'),
        ], $this->request->rules());

        $this->assertTrue(!$validator->fails());
    }

    public function testShouldBeAuthorized()
    {
        $this->assertEquals(true, $this->request->authorize());
    }

    public function testShouldReturnDateIsBeforeDepartureDate()
    {
        $request = [
            'departure_date' => Carbon::now()->subDay()->format('Y-m-d H:i:s'),
            'return_date' => Carbon::now()->subDays(2)->format('Y-m-d H:i:s')
        ];

        $validator = Validator::make($request, $this->request->rules());
        $errors = $validator->errors();
        $expected = 'O campo return date deve conter uma data posterior a departure date.';

        $this->assertEquals($expected, $errors->get('return_date')[0]);
    }


    #[DataProvider('providerInvalidData')]
    public function testInvalidData(string $fieldName, $value, string $expected)
    {
        $request = [$fieldName => $value];
        $validator = Validator::make($request, $this->request->rules());

        $errors = $validator->errors();
        $this->assertEquals($expected, $errors->get($fieldName)[0]);
    }

    public static function providerInvalidData(): array
    {
        return [
            'nameApplicantIsNull' => ['name_applicant', null, 'O campo name applicant é obrigatório.'],
            'nameApplicantIsInteger' => ['name_applicant', 9, 'O campo name applicant deve ser uma string.'],
            'destinationIsNull' => ['destination', null, 'O campo destination é obrigatório.'],
            'destinationIsInteger' => ['destination', 9, 'O campo destination deve ser uma string.'],
            'departureDateIsNull' => ['departure_date', null, 'O campo departure date é obrigatório.'],
            'departureDateIsInvalid' => [
                'departure_date',
                '2025/02/03',
                'A data informada para o campo departure date não respeita o formato Y-m-d H:i:s.'
            ],
            'departureDateIsAfterToday' => [
                'departure_date',
                Carbon::now()->subDay()->format('Y-m-d H:i:s'),
                'O campo departure date deve conter uma data posterior a today.'
            ],
            'returnDateIsNull' => ['return_date', null, 'O campo return date é obrigatório.'],
            'returnDateIsInvalid' => [
                'return_date',
                '2025/02/03',
                'A data informada para o campo return date não respeita o formato Y-m-d H:i:s.'
            ],
        ];
    }
}
