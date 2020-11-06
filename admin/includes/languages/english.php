<?php

    function lang ( $phrase ) {

        static $lang = array(

            // navbar in daashbord

            'Home_Admin'        => 'HOME',
            'Home_Categories'   => 'CATEGORIES',
            'Home_ITEMS'        => 'ITEMS',
            'Home_MEMBERS'      => 'MEMBERS',
            'Home_Comment'      => 'COMMENT',
            'Home_Loai'         => 'Loai',
            'Edit_Profile'      => 'Edit Profile',
            'Home_Settings'     => 'Settings',
            'Home_Logout'       => 'Logout',
            

        );

        return $lang[$phrase];

    }
