<?php get_header(); ?>

	<div id="primary" class="site-content section info">
		<div id="content" role="main">
		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>
                <span class="date pull-right"><?php the_date(); ?> - <a href="<?php the_permalink() ?>#disqus_thread">Comentarios</a></span>
                <h2><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h2>
                <div class="long-text blog-content">
                    <?php the_post_thumbnail( 140 , array("class" => "blog-featured-img")); ?> <?php the_excerpt(); ?>
                </div>
			<?php endwhile; ?>
		<?php else : ?>

			No hay entradas :(

		<?php endif; // end have_posts() check ?>

		</div><!-- #content -->
	</div><!-- #primary -->
<?php get_footer(); ?>