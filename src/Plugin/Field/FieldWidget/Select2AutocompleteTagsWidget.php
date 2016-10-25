<?php
/**
 * @file
 * Contains \Drupal\select2\Entity\FieldWidget\AutocompleteWidget.
 */

namespace Drupal\select2\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\Plugin\Field\FieldWidget\EntityReferenceAutocompleteTagsWidget;
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
 *     "entity_reference"
 *   },
 *   multiple_values = TRUE
 * )
 */
class Select2AutocompleteTagsWidget extends EntityReferenceAutocompleteTagsWidget  {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);
    $element['target_id'] += [
      '#attributes' => [
        'data-select2-taxonomy-widget' => 'true',
        // Disable core autocomplete
        'data-jquery-once-autocomplete' => 'true',
        'class' => [
          'select2-widget',
        ],
      ],
    ];
    $element['target_id']['#attached']['library'][] = 'select2/select2.widget';
    return $element;
  }

}
