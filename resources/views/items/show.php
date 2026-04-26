<?php
/* ─────────────────────────────────────────────
   STEP 1 – Get the poster's phone number
──────────────────────────────────────────────── */
$db   = \App\Core\Database::getInstance()->getConnection();
$stmt = $db->prepare("SELECT phone FROM users WHERE user_id = :uid");
$stmt->execute(['uid' => $item['user_id']]);
$posterPhone = $stmt->fetchColumn();


/* ─────────────────────────────────────────────
   STEP 2 – Build the WhatsApp link
──────────────────────────────────────────────── */
// Prefer the item's own WhatsApp field, fall back to profile phone
$wpNumber  = !empty($item['whatsapp_contact']) ? $item['whatsapp_contact'] : $posterPhone;
$wpPhone   = preg_replace('/[^0-9]/', '', $wpNumber ?? '');
$wpMessage = urlencode('Hello, I am inquiring about your ' . $item['type'] . ' item: ' . $item['title']);


/* ─────────────────────────────────────────────
   STEP 3 – Build social share URLs
──────────────────────────────────────────────── */
$shareUrl  = urlencode(BASE_URL . '/item/show/' . $item['report_id']);
$shareText = urlencode('Check out this ' . $item['type'] . ' item on Lost & Found: ' . $item['title']);


/* ─────────────────────────────────────────────
   STEP 4 – Handy flags used in the HTML
──────────────────────────────────────────────── */
$hasActivity  = !empty($comments);
$isResolved   = ($item['status'] === 'resolved');
$isOwnReport  = isLoggedIn() && ($_SESSION['user_id'] == $item['user_id']);
$messagingOn  = isset($item['allow_platform_message']) && $item['allow_platform_message'] != 0;


/* ─────────────────────────────────────────────
   STEP 5 – Category label to display
──────────────────────────────────────────────── */
// Custom category > database category > fallback
$categoryLabel = !empty($item['custom_category'])
    ? $item['custom_category']
    : ($item['category_name'] ?? 'Uncategorized');


/* ─────────────────────────────────────────────
   STEP 6 – Split comments into parents & replies
──────────────────────────────────────────────── */
$parentComments = [];
$childComments  = [];

foreach ($comments ?? [] as $c) {
    $cid = (int)($c['comment_id'] ?? $c['message_id'] ?? $c['id'] ?? 0);
    if ($cid <= 0) continue;

    $c['__id'] = $cid;

    if (!empty($c['parent_id']) && $c['parent_id'] > 0) {
        $childComments[$c['parent_id']][] = $c;
    } else {
        $parentComments[] = $c;
    }
}


/* ─────────────────────────────────────────────
   STEP 7 – Build the flyer image tag
──────────────────────────────────────────────── */
$flyerImageTag = !empty($item['image_path'])
    ? '<img class="flyer-photo" src="' . BASE_URL . '/uploads/' . htmlspecialchars($item['image_path']) . '" alt="Item photo">'
    : '';
?>

<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>

<link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/item-detail.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="">

