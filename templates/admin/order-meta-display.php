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
	<h4><?php esc_html_e( 'Party Bag Details', 'party-bag-builder' ); ?></h4>

	<table class="pbb-order-details">
		<tbody>
			<tr>
				<th><?php esc_html_e( 'Tier:', 'party-bag-builder' ); ?></th>
				<td><?php echo esc_html( ucfirst( $party_bag_data['tier'] ?? '' ) ); ?></td>
			</tr>

			<tr>
				<th><?php esc_html_e( 'Number of Kids:', 'party-bag-builder' ); ?></th>
				<td><?php echo absint( $party_bag_data['kid_count'] ); ?></td>
			</tr>

			<?php if ( ! empty( $party_bag_data['common_items'] ) ) : ?>
				<tr>
					<th><?php esc_html_e( 'Common Items:', 'party-bag-builder' ); ?></th>
					<td>
						<ul class="pbb-item-list">
							<?php foreach ( $party_bag_data['common_items'] as $item ) : ?>
								<li>
									<?php echo esc_html( $item['name'] ); ?>
									<?php
									/* translators: %d: quantity */
									echo esc_html( sprintf( __( '(× %d)', 'party-bag-builder' ), absint( $item['qty'] ) ) );
									?>
								</li>
							<?php endforeach; ?>
						</ul>
					</td>
				</tr>
			<?php endif; ?>

			<?php if ( ! empty( $party_bag_data['selected_toys'] ) ) : ?>
				<tr>
					<th><?php esc_html_e( 'Selected Toys:', 'party-bag-builder' ); ?></th>
					<td>
						<ul class="pbb-item-list">
							<?php foreach ( $party_bag_data['selected_toys'] as $item ) : ?>
								<li>
									<?php echo esc_html( $item['name'] ); ?>
									<?php
									/* translators: %d: quantity */
									echo esc_html( sprintf( __( '(× %d)', 'party-bag-builder' ), absint( $item['qty'] ) ) );
									?>
								</li>
							<?php endforeach; ?>
						</ul>
					</td>
				</tr>
			<?php endif; ?>

			<?php if ( ! empty( $party_bag_data['selected_addons'] ) ) : ?>
				<tr>
					<th><?php esc_html_e( 'Add-ons:', 'party-bag-builder' ); ?></th>
					<td>
						<ul class="pbb-item-list">
							<?php
							$free_addons = $party_bag_data['price_breakdown']['free_addons'] ?? array();
							foreach ( $party_bag_data['selected_addons'] as $item ) :
								$addon_id   = absint( $item['id'] );
								$is_free    = in_array( $addon_id, $free_addons, true );
								?>
								<li>
									<?php echo esc_html( $item['name'] ); ?>
									<?php
									/* translators: %d: quantity */
									echo esc_html( sprintf( __( '(× %d)', 'party-bag-builder' ), absint( $item['qty'] ) ) );
									?>
									<?php if ( $is_free ) : ?>
										<span class="pbb-free-badge"><?php esc_html_e( 'FREE', 'party-bag-builder' ); ?></span>
									<?php endif; ?>
								</li>
							<?php endforeach; ?>
						</ul>
					</td>
				</tr>
			<?php endif; ?>

			<?php if ( ! empty( $party_bag_data['name_tag_style'] ) ) : ?>
				<tr>
					<th><?php esc_html_e( 'Tag Style:', 'party-bag-builder' ); ?></th>
					<td>
						<?php
						$style = $party_bag_data['name_tag_style'];
						echo esc_html( $style['name'] ?? '' );
						?>
						<?php if ( ! empty( $style['tag_color'] ) && ! empty( $style['base_color'] ) ) : ?>
							<div class="pbb-color-preview">
								<span class="pbb-color-swatch" style="background-color: <?php echo esc_attr( $style['tag_color'] ); ?>;"></span>
								<span class="pbb-color-swatch" style="background-color: <?php echo esc_attr( $style['base_color'] ); ?>;"></span>
							</div>
						<?php endif; ?>
					</td>
				</tr>
			<?php endif; ?>

			<?php if ( ! empty( $party_bag_data['kid_names'] ) ) : ?>
				<tr>
					<th><?php esc_html_e( 'Names:', 'party-bag-builder' ); ?></th>
					<td>
						<ol class="pbb-names-list">
							<?php foreach ( $party_bag_data['kid_names'] as $name ) : ?>
								<li><?php echo esc_html( $name ); ?></li>
							<?php endforeach; ?>
						</ol>
					</td>
				</tr>
			<?php endif; ?>

			<?php if ( ! empty( $party_bag_data['price_breakdown'] ) ) : ?>
				<tr>
					<th><?php esc_html_e( 'Price Breakdown:', 'party-bag-builder' ); ?></th>
					<td>
						<table class="pbb-price-breakdown">
							<tr>
								<td>
									<?php
									/* translators: 1: tier name, 2: kid count */
									echo esc_html(
										sprintf(
											__( '%1$s tier × %2$d kids:', 'party-bag-builder' ),
											ucfirst( $party_bag_data['tier'] ?? '' ),
											absint( $party_bag_data['kid_count'] )
										)
									);
									?>
								</td>
								<td class="pbb-price-amount">
									<?php echo wp_kses_post( wc_price( $party_bag_data['price_breakdown']['base'] ) ); ?>
								</td>
							</tr>
							<?php if ( $party_bag_data['price_breakdown']['addons'] > 0 ) : ?>
								<tr>
									<td><?php esc_html_e( 'Paid add-ons:', 'party-bag-builder' ); ?></td>
									<td class="pbb-price-amount">
										<?php echo wp_kses_post( wc_price( $party_bag_data['price_breakdown']['addons'] ) ); ?>
									</td>
								</tr>
							<?php endif; ?>
							<tr class="pbb-total-row">
								<td><strong><?php esc_html_e( 'Total:', 'party-bag-builder' ); ?></strong></td>
								<td class="pbb-price-amount">
									<strong><?php echo wp_kses_post( wc_price( $party_bag_data['price_breakdown']['total'] ) ); ?></strong>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>

