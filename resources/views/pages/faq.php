<?php require_once ROOT . '/resources/views/layouts/header.php'; ?>

<div style="max-width: 800px; margin: 40px auto; background: var(--off-white); padding: 40px; border-radius: 16px; box-shadow: var(--shadow-card);">
    <h1 style="font-size: 2.5rem; margin-bottom: 30px; color: var(--midnight);">Frequently Asked Questions</h1>
    
    <div style="display: flex; flex-direction: column; gap: 20px;">
        
        <div style="background: var(--white); padding: 20px; border-radius: 12px; border-left: 4px solid var(--terracotta);">
            <h4 style="font-size: 16px; color: var(--midnight); margin-bottom: 8px;">How do I claim a found item?</h4>
            <p style="color: var(--clay); font-size: 14px; line-height: 1.6;">If you recognize an item on the 'Browse' page, log into your account and click the "This Is Mine" button. You can then use the messaging system to coordinate securely with the finder to verify ownership and arrange a meetup.</p>
        </div>

        <div style="background: var(--white); padding: 20px; border-radius: 12px; border-left: 4px solid var(--sage);">
            <h4 style="font-size: 16px; color: var(--midnight); margin-bottom: 8px;">Is there a reward for returning an item?</h4>
            <p style="color: var(--clay); font-size: 14px; line-height: 1.6;">Some users may offer a reward in their item description, however, this is completely optional. Lost & Found relies heavily on the good faith and neighborly actions of our community members.</p>
        </div>

        <div style="background: var(--white); padding: 20px; border-radius: 12px; border-left: 4px solid var(--amber);">
            <h4 style="font-size: 16px; color: var(--midnight); margin-bottom: 8px;">What if someone falsely claims my item?</h4>
            <p style="color: var(--clay); font-size: 14px; line-height: 1.6;">Never hand over an item without verifying ownership first. Ask specific questions about the contents of a wallet, the password to a phone, or distinctive marks not shown in the uploaded photographs.</p>
        </div>

    </div>
</div>

<?php require_once ROOT . '/resources/views/layouts/footer.php'; ?>
