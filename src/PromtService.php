<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Drupal\promt;

use Drupal\Core\Site\Settings;

class PromtService
{
    public static function programs()
    {
        $PROMT = Settings::get('promt_url');
        $url = $PROMT.'/PromtService';

        $client = \Drupal::httpClient();
        $response = $client->get($url);
        return json_decode($response->getBody(), true);
    }
}
