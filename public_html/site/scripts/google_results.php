<?php
    require_once 'utilities/JaduStatus.php';
    require_once 'JaduLibraryFunctions.php';
    require_once 'rupa/JaduRupaAppliance.php';
    require_once 'rupa/JaduRupaSearch.php';
    require_once 'rupa/JaduRupaCollection.php';
    require_once 'rupa/JaduRupaRenameResultURL.php';
    require_once 'JaduMimeTypeList.php';
    require_once 'JaduFileTypeMappings.php';
    require_once 'websections/JaduDownloadFiles.php';

    // Breadcrumb, H1 and Title
    $MAST_HEADING = 'Search results';
    $MAST_BREADCRUMB = '<li>
                    <a href="' . getSiteRootURL() . '" rel="home">Home</a>
                </li>
                <li>
                    <span>Search results</span>
                </li>';

    require_once 'google_results.html.php';
