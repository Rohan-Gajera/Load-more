<?php
/**
 * Template name: Ratgeber
 * 
 * @package Rocket Homepage
 */
get_header();
if( have_posts() ) : while( have_posts() ) : the_post(); ?>

<section class="home-banner home-bg">
    <div class="home-banner-img bg-img" style="background-image: url(<?php the_field( 'banner_background_image' ) ?>)">
        <div class="container">
            <div class="banner-content">
                <div class="banner-grid-item">
                    <h1 class="banner-title text-light-blue bg-title"><?php the_field( 'banner_title' ) ?></h1>
                    <div class="banner-desc"><?php the_field( 'banner_description' ) ?></div>
                    <div class="banner-btn-wrap">
                        <?php 
                        $link = get_field('banner_button');
                        if( $link ): 
                            $link_url = $link['url'];
                            $link_title = $link['title'];
                            $link_target = $link['target'] ? $link['target'] : '_self';
                            ?>
                            <a class="button" href="<?php echo esc_url( $link_url ); ?>"><?php echo esc_html( $link_title ); ?></a>
                        <?php endif; ?>
                        <p><?php the_field( 'button_short_text' ) ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="section-padding">
    <div class="container">
        <div>
            <div></div>
            <div class="blog-wrap" id="post-container">
                <?php 
                 $args = array(
                    'posts_per_page' => 6,
                    'orderby' => 'date',    
                    'order'   => 'DESC',
                    'post_type'  => 'post',  
                    'post_status' => 'publish'
                );
                $post_list = new WP_Query( $args );
                $found_posts = $post_list->found_posts; 
                $max_num_pages = $post_list->max_num_pages;
                if ( $post_list->have_posts() ) {
                while ( $post_list->have_posts() ) { $post_list->the_post(); 
                ?>
                <div class="blog-wrap-box">
                    <div class="blog-wrap-img">
                        <?php echo get_the_post_thumbnail( get_the_ID(), 'large' ); ?>
                    </div>
                    <div class="blog-wrap-text">
                        <div class="blog-t">
                            <div class="blog-text-d">
                                <p>By <?php the_author(); ?></p>
                                <svg xmlns="http://www.w3.org/2000/svg" width="6" height="7" viewBox="0 0 6 7" fill="none">
                                  <circle cx="3" cy="3.95789" r="3" fill="#00ADEF"/>
                                </svg>
                            <p><span><?php echo get_the_date('M j, Y'); ?></span></p>
                            </div>
                            <h6><?php the_title(); ?></h6>
                            <p><?php echo get_the_excerpt(); ?></p>
                        </div>
                        <a class="wrap-b-button-main" href="<?php the_permalink(); ?>">mehr Laden</a>
                    </div>
                </div> 
                <?php } } ?>
            </div>

            <button class="btn wrap-b-button" id="load-btn" data-max_num_pages="<?php echo $max_num_pages; ?>" data-found_post="<?php echo $found_posts; ?>">mehr Laden</button>
        </div>
    </div>
</section>

<script type="text/javascript">
    const newsLoadMore = jQuery("#load-btn");
    const newsmaxPage = parseInt(newsLoadMore.data("max_num_pages"));
    let currentPage = 1;
    newsLoadMore.click(function (e) {

        e.preventDefault();
        currentPage++;
        jQuery.ajax({
            method: "POST",
            url: themeObject.ajaxUrl,
            data: {
                action: "load_more_posts",
                page: currentPage
            },
            beforeSend: () => {
                newsLoadMore.prop("disabled", true);
            },
            success: (response) => {
                if (response) {
                    jQuery("#post-container").append(response);
                }

                if (currentPage >= newsmaxPage) {
                    newsLoadMore.hide();
                }
            },
            error: () => { },
            complete: () => {
                newsLoadMore.prop("disabled", false);
            }
        });
    });
</script>   

<?php endwhile; endif;
get_footer();