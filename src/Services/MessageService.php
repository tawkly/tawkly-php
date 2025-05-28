<?php

namespace Tawkly\Services;

use Tawkly\Exceptions\TawklyException;
use Tawkly\Models\Room;
use Tawkly\Models\Message;
use Tawkly\BaseClient;
use Tawkly\Models\Pager;

class MessageService extends BaseClient
{
    /**
     * @throws TawklyException
     */
    public function all(int $page = 1, int $limit = 10): Pager
    {
        try {
            $pagination = [
                'page' => $page,
                'limit' => $limit,
            ];

            $validation = self::$validator->validate($pagination, [
                'page' => 'numeric',
                'limit' => 'numeric|max:50',
            ]);

            if ($validation->fails()) {
                $errors = implode(', ', $validation->errors()->all());
                throw new TawklyException('Validation error: ' . $errors);
            }

            $response = self::$http->get('messages/' . self::$appId, $pagination);
            $rooms = array_map(fn ($room) => new Room($room), $response->data);

            return new Pager($rooms, $response->meta, [$this, 'all']);
        } catch (\Exception $e) {
            throw new TawklyException('Error fetching rooms: ' . $e->getMessage());
        }
    }

    /**
     * @throws TawklyException
     */
    public function list(string $roomId, int $page = 1, int $limit = 10): Pager
    {
        try {
            $pagination = [
                'page' => $page,
                'limit' => $limit,
            ];

            $validation = self::$validator->validate($pagination, [
                'page' => 'numeric',
                'limit' => 'numeric|max:50',
            ]);

            if ($validation->fails()) {
                $errors = implode(', ', $validation->errors()->all());
                throw new TawklyException('Validation error: ' . $errors);
            }

            $response = self::$http->get('messages/' . self::$appId . '/' . $roomId, $pagination);
            $messages = array_map(fn ($message) => new Message($message), $response->data);

            return new Pager($messages, $response->meta, [$this, 'list']);
        } catch (\Exception $e) {
            throw new TawklyException('Error fetching messages: ' . $e->getMessage());
        }
    }
}
