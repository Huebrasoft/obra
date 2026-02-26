<?php get_header(); ?>
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<article <?php post_class('mb-10'); ?>>
<h1 class="text-4xl font-heading font-bold text-mavisal-blue mb-4"><?php the_title(); ?></h1>
<div><?php the_content(); ?></div>
</article>
<?php endwhile; endif; ?>
</main>
<?php get_footer(); ?>
