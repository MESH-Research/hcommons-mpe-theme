<div id="buddypress" >

	<?php do_action( 'bp_before_member_home_content' ); ?>

	<div id="item-header" role="complementary">

		<?php bp_get_template_part( 'members/single/member-header' ) ?>

	</div><!-- #item-header -->

    <div class="full-width">
        <div id="item-main-content">
            <div id="item-nav">
                <div class="item-list-tabs no-ajax" id="object-nav" role="navigation">
                    <ul id="nav-bar-filter">

                        <?php bp_get_displayed_user_nav(); ?>

                        <?php do_action( 'bp_member_options_nav' ); ?>

                    </ul>
                </div>
            </div><!-- #item-nav -->

            <?php if(bp_current_component() != 'shop')
                echo '<div id="item-body" role="main">';
            ?>

            <?php do_action( 'bp_member_header_actions' ); ?>

                <?php
                do_action( 'bp_before_member_body' );

                // Handle plugin components first to avoid compatibility issues
                if ( bp_current_component() === 'events' ) :
                    bp_get_template_part( 'members/single/plugins' );

                elseif ( bp_current_component() === 'forums' ) :
                    bp_get_template_part( 'members/single/plugins' );

                elseif ( bp_is_user_activity() || !bp_current_component() ) :
                    bp_get_template_part( 'members/single/activity' );

                elseif ( bp_is_user_blogs() ) :
                    bp_get_template_part( 'members/single/blogs' );

                elseif ( bp_is_user_friends() ) :
                    bp_get_template_part( 'members/single/friends' );

                elseif ( bp_is_user_groups() ) :
                    bp_get_template_part( 'members/single/groups' );

                elseif ( bp_is_user_messages() ) :
                    bp_get_template_part( 'members/single/messages' );

                elseif ( bp_is_user_profile() ) :
                    bp_get_template_part( 'members/single/profile' );

                elseif ( bp_is_user_notifications() ) :
                    bp_get_template_part( 'members/single/notifications' );

                elseif ( bp_is_user_settings() ) :
                    bp_get_template_part( 'members/single/settings' );

                // If nothing sticks, load a generic template
                else :
                    bp_get_template_part( 'members/single/plugins' );

                endif;

                do_action( 'bp_after_member_body' );
                ?>

            <?php if(bp_current_component() != 'shop')
                echo '</div><!-- #item-body -->';
            ?>

            <?php do_action( 'bp_after_member_home_content' ); ?>

        </div>
        <!-- /.item-main-content -->
        <?php
        // Sidebar disabled - was BuddyBoss-specific
        ?>
    </div>

</div><!-- #buddypress -->
