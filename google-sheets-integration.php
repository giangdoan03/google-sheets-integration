<?php
/*
Plugin Name: Google Sheets Integration
Description: A plugin to integrate with Google Sheets API and submit data from WordPress.
Version: 1.0
Author: Your Name
*/

// Include Composer autoload
require_once __DIR__ . '/vendor/autoload.php';

use Google\Client;
use Google\Service\Sheets;
use Google\Service\Sheets\ValueRange;

// Function to send data to Google Sheets
function gs_send_data_to_sheet($data) {
    try {
        $serviceAccountFilePath = __DIR__ . '/optimal-bivouac-433615-p6-90fb45322b7f.json';

        // Retrieve settings
        $spreadsheetId = get_option('gs_spreadsheet_id', 'default_spreadsheet_id');
        $range = get_option('gs_range', 'default_range');
        $emailTo = get_option('gs_notification_email', 'your-email@example.com'); // Retrieve the email address

        // Create Google Client and authenticate using service account
        $client = new Google_Client();
        $client->setAuthConfig($serviceAccountFilePath);
        $client->addScope(Google_Service_Sheets::SPREADSHEETS);
        $service = new Google_Service_Sheets($client);

        // Prepare data and append to sheet
        $body = new ValueRange([
            'values' => $data
        ]);
        $params = [
            'valueInputOption' => 'RAW'
        ];
        $result = $service->spreadsheets_values->append($spreadsheetId, $range, $body, $params);
        error_log('Success: ' . json_encode($result));

        // Send notification email
        $to = $emailTo; // Use the email address from settings
        $subject = 'Google Sheets Integration - Data Submitted Successfully';
        $message = 'The following data has been successfully submitted to Google Sheets:' . "\n\n";
        foreach ($data[0] as $field) {
            $message .= esc_html($field) . "\n";
        }
        $headers = array('Content-Type: text/plain; charset=UTF-8');

        wp_mail($to, $subject, $message, $headers);

    } catch (Exception $e) {
        error_log('Error: ' . $e->getMessage());
    }
}

// Handle form submission
function gs_handle_form_submission() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $productName = sanitize_text_field($_POST['product_name']);
        $priceDiscount = sanitize_text_field($_POST['price_discount']);
        $fullname = sanitize_text_field($_POST['fullname']);
        $phone = sanitize_text_field($_POST['phone']);
        $address = sanitize_text_field($_POST['address']);
        $notes = sanitize_textarea_field($_POST['notes']); // Use sanitize_textarea_field for textarea
        $quantity = sanitize_text_field($_POST['quantity']);

        $data = [
            [$productName, $priceDiscount, $fullname, $phone, $address, $notes, $quantity]
        ];

        gs_send_data_to_sheet($data);
        echo 'Gửi thành công!';
        wp_die();
    }
}

add_action('wp_ajax_gs_submit_form', 'gs_handle_form_submission');
add_action('wp_ajax_nopriv_gs_submit_form', 'gs_handle_form_submission');

// Admin menu for plugin settings
function gs_add_admin_menu() {
    add_menu_page(
        'Google Sheets Integration',
        'Google Sheets Integration',
        'manage_options',
        'google_sheets_integration',
        'gs_options_page',
        'dashicons-google',
        20
    );
}

add_action('admin_menu', 'gs_add_admin_menu');

// Register settings for the plugin
function gs_register_settings() {
    register_setting('gs_options_group', 'gs_spreadsheet_id');
    register_setting('gs_options_group', 'gs_range');
    register_setting('gs_options_group', 'gs_notification_email'); // Register email setting
}

add_action('admin_init', 'gs_register_settings');

// Options page content
function gs_options_page() {
    ?>
    <div class="wrap">
        <h1>Google Sheets Integration Settings</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('gs_options_group');
            do_settings_sections('gs_options_group');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Google Sheet ID</th>
                    <td><input type="text" name="gs_spreadsheet_id" value="<?php echo esc_attr(get_option('gs_spreadsheet_id')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Range</th>
                    <td><input type="text" name="gs_range" value="<?php echo esc_attr(get_option('gs_range')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Notification Email</th>
                    <td><input type="email" name="gs_notification_email" value="<?php echo esc_attr(get_option('gs_notification_email')); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}
?>
