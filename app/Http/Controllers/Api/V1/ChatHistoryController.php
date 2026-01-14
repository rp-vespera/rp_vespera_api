<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Domain\AutomationDashboard\Services\AutomationDashboardService;
use App\Domain\AutomationDashboard\Services\ChatHistoryServices;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class ChatHistoryController extends Controller
{
    public function __construct(
        private ChatHistoryServices $service
        ) {}

    public function index()
    {
         return response()->json(
            $this->service->displayHistory()
        );
    }
    public function newHistoryLogs(Request $request)
    {
        $data = $request->validate([
            'customer_psid'         => 'required|string',
            'chat_message'          => 'nullable|string',
            'chat_date'             => 'nullable|date',
            'existing_leads'        => 'nullable|string',
            'existing_relationship' => 'nullable|string',
        ]);

        return response()->json(
            $this->service->newChatHistory($data),
            201
        );
    }
}