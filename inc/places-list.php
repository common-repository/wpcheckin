  <?php if ($wpcheckin_places[0]->status != "error"): ?>
    <?php for ($i = 0; $i < count($wpcheckin_places); $i++): ?>

      <li class="wpcheckin-place">

       <a href="#" id="<?php echo $wpcheckin_places[$i]->id; ?>" class="btn-prim wpcheckin"><?php echo $wpcheckin_places[$i]->name; ?></a><br/>
       <?php echo $wpcheckin_places[$i]->formatted_address; ?><br/>
       <?php echo $wpcheckin_places[$i]->formatted_phone_number; ?><br/>
       <a href="<?php if (isset($wpcheckin_places[$i]->website)) {echo $wpcheckin_places[$i]->website; }?>">
         <?php if (isset($wpcheckin_places[$i]->website)) {echo $wpcheckin_places[$i]->website;} ?>
       </a><br/><br/>

       <form id="form_<?php echo $wpcheckin_places[$i]->id; ?>" method="post" action="">
         <input name="action" type="hidden" value="wpcheckin_post_checkin"/>
         <input name="name" type='hidden' value="<?php echo $wpcheckin_places[$i]->name; ?>"/>
         <input name="address" type='hidden' value="<?php echo $wpcheckin_places[$i]->formatted_address; ?>"/>
         <input name="phone" type='hidden' value="<?php echo $wpcheckin_places[$i]->formatted_phone_number; ?>"/>

         <?php if (isset($wpcheckin_places[$i]->website)): ?>
           <input name="website" type="hidden" value="<?php echo $wpcheckin_places[$i]->website; ?>"/>
         <?php endif; ?>
       </form>
      </li>

    <?php endfor; ?>

  <?php endif; ?>
