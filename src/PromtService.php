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
        'category_id' => 'category',
        'location_id' => 'location_id',
        'ageGroup'    => 'age'
    ];

    private static function getUrl()
    {
        $config = \Drupal::config('promt.settings');
        return $config->get('promt_url');
    }
    private static function doJsonQuery($url)
    {
        $client = \Drupal::httpClient();
        $response = $client->get($url);
        return json_decode($response->getBody(), true);
    }

    public static function programs(array $fields=null)
    {
        $search = [];
        foreach (self::$fields as $drupalField => $promtField) {
            if (!empty($fields[$drupalField])) {
                $search[$promtField] = $fields[$drupalField];
            }
        }
        $params = $search ? '?'.http_build_query($search) : '';
        $url = self::getUrl().'/PromtService'.$params;
        $res = self::doJsonQuery($url);
        if (!empty($res['programs'])) {
            return $res['programs'];
        }
        return [];
    }

    public static function program($id)
    {
        $url = self::getUrl().'/PromtService';
        return self::doJsonQuery($url);
    }

    public static function locations()
    {
        $url = self::getUrl().'/PromtService?list_type=locations';
        $res = self::doJsonQuery($url);
        if (!empty($res['locations'])) {
            return $res['locations'];
        }
        return [];
    }

    public static function categories()
    {
        $url = self::getUrl().'/PromtService?list_type=categories';
        $res = self::doJsonQuery($url);
        if (!empty($res['categories'])) {
            return $res['categories'];
        }
        return [];
    }

    public static function ageGroups()
    {
        $url = self::getUrl().'/PromtService?list_type=age_groups';
        $res = self::doJsonQuery($url);
        if (!empty($res['age groups'])) {
            return $res['age groups'];
        }
        return [];
    }
}
