<?php
/**
 * Walker for comment output. Extended from Walker_Comment.
 */

declare(strict_types=1);

class Whit_Comment_Walker extends Walker_Comment {

    // Dependency injection
    public function __construct(protected Whit_Html_Helper $html_helper, protected int $base_indent = 0) {}

    private function format_comment_reply_link(WP_Comment $comment, string $link_color_class): string {
        /**
         * Format anchor tag for comment reply link
         * 
         * @param WP_Comment            $comment            WP_Comment object for current element.
         * @param string                $link_color_class   Class that defines the color for the link text.
         * 
         * @return string               Returns a formatted anchor tag with a reply link.
         */

        // Get basic data about comment and post
        $comment_id = $comment->comment_ID;
        $post_id = $comment->comment_post_ID;
        $comment_author = $comment->comment_author;

        // Build the href
        $href = sprintf(
            '%s?replytocom=%s#respond', 
            get_permalink($post_id),
            $comment_id
        );

        // Create the attributes
        $attr = array(
            'rel' => 'nofollow',
            'href' => $href,
            'data-commentid' => $comment_id,
            'data-postid' => $post_id,
            'data-belowelement' => sprintf('comment-%s', $comment_id),
            'data-respondelement' => 'respond',
            'data-replyto' => sprintf('Reply to %s', $comment_author),
            'aria-label' => sprintf('Reply to %s', $comment_author)
        );

        // Return the formatted string
        return $this->html_helper->create_html_tag(
            tag_type: 'a',
            inner_html: 'Reply',
            classes: array('comment-reply-link', $link_color_class, 'whit-hover-line-only'), // comment-reply-link is what WP JS uses to identify links
            attr: $attr,
        );

    }

    private function format_comment_edit_link(WP_Comment $comment, string $link_color_class): string {
        /**
         * Format anchor tag for comment edit link
         * 
         * @param WP_Comment            $comment            WP_Comment object for current element.
         * @param string                $link_color_class   Class that defines the color for the link text.
         * 
         * @return string               Returns a formatted anchor tag with a reply link.
         */

        // Build the href
        $href = sprintf(
            '%scomment.php?action=editcomment&amp;c=%s', 
            get_admin_url(),
            $comment->comment_ID
        );

        // Return the formatted string
        return $this->html_helper->create_html_tag(
            tag_type: 'a',
            inner_html: 'Edit',
            classes: array('me-3', $link_color_class, 'whit-hover-line-only'),
            attr: array(
                'href' => $href,
                'target' => '_self',
            ),
        );
    }

    private function format_edit_reply_line(WP_Comment $data_object, int $depth, array $args, bool $user_can_edit, string $link_color_class): string {
        /**
         * Format edit / reply line
         * 
         * @param object                $data_object        WP_Comment object for current element.
         * @param int                   $depth              Current depth of walker. Starts at 0 for level of first items.
         * @param array                 $args               Arguments as an associative array.
         * @param bool                  $user_can_edit      Whether the current user can edit the current comment
         * @param string                $link_color_class         Link color class.
         * 
         * @return string               Returns formatted HTML for an edit / reply line or string if not applicable
         */

        // Default condition is not to show edit / reply line
        $show_edit_reply = false;
        $edit_reply_line = '';

        // Create an edit link if applicable
        $edit_link = '';
        if ( $user_can_edit ) {
            $edit_link = $this->format_comment_edit_link($data_object, $link_color_class);
            $show_edit_reply = true;
        }

        // Create a reply link if comments are open and we haven't reached the maximum allowed nesting
        $reply_link = '';
        if ( comments_open($data_object->comment_post_ID) && $depth < $args['max_depth'] - 1 ) {
            $reply_link = $this->format_comment_reply_link($data_object, $link_color_class);
            $show_edit_reply = true;
        }
       
        if ($show_edit_reply) {
            $edit_reply_line = $this->html_helper->create_html_tag(
                tag_type: 'p',
                inner_html: $edit_link . $reply_link,
                classes: array('mb-0'),
            );
        }

        return str_repeat(T, $this->base_indent + $depth + 1) . $edit_reply_line . N;

    }

    private function format_comment_data(WP_Comment $data_object, int $depth, array $args, bool $user_can_edit, string $color_flag = ''): array {
        /**
         * Format comment data
         * 
         * @param object                $data_object        WP_Comment object for current element.
         * @param int                   $depth              Current depth of walker. Starts at 0 for level of first items.
         * @param array                 $args               Arguments as an associative array.
         * @param bool                  $user_can_edit      Whether the current user can edit the current comment
         * @param string                $color_flag         Alternate color flag for container div (alters some colors of comment elements)
         * 
         * @return array                Returns array of formatted HTML elements
         */

        // Get the muted text class based on the color flag
        $muted_text_class = 'text-' . $color_flag . '-muted';

        // Build the author line
        $author_line = $this->html_helper->create_html_tag(
            tag_type: 'p',
            inner_html: get_comment_author($data_object),
            classes: array('mb-0', 'fw-bold'),
        );

        // If the comment isn't approved, there will be a moderation
        // paragraph, so the date shouldn't have a bottom margin
        $date_classes = array($muted_text_class);
        if (!$data_object->comment_approved) {
            $date_classes[] = 'mb-0';
        }
        // Build the date line
        $date_line = $this->html_helper->create_html_tag(
            tag_type: 'p',
            inner_html: get_comment_date('F j, Y', $data_object),
            classes: $date_classes,
        );

        // Set default moderation condtions
        $mod_line = '';
        // Build the in moderation line
        if (!$data_object->comment_approved) {
            // Build the moderation line
            $mod_p = $this->html_helper->create_html_tag(
                tag_type: 'p',
                inner_html: 'Your comment is awaiting moderation. This is a preview; your comment will be visible after it has been approved.',
                classes: array('fst-italic', $muted_text_class),
            );
            $mod_line = str_repeat(T, $this->base_indent + $depth + 1) . $mod_p . N;
        }

        // Get the comment content and add paragraph breaks
        $comment_text = wpautop(get_comment_text($data_object));

        // Build the edit/reply line
        $link_color = ($color_flag) ? 'text-dark' : 'text-primary';
        $edit_reply_line = $this->format_edit_reply_line($data_object, $depth, $args, $user_can_edit, $link_color);

        return array(
            'author' => $author_line,
            'date' => $date_line,
            'mod_status' => $mod_line,
            'comment' => $comment_text,
            'edit_reply' => $edit_reply_line,
        );
    }

