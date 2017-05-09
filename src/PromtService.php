<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Drupal\promt;

use Drupal\Core\Site\Settings;

class PromtService
{
    /**
     * Maps Drupal search input fields to PROMT query parameters
     *
     * Drupal fieldnames are the keys.
     * Promt fieldnames are the values
     * $fields[ drupal_field => promt_field ]
     */
    public static $fields = [
        'category_id' => 'category_id',
        'location_id' => 'location_id',
        'ageGroup'    => 'age'
    ];

    private static function getUrl()
    {
        $config = \Drupal::config('promt.settings');
        return $config->get('promt_url');
    }

    /**
     * @param  string $url
     * @return array        The JSON data
     */
    private static function doJsonQuery($url)
    {
        $client   = \Drupal::httpClient();
        $response = $client->request('GET', $url);
        $json     = json_decode($response->getBody(), true);
        if (!$json) {
            throw new \Exception(json_last_error_msg());
        }
        return $json;
    }

    public static function programs(array $fields=null)
    {
        $search = [];
        foreach (self::$fields as $drupalField => $promtField) {
            if (!empty($fields[$drupalField])) {
                $search[$promtField] = $fields[$drupalField];
            }
        }
        $params   = $search ? '?'.http_build_query($search) : '';
        $url      = self::getUrl().'/PromtService'.$params;
        $programs = self::doJsonQuery($url);
        return $programs;
    }

    public static function program($id)
    {
        $url = self::getUrl().'/PromtService?program_id='.$id;
        return self::doJsonQuery($url);
    }

    public static function locations()
    {
        $url = self::getUrl().'/PromtService?list_type=locations';
        return self::doJsonQuery($url);
    }

    public static function categories()
    {
        $url = self::getUrl().'/PromtService?list_type=categories';
        return self::doJsonQuery($url);
    }

    public static function ageGroups()
    {
        $url = self::getUrl().'/PromtService?list_type=age_groups';
        return self::doJsonQuery($url);
    }
}
