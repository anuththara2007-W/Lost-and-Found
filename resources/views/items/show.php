<?php
/**
 * item-detail.php
 * Shows a single lost/found item report in full detail.
 *
 * RULE: Zero inline styles in this file.
 *       All styling lives in:  assets/css/item-detail.css
 *                              assets/css/flyer-print.css  (print window)
 *
 * HOW THIS FILE WORKS (for beginners):
 * 1. We grab data from the database and prepare variables.
 * 2. We include the shared page header (navigation, <head>, etc.).
 * 3. We output the HTML for the page using those variables.
 * 4. We include the shared page footer.
 */


/* =====================================================================
   STEP 1 – Get the poster's phone number from the database
   ===================================================================== */

// Get a connection to the database (this is a helper class in this project)
$db = \App\Core\Database::getInstance()->getConnection();

// Prepare a safe SQL query — :uid is a placeholder filled in below
$stmt = $db->prepare("SELECT phone FROM users WHERE user_id = :uid");

// Run the query, replacing :uid with the actual user ID of the item poster
$stmt->execute(['uid' => $item['user_id']]);

// Fetch just the phone column value
$posterPhone = $stmt->fetchColumn();


/* =====================================================================
   STEP 2 – Build the WhatsApp contact link
   ===================================================================== */

// Prefer the item's own WhatsApp field; fall back to the poster's profile phone
$wpNumber = !empty($item['whatsapp_contact'])
    ? $item['whatsapp_contact']
    : $posterPhone;

// Strip everything except digits — wa.me URLs need numbers only (e.g. 1234567890)
$wpPhone = preg_replace('/[^0-9]/', '', $wpNumber);

// Pre-encode the message that will be pre-filled in WhatsApp
$wpMessage = urlencode(
    'Hello, I am inquiring about your '
    . $item['type']
    . ' item: '
    . $item['title']
);


/* =====================================================================
   STEP 3 – Build social-share URLs
   ===================================================================== */

// The full URL to this item page, URL-encoded for use inside other URLs
$shareUrl = urlencode(BASE_URL . '/item/show/' . $item['report_id']);

// The text shown when someone shares on Twitter / WhatsApp
$shareText = urlencode(
    'Check out this ' . $item['type'] . ' item on Lost & Found: ' . $item['title']
);


/* =====================================================================
   STEP 4 – Handy true/false flags used in the HTML below
   ===================================================================== */

// TRUE if there is at least one comment on this item
$hasActivity = !empty($comments);

// TRUE if the item has been marked as resolved
$isResolved = ($item['status'] === 'resolved');

// TRUE if the logged-in user is the person who posted this item
$isOwnReport = isLoggedIn() && ($_SESSION['user_id'] == $item['user_id']);

// TRUE if the poster has allowed other users to message them through the platform
$messagingOn = isset($item['allow_platform_message'])
    && $item['allow_platform_message'] != 0;


/* =====================================================================
   STEP 5 – Work out the category label to display
   ===================================================================== */

// Use a custom category name if the poster typed one in; otherwise use the
// standard category name from the database; otherwise fall back to "Uncategorized"
$categoryLabel = !empty($item['custom_category'])
    ? $item['custom_category']
    : ($item['category_name'] ?? 'Uncategorized');


/* =====================================================================
   STEP 6 – Split comments into top-level messages and replies
   ===================================================================== */

$parentComments = []; // top-level comments (no parent)
$childComments  = []; // replies, grouped by the parent comment's ID

if (!empty($comments)) {
    foreach ($comments as $c) {
        // Different queries may use different column names for the comment ID.
        // Try each common name; if none exists, default to 0.
        $cid = (int)($c['comment_id'] ?? $c['message_id'] ?? $c['id'] ?? 0);

        // Skip any rows that have no usable ID
        if ($cid <= 0) {
            continue;
        }

        // Store the resolved ID back on the comment so the HTML can use it
        $c['__id'] = $cid;

        if (!empty($c['parent_id']) && $c['parent_id'] > 0) {
            // This comment is a reply — group it under its parent's ID
            $childComments[$c['parent_id']][] = $c;
        } else {
            // This comment is a top-level message
            $parentComments[] = $c;
        }
    }
}


/* =====================================================================
   STEP 7 – Pre-build the photo tag used inside the printable flyer
   ===================================================================== */

