<?php

namespace App\Domain\AutomationDashboard\Repositories;

use App\Domain\AutomationDashboard\DTO\CreateAutomationDashboardDTO;
use App\Domain\AutomationDashboard\DTO\UpdateConversationDTO;
use App\Domain\AutomationDashboard\DTO\UpdateConversationLogsDTO;
use App\Domain\AutomationDashboard\Models\AutomationDashboard;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class AutomationDashboardRepository
{
    public function getAll()
    {
        return AutomationDashboard::where('is_active', true)->get();
    }
    public function find_psid(int $customer_psid): ?AutomationDashboard
    {
        return AutomationDashboard::where('customer_psid', $customer_psid)->first();
    }

    public function find(int $conversation_log_id): ?AutomationDashboard
    {
        return AutomationDashboard::where('conversation_log_id', $conversation_log_id)->first();
    }

    public function create(CreateAutomationDashboardDTO $dto): AutomationDashboard
    {
        return AutomationDashboard::create([
            'customer_psid'              => $dto->customer_psid,
            'conversation_status'        => $dto->conversation_status,
            'conversation_updated_from'  => $dto->conversation_updated_from,
            'conversation_updated_to'    => $dto->conversation_updated_to,
            'created_by'                 => $dto->created_by,
            'date_created'               => now('Asia/Manila'),
        ]);
    }

    public function update(AutomationDashboard $conversation, array $data): AutomationDashboard
    {
        $conversation->update($data);
        return $conversation;
    }

    public function updateConversationLogs( UpdateConversationLogsDTO $updateDTO): ?AutomationDashboard {

        $conversation = AutomationDashboard::where('customer_psid', $updateDTO->customer_psid)
            ->latest('conversation_log_id')
            ->first();

        if (!$conversation) {
            return null;
        }

        $conversation->update([
            'conversation_status'     => $updateDTO->conversation_status,
            'conversation_updated_to' => $updateDTO->conversation_updated_to,
        ]);

        return $conversation;
    }



    //Summary

    public function getDashboardSummary(): ?object
    {
        return DB::table('wbs_i_conversation')
            ->selectRaw('
                SUM(transfer_count_human) AS bot_to_human_transfers,
                COUNT(CASE WHEN status != "OPEN" THEN 1 END) AS active_human_handled_chats
            ')
            ->first();
    }

    public function getAverageMinutesOnHuman(): float
    {
        $avgMinutes = DB::table('wbs_i_transitionconversation_logs')
            ->whereNotNull('conversation_updated_from')
            ->whereNotNull('conversation_updated_to')
            ->whereRaw('conversation_updated_to > conversation_updated_from')
            ->avg(DB::raw(
                'TIMESTAMPDIFF(SECOND, conversation_updated_from, conversation_updated_to) / 60'
            ));

        return (float) max($avgMinutes ?? 0, 0);
    }

}
