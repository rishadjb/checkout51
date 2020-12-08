<?php

declare(strict_types=1);

namespace App\Validators\Offer;

interface ValidateOfferInterface
{
    /**
     * Removes fields not supposed to be present in the applicant data
     * and validates the structure of fields for strings, booleans, and arrays.
     *
     * @param array $applicants
     *
     * @return array
     */
    public function cleanData(array $applicants): array;

    /**
     * Validate an array of applicants and return an array errors
     * or an empty array on success.
     *
     * @param array $application
     * @param array $applicants
     *
     * @return array
     */
    public function validateOffer(array $offer = null): array;
}