// Start with an empty string; we only fill it in if an image exists
$flyerImageTag = '';

if (!empty($item['image_path'])) {
    // Build a plain <img> tag — the CSS class handles all styling
    $flyerImageTag = '<img class="flyer-photo" src="'
        . BASE_URL . '/uploads/' . htmlspecialchars($item['image_path'])
        . '" alt="Item photo">';
}
?>
<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>

<!-- Stylesheets for this page only -->
<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/item-detail.css">
<link rel="stylesheet"
      href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin="">

<!-- ═══════════════════════════════════════════════════════════
     PAGE WRAPPER
     ═══════════════════════════════════════════════════════════ -->
<div class="item-detail-container">

    <!-- ── Back link ──────────────────────────────────────── -->
    <a href="<?= BASE_URL ?>/home/index" class="item-detail-back-link">
        &larr; Back to Recent
    </a>

    <div class="item-detail-card">

        <!-- ═══════════════════════════════════════════════════
             LEFT COLUMN  —  gallery, timeline, map
             ═══════════════════════════════════════════════════ -->
        <div class="item-detail-left">

            <!-- ── Progress Timeline ──────────────────────────
                 Step 1 is always active.
                 Step 2 activates once there are comments.
                 Step 3 activates when status = 'resolved'.
                 ──────────────────────────────────────────────── -->
            <div class="item-timeline">

                <!-- Step 1: always shown as active -->
                <div class="item-timeline-step">
                    <div class="item-timeline-step-circle item-timeline-step-circle--active">1</div>
                    <div class="item-timeline-step-label item-timeline-step-label--active">Reported</div>
                </div>

                <!-- Connector line between step 1 and 2 -->
                <div class="item-timeline-connector <?= $hasActivity
                    ? 'item-timeline-connector--active'
                    : 'item-timeline-connector--inactive' ?>">
                </div>

                <!-- Step 2: active if there are any comments -->
                <div class="item-timeline-step">
                    <div class="item-timeline-step-circle <?= $hasActivity
                        ? 'item-timeline-step-circle--active'
                        : 'item-timeline-step-circle--inactive' ?>">2</div>
                    <div class="item-timeline-step-label <?= $hasActivity
                        ? 'item-timeline-step-label--active'
                        : 'item-timeline-step-label--inactive' ?>">Activity</div>
                </div>

                <!-- Connector line between step 2 and 3 -->
                <div class="item-timeline-connector <?= $isResolved
                    ? 'item-timeline-connector--success'
                    : 'item-timeline-connector--inactive' ?>">
                </div>

                <!-- Step 3: active (green) when the item is resolved -->
                <div class="item-timeline-step">
                    <div class="item-timeline-step-circle <?= $isResolved
                        ? 'item-timeline-step-circle--success'
                        : 'item-timeline-step-circle--inactive' ?>">3</div>
                    <div class="item-timeline-step-label <?= $isResolved
                        ? 'item-timeline-step-label--active'
                        : 'item-timeline-step-label--inactive' ?>">Resolved</div>
                </div>

            </div><!-- /.item-timeline -->


            <!-- ── Main Gallery Photo ──────────────────────── -->
            <div class="item-gallery-container" onclick="openLightbox(0)">

                <?php if (empty($images)): ?>
                    <!-- No image was uploaded for this item -->
                    <div class="item-gallery-empty">[ NO PHOTO UPLOADED ]</div>

                <?php else: ?>
                    <!-- Show the first image as the main photo -->
                    <img id="main-gallery-image"
                         src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($images[0]) ?>"
                         class="item-gallery-main-img"
                         alt="Main Item Image">

                    <?php if (count($images) > 1): ?>
                        <!-- Small badge showing how many extra photos exist -->
                        <div class="item-gallery-badge">
                            <i class="fas fa-images"></i> +<?= count($images) - 1 ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>

            </div><!-- /.item-gallery-container -->


            <!-- ── Thumbnail Strip (only shown when 2+ images) ── -->
            <?php if (!empty($images) && count($images) > 1): ?>
                <div class="gallery-thumbnails">
                    <?php foreach ($images as $index => $img): ?>
                        <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($img) ?>"
                             onclick="changeMainImage(<?= $index ?>)"
                             class="gallery-thumb <?= $index === 0 ? 'gallery-thumb--active' : '' ?>"
                             data-index="<?= $index ?>"
                             alt="Thumbnail <?= $index + 1 ?>">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>


            <!-- ── Full-screen Lightbox (only when images exist) ──
                 Visibility is controlled by adding/removing
                 the .image-lightbox--open CSS class in JavaScript.
                 ──────────────────────────────────────────────────── -->
            <?php if (!empty($images)): ?>
                <div id="image-lightbox" class="image-lightbox">

                    <!-- Close button (top-right corner) -->
                    <button class="lightbox-close-btn" onclick="closeLightbox(event)">&times;</button>

                    <?php if (count($images) > 1): ?>
                        <!-- Previous / next navigation arrows -->
                        <button class="lightbox-nav-btn lightbox-nav-btn--prev"
                                onclick="prevLightboxImage(event)">&larr;</button>
                        <button class="lightbox-nav-btn lightbox-nav-btn--next"
                                onclick="nextLightboxImage(event)">&rarr;</button>
                    <?php endif; ?>

                    <!-- The large image shown inside the lightbox -->
                    <img id="lightbox-main-img"
                         src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($images[0]) ?>"
                         class="lightbox-main-img"
                         alt="Lightbox Image">

                    <?php if (count($images) > 1): ?>
                        <!-- "1 / 3" style counter -->
                        <div class="lightbox-counter">
                            <span id="lightbox-counter">1</span> / <?= count($images) ?>
                        </div>
                    <?php endif; ?>

                </div><!-- /#image-lightbox -->
            <?php endif; ?>


            <!-- ── Leaflet Map (only when coordinates exist) ──
                 The data-* attributes are read by the JavaScript
                 at the bottom of this file to initialise the map.
                 ──────────────────────────────────────────────── -->
            <?php if (!empty($item['latitude']) && !empty($item['longitude'])): ?>
                <div class="item-detail-map-container">
                    <h3 class="item-detail-desc-title">Map Location</h3>
                    <div id="detailMap"
                         class="item-detail-map-element"
                         data-lat="<?= escape($item['latitude']) ?>"
                         data-lng="<?= escape($item['longitude']) ?>"
                         data-type="<?= escape($item['type']) ?>">
                    </div>
                </div>
            <?php endif; ?>

        </div><!-- /.item-detail-left -->


        <!-- ═══════════════════════════════════════════════════
             RIGHT COLUMN  —  info, actions, comments
             ═══════════════════════════════════════════════════ -->
        <div class="item-detail-right">

            <!-- ── Status Badges ──────────────────────────── -->
            <div class="item-detail-badges">

                <?php if ($item['type'] === 'lost'): ?>
                    <span class="card-badge badge-lost badge-inline badge-inline-lost">
                        <span class="badge-dot"></span> Lost
                    </span>
                <?php else: ?>
                    <span class="card-badge badge-found badge-inline badge-inline-found">
                        <span class="badge-dot"></span> Found
                    </span>
                <?php endif; ?>

                <?php if ($isResolved): ?>
                    <span class="badge-resolved">Resolved</span>
                <?php endif; ?>

                <?php if (!empty($item['reward_amount']) && $item['reward_amount'] > 0): ?>
                    <span class="card-badge badge-inline badge-reward">
                        <span class="badge-dot"></span>
                        Reward: $<?= number_format($item['reward_amount'], 2) ?>
                    </span>
                <?php endif; ?>

            </div><!-- /.item-detail-badges -->


            <!-- ── Title ──────────────────────────────────── -->
            <h1 class="item-detail-title"><?= escape($item['title']) ?></h1>


            <!-- ── Meta row (who posted, when, where) ──────── -->
            <div class="item-detail-meta">
                Reported by <strong><?= escape($item['username']) ?></strong>
                &middot; <?= formatDate($item['date_posted']) ?>
                <br>
                <i class="fa-solid fa-location-dot"></i>
                <?= escape($item['location']) ?>
                &middot; Category: <?= escape($categoryLabel) ?>
            </div>


            <!-- ── Description ────────────────────────────── -->
            <div class="item-detail-desc-block">
                <h3 class="item-detail-desc-title">Description</h3>
                <p class="item-detail-desc-text"><?= escape($item['description']) ?></p>

                <!-- Button that opens a printable flyer with a QR code -->
                <button onclick="printFlyer()" class="btn btn-secondary item-detail-flyer-btn">
                    <i class="fas fa-print"></i> Generate QR Flyer
                </button>
            </div>


            <!-- ── Contact info (only shown if the poster supplied it) ── -->
            <?php if (!empty($item['contact_info'])): ?>
                <div class="item-detail-contact-box">
                    <strong class="item-detail-contact-title">Provided Contact Info</strong>
                    <?= escape($item['contact_info']) ?>
                </div>
            <?php endif; ?>


            <!-- ── Action Buttons ─────────────────────────── -->
            <div class="item-detail-actions-stack">
                <div class="item-detail-action-row">

                    <!-- WhatsApp button — only shown if we have a phone number -->
                    <?php if (!empty($wpNumber)): ?>
                        <a href="https://wa.me/<?= $wpPhone ?>?text=<?= $wpMessage ?>"
                           target="_blank"
                           class="btn-whatsapp btn-action-grow">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                    <?php endif; ?>

                    <!-- Direct Chat / login prompt — only if messaging is enabled -->
                    <?php if ($messagingOn): ?>

                        <?php if (isLoggedIn() && !$isOwnReport): ?>
                            <!-- A logged-in visitor who does NOT own this report -->
                            <a href="<?= BASE_URL ?>/message/chat/<?= $item['report_id'] ?>"
                               class="btn btn-primary btn-action-grow btn-action-center">
                                <i class="fas fa-comment-dots"></i> Direct Chat
                            </a>

                        <?php elseif ($isOwnReport): ?>
                            <!-- The person viewing is the one who posted this item -->
                            <div class="item-detail-own-report btn-action-grow">
                                <p class="item-detail-own-report-text">This is your own report.</p>
                            </div>

                        <?php else: ?>
                            <!-- Visitor is not logged in -->
                            <a href="<?= BASE_URL ?>/auth/login"
                               class="btn btn-secondary btn-action-grow btn-action-center">
                                Log in to chat
                            </a>
                        <?php endif; ?>

                    <?php endif; ?>

                </div><!-- /.item-detail-action-row -->


                <!-- ── Social Share Icons ──────────────────── -->
                <div class="social-share">
                    <span class="social-share-label">Share to Social Media:</span>

                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $shareUrl ?>"
                       target="_blank"
                       class="social-share-link social-share-link--facebook">
                        <i class="fab fa-facebook"></i>
                    </a>

                    <a href="https://twitter.com/intent/tweet?url=<?= $shareUrl ?>&text=<?= $shareText ?>"
                       target="_blank"
                       class="social-share-link social-share-link--twitter">
                        <i class="fab fa-twitter"></i>
                    </a>

                    <a href="https://wa.me/?text=<?= $shareText . ' ' . $shareUrl ?>"
                       target="_blank"
                       class="social-share-link social-share-link--whatsapp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>

            </div><!-- /.item-detail-actions-stack -->


            <!-- ── Potential Matches ──────────────────────── -->
            <?php if (!empty($potentialMatches)): ?>
                <div class="potential-matches">
                    <h4 class="potential-matches-title">
                        <i class="fas fa-magic"></i> Potential Matches
                    </h4>
                    <p class="potential-matches-subtitle">
                        These items in the same category might be related.
                    </p>

                    <ul class="potential-matches-list">
                        <?php foreach ($potentialMatches as $match): ?>
                            <li class="potential-matches-item">

                                <?php if (!empty($match['image_path'])): ?>
                                    <img src="<?= BASE_URL ?>/uploads/<?= escape($match['image_path']) ?>"
                                         class="potential-matches-thumb"
                                         alt="Match thumbnail">
                                <?php else: ?>
                                    <!-- Placeholder icon when no image exists -->
                                    <div class="potential-matches-thumb-empty">
                                        <i class="fas fa-box"></i>
                                    </div>
                                <?php endif; ?>

                                <div class="potential-matches-info">
                                    <a href="<?= BASE_URL ?>/item/show/<?= $match['report_id'] ?>"
                                       class="potential-matches-link">
                                        <?= escape($match['title']) ?>
                                    </a>
                                    <div class="potential-matches-location">
                                        <i class="fa-solid fa-location-dot"></i>
                                        <?= escape(substr($match['location'], 0, 25)) ?>...
                                    </div>
                                </div>

                            </li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>


            <!-- ── Comments / Messages ────────────────────── -->
            <?php if ($messagingOn): ?>

                <div class="comments-section" id="comments-app">
                    <h3 class="comments-title">Messages &amp; Updates</h3>

                    <?php if (empty($parentComments)): ?>
                        <p class="comments-empty">No messages yet. Be the first to reach out.</p>

                    <?php else: ?>
                        <div class="comments-list">
                            <?php foreach ($parentComments as $comment): ?>
                                <?php
                                // Get the ID we stored in STEP 6
                                $cId = (int)($comment['__id'] ?? 0);
                                ?>
                                <div class="comment-card" id="comment-<?= $cId ?>">

                                    <div class="comment-header">
                                        <strong class="comment-author">
                                            <?= escape($comment['username']) ?>
                                            <?php if ($comment['user_id'] == $item['user_id']): ?>
                                                <!-- Mark the item poster's own comments -->
                                                <span class="badge-author">AUTHOR</span>
                                            <?php endif; ?>
                                        </strong>
                                        <span class="comment-date">
                                            <?= formatDate($comment['created_at']) ?>
                                        </span>
                                    </div>

                                    <p class="comment-text">
                                        <?= nl2br(escape($comment['comment_text'] ?? $comment['message'])) ?>
                                    </p>

                                    <!-- Reply button — only visible to logged-in users -->
                                    <?php if (isLoggedIn() && $cId > 0): ?>
                                        <button class="reply-btn"
                                                onclick="setReply(<?= $cId ?>)">Reply</button>
                                    <?php endif; ?>

                                    <!-- Nested replies to this comment -->
                                    <?php if (!empty($childComments[$cId])): ?>
                                        <div class="child-comments">
                                            <?php foreach ($childComments[$cId] as $child): ?>
                                                <div class="comment-card comment-card--child">

                                                    <div class="comment-header">
                                                        <strong class="comment-author">
                                                            <?= escape($child['username']) ?>
                                                            <?php if ($child['user_id'] == $item['user_id']): ?>
                                                                <span class="badge-author">AUTHOR</span>
                                                            <?php endif; ?>
                                                        </strong>
                                                        <span class="comment-date">
                                                            <?= formatDate($child['created_at']) ?>
                                                        </span>
                                                    </div>

                                                    <p class="comment-text">
                                                        <?= nl2br(escape($child['comment_text'] ?? $child['message'])) ?>
                                                    </p>

                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                </div><!-- /.comment-card -->
                            <?php endforeach; ?>
                        </div><!-- /.comments-list -->
                    <?php endif; ?>


                    <!-- Post-a-message form (only for logged-in users) -->
                    <?php if (isLoggedIn()): ?>
                        <div id="comment-form-container" class="comment-form-box">
                            <form action="<?= BASE_URL ?>/message/store" method="POST">

                                <!-- Hidden fields pass extra data to the server -->
                                <input type="hidden" name="report_id"        value="<?= $item['report_id'] ?>">
                                <input type="hidden" name="redirect_context" value="item_show">
                                <input type="hidden" id="parent_id" name="parent_id" value="0">

                                <div class="input-group">
                                    <label class="input-label" for="comment_text" id="reply-label">
                                        Post a message
                                    </label>
                                    <textarea name="comment_text"
                                              id="comment_text"
                                              rows="3"
                                              class="input-field"
                                              placeholder="Share an update or ask a question about this item..."
                                              required></textarea>
                                </div>

                                <div class="comment-form-actions">
                                    <button type="submit" class="btn btn-secondary">Post Message</button>

                                    <!--
                                        Cancel Reply button.
                                        It starts hidden via the .reply-btn--hidden CSS class.
                                        JavaScript removes that class to show it — no inline style needed.
                                    -->
                                    <button type="button"
                                            id="cancel-reply"
                                            class="reply-btn reply-btn--cancel reply-btn--hidden"
                                            onclick="cancelReply()">
                                        Cancel Reply
                                    </button>
                                </div>

                            </form>
                        </div>

                    <?php else: ?>
                        <!-- Visitor is not logged in — show a login prompt instead -->
                        <div class="login-prompt-box">
                            <a href="<?= BASE_URL ?>/auth/login" class="login-prompt-link">Log in</a>
                            to post a message.
                        </div>
                    <?php endif; ?>

                </div><!-- /.comments-section -->

            <?php else: ?>
                <!-- The poster has disabled platform messaging for this report -->
                <div class="comments-section">
                    <p class="comments-disabled-notice">
                        The author disabled platform comments for this report.
                    </p>
                </div>
            <?php endif; ?>

        </div><!-- /.item-detail-right -->

    </div><!-- /.item-detail-card -->

