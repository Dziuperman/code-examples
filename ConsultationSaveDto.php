<?php

namespace App\Dto\Smc;

use App\ValueObjects\Smc\Money;
use DateTime;

class ConsultationSaveDto
{
    public function __construct(
        readonly ?DateTime $depositingInSafeDate,
        readonly ?DateTime $date,
        readonly bool $isDifficultCase,
        readonly Money $price,
        readonly int $contractorId,
        readonly int $smcConsultationTypeId,
        readonly ?string $description,
        readonly ?int $agentId,
    )
    {}
}
