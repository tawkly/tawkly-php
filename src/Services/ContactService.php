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
    public function create(array $contacts)
    {
        try {
            // TODO: implement api call to create contact

            return true;
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
    public function all($page, $limit)
    {
        try {
            // TODO: implement api call to get all contacts
            $contacts = []; // populate this array with contact data

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
    public function get($id): Contact
    {
        try {
            // TODO: implement api call to get contact details

            return new Contact($id, '6280000000000', ['tag1', 'tag2'], false, '2023-01-01');
        } catch (\Exception $e) {
            throw new UnswerException('Error fetching contact details: ' . $e->getMessage());
        }
    }

    /**
     * @param string $id
     * @throws UnswerException
     * @return bool
     */
    public function block($id): bool
    {
        try {
            // TODO: implement api call to block/unblock contact

            return true;
        } catch (\Exception $e) {
            throw new UnswerException('Error blocking/unblocking contact: ' . $e->getMessage());
        }
    }

    /**
     * @param string $id
     * @throws UnswerException
     * @return bool
     */
    public function delete($id): bool
    {
        try {
            // TODO: implement api call to delete contact

            return true;
        } catch (\Exception $e) {
            throw new UnswerException('Error deleting contact: ' . $e->getMessage());
        }
    }
}
