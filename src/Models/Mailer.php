<?php

namespace Wikichua\SAP\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class Mailer extends \Spatie\MailTemplates\Models\MailTemplate
{
    use \Illuminate\Database\Eloquent\SoftDeletes;
    use \Wikichua\SAP\Http\Traits\AllModelTraits;

    protected $table = 'mail_templates';
    protected $activity_logged = true;

    protected $dates = ['deleted_at'];
    protected $fillable = [
        'mailable',
        'subject',
        'html_template',
        'text_template',
        'created_by',
        'updated_by'
    ];

    protected $appends = [
        'readUrl',
    ];

    protected $searchableFields = ['subject'];

    protected $casts = [

    ];

    public function scopeFilterSubject($query, $search)
    {
        return $query->where('subject', 'like', "%{$search}%");
    }

    public function getReadUrlAttribute($value)
    {
        return $this->readUrl = route('brand.show', $this->id);
    }
}