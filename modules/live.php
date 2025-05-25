<?php
/**
 * Integrate live blog
 */

if ( ! function_exists( 'lukin_live_results' ) ) {
    /**
     * Get results from live table
     */
    function lukin_live_results() {
        global $wpdb;

        $results = $wpdb->get_results( "SELECT * FROM wp_live ORDER BY `from` DESC" );

        if ( empty( $results ) || count( $results ) < 2 ) {
            return array( null, array() );
        }

        $current = array_shift( $results );

        return array( $current, $results );
    }
}

if ( ! function_exists( 'lukin_live_current' ) ) {
    /**
     * Display block with current info
     */
    function lukin_live_current( $current ) {
        if ( empty( $current ) ) {
            return;
        }

        printf(
            '%s <strong>%s</strong> %s %s',
            esc_html__( 'I have been in', 'lukin-blog' ),
            esc_html( $current->place ),
            esc_html__( 'for the last', 'lukin-blog' ),
            lukin_live_diff( $current->from ),
        );
    }
}

if ( ! function_exists( 'lukin_live_diff' ) ) {
    /**
     * Display difference in days between dates
     */
    function lukin_live_diff( $from, $to = '' ) {
        $start = new DateTime( $from );
        $end   = new DateTime( $to );

        $diff = $start->diff( $end );

        $label = __( ' days', 'lukin-blog' );

        if ( $diff->days < 2 ) {
            $label = __( ' day', 'lukin-blog' );
        }

        return max( $diff->days, 1 ) . $label;
    }
}

if ( ! function_exists( 'lukin_live_areas' ) ) {
    /**
     * Display fill area colors by days count
     */
    function lukin_live_areas() {
        global $wpdb;

        $colors = array(
            'var(--color-area-1)',
            'var(--color-area-2)',
            'var(--color-area-3)',
            'var(--color-area-4)',
        );

        $thresholds = array( 10, 30, 90 );

        $rows = $wpdb->get_results(
            "SELECT `country`, `from`, `to`
            FROM {$wpdb->prefix}live
            WHERE `from` IS NOT NULL"
        );

        $results = array();

        foreach ( $rows as $row ) {
            $from = new DateTime( $row->from );
            $to = $row->to ? new DateTime( $row->to ) : new DateTime();

            $diff = $from->diff( $to );
            $days = $diff->days;

            $country = $row->country;

            if ( ! isset( $results[ $country ] ) ) {
                $results[ $country ] = 0;
            }

            $results[ $country ] += $days;
        }

        $results['UA'] = 15;
        $results['ES'] = 7;
        $results['BY'] = 2;

        $styles = array();

        foreach ( $results as $code => $days ) {
            $index = count( $thresholds );

            foreach ( $thresholds as $i => $threshold ) {
                if ( $days < $threshold ) {
                    $index = $i;
                    break;
                }
            }

            $color = $colors[ min( $index, count( $colors ) - 1 ) ];

            $styles[] = "#{$code} { fill: {$color}; }";
        }

        echo '<style>' . implode( '', $styles ) . '</style>';
    }

    add_action( 'wp_head', 'lukin_live_areas' );
}