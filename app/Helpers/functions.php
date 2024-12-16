<?php

if (!function_exists('getSortIcon')) {
    function getSortIcon($field) {
        $currentSort = request('sort');
        $currentDirection = request('direction');
        
        if ($currentSort !== $field) {
            return '';
        }
        
        return $currentDirection === 'asc' 
            ? '<i class="fas fa-sort-up ml-1"></i>' 
            : '<i class="fas fa-sort-down ml-1"></i>';
    }
} 