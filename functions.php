<?php
// For the_footer() and get_footer()
require_once APPS_PATH . '/index/components.php';

/**
 * Get dynamic link using Router
 * @param string $name
 * @param array $args
 * @return string
 */
function get_permalink($name, $args = array()) {
    return Router::get_url($name, $args);
}
/**
 * Display dynamic link using get_permalink
 * @param string $name
 * @param array $args
 * @return void
 */
function the_permalink($name, $args = array()) {
    echo get_permalink($name, $args);
}
/**
 * Return link with GET variables
 * like ?s=fasfd&page=3&platform=4
 * @param array $GET
 */
function the_GET_request($GET) {
    echo '?' . http_build_query($GET);
}

/**
 * Secure string output (XSS attacks)
 * @param string $str
 * @return void
 */
function the_safe($str) {
    echo get_the_safe($str);
}
function get_the_safe($str) {
    return htmlspecialchars($str);
}
/**
 * Redirect to some url using header()
 * @param string $url
 * @return never
 */
function redirect_to($url) {
    header("Location: " . $url);
    exit;
}


/**
 * Upload file to server
 * @param  array $file
 * @param string $subfolder_name like 'products/'
 * @param string $file_type (image, ...)
 * @return string|false
 */
function upload_file($file, $subfolder_name, $file_type) {
    if (isset($file) && $file['error'] === UPLOAD_ERR_OK) {
        $uploadDir = MEDIA_ROOT;
        
        // Create new unique filename
        $fileName = pathinfo($file['name'], PATHINFO_FILENAME);
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        
        $newFileName = $fileName . '_' . uniqid() . '.' . $fileExtension;

        // media/{subfolder_name}/{new_file.png}
        $uploadFile = $uploadDir . $subfolder_name . $newFileName;
        $filepath_db = $subfolder_name . $newFileName;

        // Get file type
        $fileType = mime_content_type($file['tmp_name']);

        // if subfolder is not exists
        if (!is_dir($uploadDir . $subfolder_name)) {
            mkdir($uploadDir . $subfolder_name, 0775, true);
        }

        // Upload file to server
        if (strpos($fileType, 'image') === 0) {
            if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
                return $filepath_db;
            } else {
                return false;
            }
        }
    }
    return false;
}
/**
 * Calculate the offset for pagination based on the limit and page number.
 * @param int $limit
 * @param int $page
 * @return int
 */
function calc_page_offset($limit, $page) {
    if ($limit <= 0 || $page <= 0) {
        throw new InvalidArgumentException('Both $limit and $page must be positive integers.');
    }
    return ($page - 1) * $limit;
}
function the_pagination($count, $elem_per_page, $current_page) {
    if($count == 0)
        return;

    $total_pages = (int)ceil($count / $elem_per_page);

    // If total pages is only one, then don't display pagination
    if($total_pages == 1)
        return;

    $GET = $_GET;
    ?>
        <nav class="pagination">
            <ul>
                <!-- Before current page -->
                 <?php for($i = $current_page - 2; $i < $current_page; $i++): 
                        if($i <= 0)
                            continue;
                        $GET['page'] = $i;
                    ?>
                <li><a class="btn page-btn" href="<?php the_GET_request($GET) ?>"><?php echo $i ?></a></li>
                <?php endfor; ?>

                 <!-- Current page -->
                <?php if($current_page > 0 && $current_page <= $total_pages): ?>
                <li><div class="btn active page-btn"><?php echo $current_page ?></div></li>
                <?php endif ?>

                <!-- After current page -->
                <?php for($i = $current_page + 1; $i <= $total_pages && $i <= $current_page + 2; $i++): 
                        $GET['page'] = $i;
                    ?>
                <li><a class="btn page-btn" href="<?php the_GET_request($GET) ?>"><?php echo $i ?></a></li>
                <?php endfor; ?>
            </ul>
        </nav>
    <?php
}

/**
 * Template for <title> in the header
 * @param mixed $page_title
 * @return string
 */
function get_title_website($page_title) {
    return $page_title . ' - KeysShop';
}
/**
 * Get current user id and username. If user is not authorized - false
 * @return UserModel|false
 */
require_once APPS_PATH . '/users/models.php';
function get_auth_user() {

    $user_id = $_SESSION['user_id'];

    if(isset($user_id))
    {
        $user = UserModel::get([
            [
                'name' => 'obj.id',
                'type' => '=',
                'value' => $user_id
            ]
        ]);
        if(empty($user))
            return false;

        return $user;
    }
    else
        return false;
}
/**
 * Set current user 
 * @param int $user_id
 */
function set_auth_user($user_id) {
    $_SESSION['user_id'] = $user_id;
}
/**
 * Destroy all session variables (and with current user)
 */
function logout() {
    session_unset();
    session_destroy();
}
function generate_uuid() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), // 32 bit
        mt_rand(0, 0xffff), // 16 bit
        mt_rand(0, 0x0fff) | 0x4000, // Version 4
        mt_rand(0, 0x3fff) | 0x8000, // Version 1
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff) // 48 bit
    );
}
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function send_email($subject, $body, $altBody, $to_address, $to_name) {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host       = EMAIL_SETTINGS['host']; 
    $mail->SMTPAuth   = EMAIL_SETTINGS['smtp_auth']; 
    $mail->Username   = EMAIL_SETTINGS['username'];
    $mail->Password   = EMAIL_SETTINGS['password'];
    $mail->SMTPSecure = EMAIL_SETTINGS['smtp_secure']; 
    $mail->Port       = EMAIL_SETTINGS['port'];

    $mail->setFrom(EMAIL_SETTINGS['username'], EMAIL_SETTINGS['from_title']);
    $mail->addAddress($to_address, $to_name);

    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = $body;
    $mail->AltBody = $altBody;

    $mail->send();
}
?>