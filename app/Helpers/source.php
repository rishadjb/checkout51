<?php

declare(strict_types=1);

namespace App\Helpers;

use Exception;
use Illuminate\Http\Response;
use App\Exceptions\ApiException;
use App\Models\Setting;
use App\Models\Source as SourceModel;

class Source
{
    /**
     * @var array
     */
    private static $cache = [];

    /**
     * Get the source information.
     *
     * @param string $name
     *
     * @throws Exception
     *
     * @return array
     */
    public static function getSource(string $name): array
    {
        if (!isset(self::$cache[$name])) {
            $source = SourceModel::where([
                    'name'   => $name,
                ])->first();

            if (!$source) {
                throw new Exception('Source not found');
            }

            self::$cache[$name] = $source->toArray();
        }

        return self::$cache[$name];
    }

    /**
     * Get the source ID for API or throw an API Exception.
     *
     * @param string $source
     *
     * @throws ApiException
     *
     * @return string
     */
    public static function getSourceIdForApi(string $source): string
    {
        try {
            return self::getSource($source)['id'];
        } catch (Exception $e) {
            throw new ApiException(ApiException::CODE_NOT_FOUND, translate('Source not found'), Response::HTTP_NOT_FOUND);
        }
    }

    /**
     * Return the clients validator class.
     *
     * @param string $sourceId
     * @param string $type
     *
     * @throws Exception
     *
     * @return string
     */
    public static function getClientValidatorClass(string $sourceId, $type)
    {
        $className = self::getClientClassName($sourceId);
        
        switch ($type) {
            case 'offer':
                $className = sprintf('\App\Clients\%s\Validators\Offer', $className);
                break;
            default:
                throw new Exception('Invalid validator type');
        }

        if (!class_exists($className)) {
            throw new Exception('Unable to find client validator class');
        }

        return new $className();
    }

    /**
     * Returns the notification or validator class name for a source ID.
     *
     * @param string $sourceId
     *
     * @throws Exception
     *
     * @return string
     */
    public static function getClientClassName(string $sourceId): string
    {
        $className = Setting::where([
                'source_id' => $sourceId,
                'key'       => 'client_class',
            ])->first();

        if (!$className) {
            throw new Exception('Unable to find client class.');
        }

        return $className->toArray()['data']['class'];
    }
}
