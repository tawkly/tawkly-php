<?php

namespace Unswer\Services;

use Unswer\Exceptions\UnswerException;
use Unswer\Models\Contact;
use Unswer\BaseClient;
use Unswer\Models\Pager;

class ContactService extends BaseClient
{
    /**
     * @throws UnswerException
     */
    public function create(array $contacts): bool
    {
        try {
            $validation = self::$validator->validate($contacts, [
                '*.name' => 'required',
                '*.phone' => 'required|numeric',
            ]);

            if ($validation->fails()) {
                $errors = implode(', ', $validation->errors()->all());
                throw new UnswerException('Validation error: ' . $errors);
            }

            $response = self::$http->post('contacts/' . self::$appId, $contacts);
            return $response->statusCode === 201;
        } catch (\Exception $e) {
            throw new UnswerException('Error creating contact: ' . $e->getMessage());
        }
    }

    /**
     * @throws UnswerException
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
                throw new UnswerException('Validation error: ' . $errors);
            }

            $response = self::$http->get('contacts/' . self::$appId, $pagination);
            $contacts = array_map(fn ($contact) => new Contact($contact), $response->data);

            return new Pager($contacts, $response->meta, [$this, 'all']);
        } catch (\Exception $e) {
            throw new UnswerException('Error fetching contacts: ' . $e->getMessage());
        }
    }

    /**
     * @throws UnswerException
     */
    public function get(string $id): Contact
    {
        try {
            $response = self::$http->get('contacts/' . self::$appId . '/' . $id);
            return new Contact($response->data);
        } catch (\Exception $e) {
            throw new UnswerException('Error fetching contact details: ' . $e->getMessage());
        }
    }

    /**
     * @throws UnswerException
     */
    public function block(string $id): Contact
    {
        try {
            $response = self::$http->put('contacts/' . self::$appId . '/' . $id);
            return new Contact($response->data);
        } catch (\Exception $e) {
            throw new UnswerException('Error blocking/unblocking contact: ' . $e->getMessage());
        }
    }

    /**
     * @throws UnswerException
     */
    public function delete(string $id): bool
    {
        try {
            $response = self::$http->delete('contacts/' . self::$appId . '/' . $id);
            return $response->statusCode === 200;
        } catch (\Exception $e) {
            throw new UnswerException('Error deleting contact: ' . $e->getMessage());
        }
    }
}