<div class="item-detail-container">

    <a href="<?= BASE_URL ?>/home/index" class="item-detail-back-link">&larr; Back to Recent</a>

    <div class="item-detail-card">

        <!-- ═══════════════════
             LEFT COLUMN
        ════════════════════════ -->
        <div class="item-detail-left">

            <!-- Progress Timeline: step 2 activates on comments, step 3 on resolved -->
            <div class="item-timeline">

                <div class="item-timeline-step">
                    <div class="item-timeline-step-circle item-timeline-step-circle--active">1</div>
                    <div class="item-timeline-step-label item-timeline-step-label--active">Reported</div>
                </div>

                <div class="item-timeline-connector <?= $hasActivity ? 'item-timeline-connector--active' : 'item-timeline-connector--inactive' ?>"></div>

                <div class="item-timeline-step">
                    <div class="item-timeline-step-circle <?= $hasActivity ? 'item-timeline-step-circle--active' : 'item-timeline-step-circle--inactive' ?>">2</div>
                    <div class="item-timeline-step-label <?= $hasActivity ? 'item-timeline-step-label--active' : 'item-timeline-step-label--inactive' ?>">Activity</div>
                </div>

                <div class="item-timeline-connector <?= $isResolved ? 'item-timeline-connector--success' : 'item-timeline-connector--inactive' ?>"></div>

                <div class="item-timeline-step">
                    <div class="item-timeline-step-circle <?= $isResolved ? 'item-timeline-step-circle--success' : 'item-timeline-step-circle--inactive' ?>">3</div>
                    <div class="item-timeline-step-label <?= $isResolved ? 'item-timeline-step-label--active' : 'item-timeline-step-label--inactive' ?>">Resolved</div>
                </div>

            </div>


            <!-- Main Gallery Photo -->
            <div class="item-gallery-container" onclick="openLightbox(0)">
                <?php if (empty($images)): ?>
                    <div class="item-gallery-empty">[ NO PHOTO UPLOADED ]</div>
                <?php else: ?>
                    <img id="main-gallery-image"
                         src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($images[0]) ?>"
                         class="item-gallery-main-img" alt="Main Item Image">
                    <?php if (count($images) > 1): ?>
                        <div class="item-gallery-badge">
                            <i class="fas fa-images"></i> +<?= count($images) - 1 ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>


            <!-- Thumbnail Strip (only when 2+ images) -->
            <?php if (!empty($images) && count($images) > 1): ?>
                <div class="gallery-thumbnails">
                    <?php foreach ($images as $i => $img): ?>
                        <img src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($img) ?>"
                             onclick="changeMainImage(<?= $i ?>)"
                             class="gallery-thumb <?= $i === 0 ? 'gallery-thumb--active' : '' ?>"
                             data-index="<?= $i ?>" alt="Thumbnail <?= $i + 1 ?>">
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>


            <!-- Full-screen Lightbox -->
            <?php if (!empty($images)): ?>
                <div id="image-lightbox" class="image-lightbox">
                    <button class="lightbox-close-btn" onclick="closeLightbox(event)">&times;</button>

                    <?php if (count($images) > 1): ?>
                        <button class="lightbox-nav-btn lightbox-nav-btn--prev" onclick="prevLightboxImage(event)">&larr;</button>
                        <button class="lightbox-nav-btn lightbox-nav-btn--next" onclick="nextLightboxImage(event)">&rarr;</button>
                    <?php endif; ?>

                    <img id="lightbox-main-img"
                         src="<?= BASE_URL ?>/uploads/<?= htmlspecialchars($images[0]) ?>"
                         class="lightbox-main-img" alt="Lightbox Image">

                    <?php if (count($images) > 1): ?>
                        <div class="lightbox-counter">
                            <span id="lightbox-counter">1</span> / <?= count($images) ?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>


            <!-- Leaflet Map (only when coordinates exist) -->
            <?php if (!empty($item['latitude']) && !empty($item['longitude'])): ?>
                <div class="item-detail-map-container">
                    <h3 class="item-detail-desc-title">Map Location</h3>
                    <div id="detailMap" class="item-detail-map-element"
                         data-lat="<?= escape($item['latitude']) ?>"
                         data-lng="<?= escape($item['longitude']) ?>"
                         data-type="<?= escape($item['type']) ?>">
                    </div>
                </div>
            <?php endif; ?>

        </div><!-- /.item-detail-left -->


        <!-- ═══════════════════
             RIGHT COLUMN
        ════════════════════════ -->
        <div class="item-detail-right">

            <!-- Status Badges -->
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
            </div>

            <!-- Title & Meta -->
            <h1 class="item-detail-title"><?= escape($item['title']) ?></h1>
            <div class="item-detail-meta">
                Reported by <strong><?= escape($item['username']) ?></strong>
                &middot; <?= formatDate($item['date_posted']) ?><br>
                <i class="fa-solid fa-location-dot"></i>
                <?= escape($item['location']) ?>
                &middot; Category: <?= escape($categoryLabel) ?>
            </div>

            <!-- Description -->
            <div class="item-detail-desc-block">
                <h3 class="item-detail-desc-title">Description</h3>
                <p class="item-detail-desc-text"><?= escape($item['description']) ?></p>
                <button onclick="printFlyer()" class="btn btn-secondary item-detail-flyer-btn">
                    <i class="fas fa-print"></i> Generate QR Flyer
                </button>
            </div>

            <!-- Contact Info -->
            <?php if (!empty($item['contact_info'])): ?>
                <div class="item-detail-contact-box">
                    <strong class="item-detail-contact-title">Provided Contact Info</strong>
                    <?= escape($item['contact_info']) ?>
                </div>
            <?php endif; ?>

            <!-- Action Buttons -->
            <div class="item-detail-actions-stack">
                <div class="item-detail-action-row">

                    <?php if (!empty($wpNumber)): ?>
                        <a href="https://wa.me/<?= $wpPhone ?>?text=<?= $wpMessage ?>"
                           target="_blank" class="btn-whatsapp btn-action-grow">
                            <i class="fab fa-whatsapp"></i> WhatsApp
                        </a>
                    <?php endif; ?>

                    <?php if ($messagingOn): ?>
                        <?php if (isLoggedIn() && !$isOwnReport): ?>
                            <a href="<?= BASE_URL ?>/message/chat/<?= $item['report_id'] ?>"
                               class="btn btn-primary btn-action-grow btn-action-center">
                                <i class="fas fa-comment-dots"></i> Direct Chat
                            </a>
                        <?php elseif ($isOwnReport): ?>
                            <div class="item-detail-own-report btn-action-grow">
                                <p class="item-detail-own-report-text">This is your own report.</p>
                            </div>
                        <?php else: ?>
                            <a href="<?= BASE_URL ?>/auth/login"
                               class="btn btn-secondary btn-action-grow btn-action-center">
                                Log in to chat
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>

                </div>

                <!-- Social Share -->
                <div class="social-share">
                    <span class="social-share-label">Share to Social Media:</span>
                    <a href="https://www.facebook.com/sharer/sharer.php?u=<?= $shareUrl ?>"
                       target="_blank" class="social-share-link social-share-link--facebook">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="https://twitter.com/intent/tweet?url=<?= $shareUrl ?>&text=<?= $shareText ?>"
                       target="_blank" class="social-share-link social-share-link--twitter">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="https://wa.me/?text=<?= $shareText . ' ' . $shareUrl ?>"
                       target="_blank" class="social-share-link social-share-link--whatsapp">
                        <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
            </div>


            <!-- Potential Matches -->
            <?php if (!empty($potentialMatches)): ?>
                <div class="potential-matches">
                    <h4 class="potential-matches-title">
                        <i class="fas fa-magic"></i> Potential Matches
                    </h4>
                    <p class="potential-matches-subtitle">These items in the same category might be related.</p>
                    <ul class="potential-matches-list">
                        <?php foreach ($potentialMatches as $match): ?>
                            <li class="potential-matches-item">
                                <?php if (!empty($match['image_path'])): ?>
                                    <img src="<?= BASE_URL ?>/uploads/<?= escape($match['image_path']) ?>"
                                         class="potential-matches-thumb" alt="Match thumbnail">
                                <?php else: ?>
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


            <!-- Comments Section -->
            <?php if ($messagingOn): ?>
                <div class="comments-section" id="comments-app">
                    <h3 class="comments-title">Messages &amp; Updates</h3>

                    <?php if (empty($parentComments)): ?>
                        <p class="comments-empty">No messages yet. Be the first to reach out.</p>
                    <?php else: ?>
                        <div class="comments-list">
                            <?php foreach ($parentComments as $comment): ?>
                                <?php $cId = (int)($comment['__id'] ?? 0); ?>
                                <div class="comment-card" id="comment-<?= $cId ?>">

                                    <div class="comment-header">
                                        <strong class="comment-author">
                                            <?= escape($comment['username']) ?>
                                            <?php if ($comment['user_id'] == $item['user_id']): ?>
                                                <span class="badge-author">AUTHOR</span>
                                            <?php endif; ?>
                                        </strong>
                                        <span class="comment-date"><?= formatDate($comment['created_at']) ?></span>
                                    </div>

                                    <p class="comment-text">
                                        <?= nl2br(escape($comment['comment_text'] ?? $comment['message'])) ?>
                                    </p>

                                    <?php if (isLoggedIn() && $cId > 0): ?>
                                        <button class="reply-btn" onclick="setReply(<?= $cId ?>)">Reply</button>
                                    <?php endif; ?>

                                    <!-- Nested replies -->
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
                                                        <span class="comment-date"><?= formatDate($child['created_at']) ?></span>
                                                    </div>
                                                    <p class="comment-text">
                                                        <?= nl2br(escape($child['comment_text'] ?? $child['message'])) ?>
                                                    </p>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Post-a-message form (logged-in users only) -->
                    <?php if (isLoggedIn()): ?>
                        <div id="comment-form-container" class="comment-form-box">
                            <form action="<?= BASE_URL ?>/message/store" method="POST">
                                <input type="hidden" name="report_id"        value="<?= $item['report_id'] ?>">
                                <input type="hidden" name="redirect_context" value="item_show">
                                <input type="hidden" id="parent_id" name="parent_id" value="0">

                                <div class="input-group">
                                    <label class="input-label" for="comment_text" id="reply-label">Post a message</label>
                                    <textarea name="comment_text" id="comment_text" rows="3"
                                              class="input-field"
                                              placeholder="Share an update or ask a question about this item..."
                                              required></textarea>
                                </div>

                                <div class="comment-form-actions">
                                    <button type="submit" class="btn btn-secondary">Post Message</button>
                                    <!-- Hidden until the user clicks Reply on a comment -->
                                    <button type="button" id="cancel-reply"
                                            class="reply-btn reply-btn--cancel reply-btn--hidden"
                                            onclick="cancelReply()">
                                        Cancel Reply
                                    </button>
                                </div>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="login-prompt-box">
                            <a href="<?= BASE_URL ?>/auth/login" class="login-prompt-link">Log in</a>
                            to post a message.
                        </div>
                    <?php endif; ?>

                </div>

            <?php else: ?>
                <div class="comments-section">
                    <p class="comments-disabled-notice">
                        The author disabled platform comments for this report.
                    </p>
                </div>
            <?php endif; ?>

        </div><!-- /.item-detail-right -->

    </div><!-- /.item-detail-card -->
