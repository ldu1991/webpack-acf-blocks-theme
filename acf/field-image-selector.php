<?php

if (!class_exists('wp_custom_acf_field_image_selector')) :

    class wp_custom_acf_field_image_selector extends acf_field
    {

        function initialize()
        {
            $this->name     = 'wp_custom_acf_image_selector';
            $this->label    = __('Image Selector', B_PREFIX);
            $this->category = 'choice';
            $this->defaults = array(
                'choices'         => array(),
                'default_value'   => '',
                'return_format'   => 'value',
                'image_path'      => 'assets/img/image-select/',
                'image_extension' => 'jpg',
            );
        }

        function input_admin_enqueue_scripts()
        {
            wp_enqueue_style(B_PREFIX . '-wp-custom-acf-image-selector', get_template_directory_uri() . '/acf/field-image-selector.css');
            wp_enqueue_script(B_PREFIX . '-wp-custom-acf-image-selector', get_template_directory_uri() . '/acf/field-image-selector.js', array('jquery'), false, true);
        }

        function render_field($field)
        {

            // vars
            $e  = '';
            $ul = array(
                'class' => 'wp-custom-acf-image-selector'
            );

            // append to class
            $ul['class'] .= ' ' . $field['class'];

            // Determine selected value.
            $value = (string)$field['value'];

            // 1. Selected choice.
            if (isset($field['choices'][$value])) {
                $checked = (string)$value;

                // 2. Custom choice.
            } else {
                $checked = (string)key($field['choices']);
            }
            // Bail early if no choices.
            if (empty($field['choices'])) {
                return;
            }

            // Hiden input.
            $e .= acf_get_hidden_input(array('name' => $field['name']));

            // Open <ul>.
            $e .= '<div ' . acf_esc_attrs($ul) . '>';

            // Loop through choices.
            foreach ($field['choices'] as $value => $label) {
                $is_selected = false;

                // Ensure value is a string.
                $value = (string)$value;

                // Define input attrs.
                $attrs = array(
                    'type'  => 'radio',
                    'id'    => sanitize_title($field['id'] . '-' . $value),
                    'name'  => $field['name'],
                    'value' => $value,
                );

                // Check if selected.
                if (esc_attr($value) === esc_attr($checked)) {
                    $attrs['checked'] = 'checked';
                    $is_selected      = true;
                }

                // Check if is disabled.
                if (isset($field['disabled']) && acf_in_array($value, $field['disabled'])) {
                    $attrs['disabled'] = 'disabled';
                }

                // append
                $e .= '<label class="wp-custom-acf-image-selector__item' . ($is_selected ? ' selected' : '') . '">';
                $e .= '<input ' . acf_esc_attrs($attrs) . '/>';
                $e .= '<span class="wp-custom-acf-image-selector__item-wrap">';
                $e .= '<img src="' . get_template_directory_uri() . '/' . $field['image_path'] . esc_attr($value) . '.' . $field['image_extension'] . '" alt="' . esc_attr($label) . '">';
                $e .= '<span class="wp-custom-acf-image-selector__item-title">' . acf_esc_html($label) . '</span>';
                $e .= '</span>';
                $e .= '</label>';
            }

            // Close <ul>.
            $e .= '</div>';

            // Output HTML.
            echo $e; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- escaped per attribute above.
        }

        function render_field_settings($field)
        {
            // Encode choices (convert from array).
            $field['choices'] = acf_encode_choices($field['choices']);

            acf_render_field_setting(
                $field,
                array(
                    'label'        => __('Choices', 'acf'),
                    'instructions' => __('Enter each choice on a new line.', 'acf') . '<br />' . __('For more control, you may specify both a value and label like this:', 'acf') . '<br /><span class="acf-field-setting-example">' . __('red : Red', 'acf') . '</span>',
                    'type'         => 'textarea',
                    'name'         => 'choices',
                )
            );

            acf_render_field_setting(
                $field,
                array(
                    'label'        => __('Default Value', 'acf'),
                    'instructions' => __('Appears when creating a new post', 'acf'),
                    'type'         => 'text',
                    'name'         => 'default_value',
                )
            );

            acf_render_field_setting(
                $field,
                array(
                    'label'        => __('Image Path'),
                    'instructions' => "Enter complete URL for images<br /><span style='color:#BC0B0B'>assets/img/image-select/{block}/</span>",
                    'prepend'      => get_template_directory_uri() . '/',
                    'type'         => 'text',
                    'name'         => 'image_path',
                )
            );

            acf_render_field_setting(
                $field,
                array(
                    'label' => _('Image Extension'),
                    'type'  => 'text',
                    'name'  => 'image_extension',
                )
            );

            acf_render_field_setting(
                $field,
                array(
                    'label'        => __('Return Value', 'acf'),
                    'instructions' => __('Specify the returned value on front end', 'acf'),
                    'type'         => 'radio',
                    'name'         => 'return_format',
                    'layout'       => 'horizontal',
                    'choices'      => array(
                        'value' => __('Value', 'acf'),
                        'label' => __('Label', 'acf'),
                        'array' => __('Both (Array)', 'acf'),
                    ),
                )
            );
        }

        function update_field($field)
        {

            // decode choices (convert to array)
            $field['choices'] = acf_decode_choices($field['choices']);

            // return
            return $field;
        }


        function update_value($value, $post_id, $field)
        {

            // bail early if no value (allow 0 to be saved)
            if (!$value && !is_numeric($value)) {
                return $value;
            }

            // return
            return $value;
        }


        function load_value($value, $post_id, $field)
        {

            // must be single value
            if (is_array($value)) {
                $value = array_pop($value);
            }

            // return
            return $value;
        }


        function translate_field($field)
        {

            return acf_get_field_type('select')->translate_field($field);
        }


        function format_value($value, $post_id, $field)
        {

            return acf_get_field_type('select')->format_value($value, $post_id, $field);
        }

        function get_rest_schema(array $field)
        {
            $schema = parent::get_rest_schema($field);

            if (isset($field['default_value']) && '' !== $field['default_value']) {
                $schema['default'] = $field['default_value'];
            }

            $schema['enum'] = acf_get_field_type('select')->format_rest_choices($field['choices']);

            return $schema;
        }

    }


    // initialize
    acf_register_field_type('wp_custom_acf_field_image_selector');

endif; // class_exists check

