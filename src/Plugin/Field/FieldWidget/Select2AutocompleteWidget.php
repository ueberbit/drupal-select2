<?php
/**
 * @file
 * Contains \Drupal\select2\Entity\FieldWidget\AutocompleteWidget.
 */

namespace Drupal\select2\Plugin\Field\FieldWidget;

use Drupal\Core\Field\Plugin\Field\FieldWidget\EntityReferenceAutocompleteWidget;

/**
 * Plugin implementation of the 'entity_reference autocomplete-tags' widget.
 *
 * @FieldWidget(
 *   id = "select2_autocomplete",
 *   label = @Translation("Autocomplete (Select2)"),
 *   description = @Translation("An autocomplete text field."),
 *   field_types = {
 *     "entity_reference"
 *   },
 *   multiple_values = FALSE
 * )
 */
class Select2AutocompleteWidget extends EntityReferenceAutocompleteWidget  {

  use Select2AutocompleteTrait;

}
