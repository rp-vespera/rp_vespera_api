<?php

namespace App\Domain\AutomationDashboard\DTO;

class CreateConversationRecordDTO
{
    public function __construct(
        public ?int $conversation_id,
        public ?string $customer_psid,
        public ?string $conversation_name,
        public ?string $assigned_status,
        public ?string $assigned_agent,
        public ?string $status,
        public ?string $last_message,
        public ?int $transfer_count_bot,
        public ?int $transfer_count_human,
        public ?string $date_created,
        public ?string $lead_stage,
        public ?string $relationship_stage,
    ) {}
}