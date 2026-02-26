<?php get_header(); ?>
<section id="inicio" class="relative pt-24 pb-32 lg:pt-48 lg:pb-56 flex items-center min-h-[85vh]">
    <div class="absolute inset-0 z-0">
        <?php $hero_bg = get_the_post_thumbnail_url(get_queried_object_id(), 'full') ?: mavisal_get_field('hero_background_image', 'https://images.unsplash.com/photo-1503387762-592deb58ef4e?q=80&w=2071&auto=format&fit=crop'); ?>
        <img src="<?php echo esc_url($hero_bg); ?>" alt="<?php esc_attr_e('Construcción moderna', 'mavisal-theme'); ?>" class="w-full h-full object-cover" />
        <div class="absolute inset-0 bg-mavisal-blue/80 mix-blend-multiply"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-mavisal-blue via-transparent to-transparent opacity-90"></div>
    </div>
    <div class="relative z-10 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center lg:text-left flex flex-col lg:flex-row items-center">
        <div class="w-full lg:w-2/3">
            <span class="inline-block py-1 px-3 rounded-full bg-mavisal-orange/20 border border-mavisal-orange/50 text-mavisal-orange text-sm font-semibold mb-6 tracking-wide uppercase"><?php echo esc_html(mavisal_get_field('hero_badge', 'Especialistas en Salamanca')); ?></span>
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-heading font-extrabold text-white leading-tight mb-6"><?php echo esc_html(mavisal_get_field('hero_titulo', 'Construcciones MaViSal: Obras y Reformas')); ?></h1>
            <p class="text-lg sm:text-xl text-gray-300 mb-10 max-w-2xl mx-auto lg:mx-0 font-light leading-relaxed"><?php echo esc_html(mavisal_get_field('hero_subtitulo', 'Transformamos espacios con la garantía de más de 40 años de experiencia. Soluciones integrales para edificios, viviendas y locales comerciales.')); ?></p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
                <a href="<?php echo esc_url(mavisal_get_field('hero_boton_1_enlace', '#presupuesto')); ?>" class="bg-mavisal-orange hover:bg-mavisal-orangeHover text-white px-8 py-4 rounded-md font-bold text-lg transition shadow-xl shadow-orange-500/20 text-center"><?php echo esc_html(mavisal_get_field('hero_boton_1_texto', 'Solicitar Presupuesto Gratuito')); ?></a>
                <a href="<?php echo esc_url(mavisal_get_field('hero_boton_2_enlace', '#portfolio')); ?>" class="bg-transparent border border-white hover:bg-white/10 text-white px-8 py-4 rounded-md font-semibold text-lg transition text-center backdrop-blur-sm"><?php echo esc_html(mavisal_get_field('hero_boton_2_texto', 'Ver nuestros proyectos')); ?></a>
            </div>
        </div>
    </div>
</section>

<section id="servicios" class="py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-3xl mx-auto mb-16">
            <h4 class="text-mavisal-orange font-bold tracking-wider uppercase text-sm mb-3">Nuestros Servicios</h4>
            <h2 class="text-3xl md:text-4xl font-heading font-extrabold text-mavisal-blue mb-4">Soluciones integrales para construcción y reformas</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $servicios_query = new WP_Query(['post_type' => 'servicios', 'posts_per_page' => 6]);
            if ($servicios_query->have_posts()) :
                while ($servicios_query->have_posts()) :
                    $servicios_query->the_post();
                    $icono = function_exists('get_field') ? get_field('icono_servicio') : '';
                    ?>
                    <div class="bg-white border border-gray-100 rounded-2xl p-8 card-hover relative group cursor-pointer">
                        <div class="w-14 h-14 bg-orange-50 text-mavisal-orange rounded-xl flex items-center justify-center mb-6 group-hover:bg-mavisal-orange group-hover:text-white transition-colors">
                            <?php if (!empty($icono)) : ?>
                                <span class="w-7 h-7"><?php echo wp_kses_post($icono); ?></span>
                            <?php else : ?>
                                <span class="w-7 h-7">★</span>
                            <?php endif; ?>
                        </div>
                        <h3 class="font-heading font-bold text-xl text-mavisal-blue mb-3"><?php the_title(); ?></h3>
                        <p class="text-gray-500 text-sm mb-6 leading-relaxed"><?php echo esc_html(get_the_excerpt()); ?></p>
                        <a href="<?php the_permalink(); ?>" class="inline-flex items-center text-sm font-bold text-mavisal-orange group-hover:text-mavisal-blue transition-colors">Leer más</a>
                    </div>
                    <?php
                endwhile;
                wp_reset_postdata();
            endif;
            ?>
        </div>
    </div>
</section>

