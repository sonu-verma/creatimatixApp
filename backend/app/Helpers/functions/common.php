<?php


if(!function_exists('generateSlug')) {
    function generateSlug($string) {
        // Convert the string to lowercase
        $slug = strtolower($string);

        // Remove special characters
        $slug = preg_replace('/[^a-z0-9-]+/', '-', $slug);

        // Trim any leading or trailing hyphens
        $slug = trim($slug, '-');

        return $slug;
    }
}

if(!function_exists('getBookingStatus')) {
    function getBookingStatus($status) {

        switch($status) {
            case 0:
                return 'pending';
            case 1:
                return 'confirmed';
            case 2:
                return 'cancelled';
            case 3:
                return 'completed';
            default:
                return 'pending';
        }
    }
}
