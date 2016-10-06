<?php
/**
 * @file
 * Contains \Drupal\select2\Entity\FieldWidget\AutocompleteWidget.
 */

namespace Drupal\select2\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\entity_reference\Plugin\Field\FieldWidget\AutocompleteTagsWidget;
use Drupal\user\EntityOwnerInterface;

/**
 * Plugin implementation of the 'entity_reference autocomplete-tags' widget.
 *
 * @FieldWidget(
 *   id = "select2_autocomplete_tags",
 *   label = @Translation("Autocomplete (Tags style, Select2)"),
 *   description = @Translation("An autocomplete text field."),
 *   field_types = {
 *     "entity_reference",
 *     "taxonomy_term_reference",
 *   },
 *   settings = {
 *     "match_operator" = "CONTAINS",
 *     "size" = 60,
 *     "autocomplete_type" = "tags",
 *     "placeholder" = ""
 *   },
 *   multiple_values = TRUE
 * )
 */
class Select2AutocompleteTagsWidget extends AutocompleteTagsWidget {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $entity = $items->getEntity();

    $element += array(
      '#type' => 'textfield',
      '#maxlength' => 1024,
      '#default_value' => implode(', ', $this->getLabels($items, $delta)),
      '#size' => $this->getSetting('size'),
      '#placeholder' => $this->getSetting('placeholder'),
      '#element_validate' => array(array($this, 'elementValidate')),
      '#autocreate_uid' => ($entity instanceof EntityOwnerInterface) ? $entity->getOwnerId() : \Drupal::currentUser()
        ->id(),
      '#attributes' => array(
        'data-select2-taxonomy-widget' => 1,
        'class' => array(
          'select2-widget',
        ),
        'data-default-value' => json_encode($this->getLabels($items, $delta)),
      ),
    );

    $element['#attached']['library'][] = 'select2/select2.widget';

    if ($this->fieldDefinition->getType() == 'taxonomy_term_reference') {
      $element['#autocomplete_route_name'] = 'taxonomy.autocomplete';
      $element['#autocomplete_route_parameters'] = array(
        'entity_type' => $items->getEntity()->getEntityTypeId(),
        'field_name' => $this->fieldDefinition->getName(),
      );
    }
    else {
      // Prepare the autocomplete route parameters.
      $autocomplete_route_parameters = array(
        'type' => $this->getSetting('autocomplete_type'),
        'field_name' => $this->fieldDefinition->getName(),
        'entity_type' => $entity->getEntityTypeId(),
        'bundle_name' => $entity->bundle(),
      );

      if ($entity_id = $entity->id()) {
        $autocomplete_route_parameters['entity_id'] = $entity_id;
      }

      $element['#autocomplete_route_name'] = 'entity_reference.autocomplete';
      $element['#autocomplete_route_parameters'] = $autocomplete_route_parameters;
    }

    return array('target_id' => $element);
  }

}
