<?php
/**
 * Order meta display template
 *
 * Displays party bag details in order view (admin and customer).
 *
 * @package PartyBagBuilder
 * @var array $party_bag_data Party bag configuration data.
 */

defined( 'ABSPATH' ) || exit;

if ( empty( $party_bag_data ) ) {
	return;
}
?>

<div class="pbb-order-meta">
	<h4><?php esc_html_e( 'Party Bag Details:', 'party-bag-builder' ); ?></h4>

	<!-- Summary -->
	<div class="pbb-section">
		<div class="pbb-badges">
			<span class="pbb-badge"><?php echo esc_html( ucfirst( $party_bag_data['tier'] ?? '' ) ); ?></span>
			<span class="pbb-badge">
				<?php
				echo esc_html(
					sprintf(
						/* translators: %d: number of kids */
						_n( '%d kid', '%d kids', absint( $party_bag_data['kid_count'] ), 'party-bag-builder' ),
						absint( $party_bag_data['kid_count'] )
					)
				);
				?>
			</span>
		</div>
	</div>

	<!-- Items Section -->
	<?php if ( ! empty( $party_bag_data['common_items'] ) || ! empty( $party_bag_data['selected_toys'] ) || ! empty( $party_bag_data['selected_addons'] ) ) : ?>
		<div class="pbb-section">
			<?php if ( ! empty( $party_bag_data['common_items'] ) ) : ?>
				<div class="pbb-section-item">
					<h4><?php esc_html_e( 'Common Items:', 'party-bag-builder' ); ?></h4>
					<div class="pbb-badges">
						<?php foreach ( $party_bag_data['common_items'] as $item ) : ?>
							<span class="pbb-badge"><?php echo esc_html( $item['name'] ); ?></span>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $party_bag_data['selected_toys'] ) ) : ?>
				<div class="pbb-section-item">
					<h4><?php esc_html_e( 'Toys:', 'party-bag-builder' ); ?></h4>
					<div class="pbb-badges">
						<?php foreach ( $party_bag_data['selected_toys'] as $item ) : ?>
							<span class="pbb-badge"><?php echo esc_html( $item['name'] ); ?></span>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $party_bag_data['selected_addons'] ) ) : ?>
				<div class="pbb-section-item">
					<h4><?php esc_html_e( 'Add-ons:', 'party-bag-builder' ); ?></h4>
					<div class="pbb-badges">
						<?php
						$free_addons = $party_bag_data['price_breakdown']['free_addons'] ?? array();
						foreach ( $party_bag_data['selected_addons'] as $item ) :
							$addon_id = absint( $item['id'] );
							$is_free  = in_array( $addon_id, $free_addons, true );
							?>
							<span class="pbb-badge <?php echo $is_free ? 'pbb-badge-free' : ''; ?>">
								<?php echo esc_html( $item['name'] ); ?>
								<?php if ( $is_free ) : ?>
									<span class="pbb-free-label"><?php esc_html_e( 'FREE', 'party-bag-builder' ); ?></span>
								<?php endif; ?>
							</span>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	<?php endif; ?>

	<!-- Personalization Section -->
	<?php if ( ! empty( $party_bag_data['kid_names'] ) ) : ?>
		<div class="pbb-section">
			<div class="pbb-section-item">
				<h4><?php esc_html_e( 'Names:', 'party-bag-builder' ); ?></h4>
				<div class="pbb-badges">
					<?php foreach ( $party_bag_data['kid_names'] as $name ) : ?>
						<span class="pbb-badge"><?php echo esc_html( $name ); ?></span>
					<?php endforeach; ?>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>

<style>
.pbb-order-meta {
	margin: 1em 0;
}

.pbb-order-meta h4 {
	margin-top: 0;
	margin-bottom: 0.5em;
}

/* Section Containers */
.pbb-section {
	margin-bottom: 1em;
}

.pbb-section-item {
	margin-bottom: 0.75em;
}

.pbb-section-item:last-child {
	margin-bottom: 0;
}

/* Badge System */
.pbb-badges {
	display: flex;
	flex-wrap: wrap;
	gap: 0.4em;
}

.pbb-badge {
	display: inline-block;
	padding: 0.2em 0.6em;
	border: 1px solid #c3c4c7;
	border-radius: 3px;
	font-size: 1rem;
}

.pbb-badge-free {
	background: #edfaed;
	border-color: #68de7c;
}

.pbb-free-label {
	display: inline-block;
	margin-left: 0.4em;
	padding: 0.1em 0.4em;
	background: #46b450;
	color: #fff;
	font-size: 0.8em;
	font-weight: 600;
	border-radius: 2px;
}
</style>
