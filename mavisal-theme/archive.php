<?php get_header(); ?>
<main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
<h1 class="text-4xl font-heading font-bold text-mavisal-blue mb-8"><?php the_archive_title(); ?></h1>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<article class="mb-6"><a class="text-xl text-mavisal-blue" href="<?php the_permalink(); ?>"><?php the_title(); ?></a></article>
<?php endwhile; the_posts_pagination(); endif; ?>
</main>
<?php get_footer(); ?>
