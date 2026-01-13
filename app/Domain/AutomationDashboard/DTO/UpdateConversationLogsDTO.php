<?php

namespace App\Domain\AutomationDashboard\DTO;

class UpdateConversationLogsDTO
{
    public function __construct(
        public ?int $conversation_log_id,
        public ?string $customer_psid,
        public ?string $conversation_status,
        public ?string $conversation_updated_to,
    ) {}
}