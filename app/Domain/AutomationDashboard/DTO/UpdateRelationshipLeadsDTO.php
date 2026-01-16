<?php

namespace App\Domain\AutomationDashboard\DTO;

class UpdateRelationshipLeadsDTO
{
    public function __construct(
        public ?string $customer_psid,
        public ?string $relationship_stage,
    ) {}
}