<?php

namespace App\Domain\AutomationDashboard\DTO;

class UpdateConversationDTO
{
    public function __construct(
        public ?string $customer_psid,
        public ?string $last_message,
        public ?string $date_created,
    ) {}
}