</div><!-- /.item-detail-container -->


<!-- ═══════════════════════════════════════════════════════════════
     SCRIPTS
     ═══════════════════════════════════════════════════════════════ -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>

<script>
/* =================================================================
   GALLERY & LIGHTBOX
   ================================================================= */

<?php if (!empty($images)): ?>
// PHP builds the array of image URLs so JavaScript never needs to
// hard-code any paths or know about the uploads folder.
const galleryImages = <?= json_encode(
    array_map(fn($img) => BASE_URL . '/uploads/' . $img, $images)
) ?>;

// Keep track of which image is currently visible
let currentIndex = 0;

/**
 * changeMainImage(index)
 * Swaps the large preview photo and highlights the matching thumbnail.
 */
function changeMainImage(index) {
    currentIndex = index;
    document.getElementById('main-gallery-image').src = galleryImages[index];

    // Update the "active" highlight on all thumbnails
    document.querySelectorAll('.gallery-thumb').forEach(function (thumb) {
        const isActive = parseInt(thumb.dataset.index) === index;
        thumb.classList.toggle('gallery-thumb--active', isActive);
    });
}

/**
 * openLightbox(index)
 * Shows the full-screen lightbox at the given image index.
 */
function openLightbox(index) {
    // Use the provided index, or keep the current one
    currentIndex = (index !== undefined) ? index : currentIndex;

    document.getElementById('lightbox-main-img').src = galleryImages[currentIndex];

    // Update the "1 / 3" counter if it exists
    const counter = document.getElementById('lightbox-counter');
    if (counter) counter.textContent = currentIndex + 1;

    // Show the lightbox and prevent the page from scrolling underneath it
    // — both done by toggling CSS classes, no inline styles
    document.getElementById('image-lightbox').classList.add('image-lightbox--open');
    document.body.classList.add('body-no-scroll');
}

