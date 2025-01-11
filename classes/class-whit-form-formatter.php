<?php
/**
 * Class that formats pages for display
 */

declare(strict_types=1);

class Whit_Form_Formatter {

    // Dependency injection
    public function __construct(private Whit_Html_Helper $html_helper) {}

    private function format_control_group(string $label_html, string $field_html, array $classes, int $base_indent = 0): string {
        /**
         * Formats a group of controls within a div.
         * 
         * @param string        $label_html         Label HTML.
         * @param string        $field_html         Field HTML.
         * @param array         $classes            Classes for the containing div.
         * @param int           $base_indent        Base indent for the div class.
         * @param string        $tag_type           Type of enclosing tag. Default div.
         * 
         * @return string       Returns a block of formatted HTML (div with label and control nested in it).
         */

        $output = '';

        $div = $this->html_helper->create_html_tag(
            tag_type: 'div',
            return_str: false,
            classes: $classes
        );

        $output .=
            str_repeat(T, $base_indent) . $div['start'] . N . 
            str_repeat(T, $base_indent + 1) . $label_html . N . 
            str_repeat(T, $base_indent + 1) . $field_html . N . 
            str_repeat(T, $base_indent) . $div['end'] . N;

        return $output;

    }

    private function format_label(
        string $label_for, 
        string $label_text, 
        bool $is_required = false, 
        array $classes = array(),
        array $attr = array()
    ): string {
        /**
         * Formats a label
         * 
         * @param string        $label_for          Value to use in the 'for' value of the label.
         * @param string        $label_text         Text to display within the label.
         * @param bool          $is_required        If the label marks a required field. Default false.
         * @param array         $ids                Array of IDs. Default empty array will not show any IDs.
         * @param array         $classes            Array of classes. Default empty array will not show any classes.
         * @param array         $attr               Array of attributes. Default empty array will not show any attributes.
         * 
         * @return string       Returns a formatted HTML label.
         */

        // Add the for attribute to the attributes
        $attr['for'] = $label_for;

        $inner_html = _x($label_text, 'noun') . ( ($is_required) ?  ' ' . wp_required_field_indicator() : '' );

        return $this->html_helper->create_html_tag(
            tag_type: 'label',
            inner_html: $inner_html,
            classes: $classes,
            attr: $attr,
        );

    }

    private function format_field(
        string $field_type, 
        string $name, 
        int $maxlength,
        bool $is_required = false,
        array $ids = array(),
        array $classes = array(),
        array $attr = array(),
    ) {
        /**
         * Formats a field
         * 
         * @param string        $field_type         Tag type for field.
         * @param string        $name               Value for name attribute.
         * @param int           $maxlength          Max length of field.
         * @param bool          $is_requried        Whether field is requried. Default false.
         * @param array         $ids                Array of IDs. Default empty array will not show any IDs.
         * @param array         $classes            Array of classes. Default empty array will not show any classes.
         * @param array         $attr               Array of attributes. Default empty array will not show any attributes.
         */

        // Add additional attributes to attributes array
        $attr = array_merge($attr, array(
            'name' => $name,
            'maxlength' => $maxlength,
        ));
        if ($is_required) {
            $attr['required'] = 'required';
        }

        return $this->html_helper->create_html_tag(
            tag_type: $field_type,
            ids: $ids,
            classes: $classes,
            attr: $attr
        );

    }

    public function get_fields(int $base_indent = 0, array $args = array()) {

        // Default conditions
        $output = array();
        $input_classes = array('form-control');

        // Default conditions
        $defaults = array(
            'author' => true,
            'email' => true,
            'url' => true,
            'cookies' => true,
            'comment' => true,
        );

        // Merge defaults with args (arg values override defaults)
        $args = array_merge($defaults, $args);
        // Extract args to variables
        extract($args);

        // If the author argument is true, get the author field group
        if ($author) {
            $label = $this->format_label('author', 'Name', true, array('form-label, mb-0'));
            $field = $this->format_field(
                field_type: 'input',
                name: 'author',
                maxlength: 100,
                is_required: true,
                classes: $input_classes,
                attr: array(
                    'type' => 'text',
                    'value' => '',
                    'autocomplete' => 'name',
                ),
            );
            $output['author'] = $this->format_control_group($label, $field, array('mb-3'), $base_indent);
        }

        // If the email argument is true, get the email field group
        if ($email) {
            $label = $this->format_label('email', 'Email', true, array('form-label, mb-0'));
            $field = $this->format_field(
                field_type: 'input',
                name: 'email',
                maxlength: 100,
                is_required: true,
                classes: $input_classes,
                attr: array(
                    'type' => 'email',
                    'value' => '',
                    'autocomplete' => 'email',
                    'aria-describedby' => 'email-notes',
                ),
            );
            $output['email'] = $this->format_control_group($label, $field, array('mb-3'), $base_indent);
        }

        // If the url argument is true, get the url field group
        if ($url) {
            $label = $this->format_label('url', 'Website', false, array('form-label, mb-0'));
            $field = $this->format_field(
                field_type: 'input',
                name: 'url',
                maxlength: 200,
                is_required: true,
                classes: $input_classes,
                attr: array(
                    'type' => 'text',
                    'value' => '',
                    'autocomplete' => 'url',
                ),
            );
            $output['url'] = $this->format_control_group($label, $field, array('mb-3'), $base_indent);
        }

        // If the cookies argument is true, get the cookies field group
        if ($cookies) {
            $label = $this->format_label(
                'wp-comment-cookies-consent', 
                'Save my name, email, and website in this browser for the next time I comment.', 
                false, 
                array('form-check-label')
            );
            $field = $this->format_field(
                field_type: 'input',
                name: 'wp-comment-cookies-consent',
                maxlength: 1,
                ids: array('wp-comment-cookies-consent'),
                classes: array('form-check-input'),
                attr: array(
                    'type' => 'checkbox',
                    'value' => '',
                ),
            );
            $output['cookies'] = $this->format_control_group($label, $field, array('mb-3', 'form-check'), $base_indent);
        }

        // If the comment argument is true, get the comment field group
        if ($comment) {
            $label = $this->format_label('comment', 'Comment', true, array('form-label, mb-0'));
            $field = $this->format_field(
                field_type: 'textarea',
                name: 'comment',
                maxlength: 2000,
                is_required: true,
                ids: array('comment'),
                classes: $input_classes,
                attr: array(
                    'rows' => 5,
                )
            );
            $output['comment'] = $this->format_control_group($label, $field, array('mb-3'), $base_indent);
        }

        return $output;

    }

}
