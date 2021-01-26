<?php
require_once TOOLKIT_FOR_ELEMENTOR_PATH . 'includes/Pagination.class.php';
global $wpdb;
$totalRecord = $wpdb->get_var("SELECT count(id) FROM {$wpdb->prefix}toolkit_gtmetrix");
$pagConfig = array(
    'baseURL' => admin_url('admin.php'),
    'ajax' => true,
    'totalRows' => $totalRecord,
    'perPage' => $this->limit
);
$pagination = new SproutPagination($pagConfig);
$toolkit_uploads = WP_CONTENT_DIR . '/toolkit-reports/';
$toolkit_uploads_url = get_option( 'siteurl' ) . '/wp-content/toolkit-reports/';
$html = '<h4>Scan History</h4>
					<table class="table table-bordered" id="gtmetrix-scan-history" style="background:#F9F9F9;">
						<thead>
						  <tr>
							<th width="15%">Download Report</th>
							<th width="30%">URL</th>
							<th width="10%">Date</th>
							<th width="10%">Load Time</th>
							<th width="15%">Page Speed</th>
							<th width="10%">YSlow</th>
							<th width="10%">Region</th>
						  </tr>
						</thead>
						<tbody>';
$scanHistory = $wpdb->get_results("SELECT `test_id`,`scan_url`, `load_time`, `page_speed`, `yslow`,`browser`, `region`,`resources`,`response_log`, `created` FROM {$wpdb->prefix}toolkit_gtmetrix ORDER BY id desc LIMIT $offset,$limit", ARRAY_A);
if ($scanHistory) {
    foreach ($scanHistory as $s => $history) {
        $resources = json_decode($history['resources'], true);
        $html .= '<tr>
						<td>';
        if ( file_exists($toolkit_uploads.'report_pdf-' . $history['test_id'] . '.pdf') ) {
            $html .= '<a href="' . $toolkit_uploads_url.'report_pdf-' . $history['test_id'] . '.pdf' . '" class="" target="_blank">Full Report</a>';
        } else {
            $html .= '<a href="javascript:void(0);" class="download-full-report" data-full_report="' . $resources['report_pdf_full'] . '" data-testid="' . $history['test_id'] . '">Full Report</a>';
        }
        $html .= '</td>
						<td><a href="' . $history['scan_url'] . '" target="_blank">' . $history['scan_url'] . '</a></td>
						<td>' . $history['created'] . '</td>
						<td>' . round($history['load_time'] / 1000, 2) . '</td>
						<td>' . $history['page_speed'] . '%</td>
						<td>' . $history['yslow'] . '%</td>
						<td>' . $history['region'] . '</td>
					 </tr>';
    }
} else {
    $html .= '<tr><td colspan="7" style="text-align:center;">No Record Found</td></tr>';
}
$html .= '</tbody>
			  </table>';
$html .= $pagination->createLinks();

echo $html;