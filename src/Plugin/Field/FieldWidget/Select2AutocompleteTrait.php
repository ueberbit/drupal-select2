<?php

namespace Drupal\select2\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;

trait Select2AutocompleteTrait {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $fieldDefinition = $this->fieldDefinition;
    $allow_multiple_values = $this->getPluginDefinition()['multiple_values'];
    $element = parent::formElement($items, $delta, $element, $form, $form_state);
    $element['target_id'] += [
      '#attributes' => [
        'data-select2-field-name' => $fieldDefinition->getName(),
        // Disable core autocomplete
        'data-jquery-once-autocomplete' => 'true',
        'class' => [
          'select2-widget',
        ],
      ],
    ];
    $element['target_id']['#attached']['library'][] = 'select2/select2.widget';
    $element['target_id']['#attached']['drupalSettings']['select2'] = array(
      $fieldDefinition->getName() => array(
        'multiple' => $allow_multiple_values,
        'display_id' => $this->getSetting('display_id'),
        'token_separator' => $allow_multiple_values ? array(',') : null,
        'tags' => $allow_multiple_values,
      ),
    );
    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $form = parent::settingsForm($form, $form_state);

    $form['display_id'] = [
      '#type' => 'checkbox',
      '#title' => t('Display Entity ID in parentheses behind the name'),
      '#default_value' => $this->getSetting('display_id'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
        'display_id' => TRUE,
      ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary = parent::settingsSummary();
    if ($this->getSetting('display_id') == FALSE) {
      $summary[] = t("Don't show entity IDs in parentheses");
    }
    return $summary;
  }


}