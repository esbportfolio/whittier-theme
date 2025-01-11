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
