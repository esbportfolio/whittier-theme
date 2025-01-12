<?php
/**
 * The template for displaying comments
 *
 * This is the template that displays the area of the page that contains both the current comments
 * and the comment form.
 * 
 * This should always be called inside a conditional statement, since the comments section
 * shouldn't be created if there are no comments.  Variables in this file should be scoped
 * to that conditional and should *not* be global.
 */

declare(strict_types=1);

// Retrieve comment count for the current post
$comment_count = get_comments_number(get_the_id());
$comment_text = $comment_count . ( (intval($comment_count) === 1) ? ' response' : ' responses' ) . ' on “' . get_the_title() . '”';

?>
                </div>
                <div id="comments">
                    <h3>Comments</h3>
                    <h5><?php echo $comment_text; ?></h5>
                    <div id="comments-content" class="pt-3">
                        <div class="thread">
<?php
wp_list_comments(array(
    'style' => 'div',
    'walker' => new Whit_Comment_Walker(new Whit_Html_Helper, 7),
));
?>
                        </div>
                    </div>
                    <div id="comment-reply" class="mb-3">
<?php

// Create a new form formatter and have it retrieve the requested fields
$form_formatter = new Whit_Form_Formatter(new Whit_Html_Helper);

// NOTE: The URL field has been unset in the functions file, but there doesn't seem to be
// a way to get that array back out.  This function will create some redundant 
// html if any fields are unset, but I think it's best to do that rather than
// having a second place we need to define those arguuments.
$form_fields = $form_formatter->format_form_fields(3);

// Output comment form, using args to modify
comment_form(array(
    'fields' => apply_filters( 'comment_form_default_fields', array(
        'author' => $form_fields['author'] ?? '',
        'email' => $form_fields['email'] ?? '',
        'url' => $form_fields['url'] ?? '',
        'cookies' => $form_fields['cookies'] ?? '',
    )),
    'comment_field' => $form_fields['comment'] ?? '',
    'cancel_reply_before' => '<span class="ms-1 fs-6">',
    'cancel_reply_after' => '</span>',
    'class_submit' => 'btn btn-primary',
    'title_reply_after' => '</h3>' . N,
)); 
?>
                    </div>
