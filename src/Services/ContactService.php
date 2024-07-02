<?php

namespace Unswer\Services;

use Unswer\Exceptions\UnswerException;
use Unswer\Models\Contact;
use Illuminate\Support\Collection;
use Unswer\BaseClient;

class ContactService extends BaseClient
{
    /**
     * @param array $contacts
     * @throws UnswerException
     * @return bool
     */
    public function create($contacts)
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
     * @param int $page
     * @param int $limit
     * @throws UnswerException
     * @return Collection
     */
    public function all($page = 1, $limit = 10)
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

            return new Collection($contacts);
        } catch (\Exception $e) {
            throw new UnswerException('Error fetching contacts: ' . $e->getMessage());
        }
    }

    /**
     * @param string $id
     * @throws UnswerException
     * @return Contact
     */
    public function get($id)
    {
        try {
            $response = self::$http->get('contacts/' . self::$appId . '/' . $id);
            return new Contact($response->data);
        } catch (\Exception $e) {
            throw new UnswerException('Error fetching contact details: ' . $e->getMessage());
        }
    }

    /**
     * @param string $id
     * @throws UnswerException
     * @return Contact
     */
    public function block($id)
    {
        try {
            $response = self::$http->put('contacts/' . self::$appId . '/' . $id);
            return new Contact($response->data);
        } catch (\Exception $e) {
            throw new UnswerException('Error blocking/unblocking contact: ' . $e->getMessage());
        }
    }

    /**
     * @param string $id
     * @throws UnswerException
     * @return bool
     */
    public function delete($id)
    {
        try {
            $response = self::$http->delete('contacts/' . self::$appId . '/' . $id);
            return $response->statusCode === 200;
        } catch (\Exception $e) {
            throw new UnswerException('Error deleting contact: ' . $e->getMessage());
        }
    }
}
