<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since gamejamua 1.0
 */

get_header(); ?>

<?php while ( have_posts() ) : the_post(); ?>
<div class="clear-form long-text blog-entry section">
    <div class="form-heading gray blog-title">
        <div style="float: right; margin-top: 13px">
            <div class="addthis_toolbox addthis_default_style addthis_32x32_style">
                <a class="addthis_button_preferred_1"></a>
                <a class="addthis_button_preferred_2"></a>
                <a class="addthis_button_preferred_3"></a>
                <a class="addthis_button_compact"></a>
                <a class="addthis_counter addthis_bubble_style"></a>
            </div>
            <script type="text/javascript">var addthis_config = {"data_track_addressbar":true, ui_language: "es", title: '<?php the_title(); ?>'};</script>
            <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-52690e1d3cf691a8"></script>
        </div>
        <h1><?php the_title(); ?></h1>
    </div>
    <div class="form-body comercios-form markdown blog-content">
        <?php the_content() ?>
    </div>
    <div class="form-footer blog-footer">
        <div id="disqus_thread" style="margin-top: 100px"></div>
        <script type="text/javascript">
            (function() {
                var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                dsq.src = '//gamejamalicante.disqus.com/embed.js';
                (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
            })();
        </script>
    </div>
</div>

<?php endwhile; // end of the loop. ?>
<?php get_footer(); ?>