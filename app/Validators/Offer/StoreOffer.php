<?php

declare(strict_types=1);

namespace App\Validators\Offer;

use StdClass;

trait StoreOffer
{
    /**
     * Register the base validators for storing an applicant.
     */
    public function OfferRules()
    {
        $this->registerValidatorRules(
            $this->OfferStructure(),
            $this->OfferValidationRules(),
            $this->OfferValidationMessages(),
            $this->OfferAdditionalRules()
        );
    }

    protected function OfferStructure()
    {
        return [
            'offer_id'        => 'int',
            'name'         => 'string',
            'image_url'            => 'string',
            'cash_back'               => 'float',
        ];
    }

    protected function OfferValidationRules()
    {
        return [
            'offer_id'        => 'required|min:1',
            'name'         => 'required|min:1',
            'cash_back'               => 'required',
        ];
    }

    protected function OfferAdditionalRules()
    {
        return [];
    }

    protected function OfferValidationMessages()
    {
        return [
            'offer_id.required'        => translate('Offer %d: Offer ID is required'),
            'offer_id.min'             => translate('Offer %d: Offer ID value must be minimum 1'),
            'name.required'         => translate('Offer %d: Product Name is required'),
            'name.min'              => translate('Offer %d: Product Name requires a minimum of 1 character to be entered.'),
            'cash_back.required'        => translate('Offer %d: Cashback is required'),
            
        ];
    }
}
