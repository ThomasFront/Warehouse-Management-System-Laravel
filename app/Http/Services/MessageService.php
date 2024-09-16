<?php

namespace App\Http\Services;

use App\Http\Requests\CreateMessageRequest;
use App\Models\Message;

class MessageService
{
    protected AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function getMessages()
    {
        return Message::paginate(5);
    }

    public function addMessage(CreateMessageRequest $request): Message
    {
        $validatedData = $request->validated();
        $userId = $this->authService->me()->id;

        $validatedData['user_id'] = $userId;

        $message = Message::create($validatedData);

        $message->load('user');

        return $message;
    }
}
