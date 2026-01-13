<?php

namespace App\Domain\AutomationDashboard\Services;

use App\Domain\AutomationDashboard\DTO\AutomationDashboardSummaryDTO;
use App\Domain\AutomationDashboard\DTO\CreateAutomationDashboardDTO;
use App\Domain\AutomationDashboard\DTO\UpdateConversationLogsDTO;
use App\Domain\AutomationDashboard\Models\AutomationDashboard;
use App\Domain\AutomationDashboard\Repositories\AutomationDashboardRepository;
use Carbon\Carbon;

class AutomationDashboardService
{
    public function __construct(
        protected AutomationDashboardRepository $repository
    ) {}

    public function list()
    {
        return $this->repository->getAll();
    }
    public function create(array $data)
    {
        $dto = new CreateAutomationDashboardDTO(
            conversation_log_id: null,
            customer_psid: $data['customer_psid'],
            conversation_status: $data['conversation_status'],
            conversation_updated_from: $data['conversation_updated_from'] ?? null,
            conversation_updated_to: $data['conversation_updated_to'] ?? null,
            created_by:1,
            is_active:true
        );

        return $this->repository->create($dto);
    }

    public function update(int $conversation_id, array $data)
    {
        $conversation = $this->repository->find($conversation_id);
        return $this->repository->update($conversation, $data);
    }
    public function updateConversationLogger(array $data): AutomationDashboard
    {
        $dto = new UpdateConversationLogsDTO(
            conversation_log_id: null,
            customer_psid: $data['customer_psid'],
            conversation_status: $data['conversation_status'],
            conversation_updated_to: now('Asia/Manila'),
        );

        $conversation = $this->repository->updateConversationLogs($dto);

        if (!$conversation) {
            throw new \Exception('Conversation not found.');
        }

        return $conversation;
    }



    //Summary
    public function getSummary(): AutomationDashboardSummaryDTO
    {
        $avgMinutes = $this->repository->getAverageMinutesOnHuman();
        $summary    = $this->repository->getDashboardSummary();

        return new AutomationDashboardSummaryDTO(
            avgMinutesOnHuman: round($avgMinutes, 2),
            botToHumanTransfers: (int) $summary->bot_to_human_transfers,
            activeHumanHandledChats: (int) $summary->active_human_handled_chats
        );
    }

}