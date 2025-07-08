<?php
namespace SpiderRewards;

function get_form_platform_options( $type = '' ) {

	switch ( $type ) {
		case 'video':
			$platforms = array(
				'youtube'   => __( 'YouTube', 'spider-rewards' ),
				'tiktok'    => __( 'TikTok', 'spider-rewards' ),
				'instagram' => __( 'Instagram', 'spider-rewards' ),
			);
			break;
		case 'referral':
			$platforms = array(
				'youtube'   => __( 'YouTube', 'spider-rewards' ),
				'tiktok'    => __( 'TikTok', 'spider-rewards' ),
				'instagram' => __( 'Instagram', 'spider-rewards' ),
			);
			break;
		case 'best':
			$platforms = array(
				'youtube'   => __( 'YouTube', 'spider-rewards' ),
				'tiktok'    => __( 'TikTok', 'spider-rewards' ),
				'instagram' => __( 'Instagram', 'spider-rewards' ),
			);
			break;
		case 'review':
			$platforms = array(
				'reddit'     => __( 'Reddit', 'spider-rewards' ),
				'sitejabber' => __( 'Sitejabber', 'spider-rewards' ),
				'trustpilot' => __( 'Trustpilot', 'spider-rewards' ),
			);
			break;
		default:
			$platforms = array(
				'video'    => array(
					'youtube'   => __( 'YouTube', 'spider-rewards' ),
					'tiktok'    => __( 'TikTok', 'spider-rewards' ),
					'instagram' => __( 'Instagram', 'spider-rewards' ),
				),
				'review'   => array(
					'reddit'     => __( 'Reddit', 'spider-rewards' ),
					'sitejabber' => __( 'Sitejabber', 'spider-rewards' ),
					'trustpilot' => __( 'Trustpilot', 'spider-rewards' ),
				),
				'referral' => array(
					'youtube'   => __( 'YouTube', 'spider-rewards' ),
					'tiktok'    => __( 'TikTok', 'spider-rewards' ),
					'instagram' => __( 'Instagram', 'spider-rewards' ),
				),
				'best'     => array(
					'youtube'   => __( 'YouTube', 'spider-rewards' ),
					'tiktok'    => __( 'TikTok', 'spider-rewards' ),
					'instagram' => __( 'Instagram', 'spider-rewards' ),
				),

			);
			break;
	}

	return $platforms;
}
