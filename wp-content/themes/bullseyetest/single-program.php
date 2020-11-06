<?php
get_header();

  while(have_posts()) {
    the_post();
    pageBanner();
    ?>

    <div class="container container--narrow page-section">
      <div class="metabox metabox--position-up metabox--with-home-link">
        <p>
          <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program'); ?>">
            <i class="fa fa-home" aria-hidden="true"></i>
              All Programs
          </a>
          <span class="metabox__main">
            <?php the_title() ?>
          </span>
        </p>
      </div>
      <div class="generic-conent">
        <?php the_field('main_body_content'); ?>
      </div>
    <?php

    $programProfessors = new WP_Query(array(
      'posts_per_page' => -1,
      'post_type' => 'professor',
      'orderby' => 'title',
      'order' => 'ASC',
      'meta_query' => array(
        array(
          'key' => 'related_programs',
          'compare' => 'LIKE',
          'value' => '"'.get_the_ID().'"'
        )
      )
    ));
  //  print_r($programProfessors);
    if ($programProfessors->have_posts()) {
      echo '<hr class="section-break">';
      echo '<h2 class="headline headline--medium">'.get_the_title().' Professors</h2>';
      echo '<ul class="professor-cards">';
      while($programProfessors->have_posts()) {
        //    print_r($homepageEvents);
        $programProfessors->the_post();
        //the_title();
        ?>
        <li class="professor-card__list-item">
          <a class="professor-card" href="<?php the_permalink(); ?>">
            <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); ?>">
            <span class="professor-card__name"><?php the_title(); ?></span>
          </a>
        </li>
        <?php
      }
      echo '</ul>';
    } wp_reset_postdata();

    $today = date('Ymd');
    $homepageEvents = new WP_Query(array(
      'posts_per_page' => -1,
      'post_type' => 'event',
      'orderby' => 'meta_value_num',
      'meta_key' => 'event_date',
      'order' => 'ASC',
      'meta_query' => array(
        array(
          'key' => 'event_date',
          'compare' => '>=',
          'value' => $today,
          'type' => 'numeric'
        ),
        array(
          'key' => 'related_programs',
          'compare' => 'LIKE',
          'value' => '"'.get_the_ID().'"'
        )
      )
    ));
    if ($homepageEvents->have_posts()) {
      echo '<hr class="section-break">';
      echo '<h2 class="headline headline--medium">Upcoming '.get_the_title().' Events</h2>';
      while($homepageEvents->have_posts()) {
        //    print_r($homepageEvents);
        $homepageEvents->the_post();
        //the_title();
        get_template_part('template-parts/content', 'event');

      }
  } wp_reset_postdata();?>
    <hr class="section-break">
    <h2 class="headline headline--medium">Campus(es) Where <?php the_title(); ?> is Taught</h2>
    <?php
      $relatedCampuses = get_field('related_campuses');
      if($relatedCampuses) {
        echo '<ul class="min-list link-list">';
        foreach($relatedCampuses as $campus) { ?>
          <li>
            <a href="<?php echo get_the_permalink($campus) ?>"><?php echo get_the_title($campus) ?></a>
          </li>
        <?php }
        echo '</ul>';
      }
      ?>
    </div>
<?php  }

  get_footer();

?>
