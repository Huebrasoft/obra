<?php get_header(); ?>
<main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
<?php while (have_posts()) : the_post(); ?>
<article>
<h1 class="text-4xl font-heading font-bold text-mavisal-blue mb-4"><?php the_title(); ?></h1>
<?php the_content(); ?>
</article>
<?php endwhile; ?>
</main>
<?php get_footer(); ?>
