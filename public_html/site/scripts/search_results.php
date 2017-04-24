<?php
    require_once 'utilities/JaduStatus.php';

    // Breadcrumb, H1 and Title
    $MAST_HEADING = 'Search results';
    $MAST_BREADCRUMB = '<li>
                    <a href="' . getSiteRootURL() . '" rel="home">Home</a>
                </li>
                <li>
                    <span>Search results</span>
                </li>';

    require_once 'search_results.html.php';