/**
 * closeLightbox(e)
 * Hides the lightbox and re-enables page scrolling.
 */
function closeLightbox(e) {
    if (e) e.stopPropagation(); // don't trigger the gallery click-to-open
    document.getElementById('image-lightbox').classList.remove('image-lightbox--open');
    document.body.classList.remove('body-no-scroll');
}

/**
 * prevLightboxImage(e)
 * Moves to the previous image, looping from the first back to the last.
 */
function prevLightboxImage(e) {
    if (e) e.stopPropagation();
    if (galleryImages.length <= 1) return; // nothing to navigate

    // If we're at the first image, jump to the last
    currentIndex = (currentIndex === 0)
        ? galleryImages.length - 1
        : currentIndex - 1;

    refreshLightbox();
}

/**
 * nextLightboxImage(e)
 * Moves to the next image, looping from the last back to the first.
 */
function nextLightboxImage(e) {
    if (e) e.stopPropagation();
    if (galleryImages.length <= 1) return;

    // If we're at the last image, jump to the first
    currentIndex = (currentIndex === galleryImages.length - 1)
        ? 0
        : currentIndex + 1;

    refreshLightbox();
}

/**
 * refreshLightbox()
 * Updates the lightbox image, counter, and the background thumbnail strip.
 */
function refreshLightbox() {
    document.getElementById('lightbox-main-img').src = galleryImages[currentIndex];

    const counter = document.getElementById('lightbox-counter');
    if (counter) counter.textContent = currentIndex + 1;

    // Also sync the thumbnail strip highlight
    changeMainImage(currentIndex);
}
<?php endif; ?>


