<?php
get_header(); ?>
	<?php
if (is_user_logged_in()) { ?>


<?php
    dynamic_sidebar('intranettop'); ?>
<article>
<?php
    if (have_posts()):
        while (have_posts()):
            the_post(); ?>
<?php
            get_template_part('content-header'); ?>
<?php
            the_content(); ?>

<?php
        endwhile;
    else: ?>
<p><?php
        _e('Ingen ting her', 'wpbase_domain'); ?></p>
<?php
        wp_link_pages(array('before' => '<div class="page-links"><span class="page-links-title">' . __('Pages:', 'wpbase_domain') . '</span>', 'after' => '</div>', 'link_before' => '<span>', 'link_after' => '</span>',));
?>
<?php
    endif; ?>
<?php
    comments_template(); ?>

</article>

<aside class="last">
<?php
    dynamic_sidebar('intranetright'); ?>
</aside>
<?php
    dynamic_sidebar('intranetbottom'); ?>
	<?php
} ?>

	<?php
if (!is_user_logged_in()) { ?>
<article>
<h4>Ingen adgang til sidens indhold!</h4>
	<p>Du har desværre ikke rettigheder til at se indholdet på denne side.<br />Det kræver at du <strong>logger ind</strong>, hvis du ønkser at se indholdet.</p>
</article>
<?php
} ?>
<?php
get_footer(); ?>