    public function start_el(&$output, $data_object, $depth = 0, $args = null, $current_object_id = 0) {
        /**
         * Start of each element.  Abstract class in extending class, so much match that format
         * and cannot specify types.
         * 
         * @param string                $output             Output of walker. Passed by reference so all functions in class affect it.
         * @param object                $data_object        WP_Comment object for current element.
         * @param int                   $depth              Current depth of walker. Starts at 0 for level of first items.
         * @param array                 $args               Arguments as an associative array.
         * @param int                   $current_objct_id   Current object ID, but not passed in by default.
         */


        // Check if the user is logged in and owns the current comment
        $user_owns_comment = is_user_logged_in() && ( intval($data_object->user_id) === intval(get_current_user_id()) );
        // The current user can edit the comment if they own the comment and can edit their own posts OR
        // if they can edit others posts
        $user_can_edit = ($user_owns_comment && current_user_can('edit_posts')) || current_user_can('edit_others_posts');

        // Set standard comment div classes
        $comment_div_classes = array('comment', 'mb-3', 'p-3');
        // Special handlling for colored boxes (logged in user's comment or comment in moderation)
        if ($user_owns_comment || !$data_object->comment_approved) {
            $color_flag = (!$data_object->comment_approved) ? 'warning' : 'success';
            array_push($comment_div_classes, 'alert', 'alert-' . $color_flag);
        } else {
            $color_flag = '';
            $comment_div_classes[] = 'card';
        }

        // Get array of HTML comment contents
        $comment_html_arr = $this->format_comment_data($data_object, $depth, $args, $user_can_edit, $color_flag);

        // Build the div to contain the comment
        $comment_div = $this->html_helper->create_html_tag(
            tag_type: 'div',
            return_str: false,
            ids: array(sprintf('comment-%s', $data_object->comment_ID)),
            classes: $comment_div_classes,
        );

        // Output the end result
        $output .= 
            str_repeat(T, $this->base_indent + $depth) . $comment_div['start'] . N . 
            str_repeat(T, $this->base_indent + $depth + 1) . $comment_html_arr['author'] . N . 
            str_repeat(T, $this->base_indent + $depth + 1) . $comment_html_arr['date'] . N . 
            $comment_html_arr['mod_status'] . 
            str_repeat(T, $this->base_indent + $depth + 1) . $comment_html_arr['comment'] . 
            $comment_html_arr['edit_reply'] . 
            str_repeat(T, $this->base_indent + $depth) . $comment_div['end'] . N;
    }

    public function end_el( &$output, $data_object, $depth = 0, $args = array() ) {
        /**
         * Start of each element.  Abstract class in extending class, so much match that format
         * and cannot specify types.
         * 
         * @param string                $output             Output of walker. Passed by reference so all functions in class affect it.
         * @param object                $data_object        WP_Comment object for current element.
         * @param int                   $depth              Current depth of walker. Starts at 0 for level of first items.
         * @param array                 $args               Arguments as an associative array.
         */

        // For the cards to behave correctly, the element div must close in start_el and NOT in end_el
        // (Otherwise, all nested comments will be placed inside their parent's comment cards)
        $output .= '';

    }

	public function start_lvl( &$output, $depth = 0, $args = null ) {
        /**
         * Start of each level beyond the top level.  Abstract class in extending class, so much match that format
         * and cannot specify types.
         * 
         * @param string                $output             Output of walker. Passed by reference so all functions in class affect it.
         * @param int                   $depth              Current depth of walker. Starts at 0 for first children of top-level items.
         * @param null|array|object     $args               Arguments.  This will be an object if invoked with wp_nav_walker but an array
         *                                                  for other uses.
         * @param int                   $current_objct_id   Current object ID, but not passed in by default.
         */

        $output .= str_repeat(T, $this->base_indent + $depth) . '<div class="thread ms-5">' . N;

    }

    public function end_lvl( &$output, $depth = 0, $args = null ) {
        /**
         * End of each level beyond the top level.  Abstract class in parent class, so much match that format
         * and cannot specify types.
         * 
         * @param string                $output             Output of walker. Passed by reference so all functions in class affect it.
         * @param int                   $depth              Current depth of walker. Starts at 0 for first children of top-level items.
         * @param null|array|object     $args               Arguments.  This will be an object if invoked with wp_nav_walker but an array
         *                                                  for other uses.
         * @param int                   $current_objct_id   Current object ID, but not passed in by default.
         */

        $output .= str_repeat(T, $this->base_indent + $depth) . '</div>' . N;
    }

}