/* =================================================================
   LEAFLET MAP
   ================================================================= */
document.addEventListener('DOMContentLoaded', function () {
    // Look for the map element — it only exists when coordinates were saved
    var mapEl = document.getElementById('detailMap');
    if (!mapEl) return; // no coordinates → nothing to do

    // Read the coordinates and item type from the data-* attributes set by PHP
    var lat  = parseFloat(mapEl.dataset.lat);
    var lng  = parseFloat(mapEl.dataset.lng);
    var type = mapEl.dataset.type; // 'lost' or 'found'

    // Create the Leaflet map centred on those coordinates
    var map = L.map('detailMap').setView([lat, lng], 15);

    // Load map tiles from OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    /*
     * Custom marker: a <div class="map-marker-dot" data-type="lost|found">
     * The CSS file colours it:
     *   .map-marker-dot[data-type="lost"]  { background: #C96442; }
     *   .map-marker-dot[data-type="found"] { background: #5C7A65; }
     * No inline colour here — all in the CSS file.
     */
    L.marker([lat, lng], {
        icon: L.divIcon({
            className : 'map-marker-wrapper',
            html      : '<div class="map-marker-dot" data-type="' + type + '"></div>',
            iconSize  : [20, 20],
            iconAnchor: [10, 10]
        })
    }).addTo(map);
});


/* =================================================================
   REPLY FORM
   ================================================================= */

/**
 * setReply(parentId)
 * Switches the message form into "reply" mode for a specific comment.
 */
function setReply(parentId) {
    // Tell the server which comment this reply belongs to
    document.getElementById('parent_id').value = parentId;

    // Update the label so the user knows they're replying
    document.getElementById('reply-label').textContent = 'Replying to message...';

    // Show the Cancel button by removing its hidden CSS class
    document.getElementById('cancel-reply').classList.remove('reply-btn--hidden');

    // Move focus to the textarea and scroll it into view
    document.getElementById('comment_text').focus();
    document.getElementById('comment-form-container')
        .scrollIntoView({ behavior: 'smooth', block: 'center' });
}

/**
 * cancelReply()
 * Resets the form back to normal "post a message" mode.
 */
function cancelReply() {
    document.getElementById('parent_id').value         = '0';
    document.getElementById('reply-label').textContent = 'Post a message';
    document.getElementById('comment_text').value      = '';

    // Hide the Cancel button by adding its hidden CSS class back
    document.getElementById('cancel-reply').classList.add('reply-btn--hidden');
}


/* =================================================================
   PRINT FLYER
   ================================================================= */

/**
 * printFlyer()
 * Opens a new browser window with a styled, printable flyer
 * that includes a QR code linking back to this page.
 *
 * All values come from PHP variables.
 * All styling comes from flyer-print.css — no inline styles here.
 */
function printFlyer() {
    // Data values from PHP
    var title    = <?= json_encode(escape($item['title'])) ?>;
    var desc     = <?= json_encode(escape($item['description'])) ?>;
    var type     = <?= json_encode(escape($item['type'])) ?>;
    var contact  = <?= json_encode(escape($item['contact_info'] ?? '')) ?>;
    var flyerImg = <?= json_encode($flyerImageTag) ?>;
    var cssUrl   = <?= json_encode(BASE_URL . '/assets/css/flyer-print.css') ?>;

    // Build the QR code URL using the free QuickChart service
    var qrUrl = 'https://quickchart.io/qr?size=150&text='
              + encodeURIComponent(window.location.href);

    // The flyer heading depends on whether the item is lost or found
    var heading = (type === 'lost') ? 'MISSING' : 'FOUND';

    // Only show a contact line if the poster provided one
    var contactBlock = contact
        ? '<p class="flyer-contact">Contact: ' + contact + '</p>'
        : '';

    // Open a new browser window and write a complete HTML page into it.
    // The <body> class (flyer-type-lost / flyer-type-found) lets CSS
    // colour the flyer differently depending on the item type.
    var win = window.open('', '_blank');
    win.document.write(
        '<!DOCTYPE html><html><head>'
        + '<title>Lost &amp; Found Flyer</title>'
        + '<link rel="stylesheet" href="' + cssUrl + '">'
        + '</head>'
        + '<body class="flyer-type-' + type + '">'
        + '<h1 class="flyer-heading">'  + heading + '</h1>'
        + flyerImg
        + '<h2 class="flyer-title">'   + title   + '</h2>'
        + '<p class="flyer-desc">'     + desc    + '</p>'
        + contactBlock
        + '<div class="flyer-spacer"></div>'
        + '<div class="flyer-qr-box">'
        +   '<p class="flyer-qr-label">Scan to view live details online</p>'
        +   '<img src="' + qrUrl + '" class="flyer-qr-img" alt="QR Code" width="150" height="150">'
        + '</div>'
        // Auto-trigger the browser's print dialog once the page loads
        + '<script>window.onload = function(){ window.print(); }<\/script>'
        + '</body></html>'
    );
    win.document.close();
}
</script>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>