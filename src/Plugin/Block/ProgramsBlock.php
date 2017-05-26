<?php
/**
 * @copyright 2017 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 */
namespace Drupal\promt\Plugin\Block;

use Drupal\promt\PromtService;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Block\BlockPluginInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * @Block(
 *     id = "promt_programs_block",
 *     admin_label = "Promt Programs",
 *     context = {
 *         "node" = @ContextDefinition("entity:node")
 *     }
 * )
 */
class ProgramsBlock extends BlockBase implements BlockPluginInterface
{
    public function build()
    {
        $config = $this->getConfiguration();
        $node   = $this->getContextValue('node');

        $fieldtype = $config['fieldtype'];
        $fieldname = $config['fieldname'];

        if ($node->hasField( $fieldname)) {
            $id = $node->get($fieldname)->value;
            if ($id) {
                $programs = PromtService::programs([$fieldtype=>$id]);
                if ($programs) {
                    return [
                        '#theme'    => 'promt_programs',
                        '#programs' => $programs
                    ];
                }
            }
        }
    }

    public function blockForm($form, FormStateInterface $form_state)
    {
        $form   = parent::blockForm($form, $form_state);
        $config = $this->getConfiguration();

        $form['promt_programsblock_fieldtype'] = [
            '#type'          => 'select',
            '#title'         => 'Type',
            '#description'   => 'Which promt ID field to query on',
            '#options'       => ['category_id'=>'Category', 'location_id'=>'Location'],
            '#default_value' => isset($config['fieldtype']) ? $config['fieldtype'] : '',
            '#required'      => true
        ];

        $form['promt_programsblock_fieldname'] = [
            '#type'          => 'textfield',
            '#title'         => 'Fieldname',
            '#description'   => 'Name of the node field that contains the id',
            '#default_value' => isset($config['fieldname']) ? $config['fieldname'] : '',
            '#required'      => true
        ];
        return $form;
    }

    public function blockSubmit($form, FormStateInterface $form_state)
    {
        $this->configuration['fieldtype'] = $form_state->getValue('promt_programsblock_fieldtype');
        $this->configuration['fieldname'] = $form_state->getValue('promt_programsblock_fieldname');
    }
}
