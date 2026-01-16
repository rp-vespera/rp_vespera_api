<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Domain\AutomationDashboard\Services\ConversationServices;
use App\Helpers\ApiResponse;
use Illuminate\Http\Request;

class ConversationController extends Controller
{
    public function __construct(
        protected ConversationServices $service
    ) {}

    public function index()
    {
        return response()->json(
            $this->service->list()
        );
    }
    public function fetchCustomerPSID(int $customer_psid)
    {
        return response()->json(
            $this->service->fetchPSID($customer_psid)
        );
    }
    public function displayHandsoff()
    {
        return response()->json(
            $this->service->listLogsHandsoff()
        );
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'conversation_id'       => 'nullable|integer',
            'customer_psid'         => 'required|string',
            'conversation_name'     => 'nullable|string',
            'assigned_status'       => 'required|string',
            'assigned_agent'        => 'nullable|string',
            'status'                => 'nullable|string',
            'last_message'          => 'nullable|string',
            'transfer_count_bot'    => 'nullable|integer',
            'transfer_count_human'  => 'nullable|integer',
            'date_created'          => 'nullable|date',
            'lead_stage'            => 'nullable|string',
            'relationship_stage'    => 'nullable|string',
        ]);

        return response()->json(
            $this->service->create($data),
            201
        );
    }

    public function update(Request $request, int $conversationid)
    {
        return response()->json(
            $this->service->update($conversationid, $request->all())
        );
    }
    public function updateTransferLogs(Request $request)
    {
        $validated = $request->validate([
            'conversation_id'       => 'required|integer',
            'status'                => 'required|string',
            'transfer_count_bot'    => 'required|integer',
            'transfer_count_human'  => 'required|integer',
        ]);

        $conversation = $this->service->UpdateTransferHandoff($validated);

        return response()->json([
            'success' => true,
            'message' => 'Transfer logs updated successfully.',
            'data'    => $conversation,
        ]);
    }
    public function updateTransferLogsBot(Request $request)
    {
        $validated = $request->validate([
            'customer_psid'         => 'required|string',
            'status'                => 'required|string',
            'transfer_count_bot'    => 'required|integer',
            'transfer_count_human'  => 'required|integer',
        ]);

        $conversation = $this->service->UpdateTransferHandoffBot($validated);

        return response()->json([
            'success' => true,
            'message' => 'Transfer logs updated successfully.',
            'data'    => $conversation,
        ]);
    }
    public function updateConversation(Request $request)
    {
        $validated = $request->validate([
            'customer_psid'         => 'required|string',
            'last_message'          => 'required|string',
            'date_created'          => 'nullable|date',
        ]);

        $conversation = $this->service->updateConversation($validated);

        return response()->json([
            'success' => true,
            'message' => 'Message Updated',
            'data'    => $conversation,
        ]);
    }
    public function updateStatusLogs(Request $request)
    {
        $validated = $request->validate([
            'customer_psid'    => 'required|string',
            'status'           => 'required|string',
            'date_created'     => 'nullable|date',
        ]);

        $conversation = $this->service->updateStatusLogs($validated);

        return response()->json([
            'success' => true,
            'message' => 'Message Updated',
            'data'    => $conversation,
        ]);
    }
    public function updateLeadsStatus(Request $request)
    {
        $validated = $request->validate([
            'customer_psid'    => 'required|string',
            'lead_stage'       => 'required|string',
        ]);

        $conversation = $this->service->updateStatusLeads($validated);

        return response()->json([
            'success' => true,
            'message' => 'Message Updated',
            'data'    => $conversation,
        ]);
    }
    public function updateLeadsRelationship(Request $request)
    {
        $validated = $request->validate([
            'customer_psid'       => 'required|string',
            'relationship_stage'  => 'required|string',
        ]);

        $conversation = $this->service->updateRelationshipLeads($validated);

        return response()->json([
            'success' => true,
            'message' => 'Message Updated',
            'data'    => $conversation,
        ]);
    }
}