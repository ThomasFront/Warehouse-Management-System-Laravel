<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Http\Resources\MessageCollection;
use App\Http\Resources\MessageResource;
use App\Http\Responses\ApiResponse;
use App\Http\Services\MessageService;
use App\Models\Message;

class MessageController extends Controller
{
    protected MessageService $messageService;

    public function __construct(MessageService $messageService)
    {
        $this->messageService = $messageService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $messages = $this->messageService->getMessages();

        return response()->json(new MessageCollection($messages));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateMessageRequest $request)
    {
        $message = $this->messageService->addMessage($request);

        return ApiResponse::success(["message" => new MessageResource($message)], 201);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMessageRequest $request, Message $message)
    {
        $updatedMessage = $this->messageService->updateMessage($request, $message);

        return ApiResponse::success(["product" => new MessageResource($updatedMessage)]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Message $message)
    {
        $this->messageService->deleteMessage($message);

        return ApiResponse::success(['message' => 'Message deleted successfully.']);
    }
}
