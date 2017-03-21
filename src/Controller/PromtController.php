<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Drupal\promt\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\promt\PromtService;

class PromtController extends ControllerBase
{
    public function programs()
    {
        $programs = PromtService::programs();

        return [
            '#theme'    => 'promt_programs',
            '#programs' => $programs
        ];
    }

    public function program($id)
    {

    }
}
