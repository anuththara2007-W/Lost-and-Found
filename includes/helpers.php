<?php
/**
 * includes/helpers.php
 * Global helper functions
 */

/**
 * Redirect to a specific URL safely
 */
function redirect($url) {
    header('Location: ' . BASE_URL . $url);
    exit;
}

/**
 * Sanitize output for HTML to prevent XSS
 */
function escape($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Format a date string
 */
function formatDate($dateString) {
    return date('M j, Y, g:i A', strtotime($dateString));
}

/**
 * Check if the user is currently logged in
 */
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

/**
 * Ensure a user is logged in, or redirect them to login page
 */
function requireLogin() {
    if (!isLoggedIn()) {
        $_SESSION['flash_error'] = 'Please log in to access that page.';
        redirect('/auth/login');
    }

    // Verify user still exists in DB
    $db = \App\Core\Database::getInstance()->getConnection();
    $stmt = $db->prepare("SELECT user_id FROM users WHERE user_id = :id");
    $stmt->execute(['id' => $_SESSION['user_id']]);
    if (!$stmt->fetchColumn()) {
        unset($_SESSION['user_id']);
        $_SESSION['flash_error'] = 'Your session is invalid or your account was removed. Please log in again.';
        redirect('/auth/login');
    }
}

/**
 * Form helpers: get old value if validation fails
 */
function old($key, $default = '') {
    return isset($_SESSION['old'][$key]) ? escape($_SESSION['old'][$key]) : $default;
}

/**
 * Clear old input from session (call at the end of form rendering)
 */
function clearOld() {
    unset($_SESSION['old']);
}

/**
 * Display and clear Flash Messages
 */
function displayFlashMessages() {
    $toasts = [];
    if (isset($_SESSION['flash_success'])) {
        $toasts[] = ['type' => 'success', 'text' => $_SESSION['flash_success']];
        unset($_SESSION['flash_success']);
    }
    if (isset($_SESSION['flash_error'])) {
        $toasts[] = ['type' => 'error', 'text' => $_SESSION['flash_error']];
        unset($_SESSION['flash_error']);
    }

    if (empty($toasts)) {
        return;
    }

    echo '<div id="global-toast-stack" style="position:fixed; top:18px; right:18px; z-index:9999; display:flex; flex-direction:column; gap:10px;">';
    foreach ($toasts as $t) {
        $isSuccess = $t['type'] === 'success';
        $bg = $isSuccess ? '#0f766e' : '#b91c1c';
        $icon = $isSuccess ? 'fa-circle-check' : 'fa-circle-exclamation';
        echo '<div class="global-toast" style="min-width:260px; max-width:340px; color:#fff; background:' . $bg . '; border-radius:12px; padding:10px 12px; box-shadow:0 12px 25px rgba(0,0,0,.2); display:flex; align-items:center; gap:8px;">';
        echo '<i class="fa-solid ' . $icon . '"></i><span style="font-size:13px; line-height:1.35;">' . escape($t['text']) . '</span>';
        echo '</div>';
    }
    echo '</div>';
    echo "<script>setTimeout(function(){document.querySelectorAll('.global-toast').forEach(function(el){el.style.opacity='0';el.style.transform='translateY(-8px)';el.style.transition='all .25s ease';});setTimeout(function(){var s=document.getElementById('global-toast-stack');if(s){s.remove();}},300);},2200);</script>";
}

function add_action($hook, callable $callback) {
    \App\Core\HookManager::addAction($hook, $callback);
}

function do_action($hook, ...$args) {
    \App\Core\HookManager::doAction($hook, ...$args);
}

function add_filter($hook, callable $callback) {
    \App\Core\HookManager::addFilter($hook, $callback);
}

function apply_filters($hook, $value, ...$args) {
    return \App\Core\HookManager::applyFilters($hook, $value, ...$args);
}

function current_lang() {
    $lang = $_SESSION['lang'] ?? 'en';
    $allowed = ['en', 'si', 'ta'];
    return in_array($lang, $allowed, true) ? $lang : 'en';
}

function t($key) {
    static $dict = [
        'en' => [
            'lost' => 'Lost',
            'found' => 'Found',
            'view' => 'View',
            'browse' => 'Browse',
            'success_stories' => 'Success Stories',
            'map_view' => 'Map View',
            'dashboard' => 'Dashboard',
            'profile' => 'Profile',
            'logout' => 'Logout',
            'login' => 'Login',
            'signup' => 'Sign Up',
            'report_lost' => 'Report Lost',
            'report_found' => 'Report Found',
            'admin_dashboard' => 'Admin Dashboard',
            'platform' => 'Platform',
            'resources' => 'Resources',
            'legal' => 'Legal',
            'browse_items' => 'Browse Items',
            'live_map' => 'Live Map',
            'about' => 'About',
            'contact' => 'Contact',
            'faq' => 'FAQ',
            'terms' => 'Terms of Service',
            'privacy' => 'Privacy Policy',
            'account_access' => 'Account Access',
            'footer_tagline' => 'Community-driven recovery platform for safer, faster reunions.',
            'footer_rights' => 'All rights reserved.',
            'footer_safety' => 'Built for community safety and trust.',
            'home_community_network' => 'Community Recovery Network',
            'home_hero_title_html' => "Find What<br>You've <em>Lost.</em><br>Return What<br>You've <em>Found.</em>",
            'home_hero_sub' => 'A secure community platform connecting people with their lost belongings through geo-matched reports, verified finders, and safe handoffs.',
            'home_lost_cta' => 'I Lost Something',
            'home_found_cta' => 'I Found Something',
            'home_recent_reports' => 'Recent Reports',
            'home_platform_access' => 'Platform Access',
            'home_safe' => 'Safe',
            'home_recovery_flow' => 'Recovery Flow',
            'home_illustration_alt' => 'Lost and found illustration',
            'home_lost_active' => 'Lost report active nearby',
            'home_found_matched' => 'Found report matched',
            'home_latest_activity' => 'Latest Activity',
            'home_recently_reported_html' => 'Recently <em>Reported</em> Items',
            'home_recent_sub' => 'Recognize something? Open an item to verify details and start a secure recovery flow.',
            'home_no_items' => 'No items have been reported yet. Be the first to help the community.',
            'home_browse_all' => 'Browse All Items',
            'home_process' => 'The Process',
            'home_how_recovery_html' => 'How <em>Recovery</em> Works',
            'home_three_steps' => 'Three clear steps from report creation to safe return.',
            'home_step1_title' => 'Report and Pin',
            'home_step1_text' => 'Submit item details and mark the location precisely on the map.',
            'home_step2_title' => 'Match and Notify',
            'home_step2_text' => 'The system compares category, text, and distance to find potential matches.',
            'home_step3_title' => 'Verify and Reunite',
            'home_step3_text' => 'Users verify undisclosed details and coordinate a safe public handoff.',
            'home_item_types' => 'Item Types',
            'home_browse_category_html' => 'Browse by <em>Category</em>',
            'home_category_sub' => 'Organized categories improve match quality and recovery speed.',
            'home_cat_identification' => 'Identification',
            'home_cat_identification_sub' => 'Secure handling',
            'home_cat_electronics' => 'Electronics',
            'home_cat_electronics_sub' => 'High-value items',
            'home_cat_wallets' => 'Wallets and Cards',
            'home_cat_wallets_sub' => 'Fraud-sensitive',
            'home_cat_keys' => 'Keys and Access',
            'home_cat_keys_sub' => 'Fast turnaround',
            'home_cat_pets' => 'Pets',
            'home_cat_pets_sub' => 'Urgent cases',
            'home_cat_bags' => 'Bags and Luggage',
            'home_cat_bags_sub' => 'Transit losses',
            'home_psychology_title' => 'The Psychology of Loss',
            'home_psychology_text1' => 'Losing an essential item causes stress and uncertainty. A fast report channel restores control immediately.',
            'home_psychology_text2' => 'This platform transforms isolated searching into coordinated community recovery.',
            'home_anatomy_title' => 'The Anatomy of Recovery',
            'home_anatomy_text1' => 'Successful recovery depends on precise details, reliable matching, and low communication friction.',
            'home_anatomy_text2' => 'Geo-tagged records plus secure messaging reduce delay and improve match confidence.',
            'home_trust_secure' => 'Secure Messaging',
            'home_trust_secure_sub' => 'Personal data stays protected',
            'home_trust_verified' => 'Verified Finders',
            'home_trust_verified_sub' => 'Trust badge progression',
            'home_trust_geo' => 'Geo-Matched',
            'home_trust_geo_sub' => 'Precision location filtering',
            'home_trust_alerts' => 'Instant Alerts',
            'home_trust_alerts_sub' => 'Immediate update delivery',
            'home_stay_safe' => 'Stay Safe',
            'home_safe_handoff_html' => 'Safe <em>Handoff</em> Guidelines',
            'home_safe_sub' => 'Always prioritize safety during real-world exchanges.',
            'home_safe_public' => 'Meet in Public',
            'home_safe_public_sub' => 'Use visible, staffed locations such as libraries or station lobbies.',
            'home_safe_verify' => 'Verify First',
            'home_safe_verify_sub' => 'Confirm one withheld detail before arranging any meeting.',
            'home_safe_daytime' => 'Prefer Daytime',
            'home_safe_daytime_sub' => 'Schedule exchanges in daylight whenever possible.',
            'home_safe_support' => 'Bring Support',
            'home_safe_support_sub' => 'Bring a companion or share your meet details with a trusted contact.',
            'home_cta_title' => 'Something Missing?',
            'home_cta_sub' => 'Start a report in minutes and activate the community recovery network.',
            'home_report_lost_item' => 'Report a Lost Item',
            'home_report_found_item' => 'Report a Found Item'
        ],
        'si' => [
            'lost' => 'අහිමි',
            'found' => 'සොයාගත්',
            'view' => 'බලන්න',
            'browse' => 'ගවේෂණය',
            'success_stories' => 'සාර්ථක කතා',
            'map_view' => 'සිතියම',
            'dashboard' => 'උපකරණ පුවරුව',
            'profile' => 'පැතිකඩ',
            'logout' => 'ඉවත් වන්න',
            'login' => 'පිවිසෙන්න',
            'signup' => 'ලියාපදිංචි වන්න',
            'report_lost' => 'අහිමි දේ වාර්තා කරන්න',
            'report_found' => 'සොයාගත් දේ වාර්තා කරන්න',
            'admin_dashboard' => 'පරිපාලක පුවරුව',
            'platform' => 'වේදිකාව',
            'resources' => 'සම්පත්',
            'legal' => 'නීතිමය',
            'browse_items' => 'අයිතම බලන්න',
            'live_map' => 'සජීවී සිතියම',
            'about' => 'අප ගැන',
            'contact' => 'අමතන්න',
            'faq' => 'නිති අසන ප්‍රශ්න',
            'terms' => 'සේවා කොන්දේසි',
            'privacy' => 'පුද්ගලිකත්ව ප්‍රතිපත්තිය',
            'account_access' => 'ගිණුම් පිවිසුම',
            'footer_tagline' => 'ආරක්ෂිත සහ වේගවත් නැවත එක්කිරීම් සඳහා ප්‍රජා මඟින් চালිත වේදිකාව.',
            'footer_rights' => 'සියලුම හිමිකම් ඇවිරිණි.',
            'footer_safety' => 'ප්‍රජා ආරක්ෂාව සහ විශ්වාසය සඳහා ඉදිකරන ලදි.',
            'home_community_network' => 'ප්‍රජා ප්‍රතිසාධන ජාලය',
            'home_hero_title_html' => 'ඔබට <em>අහිමි</em> වූ දේ<br>සොයන්න.<br>ඔබට <em>හමු</em> වූ දේ<br>නැවත ලබා දෙන්න.',
            'home_hero_sub' => 'භූ-ගැලපීම්, තහවුරු කළ සොයාගන්නන් සහ ආරක්ෂිත හුවමාරු හරහා අහිමි දේවල් නැවත සොයාගැනීමට උදව් කරන ආරක්ෂිත ප්‍රජා වේදිකාවක්.',
            'home_lost_cta' => 'මට අහිමි වුණා',
            'home_found_cta' => 'මට හමු වුණා',
            'home_recent_reports' => 'නවතම වාර්තා',
            'home_platform_access' => 'වේදිකා ප්‍රවේශය',
            'home_safe' => 'ආරක්ෂිත',
            'home_recovery_flow' => 'ප්‍රතිසාධන ප්‍රවාහය',
            'home_illustration_alt' => 'අහිමි සොයාගැනීම් රූපය',
            'home_lost_active' => 'අහිමි වාර්තාවක් ආසන්නයේ සක්‍රීයයි',
            'home_found_matched' => 'සොයාගත් වාර්තාව ගැලපුණි',
            'home_latest_activity' => 'නවතම ක්‍රියාකාරකම්',
            'home_recently_reported_html' => 'නවතම <em>වාර්තා කළ</em> අයිතම',
            'home_recent_sub' => 'හඳුනා ගත්තේද? විස්තර තහවුරු කර ආරක්ෂිත ප්‍රතිසාධනය ආරම්භ කරන්න.',
            'home_no_items' => 'තවම අයිතම වාර්තා කර නොමැත.',
            'home_browse_all' => 'සියලු අයිතම බලන්න',
            'home_process' => 'ක්‍රියාවලිය',
            'home_how_recovery_html' => '<em>ප්‍රතිසාධනය</em> සිදුවන ආකාරය',
            'home_three_steps' => 'වාර්තා කිරීමෙන් ආරක්ෂිත ආපසුදීම දක්වා සරල පියවර තුනක්.',
            'home_step1_title' => 'වාර්තා කර ස්ථානය සලකුණු කරන්න',
            'home_step1_text' => 'අයිතම විස්තර ඇතුළත් කර සිතියමේ ස්ථානය සලකුණු කරන්න.',
            'home_step2_title' => 'ගැලපීම් සොයන්න',
            'home_step2_text' => 'ප්‍රවර්ගය, පෙළ සහ දුර අනුව ගැලපීම් සොයයි.',
            'home_step3_title' => 'තහවුරු කර හමුවන්න',
            'home_step3_text' => 'විස්තර තහවුරු කර ආරක්ෂිත හුවමාරුවක් සකස් කරන්න.',
            'home_item_types' => 'අයිතම වර්ග',
            'home_browse_category_html' => '<em>ප්‍රවර්ග</em> අනුව බලන්න',
            'home_category_sub' => 'සංවිධානාත්මක ප්‍රවර්ග ගැලපීම් ගුණාත්මකභාවය වැඩි කරයි.',
            'home_cat_identification' => 'හඳුනාගැනීම්',
            'home_cat_identification_sub' => 'ආරක්ෂිත හැසිරවීම',
            'home_cat_electronics' => 'ඉලෙක්ට්‍රොනික උපකරණ',
            'home_cat_electronics_sub' => 'ඉහළ වටිනාකම',
            'home_cat_wallets' => 'වොලට් සහ කාඩ්',
            'home_cat_wallets_sub' => 'වංචා අවදානම',
            'home_cat_keys' => 'යතුරු',
            'home_cat_keys_sub' => 'වේගවත් ප්‍රතිචාර',
            'home_cat_pets' => 'සුරතල් සතුන්',
            'home_cat_pets_sub' => 'හදිසි අවස්ථා',
            'home_cat_bags' => 'බෑග්',
            'home_cat_bags_sub' => 'ගමන් අතර අහිමිවීම්',
            'home_psychology_title' => 'අහිමි වීමේ මානසිකත්වය',
            'home_psychology_text1' => 'අත්‍යවශ්‍ය අයිතමයක් අහිමි වීම ආතතියක් ගෙන එයි.',
            'home_psychology_text2' => 'මෙම වේදිකාව තනි සෙවුම ප්‍රජා ප්‍රතිසාධනයක් බවට පරිවර්තනය කරයි.',
            'home_anatomy_title' => 'ප්‍රතිසාධනයේ සැලැස්ම',
            'home_anatomy_text1' => 'නිවැරදි විස්තර සහ විශ්වාසනීය ගැලපීම් අත්‍යවශ්‍යයි.',
            'home_anatomy_text2' => 'භූ-ලේඛන සහ ආරක්ෂිත පණිවිඩ ප්‍රමාදය අඩු කරයි.',
            'home_trust_secure' => 'ආරක්ෂිත පණිවිඩ',
            'home_trust_secure_sub' => 'පුද්ගලික දත්ත ආරක්ෂිතයි',
            'home_trust_verified' => 'තහවුරු කළ සොයාගන්නන්',
            'home_trust_verified_sub' => 'විශ්වාස ලාංඡන ප්‍රගතිය',
            'home_trust_geo' => 'භූ-ගැලපීම්',
            'home_trust_geo_sub' => 'නිවැරදි ස්ථාන පෙරහන',
            'home_trust_alerts' => 'ක්ෂණික දැනුම්දීම්',
            'home_trust_alerts_sub' => 'ඉක්මන් යාවත්කාලීන',
            'home_stay_safe' => 'ආරක්ෂිතව සිටින්න',
            'home_safe_handoff_html' => 'ආරක්ෂිත <em>හුවමාරු</em> මාර්ගෝපදේශ',
            'home_safe_sub' => 'සැබෑ ලෝක හමුවීම්වලදී ආරක්ෂාව ප්‍රමුඛ කරගන්න.',
            'home_safe_public' => 'පොදු තැනක හමුවන්න',
            'home_safe_public_sub' => 'පුස්තකාල වැනි පෙනෙන ස්ථාන භාවිතා කරන්න.',
            'home_safe_verify' => 'පළමුව තහවුරු කරන්න',
            'home_safe_verify_sub' => 'හමුවීමට පෙර එක් විස්තරයක් තහවුරු කරන්න.',
            'home_safe_daytime' => 'දහවල් කාලය තෝරන්න',
            'home_safe_daytime_sub' => 'දිවා කාලයේ හුවමාරු සැලසුම් කරන්න.',
            'home_safe_support' => 'සහය රැගෙන යන්න',
            'home_safe_support_sub' => 'විශ්වාසවන්ත කෙනෙකුට විස්තර බෙදා ගන්න.',
            'home_cta_title' => 'යමක් අහිමි වුණාද?',
            'home_cta_sub' => 'මිනිත්තු කිහිපයකින් වාර්තාවක් ආරම්භ කරන්න.',
            'home_report_lost_item' => 'අහිමි අයිතමයක් වාර්තා කරන්න',
            'home_report_found_item' => 'සොයාගත් අයිතමයක් වාර්තා කරන්න'
        ],
        'ta' => [
            'lost' => 'இழந்தது',
            'found' => 'கண்டது',
            'view' => 'பார்க்க',
            'browse' => 'உலாவுக',
            'success_stories' => 'வெற்றி கதைகள்',
            'map_view' => 'வரைபடம்',
            'dashboard' => 'டாஷ்போர்டு',
            'profile' => 'சுயவிவரம்',
            'logout' => 'வெளியேறு',
            'login' => 'உள்நுழை',
            'signup' => 'பதிவு செய்',
            'report_lost' => 'இழந்ததை பதிவு செய்',
            'report_found' => 'கண்டதை பதிவு செய்',
            'admin_dashboard' => 'நிர்வாக பலகம்',
            'platform' => 'தளம்',
            'resources' => 'வளங்கள்',
            'legal' => 'சட்டம்',
            'browse_items' => 'பொருட்களை உலாவுக',
            'live_map' => 'நேரலை வரைபடம்',
            'about' => 'எங்களை பற்றி',
            'contact' => 'தொடர்பு',
            'faq' => 'அடிக்கடி கேட்கப்படும் கேள்விகள்',
            'terms' => 'சேவை விதிமுறைகள்',
            'privacy' => 'தனியுரிமைக் கொள்கை',
            'account_access' => 'கணக்கு அணுகல்',
            'footer_tagline' => 'பாதுகாப்பான, விரைவான மீளிணைப்புக்கான சமூக இயக்கம் கொண்ட தளம்.',
            'footer_rights' => 'அனைத்து உரிமைகளும் பாதுகாக்கப்பட்டவை.',
            'footer_safety' => 'சமூக பாதுகாப்பும் நம்பிக்கையும் கருதி உருவாக்கப்பட்டது.',
            'home_community_network' => 'சமூக மீட்பு வலையமைப்பு',
            'home_hero_title_html' => 'நீங்கள் <em>இழந்ததை</em><br>கண்டுபிடிக்கவும்.<br>நீங்கள் <em>கண்டதை</em><br>திருப்பி அளிக்கவும்.',
            'home_hero_sub' => 'புவியியல் பொருத்தங்கள், சரிபார்க்கப்பட்ட கண்டுபிடிப்பவர்கள் மற்றும் பாதுகாப்பான ஒப்படைப்புகள் மூலம் இழந்த பொருட்களை இணைக்கும் பாதுகாப்பான சமூக தளம்.',
            'home_lost_cta' => 'நான் ஒன்றை இழந்தேன்',
            'home_found_cta' => 'நான் ஒன்றைக் கண்டேன்',
            'home_recent_reports' => 'சமீப அறிக்கைகள்',
            'home_platform_access' => 'தள அணுகல்',
            'home_safe' => 'பாதுகாப்பான',
            'home_recovery_flow' => 'மீட்பு நடைமுறை',
            'home_illustration_alt' => 'இழந்தது கண்டுபிடிப்பு விளக்கம்',
            'home_lost_active' => 'இழந்த அறிக்கை அருகில் செயலில் உள்ளது',
            'home_found_matched' => 'கண்டறிந்த அறிக்கை பொருந்தியது',
            'home_latest_activity' => 'சமீப செயல்பாடு',
            'home_recently_reported_html' => 'சமீபத்தில் <em>அறிக்கை செய்யப்பட்ட</em> பொருட்கள்',
            'home_recent_sub' => 'ஏதாவது தெரிகிறதா? விவரங்களை சரிபார்த்து பாதுகாப்பான மீட்பை தொடங்குங்கள்.',
            'home_no_items' => 'இன்னும் எந்த பொருட்களும் அறிக்கை செய்யப்படவில்லை.',
            'home_browse_all' => 'அனைத்து பொருட்களையும் உலாவுக',
            'home_process' => 'செயல்முறை',
            'home_how_recovery_html' => '<em>மீட்பு</em> எப்படி செயல்படுகிறது',
            'home_three_steps' => 'அறிக்கை உருவாக்கம் முதல் பாதுகாப்பான ஒப்படைப்பு வரை 3 படிகள்.',
            'home_step1_title' => 'அறிக்கை செய்து இடம் குறிக்கவும்',
            'home_step1_text' => 'பொருள் விவரங்களை சமர்ப்பித்து வரைபடத்தில் இடம் குறிக்கவும்.',
            'home_step2_title' => 'பொருத்தம் மற்றும் அறிவிப்பு',
            'home_step2_text' => 'வகை, உரை மற்றும் தூரத்தை ஒப்பிட்டு பொருத்தம் கண்டறிகிறது.',
            'home_step3_title' => 'சரிபார்த்து மீள இணை',
            'home_step3_text' => 'மறைக்கப்பட்ட விவரங்களை சரிபார்த்து பாதுகாப்பாக ஒப்படைக்கவும்.',
            'home_item_types' => 'பொருள் வகைகள்',
            'home_browse_category_html' => '<em>வகை</em> அடிப்படையில் உலாவுக',
            'home_category_sub' => 'ஒழுங்குபடுத்தப்பட்ட வகைகள் பொருத்த தரத்தை உயர்த்தும்.',
            'home_cat_identification' => 'அடையாளம்',
            'home_cat_identification_sub' => 'பாதுகாப்பான கையாளல்',
            'home_cat_electronics' => 'மின்சாதனங்கள்',
            'home_cat_electronics_sub' => 'உயர் மதிப்பு பொருட்கள்',
            'home_cat_wallets' => 'பணப்பைகள் மற்றும் அட்டைகள்',
            'home_cat_wallets_sub' => 'மோசடி உணர்திறன்',
            'home_cat_keys' => 'சாவிகள்',
            'home_cat_keys_sub' => 'வேகமான மீட்பு',
            'home_cat_pets' => 'செல்லப்பிராணிகள்',
            'home_cat_pets_sub' => 'அவசர நிலைகள்',
            'home_cat_bags' => 'பைகள்',
            'home_cat_bags_sub' => 'பயண இழப்புகள்',
            'home_psychology_title' => 'இழப்பின் மனவியல்',
            'home_psychology_text1' => 'முக்கிய பொருளை இழப்பது மன அழுத்தத்தை உண்டாக்கும்.',
            'home_psychology_text2' => 'இந்த தளம் தனிப்பட்ட தேடலை சமூக மீட்பாக மாற்றுகிறது.',
            'home_anatomy_title' => 'மீட்பின் அமைப்பு',
            'home_anatomy_text1' => 'துல்லியமான விவரங்கள், நம்பகமான பொருத்தம் அவசியம்.',
            'home_anatomy_text2' => 'புவி-குறியீடு மற்றும் பாதுகாப்பான செய்தி தாமதத்தை குறைக்கும்.',
            'home_trust_secure' => 'பாதுகாப்பான செய்தி',
            'home_trust_secure_sub' => 'தனிப்பட்ட தரவு பாதுகாக்கப்படும்',
            'home_trust_verified' => 'சரிபார்க்கப்பட்ட கண்டுபிடிப்பவர்கள்',
            'home_trust_verified_sub' => 'நம்பிக்கை பதக்கம் முன்னேற்றம்',
            'home_trust_geo' => 'புவி பொருத்தம்',
            'home_trust_geo_sub' => 'துல்லியமான இட வடிகட்டி',
            'home_trust_alerts' => 'உடனடி அறிவிப்புகள்',
            'home_trust_alerts_sub' => 'உடனடி புதுப்பிப்புகள்',
            'home_stay_safe' => 'பாதுகாப்பாக இருங்கள்',
            'home_safe_handoff_html' => 'பாதுகாப்பான <em>ஒப்படைப்பு</em> வழிகாட்டிகள்',
            'home_safe_sub' => 'நேரடி சந்திப்பில் பாதுகாப்பை முன்னுரிமை செய்யுங்கள்.',
            'home_safe_public' => 'பொது இடத்தில் சந்திக்கவும்',
            'home_safe_public_sub' => 'நூலகம் போன்ற தெரியும் இடங்களை தேர்வு செய்யவும்.',
            'home_safe_verify' => 'முதல் சரிபார்க்கவும்',
            'home_safe_verify_sub' => 'சந்திப்பிற்கு முன் ஒரு மறைக்கப்பட்ட விவரத்தை உறுதிசெய்க.',
            'home_safe_daytime' => 'பகலிலேயே செய்யவும்',
            'home_safe_daytime_sub' => 'பகல் நேரத்தில் ஒப்படைப்பை திட்டமிடுங்கள்.',
            'home_safe_support' => 'ஆதரவை கொண்டு வாருங்கள்',
            'home_safe_support_sub' => 'நம்பகமானவருடன் விவரங்களை பகிரவும்.',
            'home_cta_title' => 'ஏதாவது காணவில்லையா?',
            'home_cta_sub' => 'சில நிமிடங்களில் அறிக்கையை தொடங்குங்கள்.',
            'home_report_lost_item' => 'இழந்த பொருளை அறிக்கை செய்யவும்',
            'home_report_found_item' => 'கண்ட பொருளை அறிக்கை செய்யவும்'
        ]
    ];

    $lang = current_lang();
    return $dict[$lang][$key] ?? ($dict['en'][$key] ?? $key);
}
