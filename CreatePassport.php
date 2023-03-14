<?php

namespace App\GraphQL\Mutations\Contractor\Passport;

use App\Domain\Contractor\Commands\Contractor\CreatePassportCommand;
use App\Exceptions\Contractor\ContractorNotExistsException;
use App\Exceptions\Contractor\PassportAlreadyExistsException;
use App\GraphQL\Exceptions\GraphQLException;
use App\GraphQL\Exceptions\NotFoundGraphQLException;
use App\Http\Resources\Contractor\PassportResource;
use App\Models\Contractor\ContractorPassport;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Schema\Context;

class CreatePassport
{
    public function __construct(
        private readonly CreatePassportCommand $command
    )
    {
    }

    /**
     * @param $_
     * @param array $args
     * @param Context $context
     * @param ResolveInfo $resolveInfo
     *
     * @return array
     *
     * @throws NotFoundGraphQLException
     */
    public function __invoke(
        $_,
        array $args,
        Context $context,
        ResolveInfo $resolveInfo,
    ): array
    {
        try {
            $this->command->handle($args['contractorID'], $args['passport']);

            $passport = ContractorPassport::query()
                ->where('contractor_id', $args['contractorID'])
                ->first();

            return [
                '__typename' => 'PassportResult',
                'passport' => PassportResource::make(
                    $passport,
                )->response()->getData(),
            ];
        } catch (ContractorNotExistsException) {
            throw new NotFoundGraphQLException('Contractor not found');
        } catch (PassportAlreadyExistsException) {
            throw new GraphQLException('Passport already exists');
        }
    }
}
