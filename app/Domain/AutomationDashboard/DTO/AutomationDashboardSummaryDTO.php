<?php

namespace App\Domain\AutomationDashboard\DTO;

class AutomationDashboardSummaryDTO
{
    public function __construct(
        public float $avgMinutesOnHuman,
        public int $botToHumanTransfers,
        public int $activeHumanHandledChats
    ) {}
}