</div><!-- /.item-detail-container -->


<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>

/* ─────────────────────────────────────────────
   GALLERY & LIGHTBOX
──────────────────────────────────────────────── */
<?php if (!empty($images)): ?>

// Image URLs built by PHP so JS never needs to know about the uploads folder
const galleryImages = <?= json_encode(array_map(fn($img) => BASE_URL . '/uploads/' . $img, $images)) ?>;
let currentIndex = 0;

// Swap the main preview photo and highlight the matching thumbnail
function changeMainImage(index) {
    currentIndex = index;
    document.getElementById('main-gallery-image').src = galleryImages[index];
    document.querySelectorAll('.gallery-thumb').forEach(t =>
        t.classList.toggle('gallery-thumb--active', parseInt(t.dataset.index) === index)
    );
}

// Open the full-screen lightbox at a given index
function openLightbox(index) {
    currentIndex = index ?? currentIndex;
    refreshLightbox();
    document.getElementById('image-lightbox').classList.add('image-lightbox--open');
    document.body.classList.add('body-no-scroll');
}

// Close the lightbox and re-enable scrolling
function closeLightbox(e) {
    e?.stopPropagation();
    document.getElementById('image-lightbox').classList.remove('image-lightbox--open');
    document.body.classList.remove('body-no-scroll');
}

