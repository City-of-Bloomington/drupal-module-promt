<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Drupal\promt;

use Drupal\Core\Site\Settings;

class PromtService
{
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

    public static function programs()
    {
        $url = self::getUrl().'/PromtService';
        return self::doJsonQuery($url);
    }

    public static function program($id)
    {
        $url = self::getUrl().'/PromtService';
        return self::doJsonQuery($url);
    }
}
