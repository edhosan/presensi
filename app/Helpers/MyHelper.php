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

if (!function_exists('monthName')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function  monthName($month)
    {
      $month_name = [
        '1' =>  'Januari',
        '2' =>  'Februari',
        '3' =>  'Maret',
        '4' =>  'April',
        '5' =>  'Mei',
        '6' =>  'Juni',
        '7' =>  'Juli',
        '8' =>  'Agustus',
        '9' =>  'September',
        '10'  =>  'Oktober',
        '11'  =>  'November',
        '12'  =>  'Desember'
      ];

      return $month_name[$month];
    }
}
