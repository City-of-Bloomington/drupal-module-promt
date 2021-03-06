<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license https://www.gnu.org/licenses/old-licenses/gpl-2.0 GNU/GPL2, see LICENSE
 *
 * This file is part of the ASM drupal module.
 *
 * The ASM module is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 *
 * The ASM module is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with the ASM module.  If not, see <https://www.gnu.org/licenses/old-licenses/gpl-2.0/>.
 */
namespace Drupal\promt\Breadcrumb;

use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Link;

class BreadcrumbBuilder implements BreadcrumbBuilderInterface
{
    public function applies(RouteMatchInterface $route_match)
    {
        $name = explode('.', $route_match->getRouteName());
        return ($name[0]=='promt' && $name[1]!='settings');
    }

    public function build(RouteMatchInterface $route_match)
    {
        list($module, $action) = explode('.', $route_match->getRouteName());
        $breadcrumb = new Breadcrumb();
        $breadcrumb->addCacheContexts(['url']);
        $breadcrumb->addLink(Link::createFromRoute('Home', '<front>'));

        $config  = \Drupal::config('promt.settings');
        $nids    = explode(',', $config->get('promt_breadcrumb'));
        $nodes   = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($nids);
        foreach ($nodes as $n) {
            $breadcrumb->addLink(Link::createFromRoute($n->title->value, 'entity.node.canonical', ['node'=>$n->nid->value]));
        }

        return $breadcrumb;
    }
}