// Navigate to the previous image (loops around)
function prevLightboxImage(e) {
    e?.stopPropagation();
    if (galleryImages.length <= 1) return;
    currentIndex = currentIndex === 0 ? galleryImages.length - 1 : currentIndex - 1;
    refreshLightbox();
}

// Navigate to the next image (loops around)
function nextLightboxImage(e) {
    e?.stopPropagation();
    if (galleryImages.length <= 1) return;
    currentIndex = currentIndex === galleryImages.length - 1 ? 0 : currentIndex + 1;
    refreshLightbox();
}

// Update the lightbox image, counter, and thumbnail highlight
function refreshLightbox() {
    document.getElementById('lightbox-main-img').src = galleryImages[currentIndex];
    const counter = document.getElementById('lightbox-counter');
    if (counter) counter.textContent = currentIndex + 1;
    changeMainImage(currentIndex);
}

<?php endif; ?>


/* ─────────────────────────────────────────────
   LEAFLET MAP
──────────────────────────────────────────────── */
document.addEventListener('DOMContentLoaded', () => {
    const mapEl = document.getElementById('detailMap');
    if (!mapEl) return; // No coordinates saved — skip

    const lat  = parseFloat(mapEl.dataset.lat);
    const lng  = parseFloat(mapEl.dataset.lng);
    const type = mapEl.dataset.type;

    const map = L.map('detailMap').setView([lat, lng], 15);
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Coloured dot marker — CSS classes handle the lost/found colours
    L.marker([lat, lng], {
        icon: L.divIcon({
            className : 'map-marker-wrapper',
            html      : `<div class="map-marker-dot" data-type="${type}"></div>`,
            iconSize  : [20, 20],
            iconAnchor: [10, 10]
        })
    }).addTo(map);
});


