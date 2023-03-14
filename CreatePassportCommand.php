<?php

namespace App\Domain\Contractor\Commands\Contractor;

use App\Exceptions\Contractor\ContractorNotExistsException;
use App\Exceptions\Contractor\PassportAlreadyExistsException;
use App\Models\Contractor;
use App\Models\Contractor\ContractorPassport;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Throwable;

class CreatePassportCommand
{
    /**
     * @param int $contractorId
     * @param array $passportFields
     *
     * @return void
     *
     * @throws ContractorNotExistsException
     * @throws PassportAlreadyExistsException
     * @throws Throwable
     */
    public function handle(int $contractorId, array $passportFields): void
    {
        $contractor = Contractor::query()->find($contractorId);

        throw_if(empty($contractor), ContractorNotExistsException::class);

        throw_if($contractor->passport()->exists(), PassportAlreadyExistsException::class);

        DB::transaction(function () use ($contractor, $passportFields) {
            /**
             * @var ContractorPassport $passport
             */
            $passport = $contractor->passport()->create($passportFields);

            $passport->residencePlace()
                ->create(array_merge(
                    Arr::get($passportFields, 'residence_place'),
                    ['type' => 'official'],
                ));
        });
    }
}
