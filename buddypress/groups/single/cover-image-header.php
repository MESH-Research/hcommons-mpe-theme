<?php
/**
 * BuddyPress - Groups Cover Image Header
 *
 * @package HCommons
 */
?>

<?php do_action( 'bp_before_group_header' ); ?>

<div id="cover-image-container">
	<?php
	$cover_image_url = bp_attachments_get_attachment( 'url', array(
		'object_dir' => 'groups',
		'item_id'    => bp_get_current_group_id(),
	) );
	$cover_style = '';
	if ( ! empty( $cover_image_url ) ) {
		$cover_style = ' style="background-image: url(' . esc_url( $cover_image_url ) . '); background-size: cover; background-position: center;"';
	}
	?>
	<div id="header-cover-image"<?php echo $cover_style; ?>></div>

	<div id="item-header-cover-image">
		<div id="item-header-avatar group-avatar">
			<a href="<?php bp_group_permalink(); ?>" title="<?php bp_group_name(); ?>">
				<?php bp_group_avatar( 'type=full' ); ?>
			</a>
		</div><!-- #item-header-avatar -->

		<div id="item-header-content">
			<div id="item-meta">
				<?php
				// Get group status
				global $groups_template;
				$group = & $groups_template->group;
				if ( 'public' == $group->status ) {
					$status_type = __( 'Public Group', 'flavor' );
					$status_class = 'public';
				} else if ( 'hidden' == $group->status ) {
					$status_type = __( 'Hidden Group', 'flavor' );
					$status_class = 'hidden';
				} else if ( 'private' == $group->status ) {
					$status_type = __( 'Private Group', 'flavor' );
					$status_class = 'private';
				} else {
					$status_type = ucwords( $group->status ) . ' ' . __( 'Group', 'flavor' );
					$status_class = 'other';
				}
				?>

				<span class="highlight <?php echo esc_attr( $status_class ); ?>"><?php echo esc_html( $status_type ); ?></span>
				<span class="activity"><?php printf( esc_html__( 'active %s', 'flavor' ), bp_get_group_last_active() ); ?></span>

				<?php if ( bp_group_is_visible() ) : ?>
					<p class="group-description"><?php bp_group_description_excerpt(); ?></p>
				<?php endif; ?>

				<?php do_action( 'bp_before_group_header_meta' ); ?>

				<?php if ( bp_group_is_visible() ) : ?>
                    <div id="item-actions">
                        <h3><?php esc_html_e( 'Group Admins', 'flavor' ); ?></h3>
                        <?php bp_group_list_admins(); ?>
                        <?php do_action( 'bp_after_group_menu_admins' ); ?>

                        <?php if ( bp_group_has_moderators() ) : ?>
                            <?php do_action( 'bp_before_group_menu_mods' ); ?>
                            <h3><?php esc_html_e( 'Group Mods', 'flavor' ); ?></h3>
                            <?php bp_group_list_mods(); ?>
                            <?php do_action( 'bp_after_group_menu_mods' ); ?>
                        <?php endif; ?>
                    </div><!-- #item-actions -->
                <?php endif; ?>

                <div id="item-buttons">
                    <?php do_action( 'bp_group_header_actions' ); ?>
                </div><!-- #item-buttons -->

			</div><!-- #item-meta -->





		</div><!-- #item-header-content -->
	</div><!-- #item-header-cover-image -->
</div><!-- #cover-image-container -->

<?php
do_action( 'bp_after_group_header' );
do_action( 'template_notices' );
