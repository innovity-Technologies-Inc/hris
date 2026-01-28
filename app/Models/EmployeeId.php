<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class EmployeeId extends Model
{
    protected $table = 'employee_ids';

    protected $fillable = [
        'employee_id',
        'id_card_design_id',
        'status',
        'pdf_path',
        'card_number',
        'issue_date',
        'expiry_date'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the employee that owns this ID card
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    /**
     * Get the design used for this ID card
     */
    public function idCardDesign(): BelongsTo
    {
        return $this->belongsTo(IDCardDesign::class, 'id_card_design_id', 'id');
    }

    /**
     * Scope to get active ID cards
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get inactive ID cards
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Check if PDF file exists
     */
    public function pdfExists(): bool
    {
        return $this->pdf_path && Storage::disk('public')->exists($this->pdf_path);
    }

    /**
     * Get full path to PDF file
     */
    public function getFullPdfPath(): ?string
    {
        if ($this->pdfExists()) {
            return Storage::disk('public')->path($this->pdf_path);
        }
        return null;
    }

    /**
     * Get URL for PDF file
     */
    public function getPdfUrl(): ?string
    {
        if ($this->pdfExists()) {
            return Storage::url($this->pdf_path);
        }
        return null;
    }

    /**
     * Check if ID card is expired
     */
    public function isExpired(): bool
    {
        if (!$this->expiry_date) {
            return false;
        }
        return $this->expiry_date->isPast();
    }

    /**
     * Check if ID card is active and valid
     */
    public function isValid(): bool
    {
        return $this->status === 'active' && !$this->isExpired() && $this->pdfExists();
    }
}
