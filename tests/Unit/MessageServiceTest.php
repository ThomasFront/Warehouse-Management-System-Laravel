<?php

namespace Tests\Unit;

use App\Enums\MessagePriority;
use App\Http\Requests\CreateMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Http\Services\AuthService;
use App\Http\Services\MessageService;
use App\Models\Message;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Mockery;

class MessageServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AuthService $authService;
    protected MessageService $messageService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->authService = Mockery::mock(AuthService::class);
        $this->messageService = new MessageService($this->authService);
    }

    public function test_can_get_messages()
    {
        $user = User::factory()->create();
        Message::factory()->count(10)->create(['user_id' => $user->id]);

        $result = $this->messageService->getMessages();

        $this->assertCount(5, $result);
        $this->assertEquals(10, $result->total());
    }

    public function test_can_add_message()
    {
        $data = [
            'title' => 'Test Title',
            'message' => 'Hello, this is a test message!',
            'priority' => MessagePriority::LOW
        ];

        $request = Mockery::mock(CreateMessageRequest::class);
        $request->shouldReceive('validated')->andReturn($data);

        $user = User::factory()->create();

        $this->authService
            ->shouldReceive('me')
            ->andReturn($user);

        $message = $this->messageService->addMessage($request);

        $this->assertInstanceOf(Message::class, $message);
        $this->assertEquals($user->id, $message->user_id);
        $this->assertEquals($data['title'], $message->title);
        $this->assertEquals($data['message'], $message->message);
        $this->assertEquals($data['priority'], $message->priority);
    }

    public function test_can_delete_message()
    {
        $user = User::factory()->create();
        $message = Message::factory()->create(['user_id' => $user->id]);

        $this->assertDatabaseHas('messages', ['id' => $message->id]);

        $this->messageService->deleteMessage($message);

        $this->assertDatabaseMissing('messages', ['id' => $message->id]);
    }

    public function test_can_update_message()
    {
        $updatedData = [
            'title' => 'Updated Title',
            'message' => 'Updated message content.',
            'priority' => MessagePriority::MEDIUM,
        ];

        $request = Mockery::mock(UpdateMessageRequest::class);
        $request
            ->shouldReceive('validated')
            ->andReturn($updatedData);

        $user = User::factory()->create();
        $message = Message::factory()->create(['user_id' => $user->id]);

        $updatedMessage = $this->messageService->updateMessage($request, $message);

        $this->assertEquals($updatedData['title'], $updatedMessage->title);
        $this->assertEquals($updatedData['message'], $updatedMessage->message);
        $this->assertEquals($updatedData['priority'], $updatedMessage->priority);
        $this->assertEquals($updatedMessage['user_id'], $user->id);
        $this->assertDatabaseHas('messages', [
            'id' => $message->id,
            'title' => $updatedData['title'],
            'message' => $updatedData['message'],
            'priority' => $updatedData['priority'],
            'user_id' => $user->id
        ]);
    }
}