/* ─────────────────────────────────────────────
   REPLY FORM
──────────────────────────────────────────────── */

// Switch the form into reply mode for a specific comment
function setReply(parentId) {
    document.getElementById('parent_id').value        = parentId;
    document.getElementById('reply-label').textContent = 'Replying to message...';
    document.getElementById('cancel-reply').classList.remove('reply-btn--hidden');
    document.getElementById('comment_text').focus();
    document.getElementById('comment-form-container').scrollIntoView({ behavior: 'smooth', block: 'center' });
}

// Reset the form back to normal post mode
function cancelReply() {
    document.getElementById('parent_id').value         = '0';
    document.getElementById('reply-label').textContent = 'Post a message';
    document.getElementById('comment_text').value      = '';
    document.getElementById('cancel-reply').classList.add('reply-btn--hidden');
}


/* ─────────────────────────────────────────────
   PRINT FLYER
──────────────────────────────────────────────── */
function printFlyer() {
    const title   = <?= json_encode(escape($item['title'])) ?>;
    const desc    = <?= json_encode(escape($item['description'])) ?>;
    const type    = <?= json_encode(escape($item['type'])) ?>;
    const contact = <?= json_encode(escape($item['contact_info'] ?? '')) ?>;
    const flyerImg = <?= json_encode($flyerImageTag) ?>;

    // Step 1: Build dynamic parts
    const heading      = type === 'lost' ? 'MISSING' : 'FOUND';
    const accentColor  = type === 'lost' ? '#C0392B' : '#1A6E3C';
    const contactBlock = contact
        ? `<div class="contact-box">&#128222; ${contact}</div>`
        : '';

    const qrUrl = `https://quickchart.io/qr?size=160&text=${encodeURIComponent(window.location.href)}`;

    // Step 2: Write all CSS inline so it works in the popup window
    const css = `
        @import url('https://fonts.googleapis.com/css2?family=Oswald:wght@700&family=Source+Sans+3:wght@400;600&display=swap');

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Source Sans 3', sans-serif;
            background: #fff;
            color: #1a1a1a;
            padding: 40px;
        }

        .flyer {
            max-width: 600px;
            margin: 0 auto;
            border: 3px solid ${accentColor};
            padding: 36px 40px 32px;
            min-height: 95vh;
        }

        .flyer-heading {
            font-family: 'Oswald', sans-serif;
            font-size: 72px;
            font-weight: 700;
            color: ${accentColor};
            text-align: center;
            letter-spacing: 6px;
            border-bottom: 3px solid ${accentColor};
            padding-bottom: 16px;
            margin-bottom: 20px;
        }

        .flyer-img {
            display: block;
            max-width: 100%;
            max-height: 300px;
            margin: 0 auto 20px;
            border-radius: 6px;
            object-fit: cover;
        }

        .flyer-title {
            font-family: 'Oswald', sans-serif;
            font-size: 32px;
            text-align: center;
            margin-bottom: 14px;
        }

        .flyer-desc {
            font-size: 16px;
            line-height: 1.7;
            color: #333;
            text-align: center;
            margin-bottom: 20px;
        }

        .contact-box {
            font-size: 18px;
            font-weight: 600;
            text-align: center;
            color: ${accentColor};
            border: 2px solid ${accentColor};
            border-radius: 6px;
            padding: 10px 16px;
            margin-bottom: 20px;
        }

        .flyer-qr-box {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 2px dashed #ccc;
        }

        .flyer-qr-label {
            font-size: 13px;
            color: #777;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        @media print {
            body { padding: 0; }
            .flyer { border-color: ${accentColor}; }
        }
    `;

    // Step 3: Open a new blank window and write the full HTML into it
    const win = window.open('', '_blank');
    win.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title>Lost &amp; Found Flyer</title>
            <style>${css}</style>
        </head>
        <body>
            <div class="flyer">
                <h1 class="flyer-heading">${heading}</h1>
                ${flyerImg}
                <h2 class="flyer-title">${title}</h2>
                <p class="flyer-desc">${desc}</p>
                ${contactBlock}
                <div class="flyer-qr-box">
                    <p class="flyer-qr-label">Scan to view live details online</p>
                    <img src="${qrUrl}" alt="QR Code" width="160" height="160">
                </div>
            </div>
            <script>window.onload = () => window.print()<\/script>
        </body>
        </html>
    `);
    win.document.close();
}
</script>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>