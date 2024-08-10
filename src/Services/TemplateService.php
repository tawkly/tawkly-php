<?php

namespace Unswer\Services;

use Unswer\Exceptions\UnswerException;
use Unswer\Models\Template;
use Unswer\BaseClient;
use Unswer\Models\Pager;

class TemplateService extends BaseClient
{
    /**
     * @throws UnswerException
     */
    public function all(?string $before = null, ?string $after = null, int $limit = 10): Pager
    {
        try {
            $pagination = [
                'before' => $before,
                'after' => $after,
                'limit' => $limit,
            ];

            $validation = self::$validator->validate($pagination, [
                'before' => 'cursor',
                'after' => 'cursor',
                'limit' => 'numeric|max:50',
            ]);

            if ($validation->fails()) {
                $errors = implode(', ', $validation->errors()->all());
                throw new UnswerException('Validation error: ' . $errors);
            }

            $response = self::$http->get('templates/' . self::$appId, $pagination);
            $templates = array_map(fn ($template) => new Template($template), $response->data);

            return new Pager($templates, $response->meta, [$this, 'all']);
        } catch (\Exception $e) {
            throw new UnswerException('Error fetching templates: ' . $e->getMessage());
        }
    }

    /**
     * @throws UnswerException
     */
    public function create(array $template): bool
    {
        try {
            $validation = self::$validator->validate($template, [
                'name' => 'required|snake_case',
                'category' => 'required|in:UTILITY,MARKETING,AUTHENTICATION',
                'sub_category' => 'in:ORDER_DETAILS,ORDER_STATUS',
                'language' => 'required|regex:/^[a-z]{2}(_[A-Z]{2})?$/',
                'ttl' => [
                    'nullable',
                    fn($val, $a, $i, $v) => !(in_array($i['category'], ['UTILITY', 'MARKETING']) && $val !== null),
                ],
                'components' => [],
            ]);

            if ($validation->fails()) {
                $errors = implode(', ', $validation->errors()->all());
                throw new UnswerException('Validation error: ' . $errors);
            }

            $response = self::$http->post('templates/' . self::$appId, $template);
            return $response->statusCode === 202;
        } catch (\Exception $e) {
            throw new UnswerException('Error creating template: ' . $e->getMessage());
        }
    }

    /**
     * @throws UnswerException
     */
    public function get(string $id): Template
    {
        try {
            $response = self::$http->get('templates/' . self::$appId . '/' . $id);
            return new Template($response->data);
        } catch (\Exception $e) {
            throw new UnswerException('Error fetching template details: ' . $e->getMessage());
        }
    }

    /**
     * @throws UnswerException
     */
    public function delete(string $id): bool
    {
        try {
            $response = self::$http->delete('templates/' . self::$appId . '/' . $id);
            return $response->statusCode === 200;
        } catch (\Exception $e) {
            throw new UnswerException('Error deleting template: ' . $e->getMessage());
        }
    }
}
