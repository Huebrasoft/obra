<footer id="contacto" class="bg-mavisal-dark text-white pt-20 pb-10 border-t-4 border-mavisal-orange">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
            <div>
                <span class="font-heading font-bold text-3xl tracking-tight mb-6 block">MaVi<span class="text-mavisal-orange">Sal</span></span>
                <p class="text-gray-400 text-sm leading-relaxed mb-6">Más de 40 años construyendo confianza en Salamanca y provincia. Obras, reformas y rehabilitaciones con la máxima garantía.</p>
            </div>
            <div>
                <h4 class="font-heading font-bold text-lg mb-6 border-b border-gray-800 pb-2">Servicios</h4>
                <ul class="space-y-3 text-sm text-gray-400">
                    <?php
                    $servicios = get_posts(['post_type' => 'servicios', 'numberposts' => 6]);
                    foreach ($servicios as $servicio) {
                        echo '<li><a href="' . esc_url(get_permalink($servicio)) . '" class="hover:text-mavisal-orange transition">' . esc_html(get_the_title($servicio)) . '</a></li>';
                    }
                    ?>
                </ul>
            </div>
            <div>
                <h4 class="font-heading font-bold text-lg mb-6 border-b border-gray-800 pb-2">Contacto</h4>
                <ul class="space-y-4 text-sm text-gray-400">
                    <li><span>Calle Ejemplo 123, 37000 Salamanca</span></li>
                    <li><a href="mailto:info@mavisal.es" class="hover:text-white transition">info@mavisal.es</a></li>
                    <li><a href="tel:923000000" class="hover:text-white font-bold transition">923 000 000</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-heading font-bold text-lg mb-6 border-b border-gray-800 pb-2">Síguenos</h4>
                <p class="text-sm text-gray-400">Redes sociales disponibles próximamente.</p>
            </div>
        </div>
        <div class="pt-8 border-t border-gray-800 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-gray-500">
            <p>&copy; <?php echo esc_html(date_i18n('Y')); ?> Construcciones MaViSal. Todos los derechos reservados.</p>
        </div>
    </div>
</footer>
<script type="application/ld+json">
<?php echo wp_json_encode([
    '@context' => 'https://schema.org',
    '@type' => 'LocalBusiness',
    'name' => get_bloginfo('name'),
    'telephone' => '+34 923 000 000',
    'address' => [
        '@type' => 'PostalAddress',
        'streetAddress' => 'Calle Ejemplo 123',
        'addressLocality' => 'Salamanca',
        'postalCode' => '37000',
        'addressCountry' => 'ES',
    ],
]); ?>
</script>
<?php wp_footer(); ?>
</body>
</html>
