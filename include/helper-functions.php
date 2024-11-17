<?php

/**
 * @param $min_size
 * @param $max_size
 * @param int $min_viewport
 * @param int $max_viewport
 * @return string
 */
function clamp($min_size, $max_size, int $min_viewport = 576, int $max_viewport = 1400) {
    $view_port_width_offset = ($min_viewport / 100) / 16 . 'rem';
    $size_difference = $max_size - $min_size;
    $viewport_difference = $max_viewport - $min_viewport;
    $linear_factor = round(($size_difference / $viewport_difference) * 100, 4);

    $fluid_target_size = ($min_size / 16) . "rem + ((1vw - {$view_port_width_offset}) * {$linear_factor})";

    $result = "";

    if ($min_size == $max_size) {
        $result = ($min_size / 16) . 'rem';
    } else if ($min_size > $max_size) {
        $result = "clamp(" . $max_size / 16 . "rem, {$fluid_target_size}, " . $min_size / 16 . "rem)";
    } else if ($min_size < $max_size) {
        $result = "clamp(" . $min_size / 16 . "rem, {$fluid_target_size}, " . $max_size / 16 . "rem)";
    }

    return $result;
}

/**
 * @param string $general_class
 * @param array $block
 * @param bool $is_preview
 * @return array
 */
function get_section_options(string $general_class = '', array $block = array(), bool $is_preview = false): array
{

    $result = array();

    if(!empty($block['anchor'])) {
        $result['id_attr'] = 'id="' . esc_attr($block['anchor']) . '" ';
        $result['id'] = $block['anchor'];
    } elseif (!empty($block['id'])) {
        $result['id_attr'] = 'id="' . esc_attr($block['id']) . '" ';
        $result['id'] = $block['id'];
    } else {
        $result['id_attr'] = '';
        $result['id'] = '';
    }

    $result['class'] = array();
    $result['class'][] = $general_class;

    if (!empty($block['className'])) $result['class'][] = $block['className'];

    if (!empty($block['align'])) $result['class'][] = 'align-' . $block['align'];

    if (!empty($is_preview)) $result['class'][] = $general_class . '_is-preview';

    $hp_blocks_background = get_field('hp_blocks_background') ?: 'hp-bg-brand-black';
    $result['class'][] = $hp_blocks_background;

    $result['class'][] = B_PREFIX . '-section-element';

    return $result;
}

/**
 * @param array $block
 * @param string $src
 * @return bool
 */
function has_preview_screenshot(array $block = array(), string $src = ''): bool
{
    $screenshot = get_field('screenshot');
    $filename = str_replace('acf/', '', $block['name']);
    $screenshot_src = !empty($src) ? $src : '/assets/img/screenshots/' . $filename . '.jpg';

    if (!empty($screenshot) && file_exists(B_TEMP_PATH . $screenshot_src)) {
        echo '<img width="100%" height="100%" src="' . B_TEMP_URL . $screenshot_src . '" alt="' . $filename . '">';

        return true;
    }

    return false;
}

/**
 * @param $link_arr
 * @param array $classes
 * @param string $teg
 * @param array $atts
 * @param bool $return
 * @return string|void
 */
function the_btn($link_arr, array $classes = array(), string $teg = 'a', array $atts = array(), bool $return = false)
{
    $class_link = array('fl-btn');
    $class_link = array_merge($class_link, $classes);

    if (!empty($link_arr)) {
        $html = '';
        $atts['class'] = esc_attr(trim(implode(' ', $class_link), " "));
        $atts['href'] = (!empty($link_arr['url']) && $teg === 'a') ? esc_url($link_arr['url']) : '';
        $atts['target'] = (!empty($link_arr['target']) && $link_arr['target'] === '_blank') ? '_blank' : '';
        $atts['aria-label'] = !empty($link_arr['title']) ? esc_attr($link_arr['title']) : 'Button';
        if($teg === 'span') $atts['role'] = 'button';

        $attributes = '';
        foreach ($atts as $attr => $value) {
            if (is_scalar($value) && '' !== $value && false !== $value) {
                $value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
                $attributes .= ' ' . $attr . '="' . $value . '"';
            }
        }

        $html .= '<' . $teg . $attributes . '>';
        $html .= !empty($link_arr['title']) ? esc_html($link_arr['title']) : '';
        $html .= '</' . $teg . '>';

        if (!$return) {
            echo $html;
        } else {
            return $html;
        }
    }
}

/**
 * @param $block_name
 * @return bool
 */
function has_first_block($block_name): bool
{
    global $post;

    if (!$post) {
        return false;
    }

    $content = $post->post_content;

    if (has_blocks($content)) {
        $blocks = parse_blocks($content);

        if (!empty($blocks)) {
            $first_block      = reset($blocks);
            $first_block_name = !empty($first_block['blockName']) ? $first_block['blockName'] : '';

            if (is_array($block_name)) {
                return in_array($first_block_name, $block_name);
            } else {
                return $first_block_name === $block_name;
            }
        }
    }

    return false;
}

/**
 * @param $block_name
 * @return bool
 */
function has_last_block($block_name): bool
{
    global $post;

    if (!$post) {
        return false;
    }

    $content = $post->post_content;

    if (has_blocks($content)) {
        $blocks = parse_blocks($content);

        if (!empty($blocks)) {
            $last_block      = end($blocks);
            $last_block_name = !empty($last_block['blockName']) ? $last_block['blockName'] : '';

            if (is_array($block_name)) {
                return in_array($last_block_name, $block_name);
            } else {
                return $last_block_name === $block_name;
            }
        }
    }

    return false;
}

/**
 * @param $post_id
 * @param int $excerpt_length
 * @param string $more
 * @return string
 */
function custom_wp_trim_excerpt($post_id = null, int $excerpt_length = 55, string $more = ''): string
{
    if ($post_id === null) {
        global $post;
        $post_id = $post->ID;
    }

    if (has_excerpt($post_id)) {
        return get_the_excerpt($post_id);
    } else {
        $post = get_post($post_id);
        $text = get_the_content('', false, $post);

        $text = strip_shortcodes($text);
        $text = excerpt_remove_blocks($text);
        $text = excerpt_remove_footnotes($text);

        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]&gt;', $text);

        return wp_trim_words($text, $excerpt_length, $more);
    }
}
