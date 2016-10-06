<?php
/**
 * @file
 * Contains \Drupal\select2\Plugin\Field\FieldWidget\Select2TaxonomyAutocompleteWidget.
 */

namespace Drupal\select2\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\taxonomy\Plugin\Field\FieldWidget\TaxonomyAutocompleteWidget;

/**
 * Plugin implementation of the 'taxonomy_autocomplete' widget.
 *
 * @FieldWidget(
 *   id = "select2_taxonomy_autocomplete",
 *   label = @Translation("Select2: Autocomplete term widget (tagging)"),
 *   field_types = {
 *     "taxonomy_term_reference"
 *   },
 *   settings = {
 *     "size" = "60",
 *     "autocomplete_route_name" = "taxonomy.autocomplete",
 *     "placeholder" = ""
 *   },
 *   multiple_values = TRUE
 * )
 */
class Select2TaxonomyAutocompleteWidget extends TaxonomyAutocompleteWidget {

  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);

    $element += array(
      '#attributes' => array(
        'data-select2-taxonomy-widget' => 1,
        'class' => array(
          'select2-widget',
        ),
      ),
    );

    $element['#attached']['library'][] = 'select2/select2.widget';
    return $element;
  }

}
