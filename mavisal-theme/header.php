<!DOCTYPE html>
<html <?php language_attributes(); ?> class="scroll-smooth">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class('font-sans text-gray-700 antialiased bg-mavisal-light'); ?>>
<?php wp_body_open(); ?>
<header id="navbar" class="fixed w-full top-0 z-50 transition-all duration-300 bg-mavisal-blue/90 backdrop-blur-md shadow-lg py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center">
            <div class="flex-shrink-0 flex items-center gap-2">
                <?php if (has_custom_logo()) : ?>
                    <?php the_custom_logo(); ?>
                <?php else : ?>
                    <span class="font-heading font-bold text-2xl text-white tracking-tight">MaVi<span class="text-mavisal-orange">Sal</span></span>
                <?php endif; ?>
                <span class="text-xs text-gray-300 hidden sm:block border-l border-gray-600 pl-2 ml-2">Construcciones<br>y Reformas</span>
            </div>

            <nav class="hidden md:flex space-x-8 items-center">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container'      => false,
                    'items_wrap'     => '%3$s',
                    'fallback_cb'    => false,
                ]);
                ?>
            </nav>

            <div class="hidden md:flex items-center gap-4">
                <a href="tel:+34923000000" class="text-white font-semibold flex items-center gap-2 text-sm hover:text-mavisal-orange transition">
                    923 000 000
                </a>
                <a href="#presupuesto" class="bg-mavisal-orange hover:bg-mavisal-orangeHover text-white px-5 py-2.5 rounded-md font-semibold text-sm transition shadow-lg shadow-orange-500/30">
                    <?php echo esc_html(mavisal_get_field('header_cta_texto', 'Pedir Presupuesto')); ?>
                </a>
            </div>

            <div class="md:hidden flex items-center">
                <button id="mobile-menu-button" class="text-white hover:text-mavisal-orange focus:outline-none" aria-expanded="false" aria-controls="mobile-menu">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div id="mobile-menu" class="hidden md:hidden bg-mavisal-blue border-t border-gray-800">
        <div class="px-4 pt-2 pb-6 space-y-1 shadow-xl">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container'      => false,
                'items_wrap'     => '%3$s',
                'fallback_cb'    => false,
            ]);
            ?>
            <div class="mt-4 pt-4 flex flex-col gap-3 px-3">
                <a href="tel:+34923000000" class="text-center text-white border border-gray-600 rounded-md py-3 font-medium">Llamar ahora</a>
                <a href="#presupuesto" class="text-center bg-mavisal-orange text-white rounded-md py-3 font-semibold shadow-lg">Solicitar Presupuesto</a>
            </div>
        </div>
    </div>
</header>