<style>
.pbb-order-meta {
	margin: 1em 0;
	padding: 1em;
	background: #f9f9f9;
	border: 1px solid #ddd;
	border-radius: 4px;
}

.pbb-order-meta h4 {
	margin-top: 0;
	margin-bottom: 1em;
	border-bottom: 1px solid #ddd;
	padding-bottom: 0.5em;
}

.pbb-order-details {
	width: 100%;
	border-collapse: collapse;
}

.pbb-order-details th {
	text-align: left;
	padding: 0.5em 1em 0.5em 0;
	vertical-align: top;
	width: 30%;
	font-weight: 600;
}

.pbb-order-details td {
	padding: 0.5em 0;
	vertical-align: top;
}

.pbb-item-list,
.pbb-names-list {
	margin: 0;
	padding-left: 1.5em;
}

.pbb-item-list li,
.pbb-names-list li {
	margin-bottom: 0.25em;
}

.pbb-free-badge {
	display: inline-block;
	margin-left: 0.5em;
	padding: 0.2em 0.5em;
	background: #46b450;
	color: #fff;
	font-size: 0.85em;
	font-weight: bold;
	border-radius: 3px;
}

.pbb-color-preview {
	display: flex;
	gap: 0.5em;
	margin-top: 0.5em;
}

.pbb-color-swatch {
	display: inline-block;
	width: 30px;
	height: 30px;
	border: 1px solid #ddd;
	border-radius: 3px;
}

.pbb-price-breakdown {
	width: 100%;
	border-collapse: collapse;
	margin-top: 0.5em;
}

.pbb-price-breakdown td {
	padding: 0.25em 0;
}

.pbb-price-amount {
	text-align: right;
	font-weight: 600;
}

.pbb-total-row {
	border-top: 2px solid #ddd;
	margin-top: 0.5em;
}

.pbb-total-row td {
	padding-top: 0.5em;
}
</style>