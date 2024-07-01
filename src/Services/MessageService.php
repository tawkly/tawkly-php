<?php

namespace Unswer\Services;

use Unswer\Exceptions\UnswerException;
use Unswer\Models\Room;
use Unswer\Models\Message;
use Illuminate\Support\Collection;
use Unswer\BaseClient;

class MessageService extends BaseClient
{
    /**
     * @param int $page
     * @param int $limit
     * @throws UnswerException
     * @return Collection
     */
    public function all($page, $limit): Collection
    {
        try {
            // TODO: implement api call to get all rooms
            $rooms = []; // populate this array with room data

            return new Collection($rooms);
        } catch (\Exception $e) {
            throw new UnswerException('Error fetching rooms: ' . $e->getMessage());
        }
    }

    /**
     * @param string $roomId
     * @throws UnswerException
     * @return Collection
     */
    public function list($roomId): Collection
    {
        try {
            // TODO: implement api call to get messages for a room
            $messages = []; // populate this array with message data

            return new Collection($messages);
        } catch (\Exception $e) {
            throw new UnswerException('Error fetching messages: ' . $e->getMessage());
        }
    }
}
