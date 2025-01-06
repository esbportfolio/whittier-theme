<?php

declare(strict_types=1);

function whit_get_image_data(int $img_id): array|null {
    /**
     * Gets source and alt text for a given image ID
     * 
     * @param int       $img_id     ID for the image sought
     * 
     * @return          Array with source and alt text. Null if not found.
     */

    // Get source URL
    $src = wp_get_attachment_image_src($img_id)[0] ?? null;

    // If there's no image with that ID, return null
    if (!$src) return null;

    // Get alt text (fallback to 'image' if not present)
    $alt = get_post_meta($img_id, '_wp_attachment_image_alt', true);
    if (strlen($alt) === 0 ) $alt = 'image';

    return compact('src', 'alt');

}

function whit_get_menu_id(string $menu_location) {
    
    // Get all menu locations
    $menu_arr = get_nav_menu_locations();

    // Return either the menu ID or null if not found
    return $menu_arr[$menu_location] ?? null;

}