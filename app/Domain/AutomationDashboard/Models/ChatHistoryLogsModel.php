<?php

namespace App\Domain\AutomationDashboard\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class ChatHistoryLogsModel extends Model
{
    use HasFactory;

    protected $table = 'wbs_i_chat_logs_information';
    protected $primaryKey = 'conversation_log_history_id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'customer_psid',
        'chat_message',
        'chat_date',
        'existing_leads',
        'existing_relationship',
    ];
}