<section id="presupuesto" class="bg-mavisal-blue py-0">
<div class="grid grid-cols-1 lg:grid-cols-2">
<div class="p-10 lg:p-24 flex flex-col justify-center text-white"><h2 class="text-3xl md:text-5xl font-heading font-extrabold mb-6 leading-tight">¿Tienes un proyecto en mente? <br><span class="text-transparent bg-clip-text bg-gradient-to-r from-mavisal-orange to-yellow-400">Hablemos.</span></h2></div>
<div class="bg-gray-50 p-10 lg:p-24 flex items-center justify-center"><div class="bg-white rounded-2xl shadow-xl p-8 w-full max-w-lg border border-gray-100">
<?php
$form_shortcode = mavisal_get_field('form_shortcode', '');
if (!empty($form_shortcode)) {
    echo do_shortcode($form_shortcode);
} elseif (shortcode_exists('contact-form-7')) {
    echo do_shortcode('[contact-form-7 id="" title="Formulario de contacto"]');
} else {
    do_action('mavisal_contact_form');
}
?>
</div></div></div></section>

<section id="portfolio" class="py-24 bg-white"><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center"><h4 class="text-mavisal-orange font-bold tracking-wider uppercase text-sm mb-3">Portfolio</h4><h2 class="text-3xl md:text-4xl font-heading font-extrabold text-mavisal-blue mb-12">Aquí tienes una muestra de nuestro trabajo</h2><div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
<?php $proyectos = new WP_Query(['post_type' => 'proyectos', 'posts_per_page' => 6]); if ($proyectos->have_posts()) : while ($proyectos->have_posts()) : $proyectos->the_post(); $ubicacion = function_exists('get_field') ? get_field('ubicacion') : ''; ?>
<div class="group relative overflow-hidden rounded-xl aspect-[4/3] bg-gray-200 cursor-pointer">
<?php if (has_post_thumbnail()) { the_post_thumbnail('large', ['class' => 'w-full h-full object-cover transition-transform duration-700 group-hover:scale-110']); } ?>
<div class="absolute inset-0 portfolio-overlay opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-6 text-left"><h3 class="text-white font-heading font-bold text-xl"><?php the_title(); ?></h3><p class="text-gray-300 text-sm"><?php echo esc_html($ubicacion); ?></p><span class="mt-4 inline-block text-mavisal-orange font-semibold text-sm">Ver proyecto →</span></div></div>
<?php endwhile; wp_reset_postdata(); endif; ?></div></div></section>

<section class="py-24 bg-mavisal-light relative overflow-hidden"><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"><div class="text-center mb-16"><h4 class="text-mavisal-orange font-bold tracking-wider uppercase text-sm mb-3">Testimonios</h4><h2 class="text-3xl md:text-4xl font-heading font-extrabold text-mavisal-blue">Lo que dicen nuestros clientes</h2></div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-8">
<?php $testimonios = new WP_Query(['post_type' => 'testimonios', 'posts_per_page' => 3]); if ($testimonios->have_posts()) : while ($testimonios->have_posts()) : $testimonios->the_post(); $valoracion = (int) (function_exists('get_field') ? get_field('valoracion') : 5); ?>
<div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100"><h4 class="font-bold text-gray-900 mb-2"><?php the_title(); ?></h4><div class="flex text-yellow-400 text-sm mb-3"><?php echo esc_html(str_repeat('★', max(1, min(5, $valoracion)))); ?></div><p class="text-gray-600 italic"><?php echo esc_html(get_the_excerpt() ?: get_the_content()); ?></p></div>
<?php endwhile; wp_reset_postdata(); endif; ?></div></div></section>

<section class="py-24 bg-white"><div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"><div class="text-center mb-16"><h4 class="text-mavisal-orange font-bold tracking-wider uppercase text-sm mb-3">Nuestro Blog</h4><h2 class="text-3xl md:text-4xl font-heading font-extrabold text-mavisal-blue">Lee nuestras últimas entradas</h2></div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-8"><?php $blog = new WP_Query(['post_type' => 'post', 'posts_per_page' => 3]); while ($blog->have_posts()) : $blog->the_post(); ?><article class="group cursor-pointer"><a href="<?php the_permalink(); ?>"><?php if (has_post_thumbnail()) { the_post_thumbnail('medium_large', ['class' => 'w-full h-full object-cover transform group-hover:scale-105 transition duration-500']); } ?><h3 class="font-heading font-bold text-xl text-mavisal-blue mb-3 group-hover:text-mavisal-orange transition mt-3"><?php the_title(); ?></h3></a></article><?php endwhile; wp_reset_postdata(); ?></div></div></section>
<?php get_footer(); ?>
