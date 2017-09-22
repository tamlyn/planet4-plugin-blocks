<?php if ( $data ) : ?>


	<!-- The following block will be removed once we have the master css in place -->
	<style>
		/** STYLES */
		.eager-wrap-mobile {
		  background: #b0d4c8;
		  padding: 20px 0;
		  border-top: solid 1px #c7dcd3;
		  .mobile-image {
			width: 100%;
			object-fit: cover;
		  }
		  h3 {
			color: #004d51;
			font-weight: 700;
			margin: 15px 0 0 0;
			padding: 5px 0;
			line-height: normal;
		  }
		  h4 {
			color: #12757a;
			font-weight: 400;
			font-size: 21px;
			margin-bottom: 10px;
			padding: 5px 0;
		  }
		  .btn-primary {
			width: 100%;
		  }
		}

		// Mixins because extending is not possible inside media queries
		@mixin happy-background-image {
		  background: url("images/eager-bg.jpg") center center no-repeat;
		  background-size: cover;
		  height: 350px;
		  width: 100%;
		  position: relative;
		  &:before {
			content: " ";
			background: $blue-bg;
			position: absolute;
			left: 0;
			top: 0;
			height: 350px;
			width: 100%;
			opacity: 0.3;
		  }
		}

		@mixin background-opacity {
		  &:before {
			content: " ";
			background: $blue-bg;
			position: absolute;
			left: 0;
			top: 0;
			height: 100%;
			width: 100%;
			opacity: 0.3;
		  }
		}


		@media (max-width: 575px) {
		  .eager-wrap {
			.eager-for-more {
			  &:before {
				border: none;
			  }
			}
		  }
		}

		@media (min-width: 768px) {
		  .eager-wrap {
			.eager-for-more {
			  &:before {
				border: none;
			  }
			}
		  }
		}

		@media (min-width: 992px) {
		  .eager-wrap {
			@include happy-background-image;
			.eager-for-more {
			  @include background-opacity;
			  position: absolute;
			  right: 0;
			  top: 135px;
			  background: $container-bg;
			  opacity: 0.6;
			  padding: 50px 30px;
			  width: 350px;
			  text-align: center;
			  h2 {
				color: #004d51;
				font-weight: 700;
			  }
			  h5 {
				color: #12757a;
				font-weight: 400;
				font-size: 21px;
			  }
			  .btn-primary {
				width: 200px;
			  }
			}
		  }
		}

		@media (min-width: 1200px) {
		  .eager-wrap {
			@include happy-background-image;
			.eager-for-more {
			  @include background-opacity;
			  position: absolute;
			  right: 0;
			  top: 135px;
			  background: $container-bg;
			  opacity: 0.6;
			  padding: 50px 30px;
			  width: 350px;
			  text-align: center;
			  h2 {
				color: #004d51;
				font-weight: 700;
			  }
			  h5 {
				color: #12757a;
				font-weight: 400;
				font-size: 21px;
			  }
			  .btn-primary {
				width: 200px;
			  }
			}
		  }
		}
	</style>
	<!-- The above block will be removed once we have the master css in place -->
	<div class="eager-wrap">
		<div class="container">
			<div class="eager-for-more hidden-md-down">
				<h2><?php echo $data['boxout_title']; ?></h2>
				<h5><?php echo $data['boxout_descr']; ?></h5>
				<a href="<?php echo $data['boxout_link_url']; ?>" class="btn btn-primary"><?php echo $data['boxout_link_text']; ?></a>
			</div>
		</div>
		<div class="eager-wrap-mobile hidden-lg-up">
			<div class="container">
				<?php echo $data['background']; ?>
				<h3><?php echo $data['boxout_title']; ?></h3>
				<h4><?php echo $data['boxout_descr']; ?></h4>
				<a href="<?php echo $data['boxout_link_url']; ?>" class="btn btn-primary"><?php echo $data['boxout_link_text']; ?></a>
			</div>
		</div>
	</div>

<?php endif; ?>
<div class="clear"></div>

