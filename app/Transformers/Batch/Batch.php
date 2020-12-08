<?php

declare(strict_types=1);

namespace App\Transformers\Batch;

use Novus\Platform\Core\Transformers\Transformer;

class Batch extends Transformer
{
    /**
     * (non-PHPdoc).
     *
     * @see \Novus\Platform\Core\Transformers\Transformer::transform()
     */
    public function transform($data): array
    {
        $data = (array) $data;
        return [
            'completed'         => $data['completed'],
            'failed'            => $data['failed'],
            'errors'            => $data['errors'],
        ];
    }
}
