<?php if ( $data ) : ?>

    <div id="p4bks_tasks_container" class="hidden-md-down row can-do-steps shortcode-tasks">

		<?php for ( $i = 1; $i < 5; $i++ ) { ?>

            <div class="col-md-3 col-lg-3 col-xl-3 <?php echo $i == 1 ? 'active' : ''; ?>">
                <div class="step-info-wrap clearfix">
                    <span class="step-number"><?php echo $i; ?></span>
                    <div class="step-info">
                        <div class="steps-information">

							<?php if ( $data['fields']["title_$i"] ) : ?>
                                <h5><?php echo $data['fields']["title_$i"] ?></h5>
							<?php endif; ?>

							<?php if ( $data['fields']["description_$i"] ) : ?>
                                <p><?php echo $data['fields']["description_$i"] ?></p>
							<?php endif; ?>

							<?php if ( $data['fields']["attachment_$i"] ) : ?>
                                <div class="steps-action">
                                    <img src="<?php echo $data['fields']["attachment_$i"] ?>" alt=""/>
                                </div>
							<?php endif; ?>

							<?php if ( $data['fields']["button_text_$i"] && $data['fields']["button_link_$i"] ) : ?>
                                <a class="btn"
                                   href="<?php echo $data['fields']["button_link_$i"] ?>">
									<?php echo $data['fields']["button_text_$i"] ?>
                                </a>
							<?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
		<?php } ?>
    </div>
<?php endif; ?>
<div class="clear"></div>