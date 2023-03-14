<?php

namespace App\Models\Contractor;

use App\Models\Contractor;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Crypt;

/**
 * Class ContractorPassport
 *
 * @property-read int $id
 *
 * @property string $series
 * @property string $number
 *
 * @property Carbon $date_of_issue
 * @property string $issued_at
 *
 * @property string $subdivision_code
 *
 * @property string $place_of_birth
 *
 * @property int $contractor_id
 * @property-read Contractor $contractor
 *
 * @property PassportResidencePlace $residencePlace
 * @property PassportResidencePlace $realResidencePlace
 *
 * @property-read Carbon $created_at
 * @property-read Carbon $updated_at
 */
class ContractorPassport extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'contractor_passports';

    /**
     * @var array<int,string>
     */
    protected $fillable = [
        'series',
        'number',
        'date_of_issue',
        'issued_at',
        'subdivision_code',
        'place_of_birth',
        'contractor_id',
    ];

    /**
     * @var array<string,string>
     */
    protected $casts = [
        'series' => 'encrypted',
        'number' => 'encrypted',
        'date_of_issue' => 'encrypted',
        'issued_at' => 'encrypted',
        'subdivision_code' => 'encrypted',
        'place_of_birth' => 'encrypted',
    ];

    /**
     * @var array<int,string>
     */
    protected $with = [
        'residencePlace',
        'realResidencePlace',
    ];

    /**
     * @param Carbon $value
     * @return void
     */
    public function setDateOfIssueAttribute(Carbon $value): void
    {
        $this->attributes['date_of_issue'] = Crypt::encryptString($value->toDateString());
    }

    /**
     * @return BelongsTo<Contractor>
     */
    public function contractor(): BelongsTo
    {
        return $this->belongsTo(
            related: Contractor::class,
            foreignKey: 'contractor_id',
        );
    }

    /**
     * @return HasOne<PassportResidencePlace>
     */
    public function residencePlace(): HasOne
    {
        return $this->hasOne(
            related: PassportResidencePlace::class,
            foreignKey: 'contractor_passport_id',
        )->where('type', '=', 'official');
    }

    /**
     * @return HasOne<PassportResidencePlace>
     */
    public function realResidencePlace(): HasOne
    {
        return $this->hasOne(
            related: PassportResidencePlace::class,
            foreignKey: 'contractor_passport_id',
        )->where('type', '=', 'real');
    }
}
