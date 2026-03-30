<?php
/*
 * views/components/pagination.php
 *
 * TODO: Implement the pagination component.
 *
 * Expected variables:
 *   $activePage  (int)    - active page number (1-based) - set by ProductController::index()
 *   $totalPages  (int)    - total number of pages
 *   $baseUrl     (string) - base URL (e.g. '/products')
 *   $queryParams (array)  - existing GET params to preserve in page links
 *
 * Note: use $activePage (int) for the page number, NOT $currentPage which is
 *       the nav-highlight string ('products', 'cart', etc.) set by controllers.
 *
 * Future logic:
 *   - Render Previous / page number links / Next
 *   - Do not render anything if $totalPages <= 1
 *   - Build page URLs using http_build_query()
 *
 * ACCESSIBILITY:
 *   - Wrap in <nav aria-label="Pagination navigation">
 *   - Active page link needs aria-current="page"
 *   - Disabled prev/next need aria-disabled="true" and tabindex="-1"
 */

// TODO: Implement pagination component here
