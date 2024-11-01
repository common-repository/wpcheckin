<div class="wrap">
	
	<div id="icon-options-general" class="icon32"></div>
	<h2>WordPress Checkin Plugin... It's Geo!</h2>
	
	<div id="poststuff">
	
		<div id="post-body" class="metabox-holder columns-2">
		
			<!-- main content -->
			<div id="post-body-content">
				
				<div class="meta-box-sortables ui-sortable">
					
					<div class="postbox">
					
						<h3><span>Google API Key</span></h3>
						<div class="inside">

                                                  <form name="wpcheckin_config_form" method="post" action="">
                                                    <input name="wpcheckin_form_submitted" type="hidden" value="Y"/>

                                                    <table class="form-table">
                                                    	<tr>
                                                    	  <td><label for="wpcheckin_key">Google API Key</label></td>
                                                    	  <td>
                                                              <input name="wpcheckin_key" id="wpcheckin_key" type="text" value="" 
                                                                     class="regular-text" />
                                                            </td>
                                                    	</tr>
                                                    </table>

                                                    <p>
                                                      <input class="button-primary" type="submit" name="wpcheckin_key_submit" 
                                                             value="Save" /> 
                                                    </p>

                                                  </form>
						</div> <!-- .inside -->
					</div> <!-- .postbox -->

				        <?//php else: ?>	

					<div class="postbox">
					
						<h3><span>Google API Key:</span></h3>
						<div class="inside">
                                                  <?php echo $wpcheckin_key ?>

						</div> <!-- .inside -->
					
					</div> <!-- .postbox -->

					<div class="postbox">
						<h3><span>Places Json</span></h3>
						<div class="inside">

                                                <form name="wpcheckin_places_form" method="post" action="">
                                                  <input name="wpcheckin_places_submitted" type="hidden" value="Y"/>
                                                  <input class="button-primary" type="submit" name="wpcheckin_places_submit" 
                                                         value="Get Places!" /> 
                                                </form>

                                                <br/>
                                    <?php if (isset($wpcheckin) and $wpcheckin_places == "No Details..."): ?>
                                      <p>No Details for location...</p>
                                    <?php elseif (isset($wpcheckin) and $wpcheckin_places[0]->status != "error"): ?>
                                      <?php for ($i = 0; $i < count($wpcheckin_places); $i++): ?>
                                        <strong><?php echo $wpcheckin_places[$i]->name; ?> </strong><br/>
                                        <?php echo $wpcheckin_places[$i]->formatted_address; ?><br/>
                                        <?php echo $wpcheckin_places[$i]->formatted_phone_number; ?><br/>
                                        <a href="<?php if (isset($wpcheckin_places[$i]->website)) {echo $wpcheckin_places[$i]->website; }?>">
                                          <?php if (isset($wpcheckin_places[$i]->website)) {echo $wpcheckin_places[$i]->website;} ?>
                                        </a><br/><br/>
                                        <?php endfor; ?>
                                    <?php endif; ?>

                                    <?php if (isset($options['wpcheckin_lat']) and isset($options['wpcheckin_lon'])): ?>
                                      <strong>Lattitude:</strong> <?php echo $options['wpcheckin_lat']; ?>, 
                                      <strong>Longitude:</strong> <?php echo $options['wpcheckin_lon']; ?>
                                      <br/><br/>
                                    <?php endif; ?>

                                                <?php if ($display_json == true and isset($wpcheckin_places)): ?>
                                                  <pre><code>
                                                    <?php var_dump($wpcheckin_places); ?>
                                                  </pre></code>
                                                <?php endif; ?>
                   
 						</div>
			        	</div>

                                        <?//php endif; ?>
					
				</div> <!-- .meta-box-sortables .ui-sortable -->
				
			</div> <!-- post-body-content -->

                        <form name="wpcheckin_config_form" method="post" action="">
                          <input name="wpcheckin_loc_submitted" type="hidden" value="Y"/>
                          <input name="wpcheckin_lat" type="hidden" value="Y"/>
                          <input name="wpcheckin_lon" type="hidden" value="Y"/>
                        </form>

		<br class="clear">
	</div> <!-- #poststuff -->
	
</div> <!-- .wrap -->
