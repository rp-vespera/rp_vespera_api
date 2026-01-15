<?php

namespace App\Domain\AutomationDashboard\DTO;

class UpdateLeadsConversationDTO
{
    public function __construct(
        public ?string $customer_psid,
        public ?string $lead_stage,
    ) {}
}