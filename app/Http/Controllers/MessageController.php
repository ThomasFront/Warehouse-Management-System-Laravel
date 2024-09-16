<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateMessageRequest;
use App\Http\Resources\MessageCollection;
use App\Http\Resources\MessageResource;
use App\Http\Responses\ApiResponse;
use App\Http\Services\MessageService;

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
}
