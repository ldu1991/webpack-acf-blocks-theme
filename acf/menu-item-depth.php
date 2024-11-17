<?php
if (!defined('ABSPATH')) {
    exit;
}

function wp_custom_acf_array_insert_after($array, $key, $new_key, $new_value = null)
{

    if (!is_array($array) || !isset($array[$key])) {
        return $array;
    }

    $is_sequential = acf_is_sequential_array($array);
    $new_array     = array();

    foreach ($array as $k => $value) {

        if ($is_sequential) {
            $new_array[] = $value;

        } else {
            $new_array[$k] = $value;
        }

        if ($k === $key) {

            if ($is_sequential) {

                $new_value   = $new_value === null ? $new_key : $new_value;
                $new_array[] = $new_value;

            } else {

                if ($new_value === null && is_array($new_key)) {
                    reset($new_key);
                    $new_value = current($new_key);
                    $new_key   = key($new_key);
                }

                $new_array[$new_key] = $new_value;

            }

        }

    }

    return $new_array;

}

if (!class_exists('wp_custom_acf_location_menu_item_depth')):

    class wp_custom_acf_location_menu_item_depth extends acf_location
    {
        function initialize()
        {

            $this->name     = 'nav_menu_item_depth';
            $this->label    = __('Menu Item Depth', B_PREFIX);
            $this->category = 'forms';

            add_filter('acf/location/rule_types', array($this, 'location_rules_types'));

        }

        function location_rules_types($groups)
        {
            foreach ($groups as &$sub_group) {

                if (isset($sub_group['nav_menu_item'])) {

                    $sub_group = wp_custom_acf_array_insert_after($sub_group, 'nav_menu_item', $this->name, $this->label);

                }

            }

            return $groups;

        }

        function rule_values($choices, $rule)
        {
            return array(
                0 => 'Depth 1',
                1 => 'Depth 2',
                2 => 'Depth 3',
                3 => 'Depth 4',
                4 => 'Depth 5'
            );
        }

        function rule_operators($choices, $rule)
        {
            return $choices;
        }

        function rule_match($result, $rule, $screen)
        {

            // Vars
            $depth = acf_maybe_get($screen, 'nav_menu_item_depth');

            // Bail early
            if (!$depth && $depth !== 0)
                return false;

            // Compare
            return $this->compare_advanced($depth, $rule);

        }

        function compare_advanced($value, $rule, $allow_all = false)
        {
            if ($allow_all && $value === 'all') {
                return true;
            }

            if ($rule['operator'] === '==') {
                return ($value == $rule['value']);
            }

            if ($rule['operator'] === '!=') {
                return ($value != $rule['value']);
            }

            return false;
        }
    }

    new wp_custom_acf_location_menu_item_depth();

endif;
