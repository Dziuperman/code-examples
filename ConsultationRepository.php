<?php

namespace App\Http\Repositories;

use App\Dto\Smc\ConsultationSaveDto;
use App\Models\Smc\SmcConsultation;

final class ConsultationRepository
{
    public function add(ConsultationSaveDto $dto): bool
    {
        $consultation = new SmcConsultation();

        $consultation->fill([
            'depositing_in_safe_date' => $dto->depositingInSafeDate,
            'date' => $dto->date,
            'is_difficult_case' => $dto->isDifficultCase,
            'price' => $dto->price->amount(),
            'contractor_id' => $dto->contractorId,
            'smc_consultation_type_id' => $dto->smcConsultationTypeId,
            'description' => $dto->description,
            'agent_id' => $dto->agentId,
        ]);

        return $consultation->save();
    }
}
