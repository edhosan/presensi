<?php

if (!function_exists('dateRange')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function dateRange($first, $last, $step = '+1 day', $format = 'Y-m-d')
    {
      $dates = [];
      $current = strtotime( $first );
      $last = strtotime( $last );

      while( $current <= $last ) {

          $dates[] = date( $format, $current );
          $current = strtotime( $step, $current );
      }

      return $dates;
    }
}
