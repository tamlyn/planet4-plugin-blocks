<?php if ( $data ) : ?>
<section id="p4bks_two_columns_container" class="shortcode-two-columns">
    <p>
	    <?php if ( $data['fields']['title_1'] ) : ?>
        <h2><?php echo $data['fields']['title_1'] ?></h2>
	    <?php endif; ?>

        <?php if ( $data['fields']['description_1'] ) : ?>
        <?php echo $data['fields']['description_1'] ?>
        <?php endif; ?>

        <br />
	    <?php if ( $data['fields']['button_text_1'] && $data['fields']['button_text_1'] ) : ?>
        <a class="btn" href="<?php echo $data['fields']['button_link_1'] ?>"><?php echo $data['fields']['button_text_1'] ?></a>
	    <?php endif; ?>
    </p>
    <p>
		<?php if ( $data['fields']['title_2'] ) : ?>
        <h2><?php echo $data['fields']['title_2'] ?></h2>
        <?php endif; ?>

	    <?php if ( $data['fields']['description_2'] ) : ?>
        <?php echo $data['fields']['description_2'] ?>
	    <?php endif; ?>

        <br />
	    <?php if ( $data['fields']['button_text_2'] && $data['fields']['button_text_2'] ) : ?>
        <a class="btn" href="<?php echo $data['fields']['button_link_2'] ?>"><?php echo $data['fields']['button_text_2'] ?></a>
	    <?php endif; ?>
    </p>
</section>
<?php endif; ?>
<div class="clear"></div>

