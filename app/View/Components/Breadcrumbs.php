<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Breadcrumbs extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $segments = request()->segments();
        $breadcrumbs = [];

        // Exclude the non-linkable prefixes
        $excludedSegments = ['settings', 'edit-permission', 'edit'];
        $pageTitle = 'Test';
        $button = 'Create Assembly';

        foreach ($segments as $key => $segment) {
            $displayName = ucwords(str_replace(['-', '_'], ' ', $segment));

            if (in_array($segment, $excludedSegments) || is_numeric($segment)) {
                $breadcrumbs[] = [
                    'name' => $displayName,
                    'url' => null,
                ];
            } else {
                $breadcrumbs[] = [
                    'name' => $displayName,
                    'url' => implode('/', array_slice($segments, 0, $key + 1)),
                ];
            }
        }

        return view('components.breadcrumbs', ['breadcrumbs' => $breadcrumbs, 'pageTitle' => $pageTitle, 'button' => $button]);
    }
}
