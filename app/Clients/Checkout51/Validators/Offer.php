<?php

declare(strict_types=1);

namespace App\Clients\Checkout51\Validators;

use App\Clients\Checkout51\Constants;
use App\Validators\Offer\StoreOffer;
use App\Validators\Offer\ValidateOfferInterface;
use App\Validators\BaseValidator;

class Offer extends BaseValidator implements ValidateOfferInterface
{
    use StoreOffer;
    protected $const = Constants::class;

    /**
     * {@inheritdoc}
     *
     * @see \App\Validators\Offer\ValidateOfferInterface::cleanData($offers)
     */
    public function cleanData(array $offer): array
    {
        $structure = [
            'additional_data' => [],
        ];

        return $this->cleanBodyStructure($structure, $offer);
    }

    /**
     * {@inheritdoc}
     *
     * @see \App\Validators\Offer\ValidateOfferInterface::validateOffer($offers)
     */
    public function validateOffer(array $offer = null): array
    {
        $formattedErrors = [];
        $messages = [];
        $rules = [];

        $messages = array_merge($messages, []);

        $errors = $this->validate($offer, $rules, $messages);

        return $formattedErrors;
    }
}
