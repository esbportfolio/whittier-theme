<?php
/**
 * Comments template part. Should always be called inside a conditional statement, since
 * the comments section shouldn't be created if there are no comments.
 */

declare(strict_types=1);

// Retreive comment count for the current post
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

$form_formatter = new Whit_Form_Formatter(new Whit_Html_Helper);

$form_fields = $form_formatter->get_fields(3);

comment_form(array(
    'fields' => apply_filters( 'comment_form_default_fields', array(
        'author' => $form_fields['author'],
        'email' => $form_fields['email'],
        'url' => $form_fields['url'],
        'cookies' => $form_fields['cookies'],
    )),
    'comment_field' => $form_fields['comment'],
    'class_submit' => 'btn btn-primary',
    'cancel_reply_before' => '<span class="ms-1 fs-6">',
    'cancel_reply_after' => '</span>',
)); 
?>
                    </div>
