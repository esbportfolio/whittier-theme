<?php
/**
 * Class that encapsulates helper functions for formatting HTML.  Makes it easier to do dependency injection.
 */

declare(strict_types=1);

class Whit_Html_Helper {

    public function create_html_tag(
        string $tag_type, 
        bool $return_str = true,
        string $inner_html = '',
        array $ids = array(),
        array $classes = array(),
        array $attr = array()
    ): string|array {
    /**
     * Creates an HTML tag with the specified parameters
     * 
     * @param string    $tag_type    Type of html tag. Required.
     * @param bool      $return_str  Whether to return as string or array. Default true returns as string. False returns associative array.
     * @param string    $inner_html  Content to go within tag. Default is empty string.
     * @param array     $ids         Array of IDs to apply to tag. Keys will be ignored. Default (empty array) will omit ID statement.
     * @param array     $classes     Array of classes to apply to tag. Keys will be ignored. Default (empty array) will omit class statement.
     * @param array     $attr        Array of attribtues to apply to tag. Associative array required to work properly. 
     *                               Keys will be used as attribute type and value as attribute value. Default (empty array) will not add any attributes.
     * 
     * @return string|array          Returns associative array or html string
     */

        // Format IDs
        $id_f = $ids ? ' id="' . implode(' ', $ids) . '"' : '';

        // Format classes
        $class_f = $classes ? ' class="' . implode(' ', $classes) . '"' : '';

        // Format attributes
        $attr_f = '';
        foreach ($attr as $key => $value) {
            $attr_f .= " ${key}=\"$value\"";
        }

        // Create tag array
        $output = array(
            'start' => '<' . $tag_type . $class_f . $attr_f . $id_f . '>',
            'inner_html' => $inner_html,
            'end' => '</' . $tag_type . '>'
        );

        // Return ouptut
        return $return_str ? implode('', $output) : $output;
    }
    
}