<?php
/*
  $Id$

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2015 osCommerce

  Released under the GNU General Public License
*/

  use OSC\OM\OSCOM;
  use OSC\OM\Registry;

  class bm_currencies {
    var $code = 'bm_currencies';
    var $group = 'boxes';
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function bm_currencies() {
      $this->title = MODULE_BOXES_CURRENCIES_TITLE;
      $this->description = MODULE_BOXES_CURRENCIES_DESCRIPTION;

      if ( defined('MODULE_BOXES_CURRENCIES_STATUS') ) {
        $this->sort_order = MODULE_BOXES_CURRENCIES_SORT_ORDER;
        $this->enabled = (MODULE_BOXES_CURRENCIES_STATUS == 'True');

        $this->group = ((MODULE_BOXES_CURRENCIES_CONTENT_PLACEMENT == 'Left Column') ? 'boxes_column_left' : 'boxes_column_right');
      }
    }

    function execute() {
      global $PHP_SELF, $currencies, $request_type, $oscTemplate;

      if (substr(basename($PHP_SELF), 0, 8) != 'checkout') {
        if (isset($currencies) && is_object($currencies) && (count($currencies->currencies) > 1)) {
          reset($currencies->currencies);
          $currencies_array = array();
          foreach($currencies->currencies as $key => $value) {
            $currencies_array[] = array('id' => $key, 'text' => $value['title']);
          }

          $hidden_get_variables = '';
          foreach ( $_GET as $key => $value ) {
            if ( is_string($value) && ($key != 'currency') && ($key != session_name()) && ($key != 'x') && ($key != 'y') ) {
              $hidden_get_variables .= tep_draw_hidden_field($key, $value);
            }
          }

          $form_output = tep_draw_form('currencies', OSCOM::link($PHP_SELF, '', $request_type, false), 'get') . tep_draw_pull_down_menu('currency', $currencies_array, $_SESSION['currency'], 'onchange="this.form.submit();"') . $hidden_get_variables . tep_hide_session_id() . '</form>';

          ob_start();
          include('includes/modules/boxes/templates/currencies.php');
          $data = ob_get_clean();


          $oscTemplate->addBlock($data, $this->group);
        }
      }
    }

    function isEnabled() {
      return $this->enabled;
    }

    function check() {
      return defined('MODULE_BOXES_CURRENCIES_STATUS');
    }

    function install() {
      $OSCOM_Db = Registry::get('Db');

      $OSCOM_Db->save('configuration', [
        'configuration_title' => 'Enable Currencies Module',
        'configuration_key' => 'MODULE_BOXES_CURRENCIES_STATUS',
        'configuration_value' => 'True',
        'configuration_description' => 'Do you want to add the module to your shop?',
        'configuration_group_id' => '6',
        'sort_order' => '1',
        'set_function' => 'tep_cfg_select_option(array(\'True\', \'False\'), ',
        'date_added' => 'now()'
      ]);

      $OSCOM_Db->save('configuration', [
        'configuration_title' => 'Content Placement',
        'configuration_key' => 'MODULE_BOXES_CURRENCIES_CONTENT_PLACEMENT',
        'configuration_value' => 'Right Column',
        'configuration_description' => 'Should the module be loaded in the left or right column?',
        'configuration_group_id' => '6',
        'sort_order' => '1',
        'set_function' => 'tep_cfg_select_option(array(\'Left Column\', \'Right Column\'), ',
        'date_added' => 'now()'
      ]);

      $OSCOM_Db->save('configuration', [
        'configuration_title' => 'Sort Order',
        'configuration_key' => 'MODULE_BOXES_CURRENCIES_SORT_ORDER',
        'configuration_value' => '0',
        'configuration_description' => 'Sort order of display. Lowest is displayed first.',
        'configuration_group_id' => '6',
        'sort_order' => '0',
        'date_added' => 'now()'
      ]);
    }

    function remove() {
      return Registry::get('Db')->query('delete from :table_configuration where configuration_key in ("' . implode('", "', $this->keys()) . '")')->rowCount();
    }

    function keys() {
      return array('MODULE_BOXES_CURRENCIES_STATUS', 'MODULE_BOXES_CURRENCIES_CONTENT_PLACEMENT', 'MODULE_BOXES_CURRENCIES_SORT_ORDER');
    }
  }

