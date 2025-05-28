<?php

namespace Tawkly\Services;

use Tawkly\Exceptions\TawklyException;
use Tawkly\Models\Contact;
use Tawkly\BaseClient;
use Tawkly\Models\Pager;

class ContactService extends BaseClient
{
    /**
     * @throws TawklyException
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
                throw new TawklyException('Validation error: ' . $errors);
            }

            $response = self::$http->post('contacts/' . self::$appId, $contacts);
            return $response->statusCode === 201;
        } catch (\Exception $e) {
            throw new TawklyException('Error creating contact: ' . $e->getMessage());
        }
    }

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

            $response = self::$http->get('contacts/' . self::$appId, $pagination);
            $contacts = array_map(fn ($contact) => new Contact($contact), $response->data);

            return new Pager($contacts, $response->meta, [$this, 'all']);
        } catch (\Exception $e) {
            throw new TawklyException('Error fetching contacts: ' . $e->getMessage());
        }
    }

    /**
     * @throws TawklyException
     */
    public function get(string $id): Contact
    {
        try {
            $response = self::$http->get('contacts/' . self::$appId . '/' . $id);
            return new Contact($response->data);
        } catch (\Exception $e) {
            throw new TawklyException('Error fetching contact details: ' . $e->getMessage());
        }
    }

    /**
     * @throws TawklyException
     */
    public function block(string $id): Contact
    {
        try {
            $response = self::$http->put('contacts/' . self::$appId . '/' . $id);
            return new Contact($response->data);
        } catch (\Exception $e) {
            throw new TawklyException('Error blocking/unblocking contact: ' . $e->getMessage());
        }
    }

    /**
     * @throws TawklyException
     */
    public function delete(string $id): bool
    {
        try {
            $response = self::$http->delete('contacts/' . self::$appId . '/' . $id);
            return $response->statusCode === 200;
        } catch (\Exception $e) {
            throw new TawklyException('Error deleting contact: ' . $e->getMessage());
        }
    }
}
