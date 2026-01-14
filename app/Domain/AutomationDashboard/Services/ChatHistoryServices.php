<?php

namespace App\Domain\AutomationDashboard\Services;

use App\Domain\AutomationDashboard\DTO\HistoryCreationDTO;
use App\Domain\AutomationDashboard\Repositories\ChatHistoryRepository;

class ChatHistoryServices
{
    public function __construct(
        protected ChatHistoryRepository $repository
    ) {}

    public function displayHistory(){
        return $this->repository->getAll();
    }

    public function newChatHistory(array $data){
        $dto = new HistoryCreationDTO(
            customer_psid: $data['customer_psid'],
            chat_message: $data['chat_message'],
            chat_date: $data['chat_date'],
            existing_leads: $data['existing_leads'],
            existing_relationship:$data['existing_relationship'],
        );

        return $this->repository->createChatHistory($dto);
    }


}