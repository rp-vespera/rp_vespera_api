<?php

namespace App\Domain\AutomationDashboard\DTO;

class HistoryCreationDTO
{
    public function __construct(
        public ?string $customer_psid,
        public ?string $chat_message,
        public ?string $chat_date,
        public ?string $existing_leads,
        public ?string $existing_relationship,
    ) {}
}