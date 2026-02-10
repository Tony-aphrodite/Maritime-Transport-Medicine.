<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZonaHoraria extends Model
{
    use HasFactory;

    protected $table = 'zonas_horarias';

    protected $fillable = [
        'nombre',
        'codigo',
        'offset',
        'offset_minutos',
        'activo',
        'orden',
    ];

    protected $casts = [
        'activo' => 'boolean',
        'offset_minutos' => 'integer',
        'orden' => 'integer',
    ];

    /**
     * Scope for active timezones
     */
    public function scopeActive($query)
    {
        return $query->where('activo', true);
    }

    /**
     * Scope for ordered timezones
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('orden')->orderBy('nombre');
    }

    /**
     * Get all active timezones for dropdown
     */
    public static function getForDropdown()
    {
        return static::active()
            ->ordered()
            ->get()
            ->map(function ($tz) {
                return [
                    'codigo' => $tz->codigo,
                    'nombre' => $tz->nombre,
                    'offset' => $tz->offset,
                    'label' => $tz->nombre . ' (' . $tz->offset . ')',
                ];
            });
    }

    /**
     * Get timezone label by code
     */
    public static function getLabelByCode($code)
    {
        $tz = static::where('codigo', $code)->first();
        return $tz ? $tz->nombre . ' (' . $tz->offset . ')' : $code;
    }

    /**
     * Get short label by code (for confirmation pages)
     */
    public static function getShortLabelByCode($code)
    {
        $tz = static::where('codigo', $code)->first();
        return $tz ? $tz->nombre : $code;
    }
}
