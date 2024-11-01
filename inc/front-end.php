<?php 		

  echo $before_widget;
  echo $before_title . $title . $after_title;	

  // Check for login.
  if (is_user_logged_in()):

?>

<p>
  <form name="wpcheckin_places_form" method="post" action="">
    <input name="wpcheckin_places_submitted" type="hidden" value="Y"/>
    <a href="#" id="wpcheckin_get_places" class="btn-prim">Get Places!</a>
  </form>
</p>

<div id="wpcheckin_places_list">
<ul class="wpcheckin-places frontend">

  <li id="wpcheckin_loc_place">
    <a href="#" id="wplatloc" class="btn-prim wpcheckin">Lattitude: <br/><?php echo $options['wpcheckin_lat']; ?>, <br/>
    Longitude: <br/><?php echo $options['wpcheckin_lon']; ?></a>

       <form id="form_wplatloc" method="post" action="">
         <input name="action" type="hidden" value="wpcheckin_post_checkin"/>
         <input name="name" type='hidden' value="<?php echo $options['wpcheckin_lat'] ?>, <?php echo $options['wpcheckin_lon']; ?>"/>
         <input name="address" type='hidden' value="-"/>
         <input name="phone" type='hidden' value="-"/>
         <input name="website" type="hidden" value="-"/>
       </form>
  </li>

</ul>

</div>

<hr/>

<div id="wpcheckin_checkin_list">
<?php
  $args = array(
    'post_type' => 'wpcheckin',
    'posts_per_page' => 5,
  );

  $checkins = new WP_Query( $args );
  if ( $checkins->have_posts() ) {
    while ( $checkins->have_posts() ) {
      $checkins->the_post();
?>
  <h4><?php the_title() ?></h4>
  <div class='content'>
    <?php the_content() ?>
  </div>

<?php
  }
    }
  else {
    echo '<div id="wpcheckin_no">No Checkins as of yet!</div>';
  }
?>

</div>


<?php
  endif;

  echo $after_widget; 
?>
