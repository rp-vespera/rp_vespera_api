<?php

namespace App\Domain\AutomationDashboard\Repositories;

use App\Domain\AutomationDashboard\DTO\HistoryCreationDTO;
use App\Domain\AutomationDashboard\Models\ChatHistoryLogsModel;
use App\Domain\AutomationDashboard\Models\ConversationModel;

class ChatHistoryRepository
{
    public function getAll()
    {
        return ChatHistoryLogsModel::latest('chat_date')->get();
    }
    public function createChatHistory(HistoryCreationDTO $dto): ChatHistoryLogsModel
    {
        $conversationHistory = ChatHistoryLogsModel::create(
        [
            'customer_psid'  => $dto->customer_psid,
            'chat_message'  => $dto->chat_message,
            'chat_date'  => $dto->chat_date,
            'existing_leads'  => $dto->existing_leads,
            'existing_relationship'  => $dto->existing_relationship,
        ]);

        return $conversationHistory;
    }
}