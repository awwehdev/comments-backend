<?php

namespace App\Services;

use Illuminate\Support\Collection;

class UserCommentService
{
    public function asTree(Collection $nodes)
    {
        $data = $nodes->keyBy('id');
        $count = 0;
        $iterator = $nodes->reverse()->getIterator();
        while (true) {
            $node = $iterator->current();

            if (!$node) {
                break;
            }

            if (!empty($node->reply_id)) {
                if (!is_array($data[$node->reply_id]->children)) {
                    $data[$node->reply_id]->children = [];
                }

                $data[$node->reply_id]->children = array_merge([$node], $data[$node->reply_id]->children);
                unset($data[$node->id]);
            } elseif (empty($data[$node->id]->children)) {
                $data[$node->id]->children = [];
            }

            $iterator->next();

            if ($count == $nodes->count()) {
                break;
            }

            $count++;
            if ($count >= PHP_INT_MAX) {
                break;
            }
        }

        return $data;
    }
}